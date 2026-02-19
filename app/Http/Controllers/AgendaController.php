<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use DB;
use DateTime;
use Session;
use Redirect;
use Carbon\Carbon;
use App\Models\Agenda;
use App\Models\Aseguradora;
use App\Models\Hospital;
use App\Models\Medico;
use App\Models\Paciente;

class AgendaController extends Controller
{

    public function nuevo_index(){
        date_default_timezone_set('America/Guatemala');
        $today        = Carbon::now()->format('Y-m-d');
        $cita         = Agenda::whereDate('fecha_inicio', $today)->get();
        $medicos      = Medico::where('empresa_id', Auth::user()->empresa_id)->where('estado', 1)->get();
        $hospitales   = hospital::where('estado', 1)->get();
        $aseguradoras = Aseguradora::where('estado',1)->get();
        $pacientes    = Paciente::where('estado', 1)->get();
        $medico       = Medico::where('empresa_id', Auth::user()->empresa_id)->where('estado', 1)->first();
        $salas   = DB::table('salas as s')
                   ->join('salas_x_usuarios as sxu', 's.id', 'sxu.sala_id')
                   ->where('s.empresa_id', Auth::user()->empresa_id)
                   ->where('sxu.user_id', Auth::user()->id)
                   ->where('s.estado', 1)
                   ->select('s.id', 's.sala_nombre')
                   ->get();
        $sala_seleccionada = Auth::user()->sala_principal_id;
        $tipo_admisiones = DB::table('empresa_tipo_atenciones as eta')
                           ->join('tipo_atenciones as ta', 'eta.tipo_atencion_id', 'ta.id')
                           ->where('eta.empresa_id', Auth::user()->empresa_id)
                           ->where('eta.estado', 1)
                           ->where('ta.estado', 1)
                           ->select('ta.id', 'ta.nombre')
                           ->get();

        $user = Auth::user();

        $permissions = $user->getPermissions();

        return view('agenda.nuevo_index', compact('medicos', 'medico', 'today', 'hospitales', 'pacientes', 'aseguradoras', 'salas', 'sala_seleccionada', 'cita', 'tipo_admisiones', 'permissions'));
    }

    //=========================================================================================================
    // funcion que devuelve registros para llenar la agenda
    //=========================================================================================================
    public function trae_citas(){
        $medico_id = $_POST['medico_id'];
        $fecha     = $_POST['fecha'];
        $estado    = $_POST['estado'];
        //$sala      = $_POST['sala'];
        // dd('entre con medico '.$medico_id.' fecha '.$fecha.' estado '.$estado);

        switch ($estado) {
            case 'T':
                $listado = DB::table('agendas as a')
                       ->join('salas as s', 'a.sala_id', 's.id')
                       ->leftjoin('pacientes as p', 'a.paciente_id', 'p.id')
                       ->leftjoin('users as u', 'a.usuario_bloqueo', 'u.id')
                       ->leftjoin('admisiones as ad', 'ad.agenda_id', 'a.id')
                       ->where('a.empresa_id', Auth::user()->empresa_id)
                       ->where(function ($query) use ($medico_id) {
                                    $query->whereNull('a.medico_id')
                                          ->orWhere('a.medico_id', '=', $medico_id);
                               })
                       ->whereDate('a.fecha_inicio', $fecha)
                       ->select('a.fecha_inicio', 'a.fecha_final', 'a.estado', 'a.observaciones', 'a.paciente_id', 'a.nombre_completo', 'a.telefonos', 'p.expediente_no', 'a.id', 'a.observaciones_bloqueo', 'u.name as usuario_bloqueo', 'a.fecha_bloqueo', 'a.hospital_id', 'a.sala_id', 'ad.id as admision_id', 'ad.admision_no', 's.sala_nombre', DB::raw('DATE_FORMAT(a.fecha_inicio, "%H:%i") AS horario'),
                           'a.paciente_en_clinica'
                                )
                       ->orderBy('a.sala_id', 'DESC')
                       ->orderBy('a.fecha_inicio', 'ASC')
                       ->get();
                break;
            case 'A' :
                // dd(Auth::user()->empresa_id.' - '.$medico_id.' - '.$fecha.' - '.$estado);
                $listado = DB::table('agendas as a')
                       ->join('salas as s', 'a.sala_id', 's.id')
                       ->leftjoin('pacientes as p', 'a.paciente_id', 'p.id')
                       ->leftjoin('users as u', 'a.usuario_bloqueo', 'u.id')
                       ->leftjoin('admisiones as ad', 'ad.agenda_id', 'a.id')
                       ->where('a.empresa_id', Auth::user()->empresa_id)
                       ->where(function ($query) use ($medico_id) {
                                    $query->whereNull('a.medico_id')
                                          ->orWhere('a.medico_id', '=', $medico_id);
                               })
                       ->whereDate('a.fecha_inicio', $fecha)
                       ->where(function ($query) use ($estado) {
                            $query->where('a.estado', '=', $estado)
                                  ->orWhere('a.estado', '=', 'P');
                        })
                       ->select('a.fecha_inicio', 'a.fecha_final', 'a.estado', 'a.observaciones', 'a.paciente_id', 'a.nombre_completo', 'a.telefonos', 'p.expediente_no', 'a.id', 'a.observaciones_bloqueo', 'u.name as usuario_bloqueo', 'a.fecha_bloqueo', 'a.hospital_id', 'a.sala_id', 'ad.id as admision_id', 'ad.admision_no', 's.sala_nombre', DB::raw('DATE_FORMAT(a.fecha_inicio, "%H:%i") AS horario'), 'a.paciente_en_clinica')
                       ->orderBy('a.sala_id', 'DESC')
                       ->orderBy('a.fecha_inicio', 'ASC')
                       ->get();
                break;
            default:
                $listado = DB::table('agendas as a')
                       ->leftjoin('pacientes as p', 'a.paciente_id', 'p.id')
                       ->leftjoin('users as u', 'a.usuario_bloqueo', 'u.id')
                       ->leftjoin('admisiones as ad', 'ad.agenda_id', 'a.id')
                       ->where('a.empresa_id', Auth::user()->empresa_id)
                       ->where('a.medico_id', $medico_id)
                       ->whereDate('a.fecha_inicio', $fecha)
                       ->where('a.estado', $estado)
                       ->select('a.fecha_inicio', 'fecha_final', 'a.estado', 'a.observaciones', 'a.paciente_id', 'a.nombre_completo', 'a.telefonos', 'p.expediente_no', 'a.id', 'a.observaciones_bloqueo', 'u.name as usuario_bloqueo', 'a.fecha_bloqueo', 'a.hospital_id', 'a.sala_id', 'ad.id as admision_id', 'ad.admision_no', 'a.paciente_en_clinica')
                       ->orderBy('a.sala_id', 'DESC')
                       ->orderBy('a.fecha_inicio', 'ASC')
                       ->get();
                break;
        }
        return response::json($listado);
    }

