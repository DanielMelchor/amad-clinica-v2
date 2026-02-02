<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Redirect;
use Response;
use Session;
use App\Models\InvClasificacion;

class InvClasificacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $registros = InvClasificacion::paginate(15);
        return view('invclasificaciones.index', compact('registros'));
    }

    public function store(Request $request){
        $validData = $request->validate([
            'nombre' => 'required'
        ]);
        $registro = new InvClasificacion();
        $registro->nombre = $validData['nombre'];

        if(isset($request->definir_caracteristica)){
            $registro->definir_caracteristica = 1;
        }else{
            $registro->definir_caracteristica = 0;
        }

        if(isset($request->definir_medidas)){
            $registro->definir_medidas = 1;
        }else{
            $registro->definir_medidas = 0;
        }

        if(isset($request->definir_dosis)){
            $registro->definir_dosis = 1;
        }else{
            $registro->definir_dosis = 0;
        }

        if(isset($request->estado)){
            $registro->estado = 1;
        }else{
            $registro->estado = 0;
        }

        $registro->save();

        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    public function edit(){     
        $id     = $_POST['id'];
        $registroId = Crypt::decrypt($id);
        $registro = InvClasificacion::findOrFail($registroId);
        return Response::json($registro);
    }

    public function update(Request $request){
        $validData = $request->validate([
            'enombre' => 'required'
        ]);


        $id      = $_POST['eid'];
        $registroId = Crypt::decrypt($id);
        $registro   = InvClasificacion::findOrFail($registroId);
        $registro->nombre = $validData['enombre'];
        
        if(isset($request->edefinir_caracteristica)){
            $registro->definir_caracteristica = 1;
        }else{
            $registro->definir_caracteristica = 0;
        }

        if(isset($request->edefinir_medidas)){
            $registro->definir_medidas = 1;
        }else{
            $registro->definir_medidas = 0;
        }

        if(isset($request->edefinir_dosis)){
            $registro->definir_dosis = 1;
        }else{
            $registro->definir_dosis = 0;
        }

        if(isset($request->eestado)){
            $registro->estado = 1;
        }else{
            $registro->estado = 0;
        }

        $registro->save();

        $message = array(
            'message' => 'Registro actualizado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    public function trae_extras(){
        $id     = $_POST['id'];
        $registro = InvClasificacion::findOrFail($id);
        return Response::json($registro);
    }
}
