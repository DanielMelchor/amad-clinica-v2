<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\carbon;
use App\Models\Admision;
use App\Models\Aseguradora;
use App\Models\Correlativo;
use App\Models\Especialidad;
use App\Models\Hospital;
use App\Models\Medicamento;
use App\Models\medicamento_dosis;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\PacienteEmail;
use App\Models\PacienteFacturacion;
use App\Models\PacienteFamilia;
use App\Models\PacienteSeguro;
use App\Models\PacienteTelefono;
use App\Models\PacienteUbicacion;
use App\Models\Parentesco;
use App\Models\Producto;
use App\Models\TipoComunicacion;
use App\Models\TipoUbicacion;


class PacienteController extends Controller
{
    
    public function index(Request $request)
    {
        //$pPacientes = Paciente::Nombre($request->busqueda)->get();
        $pPacientes = DB::table('pacientes as p')
                      ->where('p.empresa_id', Auth::user()->empresa_id)
                      ->select('p.id as id', 'p.codigo_id as codigo_id', 'p.expediente_No as expediente_no', 'p.nombre_completo as nombre_completo', 'p.fecha_nacimiento as fecha_nacimiento', 'p.estado')
                      ->get();

        $pMedicos   = Medico::all();
        $pHospitales = Hospital::all();
        $pAseguradoras = Aseguradora::all();
        return view('pacientes.index', [
            'pPacientes' => $pPacientes,
            'pMedicos' => $pMedicos,
            'pHospitales' => $pHospitales,
            'pAseguradoras' => $pAseguradoras
        ]);
    }

    public function create()
    {
        $aseguradoras = Aseguradora::all();
        $tipoTelefonos = TipoComunicacion::where('estado', 'A')->orderBy('nombre', 'ASC')->get();
        $tipoDirecciones = TipoUbicacion::where('estado', 'A')->orderBy('nombre', 'ASC')->get();
        $parentescos     = Parentesco::where('estado', 1)->orderBy('nombre', 'asc')->get();
        
        return view('pacientes.create', compact('aseguradoras', 'tipoTelefonos', 'tipoDirecciones', 'parentescos'));
    }

