<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Auth;
use Response;
use Session;
use App\Models\Pais;

class PaisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $listado = Pais::get();
        return view('paises.index', compact('listado'));
    }

    public function store(Request $request){
        $validData = $request->validate([
            'nombre'      => 'required|Unique:paises',
            'abreviatura' => 'required|Unique:paises',
            'cod_area'    => 'required'
        ]);

        $registro = new Pais();
        $registro->nombre      = $validData['nombre'];
        $registro->abreviatura = strtoupper($validData['abreviatura']);
        $registro->cod_area    = $validData['cod_area'];
        if (isset($request->estado)) {
            $registro->estado = 1;
        }else{
            $registro->estado = 0;
        }

        $registro->save();

        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );

        return redirect()->route('pais')->with($message);

    }

    public function edit(){
        $id     = $_GET['id'];
        $paisId = Crypt::decrypt($id);
        $registro = Pais::findOrFail($paisId);
        return Response::json($registro);
    }

    public function update(Request $request){
        $validData = $request->validate([
            'enombre'      => 'required',
            'eabreviatura' => 'required',
            'ecod_area'    => 'required'
        ]);

        $id = Crypt::decrypt($request['eid']);

        $registro              = Pais::findOrFail($id);
        $registro->nombre      = $validData['enombre'];
        $registro->abreviatura = $validData['eabreviatura'];
        $registro->cod_area    = $validData['ecod_area'];
        if (isset($request->eestado)) {
            $registro->estado = 1;
        }else{
            $registro->estado = 0;
        }

        $registro->save();

        $message = array(
            'message' => 'Registro actualizado con exito !!!',
            'type'    => 'success'
        );

        return redirect()->route('pais')->with($message);
    }
}
