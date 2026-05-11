<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use Crypt;
use DB;
use PDF;
use App\Helpers\DocumentoHelper;
use Carbon\Carbon;
use App\Models\Bodega;
use App\Models\Empresa;
use App\Models\InvFamilia;
use App\Models\InvSaldo;
use App\Models\Producto;

class ReporteController extends Controller{
	public function __construct(){
		$this->middleware('auth');
	}

	//====================================================================================
	// Admisiones 
	//====================================================================================
	public function adm_unificado_idx($fecha_inicial, $fecha_final, $tipo_admision, $saldo, $estado){
    $tipo_admisiones = DB::table('empresa_tipo_atenciones as eta')
                           ->join('tipo_atenciones as ta', 'eta.tipo_atencion_id', 'ta.id')
                           ->where('eta.empresa_id', Auth::user()->empresa_id)
                           ->where('eta.estado', 1)
                           ->where('ta.estado', 1)
                           ->select('ta.id', 'ta.nombre')
                           ->get();

		$registros = DB::table('admisiones as a')
		             ->join('pacientes as p', 'a.paciente_id', 'p.id')
		             // ->join('tipo_atenciones as ta', 'a.tipo_admision', 'ta.id')
		             ->join('hospitales as h', 'a.hospital_id', 'h.id')
		             ->join('users as u', 'a.created_by', 'u.id')
		             ->leftjoin('detalle_movimientos as dm', 'a.id', 'dm.admision_id')
		             ->whereDate('a.fecha', '>=', $fecha_inicial)
		             ->whereDate('a.fecha', '<=', $fecha_final)
		             ->when($estado != 'T', function($query) use ($estado) {
                    					return $query->where('a.estado', '=', $estado);
            					 })
		             ->groupBy('a.id', 'a.admision_no', 'a.created_by', 'p.nombre_completo', 'p.expediente_no', 'a.fecha', 'h.nombre')
		             ->select('a.id', 'a.admision_no', 'a.created_by as username', 'p.nombre_completo', 'p.expediente_no', 'a.fecha', 'h.nombre as hospital_nombre', DB::raw('("x") as procedimiento_nombre'),  DB::raw('SUM(dm.precio_total) as total_cargos'),
			                 DB::raw('CASE WHEN a.estado = "P" THEN "Proceso" WHEN a.estado =  "C" THEN "Cerrada" ELSE "Inactiva" END AS estado'))
		             ->get();
		
		foreach ($registros as $key => $registro) {
			$facturas = DB::table('documentoventa_maestros as dvm')
			            ->join('documentoventa_detalles as dvd', 'dvm.id', 'dvd.documentoventa_maestro_id')
			            ->join('detalle_movimientos as dm', 'dvd.detalle_movimiento_id', 'dm.id')
			            ->where('dm.admision_id', $registro->id)
			            ->groupBy('dvm.id', 'dvm.tipodocumento_id', 'dvm.serie', 'dvm.correlativo')
			            ->select('dvm.id', 'dvm.tipodocumento_id', 'dvm.serie', 'dvm.correlativo', DB::raw('(CONCAT(dvm.serie," - ",dvm.correlativo)) as documento'),
			                     DB::raw('(SUM(dvd.precio_neto)) as total_facturado'))
			            ->get();
			

			$documentos = $facturas->pluck('documento');
			$registro->facturas = $documentos->implode(', ');
			$total_facturado = 0;
			$total_pagado    = 0;
			foreach ($facturas as $key => $factura) {
				$total_facturado += $factura->total_facturado;
				$total_pagado += DocumentoHelper::DocumentoTotalPagado($factura->tipodocumento_id, $factura->serie, $factura->correlativo);
			}
			$registro->total_facturado = $total_facturado;
			$registro->total_pagado    = $total_pagado;
			$registro->saldo = $total_facturado - $total_pagado;
		}

		if ($saldo === 'S') {
    		$registros = $registros->filter(function ($registro) {
    			return $registro->saldo != 0;
    		});
		}

		return view('reportes.rpt_adm_unificado_idx', compact('tipo_admisiones', 'registros','fecha_inicial', 'fecha_final', 'tipo_admision', 'saldo'));
	}

