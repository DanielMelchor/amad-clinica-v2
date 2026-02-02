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
use App\Models\Especialidad;

class EspecialidadController extends Controller
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
        $pEspecialidades = Especialidad::all();
        return view('especialidades.index', [
            'pEspecialidades' => $pEspecialidades
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('especialidades.create');
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
            'iniciales' => 'required',
            'descripcion' => 'required'
        ]);

        $especialidad = new Especialidad();
        $especialidad->iniciales = $validData['iniciales'];
        $especialidad->descripcion = $validData['descripcion'];
        //$medico->firma = $file_name;
        if (isset($request->estado)) {
            $especialidad->estado = 1;
        }else{
            $especialidad->estado = 0;
        }
        $especialidad->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('especialidades')->with('message','Especialidad grabado con exito');
        // Session::flash('success', 'Especialidad Guardada con exito !!!' );
        // return redirect(route('especialidades'));
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
        $especialidadId = Crypt::decrypt($id);
        $registro = Especialidad::findOrFail($especialidadId);
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
            'einiciales' => 'required',
            'edescripcion' => 'required'
        ]);

        $id     = $_POST['eid'];
        $especialidadId = Crypt::decrypt($id);

        $especialidad = Especialidad::findOrFail($especialidadId);
        $especialidad->iniciales   = $validData['einiciales'];
        $especialidad->descripcion = $validData['edescripcion'];
        //$medico->firma = $file_name;
        if (isset($request->eestado)) {
            $especialidad->estado = 1;
        }else{
            $especialidad->estado = 0;
        }
        $especialidad->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('especialidades')->with('message','Especialidad grabado con exito');
        // Session::flash('success', 'Especialidad Actualizada con exito !!!' );
        // return redirect(route('especialidades'));
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
