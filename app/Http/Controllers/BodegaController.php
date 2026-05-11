<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BodegaController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Auth;
use Session;
use Response;
use App\Models\Bodega;
use App\Models\BodegaProductoConfig;
use App\Models\Producto;

class BodegaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	$listado = Bodega::all();
    	return view('bodegas.index', compact('listado'));
    }

    public function store(Request $request){
        $validData = $request->validate([
            'descripcion'      => 'required'
        ]);

        $bodega = new Bodega();
        $bodega->empresa_id  = Auth::user()->empresa_id;
        $bodega->descripcion = $validData['descripcion'];

        if (isset($request->estado)) {
        	$bodega->estado = 1;
        }else{
        	$bodega->estado = 0;
        }

        $bodega->save();

        $message = array(
            'message' => '! Bodega Almacenada con Exito !',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);

    }

    public function edit(){
        $id = Crypt::decrypt($_POST['id']);
    	$bodega = Bodega::findOrFail($id);
    	return Response::json($bodega);
    }

    public function update(Request $request){
        $validData = $request->validate([
            'edescripcion'      => 'required'
        ]);

        $id = Crypt::decrypt($request['eid']);

        $bodega              = Bodega::findOrFail($id);
        $bodega->descripcion = $validData['edescripcion'];
        if (isset($request->eestado)) {
        	$bodega->estado = 1;
        }else{
        	$bodega->estado = 0;
        }

        $bodega->save();

        $message = array(
            'message' => 'Bodega Actualizada con Exito !!!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);

    }

    public function getConfiguracion($id_encriptado) {
        $id = Crypt::decrypt($id_encriptado);
        // Obtenemos todos los productos y su configuración específica para esta bodega
        $productos = Producto::select('id', 'descripcion')
            ->with(['bodegasConfiguradas' => function($q) use ($id) {
                $q->where('bodega_id', $id);
            }])->get();

        return Response::json($productos);
    }

    public function guardarConfiguracion(Request $request) {
        $bodega_id = Crypt::decrypt($request->bodega_id);
        
        foreach ($request->productos as $prod) {
            BodegaProductoConfig::updateOrCreate(
                ['bodega_id' => $bodega_id, 'producto_id' => $prod['id']],
                [
                    'stock_minimo' => $prod['minimo'] ?? 0,
                    'stock_maximo' => $prod['maximo'] ?? 0,
                    'punto_reorden' => $prod['reorden'] ?? 0,
                    'estado' => 1,
                    'updated_by' => Auth::user()->name
                ]
            );
        }

        return response()->json(['message' => 'Configuración actualizada correctamente']);
    }
}