  private function desencriptarParametro($valor)
  {
      // 1. Si el valor es "0", 0, nulo o cadena vacía, retornamos 0 para indicar "Todos"
      if (empty($valor) || $valor === "0" || $valor === 0) {
          return 0;
      }

      try {
          // 2. Intentamos abrir el candado de seguridad
          return Crypt::decrypt($valor);
      } catch (DecryptException $e) {
          // 3. Si el token es inválido o fue alterado, devolvemos 0 para evitar que el sistema truene
          return 0;
      }
  }

	//====================================================================================
	// Inventarios
	//====================================================================================
	public function disponibilidad_articulos_idx(Request $request) {
      // 1. Cargar catálogos siempre (para los selects)
      $bodegas = Bodega::all()->map(function($b) {
          $b->id_encriptado = encrypt($b->id);
          return $b;
      });

      $familias = InvFamilia::all()->map(function($f) {
          $f->id_encriptado = encrypt($f->id);
          return $f;
      });

      $productos = Producto::all()->map(function($p) {
          $p->id_encriptado = encrypt($p->id);
          return $p;
      });

      $bodegaIdEncriptado   = $request->input('bodega_id');
      $familiaIdEncriptado  = $request->input('familia_id');
      $productoIdEncriptado = $request->input('producto_id');

      $bodegaId   = null;
      $familiaId  = null;
      $productoId = null;
      $existencias = collect(); // Inicializar siempre vacía

      // 2. Solo intentar desencriptar y buscar si hay parámetros presentes
      if (($bodegaIdEncriptado !== null && $bodegaIdEncriptado !== "") || ($productoIdEncriptado !== null && $productoIdEncriptado !== "") || ($familiaIdEncriptado !== null && $familiaIdEncriptado !== "")) {
          try {
              // Lógica de bodega
              if ($bodegaIdEncriptado !== "0" && $bodegaIdEncriptado !== 0 && !empty($bodegaIdEncriptado)) {
                  $bodegaId = Crypt::decrypt($bodegaIdEncriptado);
              } else {
                  $bodegaId = 0;
              }

              // Lógica de familia
              if ($familiaIdEncriptado !== "0" && $familiaIdEncriptado !== 0 && !empty($familiaIdEncriptado)) {
                  $familiaId = Crypt::decrypt($familiaIdEncriptado);
              } else {
                  $familiaId = 0;
              }

              // Lógica de producto
              if ($productoIdEncriptado !== "0" && $productoIdEncriptado !== 0 && !empty($productoIdEncriptado)) {
                  $productoId = Crypt::decrypt($productoIdEncriptado);
              } else {
                  $productoId = 0;
              }

              // Realizar la consulta
              $existencias = Producto::select(
                  'productos.id as producto_id',
                  'productos.descripcion as producto_nombre',
                  'productos.inv_familia_id',
                  'productos.medida_id',
                  DB::raw('IFNULL(SUM(inv_saldos.stock_actual), 0) as stock_total')
              )
              ->leftJoin('inv_saldos', function($join) use ($bodegaId, $familiaId) {
                  $join->on('productos.id', '=', 'inv_saldos.producto_id');
                  // El filtro de bodega debe ir dentro del Join para no excluir productos sin stock
                  if ($bodegaId != 0) {
                      $join->where('inv_saldos.bodega_id', '=', $bodegaId);
                  }
                  if ($familiaId != 0) {
                      $join->where('productos.inv_familia_id', '=', $familiaId);
                  }
              })
              ->with(['unidadMedida', 'familia']) // Carga la relación definida en tu modelo Producto
              ->when($familiaId != 0, function ($q) use ($familiaId) {
                  return $q->where('productos.inv_familia_id', $familiaId);
              })
              ->when($productoId != 0, function ($q) use ($productoId) {
                  return $q->where('productos.id', $productoId);
              })
              ->where('productos.empresa_id', Auth::user()->empresa_id) // Filtro de seguridad por empresa
              ->groupBy('productos.id', 'productos.descripcion', 'productos.inv_familia_id', 'productos.medida_id')
              ->get();

              // Si es AJAX (DataTables), responder JSON
              if ($request->ajax()) {
                  return response()->json($existencias->map(function($item) {
                      return [
                          'familia_descripcion'  => $item->familia->nombre ?? 'N/A',
                          'articulo_descripcion' => $item->descripcion ?? 'N/A',
                          'unidad_medida'        => $item->unidadMedida->descripcion ?? 'N/A',
                          'total'                => number_format($item->stock_total, 2)
                      ];
                  }));
              }

          } catch (DecryptException $e) {
              return back()->withErrors(['error' => 'Los parámetros de seguridad han sido alterados.']);
          }
      }

      // 3. Este return debe estar al FINAL, fuera de cualquier condición
      return view('reportes.rpt_disponible_idx', compact('existencias', 'bodegaId', 'familiaId', 'productoId', 'bodegas', 'familias', 'productos'));
  }

