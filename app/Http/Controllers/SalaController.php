<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Auth;
use DB;
use Response;
use Session;
use App\Models\Sala;

class SalaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salas = Sala::where('empresa_id', Auth::user()->empresa_id)->get();
        return view('salas.index', compact('salas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('salas.create');
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
            'sala_nombre'      => 'required',
            'hora_inicio'      => 'required',
            'maximo_registros' => 'required',
            'minutos_x_cita'   => 'required'
        ]);

        $hora = str_pad(substr($validData['hora_inicio'], 0, 4),5,'0');

        $sala = new Sala();
        $sala->empresa_id           = Auth::user()->empresa_id;
        $sala->sala_nombre          = $validData['sala_nombre'];
        $sala->maximo_registros     = $validData['maximo_registros'];
        $sala->minutos_por_registro = $validData['minutos_x_cita'];
        $sala->hora_inicio          = $hora;
        if (isset($request['estado'])) {
            $sala->estado               = 1;
        }else{
            $sala->estado               = 0;
        }
        $sala->save();

        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );

        return redirect()->route('salas')->with($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function show(Sala $sala)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $id = $_POST['id'];
        $salaId = Crypt::decrypt($id);
        $sala = sala::where('id', $salaId)
                ->select('id', 'sala_nombre', 'maximo_registros', 'minutos_por_registro', 'estado',
                         'hora_inicio')
                ->first();
        //$hora = str_pad(substr($sala->hora_inicio, 0, 4),5,'0');
        return Response::json($sala);
        //return view('salas.edit', compact('sala', 'hora'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validData = $request->validate([
            'esala_nombre'      => 'required',
            'ehora_inicio'      => 'required',
            'emaximo_registros' => 'required',
            'eminutos_x_cita'   => 'required'
        ]);

        $id = $request['eid'];
        $salaId = Crypt::decrypt($id);

        $sala = Sala::findOrFail($salaId);
        $sala->sala_nombre          = $validData['esala_nombre'];
        $sala->maximo_registros     = $validData['emaximo_registros'];
        $sala->minutos_por_registro = $validData['eminutos_x_cita'];
        $sala->hora_inicio          = $validData['ehora_inicio'];
        if (isset($request->eestado)) {
            $sala->estado = 1;
        }else{
            $sala->estado = 0;
        }
        
        $sala->save();

        $message = array(
            'message' => 'Registro actualizado con exito !!!',
            'type'    => 'success'
        );

        return redirect()->route('salas')->with($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sala $sala)
    {
        //
    }

    public function get_salas(){
        $salas = DB::table('salas as s')
                 ->join('salas_x_usuarios as sxu', 's.id', 'sxu.sala_id')
                 ->where('s.empresa_id', Auth::user()->empresa_id)
                 ->where('s.estado', 'A')
                 ->where('sxu.user_id', Auth::user()->id)
                 ->select('s.id', 's.sala_nombre')
                 ->get();

        return Response::json($salas);
    }

    public function salas_x_empresa(){
        $empresa_id = $_REQUEST['empresa_id'];

        $listado = Sala::where('empresa_id', $empresa_id)
                   ->where('estado', 1)
                   ->select('id', 'sala_nombre')
                   ->get();

        return Response::json($listado);
    }
}
