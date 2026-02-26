<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\Correlativo;
use App\Models\Departamento;
use App\Models\Empresa;
use App\Models\Municipio;
use App\Models\user;
use App\Models\userStamps;
use App\Models\Pais;
use Intervention\Image\Laravel\Facades\Image;

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
                'telefonos'           => 'required',
                'porcentaje_impuesto' => 'required',
                'correlativo_pacientes'  => 'required',
                'correlativo_admisiones' => 'required'
            ],[
                'razon_social'     => 'La Razón Social es de caracter obligatorio.',
                'nombre_comercial' => 'El Nombre Comercial es de caracter obligatorio.',
                'direccion'        => 'La Dirección es de caracter obligatorio.',
                'codigo_postal'    => 'El Codigo Postal es de caracter obligatorio.',
                'telefonos'        => 'El Telefono es de caracter obligatorio.',
                'porcentaje_impuesto' => 'El Porcentaje de Impuesto es de caracter obligatorio.'
            ]);

            $empresa = new Empresa();
            $empresa->razon_social        = $validData['razon_social'];
            $empresa->nombre_comercial    = $validData['nombre_comercial'];
            $empresa->direccion           = $validData['direccion'];
            $empresa->municipio_id        = $validData['municipio_id'];
            $empresa->departamento_id     = $validData['departamento_id'];
            $empresa->pais_id             = $validData['pais_id'];
            $empresa->codigo_postal       = $validData['codigo_postal'];
            $empresa->email               = $request->email;
            $empresa->telefonos           = $validData['telefonos'];
            $empresa->afiliacion_iva      = $request->afiliacion_iva;
            $empresa->porcentaje_impuesto = $validData['porcentaje_impuesto'];
            $empresa->nit_empresa         = $request->nit_empresa;
            $empresa->igss_empresa        = $request->igss_empresa;
            $empresa->fecha_constitucion  = $request->fecha_constitucion;
            $empresa->alias               = $request->alias;
            $empresa->formato             = $request->formato;
            $empresa->llave_firma         = $request->llave_firma;
            $empresa->llave_certifica     = $request->llave_certifica;

            $file = $request->logo_empresa;

            if ($file) {
                $nombreHashed = time() . '_' . $file->hashName();
                $path = storage_path('app/public/logos/');

                if (!File::isDirectory($path)) {
                    File::makeDirectory($path, 0777, true, true);
                }

                $img = Image::read($file);
                $img->scale(width: 1000); 

                // 5. GUARDAR FÍSICAMENTE
                $img->save($path . $nombreHashed);

                // $logo = $request->file('logo_empresa')->getClientOriginalName();
                // $request->file('logo_empresa')->move('logos', $logo);
                $empresa->ruta_logo = $nombreHashed;
            }
            
            if (isset($request->estado)) {
                $empresa->estado = 1;
            }else{
                $empresa->estado = 0;
            }

            $empresa->save();

            // **************************************************************** //
            // ************************* Correlativos ************************* //
            // **************************************************************** //
            $tipos = [
                'P' => $request->correlativo_pacientes,
                'A' => $request->correlativo_admisiones
            ];

            foreach ($tipos as $tipo => $valor) {
                $correlativo = new Correlativo();
                $correlativo->empresa_id = $empresa->id; // Ahora el ID ya existe
                $correlativo->tipo = $tipo;
                $correlativo->correlativo = $valor;
                $correlativo->save();
            }

            DB::commit();

            $idEncriptado = Crypt::encrypt($empresa->id);

            $message = array(
                'message' => 'Registro almacenado con exito !!!',
                'type'    => 'success'
            );

            return redirect()->route('editar_empresa', [$idEncriptado])->with($message);
            
        }catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }

        // return redirect()->route('empresas')->with($message);
    }

    public function edit($id)
    {
        try {
            // Intentamos desencriptar
            $realId = Crypt::decryptString($id);
            $empresa = Empresa::findOrFail($realId);
            $correlativos = Correlativo::where('empresa_id', $empresa->id)->get();
            $paises = Pais::where('estado', 1)->get();
            $departamentos = Departamento::where('pais_id', $empresa->pais_id)->get();
            $municipios = Municipio::where('departamento_id', $empresa->departamento_id)->get();

            return view('empresas.edit', compact('empresa', 'paises', 'departamentos', 'municipios', 'id', 'correlativos'));
            
            // ... resto de tu lógica
        } catch (DecryptException $e) {
            // Si el payload es inválido o fue alterado, redirigimos con un mensaje
            return redirect()->route('empresas')->with([
                'message' => 'El identificador del registro es inválido.',
                'type' => 'error'
            ]);
        }
    }

    public function update(REQUEST $request, $id)
    {
        $validData = $request->validate([
                'razon_social'        => 'required',
                'nombre_comercial'    => 'required',
                'direccion'           => 'required',
                'municipio_id'        => 'required',
                'departamento_id'     => 'required',
                'pais_id'             => 'required',
                'codigo_postal'       => 'required',
                'telefonos'           => 'required',
                'porcentaje_impuesto' => 'required',
                'correlativo_pacientes'  => 'required',
                'correlativo_admisiones' => 'required'
            ],[
                'razon_social'     => 'La Razón Social es de caracter obligatorio.',
                'nombre_comercial' => 'El Nombre Comercial es de caracter obligatorio.',
                'direccion'        => 'La Dirección es de caracter obligatorio.',
                'codigo_postal'    => 'El Codigo Postal es de caracter obligatorio.',
                'telefonos'        => 'El Telefono es de caracter obligatorio.',
                'porcentaje_impuesto' => 'El Porcentaje de Impuesto es de caracter obligatorio.'
            ]);

        $empresaId = Crypt::decryptString($id);

        DB::beginTransaction();

        try{
            $empresa = Empresa::findOrFail($empresaId);
            $empresa->razon_social        = $validData['razon_social'];
            $empresa->nombre_comercial    = $validData['nombre_comercial'];
            $empresa->direccion           = $validData['direccion'];
            $empresa->municipio_id        = $validData['municipio_id'];
            $empresa->departamento_id     = $validData['departamento_id'];
            $empresa->pais_id             = $validData['pais_id'];
            $empresa->codigo_postal       = $validData['codigo_postal'];
            $empresa->email               = $request->email;
            $empresa->telefonos           = $validData['telefonos'];
            $empresa->afiliacion_iva      = $request->afiliacion_iva;
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

            // **************************************************************** //
            // ************************* Correlativos ************************* //
            // **************************************************************** //
            $tipos = [
                'P' => $request->correlativo_pacientes,
                'A' => $request->correlativo_admisiones
            ];

            foreach ($tipos as $tipo => $valor) {
                Correlativo::updateOrCreate(
                    [
                        // Atributos de búsqueda (si coinciden ambos, se actualiza)
                        'empresa_id' => $empresa->id,
                        'tipo'       => $tipo,
                    ],
                    [
                        // Atributos que se crean o actualizan
                        'correlativo' => $valor,
                    ]
                );
            }

            DB::commit();

            $message = array(
                'message' => 'Registro almacenado con exito !!!',
                'type'    => 'success'
            );

            return back()->withInput()->with($message);


        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }

        

        

        

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