    //=========================================================================================================
    // Actualizar registro en agenda
    //=========================================================================================================

    public function update_nuevo(Request $request)
    {
        $validData = $request->validate([
            'agenda_id'     => 'required',
            'fecha_cita'            => 'required|date_format:Y-m-d\TH:i',
            'edit_nombre_completo'  => 'required',
            'edit_telefonos'        => 'required',
            'edit_hospital_id'      => 'required|exists:hospitales,id',
            'edit_medico_id'        => 'required|exists:medicos,id',
        ]);

        try {
            return DB::transaction(function () use ($request, $validData) {
                $agenda = Agenda::findOrFail($validData['agenda_id']);
                $agenda->update([
                            'medico_id'       => $validData['edit_medico_id'],
                            'hospital_id'     => $validData['edit_hospital_id'],
                            'nombre_completo' => $validData['edit_nombre_completo'],
                            'telefonos'       => $validData['edit_telefonos'],
                            'observaciones'   => $request->edit_observaciones,
                            'paciente_id'     => $request->edit_paciente_id ?: null,
                            'estado'          => 'A',
                        ]);

                    // CAMBIO: Devolver JSON en lugar de redirect
                    return response()->json([
                        'message' => '¡Registro actualizado con éxito!',
                        'type'    => 'success'
                    ]);
                });
        } catch (\Exception $e) {
            // Log::error("Error actualizando agenda ID {$request->context_agenda_id}: " . $e->getMessage());

            // CAMBIO: Devolver JSON de error con código 500
            return response()->json([
                'message' => 'Hubo un problema al actualizar el registro. {$request->context_agenda_id}: ' . $e->getMessage(),
                'type'    => 'error'
            ], 500);
        }
    }

