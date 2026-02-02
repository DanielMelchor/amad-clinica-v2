<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Auth;
use DB;
use Response;
use Session;
use Redirect;
use Carbon\Carbon;
use App\Models\Proveedor;
use App\Models\Bodega;
use App\Models\Producto;
use App\Models\ProductoMedida;
use App\Models\MaestroMovimiento;
use App\Models\DetalleMovimiento;

class InvMovimientoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index_compras(){
    	$lista = DB::table('maestro_movimientos as mm')
    	         ->join('proveedores as p', ['mm.empresa_id' => 'p.empresa_id', 'mm.proveedor_id' => 'p.id'])
                 ->join('inventario_transacciones as it', 'mm.inventario_transaccion_id', 'it.id')
    	         ->where('mm.empresa_id', Auth::user()->empresa_id)
                 ->where('it.tipo_transaccion', 'C')
    	         ->select('mm.id','mm.correlativo', 'mm.anio','mm.created_at', 'p.nombre_comercial', 'mm.serie', 'mm.numero_documento', 'mm.total')
    	         ->orderBy('mm.id','DESC')
    	         ->get();
    	return view('inventarios.index_compras', compact('lista'));
    }

    public function index_ajustes(){
        dd('entre');
        $lista = DB::table('maestro_movimientos as mm')
                 ->join('inventario_transacciones as it', 'mm.inventario_transaccion_id', 'it.id')
                 ->where('mm.empresa_id', Auth::user()->empresa_id)
                 ->where('it.tipo_transaccion', 'A')
                 ->select('mm.id','mm.correlativo', 'mm.anio', 'mm.created_at', 'it.descripcion as transaccion_descripcion')
                 ->orderBy('mm.id','DESC')
                 ->get();
        return view('inventarios.index_ajustes', compact('lista'));
    }

    public function create_compra(){
        $hoy             = Carbon::now()->format('Y-m-d');
        $proveedores     = Proveedor::where('empresa_id', Auth::user()->empresa_id)->where('estado', 1)->get();
        $bodegas         = Bodega::where('empresa_id', Auth::user()->empresa_id)->where('estado', 1)->get();
        $tipo_documentos = DB::table('tipo_documentos as td')
                           ->join('inventario_transacciones as it','td.inventario_transaccion_id', 'it.id')
                           ->where('it.empresa_id', Auth::user()->empresa_id)
                           ->select('td.id', 'td.descripcion')
                           ->get();
        return view('inventarios.create_compra', compact('proveedores', 'bodegas', 'hoy', 'tipo_documentos'));  
    }

    public function create_ajuste(){
        $hoy             = Carbon::now()->format('Y-m-d');
        $bodegas         = Bodega::where('empresa_id', Auth::user()->empresa_id)->where('estado', 1)->get();
        return view('inventarios.create_ajuste', compact('bodegas', 'hoy'));
    }

    public function store_compra(Request $request){
        $datos = $request->all();
        // print_r($request['productos']);
        // die;
        $validData = $request->validate([
            'proveedor_id'     => 'required',
            'nit'              => 'required',
            'documento_id'     => 'required',
            'serie'            => 'required',
            'numero_documento' => 'required',
            'fecha_emision'    => 'required',
            'dias_credito'     => 'required',
            'total'            => 'required',
            'bodega_id'        => 'required'
        ]);

        // $proveedor_id    = $_POST['proveedor_id'];
        // $nit             = $_POST['nit'];
        // $documento_id    = $_POST['documento_id'];
        // $serie           = $_POST['serie'];
        // $documento       = $_POST['documento'];
        // $fecha_emision   = $_POST['fecha_emision'];
        // $dias_credito    = $_POST['dias_credito'];
        // $total_documento = $_POST['total'];
        // $bodega_id       = $_POST['bodega_id'];

        $fecha_vencimiento = new Carbon($validData['fecha_emision']);
        $fecha_vencimiento->add($validData['dias_credito'],'day');
        $data            = $request['productos'];
        // $data = $request->input('productos');
        $totalRegistros  = count($request['productos']);
        collect($data)->sortByDesc('id')->take($totalRegistros);

        $inv_transaccion = DB::table('inventario_transacciones as it')
                           //->join('tipo_documentos as td', 'it.id', 'td.inventario_transaccion_id')
                           //->where('td.id', $documento_id)
                           ->where('it.id', 1)
                           ->first();

        $anio = Carbon::now()->format('Y');
        $correlativo = DB::table('maestro_movimientos as mm')
                       ->where('mm.empresa_id', Auth::user()->empresa_id)
                       ->where('mm.inventario_transaccion_id', $inv_transaccion->id)
                       ->where('mm.anio', $anio)
                       ->select(DB::raw('IFNULL(MAX(correlativo),0) as ultimo_correlativo'))
                       ->first();
        
        $nuevo_correlativo = $correlativo->ultimo_correlativo + 1;

        $compra = new MaestroMovimiento();
        $compra->empresa_id                = Auth::user()->empresa_id;
        $compra->inventario_transaccion_id = $inv_transaccion->id;
        $compra->signo                     = $inv_transaccion->signo;
        $compra->correlativo               = $nuevo_correlativo;
        $compra->anio                      = $anio;
        $compra->bodega_origen_id          = $validData['bodega_id'];
        $compra->proveedor_id              = $validData['proveedor_id'];
        $compra->nit                       = strtoupper($validData['nit']);
        $compra->tipo_documento_id         = $validData['documento_id'];
        $compra->serie                     = strtoupper($validData['serie']);
        $compra->numero_documento          = $validData['numero_documento'];
        $compra->fecha_emision             = $validData['fecha_emision'];
        $compra->dias_credito              = $validData['dias_credito'];
        $compra->fecha_vencimiento         = $fecha_vencimiento;
        $compra->total                     = $validData['total'];
        $compra->estado                    = 1;
        $compra->save();


        if ($totalRegistros > 0) {
            for ($i=0; $i < $totalRegistros ; $i++) {
                $producto = producto::findOrFail(intval($data[$i]['articulo_id']));
                $producto_medida = ProductoMedida::where('producto_id', $producto->id)->where('unidad_medida_id', intval($data[$i]['unidad_medida_id']))->first();
                $movimiento = new DetalleMovimiento();
                $movimiento->maestro_movimiento_id = $compra->id;
                $movimiento->producto_id        = intval($data[$i]['articulo_id']);
                $movimiento->descripcion        = $producto->descripcion;
                $movimiento->unidad_medida_id   = intval($data[$i]['unidad_medida_id']);
                $movimiento->producto_caracteristica_id   = intval($data[$i]['articulo_caracteristica_id']);
                $movimiento->cantidad           = floatval($data[$i]['cantidad']);
                $movimiento->cantidad_medida    = $producto_medida->cantidad;
                $movimiento->cantidad_x_medida  = $movimiento->cantidad * $movimiento->cantidad_medida;
                $movimiento->precio_unitario    = floatval($data[$i]['precio_unitario']);
                $movimiento->precio_bruto       = floatval($data[$i]['precio_total']);
                $movimiento->descuento          = 0;
                $movimiento->recargo            = 0;
                $movimiento->precio_base        = $movimiento->precio_bruto / 1.12;
                $movimiento->precio_impuesto    = $movimiento->precio_bruto - $movimiento->precio_base;
                $movimiento->precio_total       = $movimiento->precio_bruto + $movimiento->recargo - $movimiento->descuento;
                $movimiento->precio_cliente     = 0;
                $movimiento->precio_aseguradora = 0;
                $movimiento->estado             = 1;
                $movimiento->save();
            }
        }

        $message = array(
            'message' => 'Registro de Compra guardada con Exito !!!',
            'type'    => 'success'
        );

        return redirect()->back()->with($message);

        // return Response::json('Compra guardada con Exito !!!');
        //return Response::json($correlativo->ultimo_correlativo. ' '.$nuevo_correlativo);
    }

    public function store_ajuste(Request $request){
        $validData = $request->validate([
            'fecha_transaccion' => 'required|date_format:Y-m-d',
            'bodega_id'         => 'required'
        ]);
        // $fecha_emision   = $_POST['fecha_transaccion'];
        // $bodega_id       = $_POST['bodega_id'];
        // $tipo_ajuste     = $_POST['tipo_ajuste'];
        $data            = (array) $request['productos'];
        $totalRegistros  = count($data);

        // dd($totalRegistros);

        $inv_transaccion = DB::table('inventario_transacciones as it')
                           ->where('it.empresa_id', Auth::user()->empresa_id)
                           ->where('it.tipo_transaccion', 'A')
                           ->where('it.estado', 'A')
                           ->first();

        //$respuesta = array('error' => 0 , 'respuesta' => $inv_transaccion->descripcion. ' '.$inv_transaccion->signo);

        if (isset($inv_transaccion)) {
            $anio = Carbon::now()->format('Y');

            $correlativo = DB::table('maestro_movimientos as mm')
                           ->where('mm.empresa_id', Auth::user()->empresa_id)
                           ->where('mm.inventario_transaccion_id', $inv_transaccion->id)
                           ->where('mm.anio', $anio)
                           ->select(DB::raw('IFNULL(MAX(correlativo),0) as ultimo_correlativo'))
                           ->first();
            
            $nuevo_correlativo = $correlativo->ultimo_correlativo + 1;

            $ajuste = new MaestroMovimiento();
            $ajuste->empresa_id                = Auth::user()->empresa_id;
            $ajuste->inventario_transaccion_id = $inv_transaccion->id;
            $ajuste->fecha_emision             = $validData['fecha_transaccion'];
            $ajuste->signo                     = $inv_transaccion->signo;
            $ajuste->correlativo               = $nuevo_correlativo;
            $ajuste->anio                      = $anio;
            $ajuste->bodega_origen_id          = $validData['bodega_id'];
            $ajuste->estado                    = 1;
            $ajuste->save();

            if ($totalRegistros > 0) {
                for ($i=0; $i < $totalRegistros ; $i++) {
                    $producto = producto::findOrFail(intval($data[$i]['articulo_id']));
                    $producto_medida = ProductoMedida::where('producto_id', $data[$i]['articulo_id'])
                                       ->where('unidad_medida_id', $data[$i]['unidad_medida_id'])
                                       ->first();
                    $detalle = new DetalleMovimiento();
                    $detalle->maestro_movimiento_id = $ajuste->id;
                    $detalle->producto_id        = $data[$i]['articulo_id'];
                    $detalle->descripcion        = $producto->descripcion_a_mostrar;
                    $detalle->unidad_medida_id   = $data[$i]['unidad_medida_id'];
                    $detalle->producto_caracteristica_id = $data[$i]['articulo_caracteristica_id'];
                    $detalle->cantidad           = $data[$i]['cantidad'] * $data[$i]['signo'];
                    $detalle->cantidad_medida    = $producto_medida->cantidad;
                    $detalle->cantidad_x_medida  = $detalle->cantidad * $producto_medida->cantidad;
                    $detalle->precio_unitario    = 0;
                    $detalle->precio_bruto       = 0;
                    $detalle->descuento          = 0;
                    $detalle->recargo            = 0;
                    $detalle->precio_base        = 0;
                    $detalle->precio_impuesto    = 0;
                    $detalle->precio_total       = 0;
                    $detalle->precio_cliente     = 0;
                    $detalle->precio_aseguradora = 0;
                    $detalle->estado           = 1;
                    $detalle->save();
                }
            }

            $message = array(
                'message' => 'Registro de Compra guardada con Exito !!!',
                'type'    => 'success'
            );
        }else{
            // $respuesta = array('error' => 1 , 'respuesta' => 'Tipo de Transacción no localizada !!!');
            $message = array(
                'message' => 'Tipo de Transacción no localizada !!!',
                'type'    => 'error'
            );
        }
        
        return redirect()->back()->with($message);

    }

    public function edit_compra($id){
        $registroId      = Crypt::decrypt($id);
        $proveedores     = Proveedor::where('empresa_id', Auth::user()->empresa_id)->where('estado', 'A')->get();
        $bodegas         = Bodega::where('empresa_id', Auth::user()->empresa_id)->where('estado', 1)->get();
        $tipo_documentos = DB::table('tipo_documentos as td')
                           ->join('inventario_transacciones as it','td.inventario_transaccion_id', 'it.id')
                           ->where('it.empresa_id', Auth::user()->empresa_id)
                           ->select('td.id', 'td.descripcion')
                           ->get();

        $encabezado = DB::table('maestro_movimientos as mm')
                      ->join('proveedores as p', 'mm.proveedor_id', 'p.id')
                      ->join('tipo_documentos as td', 'mm.tipo_documento_id', 'td.id')
                      ->join('detalle_movimientos as dm', 'mm.id', 'dm.maestro_movimiento_id')
                      ->join('bodegas as b', 'mm.bodega_origen_id', 'b.id')
                      ->where('mm.id', $registroId)
                      ->select('mm.id','mm.proveedor_id', 'p.nombre_comercial', 'p.dias_credito', 'mm.nit',
                               'mm.tipo_documento_id', 'td.descripcion as tipo_documento_descripcion',
                               'mm.serie', 'mm.numero_documento', 'mm.fecha_emision', 'mm.fecha_vencimiento',
                               'mm.total', 'mm.bodega_origen_id', 'b.descripcion as bodega_descripcion')
                      ->groupBy('mm.id','mm.proveedor_id', 'p.nombre_comercial', 'p.dias_credito', 'mm.nit',
                               'mm.tipo_documento_id', 'td.descripcion', 'mm.serie', 'mm.numero_documento', 'mm.fecha_emision', 'mm.fecha_vencimiento', 'mm.total', 'mm.bodega_origen_id', 'b.descripcion')
                      ->first();

        $detalle = DB::table('detalle_movimientos as dm')
                   ->join('unidad_medidas as um', 'dm.unidad_medida_id', 'um.id')
                   ->where('dm.maestro_movimiento_id', $registroId)
                   ->where('dm.estado', '!=', 2)
                   ->select('dm.id', 'dm.producto_id', 'dm.descripcion', 'dm.unidad_medida_id', 'um.descripcion as unidad_medida_descripcion', 'dm.cantidad', 'dm.precio_unitario', 'dm.precio_total')
                   ->get();

        $productos = DB::table('productos as p')
                     ->join('invclasificaciones as ic', 'p.clasificacion', 'ic.id')
                     ->where('p.estado', 1)
                     ->where('ic.definir_medidas', 1)
                     ->select('p.id', 'p.descripcion', 'p.medida_id')
                     ->get();
        /*$productos = Producto::where('empresa_id', Auth::user()->empresa_id)
                     
                     ->where('estado', 1)
                     ->whereIn('clasificacion', ['PROD', 'MED'])
                     ->select('id', 'descripcion', 'medida_id')
                     ->get();*/

        return view('inventarios.edit_compra', compact('encabezado', 'detalle', 'proveedores', 'bodegas', 'tipo_documentos', 'productos'));
    }

    public function edit_ajuste($id){
        $registroId      = Crypt::decrypt($id);
        $bodegas         = Bodega::where('empresa_id', Auth::user()->empresa_id)->where('estado', 1)->get();

        $encabezado      = MaestroMovimiento::findOrFail($registroId);
        $productos = Producto::where('empresa_id', Auth::user()->empresa_id)
                     ->where('estado', 1)
                     ->where('clasificacion', 'PROD')
                     ->select('id', 'descripcion', 'medida_id')
                     ->get();

        return view('inventarios.edit_ajuste', compact('bodegas', 'encabezado', 'productos'));
    }

    public function update_compra(Request $request){
        // var_dump(array_values($request['productos'])); die;
        $validData = $request->validate([
            'compra_id'         => 'required',
            'proveedor_id'      => 'required',
            'nit'               => 'required',
            'documento_id'      => 'required',
            'serie'             => 'required',
            'numero_documento'  => 'required',
            'fecha_emision'     => 'required',
            'fecha_vencimiento' => 'required',
            'dias_credito'      => 'required',
            'total'             => 'required',
            'bodega_id'         => 'required'
        ]);
        $totalRegistros  = count($request['productos']);
        $data = array_values($request->input('productos'));

        // DetalleMovimiento::where('maestro_movimiento_id', $validData['compra_id'])->update(['estado' => 2]);

        //==========================================================================================
        // Se actualiza el encabezado de la compra
        //==========================================================================================
        $compra = MaestroMovimiento::findOrFail($validData['compra_id']);
        $compra->bodega_origen_id          = $validData['bodega_id'];
        $compra->proveedor_id              = $validData['proveedor_id'];
        $compra->nit                       = strtoupper($validData['nit']);
        $compra->tipo_documento_id         = $validData['documento_id'];
        $compra->serie                     = strtoupper($validData['serie']);
        $compra->numero_documento          = $validData['numero_documento'];
        $compra->fecha_emision             = $validData['fecha_emision'];
        $compra->dias_credito              = $validData['dias_credito'];
        $compra->fecha_vencimiento         = $validData['fecha_vencimiento'];
        $compra->total                     = $validData['total'];
        $compra->save();

        //==========================================================================================
        // Se da de baja a los registros que no se encuentran en el nuevo detalle
        //==========================================================================================
        $registrosActutales = DetalleMovimiento::where('maestro_movimiento_id', $validData['compra_id'])->get();
        foreach ($registrosActutales as $registro) {
            $productoEncontrado = false;
            foreach ($data as $producto) {
                 // print_r($producto);die;
                if ($registro->producto_id == $producto['articulo_id'] && $registro->unidad_medida_id == $producto['unidad_medida_id']) {
                    $productoEncontrado = true;
                }
            }
            if (!$productoEncontrado) {
                $eliminar = DetalleMovimiento::findOrFail($registro->id);
                $eliminar->estado = 2;
                $eliminar->save();
            }
        }

        for ($i = 0; $i < $totalRegistros ; $i++) { 
            $producto = producto::findOrFail(intval($data[$i]['articulo_id']));
            $producto_medida = ProductoMedida::where('producto_id', $producto->id)
                                                 ->where('unidad_medida_id', intval($data[$i]['unidad_medida_id']))
                                                 ->first();
            if ($data[$i]['id'] == 0) {
                $registro = new DetalleMovimiento();
            }else{
                $registro = DetalleMovimiento::findOrFail($data[$i]['id']);
            }
            
            $registro->maestro_movimiento_id = $compra->id;
            $registro->producto_id        = intval($data[$i]['articulo_id']);
            $registro->descripcion        = $producto->descripcion;
            $registro->unidad_medida_id   = intval($data[$i]['unidad_medida_id']);
            $registro->producto_caracteristica_id  = intval($data[$i]['articulo_caracteristica_id']);
            $registro->cantidad           = floatval($data[$i]['cantidad']);
            $registro->cantidad_medida    = $producto_medida->cantidad;
            $registro->cantidad_x_medida  = $registro->cantidad * $registro->cantidad_medida;
            $registro->precio_unitario    = floatval($data[$i]['precio_unitario']);
            $registro->precio_bruto       = floatval($data[$i]['precio_total']);
            $registro->descuento          = 0;
            $registro->recargo            = 0;
            $registro->precio_base        = $registro->precio_bruto / 1.12;
            $registro->precio_impuesto    = $registro->precio_bruto - $registro->precio_base;
            $registro->precio_total       = $registro->precio_bruto + $registro->recargo - $registro->descuento;
            $registro->precio_cliente     = 0;
            $registro->precio_aseguradora = 0;
            $registro->estado             = 1;
            $registro->save();
        }

        // $registrosActutales = DetalleMovimiento::where('maestro_movimiento_id', $validData['compra_id'])->get();
        // foreach ($registrosActutales as $registro) {
        //     $productoEncontrado = false;
        //     foreach ($data as $producto) {
        //          // print_r($producto);die;
        //         if ($registro->producto_id == $producto['articulo_id'] && $registro->unidad_medida_id == $producto['unidad_medida_id']) {
        //             $productoEncontrado = true;
        //         }
        //     }
        //     if (!$productoEncontrado) {
        //         $eliminar = DetalleMovimiento::findOrFail($registro->id);
        //         $eliminar->estado = 2;
        //         $eliminar->save();
        //     }
        // }
        
        $message = array(
            'message' => 'Registro de Compra actualizado con Exito !!!',
            'type'    => 'success'
        );

        return redirect()->back()->with($message);
    }

    public function update_ajuste(){
        $validData = $request->validate([
            'ajuste_id'         => 'required',
            'fecha_transaccion' => 'required|date_format:Y-m-d',
            'bodega_id'         => 'required'
        ]);

        $data            = (array) $request['productos'];
        $totalRegistros  = count($data);

        $ajuste = MaestroMovimiento::findOrFail($validData['ajuste_id']);
        $ajuste->bodega_origen_id          = $bodega_id;
        $ajuste->estado                    = 1;
        $ajuste->save();

        //==========================================================================================
        // Se da de baja a los registros que no se encuentran en el nuevo detalle
        //==========================================================================================
        $registrosActutales = DetalleMovimiento::where('maestro_movimiento_id', $validData['ajuste_id'])->get();
        foreach ($registrosActutales as $key => $registro) {
            $productoEncontrado = false;
             foreach ($data as $producto) {
                if ($registro->producto_id == $producto['articulo_id'] && $registro->unidad_medida_id == $producto['unidad_medida_id']){
                    $productoEncontrado = true;
                }
             }

             if (!$productoEncontrado) {
                $eliminar = DetalleMovimiento::findOrFail($registro->id);
                $eliminar->estado = 2;
                $eliminar->save();
            }
        }

        if (isset($inv_transaccion)) {

            $totalRegistros  = count($data_agregar);
            if ($totalRegistros > 0) {
                for ($i=0; $i < $totalRegistros ; $i++) {
                    $producto = Producto::findOrFail(intval($data_agregar[$i]['producto_id']));
                    $producto_medida = ProductoMedida::where('producto_id', $producto->id)->where('unidad_medida_id', intval($data_agregar[$i]['unidad_medida_id']))->first();

                    $movimiento = new DetalleMovimiento();
                    $movimiento->maestro_movimiento_id = $ajuste->id;
                    $movimiento->producto_id        = intval($data_agregar[$i]['producto_id']);
                    $movimiento->descripcion        = $producto->descripcion;
                    $movimiento->unidad_medida_id   = intval($data_agregar[$i]['unidad_medida_id']);
                    $movimiento->cantidad           = floatval($data_agregar[$i]['cantidad']);
                    $movimiento->cantidad_medida    = $producto_medida->cantidad;
                    $movimiento->cantidad_x_medida  = $movimiento->cantidad * $movimiento->cantidad_medida;
                    $movimiento->precio_unitario    = 0;
                    $movimiento->precio_bruto       = 0;
                    $movimiento->descuento          = 0;
                    $movimiento->recargo            = 0;
                    $movimiento->precio_base        = 0;
                    $movimiento->precio_impuesto    = 0;
                    $movimiento->precio_total       = 0;
                    $movimiento->precio_cliente     = 0;
                    $movimiento->precio_aseguradora = 0;
                    $movimiento->estado             = 'A';
                    $movimiento->save();
                }
            }
            $respuesta = array('error' => 0 , 'respuesta' => 'Ajuste guardado con exito !!!');
        }else{
            $respuesta = array('error' => 1 , 'respuesta' => 'Tipo de Transacción no localizada !!!');
        }
        return Response::json($respuesta);
    }

    public function trae_detalle_compra(){
        $compra_id = $_POST['compra_id'];
        
        $detalle = DB::table('detalle_movimientos as dm')
                   ->join('unidad_medidas as um', 'dm.unidad_medida_id', 'um.id')
                   ->where('dm.maestro_movimiento_id', $compra_id)
                   ->where('dm.estado', '!=', 2)
                   ->orderBy('dm.id', 'ASC')
                   ->select('dm.id', 'dm.producto_id', 'dm.descripcion as producto_descripcion', 'dm.unidad_medida_id', 'um.descripcion as unidad_medida_descripcion', 'dm.cantidad', 'dm.precio_unitario', 'dm.precio_total', 'dm.producto_caracteristica_id')
                   ->get();
        return Response::json($detalle);
    }

    public function trae_detalle_ajuste(){
        $ajuste_id = $_POST['ajuste_id'];
        
        $detalle = DB::table('detalle_movimientos as dm')
                   ->join('unidad_medidas as um', 'dm.unidad_medida_id', 'um.id')
                   ->where('dm.maestro_movimiento_id', $ajuste_id)
                   ->select('dm.id', 'dm.producto_id', 'dm.descripcion as producto_descripcion', 'dm.unidad_medida_id', 'um.descripcion as unidad_medida_descripcion', 'dm.cantidad', 'dm.producto_caracteristica_id')
                   ->get();
        return Response::json($detalle);
    }

}
