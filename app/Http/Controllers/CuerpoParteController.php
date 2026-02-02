<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Response;
use Session;
use App\Models\CuerpoParte;

class CuerpoParteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	$partes = CuerpoParte::all();
    	return view('partes.index', compact('partes'));
    }

    public function store(Request $request){
    	$validData = $request->validate([
            'nombre' => 'required'
        ]);

        $parte = new CuerpoParte();
        $parte->nombre = $validData['nombre'];
        $parte->texto_plural = $request->texto_plural;
        if (isset($request->plural)) {
        	$parte->plural = 'S';
        }else{
        	$parte->plural = 'N';
        }
        if (isset($request->estado)) {
        	$parte->estado = 1;
        }else{
        	$parte->estado = 0;
        }

        $parte->save();

        // Session::flash('success', 'Parte del Cuerpo Guardada con exito !!!' );
        // return redirect(route('partes_cuerpo'));

        $message = array(
            'message' => 'Registro Guardado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);

    }

    public function edit(){
    	$id       = $_POST['id'];
        $parteId   = Crypt::decrypt($id);
        $registro = CuerpoParte::findOrFail($parteId);
        return Response::json($registro);
    }

    public function update(Request $request){
    	$validData = $request->validate([
            'enombre' => 'required'
        ]);

        $id       = $_POST['eid'];
        $parteId   = Crypt::decrypt($id);

        $parte = CuerpoParte::findOrFail($parteId);
        $parte->nombre = $validData['enombre'];
        $parte->texto_plural = $request->etexto_plural;
        if (isset($request->eplural)) {
        	$parte->plural = 'S';
        }else{
        	$parte->plural = 'N';
        }
        if (isset($request->eestado)) {
        	$parte->estado = 1;
        }else{
        	$parte->estado = 0;
        }

        $parte->save();

        // Session::flash('success', 'Parte del Cuerpo Actualizada con exito !!!' );
        // return redirect(route('partes_cuerpo'));
        $message = array(
            'message' => 'Registro Actualizado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    public function get_partes(){
        $partes = CuerpoParte::where('estado', 1)->orderBy('id')->get();
        return Response::json($partes);
    }
}
