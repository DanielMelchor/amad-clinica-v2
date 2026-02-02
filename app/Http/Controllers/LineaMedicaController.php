<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Response;
use Session;
use App\Models\LineaMedica;

class LineaMedicaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	$lineas = LineaMedica::all();
    	return view('lineasmedicas.index', compact('lineas'));
    }

    public function create(){
    	return view('lineasmedicas.create');	
    }

    public function store(Request $request){
    	$validData = $request->validate([
            'descripcion' => 'required'
        ]);

        $linea = new LineaMedica();
        $linea->descripcion = $validData['descripcion'];
        if(isset($request->estado)){
    		$linea->estado = 1;
    	}else{
    		$linea->estado = 0;
    	}
    	$linea->save();

    	// Session::flash('success', 'Línea Médica grabada con exito !!!' );
        // return redirect(route('lineas_medicas'));

        $message = array(
            'message' => 'Registro Guardado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    public function edit(){
    	$id     = $_POST['id'];
        $lineaId = Crypt::decrypt($id);
        $registro = LineaMedica::findOrFail($lineaId);
        return Response::json($registro);
    }

    public function update(Request $request){
    	$validData = $request->validate([
            'edescripcion' => 'required'
        ]);

        $id     = $_POST['eid'];
        $lineaId = Crypt::decrypt($id);

        $linea = LineaMedica::findOrFail($lineaId);
        $linea->descripcion = $validData['edescripcion'];
        if(isset($request->eestado)){
    		$linea->estado = 1;
    	}else{
    		$linea->estado = 0;
    	}
    	$linea->save();

    	// Session::flash('success', 'Línea Médica actualizada con exito !!!' );
        // return redirect(route('lineas_medicas'));	
        $message = array(
            'message' => 'Registro Actualizado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }
}
