<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Services\ProductoService;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use File;
use Redirect;
use Session;
use PDF;
use Carbon\Carbon;
use App\Models\Admision;
use App\Models\AdmisionAtencion;
use App\Models\AdmisionBitacora;
use App\Models\AdmisionAtencionImagen;
use App\Models\AdmisionCargo;
use App\Models\AdmisionConsulta;
use App\Models\AdmisionConsultaMedicamento;
use App\Models\AdmisionDocumento;
use App\Models\AdmisionObservacion;
use App\Models\AdmisionVital;
use App\Models\Agenda;
use App\Models\ObservacionAdmision;
use App\Models\AdmisionProcedimiento;
use App\Models\Aseguradora;
use App\Models\Correlativo;
use App\Models\Empresa;
use App\Models\Hospital;
use App\Models\MaestroMovimiento;
use App\Models\Medicamento;
use App\Models\Medicamento_Dosis;
use App\Models\DetalleMovimiento;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Producto;
use App\Models\ProductoDosis;
use App\Models\ProductoMedida;
use App\Models\Receta_Medico;
use App\Models\User;




class AdmisionController extends Controller
{
    public function __construct(private ProductoService $productoService)
    {
        $this->middleware('auth');
    }


    public function index(Request $request){
        setlocale(LC_MONETARY, 'es_GT');
        $hoy = Carbon::now()->format('Y-m-d');
        $medicos       = Medico::where('empresa_id', Auth::user()->empresa_id)->where('estado','=',1)->get();
        $hospitales    = Hospital::where('empresa_id', Auth::user()->empresa_id)->where('estado','=',1)->get();
        $aseguradoras  = Aseguradora::where('estado','=',1)->get();
        $pacientes     = Paciente::where('empresa_id', Auth::user()->empresa_id)
                         ->where('estado', 1)
                         ->orderBy('nombre_completo')
                         ->get();
        $admisiones = Admision::with([
                                    'paciente:id,expediente_no,nombre_completo', 
                                    'medico:id,nombre_completo',
                                    'hospital:id,nombre'
                                ])
                                ->where('empresa_id', auth()->user()->empresa_id)
                                ->withSum('detalles as precio_total', 'precio_total')
                                ->orderBy('fecha', 'desc')
                                ->get();

        // $tipo_admisiones = DB::table('empresa_tipo_atenciones as eta')
        //                    ->join('tipo_atenciones as ta', 'eta.tipo_atencion_id', 'ta.id')
        //                    ->where('eta.empresa_id', Auth::user()->empresa_id)
        //                    ->where('eta.estado', 1)
        //                    ->where('ta.estado', 1)
        //                    ->select('ta.id', 'ta.nombre')
        //                    ->get();
        $listaAtenciones = DB::table('empresa_tipo_atenciones as eta')
                           ->join('tipo_atenciones as ta', 'eta.tipo_atencion_id', 'ta.id')
                           ->where('eta.empresa_id', Auth::user()->empresa_id)
                           ->where('eta.estado', 1)
                           ->select('ta.id', 'ta.nombre')
                           ->orderBy('ta.id', 'ASC')
                           ->get();

        return view('admisiones.index', compact('medicos','hospitales', 'aseguradoras','pacientes', 'admisiones', 'hoy', 'listaAtenciones'));
    }

    public function nueva_admision($id){
        $hoy = Carbon::now()->format('Y-m-d');
        $paciente_id = Crypt::decryptString($id);

        $pPaciente  = Paciente::where('id', $paciente_id)
                      ->select('id', DB::raw('CONCAT(nombres, " ", apellidos) as nombre_completo'), 'expediente_no', 
                               'fecha_nacimiento', 'antmedico_descripcion', 'antquirurgico_descripcion', 'antalergia_descripcion', 'antgineco_descripcion', 'antfamiliar_descripcion', 'antmedicamento_descripcion', 'tabaco_cnt', 'tabaco_tiempo', 'alcohol_cnt', 'alcohol_tiempo',
                               DB::raw('CASE WHEN genero = "M" THEN "Masculino" ELSE "Femenino" END as genero'),
                               DB::raw('TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad'),)
                      ->first();

        $totalAdmisiones = \DB::table('admisiones')
                           ->where('empresa_id', Auth::user()->empresa_id)
                           ->where('paciente_id', $paciente_id)
                           ->count();

        $listado = DB::table('admisiones as a')
                   ->where('a.empresa_id', Auth::user()->empresa_id)
                   ->where('a.paciente_id', $paciente_id)
                   ->select('a.id', 'a.admision_no', 'a.encabezado_revisado', 'a.estado', DB::raw('DATE_FORMAT(a.fecha, "%d/%m/%Y") as fecha'), 'a.inicio_atencion_medica', 'a.atencion_medica')
                   ->orderBy('a.admision_no', 'DESC')
                   ->get();
                   // ->paginate(15);

        $pListaC = DB::table('admisiones as a')
                   ->leftjoin('admision_atenciones as aa', 'a.id', 'aa.admision_id')
                   ->where('a.empresa_id', Auth::user()->empresa_id)
                   ->where('a.paciente_id', $paciente_id)
                   ->where('aa.tipo_atencion_id', 1)
                   ->select('a.admision_no', 'aa.id as detalle_id', 'a.id', 'a.created_at as fecha')
                   ->orderBy('a.admision_no', 'DESC')
                   ->paginate(10);

        $pListaP = DB::table('admisiones as a')
                   ->leftjoin('admision_atenciones as aa', 'a.id', 'aa.admision_id')
                   ->where('a.empresa_id', Auth::user()->empresa_id)
                   ->where('a.paciente_id', $paciente_id)
                   ->where('aa.tipo_atencion_id', 3)
                   ->select('a.admision_no', 'aa.id as detalle_id', 'a.id', 'a.created_at as fecha', 'aa.pprocedimiento_id', 'aa.ptolerancia', 'aa.ppremedicacion', 'aa.ppatologo', 'aa.panestesiologo', 'aa.indicacion', 'aa.hallazgos', 'aa.diagnostico', 'aa.recomendaciones')
                   ->orderBy('a.admision_no', 'DESC')
                   ->paginate(10);

        $pListaH = DB::table('admisiones as a')
                   ->leftjoin('admision_atenciones as aa', 'a.id', 'aa.admision_id')
                   ->where('a.empresa_id', Auth::user()->empresa_id)
                   ->where('a.paciente_id', $paciente_id)
                   ->where('aa.tipo_atencion_id', 2)
                   ->select('a.admision_no', 'aa.id as detalle_id', 'a.id', 'a.created_at as fecha')
                   ->orderBy('a.admision_no', 'DESC')
                   ->paginate(10);
        
        //$pMedicamentos = Producto::where('estado', 1)->where('clasificacion', 'MED')->get();
        $pMedicamentos = DB::table('productos as p')
                         ->join('invclasificaciones as c', 'p.clasificacion', 'c.id')
                         ->where('p.estado', 1)
                         ->where('c.definir_medidas', 1)
                         ->select('p.id', 'p.descripcion')
                         ->get();
        
        //$pProcedimientos = Producto::where('estado', 1)->where('clasificacion', 'PROC')->get();
        $pProcedimientos = $this->productoService->trae_producto_procedimiento();
        $premedicacion = $this->productoService->trae_premedicacion();
        // $pProcedimientos = DB::table('productos as p')
        //                    ->join('invclasificaciones as c', 'p.clasificacion', 'c.id')
        //                    ->where('p.estado', 1)
        //                    ->where('c.nombre', 'Procedimiento')
        //                    ->select('p.id', 'p.descripcion')
        //                    ->get();

        return view('admisiones.nueva_admision', compact('pPaciente', 'pListaC', 'pListaP', 'pListaH', 'pMedicamentos', 'pProcedimientos', 'premedicacion', 'listado', 'hoy'));
    }

    public function getAdmisiones($paciente_id){
        $listado = DB::table('admisiones as a')
                   ->join('admision_atenciones as aa', 'a.id', 'aa.admision_id')
                   ->join('tipo_atenciones as ta', 'aa.tipo_atencion_id', 'ta.id')
                   ->where('a.empresa_id', Auth::user()->empresa_id)
                   ->where('a.paciente_id', $paciente_id)
                   ->select('a.id', 'a.admision_no', 'aa.id as detalle_id', DB::raw('DATE_FORMAT(a.created_at, "%d/%m/%Y") as fecha'), /*'ta.abreviatura as tipo_admision',*/ 'ta.abreviatura as tipo')
                   ->orderBy('a.admision_no', 'DESC')
                   ->paginate(15);

        return $listado;
    }

    public function store(Request $request)
    {
        $mensajes = [
            'agenda_id.required'       => 'Debe Seleccionar un horario',
            'adm_fecha.required'       => 'Falta Fecha de Admisión',
            'adm_paciente_id.required' => 'Paciente pendiente de definir',
            'adm_medico_id.required'   => 'Seleccione un medico permitido',
            'adm_hospital_id.required' => 'Seleccione Centro de Atención',
        ];

        $validData = $request->validate([
            'agenda_id'         => 'required',
            'adm_fecha'         => 'required|date_format:Y-m-d',
            'adm_paciente_id'   => 'required|exists:pacientes,id',
            'adm_medico_id'     => 'required|exists:medicos,id',
            'adm_hospital_id'   => 'required|exists:hospitales,id'
        ], $mensajes);

        DB::beginTransaction();
        try {
            if ($request['admision_tercero']) {
                $admision_tercero  = trim($request['admision_tercero']);
            }else{
                $admision_tercero = NULL;
            }
            if ($request['aseguradora_id']) {
                $aseguradora_id    = $request['aseguradora_id'];
            }else{
                $aseguradora_id    = NULL;
            }
            
            $poliza_no         = $request['poliza_no'];
            $autorizacion_no   = $request['autorizacion_no'];
            if (isset($request['copago'])) {
                $copago = $request['copago'];
            }else{
                $copago = 0;
            }

            if (isset($request['coaseguro'])) {
                $coaseguro = $request['coaseguro'];
            }else{
                $coaseguro = 0;
            }

            $correlativoModel = Correlativo::where('empresa_id', Auth::user()->empresa_id)
                                ->where('tipo', 'A')
                                ->lockForUpdate() 
                                ->first();

            $no_admision = ($correlativoModel->correlativo ?? 0) + 1;
            $paciente    = Paciente::where('id', $validData['adm_paciente_id'])->first();

            $admision = new Admision();
            $admision->empresa_id       = Auth::user()->empresa_id;
            $admision->agenda_id        = $validData['agenda_id'];
            $admision->fecha            = $validData['adm_fecha'];
            $admision->admision_no      = $no_admision;
            $admision->paciente_id      = $validData['adm_paciente_id'];
            $admision->edad             = Carbon::parse($paciente->fecha_nacimiento)->age;
            $admision->medico_id        = $validData['adm_medico_id'];
            $admision->hospital_id      = $validData['adm_hospital_id'];
            
            // Uso de null coalescing ?? para valores por defecto
            $admision->coaseguro        = $request->input('coaseguro', 0);
            $admision->copago           = $request->input('copago', 0);
            $admision->estado           = 0;
            $admision->admision_tercero = $request->filled('admision_tercero') ? trim($request->admision_tercero) : null;
            $admision->aseguradora_id   = $request->input('aseguradora_id');
            $admision->poliza_no        = $request->input('poliza_no');
            $admision->aseguradora_aut_no     = $request->input('autorizacion_no');
            $admision->pagado_por_aseguradora = 'N';
            $admision->save();

            $correlativoModel->update(['correlativo' => $no_admision]);

            $admision->bitacoras()->create([
                'proceso' => 'APERTURA',
                'observaciones' => 'Creación de admisión'
            ]);

            DB::commit();


            if ($request->ajax()) {
                return response()->json([
                    'message' => '¡Admisión registrada con éxito!',
                    'type'    => 'success',
                    'admision_no' => $no_admision,
                    'admision_id' => $admision->id
                ]);
            }

            return redirect()->back()->with(['message' => '¡Admisión '. $no_admision .' registrada con éxito!', 'type' => 'success']);

        }catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }

        

