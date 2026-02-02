<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Response;
use Session;
use App\Models\Diagnostico;

class DiagnosticoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	$diagnosticos = Diagnostico::all();
    	return view('diagnosticos.index', compact('diagnosticos'));
    }

    public function store(Request $request){
    	$validData = $request->validate([
            'descripcion' => 'required'
        ]);

        $diagnostico = new Diagnostico();
        $diagnostico->descripcion = $validData['descripcion'];
        if (isset($request->estado)) {
        	$diagnostico->estado = 'A';
        }else{
        	$diagnostico->estado = 'I';
        }

        $diagnostico->save();

        // Session::flash('success', 'Diagnostico Guardada con exito !!!' );
        // return redirect(route('diagnosticos'));
        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);

    }

    public function edit(){
    	$id            = $_POST['id'];
        $diagnosticoId = Crypt::decrypt($id);
        $registro      = Diagnostico::findOrFail($diagnosticoId);
        return Response::json($registro);
    }

    public function update(Request $request){
    	$validData = $request->validate([
            'edescripcion' => 'required'
        ]);

        $id            = $request['eid'];
        $diagnosticoId = Crypt::decrypt($id);

        $diagnostico = Diagnostico::findOrFail($diagnosticoId);
        $diagnostico->descripcion = $validData['edescripcion'];
        if (isset($request->eestado)) {
        	$diagnostico->estado = 'A';
        }else{
        	$diagnostico->estado = 'I';
        }

        $diagnostico->save();

        // Session::flash('success', 'Diagnostico Actualizada con exito !!!' );
        // return redirect(route('diagnosticos'));
        $message = array(
            'message' => 'Registro actualizado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }
}
