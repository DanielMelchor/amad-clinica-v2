<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\Departamento;
use App\Models\Empresa;
use App\Models\Municipio;
use App\Models\user;
use App\Models\userStamps;
use App\Models\Pais;

class empresaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $listado = Empresa::all();
        return view('empresas.index', compact('listado'));
    }

    public function create()
    {
        $paises = Pais::where('estado', 1)->get();
        return view('empresas.create', compact('paises'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validData = $request->validate([
                'razon_social'        => 'required',
                'nombre_comercial'    => 'required',
                'direccion'           => 'required',
                'municipio_id'        => 'required',
                'departamento_id'     => 'required',
                'pais_id'             => 'required',
                'codigo_postal'       => 'required',
                'email'               => 'required|email',
                'telefonos'           => 'required',
                'afiliacion_iva'      => 'required',
                'porcentaje_impuesto' => 'required'
            ]);

            $empresa = new Empresa();
            $empresa->razon_social        = $validData['razon_social'];
            $empresa->nombre_comercial    = $validData['nombre_comercial'];
            $empresa->direccion           = $validData['direccion'];
            $empresa->municipio_id        = $validData['municipio_id'];
            $empresa->departamento_id     = $validData['departamento_id'];
            $empresa->pais_id             = $validData['pais_id'];
            $empresa->codigo_postal       = $validData['codigo_postal'];
            $empresa->email               = $validData['email'];
            $empresa->telefonos           = $validData['telefonos'];
            $empresa->afiliacion_iva      = $validData['afiliacion_iva'];
            $empresa->porcentaje_impuesto = $validData['porcentaje_impuesto'];
            $empresa->nit_empresa         = $request->nit_empresa;
            $empresa->igss_empresa        = $request->igss_empresa;
            $empresa->fecha_constitucion  = $request->fecha_constitucion;
            $empresa->alias               = $request->alias;
            $empresa->formato             = $request->formato;
            $empresa->llave_firma         = $request->llave_firma;
            $empresa->llave_certifica     = $request->llave_certifica;

            if (!empty($request->logo_empresa))
            //if ($request->hasFile('logo_empresa')) 
            {
                $logo = $request->file('logo_empresa')->getClientOriginalName();
                $request->file('logo_empresa')->move('logos', $logo);
                $empresa->ruta_logo = 'logos/' . $logo;
            }
            
            if (isset($request->estado)) {
                $empresa->estado = 1;
            }else{
                $empresa->estado = 0;
            }

            DB::commit();
            
            $saved = $empresa->save();
            // return Redirect::route('empresas')->with('message','Empresa grabada con exito');
            if ($saved) {
                $message = array(
                    'message' => 'Registro almacenado con exito !!!',
                    'type'    => 'success'
                );
            }else{
                DB::rollBack();
                $message = array(
                    'message' => 'Error al almacenar la información !!!',
                    'type'    => 'error'
                );
            }
        }catch (\Exception $e) {
            DB::rollBack();
            $message = array(
                'message' => 'Error al almacenar la información !!!',
                'type'    => 'error'
            );
        }

        // return redirect()->back()->with($message);
        return redirect()->route('empresas')->with($message);
    }

    public function edit($id)
    {
        $empresaId = decrypt($id);
        $empresa = Empresa::findOrFail($empresaId);
        $paises = Pais::where('estado', 1)->get();
        $departamentos = Departamento::where('pais_id', $empresa->pais_id)->get();
        $municipios = Municipio::where('departamento_id', $empresa->departamento_id)->get();
        return view('empresas.edit', compact('empresa', 'paises', 'departamentos', 'municipios', 'id'));
    }

    public function update(REQUEST $request, $id)
    {
        $validData = $request->validate([
            'razon_social' => 'required',
            'nombre_comercial' => 'required',
            'direccion' => 'required',
            'municipio_id' => 'required',
            'departamento_id' => 'required',
            'pais_id' => 'required',
            'codigo_postal' => 'required',
            'email' => 'required|email',
            'telefonos' => 'required',
            'afiliacion_iva' => 'required',
            'porcentaje_impuesto' => 'required'
        ]);

        $empresaId = decrypt($id);

        $empresa = Empresa::findOrFail($empresaId);

        $empresa->razon_social        = $validData['razon_social'];
        $empresa->nombre_comercial    = $validData['nombre_comercial'];
        $empresa->direccion           = $validData['direccion'];
        $empresa->municipio_id        = $validData['municipio_id'];
        $empresa->departamento_id     = $validData['departamento_id'];
        $empresa->pais_id             = $validData['pais_id'];
        $empresa->codigo_postal       = $validData['codigo_postal'];
        $empresa->email               = $validData['email'];
        $empresa->telefonos           = $validData['telefonos'];
        $empresa->afiliacion_iva      = $validData['afiliacion_iva'];
        $empresa->porcentaje_impuesto = $validData['porcentaje_impuesto'];
        $empresa->nit_empresa         = $request->nit_empresa;
        $empresa->igss_empresa        = $request->igss_empresa;
        $empresa->fecha_constitucion  = $request->fecha_constitucion;
        $empresa->alias               = $request->alias;
        $empresa->formato             = $request->formato;
        $empresa->llave_firma         = $request->llave_firma;
        $empresa->llave_certifica     = $request->llave_certifica;

        if (!empty($request->logo_empresa))
        {
        	$logo = $request->file('logo_empresa')->getClientOriginalName();
        	$request->file('logo_empresa')->move('logos', $logo);
        	$empresa->ruta_logo = 'logos/' . $logo;
        }
        
        if (isset($request->estado)) {
            $empresa->estado = 1;
        }else{
            $empresa->estado = 0;
        }
        $empresa->save();

        // return Redirect::route('empresas')->with('message','Empresa grabada con exito');
        $message = array(
            'message' => 'Registro actualizado con exito !!!',
            'type'    => 'success'
        );

        return redirect()->back()->with($message);
    }

    public function borrar_logo($id)
    {
        $empresa = Empresa::findOrFail($id);
        if (!empty($empresa->ruta_logo))
        {
            unlink($empresa->ruta_logo);
        }
        
        $empresa->ruta_logo = '';
        $empresa->save();
        return view('empresas.edit',[
            'pEmpresa' => $empresa
            ]);
    }
}
