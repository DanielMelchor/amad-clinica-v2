<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\AdmisionController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AseguradoraController;
use App\Http\Controllers\BancoController;
use App\Http\Controllers\BodegaController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\InvClasificacionController;
use App\Http\Controllers\InvFamiliaController;
use App\Http\Controllers\CorrelativoController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\DosisController;
use App\Http\Controllers\empresaController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\FormaPagoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\GraficaController;
use App\Http\Controllers\InvMovimientoController;
use App\Http\Controllers\InventarioTrnController;
use App\Http\Controllers\LineaMedicaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\MotivoAnulacionController;
use App\Http\Controllers\MotivoRechazoController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\CuerpoParteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\permisosController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\UnidadMedidaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/clear-cache', function() {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        return "¡Caché limpia!";
    });

    Route::get('/consultas', function () {
        return view('consultas.index');
    })->middleware(['auth', 'verified'])->name('consultas');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::middleware(['auth'])->group(function () {
        // Admisiones
        Route::group([
            'prefix' => 'admisiones',
            'middleware' => ['permission:administrar-procesos-admisiones']
        ], function () {
            Route::get('listado', [AdmisionController::class, 'index'])->name('admisiones');
            Route::post('lista_admisiones', [AdmisionController::class, 'getListAdmisions'])->name('listado_admisiones');
            Route::post('grabar', [AdmisionController::class, 'store'])->name('grabar_admision');
            Route::get('editar/{admision_id}', [AdmisionController::class, 'edit'])->name('editar_admision');
            Route::post('admision_actualizar/{admision_id}', [AdmisionController::class, 'update'])->name('admision_actualizar');
            Route::post('actualizar_admision', [AdmisionController::class, 'update_ajax'])->name('actualizar_admision_ajax');
            Route::post('cerrar_admision', [AdmisionController::class, 'cerrar_admision_ajax'])->name('cerrar_admision');
            Route::get('generar_receta/{admision_id}', [AdmisionController::class, 'receta'])->name('generar_receta');
            Route::get('generar_informe/{admision_id}', [AdmisionController::class, 'informe'])->name('generar_informe');
            Route::post('abrir/{admision_id}', [AdmisionController::class, 'reapertura'])->name('reapertura_admision');
            Route::post('paciente_x_admision', [AdmisionController::class, 'trae_paciente_x_admision'])->name('paciente_x_admision');
            Route::post('genrales_admision', [AdmisionController::class, 'trae_generales'])->name('trae_generales_admision');
            Route::post('trae_consulta', [AdmisionController::class, 'trae_consulta'])->name('trae_consulta');
            Route::post('trae_egreso', [AdmisionController::class, 'trae_egreso'])->name('trae_egreso');
            Route::post('trae_procedimiento', [AdmisionController::class, 'trae_procedimiento'])->name('trae_procedimiento');
            Route::post('trae_imagenes_procedimiento', [AdmisionController::class, 'trae_imagenes_procedimiento'])->name('trae_imagenes_procedimiento');
            Route::post('Admision_SubirImagen', [AdmisionController::class, 'SubirImagen'])->name('Admision_SubirImagen');
            Route::post('Admision_subirDocumento', [AdmisionController::class, 'cargarDocumento'])->name('Admision_SubirDocumento');
            Route::post('trae_cargos', [AdmisionController::class, 'trae_cargos'])->name('trae_cargos');
            Route::post('ultimaconsulta_ajax', [AdmisionController::class, 'ultimaconsulta_ajAx'])->name('ultimaconsulta_ajax');
            Route::post('ultimoegreso_ajax', [AdmisionController::class, 'ultimoegreso_Ajax'])->name('ultimoegreso_ajax');
            Route::post('ultimoprocedimiento_ajax', [AdmisionController::class, 'ultimoprocedimiento_ajax'])->name('ultimoprocedimiento_ajax');
            Route::post('actconsulta_ajax', [AdmisionController::class, 'update_consulta_ajax'])->name('actconsulta_ajax');
            Route::post('actprocedimiento_ajax', [AdmisionController::class, 'update_procedimiento_ajax'])->name('actprocedimiento_ajax');
            Route::post('acthospitalizacion_ajax', [AdmisionController::class, 'update_hospitalizacion_ajax'])->name('acthospitalizacion_ajax');
            Route::post('imagen_informe', [AdmisionController::class, 'imagen_informe'])->name('imagen_informe');
            Route::post('graph_admisiones_01', [AdmisionController::class, 'graph_admisiones_01'])->name('graph_admisiones_01');
            Route::post('graph_admisiones_02', [AdmisionController::class, 'graph_admisiones_02'])->name('graph_admisiones_02');
            Route::post('graph_admisiones_03', [AdmisionController::class, 'graph_admisiones_03'])->name('graph_admisiones_03');
            Route::post('total_admisiones', [AdmisionController::class, 'get_total_admisiones'])->name('total_admisiones');
            Route::post('get_total_admisiones_activas', [AdmisionController::class, 'get_total_admisiones_activas'])->name('get_total_admisiones_activas');
            Route::post('get_total_admisiones_con_saldo', [AdmisionController::class, 'get_total_admisiones_con_saldo'])->name('get_total_admisiones_con_saldo');
            Route::post('get_id_x_admision', [AdmisionController::class, 'get_id_x_admision'])->name('get_id_x_admision');
            Route::post('datos_facturacion_x_admision', [AdmisionController::class, 'trae_datos_facturacion_x_admision'])->name('datos_facturacion_x_admision');
            Route::post('total_admisiones_v2', [AdmisionController::class, 'get_total_admisiones_v2'])->name('total_admisiones_v2');
            Route::post('get_total_admisiones_activas_v2', [AdmisionController::class, 'get_total_admisiones_activas_v2'])->name('get_total_admisiones_activas_v2');
            Route::post('marcar_revisado', [AdmisionController::class, 'encabezadoRevisado'])->name('marcar_revisado');
            Route::post('documentos_x_admision', [AdmisionController::class, 'getDoctosxAdmin'])->name('documentos_x_admision');
            Route::post('bitacora_x_admision', [AdmisionController::class, 'getBitacoraAdmin'])->name('bitacora_x_admision');
            Route::post('cargos_x_admision', [AdmisionController::class, 'getCargosAdmin'])->name('cargos_x_admision');
            Route::post('vitalesAdmision', [AdmisionController::class, 'storeVitales'])->name('vitalesAdmision');
            Route::post('trae_lista_admiciones', [AdmisionController::class, 'getAdmisiones'])->name('trae_lista_admiciones');
            Route::post('trae_lista_vitales', [AdmisionController::class, 'getVitales'])->name('trae_lista_vitales');
            Route::post('trae_lista_hospitalizaciones', [AdmisionController::class, 'getHospitalizaciones'])->name('trae_lista_hospitalizaciones');
            Route::post('trae_lista_consultas', [AdmisionController::class, 'getConsultas'])->name('trae_lista_consultas');
            Route::post('trae_lista_procedimientos', [AdmisionController::class, 'getProcedimientos'])->name('trae_lista_procedimientos');
            Route::post('trae_registro', [AdmisionController::class, 'getAtencion'])->name('trae_registro');
            Route::get('trae_proc_imagenes/{atencion_id}', [AdmisionController::class, 'getAtencionImagen'])->name('trae_proc_imagenes');
            Route::post('trae_registro_vitales', [AdmisionController::class, 'getAtencionVitales'])->name('trae_registro_vitales');
            Route::post('trae_cargos_a_facturar', [AdmisionController::class, 'trae_cargos_a_facturar'])->name('trae_cargos_a_facturar');
        });
        
        // Agenda
        Route::group([
            'prefix' => 'agenda',
            'middleware' => ['permission:ver-agenda']
        ], function () {
            Route::get('nueva_agenda', [AgendaController::class, 'nuevo_index'])->name('nueva_agenda');
            Route::post('grabar', [AgendaController::class, 'nuevo_store'])->name('nuevo_grabar_agenda');
            Route::get('edicion/{cita_id}', [AgendaController::class, 'nuevo_edit'])->name('nueva_edicion');
            Route::post('actualizar', [AgendaController::class, 'update_nuevo'])->name('actualizar_nueva_agenda');
            Route::post('crea_admision', [AgendaController::class, 'store_admision_x_cita'])->name('crea_admision_x_cita');
            Route::post('cancelar', [AgendaController::class, 'marcar_cancelada_ajax'])->name('cancelar_cita');
            Route::post('realizar', [AgendaController::class, 'marcar_realizada_ajax'])->name('realizar_cita');
            Route::post('bloquear', [AgendaController::class, 'marcar_espacio_bloqueado'])->name('bloquear_espacio');
            Route::post('bloquear_agenda', [AgendaController::class, 'marcar_agenda_bloqueada'])->name('bloquear_agenda_x_dia');
            Route::post('citas', [AgendaController::class, 'trae_citas'])->name('trae_citas');
            Route::post('resumen_citas', [AgendaController::class, 'trae_resumen_citas'])->name('trae_resumen_citas');
            Route::post('fechas_disponibles', [AgendaController::class, 'trae_fechas_disponibles'])->name('trae_fechas_disponibles');
            Route::post('traslada_cita', [AgendaController::class, 'traslada_cita'])->name('traslada_cita');
            Route::post('trae_horarios', [AgendaController::class, 'getHorarios'])->name('trae_horarios');
            Route::post('datos_cita', [AgendaController::class, 'datos_cita'])->name('datos_cita');
            Route::post('paciente_citas', [AgendaController::class, 'paciente_citas'])->name('paciente_citas');
            Route::get('fullcalendar', [AgendaController::class, 'fullcalendar_index'])->name('fullcalendar');
            Route::post('confirmar_ingreso', [AgendaController::class, 'confirmar_ingreso'])->name('confirmar_ingreso');
        });

        // Aseguradoras
        Route::group([
            'prefix' => 'aseguradoras',
            'middleware' => ['permission:ver-catalogo-facturacion-aseguradoras']
        ], function () {
            Route::get('listado', [AseguradoraController::class, 'index'])->name('aseguradoras');
            Route::get('agregar', [AseguradoraController::class, 'create'])->name('crear_aseguradora');
            Route::post('grabar', [AseguradoraController::class, 'store'])->name('aseguradora_grabar');
            Route::post('editar', [AseguradoraController::class, 'edit'])->name('aseguradora_editar');
            Route::post('actualizar', [AseguradoraController::class, 'update'])->name('aseguradora_actualizar');
            Route::post('trae_datos_facturacion', [AseguradoraController::class, 'get_datos_facturacion'])->name('trae_datos_facturacion');
        });

        // Bancos
        Route::post('formas_de_pago',[BancoController::class, 'trae_formas_pago'])->name('formas_de_pago');
        Route::group(['prefix' => 'bancos',
                      'middleware' => ['permission:ver-catalogo-facturacion-bancos']
        ], function () {
            Route::get('listado',[BancoController::class, 'index'])->name('bancos');
            Route::get('agregar',[BancoController::class, 'create'])->name('crear_banco');
            Route::post('grabar',[BancoController::class, 'store'])->name('banco_grabar');
            Route::post('editar',[BancoController::class, 'edit'])->name('banco_editar');
            Route::post('actualizar',[BancoController::class, 'update'])->name('banco_actualizar');
        });

        // Bodegas
        Route::group(['prefix' => 'bodegas',
                      'middleware' => ['permission:ver-catalogo-inventarios-bodegas']
        ], function () {
            Route::get('listado',[BodegaController::class, 'index'])->name('bodegas');
            Route::get('agregar',[BodegaController::class, 'create'])->name('crear_bodega');
            Route::post('grabar',[BodegaController::class, 'store'])->name('bodega_grabar');
            Route::post('editar',[BodegaController::class, 'edit'])->name('bodega_editar');
            Route::post('actualizar',[BodegaController::class, 'update'])->name('bodega_actualizar');
        });

        // Cajas
        Route::post('resolucion_factura_x_caja',[CajaController::class, 'resolucion_factura_x_caja'])->name('resolucion_factura_x_caja');
        Route::group(['prefix' => 'cajas',
                      'middleware' => ['permission:ver-catalogo-facturacion-cajas']
        ], function () {
            Route::get('listado',[CajaController::class, 'index'])->name('cajas');
            Route::get('agregar',[CajaController::class, 'create'])->name('crear_caja');
            Route::post('grabar',[CajaController::class, 'store'])->name('grabar_caja');
            Route::get('editar/{Caja_id}',[CajaController::class, 'edit'])->name('editar_caja');
            Route::get('show/{Caja_id}',[CajaController::class, 'show'])->name('resolucion_caja');
            Route::post('actualizar',[CajaController::class, 'update'])->name('actualizar_caja');
            Route::post('resolucion_serie',[CajaController::class, 'resolucion_x_serie'])->name('trae_resolucion_x_serie');
            Route::post('resolucion_recibo_x_caja',[CajaController::class, 'resolucion_recibo_x_caja'])->name('resolucion_recibo_x_caja');
            Route::post('caja_resoluciones',[CajaController::class, 'caja_resoluciones'])->name('caja_resoluciones');
            Route::post('resolucion_utilizada',[CajaController::class, 'resolucion_registros_utilizados'])->name('resolucion_registros_utilizados');
            Route::post('cajas_por_empresa',[CajaController::class, 'cajas_x_empresa'])->name('cajas_por_empresa');
        });
        
        // Departamentos
        Route::group(['prefix' => 'departamentos',
                      'middleware' => ['permission:ver-departamento']
        ], function () {
            Route::get('listado',[DepartamentoController::class, 'index'])->name('departamentos');
            Route::post('grabar_departamento',[DepartamentoController::class, 'store'])->name('departamento_grabar');
            Route::post('editar_departamento',[DepartamentoController::class, 'edit'])->name('departamento_editar');
            Route::post('actualizar_departamento',[DepartamentoController::class, 'update'])->name('departamento_actualizar');
            Route::post('departamentos_x_pais', [DepartamentoController::class, 'departamentos_x_pais'])->name('departamentos_x_pais');
        });

        // Dosis
        Route::group(['prefix' => 'dosis',
                      'middleware' => ['permission:ver-catalogo-dosis']
        ], function () {
            Route::get('listado',[DosisController::class, 'index'])->name('dosis');
            Route::get('agregar',[DosisController::class, 'create'])->name('crear_dosis');
            Route::post('grabar',[DosisController::class, 'store'])->name('dosis_grabar');
            Route::post('editar',[DosisController::class, 'edit'])->name('dosis_editar');
            Route::post('actualizar',[DosisController::class, 'update'])->name('dosis_actualizar');
        });

        // Empresas
        Route::group(['prefix' => 'empresas',
                      'middleware' => ['permission:ver-empresas']
        ], function () {
            Route::get('listado',[empresaController::class, 'index'])->name('empresas');
            Route::get('agregar',[empresaController::class, 'create'])->name('crear_empresa');
            Route::post('grabar',[empresaController::class, 'store'])->name('grabar_empresa');
            Route::get('editar/{empresa_id}',[empresaController::class, 'edit'])->name('editar_empresa');
            Route::post('actualizar/{empresa_id}',[empresaController::class, 'update'])->name('actualizar_empresa');
            Route::get('borrar_logo/{empresa_id}',[empresaController::class, 'borrar_logo'])->name('borrar_logo');
        });

        // Especialidades
        Route::group(['prefix' => 'especialidades',
                      'middleware' => ['permission:ver-catalogo-especialidad']
        ], function () {
            Route::get('listado',[EspecialidadController::class, 'index'])->name('especialidades');
            Route::get('agregar',[EspecialidadController::class, 'create'])->name('crear_especialidad');
            Route::post('grabar',[EspecialidadController::class, 'store'])->name('especialidad_grabar');
            Route::post('editar',[EspecialidadController::class, 'edit'])->name('especialidad_editar');
            Route::post('actualizar',[EspecialidadController::class, 'update'])->name('especialidad_actualizar');
        });

        // Graficos
        Route::group(['prefix' => 'graficas',
                      'middleware' => ['permission:ver-graficos']
        ], function () {
            Route::post('grp_antiguedad',[VentaController::class, 'get_saldo_pendiente_graph'])->name('grp_antiguedad');
            Route::get('graficas_index/{fecha_inicial}/{fecha_final}', [GraficaController::class, 'index'])->name('graficas_index');
            Route::post('grp_data', [VentaController::class, 'trae_datos'])->name('grp_data');
            Route::get('admisiones_unificado/{fecha_inicial}/{fecha_final}/{tipo_admision}/{saldo}/{estado}', [ReporteController::class, 'adm_unificado_idx'])->name('rpt_admisiones_unificado');
        });


        // Inventario
        // Ajustes
        Route::group(['prefix' => 'invmov_ajuste',
                     'middleware' => ['permission:administrar-procesos-inventario-ajuste']   
        ], function () {
            Route::get('listadoAjustes',[InvMovimientoController::class, 'index_ajustes'])->name('lista_ajustes');
            Route::get('crearAjuste',[InvMovimientoController::class, 'create_ajuste'])->name('crear_ajuste');
            Route::get('edicionajuste/{ajuste_id}',[InvMovimientoController::class, 'edit_ajuste'])->name('editar_ajuste');
            Route::post('grabarAjuste', [InvMovimientoController::class, 'store_ajuste'])->name('grabar_ajuste');
            Route::post('actualizar_ajuste', [InvMovimientoController::class, 'update_ajuste'])->name('actualizar_ajuste');
            Route::post('trae_detalle_ajuste', [InvMovimientoController::class, 'trae_detalle_ajuste'])->name('trae_detalle_ajuste');
        });

        // Compras
        Route::group(['prefix' => 'invmov_compra',
                      'middleware' => ['permission:administrar-procesos-inventario-compra']
        ], function () { 
            Route::get('listadoCompras',[InvMovimientoController::class, 'index_compras'])->name('lista_compras');
            Route::get('crearCompra',[InvMovimientoController::class, 'create_compra'])->name('crear_compra');
            Route::get('edicionCompra/{compra_id}',[InvMovimientoController::class, 'edit_compra'])->name('editar_compra');
            Route::get('mostrarCompra/{compra_id}',[InvMovimientoController::class, 'show_compra'])->name('mostrar_compra');
            Route::get('elimianr', [InvMovimientoController::class, 'destroy'])->name('transaccion_eliminar');
            Route::post('grabar', [InvMovimientoController::class, 'store_compra'])->name('grabar_compra');
            Route::post('actualizar', [InvMovimientoController::class, 'update_compra'])->name('actualizar_compra');
            Route::post('trae_detalle_compra', [InvMovimientoController::class, 'trae_detalle_compra'])->name('trae_detalle_compra');        
        });
        // Clasificaciones
        Route::group(['prefix' => 'inv_clasificacion',
                      'middleware' => ['permission:ver-catalogo-inventarios-clasificaciones']
        ], function () {
            Route::get('listado',[InvClasificacionController::class, 'index'])->name('inv_clasificacion');
            Route::post('grabar',[InvClasificacionController::class, 'store'])->name('inv_clasificacion_grabar');
            Route::post('editar',[InvClasificacionController::class, 'edit'])->name('inv_clasificacion_editar');
            Route::post('actualizar',[InvClasificacionController::class, 'update'])->name('inv_clasificacion_actualizar');
            Route::post('extras',[InvClasificacionController::class, 'trae_extras'])->name('extras');
        });

        // Familias
        Route::group(['prefix' => 'inv_familias',
                      'middleware' => ['permission:ver-catalogo-inventarios-familias']
        ], function () {
            Route::get('listado',[InvFamiliaController::class, 'index'])->name('inv_familias');
            Route::post('grabar',[InvFamiliaController::class, 'store'])->name('inv_familia_grabar');
            Route::post('editar',[InvFamiliaController::class, 'edit'])->name('inv_familia_editar');
            Route::post('actualizar',[InvFamiliaController::class, 'update'])->name('inv_familia_actualizar');
        });

        // Transacciones
        Route::group(['prefix' => 'invtransacciones',
                      'middleware' => ['permission:ver-catalogo-inventarios-transacciones']
        ], function () {
            Route::get('listado',[InventarioTrnController::class, 'index'])->name('invtransacciones');
            Route::get('agregar',[InventarioTrnController::class, 'create'])->name('crear_invtransaccion');
            Route::post('grabar',[InventarioTrnController::class, 'store'])->name('grabar_invtransaccion');
            Route::post('editar',[InventarioTrnController::class, 'edit'])->name('editar_transaccion');
            Route::post('actualizar',[InventarioTrnController::class, 'update'])->name('actualizar_invtransaccion');
        });

        // Linea Medica
        Route::group(['prefix' => 'lineas_medicas',
                      'middleware' => ['permission:ver-catalogo-lineas']
        ], function () {
            Route::get('listado',[LineaMedicaController::class, 'index'])->name('lineas_medicas');
            Route::get('agregar',[LineaMedicaController::class, 'create'])->name('crear_lineamedica');
            Route::post('grabar',[LineaMedicaController::class, 'store'])->name('lineamedica_grabar');
            Route::post('editar',[LineaMedicaController::class, 'edit'])->name('lineamedica_editar');
            Route::post('actualizar',[LineaMedicaController::class, 'update'])->name('lineamedica_actualizar');
        });


        // Medicos
        Route::group([
            'prefix' => 'medicos',
            'middleware' => ['permission:administrar-pantalla-medicos']
        ], function () {
            Route::get('nueva_admision/{paciente_id}', [AdmisionController::class, 'nueva_admision'])->name('nueva_admision');

        });

        Route::get('index_medico', [MedicoController::class, 'index_medico'])->name('index_medico');
        Route::post('citas', [MedicoController::class, 'trae_citas_x_medico'])->name('trae_citas_x_medico');
        Route::post('inicioAtencion', [MedicoController::class, 'setBegin'])->name('inicioAtencion');
        Route::post('finalAtencion', [MedicoController::class, 'setEnd'])->name('finalAtencion');

        Route::group([
            'prefix' => 'medicos',
            'middleware' => ['permission:ver-catalogo-medicos-medicos']
        ], function () {
            Route::get('listado', [MedicoController::class, 'index'])->name('medicos');
            Route::get('agregar', [MedicoController::class, 'create'])->name('crear_medico');
            Route::post('grabar', [MedicoController::class, 'store'])->name('grabar_medico');
            Route::get('editar/{medico_id}', [MedicoController::class, 'edit'])->name('editar_medico');
            Route::post('actualizar/{medico_id}', [MedicoController::class, 'update'])->name('actualizar_medico');
            Route::get('borrar_foto_medico/{medico_id}', [MedicoController::class, 'borrar_firma'])->name('borrar_foto_medico');
            Route::post('existe_config_receta', [MedicoController::class, 'existe_config_receta_ajax'])->name('existe_config_receta');
            Route::post('grabar_config_receta', [MedicoController::class, 'store_config_receta_ajax'])->name('grabar_config_receta');
            Route::post('actualizar_config_receta', [MedicoController::class, 'update_config_receta_ajax'])->name('actualizar_config_receta');
        });

        // Motivos Anulacion
        Route::group(['prefix' => 'motivosAnulacion',
                      'middleware' => ['permission:ver-catalogo-facturacion-motivo-anulacion']
        ], function () {
            Route::get('listado',[MotivoAnulacionController::class, 'index'])->name('motivosAnulacion');
            Route::get('agregar',[MotivoAnulacionController::class, 'create'])->name('crear_motivoanulacion');
            Route::post('grabar',[MotivoAnulacionController::class, 'store'])->name('motivoanulacion_grabar');
            Route::post('editar',[MotivoAnulacionController::class, 'edit'])->name('motivoanulacion_editar');
            Route::post('actualizar',[MotivoAnulacionController::class, 'update'])->name('motivoanulacion_actualizar');
        });

        // Motivo Rechazos
        Route::group(['prefix' => 'motivoRechazos',
                      'middleware' => ['permission:ver-catalogo-facturacion-motivo-rechazo']
        ], function () {
            Route::get('listado',[MotivoRechazoController::class, 'index'])->name('motivoRechazos');
            Route::get('agregar',[MotivoRechazoController::class, 'create'])->name('crear_motivorechazo');
            Route::post('grabar',[MotivoRechazoController::class, 'store'])->name('motivorechazo_grabar');
            Route::post('editar',[MotivoRechazoController::class, 'edit'])->name('motivorechazo_editar');
            Route::post('actualizar',[MotivoRechazoController::class, 'update'])->name('motivorechazo_actualizar');
        });

        // Municipios
        Route::group(['prefix' => 'municipios',
                      'middleware' => ['permission:ver-municipio']
        ], function () {
            Route::get('listado',[MunicipioController::class, 'index'])->name('municipios');
            Route::post('grabar_municipio',[MunicipioController::class, 'store'])->name('municipio_grabar');
            Route::post('editar_municipio',[MunicipioController::class, 'edit'])->name('municipio_editar');
            Route::post('actualizar_municipio',[MunicipioController::class, 'update'])->name('municipio_actualizar');
            Route::post('municipios_x_departamento', [MunicipioController::class, 'municipios_x_departamento'])->name('municipios_x_departamento');
        });

        // Pacientes
        Route::group([
            'prefix' => 'pacientes',
            'middleware' => ['permission:ver-pacientes']
        ], function () {
            Route::get('listado', [PacienteController::class, 'index'])->name('pacientes');
            Route::get('agregar', [PacienteController::class, 'create'])->name('crear_paciente');
            Route::post('grabar', [PacienteController::class, 'store'])->name('grabar_paciente');
            Route::get('editar/{paciente_id}', [PacienteController::class, 'edit'])->name('editar_paciente');
            Route::post('actualizar', [PacienteController::class, 'update'])->name('actualizar_paciente');
            Route::get('paciente_admision/{paciente_id}', [PacienteController::class, 'show'])->name('paciente_admision');
            Route::get('consultas/{paciente_id}', [PacienteController::class, 'consultas'])->name('paciente_consultas');
            Route::post('datos_facturacion', [PacienteController::class, 'trae_datos_facturacion'])->name('datos_facturacion');
            Route::get('atencion/{admision_id}', [PacienteController::class, 'atencionMedica'])->name('atencion_medica');
            Route::post('trae_telefonos_x_paciente', [PacienteController::class,'get_telefono_x_paciente'])->name('trae_telefonos_x_paciente');
            Route::post('lista_pacientes', [PacienteController::class, 'get_patient_list'])->name('lista_pacientes');
            Route::post('verificar_expediente',[PacienteController::class, 'verifica_expediente'])->name('verificar_expediente');
        });

        // Pagos
        Route::group(['prefix' => 'pagos'], function () {
            Route::post('recibos_con_saldo', [PagoController::class, 'trae_recibos_con_saldo'])->name('recibos_con_saldo');
            Route::post('saldo_recibo', [PagoController::class, 'trae_saldo_recibo'])->name('saldo_recibo');
            Route::post('forma_pago_recibo', [PagoController::class, 'trae_detalle_pago_x_recibo'])->name('forma_pago_recibo');
            Route::post('trae_detalle_recibo', [PagoController::class, 'trae_detalle_recibo'])->name('trae_detalle_recibo');
            Route::post('trae_pago_recibo', [PagoController::class, 'trae_pago_recibo'])->name('trae_pago_recibo');
            Route::post('recibo_x_cheque', [PagoController::class, 'trae_recibo_x_cheque'])->name('trae_recibo_x_cheque');
            Route::post('generales_x_recibo_id', [PagoController::class, 'trae_generales_x_recibo_id'])->name('trae_generales_x_recibo_id');
            Route::post('trae_documentos_afectos', [PagoController::class, 'trae_documentos_afectos'])->name('trae_documentos_afectos');
        });

        // Pais
        Route::group(['prefix' => 'pais',
                      'middleware' => ['permission:ver-pais']
        ], function () {
            Route::get('listado',[PaisController::class, 'index'])->name('pais');
            Route::post('grabar_pais',[PaisController::class, 'store'])->name('pais_grabar');
            Route::get('editar_pais',[PaisController::class, 'edit'])->name('pais_editar');
            Route::post('actualizar_pais',[PaisController::class, 'update'])->name('pais_actualizar');
        });

        // PArtes del cuerpo
        Route::group(['prefix' => 'partes_cuerpo',
                      'middleware' => ['permission:ver-catalogo-partes']
        ], function () {
            Route::get('listado',[CuerpoParteController::class, 'index'])->name('partes_cuerpo');
            Route::post('grabar',[CuerpoParteController::class, 'store'])->name('parte_grabar');
            Route::post('editar',[CuerpoParteController::class, 'edit'])->name('parte_editar');
            Route::post('actualizar',[CuerpoParteController::class, 'update'])->name('parte_actualizar');
            Route::post('trae_partes',[CuerpoParteController::class, 'get_partes'])->name('trae_partes');
        });

        // Productos
        Route::post('descripcion',[ProductoController::class, 'descripcion'])->name('descripcion');
        Route::post('medidas_x_producto',[ProductoController::class, 'trae_medidas_x_producto'])->name('trae_medidas_x_producto');
        Route::group(['prefix' => 'productos',
                      'middleware' => ['permission:ver-catalogo-inventarios-productos']
        ], function () {
            Route::get('listado',[ProductoController::class, 'index'])->name('productos');
            Route::get('agregar',[ProductoController::class, 'create'])->name('crear_producto');
            Route::post('grabar',[ProductoController::class, 'store'])->name('grabar_producto');
            Route::get('editar/{producto_id}',[ProductoController::class, 'edit'])->name('editar_producto');
            Route::post('actualizar',[ProductoController::class, 'update'])->name('actualizar_producto');
            Route::post('proveedores_x_producto',[ProductoController::class, 'trae_proveedores_x_producto'])->name('trae_proveedores_x_producto');
            Route::post('dosis_x_producto',[ProductoController::class, 'trae_dosis_x_producto'])->name('trae_dosis_x_producto');
            Route::post('caracteristicas_x_producto',[ProductoController::class, 'trae_caracteristicas_x_producto'])->name('trae_caracteristicas_x_producto');
            Route::post('trae_producto',[ProductoController::class, 'trae_productos'])->name('trae_productos');
            Route::post('trae_producto_inicial',[ProductoController::class, 'trae_productos_con_inicial'])->name('trae_productos_con_inicial');
            Route::post('trae_dosis',[ProductoController::class, 'trae_dosis_x_medicamento'])->name('trae_dosis');
            Route::post('receta_descripcion',[ProductoController::class, 'receta'])->name('receta_descripcion');
        });

        // Proveedores
        Route::group(['prefix' => 'proveedores',
                      'middleware' => ['permission:ver-catalogo-inventarios-proveedores']
        ], function () {
            Route::get('listado',[ProveedorController::class, 'index'])->name('proveedores');
            Route::get('agregar',[ProveedorController::class, 'create'])->name('crear_proveedor');
            Route::post('grabar',[ProveedorController::class, 'store'])->name('grabar_proveedor');
            Route::get('editar/{proveedor_id}',[ProveedorController::class, 'edit'])->name('editar_proveedor');
            Route::post('trae_contactos',[ProveedorController::class, 'trae_contactos'])->name('trae_contactos');
            Route::post('trae_generales',[ProveedorController::class, 'trae_generales'])->name('trae_generales');
            Route::post('actualizar',[ProveedorController::class, 'update'])->name('actualizar_proveedor');
        });

        // Recibos
        Route::group(['prefix' => 'recibos',
                      'middleware' => ['permission:administrar-procesos-facturacion-recibo']
        ], function () {
            Route::get('listado',[PagoController::class, 'index'])->name('recibos_listado');
            Route::get('nuevo_recibo',[PagoController::class, 'create'])->name('nuevo_recibo');
            Route::post('grabar_recibo',[PagoController::class, 'recibo_store'])->name('recibo_grabar');
            Route::get('editar_recibo/{recibo_id}',[PagoController::class, 'edit'])->name('editar_recibo');
            Route::post('recibo_anulacion/{recibo_id}',[PagoController::class, 'recibo_anular'])->name('recibo_anular');
        });


        //=================================================================================
        // Disponibilidad de artículos
        //=================================================================================
        Route::group(['prefix' => 'reportesinv',
                  'middleware' => ['permission:ver-reporte-inventario-disponibles']
        ], function () {
            Route::get('disponibilidad_articulos', [ReporteController::class, 'disponibilidad_articulos_idx'])->name('rpt_disponible');
            Route::get('disponibilidad_articulos_pdf', [ReporteController::class, 'disponibilidad_articulos_pdf'])->name('rpt_disponible_pdf');
            Route::get('disponibilidad_articulos_xls', [ReporteController::class, 'disponibilidad_articulos_xls'])->name('rpt_disponible_xls');
        });

        //=================================================================================
        // Kardex
        //=================================================================================
        Route::group(['prefix' => 'reportesinv',
                  'middleware' => ['permission:ver-reporte-inventario-kardex']
        ], function () {
            Route::get('kardex_articulos/{producto_id}/{fecha_inicial}', [ReporteController::class, 'rpt_kardex_articulos'])->name('rpt_kardex_articulos');
            Route::get('kardex_articulos_pdf/{producto_id}/{fecha_inicial}', [ReporteController::class, 'rpt_kardex_articulos_pdf'])->name('rpt_kardex_articulos_pdf');
        });
        //=================================================================================
        // Movimientos
        //=================================================================================
        Route::group(['prefix' => 'reportesinv',
                  'middleware' => ['permission:ver-reporte-inventario-movimientos']
        ], function () {
            Route::get('movimiento_articulos/{fecha_inicial}/{fecha_final}', [ReporteController::class, 'rpt_movimiento_articulos'])->name('rpt_movimiento_articulos');
            Route::get('movimiento_articulos_pdf/{fecha_inicial}/{fecha_final}', [ReporteController::class, 'rpt_movimiento_articulos_pdf'])->name('rpt_movimiento_articulos_pdf');
        });

        // Salas
        Route::group(['prefix' => 'salas',
                      'middleware' => ['permission:ver-salas']
        ], function(){
            Route::get('listado', [SalaController::class, 'index'])->name('salas');
            Route::get('agregar', [SalaController::class, 'create'])->name('crear_sala');
            Route::post('grabar', [SalaController::class, 'store'])->name('sala_grabar');
            Route::post('editar', [SalaController::class, 'edit'])->name('sala_editar');
            Route::post('actualizar', [SalaController::class, 'update'])->name('sala_actualizar');
            Route::post('trae_salas', [SalaController::class, 'get_salas'])->name('trae_salas');
            Route::post('salas', [SalaController::class, 'salas_x_empresa'])->name('salas_x_empresa');
        });

        // Tipo de Documentos
        Route::group(['prefix' => 'tipodocumentos',
                      'middleware' => ['permission:ver-catalogo-facturacion-tipo_documentos']
        ], function () {
            Route::get('listado',[TipoDocumentoController::class, 'index'])->name('tipodocumentos');
            Route::get('agregar',[TipoDocumentoController::class, 'create'])->name('crear_tipodocumento');
            Route::post('grabar',[TipoDocumentoController::class, 'store'])->name('tipodocumento_grabar');
            Route::post('editar',[TipoDocumentoController::class, 'edit'])->name('tipodocumento_editar');
            Route::post('actualizar',[TipoDocumentoController::class, 'update'])->name('tipodocumento_actualizar');
            Route::post('trae_documentos', [TipoDocumentoController::class, 'get_documentos'])->name('trae_documentos');
        });

        // Unidad de Medida
        Route::group(['prefix' => 'unidadmedidas',
                      'middleware' => ['permission:ver-catalogo-inventarios-unidades']
        ], function () {
            Route::get('listado',[UnidadMedidaController::class, 'index'])->name('unidadmedidas');
            Route::get('agregar',[UnidadMedidaController::class, 'create'])->name('crear_unidadmedida');
            Route::post('grabar',[UnidadMedidaController::class, 'store'])->name('unidadmedida_grabar');
            Route::post('editar',[UnidadMedidaController::class, 'edit'])->name('unidadmedida_editar');
            Route::post('actualizar',[UnidadMedidaController::class, 'update'])->name('unidadmedida_actualizar');
            // Route::post('documento_renumera','VentaController@documento_renumerar')->name('documento_renumerar');
        });

        // Ventas / Facturación
        Route::post('trae_resumen_documentos',[VentaController::class, 'trae_resumen_documentos'])->name('trae_resumen_documentos');
        Route::group([
            'prefix' => 'ventas',
            'middleware' => ['permission:administrar-procesos-facturacion-facturacion']
        ], function () {
            Route::get('listado', [VentaController::class, 'index'])->name('documentos_listado');
            Route::get('nueva_factura/{admision_no}', [VentaController::class, 'factura_create'])->name('nueva_factura');
            Route::get('editar_factura/{admision_id}', [VentaController::class, 'factura_edit'])->name('editar_factura');
            Route::post('factura_grabar', [VentaController::class, 'factura_store'])->name('factura_grabar');
            Route::post('venta_anulacion', [VentaController::class, 'documento_anular'])->name('documento_anular');
            Route::post('admision_estado', [AdmisionController::class, 'get_estado'])->name('admision_estado');
            Route::post('campos_requeridos',[FormaPagoController::class, 'campos_requeridos'])->name('campos_requeridos');
        });

        // Notas de Credito
        Route::group(['prefix' => 'ventas',
                  'middleware' => ['permission:administrar-procesos-facturacion-nota-credito']
        ], function () {
            Route::get('listado_notas_credito',[VentaController::class, 'index_nc'])->name('nc_listado');
            Route::get('nueva_nota_credito',[VentaController::class, 'nc_create'])->name('nueva_nc');
            Route::get('nota_credito_editar/{nc_id}',[VentaController::class, 'nc_edit'])->name('editar_nc');
            Route::post('nota_credito_grabar',[VentaController::class, 'nc_store'])->name('grabar_nc');
            Route::post('documentos_saldo',[VentaController::class, 'documentos_con_saldo'])->name('documentos_con_saldo');
        });

        // Notas de Debito
        Route::group(['prefix' => 'ventas',
                      'middleware' => ['permission:administrar-procesos-facturacion-nota-debito']
        ], function () {
            Route::get('listado_notas_debito',[VentaController::class, 'index_nd'])->name('nd_listado');
            Route::get('nueva_nota_debito',[VentaController::class, 'nd_create'])->name('nueva_nd');
            Route::get('nota_debito_editar/{nd_id}',[VentaController::class, 'nd_edit'])->name('editar_nd');
            Route::post('nd_grabar',[VentaController::class, 'nd_store'])->name('grabar_nd');
        });

        // Corte de Caja
        Route::group(['prefix' => 'ventas',
                      'middleware' => ['permission:administrar-procesos-facturacion-corte']
        ], function () {
            Route::get('listado_cortes',[VentaController::class, 'corte_idx'])->name('listado_cortes');
            Route::get('nuevo_corte',[VentaController::class, 'corte_create'])->name('nuevo_corte');
            Route::get('editar_corte/{corte_id}',[VentaController::class, 'corte_edit'])->name('editar_corte');
            Route::post('grabar_corte',[VentaController::class, 'corte_store'])->name('grabar_corte');
        });



        // Roles y Usuarios (Solo Super Admin)
        Route::group([
            'prefix' => 'roles',
            'middleware' => ['role:Super Admin']
        ], function () {
            Route::get('listado', [permisosController::class, 'role_index'])->name('roles_listado');
            Route::post('grabar', [permisosController::class, 'role_store'])->name('role_grabar');
            Route::get('editar_role/{permiso_id}', [permisosController::class, 'role_edit'])->name('role_editar');
            Route::post('actualizar_role/{role_id}', [permisosController::class, 'role_update'])->name('role_actualizar');

            Route::get('listado_permisos', [permisosController::class, 'permiso_index'])->name('permiso_listado');
            Route::post('grabar_permiso', [permisosController::class, 'permiso_store'])->name('permiso_grabar');
            Route::post('trae_permiso', [permisosController::class, 'trae_permiso'])->name('trae_permiso');
            Route::post('actualizar_permiso', [permisosController::class, 'permiso_update'])->name('permiso_actualizar');

            Route::get('listado_usuarios', [permisosController::class, 'usuario_index'])->name('usuario_listado');
            Route::get('crear_usuario', [permisosController::class, 'usuario_create'])->name('usuario_agregar');
            Route::post('grabar_usuario', [permisosController::class, 'usuario_store'])->name('usuario_grabar');
            Route::get('editar_usuario/{user_id}', [permisosController::class, 'usuario_edit'])->name('usuario_editar');
            Route::post('actualizar_usuario', [permisosController::class, 'usuario_update'])->name('usuario_actualizar');
        });

        Route::get('/home', function() {
            return view('home');
        })->name('home');
    });

    Auth::routes();
    require __DIR__.'/auth.php';