  public function rpt_kardex_articulos(Request $request){
    $bodegas = Bodega::all()->map(function($b) {
        $b->id_encriptado = encrypt($b->id);
        return $b;
    });

    $familias = InvFamilia::all()->map(function($f) {
        $f->id_encriptado = encrypt($f->id);
        return $f;
    });

    $productos = Producto::all()->map(function($p) {
        $p->id_encriptado = encrypt($p->id);
        return $p;
    });

    $bodegaIdEncriptado   = $request->input('bodega_id');
    $familiaIdEncriptado  = $request->input('familia_id');
    $productoIdEncriptado = $request->input('producto_id');

    $bodegaId   = null;
    $familiaId  = null;
    $productoId = null;

    // 2. Desencriptar Parámetros de Filtro
    $bodegaId    = $this->desencriptarParametro($request->input('bodega_id'));
    $familiaId   = $this->desencriptarParametro($request->input('familia_id'));
    $productoId  = $this->desencriptarParametro($request->input('producto_id'));
    $fechaInicio = $request->input('fecha_inicio', date('Y-m-01')); // Por defecto inicio de mes
    $fechaFinal  = $request->input('fecha_final', date('Y-m-01')); // Por defecto inicio de mes

    $saldoInicial     = 0;
    $movimientos      = [];
    $saldoRealEnTabla = 0;
    $hayDescuadre     = false;

    if (($bodegaIdEncriptado !== null && $bodegaIdEncriptado !== "") || ($productoIdEncriptado !== null && $productoIdEncriptado !== "") || ($familiaIdEncriptado !== null && $familiaIdEncriptado !== "")) {
      try{
        $saldosIniciales = DB::table('detalle_movimientos as d')
                            ->join('maestro_movimientos as m', 'm.id', '=', 'd.maestro_movimiento_id')
                            ->select('d.producto_id', 
                                DB::raw("SUM(CASE 
                                    WHEN $bodegaId = 0 THEN (d.cantidad_x_medida * d.signo)
                                    WHEN m.bodega_origen_id = $bodegaId THEN (d.cantidad_x_medida * d.signo)
                                    WHEN m.bodega_destino_id = $bodegaId THEN (d.cantidad_x_medida * d.signo * -1) 
                                    ELSE 0 
                                END) as saldo_previo")
                            )
                            ->where('m.estado', 1)
                            ->where('d.estado', 1)
                            ->where(DB::raw("COALESCE(m.fecha_emision, m.created_at)"), '<', $fechaInicio)
                            ->when($bodegaId > 0, function($q) use ($bodegaId) {
                                $q->where(fn($query) => $query->where('m.bodega_origen_id', $bodegaId)->orWhere('m.bodega_destino_id', $bodegaId));
                            })
                            ->when($productoId > 0, function($p) use ($productoId) {
                              $p->where(fn($query) => $query->where('d.producto_id', $productoId));
                            })
                            ->groupBy('d.producto_id')
                            ->pluck('saldo_previo', 'd.producto_id'); // Retorna array [id => saldo]

        $detalles = DB::table('productos as p')
                    ->join('inv_familias as f', 'p.inv_familia_id', '=', 'f.id')
                    ->leftJoin('detalle_movimientos as d', 'p.id', '=', 'd.producto_id')
                    ->leftJoin('maestro_movimientos as m', 'm.id', '=', 'd.maestro_movimiento_id')
                    ->leftjoin('inventario_transacciones as it', 'm.inventario_transaccion_id', 'it.id')
                    ->select(
                        'f.nombre as familia',
                        'p.id as producto_id',
                        'p.descripcion as producto_nombre',
                        'm.fecha_emision',
                        'it.descripcion as transaccion_descripcion',
                        'm.correlativo',
                        'm.anio',
                        'm.bodega_origen_id',
                        'm.bodega_destino_id',
                        'd.cantidad_x_medida',
                        'd.signo',
                        'd.estado as detalle_estado'
                    )
                    ->where(function($q) use ($fechaInicio, $fechaFinal) {
                        $q->whereBetween(DB::raw("COALESCE(m.fecha_emision, m.created_at)"), [$fechaInicio, $fechaFinal])
                          ->orWhereNull('m.id'); // Para mostrar productos aunque no tengan movimientos
                    })
                    ->where(function($q) use ($bodegaId) {
                        if ($bodegaId > 0) {
                            $q->where('m.bodega_origen_id', $bodegaId)
                              ->orWhere('m.bodega_destino_id', $bodegaId)
                              ->orWhereNull('m.id');
                        }
                    })
                    ->when($familiaId > 0, function($f) use ($familiaId) {
                              $f->where(fn($query) => $query->where('p.inv_familia_id', $familiaId));
                            })
                    ->when($productoId > 0, function($p) use ($productoId) {
                              $p->where(fn($query) => $query->where('d.producto_id', $productoId));
                            })
                    ->where(fn($q) => $q->where('d.estado', 1)->orWhereNull('d.id'))
                    ->orderBy('f.nombre')
                    ->orderBy('p.descripcion')
                    ->orderBy('m.fecha_emision')
                    ->orderBy('m.created_at')
                    ->get();

        $kardexFinal = [];
        $acumulado = 0;
        $ultimoProducto = null;

        foreach ($detalles as $item) {
            // Si cambiamos de producto, reiniciamos el acumulado con el saldo inicial histórico
            if ($ultimoProducto !== $item->producto_id) {
                $acumulado = $saldosIniciales[$item->producto_id] ?? 0;
                $ultimoProducto = $item->producto_id;
            }

            $esEntrada = false;
            $esSalida = false;
            $cantidad = $item->cantidad_x_medida ?? 0;

            // Lógica para definir Entrada/Salida según la bodega consultada
            if ($item->producto_id && $item->fecha_emision) {
                if ($bodegaId > 0) {
                    // Si es destino, es entrada. Si es origen y signo negativo o tiene destino, es salida.
                    if ($item->bodega_destino_id == $bodegaId) {
                        $esEntrada = true;
                    } else {
                        ($item->signo > 0) ? $esEntrada = true : $esSalida = true;
                    }
                } else {
                    // Global: Solo afectan movimientos que entran o salen de la empresa (destino NULL)
                    if ($item->bodega_destino_id == null) {
                        ($item->signo > 0) ? $esEntrada = true : $esSalida = true;
                    }
                }
            }

            $saldoAnterior = $acumulado;
            if ($esEntrada) $acumulado += $cantidad;
            if ($esSalida) $acumulado -= $cantidad;

            $kardexFinal[] = [
                'familia' => $item->familia,
                'producto' => $item->producto_nombre,
                'fecha' => $item->fecha_emision,
                'transaccion_descripcion' => $item->transaccion_descripcion,
                'documento' => $item->correlativo ? "# {$item->correlativo}-{$item->anio}" : "N/A",
                'saldo_inicial' => $saldoAnterior,
                'entrada' => $esEntrada ? $cantidad : 0,
                'salida' => $esSalida ? $cantidad : 0,
                'saldo_final' => $acumulado,
                'es_movimiento' => !is_null($item->fecha_emision)
            ];
        }

      } catch (DecryptException $e) {
          return back()->withErrors(['error' => 'Los parámetros de seguridad han sido alterados.']);
      }
    }
    
    //dd($kardexFinal);
    return view('reportes.rpt_kardex_idx', compact('bodegas', 'familias', 'productos', 'bodegaId', 'familiaId', 'productoId', 'kardexFinal'));
  }