    public function store(Request $request)
    {
        // 1. Validación (se queda igual)
        $validData = $request->validate([
            'nombres'          => 'required|min:3',
            'apellidos'        => 'required|min:3',
            'fecha_nacimiento' => 'required|date',
            'expediente_no'    => 'required|numeric'
        ]);

        try{
            return DB::transaction(function () use ($request, $validData) {
                $empresa_id = Auth::user()->empresa_id;
                // 2. Correlativo con bloqueo
                $correlativo = Correlativo::where('empresa_id', $empresa_id)
                    ->where('tipo', 'P')
                    ->lockForUpdate()
                    ->first();

                if (!$correlativo) {
                    throw new \Exception("Configuración de correlativos no encontrada.");
                }

                $nuevoCodigo = $correlativo->correlativo + 1;
                $correlativo->update(['correlativo' => $nuevoCodigo]);

                $paciente = new Paciente();
                $paciente->empresa_id             = Auth::user()->empresa_id;
                $paciente->expediente_no          = $validData['expediente_no'];
                $paciente->expediente_anterior_no = $request->expediente_anterior_no;
                $paciente->codigo_id              = $nuevoCodigo;
                $paciente->nombres                = $validData['nombres'];
                $paciente->apellidos              = $validData['apellidos'];
                $paciente->apellido_casada        = $request->apellido_casada;
                if (isset($request->apellido_casada)) {
                    $paciente->nombre_completo  = $paciente->nombres .' '.$paciente->apellidos.' de '.$paciente->apellido_casada;
                }else{
                    $paciente->nombre_completo  = $paciente->nombres .' '.$paciente->apellidos;
                }
                if (isset($request->genero)){
                    $paciente->genero       = $request->genero;
                }
                $paciente->fecha_nacimiento = $validData['fecha_nacimiento'];
                $paciente->profesion        = $request->profesion;
                $paciente->estado_civil     = $request->estado_civil;
                $paciente->referido_por     = $request->referido_por;
                $paciente->religion         = $request->religion;
                $paciente->antmedico_descripcion      = $request->antmedico_descripcion;
                $paciente->antquirurgico_descripcion  = $request->antquirurgico_descripcion;
                $paciente->antalergia_descripcion     = $request->antalergia_descripcion;
                $paciente->antgineco_descripcion      = $request->antgineco_descripcion;
                $paciente->antfamiliar_descripcion    = $request->antfamiliar_descripcion;
                $paciente->antmedicamento_descripcion = $request->antmedicamento_descripcion;
                $paciente->tabaco_cnt       = $request->tabaco_cnt;
                $paciente->tabaco_tiempo    = $request->tabaco_tiempo;
                $paciente->alcohol_cnt      = $request->alcohol_cnt;
                $paciente->alcohol_tiempo   = $request->alcohol_tiempo;
                if (isset($request['estado'])) {
                    $paciente->estado = 1;
                }else{
                    $paciente->estado = 0;
                }
                $paciente->save();

                if ($request->has('telefonos')) {
                    foreach ($request->telefonos as $tel) {
                        // Solo guardamos si al menos el número de teléfono está presente
                        if (!empty($tel['numero'])) {
                            $nuevoTelefono = new PacienteTelefono();
                            $nuevoTelefono->paciente_id          = $paciente->id; // El ID del paciente recién guardado
                            $nuevoTelefono->tipo_comunicacion_id = $tel['tipocomunicacion_id'];
                            $nuevoTelefono->numero               = $tel['numero'];
                            $nuevoTelefono->extension            = $tel['extension'] ?? null;
                            $nuevoTelefono->estado               = 1; // O el estado que manejes
                            $nuevoTelefono->save();
                        }
                    }
                }

                if ($request->has('direcciones')) {
                    foreach ($request->direcciones as $dir) {
                        if (!empty($dir['tipodireccion_id'])) {
                            $nuevaDireccion = new PacienteUbicacion();
                            $nuevaDireccion->paciente_id       = $paciente->id; // El ID del paciente recién guardado
                            $nuevaDireccion->tipo_ubicacion_id = $dir['tipodireccion_id'];
                            $nuevaDireccion->direccion         = $dir['direccion'];
                            $nuevaDireccion->municipio_id      = 1;
                            $nuevaDireccion->departamento_id   = 1;
                            $nuevaDireccion->pais_id           = 1;
                            $nuevaDireccion->estado            = 1;
                            $nuevaDireccion->save();
                        }
                    }
                }

                if ($request->has('emails')) {
                    foreach ($request->emails as $email) {
                        if (!empty($email['email'])) {
                            $nuevoEmail = new PacienteEmail();
                            $nuevoEmail->paciente_id       = $paciente->id; // El ID del paciente recién guardado
                            $nuevoEmail->email = $email['email'];
                            $nuevoEmail->estado            = 1;
                            $nuevoEmail->save();
                        }
                    }
                }

                if ($request->has('seguros')) {
                    foreach ($request->seguros as $seguro) {
                        // Esta validación previene el error 'Undefined array key'
                        if (isset($seguro['aseguradora_id']) && !empty($seguro['aseguradora_id'])) {
                            $nuevoSeguro = new PacienteSeguro();
                            $nuevoSeguro->paciente_id    = $paciente->id; 
                            $nuevoSeguro->aseguradora_id = $seguro['aseguradora_id'];
                            
                            // Importante: 'poliza' es el nombre que viene del JS en create.blade.php
                            $nuevoSeguro->poliza_no      = $seguro['poliza'] ?? null; 
                            
                            $nuevoSeguro->estado         = 1;
                            $nuevoSeguro->save();
                        }
                    }
                }

                if ($request->has('facturacion')) {
                    foreach ($request->facturacion as $fac) {
                        if (!empty($fac['nit'])) {
                            $nuevoNit = new PacienteFacturacion();
                            $nuevoNit->paciente_id = $paciente->id; // El ID del paciente recién guardado
                            $nuevoNit->nit         = $fac['nit'];
                            $nuevoNit->nombre      = $fac['nombre'];
                            $nuevoNit->direccion   = $fac['direccion'];
                            $nuevoNit->estado      = 1;
                            $nuevoNit->save();
                        }
                    }
                }

                if ($request->has('familia')) {
                    foreach ($request->familia as $fam) {
                        if (!empty($fam['parentesco_id'])) {
                            $nuevoFamiliar = new PacienteFamilia();
                            $nuevoFamiliar->paciente_id   = $paciente->id; // El ID del paciente recién guardado
                            $nuevoFamiliar->parentesco_id = $fam['parentesco_id'];
                            $nuevoFamiliar->nombre        = $fam['nombre'];
                            $nuevoFamiliar->telefono      = $fam['telefono'];
                            if (isset($fam['emergencia'])) {
                                $nuevoFamiliar->emergencia    = 'S';
                            }else{
                                $nuevoFamiliar->emergencia    = 'N';
                            }
                            $nuevoFamiliar->estado        = 1;
                            $nuevoFamiliar->save();
                        }
                    }
                }

                $message = array(
                    'message' => 'Registro almacenado con exito !!!',
                    'type'    => 'success'
                );

                return Redirect::route('editar_paciente', [Crypt::encryptString((string)$paciente->id)])->with('message',$message);
            });
        } catch (\Exception $e){
            return redirect()->back()
                ->withInput() // Mantiene lo que el usuario escribió
                ->with([
                    'message' => 'Hubo un problema técnico: ' . $e->getMessage(),
                    'type'    => 'error'
            ]);
        }
    }