        $respuesta = ['respuesta' => 'Admisión No. '.$admision->admision, 'admision_id' => $admision->id];

        return response::json($respuesta);

        /*Session::flash('success', 'Admisión Guardada con exito !!!' );
        return redirect::route('admisiones');*/
    }

    public function edit($id)
    {
        // $admisionId     = Crypt::decrypt($id);
        $admisionId     = $id;
        $pMedicos       = Medico::all();
        $pHospitales    = Hospital::where('empresa_id', Auth::user()->empresa_id)->get();
        $pAseguradoras  = Aseguradora::all();
        $pPacientes     = Paciente::all();
        $pAdmision      = Admision::findOrFail($admisionId);

        $facturas       = DB::table('documentoventa_maestros as md')
                          ->join('tipo_documentos as td', 'md.tipodocumento_id', 'td.id')
                          ->join('documentoventa_detalles as dd', 'md.id', 'dd.documentoventa_maestro_id')
                          ->join('detalle_movimientos as dm', 'dm.id', 'dd.detalle_movimiento_id')
                          // ->join(DB::raw('(select mm.maestro_documento_id, dm.admision_id
                          //                  from maestro_movimientos as mm
                          //                  join detalle_movimientos as dm on(mm.id = dm.maestro_movimiento_id)
                          //                  group by mm.maestro_documento_id, dm.admision_id
                          //                 )as admision'),
                          // function($j){
                          //   $j->on('maestro_documento_id', '=', 'md.id');
                          // })
                          ->where('dm.admision_id', $admisionId)
                          ->select('td.descripcion as tipodocumento_descripcion', 'md.id as factura_id', 'md.serie', 'md.correlativo', 'md.fecha_emision', 'md.nombre as factura_nombre', DB::raw('(CASE WHEN md.estado = 1 THEN "Vigente" ELSE "Anulada" END) AS estado_descripcion'), DB::raw('SUM(dd.precio_neto) as total'))
                          ->groupBy('td.descripcion', 'md.id', 'md.serie', 'md.correlativo', 'md.fecha_emision', 'md.nombre', 'md.estado')
                          ->get();

        $tipo_admisiones = DB::table('empresa_tipo_atenciones as eta')
                           ->join('tipo_atenciones as ta', 'eta.tipo_atencion_id', 'ta.id')
                           ->where('eta.empresa_id', Auth::user()->empresa_id)
                           ->where('eta.estado', 'A')
                           ->where('ta.estado', 'A')
                           ->select('ta.id', 'ta.nombre')
                           ->get();

        $pObservaciones = ObservacionAdmision::where('proceso','REAPERTURA')->where('estado','A')->get();
        $pProductos     = Producto::where('empresa_id', Auth::user()->empresa_id)
                          ->where('estado', 1)->get();

        return view('admisiones.edit', compact('pMedicos', 'pHospitales', 'pAseguradoras', 'pPacientes', 'pAdmision','pObservaciones', 'pProductos', 'facturas', 'tipo_admisiones', 'id'));
    }

    public function getDoctosxAdmin(){
        $id = $_POST['id'];
        $admisionId     = $id; /*crypt::decrypt($id);*/
        $documentos     = DB::table('admision_documentos as ad')
                          ->join('admisiones as a', 'ad.admision_id', 'a.id')
                          ->join('users as u', 'ad.created_by', 'u.id')
                          ->where('ad.admision_id', $admisionId)
                          ->select('ad.paciente_id', 'a.admision_no', 'ad.admision_id', 'ad.titulo', 'ad.ruta', DB::raw('DATE_FORMAT(ad.created_at, "%d/%m/%Y %H:%i") as created_at'), 'u.name')
                          ->orderBy('ad.created_at', 'desc')
                          ->get();

        return response::json($documentos);
    }

    public function getBitacoraAdmin(){
        $id = $_POST['id'];
        $admisionId     = crypt::decrypt($id);
        $registros      = DB::table('admision_bitacoras as ba')
                          ->join('users as u', 'ba.created_by', 'u.id')
                          ->where('ba.admision_id', $admisionId)
                          ->select('u.name', DB::raw('DATE_FORMAT(ba.created_at, "%d/%m/%Y %H:%i") as created_at'), 'ba.observaciones')
                          ->orderBy('ba.created_at', 'desc')
                          ->get();
                          // ->paginate(10);

        return response::json($registros);
    }

    public function getCargosAdmin(){
        $id = $_POST['id'];
        $admisionId  = crypt::decrypt($id);
        $registros   = DB::table('detalle_movimientos as dm')
                        ->where('dm.admision_id', $admisionId)
                        ->join('unidad_medidas as um', 'dm.unidad_medida_id', 'um.id')
                        ->where('dm.estado', 1)
                        ->groupBy('dm.id', 'dm.admision_id', 'dm.producto_id', 'dm.descripcion', 'dm.unidad_medida_id', 'um.descripcion', 'dm.cantidad', 'dm.precio_unitario', 'dm.precio_total', 'copago', 'dm.precio_cliente', 'dm.precio_aseguradora', DB::raw('dm.maestro_documento_id'))
                        ->orderBy('dm.id', 'ASC')
                        ->select('dm.admision_id', 'dm.producto_id', 'dm.descripcion', 'dm.unidad_medida_id', 'um.descripcion as unidad_medida_descripcion', 'dm.cantidad', 'dm.precio_unitario', 'dm.precio_total', 'copago', 'dm.precio_cliente', 'dm.precio_aseguradora', DB::raw('IFNULL(dm.maestro_documento_id,0) as maestro_documento_id'))
                        ->get();

        return response::json($registros);
    }

    public function update(Request $request, $id){
        $admisionId = Crypt::decrypt($id);
        if (isset($request->encabezado_revisado)) {
            $validData = $request->validate([
                'tipo_admision' => 'required',
                'fecha'         => 'required',
                'paciente_id'   => 'required',
                'hospital_id'   => 'required',
                'medico_id'     => 'required'
            ]);
            $paciente = Paciente::findOrFail($validData['paciente_id']);
            $admision = Admision::findOrFail($admisionId);
            // $admision->empresa_id       = Auth::user()->empresa_id;
            $admision->tipo_admision    = $validData['tipo_admision'];
            $admision->fecha            = $validData['fecha'];
            $admision->edad = Carbon::parse($paciente->fecha_nacimiento)->age;
            $admision->paciente_id      = $validData['paciente_id'];
            $admision->medico_id        = $validData['medico_id'];
            $admision->hospital_id      = $validData['hospital_id'];
            if (isset($request->admision_tercero)) {
                $admision->admision_tercero = $request->admision_tercero;
            } else {
                $admision->admision_tercero = 0;
            }
            if (strlen(trim($request->aseguradora_id)) > 0)  {
                $admision->aseguradora_id   = trim($request->aseguradora_id);
                $admision->poliza_no          = $request->poliza_no;
                $admision->aseguradora_aut_no = $request->aseguradora_aut_no;
                $admision->coaseguro          = $request->coaseguro;
                $admision->copago             = $request->copago;
            }else{
                $admision->aseguradora_id     = null;
                $admision->poliza_no          = null;
                $admision->aseguradora_aut_no = null;
                $admision->coaseguro          = 100;
                $admision->copago             = 0;
            }
            
            $admision->save();
        }else{
            $cargosRecibidos  = $request->cargos;
            //=======================================================================
            // si no vienen cargos se da de baja a todos los movimientos asociados a 
            // la admisión en el detalle de movimientos de inventario
            //=======================================================================
            if (!isset($cargosRecibidos)) {
                $registrosEliminados = DetalleMovimiento::where('admision_id', $admisionId)
                                       ->whereNull('maestro_documento_id')
                                       ->where('estado', 'A')
                                       ->get();
                foreach ($registrosEliminados as $key => $registroEliminado) {
                    $registro = DetalleMovimiento::where('id', $registroEliminado->id)->update(['estado' => 'E']);

                    $bitacora = new AdmisionBitacora();
                    $bitacora->admision_id = $admisionId;
                    $bitacora->proceso     = 'ELIMINAR';
                    $bitacora->observaciones = 'Eliminar cargo '.$dataAgregar[$i]['descripcion'];
                    $bitacora->save();
                }
            }else{
                // $cargos ya viene de $request['cargos']
                $maestroMovimientoId = DetalleMovimiento::where('admision_id', $admisionId)
                                       ->where('estado', 1)
                                       ->select('maestro_movimiento_id')
                                       ->first();
                //==================================================================================================
                // Si no existe cargos asociados a la admision
                //==================================================================================================
                if (!isset($maestroMovimientoId)) {
                    $anio = Carbon::now()->format('Y');
                    $inv_transaccion = DB::table('inventario_transacciones as it')
                                       ->where('it.empresa_id', Auth::user()->empresa_id)
                                       ->where('it.tipo_transaccion', 'V')
                                       ->where('it.estado', 1)
                                       ->first();
                    $correlativo = DB::table('maestro_movimientos as mm')
                                   ->where('mm.empresa_id', Auth::user()->empresa_id)
                                   ->where('mm.inventario_transaccion_id', $inv_transaccion->id)
                                   ->where('mm.anio', $anio)
                                   ->select(DB::raw('IFNULL(MAX(correlativo),0) as ultimo_correlativo'))
                                   ->first();
                    $nuevo_correlativo = $correlativo->ultimo_correlativo + 1;

                    $encabezado = new MaestroMovimiento();
                    $encabezado->empresa_id                = Auth::user()->empresa_id;
                    $encabezado->inventario_transaccion_id = $inv_transaccion->id;
                    $encabezado->signo                     = $inv_transaccion->signo;
                    $encabezado->correlativo               = $nuevo_correlativo;
                    $encabezado->anio                      = $anio;
                    $encabezado->bodega_origen_id          = 1;
                    $encabezado->estado                    = 1;
                    $encabezado->save();
                }else{
                    // $encabezado = MaestroMovimiento::findOrFail($maestroMovimientoId);
                    $encabezado = MaestroMovimiento::where('id', $maestroMovimientoId->maestro_movimiento_id)->first();
                }

                // 1️⃣ Obtener todas las combinaciones actuales en la BD
                $idsActuales = DetalleMovimiento::where('maestro_movimiento_id', $encabezado->id)->get(['producto_id', 'unidad_medida_id']);

                // 2️⃣ Extraer las combinaciones que vienen del request
                $idsNuevos = collect($cargosRecibidos)->map(function ($cargo) {
                    return [
                        'producto_id' => $cargo['producto_id'],
                        'unidad_medida_id' => $cargo['medida_id'],
                    ];
                });
                
                // 3️⃣ Determinar qué registros borrar
                $paraBorrar = $idsActuales->filter(function ($registro) use ($idsNuevos) {
                    return !$idsNuevos->contains(function ($nuevo) use ($registro) {
                        return $nuevo['producto_id'] == $registro->producto_id
                            && $nuevo['unidad_medida_id'] == $registro->unidad_medida_id;
                    });
                });

                // 4️⃣ Eliminar los que ya no están
                foreach ($paraBorrar as $item) {
                    DetalleMovimiento::where('maestro_movimiento_id', $encabezado->id)
                        ->where('producto_id', $item->producto_id)
                        ->where('unidad_medida_id', $item->unidad_medida_id)
                        ->update(['estado' => 2]);
                }

                foreach ($cargosRecibidos as $key => $cargo) {
                    //===============================================================================
                    // Localiza factor de conversion para el producto y la medida recibida
                    //===============================================================================
                    $producto_medida = ProductoMedida::where('producto_id', $cargo['producto_id'])
                                       ->where('unidad_medida_id', $cargo['medida_id'])
                                       ->first();

                    if (isset($producto_medida)) {
                        $cantidad_medida = $producto_medida->cantidad;
                    }else{
                        $cantidad_medida = 1;
                    }

                    $detalle = DetalleMovimiento::updateOrCreate(
                        [
                            'maestro_movimiento_id' => $encabezado->id,
                            'producto_id'        => $cargo['producto_id'],
                            'unidad_medida_id'   => $cargo['medida_id'],
                        ],
                        [
                            'admision_id' => $admisionId,
                            'descripcion' => $cargo['descripcion'],
                            'cantidad'    => $cargo['cantidad'],
                            'precio'      => $cargo['precio'],
                            'total'       => $cargo['total'],
                            'admision_id' => $admisionId,
                            'descripcion' => $cargo['descripcion'],
                            'cantidad'    => $cargo['cantidad'],
                            'cantidad_medida' => $cantidad_medida,
                            'cantidad_x_medida' => $cargo['cantidad'] * $cantidad_medida,
                            'precio_unitario'   => $cargo['precio'],
                            'precio_bruto'      => $cargo['total'],
                            'descuento'         => 0,
                            'recargo'           => 0,
                            'precio_base'       => $cargo['total'] / 1.12,
                            'precio_impuesto'   => $cargo['total'] - ($cargo['total'] / 1.12),
                            'precio_total'      => $cargo['total'],
                            'precio_cliente'    => $cargo['total'],
                            'precio_aseguradora' => 0,
                            'estado'             => 1,
                            'copago'             => 0,
                            'deducible'          => 0

                        ]
                    );
                }


                //=======================================================================
                // se localiza todos los movimientos activos asociados a la admision en 
                // detalle de movimientos de inventario
                //=======================================================================
                $cargosGuardados = DetalleMovimiento::where('admision_id', $admisionId)
                                                        ->whereNull('maestro_documento_id')
                                                        ->where('estado', 'A')
                                                        ->get();
                //=======================================================================
                // si existen cargos activos guardados
                //=======================================================================
                // if (isset($cargosGuardados) && count($cargosGuardados) > 0) {
                //     $maestroMovimientoId = DetalleMovimiento::where('admision_id', $admisionId)
                //                            ->where('estado', 'A')
                //                            ->select('maestro_movimiento_id')
                //                            ->first();

                //     $encabezado = MaestroMovimiento::where('id', $maestroMovimientoId->maestro_movimiento_id)
                //                   ->first();

                //     dd($cargosRecibidos);

                //     foreach ($cargosGuardados as $key => $cargoGuardado) {
                //         foreach ($cargosRecibidos as $key => $cargoRecibido) {
                //             dd($cargoGuardado); die;
                //             // dd($cargoRecibido); die;
                //         }
                //     }
                // }else{
                //     //=========================================================================
                //     // si no existen cargos activos para la admisión en detalle de movimientos
                //     // de inventario
                //     //=========================================================================
                //     $anio = Carbon::now()->format('Y');
                //     $inv_transaccion = DB::table('inventario_transacciones as it')
                //                        ->where('it.empresa_id', Auth::user()->empresa_id)
                //                        ->where('it.tipo_transaccion', 'V')
                //                        ->where('it.estado', 'A')
                //                        ->first();
                //     $correlativo = DB::table('maestro_movimientos as mm')
                //                    ->where('mm.empresa_id', Auth::user()->empresa_id)
                //                    ->where('mm.inventario_transaccion_id', $inv_transaccion->id)
                //                    ->where('mm.anio', $anio)
                //                    ->select(DB::raw('IFNULL(MAX(correlativo),0) as ultimo_correlativo'))
                //                    ->first();
                //     $nuevo_correlativo = $correlativo->ultimo_correlativo + 1;

                //     $encabezado = new MaestroMovimiento();
                //     $encabezado->empresa_id                = Auth::user()->empresa_id;
                //     $encabezado->inventario_transaccion_id = $inv_transaccion->id;
                //     $encabezado->signo                     = $inv_transaccion->signo;
                //     $encabezado->correlativo               = $nuevo_correlativo;
                //     $encabezado->anio                      = $anio;
                //     $encabezado->bodega_origen_id          = 1;
                //     $encabezado->estado                    = 'A';
                //     $encabezado->save();

                //     foreach ($cargosRecibidos as $key => $cargoRecibido) {
                //         // dd($cargoRecibido);
                //         //===============================================================================
                //         // Localiza factor de conversion para el producto y la medida recibida
                //         //===============================================================================
                //         $producto_medida = ProductoMedida::where('producto_id', $cargoRecibido['producto_id'])
                //                            ->where('unidad_medida_id', $cargoRecibido['medida_id'])
                //                            ->first();
                //         if (isset($producto_medida)) {
                //             $cantidad_medida = $producto_medida->cantidad;
                //         }else{
                //             $cantidad_medida = 1;
                //         }
                //         $cargo = new DetalleMovimiento();
                //         $cargo->maestro_movimiento_id = $encabezado->id;
                //         $cargo->admision_id           = $admisionId;
                //         $cargo->producto_id           = intval($cargoRecibido['producto_id']);
                //         $cargo->descripcion           = $cargoRecibido['descripcion'];
                //         $cargo->unidad_medida_id      = intval($cargoRecibido['medida_id']);
                //         $cargo->cantidad              = floatval($cargoRecibido['cantidad']);
                //         $cargo->cantidad_medida       = $cantidad_medida;
                //         $cargo->cantidad_x_medida     = $cargoRecibido['cantidad'] * $cantidad_medida;
                //         $cargo->precio_unitario       = $cargoRecibido['precio'];
                //         $cargo->precio_bruto          = $cargoRecibido['precio'];
                //         $cargo->descuento             = 0;
                //         $cargo->recargo               = 0;
                //         $cargo->precio_base           = $cargoRecibido['total'] / 1.12;
                //         $cargo->precio_impuesto       = $cargoRecibido['total'] - ($cargoRecibido['total'] / 1.12);
                //         $cargo->precio_total          = $cargoRecibido['total'];
                //         $cargo->copago                = 250;
                //         // $cargo->coaseguro             = $cargoRecibido['coaseguro'];
                //         $cargo->precio_cliente        = 0;
                //         $cargo->precio_aseguradora    = 0;
                //         $cargo->estado                = 'A';
                //         $cargo->save();

                //         $bitacora = new AdmisionBitacora();
                //         $bitacora->admision_id = $admisionId;
                //         $bitacora->proceso     = 'CARGOS';
                //         $bitacora->observaciones = 'Agregar cargo '.$cargoRecibido['descripcion'];
                //         $bitacora->save();
                //     }
                // }
            }
        }
        $message = array(
            'message' => 'Admision Actualizada con Exito !!!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    public function update_ajax(Request $request){
        dd($request);
        /*$cargos = $_POST['cargos'];
        print_r($cargos); die;
        $admision_id = $_POST['admision_id'];
        $paciente_id = $_POST['paciente_id'];
        $hospital_id = $_POST['hospital_id'];
        $medico_id   = $_POST['medico_id'];
        $tipo_admision    = $_POST['tipo_admision'];
        $fecha            = $_POST['fecha'];
        $admision_tercero = (int)$_POST['admision_tercero'];
        $aseguradora_id   = $_POST['aseguradora_id'];
        $poliza_no   = (int)$_POST['poliza_no'];
        $dataEliminar = (array) json_decode($_POST['eliminar'], true);
        $totalEliminar = count($dataEliminar);
        $dataAgregar = (array) json_decode($_POST['agregar'], true);
        $totalAgregar = count($dataAgregar);

        $admision = Admision::findOrFail($admision_id);
        $admision->empresa_id       = Auth::user()->empresa_id;
        $admision->tipo_admision    = $tipo_admision;
        $admision->fecha            = $fecha;
        if ($paciente_id != $admision->paciente_id) {
            $paciente = Paciente::where('id', $request->paciente_id)->first();
            $admision->edad = Carbon::parse($paciente->fecha_nacimiento)->age;
        }
        $admision->paciente_id      = $paciente_id;
        $admision->medico_id        = $medico_id;
        $admision->hospital_id      = $hospital_id;
        if (isset($admision_tercero)) {
            $admision->admision_tercero = $admision_tercero;
        } else {
            $admision->admision_tercero = 0;
        }
        if (strlen(trim($aseguradora_id)) > 0)  {
            $admision->aseguradora_id   = trim($aseguradora_id);
        }
        $admision->poliza_no        = $poliza_no;
        $admision->coaseguro        = 0;
        $admision->copago           = 0;
        $admision->save();

        if ($totalAgregar > 0) {
            if (DetalleMovimiento::where('admision_id', $admision_id)->whereNull('maestro_documento_id')->count() == 0) {
                $inv_transaccion = DB::table('inventario_transacciones as it')
                                   ->where('it.empresa_id', Auth::user()->empresa_id)
                                   ->where('it.tipo_transaccion', 'V')
                                   ->where('it.estado', 'A')
                                   ->first();

                $anio = Carbon::now()->format('Y');

                $correlativo = DB::table('maestro_movimientos as mm')
                               ->where('mm.empresa_id', Auth::user()->empresa_id)
                               ->where('mm.inventario_transaccion_id', $inv_transaccion->id)
                               ->where('mm.anio', $anio)
                               ->select(DB::raw('IFNULL(MAX(correlativo),0) as ultimo_correlativo'))
                               ->first();
                
                $nuevo_correlativo = $correlativo->ultimo_correlativo + 1;

                $encabezado = new MaestroMovimiento();
                $encabezado->empresa_id                = Auth::user()->empresa_id;
                $encabezado->inventario_transaccion_id = $inv_transaccion->id;
                $encabezado->signo                     = $inv_transaccion->signo;
                $encabezado->correlativo               = $nuevo_correlativo;
                $encabezado->anio                      = $anio;
                $encabezado->bodega_origen_id          = 1;
                $encabezado->estado                    = 'A';
                $encabezado->save();
            }else{
                $maestroMovimientoId = DetalleMovimiento::where('admision_id', $admision_id)->select('maestro_movimiento_id')->first();
                $encabezado = MaestroMovimiento::where('id', $maestroMovimientoId->maestro_movimiento_id)->first();
            }

            for($i=0; $i < $totalAgregar; $i++) {
                $producto_medida = ProductoMedida::where('producto_id', $dataAgregar[$i]['producto_id'])
                                   ->where('unidad_medida_id', $dataAgregar[$i]['medida_id'])
                                   ->first();
                if (isset($producto_medida)) {
                    $cantidad_medida = $producto_medida->cantidad;
                }else{
                    $cantidad_medida = 1;
                }

                $cargo = new DetalleMovimiento();
                $cargo->maestro_movimiento_id = $encabezado->id;
                $cargo->admision_id           = $admision_id;
                $cargo->producto_id           = intval($dataAgregar[$i]['producto_id']);
                $cargo->descripcion           = $dataAgregar[$i]['cargo_descripcion'];
                $cargo->unidad_medida_id      = intval($dataAgregar[$i]['medida_id']);
                $cargo->cantidad              = floatval($dataAgregar[$i]['cantidad']);
                $cargo->cantidad_medida       = $cantidad_medida;
                $cargo->cantidad_x_medida     = $dataAgregar[$i]['cantidad'] * $cantidad_medida;
                $cargo->precio_unitario       = $dataAgregar[$i]['precio_unitario'];
                $cargo->precio_bruto          = $dataAgregar[$i]['precio_total'];
                $cargo->descuento             = 0;
                $cargo->recargo               = 0;
                $cargo->precio_base           = $dataAgregar[$i]['precio_total'] / 1.12;
                $cargo->precio_impuesto       = $dataAgregar[$i]['precio_total'] - ($dataAgregar[$i]['precio_total'] / 1.12);
                $cargo->precio_total          = $dataAgregar[$i]['precio_total'];
                $cargo->precio_cliente        = $dataAgregar[$i]['total_cliente'];
                $cargo->precio_aseguradora    = $dataAgregar[$i]['total_aseguradora'];
                $cargo->estado                = 'A';
                $cargo->save();

                $bitacora = new AdmisionBitacora();
                $bitacora->admision_id = $admision_id;
                $bitacora->proceso     = 'CARGOS';
                $bitacora->observaciones = $dataAgregar[$i]['cargo_descripcion'];
                $bitacora->save();
            }

        }

        if ($totalEliminar > 0) {
            for ($i=0; $i < $totalEliminar; $i++) { 
                $cargo = DetalleMovimiento::where('admision_id', $dataEliminar[$i]['admision_id'])
                         ->where('producto_id', intval($dataEliminar[$i]['producto_id']))
                         ->where('unidad_medida_id', intval($dataEliminar[$i]['medida_id']))
                         ->delete();

                $bitacora = new AdmisionBitacora();
                $bitacora->admision_id   = $admision->id;
                $bitacora->proceso       = 'CARGO';
                $bitacora->observaciones = 'Eliminación de cargo '. $dataEliminar[$i]['producto_descripcion'];
                $bitacora->save();
            }
        }*/

        $respuesta = 'Admision Grabada con Exito !!!!';

        return response::json($respuesta);
        exit;
    }

    public function trae_cargos(){
        $admision_id = $_POST['admision_id'];

        $pCargos = DB::table('detalle_movimientos as dm')
                   ->join('unidad_medidas as um', 'dm.unidad_medida_id', 'um.id')
                   ->where('dm.admision_id', $admision_id)
                   ->groupBy('dm.admision_id', 'dm.producto_id', 'dm.descripcion', 'dm.unidad_medida_id', 'um.descripcion', 'dm.cantidad', 'dm.precio_unitario', 'dm.precio_total', 'dm.precio_cliente', 'dm.precio_aseguradora', DB::raw('dm.maestro_documento_id'))
                   ->select('dm.admision_id', 'dm.producto_id', 'dm.descripcion', 'dm.unidad_medida_id', 'um.descripcion as unidad_medida_descripcion', 'dm.cantidad', 'dm.precio_unitario', 'dm.precio_total', 'dm.precio_cliente', 'dm.precio_aseguradora', DB::raw('IFNULL(dm.maestro_documento_id,0) as maestro_documento_id'))
                   ->orderBy('dm.id', 'ASC')
                   ->get();

        return response::json($pCargos);

    }

    public function cerrar_admision_ajax(){

        $admision_id = Crypt::decrypt($_POST['admision_id']);
        
        $admision = Admision::findOrFail($admision_id);
        $admision->estado = 1;
        $admision->save();

        $bitacora = new AdmisionBitacora();
        $bitacora->admision_id   = $admision->id;
        $bitacora->proceso       = 'CIERRE';
        $bitacora->observaciones = 'Cierre de admision';
        $bitacora->save();

        // return response::json('Admision Cerrada con Exito !!!!');
        return response()->json([
                                'message' => 'Registro actualizado con exito !!!',
                                'type'    => 'success'
                            ]);

    }

    public function get_id_x_admision(){
        $admision = $_POST['admision'];
        $verifica = Admision::where('admision_no', $admision)->count();
        if ($verifica > 0) {
            $admision_id = Admision::select('id')->where('admision_no', $admision)->first();
            $admision_id = $admision_id->id;
        }else{
            $admision_id = '';
        }

        return Response::json($admision_id);

    }

    public function trae_datos_para_factura(){
        $admision_id = $_POST['admision_id'];
        $cargos = DB::table('detalle_movimientos as dm')
                  ->join('productos as p', 'dm.producto_id', 'p.id')
                  ->join('unidad_medidas as um', 'dm.unidad_medida_id', 'um.id')
                  ->where('dm.admision_id', $admision_id)
                  ->whereNull('dm.maestro_documento_id')
                  ->where('dm.precio_cliente','>',0)
                  ->where('dm.estado', 'A')
                  ->select('dm.producto_id','dm.descripcion as producto_descripcion', 'dm.cantidad', 'dm.unidad_medida_id', 'um.descripcion as unidad_medida_descripcion', 'dm.precio_cliente as precio_unitario', 'dm.precio_cliente as precio_total')
                  ->get();
        $respuesta = array('cargos' => $cargos);
        return response::json($respuesta);
    }

    public function trae_cargos_a_facturar(){
        // $paciente_id      = $_POST['paciente_id'];
        $admision_id      = $_POST['admision_id'];
        //$origen           = $_POST['origen'];
        // $tipo_facturacion = $_POST['tipo_facturacion'];

        $cargos = [];
        $cargos = DB::table('detalle_movimientos as dm')
                      ->join('productos as p', 'dm.producto_id', 'p.id')
                      ->join('unidad_medidas as um', 'dm.unidad_medida_id', 'um.id')
                      ->join('admisiones as a', 'dm.admision_id', 'a.id')
                      ->where('a.id', $admision_id)
                      ->where('a.estado', 1)
                      ->where('dm.precio_total','>',0)
                      ->where('dm.estado', 1)
                      ->whereNotExists(function($query){
                            $query->select(DB::raw(1))
                                  ->from('documentoventa_detalles as dd')
                                  ->whereRaw('dm.id = dd.detalle_movimiento_id')
                                  // ->where('dd.tipo_facturacion', 'N')
                                  ->where('dd.estado', '1');
                        })
                      ->select('dm.id as detalle_movimiento_id','dm.admision_id','dm.producto_id','dm.descripcion as producto_descripcion', 'dm.cantidad', 'dm.unidad_medida_id', 'um.descripcion as unidad_medida_descripcion', 'dm.precio_cliente', 'dm.precio_aseguradora', 'dm.precio_unitario', 'dm.precio_bruto')
                      ->get();
        // print_r($cargos); die;
        // if ($tipo_facturacion == 'P') {
        //     $cargos = DB::table('detalle_movimientos as dm')
        //               ->join('productos as p', 'dm.producto_id', 'p.id')
        //               ->join('unidad_medidas as um', 'dm.unidad_medida_id', 'um.id')
        //               ->join('admisiones as a', 'dm.admision_id', 'a.id')
        //               ->where('a.paciente_id', $paciente_id)
        //               ->where('dm.precio_cliente','>',0)
        //               ->where('dm.estado', 1)
        //               ->whereNotExists(function($query){
        //                     $query->select(DB::raw(1))
        //                           ->from('documentoventa_detalles as dd')
        //                           ->whereRaw('dm.id = dd.detalle_movimiento_id')
        //                           ->where('dd.tipo_facturacion', 'P')
        //                           ->where('dd.estado', 'A');
        //                 })
        //               ->select('dm.id as detalle_movimiento_id','admision_id','dm.producto_id','dm.descripcion as producto_descripcion', 'dm.cantidad', 'dm.unidad_medida_id', 'um.descripcion as unidad_medida_descripcion', 'dm.precio_cliente', 'dm.precio_aseguradora')
        //               ->get();
        // }else{
        //     $cargos = DB::table('detalle_movimientos as dm')
        //               ->join('productos as p', 'dm.producto_id', 'p.id')
        //               ->join('unidad_medidas as um', 'dm.unidad_medida_id', 'um.id')
        //               ->join('admisiones as a', 'dm.admision_id', 'a.id')
        //               ->where('a.paciente_id', $paciente_id)
        //               ->where('dm.precio_cliente','>',0)
        //               ->where('dm.estado', 'A')
        //               ->whereNotExists(function($query){
        //                     $query->select(DB::raw(1))
        //                           ->from('documentoventa_detalles as dd')
        //                           ->whereRaw('dm.id = dd.detalle_movimiento_id')
        //                           ->where('dd.tipo_facturacion', 'A')
        //                           ->where('dd.estado', 'A');
        //                 })
        //               ->select('dm.id as detalle_movimiento_id','admision_id','dm.producto_id','dm.descripcion as producto_descripcion', 'dm.cantidad', 'dm.unidad_medida_id', 'um.descripcion as unidad_medida_descripcion', 'dm.precio_cliente', 'dm.precio_aseguradora')
        //               ->get();
        // }        

        return response::json($cargos);
    }

    public function trae_datos_facturacion_x_admision(){
        $admision_id = $_POST['admision_id'];
        $registro = DB::table('admisiones as a')
                    ->leftjoin('nits as n', 'a.paciente_id', 'n.paciente_id')
                    ->select('a.paciente_id', 'n.nit', 'n.nombre', 'n.direccion')
                    ->where('a.id', $admision_id)
                    ->first();

        return response::json($registro);
    }

    public function trae_paciente_x_admision(){
        $admision_id = $_POST['admision_id'];
        $admision = Admision::findOrFail($admision_id);
        $registro = Paciente::findOrFail($admision->paciente_id);
        return response::json($registro);
    }

    public function graph_admisiones_01(){
        $total   = Admision::where('empresa_id', Auth::user()->empresa_id)->count();
        $conCita = Admision::where('empresa_id', Auth::user()->empresa_id)
                             ->whereNotNull('agenda_id')
                             ->count();
        $sinCita = Admision::where('empresa_id', Auth::user()->empresa_id)
                             ->whereNull('agenda_id')->count();

        $array[0] = ['tipo', 'cantidad'];
        $array[1]= ['Con Cita', $conCita];
        $array[2]= ['Sin Cita', $sinCita];

        return json_encode($array);
    }

    public function graph_admisiones_03(){
        $resumen = DB::table('admisiones as a')
                    ->select(DB::raw('TIMESTAMPDIFF(DAY, created_at, now()) AS dias_transcurridos'), 
                             DB::raw('count(*) as total')
                             )
                    ->groupBy(DB::raw('TIMESTAMPDIFF(DAY, created_at, now())'))
                    ->get();
        
        $array = array();
        array_push($array, ['dias_transcurridos', 'total']);
        foreach ($resumen as $key => $r) {
            array_push($array, $r);
        }
        return json_encode($array);   
    }

    public function graph_admisiones_02(){
        $resultado = Admision::where('empresa_id', Auth::user()->empresa_id)
                     ->select(DB::raw('CASE tipo_admision WHEN "C" THEN "CONSULTA" WHEN "P" THEN "PROCEDIMIENTO" ELSE "HOSPITALIZACION" END as tipo_admision'), DB::raw('COUNT(id) as total'))
                     ->groupBy('tipo_admision')
                     ->get();

        $array[] = ['tipo', 'cantidad'];
        foreach($resultado as $key => $value)
        {
          $array[++$key] = [$value->tipo_admision, $value->total];
        }

        return json_encode($array);
    }

    public function get_total_admisiones(){
        $total = Admision::where('empresa_id', Auth::user()->empresa_id)->count();
        
        return Response()->json($total);
    }

    public function get_total_admisiones_v2(){
        $fecha_inicial = $_POST['fecha_inicial'];
        $fecha_final   = $_POST['fecha_final'];
        $result = [];

        $empresa_id = Auth::user()->empresa_id;

        $total = Admision::where('empresa_id', Auth::user()->empresa_id)
                 ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
                 ->count();

        array_push($result, ['total_adm' => $total]);

        $total = Admision::where('empresa_id', Auth::user()->empresa_id)
                 ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
                 ->where('estado', 'P')
                 ->count();

        array_push($result, ['total_adm_activas' => $total]);

        $total = DB::table('admisiones as a')
                 ->join('detalle_movimientos as dm', 'a.id', 'dm.admision_id')
                 ->where('a.empresa_id', Auth::user()->empresa_id)
                 ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
                 ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                              ->from('documentoventa_detalles as dvd')
                              ->whereRaw('dvd.detalle_movimiento_id = dm.id');
                 })
                 ->select(DB::raw('COUNT(a.id) as total_admisiones'))
                 ->first();

        array_push($result, ['total_adm_con_saldo' => $total]);

        $admisiones = DB::table('admisiones as a')
                    ->join('detalle_movimientos as dm', 'a.id', 'dm.admision_id')
                    ->join('pacientes as p', 'a.paciente_id', 'p.id')
                    ->leftjoin(DB::raw('(select admision_id, 
                                                sum(ifnull(precio_total,0)) as total 
                                         from detalle_movimientos 
                                         where maestro_documento_id is not null
                                         group by admision_id) f'),
                              function($j){
                                $j->on('a.id', '=', 'f.admision_id');
                              })
                    ->leftjoin(DB::raw('(select x.admision_id, sum(x.monto_aplicado) as total
                                         from(select dm.admision_id, pd.monto_aplicado
                                              from detalle_movimientos as dm
                                              join pago_documentos as pd on dm.maestro_documento_id = pd.documentoventa_id
                                          group by dm.admision_id, pd.monto_aplicado) as x
                                          group by x.admision_id) p'),
                              function($j){
                                $j->on('a.id', '=', 'p.admision_id');
                              })
                    ->select('a.admision_no', 'p.nombre_completo as paciente_nombre', 'a.fecha', DB::raw('SUM(dm.precio_total) as total_cargos'), 
                              DB::raw('ifnull(f.total,0) as total_facturado'), 
                              DB::raw('ifnull(p.total,0) as total_pagado'),
                              DB::raw('ifnull(f.total,0)-ifnull(p.total,0) as saldo')
                            )
                    ->where('a.empresa_id', Auth::user()->empresa_id)
                    ->whereBetween('a.fecha', [$fecha_inicial, $fecha_final])
                    ->Where(DB::raw('ifnull(f.total,0)-ifnull(p.total,0)'), '!=', 0)
                    ->groupBy('a.admision_no', 'p.nombre_completo', 'a.fecha', 
                               DB::raw('ifnull(f.total,0)'), 
                               DB::raw('ifnull(p.total,0)'),
                               DB::raw('ifnull(f.total,0)-ifnull(p.total,0)')
                             )
                    ->get();

        array_push($result, ['listado_admisiones_con_saldo' => $admisiones]);

        $espacios = Agenda::where('estado', '!=', 'B')
                    ->whereBetween(DB::raw('DATE(fecha_inicio)'), [$fecha_inicial, $fecha_final])
                    ->count();

        $asosiados = Agenda::where('estado', '!=', 'B')
                     ->where('estado', '!=', 'P')
                     ->whereBetween(DB::raw('DATE(fecha_inicio)'), [$fecha_inicial, $fecha_final])
                     ->count();

        $total_admisiones = DB::table('agendas as a')
                            ->join(DB::raw('(select agenda_id
                                             from admisiones
                                             where estado IN("P", "C")
                                             ) ad'),
                            function($j){
                                $j->on('a.id', '=', 'ad.agenda_id');
                            })
                            ->where('estado', '!=', 'P')
                            ->whereBetween(DB::raw('DATE(fecha_inicio)'), [$fecha_inicial, $fecha_final])
                            ->count();    

        if (isset($espacios) && $espacios > 0 ) {
            array_push($result, ['porcentaje_ocupacion' => (($asosiados / $espacios)*100) ]);
        }else{
            array_push($result, ['porcentaje_ocupacion' => 0 ]);
        }
        
        if (isset($asosiados) && $asosiados > 0) {
            array_push($result, ['porcentaje_admisiones' => (($total_admisiones / $asosiados)*100) ]);
        }else{
            array_push($result, ['porcentaje_admisiones' => 0 ]);
        }

        // *************************************************************************************//
        // ********************************   Finanzas   ***************************************//
        // *************************************************************************************//
        // 1. Subconsulta de Detalles (Totales por maestro)
        $subDetalles = DB::table('documentoventa_detalles')
            ->select('documentoventa_maestro_id', DB::raw('SUM(precio_neto) AS total'))
            ->where('estado', '!=', 2)
            ->groupBy('documentoventa_maestro_id');

        // 2. Subconsulta de Pagos (Total pagado por documento)
        $subPagos = DB::table('pago_documentos')
            ->select('documentoventa_id', DB::raw('SUM(monto_aplicado) AS total_pagado'))
            ->where('estado', 1)
            ->groupBy('documentoventa_id');

        // 3. Consulta Principal
        $resumen = DB::table('documentoventa_maestros as dvm')
            ->leftJoinSub($subDetalles, 'dvd', function ($join) {
                $join->on('dvm.id', '=', 'dvd.documentoventa_maestro_id');
            })
            ->leftJoinSub($subPagos, 'pd', function ($join) {
                $join->on('dvm.id', '=', 'pd.documentoventa_id');
            })
            ->where('dvm.empresa_id', $empresa_id)
            ->whereBetween('dvm.fecha_emision', [$fecha_inicial, $fecha_final])
            ->select(
                DB::raw('COUNT(1) AS total_documentos'),
                DB::raw('SUM(CASE WHEN dvm.estado = 2 THEN 1 ELSE 0 END) AS total_anulados'),
                DB::raw('SUM(IFNULL(dvd.total, 0)) AS monto_facturado'),
                DB::raw('SUM(IFNULL(dvd.total, 0) - IFNULL(pd.total_pagado, 0)) AS saldo_pendiente')
            )
            ->first(); // Usamos first() porque solo esperamos una fila de totales

        array_push($result, ['ventas' => $resumen ]);
        
        return Response()->json($result);
    }

    public function get_total_admisiones_activas(){
        $total = Admision::where('empresa_id', Auth::user()->empresa_id)->where('estado', 'P')->count();
        
        return Response()->json($total);
    }

    public function get_total_admisiones_con_saldo(){
        $total = DB::table('admisiones as a')
                 ->join(DB::raw('(select admision_id 
                                 from detalle_movimientos 
                                 where estado = 1
                                   and maestro_documento_id is null
                                 group by admision_id) detalle'),
                        function($j){
                            $j->on('a.id', '=', 'detalle.admision_id');
                        })
                 ->where('a.empresa_id', Auth::user()->empresa_id)
                 ->where('a.estado', 'P')
                 ->select(DB::raw('COUNT(a.id) as total_admisiones'))
                 ->first();
        
         return Response()->json($total);
    }

    public function trae_consulta(){
        $atencion_id = $_POST['atencion_id'];

        // $data = DB::table('admision_consultas as ac')
        //         ->where('ac.admision_id', $admision_id)
        //         ->select('ac.id as detalle_id', 'ac.subjetivo', 'ac.objetivo', 'ac.impresion_clinica', 'ac.plan', 'ac.tratamiento', 'ac.peso', 'ac.talla', 'ac.pulso', 'ac.temperatura', 'ac.respiracion', 'ac.presion_sistolica', 'ac.presion_diastolica', 'ac.bmi')
        //         ->first();
        $data = DB::table('admision_atenciones as aa')
                ->where('aa.id', $atencion_id)
                ->select('aa.csubjetivo', 'aa.cobjetivo', 'aa.cimpresion_clinica', 'aa.cplan', 'aa.ctratamiento')
                ->first();

        return Response::json($data);
    }

    public function trae_egreso(){
        $admision_id = $_POST['id'];

        $data = DB::table('admisiones as a')
                ->join('medicos as m', 'a.medico_id', 'm.id')
                ->join('hospitales as h', 'a.hospital_id', 'h.id')
                ->leftjoin('aseguradoras as s', 'a.aseguradora_id', 's.id')
                ->where('a.id', $admision_id)
                ->select('a.id', 'a.admision', 'm.nombre_completo as medico_nombre', 'h.nombre as hospital_nombre', 'a.created_at as fecha', 'a.edad as edad', 's.nombre as aseguradora_nombre', 'a.poliza_no', 'a.coaseguro', 'a.copago', 'a.fecha_inicio', 'a.fecha_fin', 'a.resumen_egreso', 'a.id as detalle_id')
                ->first();
        
        return Response::json($data);
    }

    public function trae_procedimiento(){
        $admision_id = $_POST['id'];
        $data = DB::table('admisiones as a')
                ->join('medicos as m', 'a.medico_id', 'm.id')
                ->join('hospitales as h', 'a.hospital_id', 'h.id')
                ->leftjoin('aseguradoras as s', 'a.aseguradora_id', 's.id')
                ->leftjoin('admision_procedimientos as ap', 'a.id', 'ap.admision_id')
                ->where('a.id', $admision_id)
                //->where('a.tipo_admision', 'P')
                ->select('a.id', 'a.admision', 'm.nombre_completo as medico_nombre', 'h.nombre as hospital_nombre', 'a.created_at as fecha', 'a.edad as edad', 's.nombre as aseguradora_nombre', 'a.poliza_no', 'a.coaseguro', 'a.copago', 'ap.id as detalle_id', 'ap.procedimiento_id', 'ap.tolerancia', 'ap.premedicacion', 'ap.patologo', 'ap.anestesiologo', 'ap.indicacion', 'ap.hallazgos', 'ap.diagnostico', 'ap.recomendaciones')
                ->first();
        
        return response::json($data);
        
    }

    public function update_consulta_ajax(){

        // dd($_POST['consulta_admision_id'].' - '.$_POST['consulta_atencion_id']);
        $admision_id = $_POST['consulta_admision_id'];
        $admision = Admision::findOrFail($admision_id);
        if ($admision->estado == 0) {
            if ($_POST['consulta_atencion_id'] == 0) {
                $registro                   = new AdmisionAtencion();
                $registro->admision_id      = $admision_id;
                $registro->tipo_atencion_id = 1;
            }else{
                $registro = AdmisionAtencion::findOrFail($_POST['consulta_atencion_id']);
            }

            $registro->csubjetivo         = $_POST['consulta_subjetivo'];
            $registro->cobjetivo          = $_POST['consulta_objetivo'];
            $registro->cimpresion_clinica = $_POST['consulta_impresion_clinica'];
            $registro->cplan              = $_POST['consulta_plan'];
            $registro->ctratamiento       = $_POST['consulta_tratamiento'];
            $registro->estado             = 1;
            $registro->save();

            $message = array(
                'message' => 'Registro Actualizado con Exito !!!',
                'type'    => 'success'
            );
            
            return response()->json($message);
        }else{
            $message = array(
                'message' => 'La admisión está cerrada. Para realizar cambios, por favor solicite una reapertura. !!!',
                'type'    => 'error'
            );
            
            return response()->json($message);
        }
        
    }

    public function update_hospitalizacion_ajax(){
        // dd($_POST['hospitalizacion_admision_id'].' - '.$_POST['hospitalizacion_atencion_id']);
        // $admision_id = $_POST['hospitalizacion_admision_id'];
        // if ($_POST['hospitalizacion_atencion_id'] == 0) {
        //     $hospitalizacion                   = new AdmisionAtencion();
        //     $hospitalizacion->admision_id      = $admision_id;
        //     $hospitalizacion->tipo_atencion_id = 2;
        // }else{
        //     $hospitalizacion = AdmisionAtencion::findOrFail($_POST['hospitalizacion_atencion_id']);
        // }

        // $hospitalizacion->hfecha_inicio    = $_POST['fecha_inicio'];
        // $hospitalizacion->hfecha_fin       = $_POST['fecha_fin'];
        // $hospitalizacion->hresumen         = $_POST['resumen_egreso'];
        // $hospitalizacion->estado           = 1;
        // $hospitalizacion->save();

        // $message = array(
        //     'message' => 'Registro Actualizado con Exito !!!',
        //     'type'    => 'success'
        // );
        // return redirect()->back()->with($message);

        $admision_id = $_POST['hospitalizacion_admision_id'];
        if ($_POST['hospitalizacion_atencion_id'] == 0) {
            $registro                   = new AdmisionAtencion();
            $registro->admision_id      = $admision_id;
            $registro->tipo_atencion_id = 2;
        }else{
            $registro = AdmisionAtencion::findOrFail($_POST['hospitalizacion_atencion_id']);
        }

        $registro->hfecha_inicio      = $_POST['fecha_inicio'];
        $registro->hfecha_fin         = $_POST['fecha_fin'];
        $registro->hresumen           = $_POST['resumen_egreso'];
        $registro->estado             = 1;
        $registro->save();

        $message = array(
            'message' => 'Registro Actualizado con Exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    public function update_procedimiento_ajax(Request $request){
        // 1. Validar (buena práctica)
        $messages = [
            'required' => 'El campo :attribute es obligatorio.',
            'image'    => 'El archivo seleccionado en :attribute debe ser una imagen.',
            'mimes'    => 'La imagen en :attribute debe ser de tipo: jpeg, png, jpg.',
            'max'      => 'La imagen en :attribute no debe pesar más de 2MB.',
            'imagenes.*.image' => 'Uno de los archivos subidos no es una imagen válida.',
        ];

        $attributes = [
            'procedimiento_admision_id' => 'ID de admisión',
            'procedimiento_atencion_id' => 'ID de atención',
            'p_procedimiento_id'        => 'tipo de procedimiento',
            'imagenes'                  => 'galería de imágenes',
        ];

        $validData = $request->validate([
            'procedimiento_admision_id' => 'required',
            'procedimiento_atencion_id' => 'required',
            'p_procedimiento_id'        => 'required',
            'imagenes.*'                => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], $messages, $attributes);

        $admision_id = $request['procedimiento_admision_id'];
        if ($request['procedimiento_atencion_id'] == 0) {
            $registro                   = new AdmisionAtencion();
            $registro->admision_id      = $admision_id;
            $registro->tipo_atencion_id = 3;
        }else{
            $registro = AdmisionAtencion::findOrFail($request['procedimiento_atencion_id']);
        }

        $registro->pprocedimiento_id   = $request['p_procedimiento_id'];
        $registro->ppremedicacion      = $request['p_premedicacion_id'];
        $registro->ptolerancia         = $request['tolerncia'];
        $registro->ppatologo           = $request['ppatologo'];
        $registro->panestesiologo      = $request['panestesilogo'];
        $registro->indicacion          = $request['pindicacion'];
        $registro->hallazgos           = $request['phallazgos'];
        $registro->diagnostico         = $request['pdiagnostico'];
        $registro->recomendaciones     = $request['precomendacion'];
        $registro->estado              = 1;
        $registro->save();


        // ... (tu código anterior de validación y guardado de $registro)

        $atencion_id = $registro->id;

        // --- PARTE A: GESTIONAR IMÁGENES EXISTENTES (EDICIÓN) ---
        // Primero ponemos todas las imágenes actuales de este procedimiento como NO VISIBLES
        AdmisionAtencionImagen::where('admision_atencion_id', $atencion_id)->update(['visible_informe' => 0]);

        // Ahora marcamos como VISIBLES solo las que el usuario dejó marcadas en el modal
        $viejasSeleccionadas = $request->input('imagenes_viejas_visibles', []); // Array de IDs
        if (!empty($viejasSeleccionadas)) {
            AdmisionAtencionImagen::whereIn('id', $viejasSeleccionadas)->update(['visible_informe' => 1]);
        }

        $guardados = [];
        
        // 2. Obtener los nombres de los archivos que tienen el checkbox marcado
        $imagenesSeleccionadas = $request->input('procesar_imagen', []);
        // 3. Obtener los archivos físicos
        if ($request->hasFile('imagenes')) {
            // 1. Definir y asegurar que la carpeta existe
            $carpetaDestino = storage_path('app/public/procedimientos'); // Recomendado usar app/public
            
            if (!file_exists($carpetaDestino)) {
                // Creamos la carpeta con permisos 0755
                mkdir($carpetaDestino, 0755, true);
            }

            foreach ($request->file('imagenes') as $file) {
                $nombreOriginal = $file->getClientOriginalName();
                $nombreHashed = time() . '_' . $file->hashName();

                // PROCESAMIENTO
                $img = Image::read($file);
                $img->scale(width: 1000); 

                // GUARDAR FÍSICAMENTE
                // Es mejor separar la carpeta del nombre del archivo
                $rutaCompleta = $carpetaDestino . '/' . $nombreHashed;
                $img->save($rutaCompleta);

                // GUARDAR EN BASE DE DATOS
                $nuevaImagen = new AdmisionAtencionImagen();
                $nuevaImagen->admision_atencion_id = $registro->id;
                $nuevaImagen->ruta = 'procedimientos/' . $nombreHashed; // Guardamos ruta relativa
                $nuevaImagen->nombre_original = $nombreOriginal;
                $nuevaImagen->visible_informe = in_array($nombreOriginal, $imagenesSeleccionadas) ? 1 : 0;
                $nuevaImagen->estado = 1;
                $nuevaImagen->save();

                $guardados[] = $nombreOriginal;
            }
        }

        return response()->json(['message' => 'Procedimiento e imágenes guardados', 'detalles' => $guardados]);

        // return response()->json([
        //     'type' => 'success',
        //     'message' => 'Se procesaron ' . count($guardados) . ' imágenes con éxito.',
        //     'archivos' => $guardados
        // ]);

        // if ($_POST['detalle_id'] == 0) {
        //     $procedimiento = new AdmisionProcedimiento();
        // }else{
        //     $procedimiento = AdmisionProcedimiento::findOrFail($_POST['detalle_id']);
        // }

        // $procedimiento->admision_id      = $_POST['admision_id'];
        // $procedimiento->paciente_id      = $_POST['paciente_id'];
        // $procedimiento->procedimiento_id = $_POST['procedimiento_id'];
        // $procedimiento->tolerancia       = $_POST['tolerancia'];
        // $procedimiento->premedicacion    = $_POST['premedicacion'];
        // $procedimiento->anestesiologo    = $_POST['anestesiologo'];
        // $procedimiento->patologo         = $_POST['patologo'];
        // $procedimiento->indicacion       = $_POST['indicacion'];
        // $procedimiento->hallazgos        = $_POST['hallazgos'];
        // $procedimiento->diagnostico      = $_POST['diagnostico'];
        // $procedimiento->recomendaciones  = $_POST['recomendaciones'];
        // $procedimiento->save();

        // $respuesta = array('id' => $procedimiento->id , 'respuesta' => 'Procedimiento grabado con exito');

        // return Response::json($respuesta);

    }

    public function trae_generales(){
        $admision_id = $_REQUEST['admision_id'];

        $generales = DB::table('admisiones as a')
                     // ->leftjoin('admision_atenciones as aa', 'a.id', 'aa.admision_id')
                     // ->leftjoin('tipo_atenciones as ta', 'aa.tipo_atencion_id', 'ta.id')
                     ->join('medicos as m', 'a.medico_id', 'm.id')
                     ->join('hospitales as h', 'a.hospital_id', 'h.id')
                     ->leftjoin('aseguradoras as a1', 'a.aseguradora_id', 'a1.id')
                     ->where('a.id', $admision_id)
                     ->select('a.id', 'a.admision_no', 'a.fecha', 'h.nombre as hospital_nombre', 
                              'm.nombre_completo as medico_nombre', 'a1.nombre as aseguradora_nombre',
                              'a.poliza_no', 'a.medico_id', 'a.edad', 'a.estado', 'a.atencion_medica',
                              DB::raw("
                                    CASE 
                                        /* 1. Si el campo ya tiene segundos (atención finalizada), devolvemos ese valor */
                                        WHEN a.segundos_atencion_medica > 0 THEN a.segundos_atencion_medica
                                        
                                        /* 2. Si el campo es cero o null, pero ya inició la atención, calculamos contra NOW() */
                                        WHEN a.inicio_atencion_medica IS NOT NULL THEN 
                                            TIMESTAMPDIFF(SECOND, a.inicio_atencion_medica, COALESCE(a.final_atencion_medica, NOW()))
                                        
                                        /* 3. Caso contrario (no ha iniciado), devolvemos 0 */
                                        ELSE 0 
                                    END as segundos_atencion
                                ")
                     )
                     ->first();

        return Response::json($generales);
    }

    public function cargarDocumento(Request $request){
        $file = $request->file('archivoInput');
        $admision_id = Crypt::decrypt($request->cargad_admision_id);
        $paciente_id = Crypt::decrypt($request->cargad_paciente_id);
        $admision    = Admision::where('id', $admision_id)->first();
        $correlativo = DB::table('admision_documentos')->where('admision_id', $admision_id)->count();
        if (!isset($correlativo)) {
            $correlativo = 1;
        }
        $correlativo = str_pad($correlativo + 1,4,'0', STR_PAD_LEFT);
        if($request->hasFile('archivoInput')){
            $file = $request->file('archivoInput');
            $file_name = time().'_'.$file->getClientOriginalName();
            $extension  = $file->getClientOriginalExtension();
            $size = $file->getSize();
            $type = $file->getType();
            $path = 'documentos';

            if(!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $path = $path ."/". $admision->admision;

            if(!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            try {
                $saved = Storage::disk('public')->put($path.'/'.$file_name, File::get($file));
                if ($saved) {
                    $documento = new AdmisionDocumento();
                    $documento->paciente_id = $paciente_id;
                    $documento->admision_id = $admision_id;
                    $documento->titulo      = $request->titulo;
                    $documento->tipo        = $type;
                    $documento->extension   = $extension;
                    $documento->ruta        = $file_name;
                    $documento->tamano      = $size;
                    $documento->save();

                    $bitacora = new AdmisionBitacora();
                    $bitacora->admision_id = $admision_id;
                    $bitacora->proceso     = 'DOCUMENTOS';
                    $bitacora->observaciones = 'Carga de documento "'.$documento->titulo;
                    $bitacora->save();

                    return response()->json([
                                'message' => 'Archivo cargado con exito !!!',
                                'type'    => 'success'
                            ]);
                }else{
                    return response()->json([
                                'message' => 'Hubo un problema al guardar el archivo.',
                                'type'    => 'error'
                            ]);
                }
            }
            catch (\Exception $exception) {
                return response($exception,400);
            }
        }
        // $file_name   = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // $extension   = $file->getClientOriginalExtension();
        // $file_name   = $file_name.'_'.$correlativo.'.'.$extension;
        // $public_path = storage_path();
        // $anio = Carbon::now()->format('Y');
        // $carpeta     = $public_path.'/documentos/';
        
        
    }

    public function receta($atencion_id) {
        $pEmpresa = Empresa::findOrFail(Auth::user()->empresa_id);

        // 1. Limpiar Logo Empresa (si es JSON)
        $datosLogo = json_decode($pEmpresa->ruta_logo, true);
        $rutaLogo = is_array($datosLogo) ? ($datosLogo['logo'] ?? $pEmpresa->ruta_logo) : $pEmpresa->ruta_logo;

        $pConsulta = DB::table('admision_atenciones as aa')
            ->join('admisiones as a', 'aa.admision_id', 'a.id')
            ->join('pacientes as p', 'a.paciente_id', 'p.id')
            ->where('aa.id', $atencion_id)
            ->select('a.id', 'a.fecha','aa.ctratamiento', 'a.medico_id',
                DB::raw("CONCAT(CASE WHEN p.genero = 'M' THEN 'Sr. ' ELSE 'Sra. ' END, p.nombre_completo) as paciente_nombre"))
            ->first();

        $medico = Medico::findOrFail($pConsulta->medico_id);
        
        // 2. LIMPIAR FIRMA DEL MÉDICO (Aquí es donde saltaba tu error)
        // Asumiendo que $medico->firma contiene el JSON {"firma":"firmas\/firma.jpg"}
        $datosFirma = json_decode($medico->firma, true);
        $rutaFirma = is_array($datosFirma) ? ($datosFirma['firma'] ?? $medico->firma) : $medico->firma;

        // 3. Convertir a Base64 para que el Hosting no falle buscando rutas
        $pathFirma = public_path($rutaFirma);
        $firmaBase64 = null;
        if (file_exists($pathFirma) && is_file($pathFirma)) {
            $type = pathinfo($pathFirma, PATHINFO_EXTENSION);
            $data = file_get_contents($pathFirma);
            $firmaBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $pRecetaC = Receta_Medico::where('medico_id', $medico->id)->first();
        
        // ... (Tu lógica de fechas y switch mes se mantiene igual) ...
        $fecha = \Carbon\Carbon::parse($pConsulta->fecha);
        $dia = $fecha->format('d');
        $nombre_mes = $this->getNombreMes($fecha->format('m')); // Simplificado
        $anio = $fecha->format('Y');

        $posiciones = [
            'pagina'      => ['alto' => ($pRecetaC->pagina_alto * 2.834).' pt', 'ancho' => ($pRecetaC->pagina_ancho * 2.834).' pt'],
            'dia'         => ['x' => $pRecetaC->dia_x * 2.834, 'y' => $pRecetaC->dia_y * 2.834],
            'mes'         => ['x' => $pRecetaC->mes_x * 2.834, 'y' => $pRecetaC->mes_y * 2.834],
            'anio'        => ['x' => $pRecetaC->anio_x * 2.834, 'y' => $pRecetaC->anio_y * 2.834],
            'paciente'    => ['x' => $pRecetaC->paciente_x * 2.834, 'y' => $pRecetaC->paciente_y * 2.834],
            'tratamiento' => ['x' => $pRecetaC->tratamiento_x * 2.834, 'y' => $pRecetaC->tratamiento_y * 2.834],
        ];

        // Enviar 'firmaBase64' a la vista

        $pdf = Pdf::loadView('admisiones.receta', compact('pEmpresa', 'dia', 'nombre_mes', 'anio', 'posiciones', 'pConsulta', 'rutaLogo', 'medico', 'firmaBase64'));
        $pdf->setPaper([0, 0, 612, 396], 'landscape');

        return $pdf->stream('receta_' . $pConsulta->paciente_nombre . '.pdf');
    }

    // Función auxiliar para no ensuciar el controlador
    private function getNombreMes($mes) {
        $meses = ['01'=>'enero','02'=>'febrero','03'=>'marzo','04'=>'abril','05'=>'mayo','06'=>'junio','07'=>'julio','08'=>'agosto','09'=>'septiembre','10'=>'octubre','11'=>'noviembre','12'=>'diciembre'];
        return $meses[$mes] ?? 'no definido';
    }

    function informe($atencion_id){
        // dd('entre');
        // $pEmpresa = Empresa::findOrFail(Auth::user()->empresa_id);
        $pEmpresa = DB::table('empresas as e')
                    ->join('municipios as m', 'e.municipio_id', 'm.id')
                    ->join('departamentos as d', 'e.departamento_id', 'd.id')
                    ->join('paises as p', 'e.pais_id', 'p.id')
                    ->where('e.id', Auth::user()->empresa_id)
                    ->select('nombre_comercial', DB::raw('CONCAT(e.direccion, " ", m.nombre, " ", d.nombre, " ", p.nombre) as direccion'), 'e.ruta_logo', DB::raw('CONCAT(e.codigo_postal, " ",e.telefonos, " | ", e.email) as telefonos'))
                    ->first();

        // 1. Limpiar Logo Empresa (si es JSON)
        $datosLogo = json_decode($pEmpresa->ruta_logo, true);
        $rutaLogo = is_array($datosLogo) ? ($datosLogo['logo'] ?? $pEmpresa->ruta_logo) : $pEmpresa->ruta_logo;

        $registro = DB::table('admision_atenciones as aa')
                    ->join('admisiones as a', 'aa.admision_id', 'a.id')
                    ->join('pacientes as p', 'a.paciente_id', 'p.id')
                    ->join('productos as prd', 'aa.pprocedimiento_id', 'prd.id')
                    ->leftjoin('productos as prem', 'aa.ppremedicacion', 'prem.id')
                    ->join('hospitales as h', 'a.hospital_id', 'h.id')
                    ->where('aa.id', $atencion_id)
                    ->select('a.id as admision_id', 'a.fecha','aa.pprocedimiento_id', 'aa.ptolerancia', 'aa.ppremedicacion', 'aa.ppatologo', 'aa.panestesiologo', 'aa.indicacion', 'aa.hallazgos', 'aa.diagnostico', 'aa.recomendaciones', 'p.codigo_id as paciente_codigo', 'a.edad as paciente_edad', 'prd.descripcion as procedimiento_descripcion', 'a.referido_por', 'h.nombre as hospital_nombre', 'prem.descripcion as premedicacion', DB::raw('CASE ptolerancia WHEN "B" THEN "Bueno" WHEN "R" THEN "Regular" WHEN "M" THEN "Malo" ELSE "No definido" END AS tolerancia_descripcion'),
                              DB::raw("CONCAT(CASE WHEN p.genero = 'M' THEN 'Sr. ' ELSE 'Sra. ' END, p.nombre_completo) as paciente_nombre"))
                    ->first();

        $fecha = \Carbon\Carbon::parse($registro->fecha);
        $dia = $fecha->format('d');
        $mes = $fecha->format('m');
        switch ($mes) {
            case '01': $nombre_mes = 'enero'; break;
            case '02': $nombre_mes = 'febrero'; break;
            case '03': $nombre_mes = 'marzo'; break;
            case '04': $nombre_mes = 'abril'; break;
            case '05': $nombre_mes = 'mayo'; break;
            case '06': $nombre_mes = 'junio'; break;
            case '07': $nombre_mes = 'julio'; break;
            case '08': $nombre_mes = 'agosto'; break;
            case '09': $nombre_mes = 'septiembre'; break;
            case '10': $nombre_mes = 'octubre'; break;
            case '11': $nombre_mes = 'noviembre'; break;
            case '12': $nombre_mes = 'diciembre'; break;
            default: $nombre_mes = 'no definido';  break;
        }
        $anio = $fecha->format('Y');

        $medico = DB::table('admision_atenciones as aa')
                  ->join('users as u', 'aa.created_by', 'u.username')
                  ->where('aa.id', $atencion_id)
                  ->select('u.medico_id')
                  ->first();

        $firma = Medico::findOrFail($medico->medico_id)->select(DB::raw('CONCAT(titulo, " ", nombre_completo) as nombre_profesional'),'firma')->first();

        $fotos = AdmisionAtencionImagen::where('admision_atencion_id', $atencion_id)->get();

        return view('admisiones.informe', compact('pEmpresa', 'dia', 'nombre_mes', 'anio', 'registro', 'fotos', 'firma', 'rutaLogo'));

        /*$pdf = Pdf::loadView('admisiones.informe', compact('pEmpresa', 'dia', 'nombre_mes', 'anio', 'registro', 'fotos', 'firma', 'rutaLogo'));
        $pdf->setPaper([0, 0, 612, 396], 'landscape');

        return $pdf->stream('informe_' . $registro->paciente_nombre . '.pdf');*/
        
    }

    public function reapertura(Request $request){
        $validData = $request->validate([
            'reapertura_admision_id' => 'required',
            'observacion_id'         => 'required',
            'observaciones'          => 'required'
        ]);
        $admisionId     = Crypt::decrypt($validData['reapertura_admision_id']);
        $registro = Admision::findOrFail($admisionId);
        $registro->estado = 'P';
        $registro->save();

        $observaciones = new AdmisionObservacion();
        $observaciones->admision_id = $registro->id;
        $observaciones->proceso = 'REAPERTURA';
        $observaciones->descripcion = $validData['observaciones'];
        $observaciones->estado = 'A';
        $observaciones->save();

        $bitacora = new AdmisionBitacora();
        $bitacora->admision_id = $registro->id;
        $bitacora->proceso     = 'REAPERTURA';
        $bitacora->observaciones = 'Re apertura de admision';
        $bitacora->save();

        return response()->json([
                                'message' => 'Se re aperturo la admisión con exito !!!',
                                'type'    => 'success'
                            ]);

    }

    public function getListAdmisions(){
        $admision_no = strtoupper($_POST['admision_no']);
        $nombre      = strtoupper($_POST['nombre']);

        /*$registros = DB::table('admisiones as a')
                     ->join('pacientes as p', 'p.id', 'a.paciente_id')
                     // ->join('tipo_atenciones as ta', 'a.tipo_admision', 'ta.id')
                     ->join('medicos as m', 'a.medico_id', 'm.id')
                     ->join('hospitales as h', 'a.hospital_id', 'h.id')
                     ->select('a.id', 'a.admision_no', DB::raw('DATE_FORMAT(a.fecha, "%d/%m/%Y") as fecha'), 'p.nombre_completo as paciente_nombre', 'a.edad', 'm.nombre_completo as medico_nombre', 'h.nombre as hospital_nombre', 
                        DB::raw('CASE WHEN a.estado = "0" THEN "Proceso" ELSE "Cerrada" END as estado'));*/
        $registros = Admision::with([
                                    'paciente:id,expediente_no,nombre_completo', 
                                    'medico:id,nombre_completo',
                                    'hospital:id,nombre'
                                ])
                                ->where('empresa_id', auth()->user()->empresa_id)
                                ->withSum('detalles as precio_total', 'precio_total')
                                ->orderBy('fecha', 'desc');

        
        if ($admision_no != null) {
            $registros->where('admision_no', $admision_no);
        }

        /*if ($nombre != null) {
            $registros->orWhere('nombre_completo', 'like', '%' . str_replace(' ', '%', $nombre) . '%');
        }*/
        if (!empty($nombre)) {
            $registros->whereHas('paciente', function($q) use ($nombre) {
                $nombreBusqueda = '%' . str_replace(' ', '%', strtoupper($nombre)) . '%';
                $q->where('nombre_completo', 'like', $nombreBusqueda);
            });
        }
        $registros = $registros->get()
                     ->map(function($item) {
                        // Creamos un campo nuevo llamado 'fecha_formateada'
                        $item->fecha_formateada = \Carbon\Carbon::parse($item->fecha)->format('d/m/Y');
                        return $item;
                    });;

        return Response::json($registros);  
    }

    public function encabezadoRevisado(){
        $admision_id = Crypt::decrypt($_POST['admision_id']);
        $registro = Admision::findOrFail($admision_id);
        $registro->encabezado_revisado = 1;
        $registro->save();

        return response()->json([
                                'message' => 'Registro actualizado con exito !!!',
                                'type'    => 'success'
                            ]);
    }

    public function get_estado(){
        $admision_id = $_POST['admision_id'];
        $registro = Admision::where('id', $admision_id)
                    ->select('estado')
                    ->first();

        return Response::json($registro);
    }

    public function storeVitales(Request $request){
        // dd($request);
        $validData = $request->validate([
                        'vitales_admision_id'  => 'required',
                        'vitales_atencion_id'  => 'required',
                        'peso'                 => 'required',
                        'talla'                => 'required',
                        'imc'                  => 'required',
                        'pulso'                => 'required',
                        'temperatura'          => 'required',
                        'respiraciones'        => 'required',
                        'presion_sistolica'    => 'required',
                        'presion_diastolica'   => 'required'
                    ]);

        // dd( $validData['id_admision_atencion']);
        if ($validData['vitales_atencion_id'] == 0) {
            $registro = new AdmisionVital();
            $registro->admision_id         = $validData['vitales_admision_id'];
        }else{
            $registro = AdmisionVital::findOrFail($validData['vitales_atencion_id']);
        }
        
        $registro->peso                = $validData['peso'];
        $registro->talla               = $validData['talla'];
        $registro->pulso               = $validData['pulso'];
        $registro->temperatura         = $validData['temperatura'];
        $registro->respiracion         = $validData['respiraciones'];
        $registro->presion_sistolica   = $validData['presion_sistolica'];
        $registro->presion_diastolica  = $validData['presion_diastolica'];
        $registro->bmi                 = $validData['imc'];
        $registro->estado              = 1;
        try {
            $registro->save();
            $message = array(
                'message' => 'Signos Vitales Actualizados con Exito !!!!',
                'type'    => 'success'
            );
        }catch (Throwable $e) {
            $message = array(
                'message' => $e,
                'type'    => 'error'
            );
            // dd('error');
            // print_r($e);
        }


        return redirect()->back()->with($message);
    }

    function getVitales(){
        $admision_id = $_POST['admision_id'];

        $registros = DB::table('admision_vitales as av')
                     ->where('admision_id', $admision_id)
                     ->where('av.estado', 1)
                     ->orderBy('av.created_at', 'DESC')
                     ->select('av.id', 'av.peso', 'av.talla', 'av.pulso', 'av.temperatura', 'av.respiracion', 
                              DB::raw('CONCAT(av.presion_sistolica, " / ", av.presion_diastolica) as presion'), 
                              'av.bmi', 'av.estado', 'av.created_by as username',
                              DB::raw('DATE_FORMAT(av.created_at, "%d/%m/%Y %H:%i") as created_at'))
                     ->get();

        return $registros;
    }

    function getHospitalizaciones(){
        $admision_id = $_POST['admision_id'];

        $registros = DB::table('admision_atenciones as aa')
                     ->join('tipo_atenciones as ta', 'aa.tipo_atencion_id', 'ta.id')
                     ->where('aa.admision_id', $admision_id)
                     ->where('ta.abreviatura', 'H')
                     ->orderBy('aa.created_at', 'DESC')
                     ->select('aa.id', 'hresumen', 'aa.created_by as username',
                              DB::raw('DATE_FORMAT(aa.created_at, "%d/%m/%Y") as created_at'),
                              DB::raw('DATE_FORMAT(aa.hfecha_inicio, "%d/%m/%Y") as hfecha_inicio'),
                              DB::raw('DATE_FORMAT(aa.hfecha_fin, "%d/%m/%Y") as hfecha_fin')
                             )
                     ->get();

        return $registros;
    }

    function getConsultas(){
        $admision_id = $_POST['admision_id'];

        $registros = DB::table('admision_atenciones as aa')
                     ->join('tipo_atenciones as ta', 'aa.tipo_atencion_id', 'ta.id')
                     ->where('aa.admision_id', $admision_id)
                     ->where('ta.abreviatura', 'C')
                     ->orderBy('aa.created_at', 'DESC')
                     ->select('aa.id', 'aa.cimpresion_clinica', 'aa.created_by as username',
                              DB::raw('DATE_FORMAT(aa.created_at, "%d/%m/%Y %H:%i") as created_at')
                             )
                     ->get();

        return $registros;
    }

    function getProcedimientos(){
        $admision_id = $_POST['admision_id'];

        $registros = DB::table('admision_atenciones as aa')
                     ->join('tipo_atenciones as ta', 'aa.tipo_atencion_id', 'ta.id')
                     ->join('productos as p', 'aa.pprocedimiento_id', 'p.id')
                     ->where('aa.admision_id', $admision_id)
                     ->where('ta.abreviatura', 'P')
                     ->orderBy('aa.created_at', 'DESC')
                     ->select('aa.id', 'p.descripcion', 'aa.created_by as username',
                              DB::raw('DATE_FORMAT(aa.created_at, "%d/%m/%Y %H:%i") as created_at')
                             )
                     ->get();

        return $registros;
    }

    function getAtencion(){
        $id = $_POST['atencion_id'];
        $registro = AdmisionAtencion::findOrFail($id);
        return $registro;
    }

    function getAtencionImagen(Request $request, $id){
        // $id = $_POST['atencion_id'];
        // dd('entre con '.$id);
        $registro = AdmisionAtencionImagen::where('admision_atencion_id', $id)->get();

        $data = $registro->map(function($img) {
            return [
                'id' => $img->id,
                'nombre' => $img->nombre_original,
                'url' => asset('storage/' . ltrim($img->ruta, '/')),
                'visible' => $img->visible_informe
            ];
        });

        return response()->json($data);
    }

    function getAtencionVitales(){
        $id = $_POST['atencion_id'];
        $registro = AdmisionVital::findOrFail($id);
        return $registro;
    }
}
