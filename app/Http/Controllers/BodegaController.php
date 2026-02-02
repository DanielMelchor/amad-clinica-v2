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
}