    private function generarNombreCompleto($request)
    {
        $nombreBase = trim($request->nombres) . ' ' . trim($request->apellidos);
        
        return $request->filled('apellido_casada') 
            ? $nombreBase . ' de ' . trim($request->apellido_casada) 
            : $nombreBase;
    }

    /**
     * Función auxiliar para limpiar el store
     */
    private function guardarRelaciones($paciente, $request)
    {
        if ($request->filled('telefonos')) {
            foreach ($request->telefonos as $tel) {
                $paciente->telefonos()->create([
                    'tipo_comunicacion_id' => $tel['tipocomunicacion_id'],
                    'numero'               => $tel['numero'],
                    'extension'            => $tel['extension'] ?? null,
                ]);
            }
        }

        // Guardar Direcciones si existen
        if ($request->filled('direcciones')) {
            foreach ($request->direcciones as $dir) {
                $paciente->ubicaciones()->create([
                    'empresa_id '          => $empresa_id,
                    'tipo_ubicacion_id' => $dir['tipodireccion_id'],
                    'direccion'         => $dir['direccion'],
                    'municipio_id'      => 1, // Valores por defecto
                    'departamento_id'   => 1,
                    'pais_id'           => 1,
                    'estado'            => 'A'
                ]);
            }
        }
        
        // ... puedes agregar direcciones, seguros, etc.
    }

    public function edit($id)
    {
        $pacienteId      = Crypt::decryptString($id);
        $registro        = Paciente::findOrFail($pacienteId);
        $aseguradoras    = Aseguradora::all();
        $tipoTelefonos   = TipoComunicacion::where('estado', 'A')->orderBy('nombre', 'ASC')->get();
        $tipoDirecciones = TipoUbicacion::where('estado', 'A')->orderBy('nombre', 'ASC')->get();
        $parentescos     = Parentesco::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $pacienteTelefonos     = PacienteTelefono::where('paciente_id', $pacienteId)->where('estado', 1)->get();
        $pacienteDirecciones   = PacienteUbicacion::where('paciente_id', $pacienteId)->where('estado', 1)->get();
        $pacienteEmails        = PacienteEmail::where('paciente_id', $pacienteId)->where('estado', 1)->get();
        $pacienteSeguros       = PacienteSeguro::where('paciente_id', $pacienteId)->where('estado', 1)->get();
        $pacienteFacturaciones = PacienteFacturacion::where('paciente_id', $pacienteId)->where('estado', 1)->get();
        $pacienteFamilias      = PacienteFamilia::where('paciente_id', $pacienteId)->where('estado', 1)->get();

        //$pAdmisiones   = vw_admision::where('paciente_id', $id)->orderByRaw('fecha_creacion DESC')->get();

        return view('pacientes.edit', compact('registro', 'aseguradoras', 'tipoTelefonos', 'tipoDirecciones', 'parentescos', 'pacienteTelefonos', 'pacienteDirecciones', 'pacienteEmails', 'pacienteSeguros', 'pacienteFacturaciones', 'pacienteFamilias', 'id'));

    }