  public function rpt_movimiento_articulos(Request $request){
    $bodegas = Bodega::all()->map(function($b) {
        $b->id_encriptado = encrypt($b->id);
        return $b;
    });

    $familias = InvFamilia::all()->map(function($f) {
        $f->id_encriptado = encrypt($f->id);
        return $f;
    });

    $productos = Producto::all()->map(function($p) {
        $p->id_encriptado = encrypt($p->id);
        return $p;
    });

    $bodegaIdEncriptado   = $request->input('bodega_id');
    $familiaIdEncriptado  = $request->input('familia_id');
    $productoIdEncriptado = $request->input('producto_id');

    $bodegaId   = null;
    $familiaId  = null;
    $productoId = null;

    // 2. Desencriptar Parámetros de Filtro
    $bodegaId    = $this->desencriptarParametro($request->input('bodega_id'));
    $familiaId   = $this->desencriptarParametro($request->input('familia_id'));
    $productoId  = $this->desencriptarParametro($request->input('producto_id'));
    $fechaInicio = $request->input('fecha_inicio', date('Y-m-01')); // Por defecto inicio de mes
    $fechaFinal  = $request->input('fecha_final', date('Y-m-01')); // Por defecto inicio de mes

    $saldoInicial     = 0;
    $movimientos      = [];
    $saldoRealEnTabla = 0;
    $hayDescuadre     = false;

    if (($bodegaIdEncriptado !== null && $bodegaIdEncriptado !== "") || ($productoIdEncriptado !== null && $productoIdEncriptado !== "") || ($familiaIdEncriptado !== null && $familiaIdEncriptado !== "")) {
      try{
        // 1. Saldo Inicial (Historia previa a la fecha)
        $saldoInicial = DB::table('detalle_movimientos as dm')
                        ->join('maestro_movimientos as mm', 'dm.maestro_movimiento_id', '=', 'mm.id')
                        ->where('mm.fecha_emision', '<', $fechaInicio)
                        ->when($bodegaId != 0, fn($q) => $q->where('mm.bodega_origen_id', $bodegaId))
                        ->when($productoId != 0, fn($q) => $q->where('dm.producto_id', $productoId))
                        ->sum(\DB::raw('dm.cantidad_x_medida * dm.signo'));

        // 2. Movimientos del periodo
        $movimientos = DB::table('productos as p')
                        ->join('inv_familias as f', 'p.inv_familia_id', '=', 'f.id')
                        ->leftJoin('detalle_movimientos as d', function($join) {
                            $join->on('p.id', '=', 'd.producto_id')
                                 ->where('d.estado', '=', 1); // Filtra el registro 100 (Gasa anulada)
                        })
                        ->leftJoin('maestro_movimientos as m', function($join) {
                            $join->on('m.id', '=', 'd.maestro_movimiento_id')
                                 ->where('m.estado', '=', 1); // Filtra por movimientos activos
                        })
                        ->select(
                            'f.nombre as familia_nombre',
                            'p.descripcion',
                            'p.id as producto_id',
                            
                            // SALDO INICIAL: Aplica la lógica del Observer para fechas anteriores
                            DB::raw("SUM(CASE 
                                WHEN COALESCE(m.fecha_emision, m.created_at) < '$fechaInicio' THEN 
                                    CASE 
                                        WHEN $bodegaId = 0 THEN (d.cantidad_x_medida * d.signo)
                                        WHEN m.bodega_origen_id = $bodegaId THEN (d.cantidad_x_medida * d.signo)
                                        WHEN m.bodega_destino_id = $bodegaId THEN (d.cantidad_x_medida * d.signo * -1) 
                                        ELSE 0 
                                    END
                                ELSE 0 END) as saldo_inicial"),

                            // ENTRADAS: Basado en el signo del detalle y la bodega destino
                            DB::raw("SUM(CASE 
                                WHEN COALESCE(m.fecha_emision, m.created_at) BETWEEN '$fechaInicio' AND '$fechaFinal' THEN
                                    CASE 
                                        -- GLOBAL: Entrada si el detalle suma y NO es un movimiento interno (traslado)
                                        WHEN $bodegaId = 0 THEN 
                                            (CASE WHEN d.signo > 0 AND m.bodega_destino_id IS NULL THEN d.cantidad_x_medida ELSE 0 END)
                                        
                                        -- ESPECÍFICO:
                                        -- 1. Es bodega destino (Traslado recibido: el Observer hace cantidad * -1 * -1)
                                        WHEN m.bodega_destino_id = $bodegaId THEN d.cantidad_x_medida
                                        -- 2. Es bodega origen y el signo del detalle es positivo (Compra/Ajuste)
                                        WHEN m.bodega_origen_id = $bodegaId AND d.signo > 0 THEN d.cantidad_x_medida
                                        ELSE 0 
                                    END
                                ELSE 0 END) as entradas"),

                            // SALIDAS: Basado en el origen y la existencia de un destino
                            DB::raw("SUM(CASE 
                                WHEN COALESCE(m.fecha_emision, m.created_at) BETWEEN '$fechaInicio' AND '$fechaFinal' THEN
                                    CASE 
                                        -- GLOBAL: Salida si el detalle resta y NO se queda dentro de la empresa
                                        WHEN $bodegaId = 0 THEN 
                                            (CASE WHEN d.signo < 0 AND m.bodega_destino_id IS NULL THEN d.cantidad_x_medida ELSE 0 END)
                                        
                                        -- ESPECÍFICO: Es bodega origen y (es un traslado o es un ajuste negativo)
                                        WHEN m.bodega_origen_id = $bodegaId AND (m.bodega_destino_id IS NOT NULL OR d.signo < 0) THEN d.cantidad_x_medida
                                        ELSE 0 
                                    END
                                ELSE 0 END) as salidas")
                        )
                        // Filtro dinámico de Bodega
                        ->when($bodegaId > 0, function($query) use ($bodegaId) {
                            $query->where(function($q) use ($bodegaId) {
                                $q->where('m.bodega_origen_id', $bodegaId)
                                  ->orWhere('m.bodega_destino_id', $bodegaId)
                                  ->orWhereNull('m.id'); 
                            });
                        })
                        ->when($productoId > 0, fn($q) => $q->where('p.id', $productoId))
                        ->when($familiaId > 0, fn($q) => $q->where('p.inv_familia_id', $familiaId))
                        ->groupBy('f.nombre', 'p.descripcion', 'p.id')
                        ->get();
                        

        // 3. Validación contra Tabla de Saldos
        $saldoRealEnTabla = InvSaldo::when($bodegaId != 0, fn($q) => $q->where('bodega_id', $bodegaId))
                            ->when($productoId != 0, fn($q) => $q->where('producto_id', $productoId))
                            ->sum('stock_actual');

        $saldoFinalCalculado = $saldoInicial + $movimientos->where('signo', '>', 0)->sum('cantidad_x_medida') 
                                            - $movimientos->where('signo', '<', 0)->sum('cantidad_x_medida');

        $hayDescuadre = round($saldoFinalCalculado, 2) != round($saldoRealEnTabla, 2);
      } catch (DecryptException $e) {
          return back()->withErrors(['error' => 'Los parámetros de seguridad han sido alterados.']);
      } 
    }

    return view('reportes.rpt_movimientos_idx', compact(
        'movimientos', 'saldoInicial', 'saldoRealEnTabla', 'hayDescuadre', 
        'bodegas', 'familias', 'productos', 'bodegaId', 'familiaId', 'productoId'
    ));
  }

