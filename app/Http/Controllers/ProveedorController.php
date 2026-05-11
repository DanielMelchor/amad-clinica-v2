<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use DB;
use Response;
use App\Models\Proveedor;
use App\Models\LineaMedica;
use App\Models\LineaMedicaContacto;

class ProveedorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	$proveedores = Proveedor::where('empresa_id', Auth::user()->empresa_id)->get();
    	return view('proveedores.index', compact('proveedores'));
    }

    public function create(){
    	$lineasmedicas = LineaMedica::where('estado', 'A')->get();
        return view('proveedores.create', compact('lineasmedicas'));
    }

    public function store(Request $request){
        //dd('entre '.$request);
        $validData = $request->validate([
            'nit'                   => 'required',
            'razon_social'          => 'required',
            'nombre_comercial'      => 'required',
            'direccion'             => 'required',
            'telefonos'             => 'required',
            'email'                 => 'required',
        ]);

        DB::beginTransaction();
        try {
            $proveedor = new Proveedor();
            $proveedor->empresa_id       = Auth::user()->empresa_id;
            $proveedor->nit              = $validData['nit'];
            $proveedor->razon_social     = $validData['razon_social'];
            $proveedor->nombre_comercial = $validData['nombre_comercial'];
            $proveedor->direccion        = $validData['direccion'];
            $proveedor->telefonos        = $validData['telefonos'];
            $proveedor->email            = $validData['email'];
            $proveedor->condicion        = $request->condicion;
            if ($request->condicion == 0) {
                $proveedor->dias_credito     = 0;
            }else{
                $proveedor->dias_credito     = $request->dias_credito;
            }

            if (isset($request->estado)) {
                $proveedor->estado = 1;
            }else{
                $proveedor->estado = 0;
            }
            $proveedor->save();

            $contactos = (array) $request['contactos'];
            $totalRegistros = count($contactos);

            for ($i=0; $i < $totalRegistros ; $i++) {
                $contacto = new LineaMedicaContacto;
                $contacto->lineamedica_id  = intval($contactos[$i]['lineamedica_id']);
                $contacto->proveedor_id    = $proveedor->id;
                $contacto->nombre_contacto = $contactos[$i]['nombre_contacto'];
                $contacto->telefonos       = $contactos[$i]['contacto_telefonos'];
                $contacto->email           = $contactos[$i]['contacto_email'];
                if (isset($contactos[$i]['contacto_estado'])) {
                    $contacto->estado          = 1;
                }else{
                    $contacto->estado          = 0;
                }
                
                $contacto->save();
            }

            DB::commit();

            $message = array(
                'message' => '! Registro almacenado con exito !',
                'type'    => 'success'
            );

            return redirect()->route('editar_proveedor', [$proveedor->id])->with($message);

        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Hubo un problema al actualizar el registro. {$request->context_agenda_id}: ' . $e->getMessage(),
                'type'    => 'error'
            ], 500);
        }
    }

    public function edit($id){
        $lineasmedicas = LineaMedica::where('estado', 'A')->get();
        $proveedor = Proveedor::where('id', $id)->first();
        $contactos = DB::table('lineamedica_contactos as lc')
                     ->join('lineas_medicas as lm', 'lc.lineamedica_id', 'lm.id')
                     ->where('proveedor_id', $id)
                     ->select('lc.id','lc.lineamedica_id', 'lm.descripcion as lineamedica_descripcion', 'lc.nombre_contacto', 'lc.telefonos', 'lc.email', 'lc.estado')
                     ->get();

        return view('proveedores.edit', compact('lineasmedicas', 'proveedor', 'contactos'));
    }

    public function trae_contactos(){
        $proveedor_id = $_POST['proveedor_id'];
        $contactos = DB::table('lineamedica_contactos as lc')
                     ->join('lineas_medicas as lm', 'lc.lineamedica_id', 'lm.id')
                     ->where('proveedor_id', $proveedor_id)
                     ->select('lc.id','lc.lineamedica_id', 'lm.descripcion as lineamedica_descripcion', 'lc.nombre_contacto', 'lc.telefonos', 'lc.email', 'lc.estado')
                     ->get();

        return Response::json($contactos);   
    }

    public function update(Request $request){
        // dd($request);
        $validData = $request->validate([
            'proveedor_id' => 'required',
            'razon_social' => 'required',
            'dias_credito' => 'required'
        ]);

        $proveedor_id = $validData['proveedor_id'];
        $proveedor = Proveedor::findOrFail($proveedor_id);
        $proveedor->razon_social     = $validData['razon_social'];
        $proveedor->nombre_comercial = $request['nombre_comercial'];
        $proveedor->direccion        = $request['direccion'];
        $proveedor->telefonos        = $request['telefonos'];
        $proveedor->email            = $request['email'];
        $proveedor->condicion        = $_POST['condicion'];
        $proveedor->dias_credito     = $validData['dias_credito'];
        if (isset($request['estado'])) {
            $proveedor->estado = 1;
        }else{
            $proveedor->estado = 0;
        }
        $proveedor->save();

        LineaMedicaContacto::where('proveedor_id', $proveedor_id)->update(['estado' => 0]);

        if (isset($_POST['contactos'])) {
            $contactos = (array) $_POST['contactos'];
            // dd($contactos);
            foreach ($contactos as $contacto) {
                $registro = LineaMedicaContacto::updateOrCreate(
                                [
                                    'proveedor_id'   => $proveedor_id,       // Campo de búsqueda 1
                                    'lineamedica_id' => $contacto['lineamedica_id']  // Campo de búsqueda 2
                                ],
                                [
                                    'nombre_contacto' => $contacto['nombre_contacto'],    // Campo a actualizar/insertar
                                    'telefonos'       => $contacto['contacto_telefonos'],  // Campo a actualizar/insertar
                                    'email'           => $contacto['contacto_email'],
                                    'estado'          => 1               // Campo a actualizar/insertar
                                ]
                            );
            }
        }

        $message = array(
            'message' => 'Registro actualizado con exito !!!',
            'type'    => 'success'
        );

        return redirect()->back()->with($message);
    }

    public function trae_generales(){
        $nit = $_POST['nit'];

        $proveedor = Proveedor::where('empresa_id', Auth::user()->empresa_id)->where('nit', $nit)->first();

        return Response::json($proveedor);   
    }

    // public function trae_contactos(){
    //     $proveedor_id = $_POST['proveedor_id'];

    //     $contactos = LineaMedicaContacto::where('´proveedor_id', $proveedor_id)->get();
    //     return Response::json($contactos);
    // }
}
