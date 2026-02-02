<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Auth;
use DB;
use Response;
use Session;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Pais;

class MunicipioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $departamentos  = Departamento::orderBy('nombre', 'ASC')->get();
        $listado = DB::table('paises as p')
                   ->join('departamentos as d', 'p.id', 'd.pais_id')
                   ->join('municipios as m', 'd.id', 'm.departamento_id')
                   ->select('m.id', 'd.pais_id', 'p.nombre as pais_nombre', 'd.nombre as departamento_nombre', 'm.nombre as municipio_nombre', 'm.estado')
                   ->get();

        return view('municipios.index', compact('listado', 'departamentos'));
    }

    public function store(Request $request){
        $validData = $request->validate([
            'departamento_id' => 'required',
            'nombre'          => 'required|Unique:municipios',
        ]);

        $registro = new Municipio();
        $registro->departamento_id = $validData['departamento_id'];
        $registro->nombre          = $validData['nombre'];
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

        return redirect()->route('municipios')->with($message);
    }

    public function edit(){
        $id     = $_POST['id'];
        $municipioId = Crypt::decrypt($id);
        $registro = Municipio::findOrFail($municipioId);
        return Response::json($registro);
    }

    public function update(Request $request){
        $validData = $request->validate([
            'edepartamento_id' => 'required',
            'enombre'          => 'required'
        ]);

        $id = Crypt::decrypt($request['eid']);

        $registro                  = Municipio::findOrFail($id);
        $registro->departamento_id = $validData['edepartamento_id'];
        $registro->nombre          = $validData['enombre'];
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

        return redirect()->route('municipios')->with($message);
    }

    public function municipios_x_departamento(){
        $departamento_id = $_POST['departamento_id'];

        $listado = Municipio::where('departamento_id', $departamento_id)
                               ->where('estado', '1')
                               ->select('id', 'nombre')
                               ->get();

        Return response::json($listado);
    }
}