    public function marcar_cancelada_ajax(Request $request){
        $validator = Validator::make($request->all(), [
            'cita_id'       => 'required|exists:agendas,id',
            'observaciones' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos: ' . implode(', ', $validator->errors()->all()),
                'type'    => 'error'
            ], 422);
        }
        DB::beginTransaction();
        try {
            $cita = Agenda::findOrFail($request->cita_id);
            if ($cita->estado === 'R') {
                return response()->json([
                    'message' => 'Esta cita ya ha sido marcada como Cancelada previamente.',
                    'type'    => 'error'
                ]);
            }else{
                $cita->estado = 'C'; // R de Realizada / Finalizada
                $cita->observaciones = $request->input('observaciones');
                $cita->save();

                $registro = new agenda();
                $registro->empresa_id   = $cita->empresa_id;
                $registro->sala_id      = $cita->sala_id;
                $registro->fecha_inicio = $cita->fecha_inicio;
                $registro->fecha_final  = $cita->fecha_final;
                $registro->estado       = 'P';
                $registro->save();

                // $cita->bitacoras()->create([
                //     'proceso'       => 'FINALIZACIÓN',
                //     'observaciones' => 'Cita marcada como realizada. Obs: ' . $request->input('observaciones'),
                //     'user_id'       => Auth::id() // Si tienes columna de usuario en bitácora
                // ]);

                DB::commit();

                return response()->json([
                    'message' => '¡Cita Cancelada con Éxito!',
                    'type'    => 'success'
                ]);
            }
        }catch (\Throwable $e) {
            DB::rollBack();
            
            // Log del error para el desarrollador, pero mensaje genérico para el usuario
            // Log::error("Error al finalizar cita ID {$request->cita_id}: " . $e->getMessage());

            DB::rollBack();
            return response()->json([
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function marcar_realizada_ajax(Request $request){
        
        $validator = Validator::make($request->all(), [
            'cita_id'       => 'required|exists:agendas,id',
            'observaciones' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos: ' . implode(', ', $validator->errors()->all()),
                'type'    => 'error'
            ], 422);
        }
        DB::beginTransaction();
        try {
            $cita = Agenda::findOrFail($request->cita_id);
            if ($cita->estado === 'R') {
                return response()->json([
                    'message' => 'Esta cita ya ha sido marcada como realizada previamente.',
                    'type'    => 'error'
                ]);
            }else{
                $cita->estado = 'R'; // R de Realizada / Finalizada
                $cita->observaciones = $request->input('observaciones');
                $cita->save();

                // $cita->bitacoras()->create([
                //     'proceso'       => 'FINALIZACIÓN',
                //     'observaciones' => 'Cita marcada como realizada. Obs: ' . $request->input('observaciones'),
                //     'user_id'       => Auth::id() // Si tienes columna de usuario en bitácora
                // ]);

                DB::commit();

                return response()->json([
                    'message' => '¡Cita Finalizada con Éxito!',
                    'type'    => 'success'
                ]);
            }
        }catch (\Throwable $e) {
            DB::rollBack();
            
            // Log del error para el desarrollador, pero mensaje genérico para el usuario
            // Log::error("Error al finalizar cita ID {$request->cita_id}: " . $e->getMessage());

            DB::rollBack();
            return response()->json([
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function marcar_espacio_bloqueado(){
        $cita_id = $_POST['cita_id'];
        $observaciones = $_POST['observaciones'];
        $cita = Agenda::where('id', $cita_id)->first();
        $cita->estado = 'B';
        $cita->observaciones_bloqueo = $observaciones;
        $cita->usuario_bloqueo = Auth::user()->id;
        $cita->fecha_bloqueo  = now();
        $cita->save();
        return Response::json('espacio Bloqueado con Exito !!!');
    }

    public function fullcalendar_index(){
        return view('agenda.fullcalendar');
    }

    public function paciente_citas(){
        $paciente_id = $_POST['paciente_id'];

        $admisiones = DB::table('admisiones as a')
                      ->join('admision_atenciones as aa', 'a.id', 'aa.admision_id')
                      ->join('tipo_atenciones as ta', 'aa.tipo_atencion_id', 'ta.id')
                      ->select('a.id', 'a.admision_no', 'a.agenda_id', 'ta.nombre');


        $registros = DB::table('agendas as a')
                     ->join('medicos as m', 'a.medico_id', 'm.id')
                     ->leftjoin('hospitales as h', 'a.hospital_id', 'h.id')
                     ->leftJoinSub($admisiones, 'subadm', function ($join) {
                                        $join->on('a.id', '=', 'subadm.agenda_id');
                                })
                     ->where('a.paciente_id', $paciente_id)
                     ->orderBy('a.fecha_inicio', 'ASC')
                     ->select(DB::raw('DATE_FORMAT(a.fecha_inicio, "%d/%m/%Y %H:%i") as fecha_inicio'), DB::raw('a.estado'), 'subadm.admision_no', 'subadm.nombre as tipo_atencion', 'm.nombre_completo', 'h.nombre', 'a.id')
                     ->get();

        return Response::json($registros);
    }

    public function confirmar_ingreso(){
        $cita_id = $_POST['cita_id'];
        $registro = Agenda::findOrFail($cita_id);
        if ($registro->paciente_en_clinica == 1) {
            return response()->json([
                'message' => 'El paciente ya ha sido registrado previamente en clínica.',
                'type'    => 'error' 
            ]); // Código 400 para indicar una solicitud que no se puede procesar
        }
        if ($registro->estado == 'C') {
            return response()->json([
                'message' => 'No se puede marcar ingreso: La cita está CANCELADA.',
                'type'    => 'error'
            ]);
        }
        
        if ($registro->estado == 'R') {
            return response()->json([
                'message' => 'No se puede marcar ingreso: La cita fue FINALIZADA.',
                'type'    => 'warning'
            ]);
        }
        try {
            $registro->fecha_en_clinica = Carbon::now();
            $registro->paciente_en_clinica = 1;
            $registro->save();

            return response()->json(['message' => '! Registro de asistencia, finalizado con éxito !',
                                     'type'    => 'success'
                                   ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al actualizar el ingreso: ' . $e->getMessage(),
                'type'    => 'error'
            ], 500);
        }
    }
}