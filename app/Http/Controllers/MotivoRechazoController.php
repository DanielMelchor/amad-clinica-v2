<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Redirect;
use Response;
use Session;
use App\Models\MotivoRechazo;

class MotivoRechazoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $pMotivosRechazo = MotivoRechazo::all();
        return view('motivoRechazos.index', compact('pMotivosRechazo'));
    }

    public function create(){
    	return view('motivoRechazos.create');
    }

    public function store(Request $request){
    	$validData = $request->validate([
            'descripcion' => 'required'
        ]);

        $motivoRechazo = new MotivoRechazo();
        $motivoRechazo->descripcion = $validData['descripcion'];

        if(isset($request->estado)){
    		$motivoRechazo->estado = 1;
    	}else{
    		$motivoRechazo->estado = 0;
    	}

    	$motivoRechazo->save();

    	//return Redirect::route('motivoRechazos')->with('message','Motivo grabado con exito');
        // Session::flash('success', 'Motivo de Rechazo Guardado con exito !!!' );
        // return redirect(route('motivoRechazos'));
        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    public function edit(){
    	$id       = $_POST['id'];
        $motivoId = Crypt::decrypt($id);
        $registro = MotivoRechazo::findOrFail($motivoId);
        return Response::json($registro);
    }

    public function update(Request $request){
    	$validData = $request->validate([
            'edescripcion' => 'required'
        ]);

        $id       = $_POST['eid'];
        $motivoId = Crypt::decrypt($id);
        $motivoRechazo = MotivoRechazo::findOrFail($motivoId);
        $motivoRechazo->descripcion = $validData['edescripcion'];

        if(isset($request->eestado)){
    		$motivoRechazo->estado = 1;
    	}else{
    		$motivoRechazo->estado = 0;
    	}

    	$motivoRechazo->save();

    	//return Redirect::route('motivoRechazos')->with('message','Motivo grabado con exito');
        // Session::flash('success', 'Motivo de Rechazo Actualizado con exito !!!' );
        // return redirect(route('motivoRechazos'));
        $message = array(
            'message' => 'Registro actualizado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }
}
