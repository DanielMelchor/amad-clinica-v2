<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Session;
use Auth;
use DB;
use App\Models\Caja;
use App\Models\CajaResolucion;
use App\Models\TipoDocumento;
use App\Models\DocumentoMaestro;

class CajaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $pCajas = Caja::where('empresa_id', Auth::user()->empresa_id)->get();
        return view('cajas.index', compact('pCajas'));
    }

    public function create(){
        $tipo_documentos = TipoDocumento::all();
        return view('cajas.create', compact('tipo_documentos'));
    }

    public function store(Request $request){
        $validData = $request->validate([
            'caja_nombre' => 'required'
        ]);

        $caja = new Caja();
        $caja->empresa_id       = Auth::user()->empresa_id;
        $caja->nombre_maquina   = $validData['caja_nombre'];
        if (isset($request['editar_documento'])) {
            $caja->editar_documento = 1;
        }else{
            $caja->editar_documento = 0;
        }
        if (isset($request['estado'])) {
            $caja->estado = 1;
        }else{
            $caja->estado = 0;
        }
        $caja->save();

        if (isset($request['resoluciones'])) {
            foreach ($request['resoluciones'] as $key => $registro) {
                $resolucion = new CajaResolucion();
                $resolucion->caja_id             = $caja->id;
                $resolucion->tipo_documento_id   = $registro['tipo_documento_id'];
                $resolucion->serie               = $registro['serie'];
                $resolucion->correlativo_inicial = $registro['inicial'];
                $resolucion->correlativo_final   = $registro['final'];
                $resolucion->ultimo_correlativo  = $registro['ultimo'];
                if (isset($registro['estado'])) {
                    $resolucion->estado          = 1;
                }else{
                    $resolucion->estado          = 0;
                }
                $resolucion->save();
            }
        }

        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);

    }

    public function edit($id){
        $pCaja = Caja::findOrFail($id);
        $pResoluciones = CajaResolucion::where('caja_id', $id)->get();
        $tipo_documentos = TipoDocumento::all();
        return view('cajas.edit', compact('pCaja', 'pResoluciones', 'tipo_documentos'));
    }

    public function update(Request $request){
        $validData = $request->validate([
            'caja_id'     => 'required',
            'caja_nombre' => 'required'
        ]);

        $caja = Caja::findOrFail($validData['caja_id']);
        $caja->nombre_maquina   = $validData['caja_nombre'];
        if (isset($request['editar_documento'])) {
            $caja->editar_documento = 1;
        }else{
            $caja->editar_documento = 0;
        }
        if (isset($request['estado'])) {
            $caja->estado = 1;
        }else{
            $caja->estado = 0;
        }
        $caja->save();

        if (isset($request['resoluciones'])) {
            $resoluciones = array_values($request['resoluciones']);
            $totalRegistrosResoluciones = count($resoluciones);
            $registroActual = CajaResolucion::where('caja_id', $caja->id)
                              ->where('estado', 1)
                              ->get();

            foreach ($registroActual as $key => $registro) {
                $resolucionEncontrada = false;
                foreach ($resoluciones as $resolucion) {
                    if ($registro->tipo_documento_id == $validData['caja_id'] && $registro->tipo_documento_id == $resolucion['tipo_documento_id']) {
                        $resolucionEncontrada = true;
                    }
                }
                if (!$resolucionEncontrada) {
                    $eliminar = CajaResolucion::where('caja_id', $caja->id)
                                ->where('tipo_documento_id', $registro['tipo_documento_id'])
                                ->first();
                    $eliminar->estado = 0;
                    $eliminar->save();
                }
            }

            for ($i=0; $i < $totalRegistrosResoluciones ; $i++) {
                $existe_registro = CajaResolucion::where('caja_id', $caja->id)
                                   ->where('tipo_documento_id', $resoluciones[$i]['tipo_documento_id'])
                                   ->count();
                if ($existe_registro == 0) {
                    $resolucion = new CajaResolucion();
                    $resolucion->caja_id             = $caja->id;
                    $resolucion->tipo_documento_id   = $resoluciones[$i]['tipo_documento_id'];
                    $resolucion->serie               = $resoluciones[$i]['serie'];
                    $resolucion->correlativo_inicial = $resoluciones[$i]['inicial'];
                    $resolucion->correlativo_final   = $resoluciones[$i]['final'];
                    $resolucion->ultimo_correlativo  = $resoluciones[$i]['ultimo'];
                    if (isset($resoluciones[$i]['estado'])) {
                        $resolucion->estado          = 1;
                    }else{
                        $resolucion->estado          = 0;
                    }
                    $resolucion->save();
                    
                }else{
                    $resolucion = CajaResolucion::where('caja_id', $caja->id)
                                  ->where('tipo_documento_id', $resoluciones[$i]['tipo_documento_id'])
                                  ->first();
                    $resolucion->serie               = $resoluciones[$i]['serie'];
                    $resolucion->correlativo_inicial = $resoluciones[$i]['inicial'];
                    $resolucion->correlativo_final   = $resoluciones[$i]['final'];
                    $resolucion->ultimo_correlativo  = $resoluciones[$i]['ultimo'];
                    if (isset($resoluciones[$i]['estado'])) {
                        $resolucion->estado          = 1;
                    }else{
                        $resolucion->estado          = 0;
                    }
                    $resolucion->save();
                }
            }
        }

        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }



    public function caja_resoluciones(){
        $caja_id = $_POST['caja_id'];
        $resoluciones = DB::table('Caja_resoluciones as r')
                        ->join('tipo_documentos as td', 'r.tipo_documento_id', 'td.id')
                        ->where('r.caja_id', $caja_id)
                        ->where('r.estado', 1)
                        ->select('r.id','r.tipo_documento_id', 'td.descripcion as tipo_documento_descripcion', 'r.serie', 'r.correlativo_inicial', 'r.correlativo_final', 'r.ultimo_correlativo', 'r.estado')
                        ->groupBy('r.id','r.tipo_documento_id', 'td.descripcion', 'r.serie', 'r.correlativo_inicial', 'r.correlativo_final', 'r.ultimo_correlativo', 'r.estado')
                        ->orderBy('r.id', 'asc')
                        ->get();

        return response::json($resoluciones);
    }

    public function resolucion_factura_x_caja(){
        $caja_id = $_POST['caja_id'];
        $tipo_documento_id = $_POST['tipo_documento_id'];
        $resolucion = CajaResolucion::where('caja_id', $caja_id)->where('tipo_documento_id', $tipo_documento_id)->where('estado', 1)->first();

        if (isset($resolucion)) {
            $existe = true;

            $empresa_id  = Auth::user()->empresa_id;
            $serie       = $resolucion->serie;
            $correlativo = $resolucion->ultimo_correlativo;

            while ($existe == true) {
                $correlativo++;

                $existe = DocumentoMaestro::where('empresa_id', $empresa_id)
                                          ->where('tipodocumento_id', $tipo_documento_id)
                                          ->where('serie', $serie)
                                          ->where('correlativo', $correlativo)
                                          ->exists();
            }

            $respuesta = array('resolucion_existe' => 'S', 'resolucion_id' => $resolucion->id, 'serie' => $resolucion->serie, 'correlativo' => $correlativo);
        }else{
            $respuesta = array('resolucion_existe' => 'N');
        }

        return response::json($respuesta);
    }

    public function resolucion_x_serie(){
        $caja_id = $_POST['caja_id'];
        $tipo_documento_id = $_POST['tipo_documento_id'];
        $serie   = strtoupper($_POST['serie']);

        $resolucion = CajaResolucion::where('caja_id', $caja_id)->where('tipo_documento_id', $tipo_documento_id)->where('serie', $serie)->where('estado', 1)->first();
        dd($resolucion);

        if (isset($resolucion)) {
            $correlativo = $resolucion->ultimo_correlativo + 1;
            $resolucion_id = $resolucion->id;
        }else{
            $correlativo = 0;
            $resolucion_id = 0;
        }

        
        $respuesta = array('resolucion_id' => $resolucion_id, 'correlativo' => $correlativo);

        return response::json($respuesta);
    }

    public function resolucion_recibo_x_caja(){
        $caja_id = $_POST['caja_id'];
        $tipo_documento = TipoDocumento::where('tipo_interno', 'RP')->first();
        $resolucion = CajaResolucion::where('caja_id', $caja_id)
                      ->where('tipo_documento_id', $tipo_documento->id)
                      ->where('estado', 1)
                      ->first();

        $correlativo = $resolucion->ultimo_correlativo + 1;

        $respuesta = array('resolucion_id' => $resolucion->id, 'serie' => $resolucion->serie, 'correlativo' => $correlativo);

        return response::json($respuesta);   
    }

    public function cajas_x_empresa(){
        $empresa = $_REQUEST['empresa_id'];

        $listado = Caja::where('empresa_id', $empresa)
                   ->where('estado', 1)
                   ->select('id', 'nombre_maquina')
                   ->get();

        return Response::json($listado);
    }
}
