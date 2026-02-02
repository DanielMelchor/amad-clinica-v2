<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Response;
use Session;
use user;
use App\Models\Aseguradora;

class AseguradoraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $pAseguradoras = Aseguradora::all();
        return view('aseguradoras.index', [
            'pAseguradoras' => $pAseguradoras
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('aseguradoras.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validData = $request->validate([
            'nombre' => 'required'
        ]);

        $aseguradora = new Aseguradora();
        $aseguradora->empresa_id = Auth::user()->empresa_id;
        $aseguradora->nombre = $validData['nombre'];
        $aseguradora->direccion = $request->direccion;
        $aseguradora->telefonos = $request->telefonos;
        $aseguradora->contacto = $request->contacto;
        $aseguradora->facturacion_nit = $request->facturacion_nit;
        $aseguradora->facturacion_nombre = $request->facturacion_nombre;
        $aseguradora->facturacion_direccion = $request->facturacion_direccion;
        if (isset($request->estado)) {
            $aseguradora->estado = 1;
        }else{
            $aseguradora->estado = 0;
        }
        $aseguradora->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        // Session::flash('success', 'Aseguradora grabada con exito !!!' );
        // return redirect(route('aseguradoras'));
        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $id     = $_POST['id'];
        $aseguradoraId = Crypt::decrypt($id);
        $registro = Aseguradora::findOrFail($aseguradoraId);
        return Response::json($registro);
    }

 	 /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validData = $request->validate([
            'enombre' => 'required'
        ]);

        $id     = $_POST['eid'];
        $aseguradoraId = Crypt::decrypt($id);

        $aseguradora = Aseguradora::findorfail($aseguradoraId);
        $aseguradora->nombre = $validData['enombre'];
        $aseguradora->direccion = $request->edireccion;
        $aseguradora->telefonos = $request->etelefonos;
        $aseguradora->contacto = $request->econtacto;
        $aseguradora->facturacion_nit = $request->efacturacion_nit;
        $aseguradora->facturacion_nombre = $request->efacturacion_nombre;
        $aseguradora->facturacion_direccion = $request->efacturacion_direccion;
        if (isset($request->eestado)) {
            $aseguradora->estado = 1;
        }else{
            $aseguradora->estado = 0;
        }
        $aseguradora->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('aseguradoras')->with('message','Medico grabado con exito');
        // Session::flash('success', 'Aseguradora Actualizada con exito !!!' );
        // return redirect(route('aseguradoras'));
        $message = array(
            'message' => 'Registro actualizado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function get_datos_facturacion(){
        $aseguradora_id = $_POST['aseguradora_id'];
        $registro = Aseguradora::where('id', $aseguradora_id)
                    ->select('facturacion_nit as factura_nit', 'facturacion_nombre as factura_nombre', 'facturacion_direccion as factura_direccion')
                    ->first();

        return Response::json($registro);
    }
}
