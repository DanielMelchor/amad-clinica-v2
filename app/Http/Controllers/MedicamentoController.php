<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Response;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Models\medicamento;
use App\Models\dosis;
use App\Models\medicamento_dosis;

class MedicamentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $pMedicamentos = medicamento::all();
        return view('medicamentos.index', [
            'pMedicamentos' => $pMedicamentos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $destinos = \DB::table('dosis')->where('estado', 'A')
                     ->select('dosis.id', 'dosis.descripcion')->get();
        return view('medicamentos.create', compact('destinos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validData = $request->validate([
            'nombre' => 'required'
        ]);

        $medicamento = new medicamento();
        $medicamento->nombre = $validData['nombre'];
        //$medico->firma = $file_name;
        if (isset($request->estado)) {
            $medicamento->estado = 'A';
        }else{
            $medicamento->estado = 'I';
        }
        $medicamento->save();

        /*
        Dosis por medicamento
        */
        if (isset($request->destinos)) {
            for ($i=0; $i < count($request->destinos); $i++) {
                $mdosis = new medicamento_dosis();
                $mdosis->medicamento_id     = $medicamento->id;
                $mdosis->dosis_id           = $request->destinos[$i]['destino_id'];
                $mdosis->descripcion_receta = $request->destinos[$i]['descripcion'];
                $mdosis->estado             = 'A';
                $mdosis->save();
            }
        }
        

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('medicamentos')->with('message','Medicmento grabado con exito');
        // Session::flash('success', 'Medicamento Guardado con exito !!!' );
        // return redirect(route('medicamentos'));
        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pMedicamento = medicamento::findOrFail($id);
        $medicamento_dosis = \DB::table('medicamento_dosis')->
                             join('dosis', 'medicamento_dosis.dosis_id', 'dosis.id')->
                             where('medicamento_id', $id)->select('dosis.descripcion', 'medicamento_dosis.descripcion_receta', 'medicamento_dosis.estado', 'medicamento_dosis.id')->get();
        $destinos = \DB::table('dosis')->where('estado', 'A')
                     ->select('dosis.id', 'dosis.descripcion')->get();
        return view('medicamentos.edit', compact('pMedicamento', 'destinos', 'medicamento_dosis'));
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validData = $request->validate([
            'nombre' => 'required'
        ]);

        $medicamento = medicamento::findOrFail($id);
        $medicamento->nombre = $validData['nombre'];
        //$medico->firma = $file_name;
        if (isset($request->estado)) {
            $medicamento->estado = 'A';
        }else{
            $medicamento->estado = 'I';
        }
        $medicamento->save();

        if (isset($request->destinos)) {
            for ($i=0; $i < count($request->destinos); $i++) {
                if(isset($request->destinos[$i]['dosis_id'])){
                    $existe = medicamento_dosis::where('medicamento_id', $medicamento->id)->where('dosis_id', $request->destinos[$i]['dosis_id'])->select('id')->first();
                    if (isset($existe)) {
                        $mdosis = medicamento_dosis::findOrFail($existe->id);
                        $mdosis->descripcion_receta = $request->destinos[$i]['descripcion_receta'];
                        $mdosis->estado = $request->destinos[$i]['estado'];
                    }else{
                        $mdosis = new medicamento_dosis();
                        $mdosis->medicamento_id     = $medicamento->id;
                        $mdosis->dosis_id           = $request->destinos[$i]['dosis_id'];
                        $mdosis->descripcion_receta = $request->destinos[$i]['descripcion_receta'];
                        $mdosis->estado = 'A';
                        
                    }
                }else{
                    dd($request->destinos[$i]);
                    $mdosis = new medicamento_dosis();
                    $mdosis->medicamento_id     = $medicamento->id;
                    $mdosis->dosis_id           = $request->destinos[$i]['dosis_id'];
                    $mdosis->descripcion_receta = $request->destinos[$i]['descripcion_receta'];
                    $mdosis->estado = 'A';
                }
                
                $mdosis->save();
            }
        }

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('medicamentos')->with('message','Medicamento grabado con exito');
        // Session::flash('success', 'Medicamento Actualizado con exito !!!' );
        // return redirect(route('medicamentos'));
        $message = array(
            'message' => 'Registro actualizado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    public function dosisByMedicamento(Request $request, $id){
        if($request->ajax()){
            $dosis = medicamento_dosis::dosis($id);
            return response()->json($dosis);
        }
    }

    public function trae_dosis_medicamento(){
        $medicamento_id = $_POST['medicamento_id'];
        $medicamento_dosis = \DB::table('medicamento_dosis')
                             ->join('dosis', 'medicamento_dosis.dosis_id', 'dosis.id')
                             ->where('medicamento_id', $medicamento_id)
                             ->select('dosis.descripcion', 'medicamento_dosis.descripcion_receta', 'medicamento_dosis.estado', 'medicamento_dosis.id', 'medicamento_dosis.dosis_id')
                             ->get();
        return Response::json($medicamento_dosis);
    }

    public function receta(){

        //print_r($_POST);
        $dosis_id = $_POST['dosis_id'];
        $data = medicamento_dosis::where('id', $dosis_id)->first();
        return Response::json($data);
    }
}
