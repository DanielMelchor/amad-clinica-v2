<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Redirect;
use Response;
use Session;
use App\Models\MotivoAnulacion;

class MotivoAnulacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $pMotivosAnulacion = MotivoAnulacion::all();
        return view('motivosAnulacion.index', compact('pMotivosAnulacion'));
    }

    public function create(){
    	return view('motivosAnulacion.create');
    }

    public function store(Request $request){
    	$validData = $request->validate([
            'descripcion' => 'required'
        ]);

        $motivoAnulacion = new MotivoAnulacion();
        $motivoAnulacion->descripcion = $validData['descripcion'];

        if(isset($request->estado)){
    		$motivoAnulacion->estado = 1;
    	}else{
    		$motivoAnulacion->estado = 1;
    	}

    	$motivoAnulacion->save();

    	//return Redirect::route('motivosAnulacion')->with('message','Motivo grabado con exito');
        // Session::flash('success', 'Motivo de Anulación Guardado con exito !!!' );
        // return redirect(route('motivosAnulacion'));
        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    public function edit(){
    	$id     = $_POST['id'];
        $motivoId = Crypt::decrypt($id);
        $registro = MotivoAnulacion::findOrFail($motivoId);
        return Response::json($registro);
    }

    public function update(Request $request){
    	$validData = $request->validate([
            'edescripcion' => 'required'
        ]);

        $id     = $_POST['eid'];
        $motivoId = Crypt::decrypt($id);
        $motivoAnulacion = MotivoAnulacion::findOrFail($motivoId);
        $motivoAnulacion->descripcion = $validData['edescripcion'];

        if(isset($request->eestado)){
    		$motivoAnulacion->estado = 1;
    	}else{
    		$motivoAnulacion->estado = 0;
    	}

    	$motivoAnulacion->save();

    	//return Redirect::route('motivosAnulacion')->with('message','Motivo grabado con exito');
        // Session::flash('success', 'Motivo de Anulación Actualizado con exito !!!' );
        // return redirect(route('motivosAnulacion'));
        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }
}
