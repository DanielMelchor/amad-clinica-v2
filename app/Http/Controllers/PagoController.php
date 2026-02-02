<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use Auth;
use Redirect;
use Response;
use Carbon\carbon;
use App\Models\Banco;
use App\Models\Caja;
use App\Models\CajaResolucion;
use App\Models\FormaPago;
use App\Models\MotivoAnulacion;
use App\Models\Paciente;
use App\Models\PagoDetalle;
use App\Models\PagoDocumento;
use App\Models\PagoMaestro;
use App\Models\TipoDocumento;

class PagoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $listado = DB::table('pago_maestros as mp')
                   ->join('tipo_documentos as td', 'mp.tipo_documento_id', 'td.id')
                   ->leftjoin('pago_detalles as dp', 'mp.id', 'dp.pago_maestro_id')
                   ->where('mp.empresa_id', Auth::user()->empresa_id)
                   ->where('td.tipo_interno', 'RP')
                   ->select('mp.id','mp.tipo_documento_id', 'td.descripcion as tipodocumento_descripcion','mp.fecha_emision', 'mp.serie', 'mp.correlativo','mp.estado', DB::raw('(CASE WHEN mp.estado = "1" THEN "Vigente" ELSE "Anulado" END) AS estado_descripcion'), DB::raw('SUM(dp.monto) as monto'))
                   ->groupBy('mp.id','mp.tipo_documento_id', 'td.descripcion','mp.fecha_emision', 'mp.serie', 'mp.correlativo', 'mp.estado')
                   ->get();
        return view('pagos.index', compact('listado'));
    }

    public function create(){
        $hoy       = Carbon::now()->format('Y-m-d');
        $documento = TipoDocumento::where('tipo_interno', 'RP')->first();
        $pacientes = Paciente::all();
        $caja      = Caja::where('id', Auth::user()->caja_id)->first();
        $bancos    = Banco::where('tipo_referencia', 'B')->where('estado', 'A')->get();
        $tarjetas  = Banco::where('tipo_referencia', 'T')->where('estado', 'A')->get();
        $formas_pago = FormaPago::where('estado', 'A')->get();

        if (empty($caja)) {
            $message = array(
                'message' => 'Usuario no permitido para emitir Recibos !!!',
                'type'    => 'error'
            );

            return redirect()->back()->with($message);
            // return Redirect::back()->withErrors('Usuario no permitido para emitir Recibos');
        } else{
            $resolucion = CajaResolucion::where('caja_id', Auth::user()->caja_id)->where('tipo_documento_id', $documento->id)->where('estado', '1')->count();
            if ($resolucion == 0) {
                $message = array(
                    'message' => 'Caja no cuenta con una resolucion Activa que permita emitir Recibos !!!',
                    'type'    => 'error'
                );

                return redirect()->back()->with($message);
                // return Redirect::back()->withErrors('Caja no cuenta con una resolucion Activa que permita emitir Recibos');
            } else{
                return view('pagos.create', compact('hoy', 'documento', 'pacientes', 'caja', 'bancos', 'tarjetas', 'formas_pago', 'resolucion'));
            }
        }
    }

    public function recibo_store(Request $request){
        $validData = $request->validate([
            'tipo_documento_id' => 'required',
            'resolucion_id'     => 'required',
            'caja_id'           => 'required',
            'fecha_emision'     => 'required',
            'serie'             => 'required',
            'correlativo'       => 'required'
        ]);

        $existe = PagoMaestro::where('empresa_id', Auth::user()->empresa_id)
                    ->where('tipo_documento_id', $validData['tipo_documento_id'])
                    ->where('serie', $validData['serie'])
                    ->where('correlativo', $validData['correlativo'])
                    ->count();

        if ($existe > 0) {
            $message = array(
                'message' => 'Documento Ya existe',
                'type'    => 'error'
            );

            return redirect()->back()->with($message);
        }

        try{
            return DB::transaction(function () use ($request, $validData) {
                // ================================================================
                // crea encabezado de recibo
                // ================================================================
                $recibo = new PagoMaestro;
                $recibo->empresa_id       = Auth::user()->empresa_id;
                $recibo->caja_id           = $validData['caja_id'];
                $recibo->tipo_documento_id = $validData['tipo_documento_id'];
                $recibo->resolucion_id     = $validData['resolucion_id'];
                $recibo->fecha_emision     = $validData['fecha_emision'];
                $recibo->serie             = $validData['serie'];
                $recibo->correlativo       = $validData['correlativo'];
                $recibo->estado            = 1;
                $recibo->save();

                if ($request->has('mpago')) {
                    // =================================================================
                    // crea detalle de recibo
                    // =================================================================
                    foreach ($request['mpago'] as $key => $pago) {
                        $recibo_detalle = new PagoDetalle();
                        $recibo_detalle->pago_maestro_id = $recibo->id;
                        $recibo_detalle->forma_pago      = $pago['fpago_id'];
                        $recibo_detalle->banco_id        = $pago['casa_id'];
                        $recibo_detalle->cuenta_no       = $pago['cuenta_no'];
                        $recibo_detalle->documento_no    = $pago['documento_no'];
                        $recibo_detalle->autoriza_no     = $pago['autoriza_no'];
                        $recibo_detalle->monto           = $pago['monto'];
                        $recibo_detalle->estado          = 1;
                        $recibo_detalle->save();
                    }
                }

                if ($request->has('documentos')) {
                    // =================================================================
                    // Actualiza saldo a cada documento
                    // =================================================================
                    foreach ($request['documentos'] as $key => $documento) {
                        $documento_pago = new PagoDocumento();
                        $documento_pago->pago_maestro_id      = $recibo->id;
                        $documento_pago->documentoventa_id    = $documento['id'];
                        $documento_pago->saldo_pendiente      = $documento['saldo'];
                        $documento_pago->monto_aplicado       = $documento['monto'];
                        $documento_pago->estado               = 1;
                        $documento_pago->save();
                    }
                }

                /*=================================================================
                actualiza correlativo de recibo
                =================================================================*/

                $resolucion = CajaResolucion::findOrFail($recibo->resolucion_id);
                if ($resolucion->ultimo_correlativo == $recibo->correlativo) {
                    $resolucion->estado = 2;
                }
                $resolucion->ultimo_correlativo = $recibo->correlativo;
                $resolucion->save();

                $message = array(
                    'message' => 'Registro almacenado con exito !!!',
                    'type'    => 'success'
                );

                return redirect()
                    ->route('editar_recibo', ['recibo_id' => $recibo->id])
                    ->with($message);
            });
        }catch (\Exception $e) {
            // Si algo falla, Laravel hace rollback automático gracias a DB::transaction
            return redirect()->back()->with([
                'message' => 'Error al procesar el pago: ' . $e->getMessage(),
                'type'    => 'error'
            ]);
        }
    }

    public function edit($id){
        $hoy       = Carbon::now()->format('Y-m-d');
        $documento = TipoDocumento::where('tipo_interno', 'RP')->first();
        $pacientes = Paciente::all();
        $caja      = Caja::where('id', Auth::user()->caja_id)->first();
        $bancos    = Banco::where('tipo_referencia', 'B')->where('estado', 'A')->get();
        $tarjetas  = Banco::where('tipo_referencia', 'T')->where('estado', 'A')->get();
        $formas_pago = FormaPago::where('estado', 'A')->get();

        $ventas = DB::table('documentoventa_maestros as dvm')
                  ->join('documentoventa_detalles as dvd', 'dvm.id', 'dvd.documentoventa_maestro_id')
                  ->join('tipo_documentos as td', 'dvm.tipodocumento_id', 'td.id')
                  ->groupBy('dvm.id', 'td.descripcion', 'dvm.serie', 'dvm.correlativo', 'dvm.fecha_emision', 'dvm.nit', 'dvm.nombre')
                  ->select('dvm.id', 'td.descripcion', 'dvm.serie', 'dvm.correlativo', 'dvm.fecha_emision', 'dvm.nit', 'dvm.nombre', DB::raw('(SUM(dvd.precio_neto)) as total'));

        $encabezado = PagoMaestro::findOrFail($id);
        $detalle    = DB::table('pago_documentos as pd')
                      ->join('pago_maestros as pm', 'pd.pago_maestro_id', 'pm.id')
                      ->JoinSub($ventas, 'vta', function($join){
                            $join->on('pd.documentoventa_id', '=', 'vta.id');
                      })
                      ->where('pd.pago_maestro_id', $id)
                      ->where('pd.estado', 1)
                      ->select('vta.descripcion', 'vta.serie', 'vta.correlativo', 'vta.fecha_emision', 'vta.nit', 'vta.nombre', 'vta.total', 'pd.saldo_pendiente', 'pd.monto_aplicado')
                      ->get();

        $pagos = DB::table('pago_detalles as pd')
                 ->join('formas_pago as fp', 'pd.forma_pago', 'fp.id')
                 ->leftjoin('bancos as b', 'pd.banco_id', 'b.id')
                 ->where('pd.pago_maestro_id', $id)
                 ->where('pd.estado', 1)
                 ->select('fp.descripcion', 'b.nombre', 'pd.cuenta_no', 'pd.documento_no', 'autoriza_no', 'monto')
                 ->get();


        return view('pagos.edit', compact('hoy', 'documento', 'pacientes', 'caja', 'bancos', 'tarjetas', 'formas_pago', 'encabezado', 'detalle', 'pagos'));
    }

    public function trae_recibos_con_saldo(){
        $paciente_id = $_POST['paciente_id'];

        $recibos = DB::table('pago_maestros as pm')
                   ->join('pago_detalles as pd', 'pm.id', 'pd.pago_maestro_id')
                   ->join('pago_documentos as pds', 'pm.id', 'pds.pago_maestro_id')
                   ->join('documentoventa_maestros as dm1', 'pds.documentoventa_id', 'dm1.id')
                   ->leftjoin(DB::raw('(SELECT pm.id as pago_maestro_id, SUM(IFNULL(pd1.monto,0))monto
                                        FROM pago_maestros as pm
                                        JOIN pago_detalles as pd1 on pm.id = pd1.pago_maestro_id
                                        JOIN pago_documentos as pd on pm.id = pd.pago_maestro_id
                                        JOIN documentoventa_maestros dm on pd.documentoventa_id = dm.id 
                                        WHERE pm.estado = 1 
                                          AND dm.paciente_id = '.$paciente_id.'
                                        GROUP BY pm.id) as pago_documento'
                                      ),
                                    function($j){
                                            $j->on('pm.id', '=', 'pago_documento.pago_maestro_id');
                                    }
                                )
                   ->where('pm.estado','A')
                   ->whereIn('dm1.paciente_id', [$paciente_id, ''])
                   ->select('pm.id', 'pm.serie', 'pm.correlativo', 'pago_documento.monto')
                   ->groupBy('pm.id', 'pm.serie', 'pm.correlativo', 'pago_documento.monto')
                   ->get();

        /*$recibos     = DB::table('pago_maestros as mp')
                       ->join('pago_Detalles as dp', 'mp.id', 'dp.maestro_pago_id')
                       ->leftjoin('pago_documentos as pd', 'mp.id', 'pd.maestro_pago_id')
                       ->where('mp.empresa_id', Auth::user()->empresa_id)
                       ->where('mp.estado', 'A')
                       ->where('dp.estado', 'A')
                       ->where('mp.paciente_id', $paciente_id)
                       ->groupBy('mp.id', 'mp.serie', 'mp.correlativo')
                       ->select('mp.id', 'mp.serie', 'mp.correlativo')
                       ->get();*/

        return Response::json($recibos);
    }

    public function trae_detalle_pago_x_recibo(){
        $recibo_id = $_POST['recibo_id'];

        $detalle = DB::table('pago_detalles as dp')
                   ->join('formas_pago as fp', 'dp.forma_pago', 'fp.id')
                   ->leftjoin('bancos as b', 'dp.banco_id', 'b.id')
                   ->where('dp.pago_maestro_id', $recibo_id)
                   ->where('dp.estado', 'A')
                   ->select('dp.pago_maestro_id','dp.forma_pago', 'fp.descripcion', 'dp.banco_id', 'b.nombre', 'dp.cuenta_no', 'dp.documento_no', 'dp.autoriza_no', 'dp.monto')
                   ->get();

       return Response::json($detalle);
    }

    public function recibo_anular(Request $request, $id){
        $recibo = PagoMaestro::findOrFail($id);
        $recibo->motivo_anulacion_id     = $request->motivo_id;
        $recibo->anulacion_observaciones = $request->observacion_anulacion;
        $recibo->anulacion_usuario_id    = Auth::user()->id;
        $recibo->anulacion_fecha         = Carbon::now();
        $recibo->estado                  = 'I';
        $recibo->save();

        $pago_recibo = PagoDetalle::where('pago_maestro_id', $id)->get();

        foreach ($pago_recibo as $pr) {
            $detalle = PagoDetalle::findOrFail($pr->id);
            $detalle->estado = 'I';
            $detalle->save();
        }

        $pago_documento = PagoDocumento::where('pago_maestro_id', $id)->get();

        foreach ($pago_documento as $pd) {
            $pago = PagoDocumento::findOrFail($pd->id);
            $pago->estado = 'I';
            $pago->save();
        }

        return Redirect::route('editar_recibo',[$id])->with('message','Recibo anulado con exito !!!');
    }

    public function trae_detalle_recibo(){
        $recibo_id = $_POST['recibo_id'];

        $detalle = DB::table('pago_maestros as pm')
                   ->join('pago_documentos as pd', 'pm.id', 'pd.pago_maestro_id')
                   ->join('documentoventa_maestros as dm', 'pd.documentoventa_id', 'dm.id')
                   ->join('tipo_documentos as td', 'dm.tipodocumento_id', 'td.id')
                   ->where('pm.id', $recibo_id)
                   ->orderBy('pd.id')
                   ->select('pm.id', 'pm.tipo_documento_id', 'td.descripcion', DB::raw('DATE_FORMAT(dm.fecha_emision, "%d/%m/%Y") AS fecha_emision'), 'dm.serie', 'dm.correlativo', 'dm.nit', 'dm.nombre', 'pd.saldo_pendiente', 'pd.monto_aplicado')
                   ->get();
        //print_r($detalle);
        
        /*$detalle = DB::table('pago_documentos as pd')
                   ->join('documento_maestros as md', 'pd.maestro_documento_id', 'md.id')
                   ->join('tipo_documentos as td', 'md.tipodocumento_id', 'td.id')
                   ->where('pd.maestro_pago_id', $recibo_id)
                   ->orderBy('pd.id')
                   ->select('md.id', 'md.tipodocumento_id', 'td.descripcion', DB::raw('DATE_FORMAT(md.fecha_emision, "%d/%m/%Y") as fecha_emision'), 'md.serie', 'md.correlativo', 'md.nit', 'md.nombre', 'pd.saldo_documento', 'pd.total_aplicado')
                   ->get();*/
        return Response::json($detalle);
    }

    public function trae_saldo_recibo(){
        $recibo_id = $_POST['recibo_id'];

        $recibo = DB::table('pago_maestros as pm')
                  ->join('pago_detalles as pd', 'pm.id', 'pd.pago_maestro_id')
                  ->leftjoin(DB::raw('(SELECT pd.pago_maestro_id, SUM(IFNULL(pd.monto_aplicado,0))monto_aplicado 
                                       FROM pago_documentos as pd 
                                       WHERE pd.estado = "A"
                                       GROUP BY pago_maestro_id) as pago_documento'),
                        function($j){
                            $j->on('pm.id', '=', 'pago_documento.pago_maestro_id');
                        })
                  ->where('pm.empresa_id', Auth::user()->empresa_id)
                  ->where('pm.estado','A')
                  ->where('pm.id', $recibo_id)
                  ->select('pm.id', DB::raw('SUM(IFNULL(pd.monto,0)) as total_pago'), DB::raw('SUM(IFNULL(pd.monto,0)) - IFNULL(pago_documento.monto_aplicado,0) saldo_pendiente'))
                  ->groupBy('pm.id', 'pago_documento.monto_aplicado')
                  ->first();
        return Response::json($recibo);
    }

    public function trae_pago_recibo(){
        $recibo_id = $_POST['recibo_id'];

        $pago = DB::table('pago_detalles as dp')
                ->join('formas_pago as fp', 'dp.forma_pago', 'fp.id')
                ->leftjoin('bancos as b', 'dp.banco_id', 'b.id')
                ->where('dp.pago_maestro_id', $recibo_id)
                ->select('dp.id', 'dp.forma_pago', 'fp.descripcion as forma_pago_descripcion', 'dp.banco_id', DB::raw('CASE WHEN IFNULL(b.nombre,0) = 0 then "" ELSE b.nombre END as emisor_nombre'), 'dp.cuenta_no', 'dp.documento_no', 'dp.autoriza_no', 'dp.monto')
                ->orderBy('dp.id')
                ->get();
        return Response::json($pago);

    }

    public function trae_recibo_x_cheque(){
        $banco_id     = $_POST['banco_id'];
        $cuenta_no    = $_POST['cuenta_no'];
        $documento_no = $_POST['documento_no'];

        $recibo = DB::table('pago_detalles as pd')
                  ->join('pago_maestros as pm', 'pd.pago_maestro_id', 'pm.id')
                  ->where('pd.banco_id', $banco_id)
                  ->where(DB::raw('TRIM(pd.cuenta_no)'), $cuenta_no)
                  ->where(DB::raw('TRIM(pd.documento_no)'), $documento_no)
                  ->where('pd.estado', 'A')
                  ->select('pm.id')
                  ->first();

        if (empty($recibo)) {
            $recibo_id = 0;
        }else{
            $recibo_id = $recibo->id;
        }

        return $recibo_id;
    }

    public function trae_generales_x_recibo_id(){
        $recibo_id = $_POST['recibo_id'];
        $registro = DB::table('pago_maestros as pm')
                    ->join('users as u', 'pm.created_by', 'u.id')
                    ->leftjoin('caja_cortes as cc', 'pm.caja_corte_id', 'cc.id')
                    ->where('pm.empresa_id', Auth::user()->empresa_id)
                    ->where('pm.id', $recibo_id)
                    ->select('pm.id', 'pm.serie', 'pm.correlativo', 'pm.fecha_emision', 'cc.corte', 'u.name')
                    ->first();

        return Response::json($registro);
    }

    public function trae_documentos_afectos(){
        $recibo_id = $_POST['recibo_id'];
        $registros = DB::table('pago_documentos as pd')
                     ->join('documentoventa_maestros as dm', 'pd.documentoventa_id', 'dm.id')
                     ->join('documentoventa_detalles as dd', 'dm.id', 'dd.documentoventa_maestro_id')
                     ->join('tipo_documentos as td', 'dm.tipodocumento_id', 'td.id')
                     ->leftjoin(DB::raw('(SELECT dms.id, a.admision
                                          FROM detalle_movimientos as dms
                                          JOIN admisiones as a on dms.admision_id = a.id) as admisiones
                                          '),
                                    function($j){
                                        $j->on('dd.admision_cargo_id', '=', 'admisiones.id');
                                    }
                                )
                     ->where('pd.pago_maestro_id', $recibo_id)
                     ->groupBy('td.descripcion', 'dm.serie', 'dm.correlativo', 'dm.fecha_emision', 'dm.nit', 'dm.nombre', 'dm.direccion', 'dm.email', 'admisiones.admision', 'dm.paciente_id')
                     ->select('td.descripcion', 'dm.serie', 'dm.correlativo', 'dm.fecha_emision', 'dm.nit', 'dm.nombre','dm.direccion', 'dm.email', 'admisiones.admision', 'dm.paciente_id', DB::raw('SUM(dd.precio_neto) as total'))
                     ->get();
        
        return Response::json($registros);

    }
}