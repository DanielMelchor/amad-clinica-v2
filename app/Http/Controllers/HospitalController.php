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
use App\Models\Hospital;

class HospitalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /*public function hospital_Correlativos(){
        return $this->belongsTo(hospital_correlaivo::class);
    }*/

    public function index()
    {
        $pHospitales = \DB::table('hospitales')->OrderBy('nombre')->get();
        return view('hospitales.index', [
            'pHospitales' => $pHospitales
        ]);
    }

    public function create()
    {
        return view('hospitales.create');
    }

    public function store(Request $request)
    {
        
        $validData = $request->validate([
            'nombre' => 'required'
        ]);

        $hospital = new Hospital();
        $hospital->nombre = $validData['nombre'];
        $hospital->direccion = $request->direccion;
        $hospital->telefonos = $request->telefonos;
        $hospital->contacto = $request->contacto;
        $hospital->principal_agenda = $request->principal_agenda;
        if (isset($request->referencia)) {
            $hospital->referencia = 'S';
        }else{
            $hospital->referencia = 'N';
        }
        if (isset($request->estado)) {
            $hospital->estado = 'A';
        }else{
            $hospital->estado = 'I';
        }
        if (isset($request->principal_agenda)) {
            $hospital->principal_agenda = 'S';
        }else{
            $hospital->principal_agenda = 'N';
        }
        $hospital->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('hospitales')->with('message','Hospital grabado con exito');
        // Session::flash('success', 'Hospital Guardado con exito !!!' );
        // return redirect(route('hospitales'));
        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    public function edit()
    {
        $id         = $_POST['id'];
        $hospitalId = Crypt::decrypt($id);
        $registro   = Hospital::findOrFail($hospitalId);
        return Response::json($registro);
    }

    public function update(Request $request)
    {
        $validData = $request->validate([
            'enombre' => 'required'
        ]);

        $id         = $_POST['eid'];
        $hospitalId = Crypt::decrypt($id);

        $hospital = Hospital::findOrFail($hospitalId);
        $hospital->nombre    = $validData['enombre'];
        $hospital->direccion = $request->edireccion;
        $hospital->telefonos = $request->etelefonos;
        $hospital->contacto  = $request->econtacto;
        if (isset($request->eprincipal_agenda)) {
            $hospital->principal_agenda = 'S';
        }else{
            $hospital->principal_agenda = 'N';
        }
        if (isset($request->ereferencia)) {
            $hospital->referencia = 'S';
        }else{
            $hospital->referencia = 'N';
        }
        if (isset($request->eestado)) {
            $hospital->estado = 'A';
        }else{
            $hospital->estado = 'I';
        }
        $hospital->save();

        //Session::flash('success', 'Se editó el medico con éxito.');

        //return Redirect::route('hospitales')->with('message','Hospital grabado con exito');
        // Session::flash('success', 'Hospital Actualizado con exito !!!' );
        // return redirect(route('hospitales'));
        $message = array(
            'message' => 'Registro actualizado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    /*public function show($id){
        
        $pHospital = Hospital::findOrFail($id);
        $pHospitalCorr = \DB::table('hospital_Correlativos')->where('hospital_id', $pHospital->id)->get();
        //dd($pHospitalCorr);
        return view('hospitales.show', [
            'pHospital' => $pHospital,
            'pCorrelativos' => $pHospitalCorr
        ]);
    }*/
}
