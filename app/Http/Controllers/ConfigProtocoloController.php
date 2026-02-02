<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Session;
use DB;
use Response;
use Auth;
use Carbon;
use App\Models\Agenda;
use App\Models\Aseguradora;
use App\Models\CuerpoParte;
use App\Models\ConfigAgendaProtocolo;
use App\Models\ConfigDetalleProtocolo;
use App\Models\ConfigMaestroProtocolo;
use App\Models\ConfigMetastasisProtocolo;
use App\Models\Diagnostico;
use App\Models\DetalleProtocolo;
use App\Models\DetalleMovimiento;
use App\Models\Hospital;
use App\Models\MaestroProtocolo;
use App\Models\Medico;
use App\Models\MetastasisProtocolo;
use App\Models\Paciente;
use App\Models\Producto;
use App\Models\Sala;
use App\Models\Unidadmedida;

class ConfigProtocoloController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $listado = DB::table('config_maestro_protocolos as cmp')
                   ->join('pacientes as p', 'cmp.paciente_id', 'p.id')
                   ->join('diagnosticos as d', 'cmp.diagnostico_id', 'd.id')
                   ->join('cuerpo_partes as cp', 'cmp.cuerpo_parte_id', 'cp.id')
                   ->join('hospitales as h', 'cmp.lugar_tratamiento_id', 'h.id')
                   ->select('cmp.id','cmp.paciente_id', 'p.nombre_completo', 'cmp.diagnostico_id', 'd.descripcion as diagnostico_descripcion', 'cmp.cuerpo_parte_id', 'cp.nombre as parte_cuerpo_nombre', 'cmp.lugar_tratamiento_id', 'h.nombre as hospital_nombre', DB::raw('CASE cmp.tipo_tratamiento WHEN "A" THEN "Ambulatorio" WHEN "I" THEN "Interno" ELSE "No Definido" END AS tipo_tratamiento'), 'cantidad_ciclos', 'frecuencia_ciclos')
                   ->get();
        //dd($listado);
        return view('protocolos.index', compact('listado'));
    }

    public function create(){
        $pacientes     = Paciente::where('empresa_id', Auth::user()->empresa_id)->get();
        $diagnosticos  = Diagnostico::where('estado', 'A')->get();
        $cuerpo_partes = CuerpoParte::where('estado', 'A')->orderBy('nombre')->get();
        $hospitales    = Hospital::where('empresa_id', Auth::user()->empresa_id)->where('estado','A')->get();
        $aseguradoras  = Aseguradora::where('estado', 'A')->get();
        $medicos       = Medico::where('empresa_id', Auth::user()->empresa_id)->where('estado', 'A')->get();
        $productos     = Producto::where('empresa_id', Auth::user()->empresa_id)->where('estado', 'A')->orderBy('clasificacion', 'ASC')->orderBy('descripcion', 'ASC')->get();
        $medidas       = DB::table('producto_medidas as pm')
                         ->join('unidad_medidas as um', 'pm.unidad_medida_id', 'um.id')
                         ->join('productos as p', 'pm.producto_id', 'p.id')
                         ->where('p.empresa_id', Auth::user()->empresa_id)
                         ->where('um.estado', 'A')
                         ->select('um.id', 'um.descripcion', 'pm.producto_id')
                         ->get();
        return view('protocolos.create', compact('pacientes', 'diagnosticos', 'cuerpo_partes', 'hospitales', 'aseguradoras', 'productos', 'medicos', 'medidas'));
    }

    public function store_ajax(){
        $paciente_id            = $_POST['paciente_id'];
        $fecha_nacimiento       = $_POST['fecha_nacimiento'];
        $edad                   = $_POST['edad'];
        $diagnostico_id         = $_POST['diagnostico_id'];
        $cuerpo_parte_id        = $_POST['cuerpo_parte_id'];
        $lugar_tratamiento_id   = $_POST['hospital_id'];
        $medico_id              = $_POST['medico_id'];
        $aseguradora_id         = $_POST['aseguradora_id'];
        $poliza_no              = $_POST['poliza_no'];
        $proveedor_medicamento  = $_POST['proveedor_medicamento'];
        $inmunoterapia          = $_POST['inmunoterapia'];
        $tipo_tratamiento       = $_POST['tipo_tratamiento'];
        $cantidad_ciclos        = $_POST['cantidad_ciclos'];
        $frecuencia_ciclos      = $_POST['frecuencia_ciclos'];
        $fecha_inicio           = $_POST['fecha_inicio'];
        $data_productos         = (array) json_decode($_POST['productos_db'], true);
        $data_metastasis        = (array) json_decode($_POST['metastasis_db'], true);
        $data_agenda            = (array) json_decode($_POST['agenda_db'], true);

        $protocolo = new ConfigMaestroProtocolo();
        $protocolo->empresa_id            = Auth::user()->empresa_id;
        $protocolo->paciente_id           = $paciente_id;
        $protocolo->fecha_nacimiento      = $fecha_nacimiento;
        $protocolo->edad                  = $edad;
        $protocolo->diagnostico_id        = $diagnostico_id;
        $protocolo->cuerpo_parte_id       = $cuerpo_parte_id;
        $protocolo->lugar_tratamiento_id  = $lugar_tratamiento_id;
        $protocolo->medico_id             = $medico_id;
        $protocolo->aseguradora_id        = $aseguradora_id;
        $protocolo->poliza_no             = $poliza_no;
        $protocolo->proveedor_medicamento = $proveedor_medicamento;
        $protocolo->inmunoterapia         = $inmunoterapia;
        $protocolo->tipo_tratamiento      = $tipo_tratamiento;
        $protocolo->cantidad_ciclos       = $cantidad_ciclos;
        $protocolo->frecuencia_ciclos     = $frecuencia_ciclos;
        $protocolo->fecha_inicio          = $fecha_inicio;
        $protocolo->estado                = 'P';
        $protocolo->save();

        $totalRegistros  = count($data_productos);
        if ($totalRegistros > 0) {
            for ($i=0; $i < $totalRegistros ; $i++) {
                $detalle = new ConfigDetalleProtocolo();
                $detalle->config_maestro_protocolo_id = $protocolo->id;
                $detalle->producto_id      = $data_productos[$i]['producto_id'];
                $detalle->cantidad         = $data_productos[$i]['cantidad'];
                $detalle->unidad_medida_id = $data_productos[$i]['medida_id'];
                $detalle->precio_unitario  = $data_productos[$i]['preciouni'];
                $detalle->precio_total     = $data_productos[$i]['preciotot'];
                $detalle->estado           = 'P';
                $detalle->save();
            }
        }

        $totalRegistros  = 0;
        $totalRegistros  = count($data_metastasis);
        if ($totalRegistros > 0) {
            for ($i=0; $i < $totalRegistros ; $i++) {
                $metastasis = new ConfigMetastasisProtocolo();
                $metastasis->config_maestro_protocolo_id = $protocolo->id;
                $metastasis->cuerpo_parte_id  = $data_metastasis[$i]['cuerpo_parte_id'];
                $metastasis->save();
            }
        }

        $totalRegistros  = 0;
        $totalRegistros  = count($data_agenda);
        if ($totalRegistros > 0) {
            for ($i=0; $i < $totalRegistros ; $i++) {
                $agenda = new ConfigAgendaProtocolo();
                $agenda->config_maestro_protocolo_id = $protocolo->id;
                $agenda->ciclo_no    = $data_agenda[$i]['ciclo_no'];
                $agenda->fecha_ciclo = $data_agenda[$i]['fecha_ciclo'];
                $agenda->agenda_id   = $data_agenda[$i]['horario'];
                $agenda->sala_id     = $data_agenda[$i]['sala_id'];
                $agenda->estado      = 'A';
                $agenda->save();
            }
        }

        for ($i=1; $i <= $protocolo->cantidad_ciclos; $i++) { 
            $cagenda = ConfigAgendaProtocolo::where('config_maestro_protocolo_id', $protocolo->id)
                       ->where('ciclo_no', $i)
                       ->first();
            $paciente     = Paciente::findOrFail($protocolo->paciente_id)->first();
            $diagnostico  = Diagnostico::findOrFail($protocolo->diagnostico_id)->first();
            $cuerpo_parte = CuerpoParte::findOrFail($protocolo->cuerpo_parte_id)->first();

            $maestro = new MaestroProtocolo();
            $maestro->empresa_id                  = Auth::user()->empresa_id;
            $maestro->config_maestro_protocolo_id = $protocolo->id;
            $maestro->paciente_id                 = $protocolo->paciente_id;
            $maestro->fecha_nacimiento            = $protocolo->fecha_nacimiento;
            $maestro->edad                        = $protocolo->edad;
            $maestro->diagnostico_id              = $protocolo->diagnostico_id;
            $maestro->cuerpo_parte_id             = $protocolo->cuerpo_parte_id;
            $maestro->lugar_tratamiento_id        = $protocolo->lugar_tratamiento_id;
            $maestro->medico_id                   = $protocolo->medico_id;
            $maestro->aseguradora_id              = $protocolo->aseguradora_id;
            $maestro->poliza_no                   = $protocolo->poliza_no;
            $maestro->proveedor_medicamento       = $protocolo->proveedor_medicamento;
            $maestro->inmunoterapia               = $protocolo->inmunoterapia;
            $maestro->tipo_tratamiento            = $protocolo->tipo_tratamiento;
            $maestro->ciclo                       = $i;
            $maestro->fecha_ciclo                 = $cagenda->fecha_ciclo;
            $maestro->estado                      = 'A';
            $maestro->save();

            $agenda = Agenda::findOrFail($cagenda->agenda_id);
            $agenda->medico_id       = $protocolo->medico_id;
            $agenda->hospital_id     = $protocolo->lugar_tratamiento_id;
            $agenda->paciente_id     = $protocolo->paciente_id;
            $agenda->maestro_protocolo_id    = $maestro->id;
            $agenda->nombre_completo = $paciente->nombre_completo;
            $agenda->telefonos       = $paciente->celular.' '.$paciente->telefonos;
            $agenda->observaciones   = $diagnostico->descripcion.' de '.$cuerpo_parte->nombre.' Ciclo No. '.$i;
            $agenda->save();

            $totalRegistros  = 0;
            $totalRegistros  = count($data_metastasis);
            if ($totalRegistros > 0) {
                for ($j=0; $j < $totalRegistros ; $j++) {
                    $metastasis = new MetastasisProtocolo();
                    $metastasis->maestro_protocolo_id = $maestro->id;
                    $metastasis->cuerpo_parte_id  = $data_metastasis[$j]['cuerpo_parte_id'];
                    $metastasis->save();
                }
            }

            $totalRegistros  = 0;
            $totalRegistros  = count($data_productos);
            if ($totalRegistros > 0) {
                for ($k=0; $k < $totalRegistros ; $k++) {
                    $detalle = new DetalleProtocolo();
                    $detalle->maestro_protocolo_id = $maestro->id;
                    $detalle->producto_id      = $data_productos[$k]['producto_id'];
                    $detalle->cantidad         = $data_productos[$k]['cantidad'];
                    $detalle->unidad_medida_id = $data_productos[$k]['medida_id'];
                    $detalle->precio_unitario  = $data_productos[$k]['preciouni'];
                    $detalle->precio_total     = $data_productos[$k]['preciotot'];
                    $detalle->estado           = 'P';
                    $detalle->save();
                }
            }
        }


        return Response::json('Protocolo guardado con exito !!!');
    }

    public function edit($id){
        
        $protocolo     = MaestroProtocolo::where('id', $id)->first();
        $pacientes     = Paciente::where('empresa_id', Auth::user()->empresa_id)->get();
        $medicos       = Medico::where('empresa_id', Auth::user()->empresa_id)->where('estado','A')->get();
        $diagnosticos  = Diagnostico::all();
        $cuerpo_partes = DB::table('cuerpo_partes as cp')
                         ->leftjoin('metastasis_protocolos as mp', 'cp.id', 'mp.cuerpo_parte_id', 'mp.maestro_protocolo_id', $id)
                         ->select('cp.id', 'cp.nombre', DB::raw('CASE IFNULL(mp.id,0) WHEN 0 THEN "N" ELSE "S" END AS existe'))
                         ->groupBy('cp.id', 'cp.nombre', DB::raw('CASE IFNULL(mp.id,0) WHEN 0 THEN "N" ELSE "S" END'))
                         ->get();
        $hospitales    = Hospital::where('empresa_id', Auth::user()->empresa_id)->where('estado','A')->get();
        $aseguradoras  = Aseguradora::where('estado', 'A')->get();
        $productos     = DB::table('productos as p')
                         ->leftjoin('detalle_protocolos as dp', 'p.id', 'dp.producto_id', 'dp.maestro_protocolo_id', $id)
                         ->where('p.empresa_id', Auth::user()->empresa_id)
                         ->where('p.estado', 'A')
                         ->select('p.id', 'p.clasificacion', 'p.siglas', 'p.descripcion', 'p.medida_id', 'dp.cantidad', 'dp.unidad_medida_id', 'dp.precio_unitario', 'dp.precio_total')
                         ->groupBy('p.id', 'p.clasificacion', 'p.siglas', 'p.descripcion', 'p.medida_id', 'dp.cantidad', 'dp.unidad_medida_id', 'dp.precio_unitario', 'dp.precio_total')
                         ->orderBy('p.clasificacion', 'ASC', 'p.descripcion', 'ASC')
                         ->get();
        
        return view('protocolos.edit', compact('protocolo', 'pacientes', 'diagnosticos', 'cuerpo_partes', 'hospitales', 'aseguradoras', 'productos', 'medicos'));
    }

    public function show($id){
        $protocolos = DB::table('config_maestro_protocolos as cmp')
                      ->join('maestro_protocolos as mp', 'cmp.id', 'mp.config_maestro_protocolo_id')
                      ->join('pacientes as p', 'mp.paciente_id', 'p.id')
                      ->join('diagnosticos as d', 'mp.diagnostico_id', 'd.id')
                      ->join('cuerpo_partes as cp', 'mp.cuerpo_parte_id', 'cp.id')
                      ->join('hospitales as h', 'mp.lugar_tratamiento_id', 'h.id')
                      ->leftjoin('aseguradoras as a', 'mp.aseguradora_id', 'a.id')
                      ->where('cmp.id', $id)
                      ->select('mp.id','mp.ciclo', 'p.nombre_completo', 'mp.edad', 'd.descripcion as diagnostico_descripcion', 'cp.nombre as cuerpo_parte_nombre', 'h.nombre as hospital_nombre', 'mp.fecha_ciclo', 'a.nombre as aseguradora_nombre', 'mp.estado')
                      ->get();

        return view('protocolos.ciclos', compact('protocolos'));   
    }

    public function update(Request $request){
        dd($request);
    }

    public function trae_datos_seguro(){
        $protocolo_id = $_POST['protocolo_id'];
        $datos = DB::table('maestro_protocolos as mp')
                 ->where('mp.id', $protocolo_id)
                 ->select('mp.aseguradora_id', 'mp.poliza_no', 'mp.aseguradora_aut_no')
                 ->first();
        return Response::json($datos);
    }
}
