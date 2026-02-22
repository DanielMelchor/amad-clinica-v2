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
        $validData = $request->validate([
            'nombres'          => 'required|min:3',
            'apellidos'        => 'required|min:3',
            'fecha_nacimiento' => 'required|date',
            'expediente_no'    => 'required|numeric'

        ]);

        $codigo_no = Correlativo::where('empresa_id', Auth::user()->empresa_id)
                     ->where('tipo', 'P')
                     ->max('correlativo');
        $codigo_no += 1;

        if (isset($request->telefonos)) {
            $totalTelefonos   = count($request->telefonos);
            $dataTelefono     = (array) $request->telefonos;
        }else{
            $totalTelefonos   = 0;
        }
        
        if (isset($request->direcciones)) {
            $totalDirecciones = count($request->direcciones);
            $dataDireccion    = (array) $request->direcciones;
        }else{
            $totalDirecciones = 0;
        }

        if (isset($request->emails)) {
            $totalEmails      = count($request->emails);
            $dataEmail        = (array) $request->emails;
        }else{
            $totalEmails = 0;
        }

        if (isset($request->seguros)) {
            $totalSeguros     = count($request->seguros);
            $dataSeguro       = (array) $request->seguros;
        }else{
            $totalSeguros = 0;
        }

        if (isset($request->facturacion)) {
            $totalFacturacion = count($request->facturacion);
            $dataFacturacion  = (array) $request->facturacion;
        }else{
            $totalFacturacion = 0;
        }

        if (isset($request->familia)) {
            $totalFamilia     = count($request->familia);
            $dataFamilia      = (array) $request->familia;
        }else{
            $totalFamilia     = 0;
        }
        // dd($totalRegistros);

        //===========================================================================
        // Datos generales de paciente
        //===========================================================================
        $paciente = new Paciente();
        $paciente->empresa_id             = Auth::user()->empresa_id;
        $paciente->expediente_no          = $validData['expediente_no'];
        $paciente->expediente_anterior_no = $request->expediente_anterior_no;
        $paciente->codigo_id              = $codigo_no;
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

        /*if (isset($request->antecedente_importante)){
            $paciente->antecedente_importante       = 'S';
        }else
        {
            $paciente->antecedente_importante       = 'N';
        }*/

        $paciente->save();

        //===========================================================================
        // Actualización de correlativo
        //===========================================================================
        $corr = Correlativo::where('empresa_id', Auth::user()->empresa_id)->where('tipo', 'P')->first();
        $corr->correlativo = $codigo_no;
        $corr->save();

        //===========================================================================
        // Telefonos
        //===========================================================================
        if ($totalTelefonos > 0) {
            for ($i=0; $i < $totalTelefonos; $i++) { 
                $pacienteTelefono = new PacienteTelefono();
                $pacienteTelefono->paciente_id          = $paciente->id;
                $pacienteTelefono->tipo_comunicacion_id = $dataTelefono[$i]['tipocomunicacion_id'];
                $pacienteTelefono->numero               = $dataTelefono[$i]['numero'];
                $pacienteTelefono->extension            = $dataTelefono[$i]['extension'];
                $pacienteTelefono->save();
            }
        }

        //===========================================================================
        // Direcciones
        //===========================================================================
        if ($totalDirecciones > 0) {
            //print_r($dataDireccion); die;
            for ($i=0; $i < $totalDirecciones; $i++) { 
                //dd($dataDireccion[$i]['tipodireccion_id']);
                $pacienteDireccion = new PacienteUbicacion();
                $pacienteDireccion->paciente_id       = $paciente->id;
                $pacienteDireccion->tipo_ubicacion_id = $dataDireccion[$i]['tipodireccion_id'];
                $pacienteDireccion->direccion         = $dataDireccion[$i]['direccion'];
                $pacienteDireccion->municipio_id      = 1;
                $pacienteDireccion->departamento_id   = 1;
                $pacienteDireccion->pais_id = 1;
                $pacienteDireccion->estado            = 'A';
                $pacienteDireccion->save();
            }
        }

        //===========================================================================
        // Emails
        //===========================================================================
        if ($totalEmails > 0) {
            for ($i=0; $i < $totalEmails; $i++) { 
                $pacienteEmail = new PacienteEmail();
                $pacienteEmail->paciente_id = $paciente->id;
                $pacienteEmail->email       = $dataEmail[$i]['email'];
                $pacienteEmail->estado      = 'A';
                $pacienteEmail->save();
            }
        }

        //===========================================================================
        // Seguros
        //===========================================================================
        if ($totalSeguros > 0) {
            for ($i=0; $i < $totalSeguros; $i++) { 
                $pacienteSeguro = new PacienteSeguro();
                $pacienteSeguro->paciente_id    = $paciente->id;
                $pacienteSeguro->aseguradora_id = $dataSeguro[$i]['aseguradora_id'];
                $pacienteSeguro->poliza_no      = $dataSeguro[$i]['poliza'];
                $pacienteSeguro->estado         = 'A';
                $pacienteSeguro->save();
            }
        }

        //===========================================================================
        // Facturación
        //===========================================================================
        if ($totalFacturacion > 0) {
            for ($i=0; $i < $totalFacturacion; $i++) { 
                $pacienteFacturacion = new PacienteFacturacion();
                $pacienteFacturacion->paciente_id = $paciente->id;
                $pacienteFacturacion->nit         = $dataFacturacion[$i]['nit'];
                $pacienteFacturacion->nombre      = $dataFacturacion[$i]['nombre'];
                $pacienteFacturacion->direccion   = $dataFacturacion[$i]['direccion'];
                $pacienteFacturacion->estado      = 'A';
                $pacienteFacturacion->save();
            }
        }

        //===========================================================================
        // Familia
        //===========================================================================
        if ($totalFamilia > 0) {
            for ($i=0; $i < $totalFamilia; $i++) { 
                $pacienteFamilia = new PacienteFamilia();
                $pacienteFamilia->paciente_id   = $paciente->id;
                $pacienteFamilia->parentesco_id = $dataFamilia[$i]['parentesco_id'];
                $pacienteFamilia->nombre        = $dataFamilia[$i]['nombre'];
                $pacienteFamilia->telefono      = $dataFamilia[$i]['telefono'];
                if (isset($dataFamilia[$i]['emergencia'])) {
                    $pacienteFamilia->emergencia = 'S';
                }else{
                    $pacienteFamilia->emergencia = 'N';
                }
                $pacienteFamilia->estado         = 'A';
                $pacienteFamilia->save();
            }
        }


        //Session::flash('success', 'Se editó el medico con éxito.');
        // Session::flash('success', 'Paciente grabado con exito !!!' );
        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );

        return redirect()->back()->with($message);

        /*if ($origen == 'P') {
            // return Redirect::route('pacientes');
            return redirect()->back()->with($message);
        } else {
            return Redirect::route('nueva_edicion', $cita);
        }*/
    }

    public function edit($id)
    {
        $pacienteId      = Crypt::decrypt($id);
        $registro        = Paciente::findOrFail($pacienteId);
        $aseguradoras    = Aseguradora::all();
        $tipoTelefonos   = TipoComunicacion::where('estado', 'A')->orderBy('nombre', 'ASC')->get();
        $tipoDirecciones = TipoUbicacion::where('estado', 'A')->orderBy('nombre', 'ASC')->get();
        $parentescos     = Parentesco::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $pacienteTelefonos     = PacienteTelefono::where('paciente_id', $pacienteId)->where('estado', 'A')->get();
        $pacienteDirecciones   = PacienteUbicacion::where('paciente_id', $pacienteId)->where('estado', 'A')->get();
        $pacienteEmails        = PacienteEmail::where('paciente_id', $pacienteId)->where('estado', 'A')->get();
        $pacienteSeguros       = PacienteSeguro::where('paciente_id', $pacienteId)->where('estado', 'A')->get();
        $pacienteFacturaciones = PacienteFacturacion::where('paciente_id', $pacienteId)->where('estado', 'A')->get();
        $pacienteFamilias      = PacienteFamilia::where('paciente_id', $pacienteId)->where('estado', 'A')->get();
        //dd($pacienteEmails);


        //$pAdmisiones   = vw_admision::where('paciente_id', $id)->orderByRaw('fecha_creacion DESC')->get();

        return view('pacientes.edit', compact('registro', 'aseguradoras', 'tipoTelefonos', 'tipoDirecciones', 'parentescos', 'pacienteTelefonos', 'pacienteDirecciones', 'pacienteEmails', 'pacienteSeguros', 'pacienteFacturaciones', 'pacienteFamilias', 'id'));

    }

    public function update(Request $request, $id)
    {
        $validData = $request->validate([
            'expediente_no'    => 'required|numeric',
            'codigo_id'        => 'required|numeric',
            'nombres'          => 'required|min:3',
            'apellidos'        => 'required|min:3',
            'fecha_nacimiento' => 'required|date'

        ]);

        $pacienteId = Crypt::decrypt($id);

        if (isset($request->telefonos)) {
            $totalTelefonos   = count($request->telefonos);
            $dataTelefono     = (array) $request->telefonos;
        }else{
            $totalTelefonos   = 0;
        }
        
        if (isset($request->direcciones)) {
            $totalDirecciones = count($request->direcciones);
            $dataDireccion    = (array) $request->direcciones;
        }else{
            $totalDirecciones = 0;
        }

        if (isset($request->emails)) {
            $totalEmails      = count($request->emails);
            $dataEmail        = (array) $request->emails;
        }else{
            $totalEmails = 0;
        }

        if (isset($request->seguros)) {
            $totalSeguros     = count($request->seguros);
            $dataSeguro       = (array) $request->seguros;
        }else{
            $totalSeguros = 0;
        }

        if (isset($request->facturacion)) {
            $totalFacturacion = count($request->facturacion);
            $dataFacturacion  = (array) $request->facturacion;
        }else{
            $totalFacturacion = 0;
        }

        if (isset($request->familia)) {
            $totalFamilia     = count($request->familia);
            $dataFamilia      = (array) $request->familia;
        }else{
            $totalFamilia     = 0;
        }

        $paciente = Paciente::findOrFail($pacienteId);
        //$paciente->empresa_id             = Auth::user()->empresa_id;
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
        PacienteTelefono::where('paciente_id', $pacienteId)->update(['estado' => 'I']);
        if ($totalTelefonos > 0) {
            foreach ($dataTelefono as $key => $data) {
                if ($data['id'] == 0) {
                    $pacienteTelefono = new PacienteTelefono();
                }else{
                    $pacienteTelefono = PacienteTelefono::findOrFail($data['id']);
                }
                $pacienteTelefono->paciente_id          = $paciente->id;
                $pacienteTelefono->tipo_comunicacion_id = $data['tipocomunicacion_id'];
                $pacienteTelefono->numero               = $data['numero'];
                $pacienteTelefono->extension            = $data['extension'];
                $pacienteTelefono->estado               = 'A';
                $pacienteTelefono->save();
            }
            for ($i=0; $i < $totalTelefonos; $i++) { 
                
            }
        }

        //===========================================================================
        // Direcciones
        //===========================================================================
        PacienteUbicacion::where('paciente_id', $pacienteId)->update(['estado' => 'I']);
        if ($totalDirecciones > 0) {
            foreach ($dataDireccion as $key => $data) {
                if ($data['id'] == 0) {
                    $pacienteDireccion = new PacienteUbicacion();
                }else{
                    $pacienteDireccion = PacienteUbicacion::findOrFail($data['id']);
                }
                $pacienteDireccion->paciente_id       = $paciente->id;
                $pacienteDireccion->tipo_ubicacion_id = $data['tipodireccion_id'];
                $pacienteDireccion->direccion         = $data['direccion'];
                $pacienteDireccion->municipio_id      = 1;
                $pacienteDireccion->departamento_id   = 1;
                $pacienteDireccion->pais_id           = 1;
                $pacienteDireccion->estado            = 'A';
                $pacienteDireccion->save();
            }
        }

        //===========================================================================
        // Emails
        //===========================================================================
        PacienteEmail::where('paciente_id', $pacienteId)->update(['estado' => 'I']);
        if ($totalEmails > 0) {
            foreach ($dataEmail as $key => $data) {
                if ($data['id'] == 0) {
                    $pacienteEmail = new PacienteEmail();
                }else{
                    $pacienteEmail = PacienteEmail::findOrFail($data['id']);
                }
                $pacienteEmail->paciente_id = $paciente->id;
                $pacienteEmail->email       = $data['email'];
                $pacienteEmail->estado      = 'A';
                $pacienteEmail->save();
            }
        }

        //===========================================================================
        // Seguros
        //===========================================================================
        PacienteSeguro::where('paciente_id', $pacienteId)->update(['estado' => 'I']);
        if ($totalSeguros > 0) {
            foreach ($dataSeguro as $key => $data) {
                if ($data['id'] == 0) {
                    $pacienteSeguro = new PacienteSeguro();
                }else{
                    $pacienteSeguro = PacienteSeguro::findOrFail($data['id']);
                }
                $pacienteSeguro->paciente_id    = $paciente->id;
                $pacienteSeguro->aseguradora_id = $data['aseguradora_id'];
                $pacienteSeguro->poliza_no      = $data['poliza'];
                $pacienteSeguro->estado         = 'A';
                $pacienteSeguro->save();
            }
        }

        //===========================================================================
        // Facturación
        //===========================================================================
        PacienteFacturacion::where('paciente_id', $pacienteId)->update(['estado' => 'I']);
        if ($totalFacturacion > 0) {
            foreach ($dataFacturacion as $key => $data) {
                if ($data['id'] == 0) {
                    $pacienteFacturacion = new PacienteFacturacion();
                }else{
                    $pacienteFacturacion = PacienteFacturacion::findOrFail($data['id']);
                }
                $pacienteFacturacion->paciente_id = $paciente->id;
                $pacienteFacturacion->nit         = $data['nit'];
                $pacienteFacturacion->nombre      = $data['nombre'];
                $pacienteFacturacion->direccion   = $data['direccion'];
                $pacienteFacturacion->estado      = 'A';
                $pacienteFacturacion->save();
            }
        }

        //===========================================================================
        // Familia
        //===========================================================================
        PacienteFamilia::where('paciente_id', $pacienteId)->update(['estado' => 'I']);
        if ($totalFamilia > 0) {
            foreach ($dataFamilia as $key => $data) {
                if ($data['id'] == 0) {
                    $pacienteFamilia = new PacienteFamilia();
                }else{
                    $pacienteFamilia = PacienteFamilia::findOrFail($data['id']);
                }
                $pacienteFamilia->paciente_id   = $paciente->id;
                $pacienteFamilia->parentesco_id = $data['parentesco_id'];
                $pacienteFamilia->nombre        = $data['nombre'];
                $pacienteFamilia->telefono      = $data['telefono'];
                if (isset($dataFamilia[$i]['emergencia'])) {
                    $pacienteFamilia->emergencia = 'S';
                }else{
                    $pacienteFamilia->emergencia = 'N';
                }
                $pacienteFamilia->estado         = 'A';
                $pacienteFamilia->save();
            }
        }

        //Session::flash('success', 'Se editó el medico con éxito.');
        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );

        // return redirect()->back()->with($message);
        // return Redirect::route('pacientes')->with('message','Paciente grabado con exito');
        return redirect()->back()->with($message);
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
