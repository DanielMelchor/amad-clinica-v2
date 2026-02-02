<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Auth;
use DB;
use Redirect;
use Response;
use Session;
use App\Models\InvFamilia;


class InvFamiliaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $registros = InvFamilia::paginate(15);
        return view('invFamilias.index', compact('registros'));
    }

    public function store(Request $request){
        $validData = $request->validate([
            'nombre' => 'required'
        ]);
        $registro = new InvFamilia();
        $registro->empresa_id = Auth::user()->empresa_id;
        $registro->nombre = $validData['nombre'];

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
        $registro = InvFamilia::findOrFail($registroId);
        return Response::json($registro);
    }

    public function update(Request $request){
        $validData = $request->validate([
            'enombre' => 'required'
        ]);


        $id      = $_POST['eid'];
        $registroId = Crypt::decrypt($id);
        $registro   = InvFamilia::findOrFail($registroId);
        $registro->nombre = $validData['enombre'];
        
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
}
