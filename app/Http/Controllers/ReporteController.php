<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use DB;
use PDF;
use App\Helpers\DocumentoHelper;
use Carbon\Carbon;
use App\Models\Empresa;

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
		             ->join('tipo_atenciones as ta', 'a.tipo_admision', 'ta.id')
		             ->join('hospitales as h', 'a.hospital_id', 'h.id')
		             ->join('users as u', 'a.created_by', 'u.id')
		             ->leftjoin('detalle_movimientos as dm', 'a.id', 'dm.admision_id')
		             ->whereDate('a.fecha', '>=', $fecha_inicial)
		             ->whereDate('a.fecha', '<=', $fecha_final)
		             ->when($estado != 'T', function($query) use ($estado) {
        					return $query->where('a.estado', '=', $estado);
					 })
		             ->groupBy('a.id', 'a.admision', 'u.username', 'p.nombre_completo', 'p.expediente_no', 'a.fecha', 'ta.nombre', 'h.nombre')
		             ->select('a.id', 'a.admision', 'u.username', 'p.nombre_completo', 'p.expediente_no', 'a.fecha', 'ta.nombre as tipo_admision', 'h.nombre as hospital_nombre', DB::raw('("x") as procedimiento_nombre'),  DB::raw('SUM(dm.precio_total) as total_cargos'),
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

	//====================================================================================
	// Inventarios
	//====================================================================================
	public function disponibilidad_articulos_idx(){
      $detalle = DB::table('productos as p')
                 ->join('unidad_medidas as um', 'p.medida_id', 'um.id')
                 ->join('invclasificaciones as ic', 'p.clasificacion', 'ic.id')
                 ->leftjoin('detalle_movimientos as dm', 'p.id', 'dm.producto_id')
                 ->leftjoin('maestro_movimientos as mm', 'dm.maestro_movimiento_id', 'mm.id')
                 ->where('p.empresa_id', Auth::user()->empresa_id)
                 // ->where('p.clasificacion', 'PROD')
                 ->where('ic.definir_medidas', 1)
                 ->groupby('p.descripcion', 'um.descripcion')
                 ->select(DB::raw("IFNULL(SUM(dm.cantidad_x_medida * mm.signo),0) as disponible"), 'p.descripcion as producto_descripcion', 'um.descripcion as unidad_medida_descripcion')
                 ->orderBy('p.descripcion')
                 ->get();
      
  		return view('reportes.rpt_disponible_idx', compact('detalle'));
    }

    public function disponibilidad_articulos_pdf(){
  		$hoy     = Carbon::now()->format('d/m/Y');
      	$empresa = Empresa::findOrFail(Auth::user()->empresa_id)->first();
      	$detalle = DB::table('productos as p')
                   ->join('invclasificaciones as ic', 'p.clasificacion', 'ic.id')
	                 ->leftjoin('detalle_movimientos as dm', 'p.id', 'dm.producto_id')
	                 ->leftjoin('maestro_movimientos as mm', 'dm.maestro_movimiento_id', 'mm.id')
	                 ->join('unidad_medidas as um', 'p.medida_id', 'um.id')
	                 ->where('p.empresa_id', Auth::user()->empresa_id)
	                 // ->where('p.clasificacion', 'PROD')
                   ->where('ic.definir_medidas', 1)
	                 ->groupby('p.descripcion', 'um.descripcion')
	                 ->select(DB::raw("IFNULL(SUM(dm.cantidad_x_medida * mm.signo),0) as disponible"), 'p.descripcion as producto_descripcion', 'um.descripcion as unidad_medida_descripcion')
	                 ->orderBy('p.descripcion')
	                 ->get();

      	ini_set('memory_limit', '-1');
      	$pdf = PDF::loadView('reportes.rpt_producto_disponible_pdf', compact('hoy', 'detalle', 'empresa'));
      	$pdf->setPaper('letter','portrait');
      	$nombre_informe = 'disponibilidad_de_productos.pdf';
      	return $pdf->stream($nombre_informe);
    }

    public function rpt_kardex_articulos($producto_id, $fecha_inicial){
      $productos = DB::table('productos as p')
                   ->join('invclasificaciones as ic', 'p.clasificacion', 'ic.id')
                   ->where('p.empresa_id', Auth::user()->empresa_id)
                   // ->where('p.clasificacion', 'PROD')
                   ->where('ic.definir_medidas', 1)
                   ->select('p.id as producto_id', 'p.descripcion as producto_descripcion')
                   ->get();

      /*$hoy = Carbon::now()->format('Y-m-d');
      
      
      if ($producto_id != 0) {
        $productos = DB::table('productos as p')
                   ->where('p.empresa_id', Auth::user()->empresa_id)
                   ->where('p.clasificacion', 'PROD')
                   ->where('p.id', $producto_id)
                   ->select('P.id as producto_id', 'p.descripcion as producto_descripcion')
                   ->get();
      }else{
        $productos = DB::table('productos as p')
                   ->where('p.empresa_id', Auth::user()->empresa_id)
                   ->where('p.clasificacion', 'PROD')
                   ->select('P.id as producto_id', 'p.descripcion as producto_descripcion')
                   ->get();
      }*/
      if ($producto_id == 0) {
        $articulos = DB::table('productos as p')
                     ->join('invclasificaciones as ic', 'p.clasificacion', 'ic.id')
                     ->where('p.empresa_id', Auth::user()->empresa_id)
                     ->where('ic.definir_medidas', 1)
                     ->select('p.id as producto_id', 'p.descripcion as producto_descripcion')
                     ->orderby('p.descripcion')
                     ->get();
        // $articulos = Producto::where('clasificacion', 'PROD')
        //              ->where('empresa_id', Auth::user()->empresa_id)
        //              ->where('clasificacion', 'PROD')
        //              ->select('id as producto_id', 'descripcion as producto_descripcion')
        //              ->orderby('descripcion')->get()
        //              ->toArray();
      }else{
        $articulos = DB::table('productos as p')
                     ->join('invclasificaciones as ic', 'p.clasificacion', 'ic.id')
                     ->where('p.empresa_id', Auth::user()->empresa_id)
                     ->where('p.id', $producto_id)
                     ->where('ic.definir_medidas', 1)
                     ->select('p.id as producto_id', 'p.descripcion as producto_descripcion')
                     ->orderby('p.descripcion')
                     ->get();
        // $articulos = Producto::where('clasificacion', 'PROD')
        //              ->where('empresa_id', Auth::user()->empresa_id)
        //              ->where('clasificacion', 'PROD')
        //              ->where('id', $producto_id)
        //              ->select('id as producto_id', 'descripcion as producto_descripcion')
        //              ->orderby('descripcion')->get()
        //              ->toArray();
      }

      $movimientos = array();
      $saldo_final = 0;

      foreach ($articulos as $a) {
        $saldo_inicial = DB::table('maestro_movimientos as mm')
                         ->join('detalle_movimientos as dm', 'mm.id', 'dm.maestro_movimiento_id')
                         ->where('mm.empresa_id', Auth::user()->empresa_id)
                         ->whereDate('mm.created_at', '<', $fecha_inicial)
                         ->where('dm.producto_id', $a->producto_id)
                         ->select(DB::raw('SUM(IFNULL(dm.cantidad_x_medida,0)*mm.signo) as saldo_inicial'))
                         ->first();

        foreach ($saldo_inicial as $si) {
          if (!isset($si)) {
            $saldo_inicial = 0;
          }else{
            $saldo_inicial = $si;
          }
          array_push($movimientos, ['tipo'                 => 'P',
                                    'producto_descripcion' => $a->producto_descripcion, 
                                    'saldo_inicial'        => $saldo_inicial,
                                    'ingreso'              => '',
                                    'egreso'               => '', 
                                    'saldo_final'          => $saldo_inicial]);

          $saldo_final   = $saldo_inicial;

          $movimiento = DB::table('maestro_movimientos as mm')
                      ->join('detalle_movimientos as dm', 'mm.id', 'dm.maestro_movimiento_id')
                      ->join('inventario_transacciones as it', 'mm.inventario_transaccion_id', 'it.id')
                      ->where('mm.empresa_id', Auth::user()->empresa_id)
                      ->whereDate('mm.created_at', '>=', $fecha_inicial)
                      ->where('dm.producto_id', $a->producto_id)
                      ->orderBy('mm.created_at', 'ASC')
                      ->select('it.descripcion as transaccion_descripcion', 'mm.correlativo', 'mm.anio', 'mm.created_at as transaccion_fecha', DB::raw('IFNULL(dm.cantidad_x_medida,0)*mm.signo as cantidad'), 'mm.signo')
                      ->get();

          //dd($fecha_inicial);
          foreach ($movimiento as $m) {
            $fecha = Carbon::parse($m->transaccion_fecha)->format('d/m/Y');
            $cantidad    = floatval($m->cantidad);
            $saldo_final = $saldo_inicial + $m->cantidad;
            if ($m->signo == 1) {
              array_push($movimientos, ['tipo'                 => 'T',
                                        'producto_descripcion' => '      '.$m->transaccion_descripcion.' # '.$m->correlativo.'-'.$m->anio.' fecha '.$fecha, 
                                        'saldo_inicial'  => $saldo_inicial, 
                                        'ingreso'        => $cantidad,
                                        'egreso'         => 0, 
                                        'saldo_final'    => $saldo_final]);
            } else {
              array_push($movimientos, ['tipo'                 => 'T',
                                        'producto_descripcion' => $m->transaccion_descripcion.' # '.$m->correlativo.'-'.$m->anio.' fecha '.$fecha, 
                                        'saldo_inicial' => $saldo_inicial, 
                                        'ingreso'       => 0, 
                                        'egreso'        => $cantidad, 
                                        'saldo_final'   => $saldo_final]);
            }
            $saldo_inicial = $saldo_final; 
          }
        }
      }
      

      return view('reportes.rpt_kardex_idx', compact('articulos', 'productos', 'fecha_inicial', 'movimientos'));
    }

    public function rpt_kardex_articulos_pdf($producto_id, $fecha_inicial){
      $empresa = Empresa::where('id', Auth::user()->empresa_id)->first();

      if ($producto_id == 0) {
        // $articulos = Producto::where('clasificacion', 'PROD')
        //              ->where('empresa_id', Auth::user()->empresa_id)
        //              ->where('clasificacion', 'PROD')
        //              ->select('id as producto_id', 'descripcion as producto_descripcion')
        //              ->orderby('descripcion')->get()
        //              ->toArray();
        $articulos = DB::table('productos as p')
                     ->join('invclasificaciones as ic', 'p.clasificacion', 'ic.id')
                     ->where('p.empresa_id', Auth::user()->empresa_id)
                     ->where('ic.definir_medidas', 1)
                     ->select('p.id as producto_id', 'p.descripcion as producto_descripcion')
                     ->orderby('p.descripcion')
                     ->get();
      }else{
        // $articulos = Producto::where('clasificacion', 'PROD')
        //              ->where('empresa_id', Auth::user()->empresa_id)
        //              ->where('clasificacion', 'PROD')
        //              ->where('id', $producto_id)
        //              ->select('id as producto_id', 'descripcion as producto_descripcion')
        //              ->orderby('descripcion')->get()
        //              ->toArray();
        $articulos = DB::table('productos as p')
                     ->join('invclasificaciones as ic', 'p.clasificacion', 'ic.id')
                     ->where('p.empresa_id', Auth::user()->empresa_id)
                     ->where('p.id', $producto_id)
                     ->where('ic.definir_medidas', 1)
                     ->select('p.id as producto_id', 'p.descripcion as producto_descripcion')
                     ->orderby('p.descripcion')
                     ->get();
      }

      $movimientos = array();
      $saldo_final = 0;

      foreach ($articulos as $a) {
        $saldo_inicial = DB::table('maestro_movimientos as mm')
                         ->join('detalle_movimientos as dm', 'mm.id', 'dm.maestro_movimiento_id')
                         ->where('mm.empresa_id', Auth::user()->empresa_id)
                         ->whereDate('mm.created_at', '<', $fecha_inicial)
                         ->where('dm.producto_id', $a->producto_id)
                         ->select(DB::raw('SUM(IFNULL(dm.cantidad_x_medida,0)*mm.signo) as saldo_inicial'))
                         ->first();

        foreach ($saldo_inicial as $si) {
          if (!isset($si)) {
            $saldo_inicial = 0;
          }else{
            $saldo_inicial = $si;
          }
          array_push($movimientos, ['tipo'                 => 'P',
                                    'producto_descripcion' => $a->producto_descripcion, 
                                    'saldo_inicial'        => $saldo_inicial,
                                    'ingreso'              => '',
                                    'egreso'               => '', 
                                    'saldo_final'          => $saldo_inicial]);

          $saldo_final   = $saldo_inicial;

          $movimiento = DB::table('maestro_movimientos as mm')
                      ->join('detalle_movimientos as dm', 'mm.id', 'dm.maestro_movimiento_id')
                      ->join('inventario_transacciones as it', 'mm.inventario_transaccion_id', 'it.id')
                      ->where('mm.empresa_id', Auth::user()->empresa_id)
                      ->whereDate('mm.created_at', '>=', $fecha_inicial)
                      ->where('dm.producto_id', $a->producto_id)
                      ->orderBy('mm.created_at', 'ASC')
                      ->select('it.descripcion as transaccion_descripcion', 'mm.correlativo', 'mm.anio', 'mm.created_at as transaccion_fecha', DB::raw('IFNULL(dm.cantidad_x_medida,0)*mm.signo as cantidad'), 'mm.signo')
                      ->get();

          //dd($fecha_inicial);
          foreach ($movimiento as $m) {
            $fecha = Carbon::parse($m->transaccion_fecha)->format('d/m/Y');
            $cantidad    = floatval($m->cantidad);
            $saldo_final = $saldo_inicial + $m->cantidad;
            if ($m->signo == 1) {
              array_push($movimientos, ['tipo'                 => 'T',
                                        'producto_descripcion' => '      '.$m->transaccion_descripcion.' # '.$m->correlativo.'-'.$m->anio.' fecha '.$fecha, 
                                        'saldo_inicial'  => $saldo_inicial, 
                                        'ingreso'        => $cantidad,
                                        'egreso'         => 0, 
                                        'saldo_final'    => $saldo_final]);
            } else {
              array_push($movimientos, ['tipo'                 => 'T',
                                        'producto_descripcion' => $m->transaccion_descripcion.' # '.$m->correlativo.'-'.$m->anio.' fecha '.$fecha, 
                                        'saldo_inicial' => $saldo_inicial, 
                                        'ingreso'       => 0, 
                                        'egreso'        => $cantidad, 
                                        'saldo_final'   => $saldo_final]);
            }
            $saldo_inicial = $saldo_final; 
          }
        }
      }

      //return view('reportes.rpt_kardex_pdf', compact('empresa', 'movimientos'));
      ini_set('memory_limit', '-1');
      $pdf = PDF::loadView('reportes.rpt_kardex_pdf', compact('empresa', 'movimientos', 'fecha_inicial'));
      $pdf->setPaper('letter','portrait');
      $nombre_informe = 'Kardex.pdf';
      return $pdf->stream($nombre_informe);
    }

    public function rpt_movimiento_articulos($fecha_inicial, $fecha_final){
      $movimientos = array();

      $articulos = DB::table('productos as p')
                     ->join('invclasificaciones as ic', 'p.clasificacion', 'ic.id')
                     ->where('p.empresa_id', Auth::user()->empresa_id)
                     ->where('ic.definir_medidas', 1)
                     ->select('p.id as producto_id', 'p.descripcion as producto_descripcion')
                     ->orderby('p.descripcion')
                     ->get();

      foreach ($articulos as $a) {
        $saldo_inicial = DB::table('maestro_movimientos as mm')
                         ->join('detalle_movimientos as dm', 'mm.id', 'dm.maestro_movimiento_id')
                         ->where('mm.empresa_id', Auth::user()->empresa_id)
                         ->whereDate('mm.created_at', '<', $fecha_inicial)
                         ->where('dm.producto_id', $a->producto_id)
                         ->select(DB::raw('SUM(IFNULL(dm.cantidad_x_medida,0)*mm.signo) as saldo_inicial'))
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
                      ->select(DB::raw('SUM(CASE mm.signo WHEN 1 THEN IFNULL(dm.cantidad_x_medida,0) ELSE 0 END) as ingreso'), DB::raw('SUM(CASE mm.signo WHEN -1 THEN IFNULL(dm.cantidad_x_medida,0) ELSE 0 END) as egreso'))
                      ->get();

          foreach ($movimiento as $m) {
            $ingreso     = floatval($m->ingreso);
            $egreso      = floatval($m->egreso);
            $saldo_final = $saldo_inicial + $m->ingreso - $m->egreso;

            array_push($movimientos, ['producto_descripcion' => $a->producto_descripcion, 
                                      'saldo_inicial'        => $saldo_inicial,
                                      'ingreso'              => $ingreso,
                                      'egreso'               => $egreso, 
                                      'saldo_final'          => $saldo_final]);
          }
        }
      }
      return view('reportes.rpt_movimientos_idx', compact('fecha_inicial', 'fecha_final', 'movimientos'));
    }

    public function rpt_movimiento_articulos_pdf($fecha_inicial, $fecha_final){
      $empresa = Empresa::where('id', Auth::user()->empresa_id)->first();

      $movimientos = [];

      $articulos = DB::table('productos as p')
                     ->join('invclasificaciones as ic', 'p.clasificacion', 'ic.id')
                     ->where('p.empresa_id', Auth::user()->empresa_id)
                     ->where('ic.definir_medidas', 1)
                     ->select('p.id as producto_id', 'p.descripcion as producto_descripcion')
                     ->orderby('p.descripcion')
                     ->get();

      foreach ($articulos as $a) {
        $saldo_inicial = DB::table('maestro_movimientos as mm')
                         ->join('detalle_movimientos as dm', 'mm.id', 'dm.maestro_movimiento_id')
                         ->where('mm.empresa_id', Auth::user()->empresa_id)
                         ->whereDate('mm.created_at', '<', $fecha_inicial)
                         ->where('dm.producto_id', $a->producto_id)
                         ->select(DB::raw('SUM(IFNULL(dm.cantidad_x_medida,0)*mm.signo) as saldo_inicial'))
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
                      ->select(DB::raw('SUM(CASE mm.signo WHEN 1 THEN IFNULL(dm.cantidad_x_medida,0) ELSE 0 END) as ingreso'), DB::raw('SUM(CASE mm.signo WHEN -1 THEN IFNULL(dm.cantidad_x_medida,0) ELSE 0 END) as egreso'))
                      ->get();

          foreach ($movimiento as $m) {
            $ingreso     = floatval($m->ingreso);
            $egreso      = floatval($m->egreso);
            $saldo_final = $saldo_inicial + $m->ingreso - $m->egreso;

            array_push($movimientos, ['producto_descripcion' => $a->producto_descripcion, 
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