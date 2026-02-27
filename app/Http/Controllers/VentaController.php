<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use DB;
use Auth;
use Redirect;
use Response;
use Carbon\Carbon;
use App\Models\Admision;
use App\Models\AdmisionBitacora;
use App\Models\Banco;
use App\Models\Caja;
use App\Models\CajaCorte;
use App\Models\CajaResolucion;
use App\Models\DetalleMovimiento;
use App\Models\DocumentoDetalle;
use App\Models\DocumentoMaestro;
use App\Models\Empresa;
use App\Models\FormaPago;
use App\Models\Inventario_Transaccion;
use App\Models\MaestroMovimiento;
use App\Models\Nit;
use App\Models\MotivoAnulacion;
use App\Models\Paciente;
use App\Models\PagoMaestro;
use App\Models\PagoDetalle;
use App\Models\PagoDocumento;
use App\Models\Producto;
use App\Models\ProductoMedida;
use App\Models\TipoDocumento;

class VentaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $nc = DB::table('documentoventa_maestros as dvm')
              ->join('documentoventa_detalles as dvd', 'dvm.id', '=', 'dvd.documentoventa_maestro_id')
              ->where('dvm.empresa_id', Auth::user()->empresa_id)
              ->where('dvm.tipodocumento_id', 4)
              ->where('dvm.estado', 1)
              ->groupBy('dvm.tipodocumentoafecto_id', 'dvm.serie_afecta', 'dvm.correlativo_afecto')
              ->select('dvm.tipodocumentoafecto_id', 'dvm.serie_afecta', 'dvm.correlativo_afecto', DB::raw('(SUM(dvd.precio_neto)) as total_nc'));

        $pago = DB::table('pago_maestros as pm')
                ->join('pago_documentos as pd', 'pm.id', 'pd.pago_maestro_id')
                ->where('pm.estado', 1)
                ->where('pd.estado', 1)
                ->groupBy('pd.documentoventa_id')
                ->select('pd.documentoventa_id', DB::raw('(SUM(pd.monto_aplicado)) AS total_pagado'));

        $listado = DB::table('documentoventa_maestros as dvm')
                   ->join('tipo_documentos as td', 'dvm.tipodocumento_id', '=', 'td.id')
                   ->join('cajas as c', 'dvm.caja_id', '=', 'c.id')
                   ->leftJoinSub($nc, 'nc', function($join){
                        $join->on('dvm.tipodocumento_id', '=', 'nc.tipodocumentoafecto_id');
                        $join->on('dvm.serie', '=', 'nc.serie_afecta');
                        $join->on('dvm.correlativo', '=', 'nc.correlativo_afecto');
                   })
                   ->leftJoinSub($pago, 'pago', function($join){
                        $join->on('dvm.id', '=', 'pago.documentoventa_id');
                   })
                   ->leftJoin('pacientes as p', 'dvm.paciente_id', '=', 'p.id')
                   ->where('dvm.empresa_id', Auth::user()->empresa_id)
                   ->whereIn('dvm.tipodocumento_id', [1,3])
                   ->groupBy('dvm.id', 'c.nombre_maquina', 'dvm.corte_id', 'td.descripcion', 'dvm.fecha_emision', 'dvm.serie', 'dvm.correlativo', 'dvm.paciente_id', 'dvm.nit', 'dvm.nombre', 'p.nombre_completo', 'nc.total_nc')
                   ->select('dvm.id', 'c.nombre_maquina', 'dvm.corte_id', 'td.descripcion', 'dvm.fecha_emision', 'dvm.serie', 'dvm.correlativo', 'dvm.paciente_id', 'dvm.nit', 'dvm.nombre', 'p.nombre_completo as paciente_nombre', 'dvm.total', 'nc.total_nc', 'pago.total_pagado',
                            DB::raw("CASE WHEN dvm.condicion = 0 THEN 'Contado' ELSE 'Credito' END as condicion"),  
                            DB::raw('dvm.total - IFNULL(nc.total_nc, 0) - IFNULL(pago.total_pagado, 0) as saldo'),
                            DB::raw('CASE WHEN dvm.estado = 1 THEN "Vigente" ELSE "Anulado" END as estado'))
                   ->get();
        
        return view('ventas.documentos_index', compact('listado'));
    }

    public function index_nc(){
        $listado = DB::table('documentoventa_maestros as dm')
                   ->join('documentoventa_detalles as dd', 'dm.id', 'dd.documentoventa_maestro_id')
                   ->join('cajas as c', 'dm.caja_id', '=', 'c.id')
                   ->join('tipo_documentos as td',
                        function($j){
                            $j->on('dm.tipodocumento_id', '=', 'td.id')->where('td.tipo_interno','=','NC');
                        })
                   ->where('dm.empresa_id', Auth::user()->empresa_id)
                   ->select('dm.id', 'c.nombre_maquina', 'dm.corte_id', 'td.descripcion as tipo_descripcion', 'dm.serie', 'dm.correlativo', 'dm.fecha_emision', 'dm.serie_afecta', 'dm.correlativo_afecto', 'dm.nit', 'dm.nombre',  'dm.estado', DB::raw('(CASE WHEN dm.estado = 1 THEN "Vigente" ELSE "Anulada" END) AS estado_descripcion'), DB::raw('SUM(dd.precio_neto) as precio_neto'))
                   ->groupBy('dm.id', 'td.descripcion', 'dm.serie', 'dm.correlativo', 'dm.fecha_emision', 'dm.serie_afecta', 'dm.correlativo_afecto', 'dm.nit', 'dm.nombre', 'dm.estado')
                   ->get();

        return view('ventas.nc_index', compact('listado'));

    }

    public function index_nd(){
        $listado = DB::table('documentoventa_maestros as dm')
                   ->join('documentoventa_detalles as dd', 'dm.id', 'dd.documentoventa_maestro_id')
                   ->join('tipo_documentos as td',
                        function($j){
                            $j->on('dm.tipodocumento_id', '=', 'td.id')->where('td.tipo_interno','=','ND');
                        })
                   ->where('dm.empresa_id', Auth::user()->empresa_id)
                   ->select('dm.id', 'td.descripcion as tipo_descripcion', 'dm.serie', 'dm.correlativo', 'dm.fecha_emision',  'dm.nit', 'dm.nombre',  'dm.estado', DB::raw('(CASE WHEN dm.estado = "A" THEN "Vigente" ELSE "Anulada" END) AS estado_descripcion'), DB::raw('SUM(dd.precio_neto) as precio_neto'))
                   ->groupBy('dm.id', 'td.descripcion', 'dm.serie', 'dm.correlativo', 'dm.fecha_emision', 'dm.nit', 'dm.nombre', 'dm.estado')
                   ->get();
        return view('ventas.nd_index', compact('listado'));
    }

    public function factura_create(string $parametro_admision){
        $hoy         = Carbon::now()->format('Y-m-d');
        $documento   = TipoDocumento::where('tipo_interno', 'VT')->where('estado', 1)->get();
        $pacientes   = Paciente::all();
        $caja        = Caja::where('id', Auth::user()->caja_id)->first();
        $formas_pago = FormaPago::where('estado', 1)->get();
        $bancos      = Banco::where('tipo_referencia', 'B')->where('estado', 1)->get();
        $tarjetas    = Banco::where('tipo_referencia', 'T')->where('estado', 1)->get();
        $productos   = Producto::where('empresa_id', Auth::user()->empresa_id)->where('estado', 1)->get();

        $no_admision    = 0;
        $aseguradora_id = 0;

        if (empty($caja)) {
            $message = array(
                'message' => 'Usuario no permitido para emitir Facturas !!!',
                'type'    => 'error'
            );

            return redirect()->back()->with($message);
            // return Redirect::back()->withErrors('Usuario no permitido para emitir Facturas');
        } else{
            $resolucion = CajaResolucion::where('caja_id', Auth::user()->caja_id)->where('tipo_documento_id', 1)->where('estado', '1')->count();
            if ($resolucion == 0) {
                $message = array(
                    'message' => 'Caja no cuenta con una resolucion Activa que permita emitir Facturas !!!',
                    'type'    => 'error'
                );

                return redirect()->back()->with($message);
                // return Redirect::back()->withErrors('Caja no cuenta con una resolucion Activa que permita emitir Facturas');
            } else{
                return view('ventas.factura_create', compact('documento', 'hoy', 'caja', 'pacientes', 'productos', 'bancos', 'tarjetas', 'formas_pago', 'no_admision', 'aseguradora_id', 'productos', 'parametro_admision'));
            }
        }
    }

    public function nc_create(){
        $hoy       = Carbon::now()->format('Y-m-d');
        $documento = TipoDocumento::where('tipo_interno', 'NC')->first();
        $pacientes = Paciente::all();
        $caja      = Caja::where('id', Auth::user()->caja_id)->first();
        $productos = Producto::where('estado', '1')->get();
        if (empty($caja)) {
            $message = array(
                'message' => 'Caja no cuenta con una resolucion Activa que permita emitir Notas de Crédito !!!',
                'type'    => 'error'
            );
            return redirect()->back()->with($message);
        } else{
            $resolucion = CajaResolucion::where('caja_id', Auth::user()->caja_id)->where('tipo_documento_id', $documento->id)->where('estado', 1)->count();
            if ($resolucion == 0) {
                $message = array(
                    'message' => 'Caja no cuenta con una resolucion Activa que permita emitir Notas de Crédito !!!',
                    'type'    => 'error'
                );
                return redirect()->back()->with($message);
            } else{
                return view('ventas.nc_create', compact('documento', 'hoy', 'caja', 'pacientes', 'productos'));
            }
        }   
    }

    public function nd_create(){
        $hoy       = Carbon::now()->format('Y-m-d');
        $documento = TipoDocumento::where('tipo_interno', 'ND')->first();
        $productos   = Producto::where('estado', 1)->get();
        //dd($documento);
        //$pacientes = Paciente::all();
        $caja      = Caja::where('id', Auth::user()->caja_id)->first();
        $bancos    = Banco::where('tipo_referencia', 'B')->where('estado', 'A')->get();
        if (empty($caja)) {
            return Redirect::back()->withErrors('Usuario no permitido para emitir Notas de Débito');
        } else{
            $resolucion = CajaResolucion::where('caja_id', Auth::user()->caja_id)->where('tipo_documento_id', 4)->where('estado', 1)->count();
            if ($resolucion == 0) {
                return Redirect::back()->withErrors('Caja no cuenta con una resolucion Activa que permita emitir Notas de Débito');
            } else{
                return view('ventas.nd_create', compact('documento', 'hoy', 'caja', 'bancos', 'productos'));
            }
        }   
    }

    public function factura_store(Request $request){
        // 1. Definir Mensajes
        $messages = [
            'required'                 => 'El campo :attribute es obligatorio.',
            'tipo_documento_id.exists' => 'El tipo de documento seleccionado no es válido o está inactivo.',
            'caja_id.unique'           => 'Este documento (Serie y Correlativo) ya fue registrado previamente.',
            'cargos.required'          => 'Debe agregar productos a la factura.',
            'cargos.array'             => 'La estructura de los cargos es inválida.',
            'cargos.min'               => 'Debe agregar al menos un detalle de cargo.',
            'cargos.*.producto_id.required' => 'Cada cargo debe tener un producto.',
            'cargos.*.producto_id.exists'   => 'Un producto seleccionado no es válido.',
            'cargos.*.precio.required' => 'El precio es obligatorio en todos los cargos.',
            'mpago.min'                => 'Debe registrar al menos una forma de pago.',
            'mpago.*.fpago_id.exists'  => 'La forma de pago seleccionada no es válida.',
            'mpago.required_if'        => 'Debe registrar al menos un medio de pago para ventas al contado.',
            'mpago.*.fpago_id.required_with' => 'El campo forma de pago es obligatorio.'
        ];

        // 2. Definir Reglas en una variable
        $rules = [
            'caja_id' => [
                'required',
                Rule::unique('documentoventa_maestros')->where(function ($query) use ($request) {
                    return $query->where('tipodocumento_id', $request->tipo_documento_id)
                                 ->where('serie', $request->serie)
                                 ->where('correlativo', $request->correlativo);
                }),
            ],
            'resolucion_id'     => 'required',
            'tipo_documento_id' => [
                'required',
                Rule::exists('tipo_documentos', 'id')->where(fn($q) => $q->where('estado', 1)),
            ],
            'condicion'            => 'required',
            'mpago'                => 'required_if:condicion,0|array', // Se quitó min:1 temporalmente para evitar choques con el IF
            'mpago.*.fpago_id'     => 'required_with:mpago|exists:formas_pago,id',
            'mpago.*.monto'        => 'required_with:mpago|numeric|min:0.01',
            'fecha_emision'        => 'required',
            'serie'                => 'required',
            'correlativo'          => 'required',
            'nit'                  => 'required',
            'nombre'               => 'required',
            'direccion'            => 'required',
            'cargos'               => 'required|array|min:1',
            'cargos.*.producto_id' => 'required|exists:productos,id',
            'cargos.*.precio'      => 'required|numeric', // En tu form frontend el name es "precio", verifica esto.
            'cargos.*.descripcion' => 'required|string'
        ];

        // 3. Ejecutar validación (Si falla, Laravel regresa automáticamente atrás con la variable $errors)
        $validData = $request->validate($rules, $messages);

        $hoy = Carbon::now()->format('Y-m-d');
        $empresaId = Auth::user()->empresa_id;
        $anio = Carbon::now()->format('Y');

        // 4. Validar Inventario
        $inv_transaccion = DB::table('tipo_documentos as td')
                           ->join('inventario_transacciones as it', 'td.inventario_transaccion_id', 'it.id')
                           ->where('td.id', $request->tipo_documento_id)
                           ->where('it.empresa_id', $empresaId)
                           ->where('it.estado', 1)
                           ->select('it.id', 'it.signo')
                           ->first();

        if (!$inv_transaccion) {
            return redirect()->back()
                ->withInput()
                ->with(['message' => '¡ Documento no tiene asociado movimiento en inventarios !', 'type' => 'error']);
        }

        // 5. Transacción de Base de Datos
        try {
            // DB::transaction maneja el begin, commit y rollback automáticamente
            DB::transaction(function () use ($request, $inv_transaccion, $anio, $empresaId) {
                $totalDocumento = 0;
                $porcentajeImpuesto = Empresa::obtenerImpuesto($empresaId);
                $ultimo = DB::table('maestro_movimientos')
                    ->where('empresa_id', $empresaId)
                    ->where('inventario_transaccion_id', $inv_transaccion->id)
                    ->where('anio', $anio)
                    ->lockForUpdate()
                    ->max('correlativo');

                // dd($ultimo); <--- ESTO MATABA EL CÓDIGO. ELIMINADO.
                $nuevoCorrelativo = ($ultimo ?? 0) + 1;

                $maestroVenta = new DocumentoMaestro(); 
                $maestroVenta->fill($request->all());

                $maestroVenta->empresa_id = $empresaId;
                $maestroVenta->tipodocumento_id = $request->tipo_documento_id;
                $maestroVenta->estado = 1; 

                $maestroVenta->serie            = $request->serie;
                $maestroVenta->correlativo      = $request->correlativo;

                $maestroVenta->save();

                $encabezadoInv = MaestroMovimiento::obtenerOInstanciar($request->admision_id, $inv_transaccion->id);
                $encabezadoInv->cargarDatosTransaccion($inv_transaccion, $nuevoCorrelativo, $anio, $maestroVenta->id);
                $encabezadoInv->save();

                // Aquí procesas tus $request->cargos ...
                foreach ($request->cargos as $detalle) {
                    $factor = ProductoMedida::obtenerFactor(
                        $detalle['producto_id'], 
                        $detalle['medida_id']
                    );

                    // 1. Crear el detalle de la venta
                    $nuevoDetalle = new DocumentoDetalle(); // Ajusta al nombre real de tu modelo
                    $nuevoDetalle->documentoventa_maestro_id = $maestroVenta->id;
                    $nuevoDetalle->detalle_movimiento_id = $detalle['movimiento_id'];
                    $nuevoDetalle->tipo_facturacion      = 'X';
                    $nuevoDetalle->cantidad              = $detalle['cantidad'];
                    $nuevoDetalle->cantidad_medida       = $factor;
                    $nuevoDetalle->cantidad_x_medida     = $detalle['cantidad'] * $factor;
                    $nuevoDetalle->precio_unitario       = $detalle['precio'];
                    $nuevoDetalle->precio_bruto          = $detalle['precio'];
                    $nuevoDetalle->descuento             = 0;
                    $nuevoDetalle->recargo               = 0;
                    $nuevoDetalle->precio_neto           = $detalle['precio'];
                    $nuevoDetalle->precio_base           = $nuevoDetalle->precio_neto / $porcentajeImpuesto;
                    $nuevoDetalle->precio_impuesto       = $nuevoDetalle->precio_neto - $nuevoDetalle->precio_base;
                    $nuevoDetalle->estado                = 1;
                    $nuevoDetalle->save();

                    $totalDocumento += $nuevoDetalle->precio_neto;

                    // 2. Aquí podrías rebajar el inventario usando $encabezadoInv->id
                    // que ya tenías instanciado más arriba.
                }

                // Actualizas el total...
                $maestroVenta->update(['total' => $totalDocumento]);
                if (isset($request->mpago)) {
                    $totalPago = 0;
                    $recibo = TipoDocumento::where('tipo_interno', 'RP')->where('estado', 1)->first();

                    $resolucion = CajaResolucion::where('caja_id', $request->caja_id)
                                                 ->where('tipo_documento_id', $recibo->id)
                                                 ->where('estado', 1)
                                                 ->lockForUpdate()
                                                 ->select('serie', 'ultimo_correlativo')
                                                 ->first();

                    if (!isset($resolucion->ultimo_correlativo)) {
                        return back()->withInput()->with([
                            'message' => 'Error al guardar: Caja no cuenta con una resolucion Aciva para emitir recibos',
                            'type'    => 'error'
                        ]);
                    }
                    
                    $nuevoCorrelativo = ($resolucion->ultimo_correlativo ?? 0) + 1;
                    
                    $maestroPago = new PagoMaestro();
                    $maestroPago->empresa_id = $empresaId;
                    $maestroPago->caja_id    = $request->caja_id;
                    $maestroPago->tipo_documento_id = $recibo->id;
                    $maestroPago->resolucion_id     = $request->resolucion_id;
                    $maestroPago->fecha_emision     = $request->fecha_emision;
                    $maestroPago->serie             = $resolucion->serie;
                    $maestroPago->correlativo       = $nuevoCorrelativo;
                    $maestroPago->estado            = 1;
                    $maestroPago->save();

                }
                foreach($request->mpago as $pago){
                    $detallePago = new PagoDetalle();
                    $detallePago->pago_maestro_id = $maestroPago->id;
                    $detallePago->forma_pago      = $pago['fpago_id'];
                    $detallePago->banco_id        = $pago['casa_id'];
                    $detallePago->cuenta_no       = $pago['cuenta_no'];
                    $detallePago->documento_no    = $pago['documento_no'];
                    $detallePago->autoriza_no     = $pago['autoriza_no'];
                    $detallePago->monto           = $pago['monto'];
                    $detallePago->estado          = 1;
                    $detallePago->save();
                    $totalPago += $detallePago->monto;

                }

                $documentoPagado = new PagoDocumento();
                $documentoPagado->pago_maestro_id   = $maestroPago->id;
                $documentoPagado->documentoventa_id = $maestroVenta->id;
                $documentoPagado->saldo_pendiente   = $totalDocumento;
                $documentoPagado->monto_aplicado    = $totalPago;
                $documentoPagado->estado            = 1;
                $documentoPagado->save();

            });

            // 6. Si todo sale bien, retornamos una redirección con mensaje (No JSON)
            return redirect()->route('documentos_listado')->with([
                'message' => 'Guardado con éxito. Correlativo generado.',
                'type'    => 'success'
            ]);

        } catch (\Exception $e) {
            // Si hay error, regresamos atrás con el mensaje de error de SQL/PHP
            return back()->withInput()->with([
                'message' => 'Error al guardar: ' . $e->getMessage(),
                'type'    => 'error'
            ]);
        }
    }

    public function nc_store(Request $request){
        $validData = $request->validate([
            'caja_id'           => 'required',
            'tipo_documento_id' => 'required',
            'fecha_emision'     => 'required',
            'fecha'             => 'required',
            'nit'               => 'required',
            'nombre'            => 'required',
            'direccion'         => 'required',
        ]);

        $resolucion = CajaResolucion::where('caja_id', $validData['caja_id'])
                      ->where('tipo_documento_id', $validData['tipo_documento_id'])
                      ->where('estado', 1)
                      ->first();

        if (!isset($resolucion)) {
            $message = array(
                'message' => 'Caja NO cuenta con una resolucion activa que permita guardar el documento, Favor verifique !!!',
                'type'    => 'error'
            );

            return redirect()->back()->with($message);
        }else{
            $serie       = $resolucion->serie;
            $correlativo = $resolucion->ultimo_correlativo + 1;

            $existe = DocumentoMaestro::where('empresa_id', Auth::user()->empresa_id)
                        ->where('tipodocumento_id', $validData['tipo_documento_id'])
                        ->where('serie', $serie)
                        ->where('correlativo', $correlativo)
                        ->count();

            if ($existe > 0) {
                $message = array(
                    'message' => 'Nota de Crédito '.$serie.' - '.$correlativo.' Ya existe',
                    'type'    => 'error'
                );

                return redirect()->back()->with($message);
            } else{
                //====================================================================================
                // Localiza encabezado documento que origino la nota de crédito
                //====================================================================================
                $documento_original = DocumentoMaestro::where('empresa_id', Auth::user()->empresa_id)
                                        ->where('tipodocumento_id', $request['tipodocumento'])
                                        ->where('serie', $request['serie_afecta'])
                                        ->where('correlativo', $request['documento_afecto'])
                                        ->first();

                //====================================================================================
                // Crea encabezado de nota de crédito
                //====================================================================================
                $maestro = new DocumentoMaestro();
                $maestro->empresa_id             = Auth::user()->empresa_id;
                $maestro->caja_id                = Auth::user()->caja_id;
                $maestro->tipodocumento_id       = $validData['tipo_documento_id'];
                $maestro->resolucion_id          = $resolucion->id;
                $maestro->fecha_emision          = $validData['fecha_emision'];
                $maestro->serie                  = $serie;
                $maestro->correlativo            = $correlativo;
                $maestro->paciente_id            = null; /*$paciente_id;*/
                $maestro->condicion              = 0; /*$condicion;*/
                $maestro->nit                    = $validData['nit'];
                $maestro->nombre                 = $validData['nombre'];
                $maestro->direccion              = $validData['direccion'];
                $maestro->tipodocumentoafecto_id = $request['tipodocumento'];
                $maestro->serie_afecta           = strtoupper($request['serie_afecta']);
                $maestro->correlativo_afecto     = $request['documento_afecto'];
                $maestro->estado                 = 1;
                $maestro->save();


                //====================================================================================
                // Actualiza correlativo de resolución
                //====================================================================================
                $resolucion->ultimo_correlativo = $correlativo;
                if ($correlativo == $resolucion->correlativo_final ) {
                    $resolucion->estado = 2;
                }
                $resolucion->save();

                $trnInventario = Inventario_Transaccion::where('empresa_id', Auth::user()->empresa_id)
                                 ->where('id', 6)
                                 ->where('estado', 'A')
                                 ->first();

                $anio = Carbon::now()->format('Y');

                $correlativo = DB::table('maestro_movimientos as mm')
                               ->where('mm.empresa_id', Auth::user()->empresa_id)
                               ->where('mm.inventario_transaccion_id', $trnInventario->id)
                               ->where('mm.anio', $anio)
                               ->select(DB::raw('IFNULL(MAX(correlativo),0) as ultimo_correlativo'))
                               ->first();

                $nuevo_correlativo = $correlativo->ultimo_correlativo + 1;

                //====================================================================================
                // Graba encabezado de movimientos en inventario
                //====================================================================================
                // dd($trnInventario->id);
                $invMaestro = new MaestroMovimiento();
                $invMaestro->empresa_id                = Auth::user()->empresa_id;
                $invMaestro->inventario_transaccion_id = $trnInventario->id;
                $invMaestro->signo                     = $trnInventario->signo;
                $invMaestro->correlativo               = $nuevo_correlativo;
                $invMaestro->anio                      = $anio;
                $invMaestro->maestro_documento_id      = $maestro->id;
                $invMaestro->bodega_origen_id          = 1;
                $invMaestro->estado                    = 'A';
                $invMaestro->save();

                //====================================================================================
                // Graba detalle en inventario para cargar unidades devueltas
                //====================================================================================

                $total = 0;

                foreach ($request->cargos as $key => $cargo) {
                    $total   += $cargo['precio_total'];
                    $detalle = new DetalleMovimiento();
                    $detalle->maestro_movimiento_id = $invMaestro->id;
                    //$detalle->admision_id           = $admision_id;
                    $detalle->maestro_documento_id  = $maestro->id;
                    $detalle->producto_id           = $cargo['producto_id'];
                    $detalle->descripcion           = $cargo['descripcion'];
                    $detalle->unidad_medida_id      = $cargo['medida_id'];
                    $detalle->cantidad              = $cargo['cantidad'];

                    $producto = Producto::findOrFail($cargo['producto_id']);

                    if ($producto->clasificacion == 'PROD') {
                        $medida = ProductoMedida::where('producto_id',$cargo['producto_id'])->where('unidad_medida_id', $cargo['medida_id'])->first();
                        $detalle->cantidad_medida       = $medida->cantidad;
                        $detalle->cantidad_x_medida     = $cargo['cantidad'] * $medida->cantidad;
                    }else{
                        $detalle->cantidad_medida       = 1;
                        $detalle->cantidad_x_medida     = $cargo['cantidad'];
                    }
                    $detalle->precio_unitario = $cargo['precio_unitario'];
                    $detalle->precio_bruto    = $cargo['precio_total'];
                    $detalle->descuento       = 0;
                    $detalle->recargo         = 0;
                    $detalle->precio_base     = $cargo['precio_total'] /1.12;
                    $detalle->precio_impuesto = $cargo['precio_total'] - ($cargo['precio_total'] /1.12);
                    $detalle->precio_total    = $cargo['precio_total'];
                    $detalle->precio_cliente  = $cargo['precio_total'];
                    $detalle->precio_aseguradora = 0;
                    $detalle->save();


                    $registro = new DocumentoDetalle();
                    $registro->documentoventa_maestro_id = $maestro->id;
                    $registro->detalle_movimiento_id     = $detalle->id;
                    $registro->tipo_facturacion          = 'N';
                    $registro->cantidad                  = $detalle->cantidad;
                    $registro->cantidad_medida           = $detalle->cantidad_medida;
                    $registro->cantidad_x_medida         = $detalle->cantidad_x_medida;
                    $registro->precio_unitario           = $detalle->precio_unitario;
                    $registro->precio_bruto              = $detalle->precio_unitario * $detalle->cantidad_x_medida;
                    $registro->descuento                 = $detalle->descuento;
                    $registro->recargo                   = $detalle->recargo;
                    $registro->precio_neto               = ($detalle->precio_unitario * $detalle->cantidad_x_medida) + $detalle->recargo - $detalle->descuento;
                    $registro->precio_base               = $registro->precio_neto / 1.12;
                    $registro->precio_impuesto           = $registro->precio_neto - ($registro->precio_neto / 1.12);
                    $registro->estado                    = 1;
                    $registro->save();
                }
                
                $message = array(
                    'message' => 'Nota de Crédito '.$serie.' - '.$nuevo_correlativo.' Guardada correctamente !!!',
                    'type'    => 'success'
                );

                return redirect()->back()->with($message);
            }

        }



        // $tipodocumento_id  = $_POST['tipo_documento_id'];
        // $serie             = $_POST['serie'];
        // $correlativo       = $_POST['correlativo'];
        // $resolucion_id     = $_POST['resolucion_id'];
        // $paciente_id       = $_POST['paciente_id'];
        // $fecha_emision     = $_POST['fecha_emision'];
        // $condicion         = $_POST['condicion'];
        // $nit               = $_POST['nit'];
        // $nombre            = $_POST['nombre'];
        // $direccion         = $_POST['direccion'];
        // $email             = $_POST['email'];
        // $tipo_documento_afecto_id = $_POST['tipo_documento_afecto_id'];
        // $serie_afecta      = strtoupper($_POST['serie_afecta']);
        // $documento_afecto  = $_POST['documento_afecto'];
        // $data = (array) json_decode($_POST['arreglo'], true);

        // $tipo_documento = TipoDocumento::findOrFail($tipodocumento_id);
        // $caja           = Caja::where('id', Auth::user()->caja_id)->first();
        // $resolucion     = CajaResolucion::findOrFail($resolucion_id);
        
        // $totalRegistros = count($data);
        // $totalAplicar   = 0;
        // $cadena_error = '';
        // $hoy = Carbon::now()->format('Y-m-d');

        // $existe = DocumentoMaestro::where('empresa_id', Auth::user()->empresa_id)
        //                             ->where('tipodocumento_id', $tipodocumento_id)
        //                             ->where('serie', $serie)
        //                             ->where('correlativo', $correlativo)
        //                             ->count();

        // if ($totalRegistros == 0) {
        //     $cadena_error = $cadena_error.', No existe detalle de para nota de credito ';
        // }
        // if ($existe > 0) {
        //     $cadena_error = $cadena_error.', Nota de Crédito '.$serie.' - '.$correlativo.' Ya existe';
        // }

        // if ($cadena_error != '') {
        //     $respuesta1 = array('estado' => '0', 'mensaje' => $cadena_error);
        // }
        // else{
        //     $documento_original = DocumentoMaestro::where('empresa_id', Auth::user()->empresa_id)
        //                           ->where('tipodocumento_id', $tipo_documento_afecto_id)
        //                           ->where('serie', $serie_afecta)
        //                           ->where('correlativo', $documento_afecto)
        //                           ->first();

        //     $maestro = new DocumentoMaestro();
        //     $maestro->empresa_id             = Auth::user()->empresa_id;
        //     $maestro->caja_id                = Auth::user()->caja_id;
        //     $maestro->tipodocumento_id       = $tipo_documento->id;
        //     $maestro->resolucion_id          = $resolucion->id;
        //     $maestro->fecha_emision          = $fecha_emision;
        //     $maestro->serie                  = $serie;
        //     $maestro->correlativo            = $correlativo;
        //     $maestro->paciente_id            = $paciente_id;
        //     $maestro->condicion              = $condicion;
        //     $maestro->nit                    = $nit;
        //     $maestro->nombre                 = $nombre;
        //     $maestro->direccion              = $direccion;
        //     $maestro->tipodocumentoafecto_id = $tipo_documento_afecto_id;
        //     $maestro->serie_afecta           = $serie_afecta;
        //     $maestro->correlativo_afecto     = $documento_afecto;
        //     $maestro->estado                 = 1;
        //     $maestro->save();


        //     $resolucion->ultimo_correlativo = $correlativo;
        //     if ($correlativo == $resolucion->correlativo_final ) {
        //         $resolucion->estado = 'I';
        //     }
        //     $resolucion->save();

        //     for ($i=0; $i < $totalRegistros ; $i++) {
        //         $tipo_facturacion = DocumentoDetalle::where('admision_cargo_id', $data[$i]['cargo_detalle_id'])->select('tipo_facturacion')->first();
        //         $detalle = new DocumentoDetalle();
        //         $detalle->documentoventa_maestro_id = $maestro->id;
        //         $detalle->admision_cargo_id         = $data[$i]['cargo_detalle_id'];
        //         $detalle->producto_id               = $data[$i]['producto_id'];
        //         $detalle->unidad_medida_id          = $data[$i]['unidad_medida_id'];
        //         $detalle->descripcion               = $data[$i]['descripcion'];
        //         $detalle->signo                     = $tipo_documento->signo;
        //         $detalle->cantidad                  = $data[$i]['cantidad'];
        //         $detalle->precio_unitario           = $data[$i]['precio_unitario'];
        //         $detalle->precio_bruto              = $data[$i]['precio_bruto'];
        //         $detalle->descuento                 = $data[$i]['descuento'];
        //         $detalle->recargo                   = $data[$i]['recargo'];
        //         $detalle->precio_neto               = $data[$i]['precio_neto'];
        //         $detalle->precio_base               = intval($data[$i]['precio_neto'])/1.12;
        //         $detalle->precio_impuesto           = $detalle->precio_neto - $detalle->precio_base;
        //         $detalle->estado                    = 1;
        //         $detalle->save();

        //         $totalAplicar += $detalle->precio_neto;
        //     }

        //     $pago_documento = new PagoDocumento();
        //     $pago_documento->nc_id             = $maestro->id;
        //     $pago_documento->documentoventa_id = $documento_original->id;
        //     $pago_documento->saldo_pendiente   = 0;
        //     $pago_documento->monto_aplicado    = $totalAplicar;
        //     $pago_documento->estado            = 1;
        //     $pago_documento->save();

        //     $respuesta1 = array('estado'  => '1', 
        //                         'mensaje' => 'Nota de Crédito '.$maestro->serie.'-'.$maestro->correlativo.' Grabada con exito !!!', 
        //                         'nota_id' => $maestro->id);
        // }
        // return Response::json($respuesta1);
    }

    public function nd_store(){
        $tipo_documento_id = $_POST['tipo_documento_id'];
        $resolucion_id     = $_POST['resolucion_id'];
        $fecha_emision     = $_POST['fecha_emision'];
        $serie             = $_POST['serie'];
        $correlativo       = $_POST['correlativo'];
        $condicion         = $_POST['condicion'];
        //$banco_id          = $_POST['banco_id'];
        //$cheque_no         = $_POST['cheque_no'];
        $paciente_id       = $_POST['paciente_id'];
        //$motivo_id         = $_POST['motivo_id'];
        //$otros_cobros      = $_POST['otros_cobros'];
        //$observaciones     = $_POST['observaciones'];
        $nit               = $_POST['nit'];
        $nombre            = $_POST['nombre'];
        $direccion         = $_POST['direccion'];
        $recibo_id         = $_POST['recibo_id'];

        $data = (array) json_decode($_POST['local_db'], true);
        $totalRegistros = count($data);

        $tipo_documento = TipoDocumento::findOrFail($tipo_documento_id);

        /*======================================================================
        Creacion de encabezado de nota de debito
        ======================================================================*/
        $maestro = new DocumentoMaestro();
        $maestro->empresa_id       = Auth::user()->empresa_id;
        $maestro->caja_id          = Auth::user()->caja_id;
        $maestro->tipodocumento_id = $tipo_documento_id;
        $maestro->resolucion_id    = $resolucion_id;
        $maestro->fecha_emision    = $fecha_emision;
        $maestro->serie            = $serie;
        $maestro->correlativo      = $correlativo;
        $maestro->paciente_id      = $paciente_id;
        $maestro->condicion        = $condicion;
        $maestro->nit              = $nit;
        $maestro->nombre           = $nombre;
        $maestro->direccion        = $direccion;
        $maestro->estado           = 1;
        $maestro->save();

        /*======================================================================
        Creacion detalle de nota de debito
        ======================================================================*/
        for ($i=0; $i < $totalRegistros ; $i++) {
            $detalle = new DocumentoDetalle();
            $detalle->documentoventa_maestro_id = $maestro->id;
            $detalle->admision_cargo_id         = 0;
            $detalle->producto_id               = $data[$i]['producto_id'];
            $detalle->unidad_medida_id          = $data[$i]['unidad_medida_id'];
            $detalle->descripcion               = $data[$i]['descripcion'];
            $detalle->signo                     = $tipo_documento->signo;
            $detalle->cantidad                  = $data[$i]['cantidad'];
            $detalle->precio_unitario           = $data[$i]['precio_unitario'];
            $detalle->precio_bruto              = $data[$i]['precio_total'];
            $detalle->descuento                 = 0;
            $detalle->recargo                   = 0;
            $detalle->precio_neto               = $data[$i]['precio_total'];
            $detalle->precio_base               = intval($data[$i]['precio_total'])/1.12;
            $detalle->precio_impuesto           = $detalle->precio_neto - $detalle->precio_base;
            $detalle->estado                    = 1;
            $detalle->save();
        }
        
        /*======================================================================
        Marcar cheque como rechazado
        ======================================================================*/
        $marcar_cheque = PagoDetalle::where('pago_maestro_id', $recibo_id)->first();
        $marcar_cheque->estado = 'R';
        $marcar_cheque->save();

        $respuesta = array('parametro' => 0,'respuesta' => 'Nota de Debito '.$serie.'-'.$correlativo.' Grabada con exito !!!', 'nd_id' => $maestro->id);

        return Response::json($respuesta);
    }

    public function factura_edit($id){
        $id               = Crypt::decrypt($id);

        $hoy        = Carbon::now()->format('Y-m-d');
        $listado    = MotivoAnulacion::where('estado', 1)->get();
        $documento  = TipoDocumento::where('tipo_interno', 'VT')->where('estado', 1)->get();
        $caja       = Caja::where('id', Auth::user()->caja_id)->first();
        $pacientes  = Paciente::all();
        $productos  = Producto::where('estado', '1')->get();
        $formas_pago = FormaPago::where('estado', 'A')->get();
        $motivos_anulacion = MotivoAnulacion::where('estado', 1)->get();
        //$encabezado = DocumentoMaestro::findOrFail($id);
        $encabezado = DB::table('documentoventa_maestros as dm')
                      ->join('tipo_documentos as td', 'dm.tipodocumento_id', 'td.id')
                      ->join('documentoventa_detalles as dd', 'dm.id', 'dd.documentoventa_maestro_id')
                      ->leftjoin('motivo_anulaciones as ma', 'dm.motivoanulacion_id', 'ma.id')
                      ->leftjoin('detalle_movimientos as dms', 'dd.detalle_movimiento_id', 'dms.id')
                      ->leftjoin(DB::raw('(SELECT admisiones.id as admision_id, admision_no, pacientes.id as paciente_id, 
                                                  pacientes.nombre_completo as nombre_completo 
                                           FROM admisiones 
                                           JOIN pacientes on admisiones.paciente_id = pacientes.id) Admision'),
                        function($j){
                            $j->on('dms.admision_id', '=', 'Admision.admision_id');
                        })
                      ->where('dm.id', $id)
                      ->select('dm.id', 'dm.tipodocumento_id', 'td.descripcion as tipodocumento_descripcion', 'dm.serie', 'dm.correlativo', 'dm.fecha_emision', 'dm.nit', 'dm.nombre', 'dm.direccion', 'dm.condicion', 'Admision.admision_id', 'Admision.nombre_completo', 'dm.resolucion_id', 'dm.caja_id', 'dm.id', 'dm.estado', 'Admision.paciente_id', 'dm.corte_id', 'dm.motivoanulacion_id', 'dm.observacion_anulacion', 'dm.anulacion_usuario_id', 'dm.fecha_anulacion', 'ma.descripcion as motivoanulacion_descripcion', 'dm.email', 'dms.admision_id',DB::raw('IFNULL(Admision.admision_no,0) as no_admision'))
                      ->first();

        if (isset($encabezado->admision_id)) {
            if ($encabezado->admision_id != 0) {
                $admision = Admision::where('id', $encabezado->admision_id)->select('id', 'admision_no', 'aseguradora_id')->first();
                $admision_id    = $admision->id;
                $no_admision    = $admision->admision_no;
                $aseguradora_id = $admision->aseguradora_id;
            }else{
                $admision_id    = 0;
                $no_admision    = 0;
                $aseguradora_id = 0;
            }
        }else{
            $admision_id    = 0;
            $no_admision    = 0;
            $aseguradora_id = 0;
        }

        $detalle = DB::table('detalle_movimientos as dm')
                   ->join('documentoventa_detalles as dd', 'dm.id', 'dd.detalle_movimiento_id')
                   ->join('unidad_medidas as um', 'dm.unidad_medida_id', 'um.id')
                   ->where('dd.documentoventa_maestro_id', $id)
                   ->select('dm.producto_id', 'dm.descripcion as producto_descripcion', 'um.descripcion as unidad_medida_descripcion', 'dd.cantidad', 'dd.precio_unitario', 'dd.precio_neto')
                   ->get();
                  
        $pago       = DB::table('pago_documentos as pd')
                      ->join('pago_detalles as pds', 'pd.pago_maestro_id', 'pds.pago_maestro_id')
                      ->join('formas_pago as fp', 'pds.forma_pago', 'fp.id')
                      ->leftjoin('bancos as b', 'pds.banco_id', 'b.id')
                      ->select('pd.id', 'pds.forma_pago', 'fp.descripcion as forma_pago_descripcion', 'b.nombre as banco_descripcion', 'pds.cuenta_no', 'pds.documento_no', 'pds.autoriza_no', 'pds.monto', 'pds.estado')
                      ->where('pd.documentoventa_id', $id)
                      ->where('pds.estado', 1)
                      ->get();
        
        $totalDetalle = DB::table('documentoventa_detalles as dd')
                        ->select(DB::raw('SUM(dd.precio_neto) as sum_precio_neto'))
                        ->where('dd.documentoventa_maestro_id', $id)
                        ->first();

        $totalPago   = DB::table('pago_documentos as pd')
                       ->join('pago_detalles as pds', 'pd.pago_maestro_id', 'pds.pago_maestro_id')
                       ->select(DB::raw('SUM(pds.monto) as sum_monto'))
                       ->where('pd.documentoventa_id', $id)
                       ->where('pds.estado', 1)
                       ->first();

        return view('ventas.factura_edit', compact('encabezado', 'detalle', 'pago', 'caja', 'pacientes', 'listado', 'totalDetalle', 'totalPago', 'hoy', 'documento', 'admision_id', 'no_admision', 'aseguradora_id', 'productos', 'formas_pago', 'motivos_anulacion'));
    }

    public function nc_edit($id){
        $id = Crypt::decrypt($id);
        $hoy        = Carbon::now()->format('Y-m-d');
        $encabezado = DocumentoMaestro::findOrFail($id);
        $caja       = Caja::where('id', Auth::user()->caja_id)->first();
        $detalle    = DB::table('detalle_movimientos as dm')
                      ->join('documentoventa_detalles as dd', 'dm.id', 'dd.detalle_movimiento_id')
                      ->join('unidad_medidas as um', 'dm.unidad_medida_id', 'um.id')
                      ->where('dd.documentoventa_maestro_id', $id)
                      ->select('dm.producto_id', 'dm.descripcion as producto_descripcion', 'dm.unidad_medida_id', 'um.descripcion as unidad_medida_descripcion', 'dd.cantidad', 'dd.precio_unitario', 'dd.precio_neto')
                      ->get();
        $productos = Producto::where('estado', '1')->get();

        // $detalle    = DB::table('documentoventa_detalles as dd')
        //               ->join('unidad_medidas as um', 'dm.unidad_medida_id', 'um.id')
        //               ->where('dd.documentoventa_maestro_id', $id)
        //               ->select('dd.producto_id', 'dm.unidad_medida_id', 'dd.descripcion as producto_descripcion', 'um.descripcion as unidad_medida_descripcion', 'dd.cantidad', 'dd.precio_unitario', 'precio_neto')
        //               ->get();
        $total      = DocumentoDetalle::where('documentoventa_maestro_id', $id)->sum('precio_neto');
        $documento  = TipoDocumento::findOrFail($encabezado->tipodocumento_id);
        $pacientes  = Paciente::all();
        $listado    = MotivoAnulacion::where('estado', 1)->get();
        return view('ventas.nc_edit', compact('documento', 'encabezado', 'detalle', 'listado', 'pacientes', 'productos', 'total', 'hoy', 'caja'));
    }

    public function nd_edit($id){
        $hoy        = Carbon::now()->format('Y-m-d');
        $bancos     = Banco::where('tipo_referencia', 'B')->where('estado', 'A')->get();
        $encabezado = DocumentoMaestro::findOrFail($id);
        $caja       = Caja::where('id', Auth::user()->caja_id)->first();
        $detalle    = DB::table('documentoventa_detalles as dd')
                      ->join('unidad_medidas as um', 'dm.unidad_medida_id', 'um.id')
                      ->where('dd.documentoventa_maestro_id', $id)
                      ->select('dd.producto_id', 'dm.unidad_medida_id', 'dd.descripcion as producto_descripcion', 'um.descripcion as unidad_medida_descripcion', 'dd.cantidad', 'dd.precio_unitario', 'precio_neto')
                      ->get();
        $listado    = MotivoAnulacion::where('estado', 1)->get();
        $total      = DocumentoDetalle::where('documentoventa_maestro_id', $id)->sum('precio_neto');
        $documento  = TipoDocumento::findOrFail($encabezado->tipodocumento_id);
        return view('ventas.nd_edit', compact('documento', 'encabezado', 'detalle', 'listado', 'total', 'caja', 'bancos', 'hoy'));   
    }

    public function documentos_con_saldo(){

        $paciente_id = $_POST['paciente_id'];
        $serie       = strtoupper($_POST['serie']);
        $correlativo = $_POST['correlativo'];

        $detalle = DB::table('documentoventa_detalles as dvd')
                   ->groupBy('dvd.documentoventa_maestro_id')
                   ->select('dvd.documentoventa_maestro_id', DB::raw('SUM(dvd.precio_neto) AS total'));

        $pago = DB::table('pago_documentos as pd')
                ->where('pd.estado', 1)
                ->groupBy('pd.documentoventa_id')
                ->select('pd.documentoventa_id', DB::raw('SUM(IFNULL(monto_aplicado,0)) AS monto_aplicado'));

        $listado = DB::table('documentoventa_maestros as dvm')
                   ->join('tipo_documentos as td', 'dvm.tipodocumento_id', 'td.id')
                   ->JoinSub($detalle, 'det', function($join){
                        $join->on('dvm.id', '=', 'det.documentoventa_maestro_id');
                    })
                   ->leftJoinSub($pago, 'pd', function($join){
                        $join->on('dvm.id', '=', 'pd.documentoventa_id');
                    })
                   ->select('dvm.id', 'dvm.tipodocumento_id', 'td.descripcion', 'dvm.serie', 'dvm.correlativo', DB::raw('DATE_FORMAT(dvm.fecha_emision, "%Y-%m-%d") as fecha_emision'), 'dvm.nit', 'dvm.nombre', 'det.total', DB::raw('IFNULL(pd.monto_aplicado,0) as monto_aplicado'), DB::raw('det.total - IFNULL(pd.monto_aplicado,0)as saldo')
                    )
                   ->where('dvm.empresa_id', Auth::user()->empresa_id)
                   ->when($paciente_id, function ($query, $paciente_id) {
                        return $query->where('dvm.paciente_id', $paciente_id);
                    })
                   ->when($serie, function ($query, $serie) {
                        return $query->where('dvm.serie', $serie);
                    })
                    ->when($correlativo, function ($query, $correlativo) {
                        return $query->where('dvm.correlativo', $correlativo);
                    })
                   ->having('saldo', '!=', 0)
                   ->get();
            // dd($listado);
        return Response::json($listado);
        
    }

    function documento_anular(){
        $documento_id = $_POST['documento_id'];
        $motivo_id    = $_POST['motivo_id'];
        $observacion  = $_POST['observacion'];

        $encabezado = DocumentoMaestro::findOrFail($documento_id);

        $tipo_documento = TipoDocumento::findOrFail($encabezado->tipodocumento_id);
        $encabezado->estado                = 2;
        $encabezado->motivoanulacion_id    = $motivo_id;
        $encabezado->observacion_anulacion = $observacion;
        $encabezado->anulacion_usuario_id  = Auth::user()->id;
        $encabezado->fecha_anulacion       = Carbon::now();
        $encabezado->save();

        $detalle     = DocumentoDetalle::where('documentoventa_maestro_id', $documento_id)->update(['estado' => 2]);

        $movimiento = DetalleMovimiento::where('maestro_documento_id', $documento_id)->update(['maestro_documento_id' => null]);

        $pagos = PagoDocumento::where('documentoventa_id', $documento_id)->update(['estado' => 2]);

        $respuesta = array('parametro' => 0, 'respuesta' => $tipo_documento->descripcion.' '.$encabezado->serie.'-'.$encabezado->correlativo.' Anulado con exito !!!');
 
        // return Response::json($respuesta);
        $message = array(
            'message' => $tipo_documento->descripcion.' '.$encabezado->serie.'-'.$encabezado->correlativo.' Anulado con exito !!!',
            'type'    => 'success'
        );

        return redirect()->back()->with($message);
    }

    public function documento_renumerar(){
        $documento_id      = $_POST['documento_id'];
        $tipodocumento_id  = $_POST['tipodocumento_id'];
        $nueva_serie       = $_POST['nueva_serie'];
        $nuevo_correlativo = $_POST['nuevo_correlativo'];

        $Caja_id    = Auth::user()->caja_id;
        $Caja       = Caja::findOrFail($Caja_id);
        $tipo_documento = TipoDocumento::findOrFail($tipodocumento_id);

        $existe = DocumentoMaestro::where('empresa_id', Auth::user()->empresa_id)
                  ->where('tipodocumento_id', $tipodocumento_id)
                  ->where('serie', $nueva_serie)
                  ->where('correlativo', $nuevo_correlativo)
                  ->count();

        if ($existe == 0) {
            $Resolucion = CajaResolucion::where('tipo_documento_id', $tipodocumento_id)
                          ->where('estado', 1)
                          ->where('caja_id', $Caja_id)
                          ->where('serie', strtoupper($nueva_serie))
                          ->where('correlativo_inicial' ,'<=', $nuevo_correlativo)
                          ->where('correlativo_final' ,'>=', $nuevo_correlativo)
                          ->first();

            if (!empty($Resolucion)) {
                $Factura = DocumentoMaestro::findOrFail($documento_id);
                $Factura->resolucion_id = $Resolucion->id;
                $Factura->serie         = $nueva_serie;
                $Factura->correlativo   = $nuevo_correlativo;
                $Factura->save();

                $respuesta = array('parametro' => 0, 'respuesta' => $tipo_documento->descripcion.' '.$Factura->serie.'-'.$Factura->correlativo.' Actualizada con exito');
            }else{
                $respuesta = array('parametro' => 1, 'respuesta' => $Caja->nombre_maquina.' No cuenta con una resolucion activa que permita emitir el documento, favor Verifique');
            }
        }else{
            $respuesta = array('parametro' => 1, 'respuesta' => $tipo_documento->descripcion.' '.$nueva_serie.'-'.$nuevo_correlativo.' Ya se encuentra grabado en nuestros registros, Favor verifique');
        }

        return Response::json($respuesta);
    }

    public function documento_refacturar(){
        $documento_id      = $_POST['documento_id'];

        $paciente_id       = $_POST['paciente_id'];
        $tipodocumento_id  = $_POST['tipodocumento_id'];
        $nueva_fecha       = $_POST['nueva_fecha'];
        $nueva_serie       = strtoupper($_POST['nueva_serie']);
        $nuevo_correlativo = $_POST['nuevo_correlativo'];
        $nueva_condicion   = $_POST['nueva_condicion'];
        $nuevo_nit         = $_POST['nuevo_nit'];
        $nuevo_nombre      = $_POST['nuevo_nombre'];
        $nueva_direccion   = $_POST['nueva_direccion'];
        $motivo_id         = $_POST['motivo_id'];
        $observaciones     = $_POST['observaciones'];

        $tipo_documento = TipoDocumento::findOrFail($tipodocumento_id);
        $caja_id        = Auth::user()->caja_id;
        $caja           = Caja::findOrFail($caja_id);
        $resolucion     = CajaResolucion::where('tipo_documento_id', $tipodocumento_id)
                          ->where('estado', 1)
                          ->where('caja_id', $caja_id)
                          ->where('serie', $nueva_serie)
                          ->where('correlativo_inicial' ,'<=', $nuevo_correlativo)
                          ->where('correlativo_final' ,'>=', $nuevo_correlativo)
                          ->first();

        $Existe = DocumentoMaestro::where('empresa_id', Auth::user()->empresa_id)
                  ->where('serie', $nueva_serie)
                  ->where('correlativo', $nuevo_correlativo)->count();

        $facturaAnterior = DocumentoMaestro::findOrFail($documento_id);

        //$respuesta = array('parametro' => 0, 'respuesta' => $documento_id);
        if ($Existe == 0) {
            if (!empty($resolucion)) {
                $factura = new DocumentoMaestro();
                $factura->empresa_id = Auth::user()->empresa_id;
                $factura->caja_id    = Auth::user()->caja_id;
                if (empty($paciente_id)) {
                    $factura->paciente_id  = null;
                }else{
                    $factura->paciente_id      = $paciente_id;
                }
                
                $factura->tipodocumento_id = $tipodocumento_id;
                $factura->resolucion_id    = $resolucion->id;
                $factura->fecha_emision    = $nueva_fecha;
                $factura->serie            = $nueva_serie;
                $factura->correlativo      = $nuevo_correlativo;
                $factura->condicion        = $nueva_condicion;
                $factura->nit              = $nuevo_nit;
                $factura->nombre           = $nuevo_nombre;
                $factura->direccion        = $nueva_direccion;
                $factura->estado           = 1;
                $factura->tipodocumentoafecto_id = $facturaAnterior->tipodocumentoafecto_id;
                $factura->serie_afecta           = $facturaAnterior->serie_afecta;
                $factura->correlativo_afecto     = $facturaAnterior->correlativo_afecto;
                $factura->save();

                //Actualiza la resolucion
                $factura_resolucion = CajaResolucion::findOrFail($resolucion->id);
                $factura_resolucion->ultimo_correlativo = $nuevo_correlativo;
                $factura_resolucion->save();

                $movimientos = DetalleMovimiento::where('maestro_documento_id', $documento_id)->get();

                foreach ($movimientos as $m) {
                    $mov = DetalleMovimiento::findOrFail($m->id);
                    $mov->maestro_documento_id = $factura->id;
                    $mov->save();
                }

                $detalles = DocumentoDetalle::where('documentoventa_maestro_id', $documento_id)->get();

                foreach ($detalles as $d) {
                    $detalle = new DocumentoDetalle();
                    $detalle->documentoventa_maestro_id = $factura->id;
                    $detalle->admision_cargo_id         = $d->admision_cargo_id;
                    $detalle->producto_id               = $d->producto_id;
                    $detalle->unidad_medida_id          = $d->unidad_medida_id;
                    $detalle->descripcion               = $d->descripcion;
                    $detalle->signo                     = $tipo_documento->signo;
                    $detalle->cantidad                  = $d->cantidad;
                    $detalle->precio_unitario           = $d->precio_unitario;
                    $detalle->precio_bruto              = $d->precio_bruto;
                    $detalle->descuento                 = $d->descuento;
                    $detalle->recargo                   = $d->recargo;
                    $detalle->precio_neto               = $d->precio_neto;
                    $detalle->precio_base               = $d->precio_base;
                    $detalle->precio_impuesto           = $d->precio_impuesto;
                    $detalle->estado                    = $d->estado;
                    $detalle->save();
                }

                $pagos = PagoDocumento::where('documentoventa_id', $documento_id)->get();

                foreach ($pagos as $p) {
                    $pago = new PagoDocumento();
                    $pago->documentoventa_id    = $factura->id;
                    $pago->pago_maestro_id      = $p->pago_maestro_id;
                    $pago->saldo_pendiente      = $p->saldo_pendiente;
                    $pago->monto_aplicado       = $p->monto_aplicado;
                    $pago->estado               = $p->estado;
                    $pago->save();
                }


                $respuesta = array('parametro' => 0, 'respuesta' => $tipo_documento->descripcon.' '.$factura->serie.'-'.$factura->correlativo.' Guardada con exito', 'id' => $factura->id);
            }else{
                $respuesta = array('parametro' => 1, 'respuesta' => $caja->nombre_maquina.' No cuenta con una resolucion activa que permita emitir el documento, favor Verifique');
            }
        } else{
            $respuesta = array('parametro' => 1, 'respuesta' => $tipo_documento->descripcion.' '.$nueva_serie.'-'.$nuevo_correlativo.' Ya se encuentra guardada en nuestros registros, Favor verifique');
        }

        //$respuesta = array('parametro' => 0, 'respuesta' => $Existe);
        return Response::json($respuesta);
    }

    public function nc_doctos_aplicar(){

        $tipodocumento_id = $_POST['tipodocumento_id'];
        $serie            = strtoupper($_POST['serie']);
        $correlativo      = $_POST['correlativo'];

        $tipo_documento = TipoDocumento::findOrFail($tipodocumento_id);

        $existe = DocumentoMaestro::where('empresa_id', Auth::user()->empresa_id)
                  ->where('tipodocumento_id', $tipodocumento_id)
                  ->where('serie', $serie)
                  ->where('correlativo', $correlativo)
                  ->where('estado', 1)
                  ->count();

        if ($existe == 0) {
            return response()->json([
                'message' => $tipo_documento->descripcion.' '.$serie.'-'.$correlativo.' No encontrado',
                'type'    => 'error'
            ]);
        }

        //==============================================================================================
        // Verifica si el documento tiene pago realizado
        //==============================================================================================
        // $saldo       = DocumentoHelper::DocumentoSaldo($tipodocumento_id, $serie, $correlativo);
        $saldo = $this->DocumentoSaldo($tipodocumento_id, $serie, $correlativo);
        
        if ($saldo <= 0) {
            return response()->json([
                'message' => 'Documento NO tiene saldo pendiente, Favor verifique !!!',
                'type'    => 'error'
            ]);
        }else{
            $encabezado = DB::table('documentoventa_maestros as dm')
                          ->where('dm.empresa_id', Auth::user()->empresa_id)
                          ->where('dm.tipodocumento_id', $tipodocumento_id)
                          ->where('dm.serie', $serie)
                          ->where('dm.correlativo', $correlativo)
                          ->select('dm.id','dm.paciente_id', 'dm.nit', 'dm.nombre', 'dm.direccion', 'dm.fecha_emision', 'email')
                          ->first();

            $detalle = DB::table('documentoventa_detalles as dd')
                       ->join('detalle_movimientos as dm', 'dd.detalle_movimiento_id', 'dm.id')
                       ->join('unidad_medidas as um', 'dm.unidad_medida_id', 'um.id')
                       ->where('dd.documentoventa_maestro_id', $encabezado->id)
                       ->select('dm.producto_id', 'dm.descripcion', 'dd.cantidad', 'dd.precio_unitario', 'dd.precio_bruto', 'dd.descuento', 'dd.recargo', 'dd.precio_unitario', 'dd.precio_neto', 'dd.precio_base', 'dd.precio_impuesto', 'dd.estado', 'dm.unidad_medida_id', 'um.descripcion as medida_descripcion')
                       ->get();

            return response()->json([
                'message'    => $tipo_documento->descripcion.' '.$serie.'-'.$correlativo.' No encontrado',
                'type'       => 'success',
                'encabezado' => $encabezado,
                'detalle'    => $detalle
            ]);
        }
    }

    public function corte_idx(){
        $empresaId = Auth::user()->empresa_id;

        $listado = DB::table('caja_cortes as cc')
                   ->join('cajas as c', 'cc.caja_id', 'c.id')
                   ->leftjoin('documentoventa_maestros as dm', 'cc.id', 'dm.corte_id')
                   ->leftJoin(DB::raw('(SELECT dd.documentoventa_maestro_id, SUM(dd.precio_neto) as precio_neto
                                        FROM documentoventa_detalles as dd
                                        GROUP BY dd.documentoventa_maestro_id) as detalle'),
                                function($j){
                                    $j->on('dm.id', 'detalle.documentoventa_maestro_id');
                                }
                             )
                   ->where('cc.empresa_id', $empresaId)
                   ->groupBy('cc.id', 'c.nombre_maquina', 'cc.corte', 'cc.fecha', 'cc.created_by')
                   ->select('cc.id', 'c.nombre_maquina as caja_descripcion', 'cc.corte', 'cc.fecha', 'cc.created_by',
                            DB::raw('COUNT(dm.id) as cnt_documentos'),
                            DB::raw('SUM(IFNULL(detalle.precio_neto, 0)) as monto_total_corte')
                           )
                   ->orderBy('cc.fecha', 'DESC')
                   ->get();

        return view('ventas.cortes_idx', compact('listado'));
    }

    public function corte_create(){
        $hoy        = Carbon::now()->format('Y-m-d');
        $caja       = Caja::where('id', Auth::user()->caja_id)->first();
        $cajas      = Caja::where('empresa_id', Auth::user()->empresa_id)->get();
        $documentos = DB::table('tipo_documentos as td')
                      ->select('td.id', 'td.descripcion')
                      ->where('td.estado', 1)
                      ->where('td.tipo_interno', '<>', 'RP')
                      ->whereNotNull('td.orden')
                      ->orderBy('td.orden', 'ASC')
                      ->get();

        $formas_pago = DB::Table('formas_pago as fp')
                       ->select('fp.id', 'fp.descripcion')
                       ->where('fp.estado', 'A')
                       ->where('fp.id', '<>', 5)
                       ->orderBy('fp.id', 'ASC')
                       ->get();

        return view('ventas.corte_create', compact('hoy', 'caja', 'cajas', 'documentos', 'formas_pago'));
    }

    public function trae_resumen_documentos(){
        $fecha   = $_POST['fecha'];
        $caja_id = $_POST['caja_id'];
        $corte_id = $_POST['corte_id'];
        $retorno = [];

        $resumen = DB::table('tipo_documentos as td')
                   ->leftJoinSub(function ($query) use ($caja_id, $fecha, $corte_id) {
                        $query->from('documentoventa_maestros as dm')
                                ->join('documentoventa_detalles as dd', 'dm.id', '=', 'dd.documentoventa_maestro_id')
                                ->select(
                                    'dm.corte_id',
                                    'dm.tipodocumento_id',
                                    DB::raw('IFNULL(SUM(dd.precio_neto * dm.signo), 0) as total'),
                                    DB::raw('SUM(IFNULL(dd.precio_neto, 0)) as totalsinsigno')
                                )
                                ->where('dm.empresa_id', Auth::user()->empresa_id)
                                ->where('dm.caja_id', $caja_id)
                                ->where('dm.fecha_emision', $fecha)
                                ->where('dm.estado', 1)
                                // AQUÍ APLICAMOS LA LÓGICA DINÁMICA
                                ->when($corte_id, function ($q) use ($corte_id) {
                                    return $q->where('dm.corte_id', $corte_id);
                                }, function ($q) {
                                    return $q->whereNull('dm.corte_id'); // Si no hay ID, que busque los NULL
                                })
                                ->groupBy('dm.corte_id', 'dm.tipodocumento_id');
                        }, 'detalle', 'td.id', '=', 'detalle.tipodocumento_id'
                    )
                   ->where('td.tipo_interno', '<>', 'RP')
                   ->select('td.id', 'td.descripcion', 'td.signo', 'detalle.total', 'td.orden', 'detalle.totalsinsigno')
                   ->whereNotNull('td.orden')
                   ->orderBy('td.orden', 'ASC')
                   ->get();

        array_push($retorno, $resumen);

        $resumen = DB::table('formas_pago as fp')
                    ->leftJoinSub(function ($query) use ($caja_id, $fecha, $corte_id) {
                        $query->from('pago_maestros as pm')
                            ->join('pago_detalles as pd', 'pm.id', '=', 'pd.pago_maestro_id')
                            ->select(
                                'pd.forma_pago',
                                DB::raw('SUM(IFNULL(pd.monto, 0)) as total')
                            )
                            ->where('pm.empresa_id', Auth::user()->empresa_id)
                            ->where('pm.caja_id', $caja_id)
                            ->where('pm.fecha_emision', $fecha)
                            ->where('pm.estado', 1)
                            // Lógica dinámica para el corte
                            ->when($corte_id, function ($q) use ($corte_id) {
                                return $q->where('pm.caja_corte_id', $corte_id);
                            }, function ($q) {
                                return $q->whereNull('pm.caja_corte_id');
                            })
                            ->groupBy('pd.forma_pago');
                    }, 'detalle', 'fp.id', '=', 'detalle.forma_pago')
                    ->where('fp.recibos', 'N')
                    ->select('fp.id', 'fp.descripcion', DB::raw('IFNULL(detalle.total, 0) as total'))
                    ->orderBy('fp.id')
                    ->get();

        array_push($retorno, $resumen);

        $detalle = DB::table('documentoventa_maestros as dm')
                   ->join('tipo_documentos as td', 'dm.tipodocumento_id', 'td.id')
                   ->leftjoin(DB::raw('(SELECT dd.documentoventa_maestro_id, 
                                               SUM(IFNULL(dd.precio_neto,0)) as total
                                        FROM documentoventa_detalles as dd
                                        GROUP BY dd.documentoventa_maestro_id) as detalle'
                                      ),
                             function($j){
                                $j->on('dm.id', '=', 'detalle.documentoventa_maestro_id');
                             }
                             )
                   ->where('dm.empresa_id', Auth::user()->empresa_id)
                   ->where('dm.caja_id', $caja_id)
                   ->whereDate('dm.fecha_emision', $fecha)
                   ->where('dm.estado', 1)
                   ->when($corte_id, function ($q) use ($corte_id) {
                            return $q->where('dm.corte_id', $corte_id);
                        }, function ($q) {
                            return $q->whereNull('dm.corte_id');
                        })
                   ->select('td.descripcion as tipodocumento_descripcion', 'dm.serie', 'dm.correlativo', DB::raw('date_format(dm.fecha_emision, "%d/%m/%Y") as fecha_emision'), 'dm.nit', 'dm.nombre', 'dm.direccion', DB::raw('(detalle.total * dm.signo) total'), 'detalle.total as totalsinsigno')
                   ->orderBy('td.descripcion', 'ASC', 'dm.serie', 'ASC', 'dm.correlativo', 'ASC')
                   ->get();

        array_push($retorno, $detalle);

        $detalle = DB::table('pago_maestros as pm')
                   ->join('pago_documentos as pd', 'pm.id', 'pd.pago_maestro_id')
                   ->join('documentoventa_maestros as dm', 'pd.documentoventa_id', 'dm.id')
                   ->join('tipo_documentos as td', 'dm.tipodocumento_id', 'td.id')
                   ->where('pm.empresa_id', Auth::user()->empresa_id)
                   ->where('pm.caja_id', $caja_id)
                   ->whereDate('pm.fecha_emision', $fecha)
                   ->where('pm.estado', 1)
                   ->when($corte_id, function ($q) use ($corte_id) {
                            return $q->where('pm.caja_corte_id', $corte_id);
                        }, function ($q) {
                            return $q->whereNull('pm.caja_corte_id');
                        })
                   ->select('pm.serie as recibo_serie', 'pm.correlativo as recibo_correlativo', DB::raw('date_format(pm.fecha_emision, "%d/%m/%Y") as fecha_emision'), 'pm.estado', 'td.descripcion', 'dm.serie', 'dm.correlativo', 'dm.fecha_emision as fecha_emision_factura', 'dm.nit', 'dm.nombre', 'pd.monto_aplicado')
                   ->get();

        array_push($retorno, $detalle);

        return Response::json($retorno);


    }
    // public function trae_resumen_documentos(){
    //     $fecha   = $_POST['fecha'];
    //     $caja_id = $_POST['caja_id'];

    //     $resumen = DB::table('tipo_documentos as td')
    //                ->leftjoin(DB::raw('(SELECT dm.tipodocumento_id, IFNULL(SUM(dd.precio_neto * dm.signo), 0) as total,
    //                                            SUM(IFNULL(dd.precio_neto, 0)) as totalsinsigno
    //                                     FROM documentoventa_maestros as dm
    //                                     JOIN documentoventa_detalles as dd on dm.id = dd.documentoventa_maestro_id
    //                                     WHERE dm.empresa_id = '.Auth::user()->empresa_id.'
    //                                       AND dm.caja_id = '.$caja_id.'
    //                                       AND dm.fecha_emision = "'.$fecha.'"
    //                                       AND dm.estado = "A"
    //                                       AND dm.corte_id is null
    //                                     GROUP BY dm.tipodocumento_id) as detalle'
    //                               ),
    //                         function($j){
    //                             $j->on('td.id', '=', 'detalle.tipodocumento_id');
    //                         }
    //                       )
    //                ->where('td.tipo_interno', '<>', 'RP')
    //                ->select('td.id', 'td.descripcion', 'detalle.total', 'td.orden', 'detalle.totalsinsigno')
    //                ->orderBy('td.orden', 'ASC')
    //                ->get();

    //     return Response::json($resumen);
    // }

    public function trae_resumen_credito(){
        $fecha   = $_POST['fecha'];
        $caja_id = $_POST['caja_id'];

        $total = DB::table('documentoventa_maestros as dm')
                 ->join('documentoventa_detalles as dd', 'dm.id', 'dd.documentoventa_maestro_id')
                 ->leftjoin(DB::raw('(SELECT pd.documentoventa_id, 
                                             SUM(monto_aplicado) as total
                                      FROM pago_documentos as pd
                                      WHERE estado = "A"
                                      GROUP BY pd.documentoventa_id) as pago'
                                    ),
                                function($j){
                                    $j->on('dm.id', '=', 'pago.documentoventa_id');
                                }
                            )
                 ->where('dm.empresa_id', Auth::user()->empresa_id)
                 ->where('dm.caja_id', $caja_id)
                 ->whereNull('dm.corte_id')
                 ->where('dm.condicion',1)
                 ->whereDate('dm.fecha_emision', $fecha)
                 ->select(DB::raw('SUM(IFNULL(dd.precio_neto,0)) as total'))
                 ->first();
        

        /*$total = DB::table('documentoventa_maestros as dm')
                 ->join('documentoventa_detalles as dd', 'dm.id', 'dd.documentoventa_maestro_id')
                 ->leftjoin(DB::raw('(SELECT pd.documentoventa_id, 
                                             SUM(monto_aplicado) as total
                                      FROM pago_documentos as pd
                                      WHERE estado = "A"
                                      GROUP BY pd.documentoventa_id) as pago'
                                    ),
                                function($j){
                                    $j->on('dm.id', '=', 'pago.documentoventa_id');
                                }
                            )
                 ->where('dm.empresa_id', Auth::user()->empresa_id)
                 ->where('dm.caja_id', $caja_id)
                 ->whereNull('dm.corte_id')
                 ->where('dm.condicion',1)
                 ->whereDate('dm.fecha_emision', $fecha)
                 ->select(DB::raw('SUM(IFNULL(dd.precio_neto,0)) as total'), 'pago.total')
                 ->groupBy('pago.total')
                 ->first();*/


                 //print_r($total);

        return Response::json($total);
    }

    public function trae_detalle_documentos(){
        $fecha   = $_POST['fecha'];
        $caja_id = $_POST['caja_id'];

        $detalle = DB::table('documentoventa_maestros as dm')
                   ->join('tipo_documentos as td', 'dm.tipodocumento_id', 'td.id')
                   ->leftjoin(DB::raw('(SELECT dd.documentoventa_maestro_id, 
                                               SUM(IFNULL(dd.precio_neto,0) * td.signo) as total,
                                               SUM(IFNULL(dd.precio_neto,0)) as totalsinsigno
                                        FROM documentoventa_detalles as dd
                                        GROUP BY dd.documentoventa_maestro_id) as detalle'
                                      ),
                             function($j){
                                $j->on('dm.id', '=', 'detalle.documentoventa_maestro_id');
                             }
                             )
                   ->where('dm.empresa_id', Auth::user()->empresa_id)
                   ->where('dm.caja_id', $caja_id)
                   ->whereDate('dm.fecha_emision', $fecha)
                   ->where('dm.estado', 1)
                   ->whereNull('dm.corte_id')
                   ->select('td.descripcion as tipodocumento_descripcion', 'dm.serie', 'dm.correlativo', 'dm.fecha_emision', 'dm.nit', 'dm.nombre', 'dm.direccion', 'detalle.total', 'detalle.totalsinsigno')
                   ->orderBy('td.descripcion', 'ASC', 'dm.serie', 'ASC', 'dm.correlativo', 'ASC')
                   ->get();

        //print_r($detalle);


        /*$detalle = DB::table('vw_venta_documentos as vvd')
                   ->where('vvd.empresa_id', Auth::user()->empresa_id)
                   ->where('vvd.caja_id', $caja_id)
                   ->whereDate('vvd.fecha_emision', $fecha)
                   ->where('vvd.corte_id',null)
                   ->select('tipodocumento_descripcion','serie', 'correlativo', 'fecha_emision', 'nit', 'nombre', 'total_documento')
                   ->get();*/
        return Response::json($detalle);
    }

    public function trae_resumen_pagos(){
        $fecha   = $_POST['fecha'];
        $caja_id = $_POST['caja_id'];

        $resumen = DB::table('formas_pago as fp')
                   ->leftjoin(DB::raw('(SELECT pd.forma_pago, SUM(IFNULL(pd.monto, 0)) as total
                                        FROM pago_maestros pm
                                        JOIN pago_detalles pd on pm.id = pd.pago_maestro_id
                                        WHERE pm.empresa_id = '.Auth::user()->empresa_id.'
                                          AND pm.caja_id = '.$caja_id.'
                                          AND pm.fecha_emision = "'.$fecha.'"
                                          AND pm.estado = "A"
                                          AND pm.caja_corte_id is null
                                        GROUP BY pd.forma_pago  
                                        ) detalle'),
                                function($j){
                                    $j->on('fp.id', '=', 'Detalle.forma_pago');
                                })
                    ->where('fp.recibos', 'N')
                    ->select('fp.id', 'fp.descripcion', 'detalle.total')
                    ->orderBy('fp.id')
                    ->get();

        return Response::json($resumen);

        /*$fecha   = $_POST['fecha'];
        $caja_id = $_POST['caja_id'];

        $resumen = DB::table('formas_pago as fp')
                   ->distinct()
                   ->leftjoin('vw_forma_pago_documentos as vfpd', function($join) use($fecha, $caja_id){
                        $join->on('fp.id', 'vfpd.forma_pago')
                        ->where('vfpd.empresa_id', Auth::user()->empresa_id)
                        ->where('vfpd.caja_id', '=', $caja_id)
                        ->where('vfpd.fecha_emision', '=', $fecha)
                        ->where('vfpd.corte_id',null);
                   })
                    ->groupBy('fp.id', 'fp.descripcion')
                    ->select('fp.id', 'fp.descripcion', DB::raw('SUM(IFNULL(vfpd.total_forma_pago,0)) as total'))
                   ->get();
        return Response::json($resumen);*/
    }

    public function trae_detalle_pagos(){
        $fecha   = $_POST['fecha'];
        $caja_id = $_POST['caja_id'];

        $detalle = DB::table('pago_maestros as pm')
                   ->join('pago_documentos as pd', 'pm.id', 'pd.pago_maestro_id')
                   ->join('documentoventa_maestros as dm', 'pd.documentoventa_id', 'dm.id')
                   ->join('tipo_documentos as td', 'dm.tipodocumento_id', 'td.id')
                   ->where('pm.empresa_id', Auth::user()->empresa_id)
                   ->where('pm.caja_id', $caja_id)
                   ->whereDate('pm.fecha_emision', $fecha)
                   ->where('pm.estado', 1)
                   ->whereNull('pm.caja_corte_id')
                   ->select('pm.serie as recibo_serie', 'pm.correlativo as recibo_correlativo', 'pm.fecha_emision', 'pm.estado', 'td.descripcion', 'dm.serie', 'dm.correlativo', 'dm.fecha_emision', 'dm.nit', 'dm.nombre', 'pd.monto_aplicado')
                   ->get();

        return Response::json($detalle);

        /*$fecha   = $_POST['fecha'];
        $caja_id = $_POST['caja_id'];

        $detalle = DB::table('vw_corte_caja_recibos as vccr')
                   ->where('vccr.empresa_id', Auth::user()->empresa_id)
                   ->where('vccr.caja_id', $caja_id)
                   ->whereDate('vccr.fecha_emision', $fecha)
                   ->where('vccr.corte_id',null)
                   ->select('serie', 'correlativo', 'tipo_admision', 'admision', 'nombre_completo', 'total_recibo', 'efectivo', 'cheque', 'tarjeta', 'transferencia')
                   ->get();
        return Response::json($detalle);*/
    }

    public function corte_store(Request $request){
        $validData = $request->validate([
            'fecha' => 'required',
            'caja_id' => 'required'
        ]);

        try {
            return DB::transaction(function () use ($validData) {
                $empresaId = Auth::user()->empresa_id;
                $maximo = CajaCorte::where('empresa_id', $empresaId)->max('corte') ?? 0;

                // =====================================================================
                // Crea encabezado de corte
                // =====================================================================
                $corte = new CajaCorte();
                $corte->empresa_id = Auth::user()->empresa_id;
                $corte->caja_id    = $validData['caja_id'];
                $corte->corte      = $maximo+1;
                $corte->fecha      = $validData['fecha'];
                $corte->estado     = 1;
                $corte->save();

                // 4. Actualizar Facturas
                // Usamos una variable para saber cuántas se afectaron si fuera necesario
                DocumentoMaestro::where('empresa_id', $empresaId)
                    ->where('caja_id', $validData['caja_id'])
                    ->where('fecha_emision', $validData['fecha'])
                    ->whereNull('corte_id')
                    ->update(['corte_id' => $corte->id]);

                // 5. Actualizar Recibos
                PagoMaestro::where('empresa_id', $empresaId)
                    ->where('caja_id', $validData['caja_id'])
                    ->where('fecha_emision', $validData['fecha'])
                    ->whereNull('caja_corte_id')
                    ->update(['caja_corte_id' => $corte->id]);

                return Redirect::route('editar_corte', Crypt::encrypt($corte->id))
                        ->with(['message' => 'Corte número ' . $corte->corte . ' generado con éxito.', 'type' => 'success']);
            });
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['message' => 'Error al generar el corte: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function corte_edit($id){
        $id = Crypt::decrypt($id);
        $cajas = Caja::where('empresa_id', Auth::user()->empresa_id)->get();
        $corte = CajaCorte::where('id', $id)->select('id', str_pad('corte', 4), 'caja_id', 'fecha', 'estado')
                 ->first();

        $total_pagos   = DB::table('pago_maestros as pm')
                         ->join('pago_detalles as pd', 'pm.id', 'pd.pago_maestro_id')
                         ->where('pm.empresa_id', Auth::user()->empresa_id)
                         ->where('pm.caja_corte_id', $id)
                         ->SUM('pd.monto');

        $total_venta   = DB::table('documentoventa_maestros as dm')
                         ->join('documentoventa_detalles as dd', 'dm.id', 'dd.documentoventa_maestro_id')
                         ->where('dm.empresa_id', Auth::user()->empresa_id)
                         ->where('dm.corte_id', $id)
                         ->SUM('dd.precio_neto');

        $total_credito = DB::table('documentoventa_maestros as dm')
                         ->join('documentoventa_detalles as dd', 'dm.id', 'dd.documentoventa_maestro_id')
                         ->where('dm.empresa_id', Auth::user()->empresa_id)
                         ->where('dm.corte_id', $id)
                         ->where('dm.condicion', 1)
                         ->SUM('dd.precio_neto');

        $documentos = DB::table('tipo_documentos as td')
                   ->leftjoin(DB::raw('(SELECT dm.tipodocumento_id, SUM(IFNULL(dd.precio_neto,0)) as total
                                        FROM documentoventa_maestros as dm
                                        JOIN documentoventa_detalles as dd on dm.id = dd.documentoventa_maestro_id
                                        WHERE dm.empresa_id = '.Auth::user()->empresa_id.'
                                          AND dm.corte_id = '.$id.'
                                        GROUP BY dm.tipodocumento_id) as detalle'
                                  ),
                            function($j){
                                $j->on('td.id', '=', 'detalle.tipodocumento_id');
                            }
                          )
                   ->where('td.estado', 1)
                   ->where('td.tipo_interno', '<>', 'RP')
                   ->select('td.id', 'td.descripcion', DB::raw('(IFNULL(detalle.total,0)) as total'), 'td.orden')
                   ->orderBy('td.orden', 'ASC')
                   ->get();

        $formas_pago = DB::table('formas_pago as fp')
                   ->leftjoin(DB::raw('(SELECT pd.forma_pago, SUM(IFNULL(pd.monto, 0)) as total
                                        FROM pago_maestros pm
                                        JOIN pago_detalles pd on pm.id = pd.pago_maestro_id
                                        WHERE pm.empresa_id = '.Auth::user()->empresa_id.'
                                          AND pm.caja_corte_id = '.$id.'
                                        GROUP BY pd.forma_pago  
                                        ) detalle'),
                                function($j){
                                    $j->on('fp.id', '=', 'Detalle.forma_pago');
                                })
                    ->where('fp.recibos', 'N')
                    ->select('fp.id', 'fp.descripcion', DB::raw('(IFNULL(detalle.total,0)) as total'))
                    ->orderBy('fp.id')
                    ->get();

        $detalle_venta = DB::table('documentoventa_maestros as dm')
                   ->join('tipo_documentos as td', 'dm.tipodocumento_id', 'td.id')
                   ->leftjoin(DB::raw('(SELECT dd.documentoventa_maestro_id, 
                                               SUM(IFNULL(dd.precio_neto,0)) as total
                                        FROM documentoventa_detalles as dd
                                        GROUP BY dd.documentoventa_maestro_id) as detalle'
                                      ),
                             function($j){
                                $j->on('dm.id', '=', 'detalle.documentoventa_maestro_id');
                             }
                             )
                   ->where('dm.empresa_id', Auth::user()->empresa_id)
                   ->where('dm.corte_id', $id)
                   ->select('td.descripcion as tipodocumento_descripcion', 'dm.serie', 'dm.correlativo', 'dm.fecha_emision', 'dm.nit', 'dm.nombre', 'dm.direccion', 'detalle.total')
                   ->orderBy('td.descripcion', 'ASC', 'dm.serie', 'ASC', 'dm.correlativo', 'ASC')
                   ->get();

        $detalle_pago = DB::table('pago_maestros as pm')
                   ->join('pago_documentos as pd', 'pm.id', 'pd.pago_maestro_id')
                   ->join('documentoventa_maestros as dm', 'pd.documentoventa_id', 'dm.id')
                   ->join('tipo_documentos as td', 'dm.tipodocumento_id', 'td.id')
                   ->where('pm.empresa_id', Auth::user()->empresa_id)
                   ->where('pm.caja_corte_id', $id)
                   ->select('pm.serie as recibo_serie', 'pm.correlativo as recibo_correlativo', 'pm.fecha_emision', 'pm.estado', 'td.descripcion', 'dm.serie', 'dm.correlativo', 'dm.fecha_emision', 'dm.nit', 'dm.nombre', 'pd.monto_aplicado')
                   ->get();

        return view('ventas.corte_edit', compact('cajas', 'corte', 'documentos', 'formas_pago', 'detalle_venta', 'detalle_pago', 'total_venta', 'total_credito', 'formas_pago'));
    }

    function get_saldo_pendiente_graph(){
        $fecha = $_POST['fecha'];
        $arreglo = [];
        $hoy = Carbon::now()->format('Y-m-d');
        $var30   = 0;
        $var60   = 0;
        $var90   = 0;
        $var120  = 0;
        $varOtro = 0;
        //echo $hoy; die;
        //dd($fecha);

        $resumen = DB::table('documentoventa_maestros as dm')
                   ->join('documentoventa_detalles as dd', 'dm.id', 'dd.documentoventa_maestro_id')
                   ->leftjoin(DB::raw('(SELECT documentoventa_id, SUM(IFNULL(monto_aplicado,0)) as total_pagado
                                        FROM pago_documentos 
                                        WHERE estado = "A"
                                        group by documentoventa_id) as pago'),
                              function($j){
                                  $j->on('dm.id', '=', 'pago.documentoventa_id');
                              }
                             )
                   ->where('dm.empresa_id', Auth::user()->empresa_id)
                   ->where('dm.estado', 1)
                   ->where('dm.fecha_emision', '<', $fecha)
                   ->groupBy(DB::raw('datediff(curdate(), dm.fecha_emision)'), 'pago.total_pagado')
                   ->select(DB::raw('datediff(curdate(), dm.fecha_emision) as dias'), 
                            DB::raw('sum(ifnull(dd.precio_neto,0)) - ifnull(pago.total_pagado,0) as pendiente')
                            )
                   ->get();

        foreach ($resumen as $key => $value) {
            if ($value->dias <= 30) {
                $var30 += $value->pendiente;
            }

            if ($value->dias > 30 && $value->dias <= 60) {
                $var60 += $value->pendiente;
            }

            if ($value->dias > 60 && $value->dias <= 90) {
                $var90 += $value->pendiente;
            }

            if ($value->dias > 90 && $value->dias <= 120) {
                $var120 += $value->pendiente;
            }

            if ($value->dias > 120) {
                $varOtro += $value->pendiente;
            }
        }
        array_push($arreglo, ['dias' => '30', 'pendiente' => $var30]);
        array_push($arreglo, ['dias' => '60', 'pendiente' => $var60]);
        array_push($arreglo, ['dias' => '90', 'pendiente' => $var90]);
        array_push($arreglo, ['dias' => '120', 'pendiente' => $var120]);
        array_push($arreglo, ['dias' => '+ 120', 'pendiente' => $varOtro]);

        return Response::json($arreglo);
    }

    public function get_documento_existe(){
        $empresa_id       = $_POST['empresa_id'];
        $tipodocumento_id = $_POST['tipodocumento_id'];
        $serie            = $_POST['serie'];
        $correlativo      = $_POST['correlativo'];

        return DocumentoMaestro::where('empresa', $empresa_id)
                               ->where('tipodocumento_id', $tipodocumento_id)
                               ->where('serie', $serie)
                               ->where('correlativo', $correlativo)
                               ->exists();
    }
}
