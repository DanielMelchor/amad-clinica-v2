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
use App\Models\Dosis;

class DosisController extends Controller
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
        $pDosis = dosis::all();
        return view('dosis.index', [
            'pDosis' => $pDosis
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dosis.create');
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
            'descripcion' => 'required'
        ]);

        $dosis = new Dosis();
        $dosis->descripcion = $validData['descripcion'];
        //$medico->firma = $file_name;
        if (isset($request->estado)) {
            $dosis->estado = 1;
        }else{
            $dosis->estado = 0;
        }
        $dosis->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('dosis')->with('message','Especialidad grabado con exito');
        // Session::flash('success', 'Dosis Guardada con exito !!!' );
        // return redirect(route('dosis'));
        $message = array(
            'message' => 'Registro Guardado con exito !!!',
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
        $dosisId = Crypt::decrypt($id);
        $registro = Dosis::findOrFail($dosisId);
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
            'edescripcion' => 'required'
        ]);

        $id     = $request['eid'];
        $dosisId = Crypt::decrypt($id);


        $dosis = dosis::findOrFail($dosisId);
        $dosis->descripcion = $validData['edescripcion'];
        //$medico->firma = $file_name;
        if (isset($request->eestado)) {
            $dosis->estado = 1;
        }else{
            $dosis->estado = 0;
        }
        $dosis->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('dosis')->with('message','Especialidad grabado con exito');
        // Session::flash('success', 'Dosis Actualizada con exito !!!' );
        // return redirect(route('dosis'));
        $message = array(
            'message' => 'Registro Actualizado con exito !!!',
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
}
