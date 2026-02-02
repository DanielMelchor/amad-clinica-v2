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
use App\Models\UnidadMedida;

class UnidadMedidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $pUnidadmedidas = UnidadMedida::all();
        return view('unidadmedidas.index', [
            'pUnidadmedidas' => $pUnidadmedidas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('unidadmedidas.create');
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
            'descripcion' => 'required',
            'siglas' => 'required'
        ]);

        $unidadmedida = new UnidadMedida();
        $unidadmedida->descripcion   = $validData['descripcion'];
        $unidadmedida->siglas        = $validData['siglas'];
        if (isset($request->aplica_receta)) {
            $unidadmedida->aplica_receta = $request->aplica_receta;
        }else{
            $unidadmedida->aplica_receta = 'N';
        }
        if (isset($request->estado)) {
            $unidadmedida->estado = 1;
        }else{
            $unidadmedida->estado = 0;
        }
        $unidadmedida->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('unidadmedidas')->with('message','Unidad de medida grabada con exito');
        Session::flash('success', 'Unidad de Médida Guardada con exito !!!' );
        return redirect(route('unidadmedidas'));
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
        $medidaId     = Crypt::decrypt($id);
        $registro     = UnidadMedida::findOrFail($medidaId);
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
            'edescripcion' => 'required',
            'esiglas' => 'required'
        ]);

        $id = Crypt::decrypt($request['eid']);

        $unidadmedida = UnidadMedida::findOrFail($id);
        $unidadmedida->descripcion   = $validData['edescripcion'];
        $unidadmedida->siglas        = $validData['esiglas'];
        if (isset($request->eaplica_receta)) {
            $unidadmedida->aplica_receta = $request->eaplica_receta;
        }else{
            $unidadmedida->aplica_receta = 'N';
        }
        if (isset($request->eestado)) {
            $unidadmedida->estado = 1;
        }else{
            $unidadmedida->estado = 0;
        }
        $unidadmedida->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('unidadmedidas')->with('message','Unidad de medida grabada con exito');
        Session::flash('success', 'Unidad de Médida Actualizada con exito !!!' );
        return redirect(route('unidadmedidas'));
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
}