    public function update(Request $request)
    {
        
        $validData = $request->validate([
            'paciente_id'      => 'required',
            'expediente_no'    => 'required|numeric',
            'codigo_id'        => 'required|numeric',
            'nombres'          => 'required|min:3',
            'apellidos'        => 'required|min:3',
            'fecha_nacimiento' => 'required|date'

        ]);

        $pacienteId = Crypt::decryptString($validData['paciente_id']);

        try{
            // ************************************************************ //
            // ************** Actualizar Paciente ************************* //
            // ************************************************************ //
            $paciente = Paciente::findOrFail($pacienteId);
            $paciente->expediente_no          = $validData['expediente_no'];
            $paciente->expediente_anterior_no = $request->expediente_anterior_no;
            $paciente->codigo_id              = $validData['codigo_id'];
            $paciente->nombres                = $validData['nombres'];
            $paciente->apellidos              = $validData['apellidos'];
            $paciente->apellido_casada        = $request->apellido_casada;
            if (isset($request->apellido_casada)) {
                $paciente->nombre_completo  = $paciente->nombres .' '.$paciente->apellidos.' de '.$paciente->apellido_casada;
            }else{
                $paciente->nombre_completo  = $paciente->nombres .' '.$paciente->apellidos;
            }
            if (isset($request->genero)){
                $paciente->genero       = $request->genero;
            }
            $paciente->fecha_nacimiento = $validData['fecha_nacimiento'];
            $paciente->profesion        = $request->profesion;
            $paciente->estado_civil     = $request->estado_civil;
            $paciente->referido_por     = $request->referido_por;
            $paciente->religion         = $request->religion;
            $paciente->antmedico_descripcion      = $request->antmedico_descripcion;
            $paciente->antquirurgico_descripcion  = $request->antquirurgico_descripcion;
            $paciente->antalergia_descripcion     = $request->antalergia_descripcion;
            $paciente->antgineco_descripcion      = $request->antgineco_descripcion;
            $paciente->antfamiliar_descripcion    = $request->antfamiliar_descripcion;
            $paciente->antmedicamento_descripcion = $request->antmedicamento_descripcion;
            $paciente->tabaco_cnt       = $request->tabaco_cnt;
            $paciente->tabaco_tiempo    = $request->tabaco_tiempo;
            $paciente->alcohol_cnt      = $request->alcohol_cnt;
            $paciente->alcohol_tiempo   = $request->alcohol_tiempo;
            if (isset($request['estado'])) {
                $paciente->estado = 1;
            }else{
                $paciente->estado = 0;
            }
            
            $paciente->save();

            //===========================================================================
            // Telefonos
            //===========================================================================
            if ($request->has('telefonos')) {
                // 2. "Eliminación lógica": Ponemos en estado 2 todos los teléfonos actuales del paciente
                PacienteTelefono::where('paciente_id', $paciente->id)->update(['estado' => 2]);

                foreach ($request->telefonos as $tel) {
                    if (!empty($tel['numero'])) {
                        // 3. updateOrCreate
                        // El primer array son las columnas para BUSCAR el registro
                        // El segundo array son las columnas para ACTUALIZAR o CREAR
                        PacienteTelefono::updateOrCreate(
                            [
                                'id' => $tel['id'] ?? 0, // Si no tiene ID (es nuevo), buscamos el ID 0
                                'paciente_id' => $paciente->id
                            ],
                            [
                                'tipo_comunicacion_id' => $tel['tipocomunicacion_id'],
                                'numero'               => $tel['numero'],
                                'extension'            => $tel['extension'] ?? null,
                                'estado'               => 1 // Lo reactivamos o lo creamos como activo
                            ]
                        );
                    }
                }
            }

            //===========================================================================
            // Direcciones
            //===========================================================================
            PacienteUbicacion::where('paciente_id', $paciente->id)->update(['estado' => 2]);

            if ($request->has('direcciones')) {
                foreach ($request->direcciones as $data) {
                    // Validamos que el campo dirección no esté vacío para evitar registros basura
                    if (!empty($data['direccion'])) {
                        
                        // 2. Usamos updateOrCreate para procesar cada registro
                        PacienteUbicacion::updateOrCreate(
                            [
                                // Criterios de búsqueda:
                                // Si el id es 0 o null, intentará crear uno nuevo.
                                'id' => $data['id'] ?? 0, 
                                'paciente_id' => $paciente->id
                            ],
                            [
                                // Valores a actualizar o insertar:
                                'tipo_ubicacion_id' => $data['tipodireccion_id'],
                                'direccion'         => $data['direccion'],
                                'municipio_id'      => 1, // Valores por defecto según tu código
                                'departamento_id'   => 1,
                                'pais_id'           => 1,
                                'estado'            => 1 // Se activa o reactiva
                            ]
                        );
                    }
                }
            }

            //===========================================================================
            // Emails
            //===========================================================================
            PacienteEmail::where('paciente_id', $paciente->id)->update(['estado' => 2]);

            if ($request->has('emails')) {
                foreach ($request->emails as $data) {
                    // Validamos que el campo email no esté vacío y sea un formato válido
                    if (!empty($data['email'])) {
                        
                        // 2. Usamos updateOrCreate para procesar cada registro
                        PacienteEmail::updateOrCreate(
                            [
                                // Criterios de búsqueda:
                                // Si el id es 0, null o no viene, se creará uno nuevo.
                                'id' => $data['id'] ?? 0, 
                                'paciente_id' => $paciente->id
                            ],
                            [
                                // Valores a actualizar o insertar:
                                'email'  => $data['email'],
                                'estado' => 1 // Se activa o reactiva
                            ]
                        );
                    }
                }
            }

            //===========================================================================
            // Seguros
            //===========================================================================
            PacienteSeguro::where('paciente_id', $paciente->id)->update(['estado' => 2]);

            if ($request->has('seguros')) {
                foreach ($request->seguros as $data) {
                    // Validamos que se haya seleccionado una aseguradora
                    if (isset($data['aseguradora_id']) && !empty($data['aseguradora_id'])) {
                        // 2. Sincronizamos con updateOrCreate
                        PacienteSeguro::updateOrCreate(
                            [
                                'id' => $data['id'] ?? 0, 
                                'paciente_id' => $paciente->id
                            ],
                            [
                                'aseguradora_id' => $data['aseguradora_id'],
                                'poliza_no'      => $data['poliza'] ?? null, // 'poliza' viene del JS
                                'estado'         => 1
                            ]
                        );
                    }
                }
            }

            //===========================================================================
            // Facturación
            //===========================================================================
            PacienteFacturacion::where('paciente_id', $paciente->id)->update(['estado' => 2]);

            if ($request->has('facturacion')) {
                foreach ($request->facturacion as $data) {
                    // Validamos que el NIT no esté vacío para procesar la fila
                    if (isset($data['nit']) && !empty($data['nit'])) {
                        
                        // 2. Sincronizamos con updateOrCreate
                        PacienteFacturacion::updateOrCreate(
                            [
                                // Criterio de búsqueda
                                'id' => $data['id'] ?? 0, 
                                'paciente_id' => $paciente->id
                            ],
                            [
                                // Valores a insertar o actualizar
                                'nit'       => $data['nit'],
                                'nombre'    => $data['nombre'] ?? 'Consumidor Final',
                                'direccion' => $data['direccion'] ?? 'Ciudad',
                                'estado'    => 1
                            ]
                        );
                    }
                }
            }

            //===========================================================================
            // Familia
            //===========================================================================
            PacienteFamilia::where('paciente_id', $paciente->id)->update(['estado' => 2]);
            if ($request->has('familia')) {
                foreach ($request->familia as $data) {
                    // Validamos que el nombre no esté vacío para procesar la fila
                    if (isset($data['nombre']) && !empty($data['nombre'])) {
                        $idFamiliar = (isset($data['id']) && is_numeric($data['id'])) ? $data['id'] : 0;
                        // 2. Sincronizamos con updateOrCreate
                        PacienteFamilia::updateOrCreate(
                            [
                                'id' => $idFamiliar,
                                'paciente_id' => $paciente->id
                            ],
                            [
                                'parentesco_id' => $data['parentesco_id'],
                                'nombre'        => $data['nombre'],
                                'telefono'      => $data['telefono'],
                                /**
                                 * Lógica del Checkbox:
                                 * Si la llave 'emergencia' existe en el array, es 'S'.
                                 * De lo contrario, el usuario lo desmarcó o no lo marcó, es 'N'.
                                 */
                                'emergencia'    => isset($data['emergencia']) ? 'S' : 'N',
                                'estado'        => 1
                            ]
                        );
                    }
                }
            }

            $message = array(
                'message' => 'Registro almacenado con exito !!!',
                'type'    => 'success'
            );

            return redirect()->back()->with($message);

        } catch (\Exception $e){
            return redirect()->back()
                ->withInput() // Mantiene lo que el usuario escribió
                ->with([
                    'message' => 'Hubo un problema técnico: ' . $e->getMessage(),
                    'type'    => 'error'
            ]);
        }
    }

