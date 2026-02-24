<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Session;
use Response;
use DB;
use Carbon\Carbon;
use App\Models\InvClasificacion;
use App\Models\InvFamilia;
use App\Models\Producto;
use App\Models\ProductoCaracteristica;
use App\Models\ProductoDosis;
use App\Models\ProductoMedida;
use App\Models\ProductoProveedor;
use App\Models\Proveedor;
use App\Models\UnidadMedida;

class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $pProductos = DB::table('productos as p')
                      ->join('invclasificaciones as ic', 'p.clasificacion', 'ic.id')
                      ->where('p.empresa_id', Auth::user()->empresa_id)
                      ->select('ic.nombre as clasificacion', 'p.id', 'p.descripcion', 'p.estado')
                      ->get();

        // $pProductos = Producto::all();
        return view('productos.index', compact('pProductos'));
    }

    public function create()
    {
        $pUnidades       = UnidadMedida::where('estado', 1)->where('aplica_receta', 'N')->get();
        $proveedores     = Proveedor::where('empresa_id', Auth::user()->empresa_id)->get();
        $familias        = InvFamilia::where('empresa_id', Auth::user()->empresa_id)->where('estado', 1)->get();
        $medidas         = UnidadMedida::where('estado', 1)->where('aplica_receta', 'N')->get();
        $dosis           = UnidadMedida::where('estado', 1)->where('aplica_receta', 'S')->get();
        $clasificaciones = InvClasificacion::where('estado', 1)->get();
        return view('productos.create', compact('pUnidades', 'proveedores', 'medidas', 'dosis', 'clasificaciones', 'familias'));
    }

    public function store(Request $request){
        $validData = $request->validate([
            'inv_clasificacion_id'   => 'required',
            'inv_familia_id'         => 'required',
            'descripcion'            => 'required',
            'descripcion_a_mostrar'  => 'required'
        ]); 

        $producto = new Producto();
        $producto->empresa_id            = Auth::user()->empresa_id;
        $producto->clasificacion         = $validData['inv_clasificacion_id'];
        $producto->inv_familia_id        = $validData['inv_familia_id'];
        //$producto->siglas                = $request['siglas'];
        $producto->descripcion           = $validData['descripcion'];
        $producto->descripcion_a_mostrar = $validData['descripcion_a_mostrar'];
        if (isset($request['medida_id'])) {
            $producto->medida_id = $request['medida_id'];
        }
        // if (!isset($request['medida_id']) || $request['medida_id'] == '') {
        //     $producto->medida_id = 1;
        // }else{
            
        // }

        if (isset($request['premedicacion'])) {
            $producto->premedicacion = $request['premedicacion'];
        }else{
            $producto->premedicacion = 0;
        }
        
        if (isset($request['estado'])) {
            $producto->estado = 1;
        }else{
            $producto->estado = 0;
        }
        $producto->save();

        if (isset($request['medidas'])) {
            $totalRegistrosMedidas = count($request['medidas']);
            for ($i=0; $i < $totalRegistrosMedidas ; $i++) {
                $medida = new ProductoMedida();
                $medida->producto_id = $producto->id;
                $medida->unidad_medida_id = intval($request['medidas'][$i]['unidad_medida_id']);
                $medida->cantidad         = intval($request['medidas'][$i]['cantidad']);
                $medida->estado           = 1;
                $medida->save();
            }
        }

        if (isset($request['caracteristica'])) {
            $totalRegistrosCaracteristicas = count($request['caracteristica']);
            for ($i=0; $i < $totalRegistrosCaracteristicas ; $i++) {
                $medida = new ProductoCaracteristica();
                $medida->producto_id = $producto->id;
                $medida->descripcion = $request['caracteristica'][$i]['descripcion'];
                $medida->estado      = 1;
                $medida->save();
            }
        }

        if (isset($request['dosis'])) {
            $totalRegistrosDosis   = count($request['dosis']);
            for ($i=0; $i < $totalRegistrosDosis; $i++) { 
                $registro = new ProductoDosis();
                $registro->producto_id      = $producto->id;
                $registro->unidad_medida_id = intval($request['dosis'][$i]['dosis_id']);
                $registro->descripcion      = $request['dosis'][$i]['descripcion'];
                $registro->estado           = 1;
                $registro->save();
            }
        }

        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );

        return redirect()->route('editar_producto', [Crypt::encryptString((string)$producto->id)])->with($message);
    }

    public function edit($id){
        $pUnidades   = Unidadmedida::where('estado', 1)->where('aplica_receta', 'N')->get();
        $proveedores = Proveedor::where('empresa_id', Auth::user()->empresa_id)->get();
        $familias    = InvFamilia::where('empresa_id', Auth::user()->empresa_id)->get();
        $medidas     = UnidadMedida::where('estado', 1)->where('aplica_receta', 'N')->get();
        $dosis       = UnidadMedida::where('estado', 1)->where('aplica_receta', 'S')->get();
        $productoId  = Crypt::decryptString($id);
        $producto    = Producto::findOrFail($productoId);
        $clasificaciones = InvClasificacion::where('estado', 1)->get();
        return view('productos.edit', compact('pUnidades', 'proveedores', 'producto', 'medidas', 'dosis', 'clasificaciones', 'familias'));
    }

    public function update(Request $request){
        // dd($request); die;   
        $validData = $request->validate([
            'producto_id'            => 'required',
            'inv_clasificacion_id'   => 'required',
            'descripcion'            => 'required'
        ]); 

        $producto_id           = $validData['producto_id'];
        $siglas                = $request['siglas'];
        $descripcion           = $validData['descripcion'];
        $descripcion_a_mostrar = $request['descripcion_a_mostrar'];
        if (isset($request['medida_id'])) {
            $medida_id             = $request['medida_id'];
        }else{
            $medida_id             = 1;
        }
        
        if (isset($request['estado'])) {
            $estado      = 1;
        }else{
            $estado      = 0;
        }

        $producto = Producto::findOrFail($producto_id);
        $producto->clasificacion         = $validData['inv_clasificacion_id'];
        $producto->siglas                = $siglas;
        $producto->descripcion           = $descripcion;
        $producto->descripcion_a_mostrar = $descripcion_a_mostrar;
        $producto->medida_id             = $medida_id;
        // $producto->premedicacion         = $premedicacion;
        $producto->estado                = $estado;
        $producto->save();

        if (isset($request['medidas'])) {
            $medidas = array_values($request['medidas']);
            $totalRegistrosMedidas = count($medidas);

            $registroActual = ProductoMedida::where('producto_id', $producto_id)
                              ->where('estado', 1)
                              ->get();
            foreach ($registroActual as $key => $registro) {
                $medidaEncontrada = false;
                foreach ($medidas as $medida) {
                    if ($registro->producto_id == $producto_id && $registro->unidad_medida_id == $producto['unidad_medida_id']) {
                        $medidaEncontrada = true;
                    }
                }
                if (!$medidaEncontrada) {
                    $eliminar = ProductoMedida::findOrFail($registro->id);
                    $eliminar->estado = 0;
                    $eliminar->save();
                }
            }

            for ($i=0; $i < $totalRegistrosMedidas ; $i++) {
                $existe_medida = ProductoMedida::where('producto_id', $producto_id)
                                 ->where('unidad_medida_id', intval($medidas[$i]['unidad_medida_id']))
                                 ->count();

                if ($existe_medida == 0) {
                    $medida = new ProductoMedida();
                    $medida->producto_id = $producto->id;
                    $medida->unidad_medida_id = intval($medidas[$i]['unidad_medida_id']);
                    $medida->cantidad         = $medidas[$i]['cantidad'];
                    $medida->estado           = 1;
                    $medida->save();
                }else{
                    $medida = ProductoMedida::where('producto_id', $producto_id)
                              ->where('unidad_medida_id', intval($medidas[$i]['unidad_medida_id']))
                              ->first();
                    $medida->cantidad         = $medidas[$i]['cantidad'];
                    $medida->estado           = 1;
                    $medida->save();
                }
                
            }
        }else{
            ProductoMedida::where('producto_id', $producto_id)->update(['estado' => 0]);
        }

        // if (isset($request['caracteristica'])) {
        //     $caracteristicas = array_values($request['caracteristica']);
        //     $totalRegistrosCaracteristicas = count($caracteristicas);

        //     $registroActual = ProductoCaracteristica::where('producto_id', $producto_id)
        //                       ->where('estado', 1)
        //                       ->get();

        //     foreach ($registroActual as $key => $registro) {
        //         $caracteristicaEncontrada = false;
        //         foreach ($caracteristicas as $caracteristica) {
        //             if ($registro->producto_id == $producto_id && $registro->id == $caracteristica['id']) {
        //                 $caracteristicaEncontrada = true;
        //             }
        //         }
        //         if (!$caracteristicaEncontrada) {
        //             $eliminar = ProductoCaracteristica::findOrFail($registro->id);
        //             $eliminar->estado = 0;
        //             $eliminar->save();
        //         }
        //     }    

        //     for ($i=0; $i < $totalRegistrosCaracteristicas ; $i++) {
        //         $existe_caracteristica = ProductoCaracteristica::where('id', intval($caracteristicas[$i]['id']))
        //                                  ->count();

        //         if ($existe_caracteristica == 0) {
        //             $medida = new ProductoCaracteristica();
        //             $medida->producto_id      = $producto->id;
        //             $medida->descripcion      = $caracteristicas[$i]['descripcion'];
        //             $medida->estado           = 1;
        //             $medida->save();
        //         }else{
        //             $medida = ProductoCaracteristica::where('id', $caracteristicas[$i]['id'])
        //                       ->first();
        //             $medida->descripcion      = $caracteristicas[$i]['descripcion'];
        //             $medida->estado           = 1;
        //             $medida->save();
        //         }
                
        //     }
        // }

        if (isset($request['dosis'])) {
            $dosis = array_values($request['dosis']);
            $totalRegistrosDosis = count($dosis);

            $registroActual = ProductoDosis::where('producto_id', $producto_id)
                              ->where('estado', 1)
                              ->get();
            foreach ($registroActual as $key => $registro) {
                $dosisEncontrada = false;
                foreach ($dosis as $dos) {
                    if ($registro->producto_id == $producto_id && $registro->unidad_medida_id == $dos['dosis_id']) {
                        $dosisEncontrada = true;
                    }
                }
                if (!$dosisEncontrada) {
                    $eliminar = ProductoDosis::findOrFail($registro->id);
                    $eliminar->estado = 0;
                    $eliminar->save();
                }
            }

            for ($i=0; $i < $totalRegistrosDosis ; $i++) {
                $existe_dosis = ProductoDosis::where('producto_id', $producto_id)
                                 ->where('unidad_medida_id', intval($dosis[$i]['dosis_id']))
                                 ->count();

                if ($existe_dosis == 0) {
                    $medida = new ProductoDosis();
                    $medida->producto_id      = $producto->id;
                    $medida->unidad_medida_id = intval($dosis[$i]['dosis_id']);
                    $medida->descripcion      = $dosis[$i]['descripcion'];
                    $medida->estado           = 1;
                    $medida->save();
                }else{
                    $medida = ProductoDosis::where('producto_id', $producto_id)
                              ->where('unidad_medida_id', intval($dosis[$i]['dosis_id']))
                              ->first();
                    $medida->descripcion      = $dosis[$i]['descripcion'];
                    $medida->estado           = 1;
                    $medida->save();
                }
                
            }
        }else{
            ProductoDosis::where('producto_id', $producto_id)->update(['estado' => 0]);
        }
        
        $message = array(
            'message' => 'Registro actualizado con exito !!!',
            'type'    => 'success'
        );

        return redirect()->back()->with($message);
    }

    public function descripcion(){
        
        $producto = Producto::findOrFail($_POST['cod']);
        $descripcion = $producto->descripcion_a_mostrar;
        return Response::json($descripcion);
    }

    public function trae_medidas_x_producto(){
        $producto_id = $_POST['producto_id'];
        $medidas = DB::table('producto_medidas as pm')
                   ->join('unidad_medidas as um', 'pm.unidad_medida_id', 'um.id')
                   ->where('pm.producto_id', $producto_id)
                   ->where('pm.estado', 1)
                   ->select('pm.unidad_medida_id', 'um.descripcion as unidad_medida_descripcion', 'pm.cantidad')
                   ->get();
        return Response::json($medidas);
    }

    public function trae_dosis_x_producto(){
        $producto_id = $_POST['producto_id'];
        $dosis = DB::table('producto_dosis as pd')
                 ->join('unidad_medidas as um', 'pd.unidad_medida_id', 'um.id')
                 ->where('pd.producto_id', $producto_id)
                 ->where('um.aplica_receta', 'S')
                 ->where('pd.estado', 1)
                 ->select('pd.id', 'pd.unidad_medida_id', 'um.descripcion as unidad_medida_descripcion', 'pd.descripcion', 'pd.estado')
                 ->get();
        return Response::json($dosis);
    }

    public function trae_caracteristicas_x_producto(){
        $producto_id = $_POST['producto_id'];
        $caracteristicas = DB::table('producto_caracteristicas as pc')
                 ->where('pc.producto_id', $producto_id)
                 ->where('pc.estado', 1)
                 ->select('pc.id', 'pc.descripcion', 'pc.estado')
                 ->get();
        return Response::json($caracteristicas);
    }

    public function trae_proveedores_x_producto(){
        $producto_id = $_POST['producto_id'];
        $proveedores = DB::table('producto_proveedores as pp')
                       ->join('proveedores as p', 'pp.proveedor_id', 'p.id')
                       ->join('unidad_medidas as um', 'pp.unidad_medida_id', 'um.id')
                       ->where('pp.producto_id', $producto_id)
                       ->select('pp.proveedor_id', 'p.nombre_comercial as proveedor_nombre', 'pp.unidad_medida_id as unidad_medida_provee_id', 'um.descripcion as unidad_medida_provee_descripcion', 'pp.precio_inicial', 'pp.precio_ultima_compra', 'pp.fecha_ultima_compra')
                       ->get();
        return Response::json($proveedores);
    }

    public function trae_productos(){
        $listado = DB::table('productos as p')
                   ->join('producto_medidas as pm', 'p.id', 'pm.producto_id')
                   // ->join('unidad_medidas as um', 'p.medida_id', 'um.id')
                   ->where('p.empresa_id', Auth::user()->empresa_id)
                   ->where('p.estado', 1)
                   // ->groupBy('p.id', 'p.descripcion', 'p.medida_id', 'um.descripcion')
                   ->groupBy('p.id', 'p.descripcion')
                   // ->select('p.id', 'p.descripcion', 'p.medida_id', 'um.descripcion as medida_descripcion')
                   ->select('p.id', 'p.descripcion')
                   ->get();

        return Response::json($listado);   
    }

    public function trae_productos_con_inicial(){
        $fecha_inicial = Carbon::now()->format('Y-m-d');
        $month_start = strtotime('first day of this month', time());

        $datos = array();

        $detalle = DB::table('productos as p')
                   ->where('p.empresa_id', Auth::user()->empresa_id)
                   ->where('p.clasificacion', 'PROD')
                   ->select('p.id as producto_id', 'p.descripcion as producto_descripcion')
                   ->get();

        foreach ($detalle as $d) {
            //========================================================================================
            // trae saldo inicial por cada articulo
            //========================================================================================
          
            $saldo_inicial = DB::table('maestro_movimientos as mm')
                                ->join('detalle_movimientos as dm', 'mm.id', 'dm.maestro_movimiento_id')
                                ->where('dm.producto_id', $d->producto_id)
                                ->whereDate('mm.created_at', '<', $month_start)
                                ->select(DB::raw('ifnull(SUM(dm.cantidad_x_medida * mm.signo),0) as saldo_inicial'))
                                ->get();

            //========================================================================================
            // trae movimientos para cada articulo
            //========================================================================================
            $movimientos = array();
            $movimiento = DB::table('detalle_movimientos as dm')
                            ->join('maestro_movimientos as mm', 'dm.maestro_movimiento_id', 'mm.id')
                            ->join('inventario_transacciones as it', 'mm.inventario_transaccion_id', 'it.id')
                            ->where('dm.producto_id', $d->producto_id)
                            ->whereDate('mm.created_at', '>=', $month_start)
                            ->where(DB::raw('IFNULL(dm.cantidad_x_medida,0)'),'>', 0)
                            ->select('it.descripcion as transaccion_descripcion', 'mm.correlativo', 'mm.anio', 'mm.created_at as transaccion_fecha', DB::raw('IFNULL(dm.cantidad_x_medida,0) as cantidad'), 'mm.signo')
                            ->orderBy('mm.created_at', 'asc')
                            ->get();
            foreach ($movimiento as $m) {
                array_push($movimientos, $m);
            }
            array_push($datos, [$d, $saldo_inicial, $movimientos]);
        }
        return Response($datos);
    }

    public function trae_dosis_x_medicamento(){
        $medicamento_id = $_POST['medicamento_id'];

        $listado = DB::table('producto_dosis as pd')
                   ->join('unidad_medidas as um', 'pd.unidad_medida_id', 'um.id')
                   ->where('pd.producto_id', $medicamento_id)
                   ->where('pd.estado', 1)
                   ->select('pd.id', 'pd.unidad_medida_id', 'um.descripcion')
                   ->get();
        // print_r($listado); die;

        return Response($listado);
    }

    public function receta(){
        $medicamento_id = $_POST['medicamento_id'];
        $dosis_id       = $_POST['dosis_id'];
        $data = ProductoDosis::where('producto_id', $medicamento_id)
                ->where('unidad_medida_id', $dosis_id)
                ->select('descripcion')
                ->first();

        return Response::json($data);
    }
}
