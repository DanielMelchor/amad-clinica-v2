<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use DB;
use Response;
use Session;
use Carbon\Carbon;
use App\Models\AgendaController;
use App\Models\Medico;
use App\Models\Receta_Medico;
use App\Models\Especialidad;
use App\Models\Medico_Especialidad;

class MedicoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $pMedicos = Medico::where('empresa_id', Auth::user()->empresa_id)->get();
        return view('medicos.index', [
            'pMedicos' => $pMedicos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pEspecialidades = Especialidad::where('estado', 'A')->orderBy('descripcion')->get();
        return view('medicos.create', compact('pEspecialidades'));
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
            'nombres'   => 'required',
            'apellidos' => 'required',
            'titulo'    => 'required'
        ]);

        $medico = new Medico();
        $medico->empresa_id = Auth::user()->empresa_id;
        $medico->nombres   = $validData['nombres'];
        $medico->apellidos = $validData['apellidos'];
        $medico->direccion = $request->direccion;
        $medico->nombre_completo = $validData['nombres'].' '.$validData['apellidos'];
        $medico->titulo    = $request->titulo;
        $medico->nit       = $request->nit;
        $medico->telefono  = $request->telefono;
        $medico->celular   = $request->celular;
        if ($request->hasFile('medico_firma')){
            $firma             = $request->file('medico_firma')->getClientOriginalName();
            $request->file('medico_firma')->move('firmas', $firma);
            $medico->firma = 'firmas/' . $firma;    
        }
        
        if (isset($request->estado)) {
            $medico->estado = 1;
        }else{
            $medico->estado = 0;
        }

        if (isset($request->principal)) {
            $medico->principal = 'S';
        }else{
            $medico->principal = 'N';
        }

        $medico->save();

        // especialidades
        $check = $request->get('check');
        if(!is_null($request->get('check'))){
            for ($i=0; $i < count($check) ; $i++) { 
                $medico_especialidad = new Medico_Especialidad();
                $medico_especialidad->medico_id = $medico->id;
                $medico_especialidad->especialidad_id = $check[$i];
                $medico_especialidad->estado = 1;
                $medico_especialidad->save();
            }
        }

        $receta = new Receta_Medico();
        $receta->medico_id      = $medico->id;
        $receta->pagina_alto    = $request->pagina_x;
        $receta->pagina_ancho   = $request->pagina_y;
        $receta->orientacion    = $request->orientacion;
        $receta->unidad_medida  = $request->unidad_medida;
        $receta->dia_x          = $request->dia_x;
        $receta->dia_y          = $request->dia_y;
        $receta->mes_x          = $request->mes_x;
        $receta->mes_y          = $request->mes_y;
        $receta->anio_x         = $request->anio_x;
        $receta->anio_y         = $request->anio_y;
        $receta->paciente_x     = $request->paciente_x;
        $receta->paciente_y     = $request->paciente_y;
        $receta->tratamiento_x  = $request->tratamiento_x;
        $receta->tratamiento_y  = $request->tratamiento_y;
        $receta->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('editar_medico', $medico->id)->with('message','Medico grabado con exito');
        // Session::flash('success', 'Médico Guardado con exito !!!' );
        //return redirect(route('editar_medico', $medico->id));
        // return redirect::route('medicos');

        $message = array(
            'message' => 'Registro Guardado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id       = $id;
        $medicoId = Crypt::decrypt($id);
        $medico   = Medico::findOrFail($medicoId);
        $receta   = Receta_Medico::first();
        $pEspecialidades = especialidad::addSelect(['detestado' => Medico_Especialidad::where('medico_id', $id)->select(DB::raw('IFNULL(estado, "I")'))->whereColumn('especialidad_id', 'especialidades.id')->limit(1)
                            ])->get();

        return view('medicos.edit', compact('medico', 'receta', 'pEspecialidades'));
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
            'nombres'   => 'required',
            'apellidos' => 'required',
            'titulo'    => 'required'
        ]);

        $medico = Medico::findOrFail($id);
        $medico->nombres   = $validData['nombres'];
        $medico->apellidos = $validData['apellidos'];
        $medico->direccion = $request->direccion;
        $medico->titulo    = $request->titulo;
        $medico->nit       = $request->nit;
        $medico->telefono  = $request->telefono;
        $medico->celular   = $request->celular;
        $name              = $request->file('firma');

        if ($request->hasFile('medico_firma')){
            $firma         = $request->file('medico_firma')->getClientOriginalName();
            $request->file('medico_firma')->move('firmas', $firma);
            $medico->firma = 'firmas/' . $firma;    
        }

        if (isset($request->estado)) {
            $medico->estado = 1;
        }else{
            $medico->estado = 0;
        }

        if (isset($request->principal)) {
            $medico->principal = 'S';
        }else{
            $medico->principal = 'N';
        }

        $medico->save();

        // especialidades
        Medico_Especialidad::where('medico_id', $id)->delete();
        $check = $request->get('check');
        if(!is_null($request->get('check'))){
            for ($i=0; $i < count($check) ; $i++) { 
                $medico_especialidad = new Medico_Especialidad();
                $medico_especialidad->medico_id       = $medico->id;
                $medico_especialidad->especialidad_id = $check[$i];
                $medico_especialidad->estado          = 1;
                $medico_especialidad->save();
            }
        }

        $existe = Receta_Medico::where('medico_id', $id)->count();

        if ($existe == 0) {
            $receta = new Receta_Medico();
        }else{
            $receta = Receta_Medico::where('medico_id', $id)->first();
        }
        
        $receta->medico_id      = $medico->id;
        $receta->pagina_alto    = $request->pagina_x;
        $receta->pagina_ancho   = $request->pagina_y;
        $receta->orientacion    = $request->orientacion;
        $receta->unidad_medida  = $request->unidad_medida;
        $receta->dia_x          = $request->dia_x;
        $receta->dia_y          = $request->dia_y;
        $receta->mes_x          = $request->mes_x;
        $receta->mes_y          = $request->mes_y;
        $receta->anio_x         = $request->anio_x;
        $receta->anio_y         = $request->anio_y;
        $receta->paciente_x     = $request->paciente_x;
        $receta->paciente_y     = $request->paciente_y;
        $receta->tratamiento_x  = $request->tratamiento_x;
        $receta->tratamiento_y  = $request->tratamiento_y;
        $receta->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return back()->with('message','Medico grabado con exito');
        // Session::flash('success', 'Médico Actualizado con exito !!!' );
        //return redirect(route('editar_medico', $medico->id));
        // return redirect::route('medicos');

        $message = array(
            'message' => 'Registro Guardado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function borrar_firma($id)
    {
        $medico = Medico::findOrFail($id);
        $receta = Receta_Medico::where('medico_id', $id)->first();
        $pEspecialidades = especialidad::addSelect(['detestado' => Medico_Especialidad::where('medico_id', $id)->select(DB::raw('IFNULL(estado, "I")'))->whereColumn('especialidad_id', 'especialidades.id')->limit(1)
                            ])->get();
        if (!empty($medico->firma))
        {
            unlink($medico->firma);
        }
        
        $medico->firma = '';
        $medico->save();
        return view('medicos.edit', compact('medico', 'receta', 'pEspecialidades'));
    }

    public function existe_config_receta_ajax(){
        $medico_id = $_POST['medico_id'];
        $total_registros = Receta_Medico::where('medico_id', $medico_id)->count();
        return response::json($total_registros);
    }

    public function store_config_receta_ajax(){
        $receta = new Receta_Medico();
    }

    public function update_config_receta_ajax(){
        $receta = Receta_Medico::where('medico_id')->first();
    }

    public function index_medico(){
        $usuario_id = Auth::user()->medico_id;
        $medicos = Medico::where('empresa_id', Auth::user()->empresa_id)
                   ->where('estado', 1)
                   ->get();

        return view('medicos.index_medico', compact('medicos', 'usuario_id'));
    }

    public function trae_citas_x_medico(){
        $medico_id = $_POST['medico_id'];
        $fecha     = $_POST['fecha'];
        $estado    = $_POST['estado'];
        $ahoraPHP = now()->format('Y-m-d H:i:s');

        $listado = DB::table('agendas as a')
                   ->join('salas as s', 'a.sala_id', 's.id')
                   ->leftjoin('pacientes as p', 'a.paciente_id', 'p.id')
                   ->leftjoin('users as u', 'a.usuario_bloqueo', 'u.id')
                   ->leftjoin('admisiones as ad', 'ad.agenda_id', 'a.id')
                   ->where('a.empresa_id', Auth::user()->empresa_id)
                   ->where(function ($query) use ($medico_id) {
                                $query->Where('a.medico_id', '=', $medico_id);
                           })
                   ->whereDate('a.fecha_inicio', $fecha)
                   ->where(function ($query) use ($estado) {
                        $query->where('a.estado', '=', $estado);
                              // ->orWhere('a.estado', '=', 'P');
                    })
                   ->select('a.fecha_inicio', 'a.fecha_final', 'a.estado', 'a.observaciones', 'a.paciente_id', 'a.nombre_completo', 'a.telefonos', 'p.expediente_no', 'a.id', 'a.observaciones_bloqueo', 'u.name as usuario_bloqueo', 'a.fecha_bloqueo', 'a.hospital_id', 'a.sala_id', 'ad.id as admision_id', 'ad.admision_no', 's.sala_nombre', DB::raw('DATE_FORMAT(a.fecha_inicio, "%H:%i") AS horario'),
                       DB::raw("IF(a.fecha_en_clinica IS NOT NULL, TIME_FORMAT(TIMEDIFF('$ahoraPHP', a.fecha_en_clinica), '%H:%i'), '00:00') AS tiempo_espera")
                            )
                   ->orderBy('a.sala_id', 'DESC')
                   ->orderBy('a.fecha_inicio', 'ASC')
                   ->get();
        return Response::json($listado);
    }
}