    public function get_telefono_x_paciente(){
        $cadena = null;
        $paciente_id = $_POST['paciente_id'];
        $complemento = PacienteTelefono::where('paciente_id', $paciente_id)
                       ->pluck('numero')  // Obtener solo los valores de la columna 'numero'
                       ->implode(', ');    // Convertirlos en una cadena separada por comas

        return Response::json($complemento);
    }

    public function get_fecha_nacimiento(){
        $paciente_id = $_POST['paciente_id'];
        $complemento = Paciente::where('id',$paciente_id)->select('fecha_nacimiento')->first();
        return Response::json($complemento);
    }

    public function trae_datos_facturacion(){
        $paciente_id = $_POST['paciente_id'];
        $registro = DB::table('nits as n')
                    ->select('n.nit as factura_nit', 'n.nombre as factura_nombre', 'n.direccion as factura_direccion')
                    ->where('n.paciente_id', $paciente_id)
                    ->orderBy('n.id', 'desc')
                    ->first();

        if (!isset($registro)) {
            $registro = DB::table('pacientes as p')
                        ->where('p.id', $paciente_id)
                        ->select('p.factura_nit', 'p.factura_nombre', 'p.factura_direccion')
                        ->first();
        }

        return response::json($registro);
    }

    public function verifica_expediente(){
        $expediente =$_POST['expediente'];
        $registro = Paciente::where('expediente_no', $expediente)->count();
        
        return Response::json($registro);
    }

