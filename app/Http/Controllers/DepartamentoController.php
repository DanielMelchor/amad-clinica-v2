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
use App\Models\Pais;

class DepartamentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $paises  = Pais::orderBy('nombre', 'ASC')->get();
        $listado = DB::table('paises as p')
                   ->join('departamentos as d', 'p.id', 'd.pais_id')
                   ->select('d.id', 'd.pais_id', 'p.nombre as pais_nombre', 'd.nombre as departamento_nombre', 'd.estado')
                   ->get();
        return view('departamentos.index', compact('listado', 'paises'));
    }

    public function store(Request $request){
        $validData = $request->validate([
            'pais_id' => 'required',
            'nombre'  => 'required|Unique:departamentos',
        ]);

        $registro = new Departamento();
        $registro->pais_id     = $validData['pais_id'];
        $registro->nombre      = $validData['nombre'];
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

        return redirect()->route('departamentos')->with($message);
    }

    public function edit(){
        $id     = $_POST['id'];
        $deptoId = Crypt::decrypt($id);
        $registro = Departamento::findOrFail($deptoId);
        return Response::json($registro);
    }

    public function update(Request $request){
        $validData = $request->validate([
            'epais_id'     => 'required',
            'enombre'      => 'required'
        ]);

        $id = Crypt::decrypt($request['eid']);

        $registro              = Departamento::findOrFail($id);
        $registro->pais_id     = $validData['epais_id'];
        $registro->nombre      = $validData['enombre'];
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

        return redirect()->route('departamentos')->with($message);
    }

    public function departamentos_x_pais(){
        $pais_id = $_POST['pais_id'];
        $listado = Departamento::where('pais_id', $pais_id)
                               ->where('estado', '1')
                               ->select('id', 'nombre')
                               ->get();

        Return response::json($listado);
    }
}