    public function rpt_movimiento_articulos_pdf($fecha_inicial, $fecha_final){
      $empresa = Empresa::where('id', Auth::user()->empresa_id)->first();

      $movimientos = [];

      $articulos = DB::table('productos as p')
                     ->join('invclasificaciones as ic', 'p.clasificacion', 'ic.id')
                     ->join('inv_familias as if', 'p.inv_familia_id', 'if.id')
                     ->where('p.empresa_id', Auth::user()->empresa_id)
                     ->where('ic.definir_medidas', 1)
                     ->select('p.id as producto_id', 'p.descripcion as producto_descripcion', 'if.nombre as familia_descripcion')
                     ->orderby('p.descripcion')
                     ->get();

      foreach ($articulos as $a) {
        $saldo_inicial = DB::table('maestro_movimientos as mm')
                         ->join('detalle_movimientos as dm', 'mm.id', 'dm.maestro_movimiento_id')
                         ->where('mm.empresa_id', Auth::user()->empresa_id)
                         ->whereDate('mm.created_at', '<', $fecha_inicial)
                         ->where('dm.producto_id', $a->producto_id)
                         ->select(DB::raw('SUM(IFNULL(dm.cantidad_x_medida,0)*dm.signo) as saldo_inicial'))
                         ->first();

        foreach ($saldo_inicial as $si) {
          if (!isset($si)) {
            $saldo_inicial = 0;
          }else{
            $saldo_inicial = $si;
          }
          $movimiento = DB::table('maestro_movimientos as mm')
                      ->join('detalle_movimientos as dm', 'mm.id', 'dm.maestro_movimiento_id')
                      ->where('mm.empresa_id', Auth::user()->empresa_id)
                      ->whereDate('mm.created_at', '>=', $fecha_inicial)
                      ->whereDate('mm.created_at', '<=', $fecha_final)
                      ->where('dm.producto_id', $a->producto_id)
                      ->select(DB::raw('SUM(CASE dm.signo WHEN 1 THEN IFNULL(dm.cantidad_x_medida,0) ELSE 0 END) as ingreso'), DB::raw('SUM(CASE dm.signo WHEN -1 THEN IFNULL(dm.cantidad_x_medida,0) ELSE 0 END) as egreso'))
                      ->get();

          foreach ($movimiento as $m) {
            $ingreso     = floatval($m->ingreso);
            $egreso      = floatval($m->egreso);
            $saldo_final = $saldo_inicial + $m->ingreso - $m->egreso;

            array_push($movimientos, ['producto_familia'     => $a->producto_familia,
                                      'producto_descripcion' => $a->producto_descripcion, 
                                      'saldo_inicial'        => $saldo_inicial,
                                      'ingreso'              => $ingreso,
                                      'egreso'               => $egreso, 
                                      'saldo_final'          => $saldo_final]);
          }
        }
      }
      //return view('reportes.rpt_movimientos_pdf', compact('empresa', 'fecha_inicial', 'fecha_final', 'movimientos'));
      ini_set('memory_limit', '-1');
      $pdf = PDF::loadView('reportes.rpt_movimientos_pdf', compact('empresa', 'movimientos', 'fecha_inicial', 'fecha_final'));
      $pdf->setPaper('letter','portrait');
      $nombre_informe = 'movimientos.pdf';
      return $pdf->stream($nombre_informe);
    }

}