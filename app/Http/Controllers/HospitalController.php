<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use DB;
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

        DB::beginTransaction();
        try{
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
                $hospital->estado = 1;
            }else{
                $hospital->estado = 0;
            }
            if (isset($request->principal_agenda)) {
                $hospital->principal_agenda = 'S';
            }else{
                $hospital->principal_agenda = 'N';
            }
            $hospital->save();

            DB::commit();

            return back()->withInput()->with([
                'message' => '! Registro almacenado con exito !',
                'type' => 'success'
            ]);

        } catch (\Exception $e){
            DB::rollBack();
            return back()->withInput()->with([
                'message' => 'Error al guardar: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function edit($hospital_id)
    {
        $registro   = Hospital::findOrFail(Crypt::decryptString($hospital_id));
        return Response::json($registro);
    }

    public function update(Request $request)
    {
        $validData = $request->validate([
            'enombre' => 'required'
        ]);

        $id         = $_POST['eid'];
        $hospitalId = Crypt::decryptString($id);

        DB::beginTransaction();
        try{
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
                $hospital->estado = 1;
            }else{
                $hospital->estado = 0;
            }
            $hospital->save();

            DB::commit();

            return back()->withInput()->with([
                'message' => '! Registro actualizado con exito !',
                'type' => 'success'
            ]);

        } catch (\Exception $e){
            DB::rollBack();
            return back()->withInput()->with([
                'message' => 'Error al guardar: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
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