    public function get_patient_list(){
        $pacientes = Paciente::where('empresa_id', Auth::user()->empresa_id)
                     ->where('estado', 1)
                     ->select('id', 'nombre_completo')
                     ->get();
                     
        return Response::json($pacientes);
    }

    public function atencionMedica($admision_id){
        $admision = Admision::findOrFail($admision_id);
        $pPaciente  = Paciente::where('id', $admision->paciente_id)
                      ->select('id', DB::raw('CONCAT(nombres, " ", apellidos) nombre_completo'), 'expediente_no', 'fecha_nacimiento')
                      ->first();

        if ($pPaciente->genero == 'M') {
            $genero = 'Masculino';
        }else{
            $genero = 'Femenino';
        }

        $encabezado = DB::table('admisiones as a')
                      ->join('pacientes as p', 'a.paciente_id', 'p.id')
                      ->join('hospitales as h', 'a.hospital_id', 'h.id')
                      ->join('medicos as m', 'a.medico_id', 'm.id')
                      ->leftjoin('aseguradoras as s', 'a.aseguradora_id', 's.id')
                      ->select('a.id', 'a.admision_no', 'h.nombre as hospital_nombre', 's.nombre as aseguradora_nombre', 'a.fecha', 'm.nombre_completo as medico_nombre', DB::raw('CONCAT(p.nombres, " ", p.apellidos) nombre_completo'), 'a.poliza_no')
                      ->first();

        // $totalAdmisiones = \DB::table('admisiones')
        //                    ->where('empresa_id', Auth::user()->empresa_id)
        //                    ->where('paciente_id', $admision->paciente_id)
        //                    ->count();

        $listado = DB::table('admisiones as a')
                   ->join('hospitales as h', 'a.hospital_id', 'h.id')
                   ->join('medicos as m', 'a.medico_id', 'm.id')
                   ->leftjoin('aseguradoras as a1', 'a.aseguradora_id', 'a1.id')
                   ->where('a.empresa_id', Auth::user()->empresa_id)
                   ->where('a.paciente_id', $admision->paciente_id)
                   ->orderBy('a.admision_no', 'DESC')
                   ->select('a.id', 'a.admision_no', 'h.nombre as hospital_nombre', DB::raw('DATE_FORMAT(a.fecha, "%d/%m/%Y") as fecha'), 'm.nombre_completo as medico_nombre', 'a.poliza_no', 'a1.nombre as aseguradora_nombre')
                   ->paginate(15);

        // $pListaC = DB::table('admisiones as a')
        //            // ->join('admision_vitales as av', 'a.id', 'av.admision_id')
        //            // ->leftjoin('admision_consultas as ac', 'av.id', 'ac.admision_vital_id')
        //            ->where('a.empresa_id', Auth::user()->empresa_id)
        //            ->where('a.paciente_id', $admision->paciente_id)
        //            ->select('a.admision_no', 'a.id', 'a.created_at as fecha')
        //            ->orderBy('a.admision_no', 'DESC')
        //            ->paginate(10);

        $medicamentos = DB::table('productos as p')
                        ->join('producto_dosis as pd', 'p.id', 'pd.producto_id')
                        ->where('p.estado', 1)
                        ->groupBy('p.id', 'p.descripcion')
                        ->select('p.id', 'p.descripcion')
                        ->get();
        
        return view('pacientes.atencion', compact('admision_id', 'encabezado', 'medicamentos', 'listado'));
    }
}
