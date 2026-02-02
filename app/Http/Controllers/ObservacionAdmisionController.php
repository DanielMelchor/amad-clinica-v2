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
use App\Models\ObservacionAdmision;

class ObservacionAdmisionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $pObservaciones = ObservacionAdmision::all();
        return view('observaciones.index', [
            'pObservaciones' => $pObservaciones
        ]);
    }

    public function create()
    {
        return view('observaciones.create');
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'proceso' => 'required',
            'descripcion' => 'required'
        ]);

        $observacion = new ObservacionAdmision();
        $observacion->proceso = $validData['proceso'];
        $observacion->descripcion = $validData['descripcion'];
        if (isset($request->estado)) {
            $observacion->estado = 'A';
        }else{
            $observacion->estado = 'I';
        }
        $observacion->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('observaciones')->with('message','Observación grabada con exito');
        // Session::flash('success', 'Observación Guardada con exito !!!' );
        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);

        // return redirect(route('observaciones'));
    }

    public function edit()
    {
        $id     = $_POST['id'];
        $observacionId = Crypt::decrypt($id);
        $registro = ObservacionAdmision::findOrFail($observacionId);
        return Response::json($registro);
    }

    public function update(Request $request)
    {
        $validData = $request->validate([
            'eproceso' => 'required',
            'edescripcion' => 'required'
        ]);

        $id     = $_POST['eid'];
        $observacionId            = Crypt::decrypt($id);
        $observacion              = ObservacionAdmision::findOrFail($observacionId);
        $observacion->proceso     = $validData['eproceso'];
        $observacion->descripcion = $validData['edescripcion'];
        if (isset($request->eestado)) {
            $observacion->estado = 'A';
        }else{
            $observacion->estado = 'I';
        }
        $observacion->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('observaciones')->with('message','Observación grabada con exito');
        // Session::flash('success', 'Observacion Actualizada con exito !!!' );
        // return redirect(route('observaciones'));
        $message = array(
            'message' => 'Registro actualizado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }
}
