<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Auth;
use Session;
use Response;
use App\Models\Inventario_Transaccion;

class InventarioTrnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	$listado = Inventario_Transaccion::all();
    	return view('inv_transacciones.index', compact('listado'));
    }

    public function store(Request $request){
    	$validData = $request->validate([
            'descripcion'      => 'required',
            'signo'            => 'required',
            'tipo_transaccion' => 'required'
        ]);

        $transaccion = new Inventario_Transaccion();
        $transaccion->empresa_id  = Auth::user()->empresa_id;
        $transaccion->descripcion = $validData['descripcion'];
        $transaccion->signo       = $validData['signo'];
        $transaccion->tipo_transaccion = $validData['tipo_transaccion'];

        if (isset($request->estado)) {
        	$transaccion->estado = 1;
        }else{
        	$transaccion->estado = 0;
        }

        $transaccion->save();

        Session::flash('success', 'Transacción Guardada con exito !!!' );
        return redirect(route('invtransacciones'));

    }

    public function edit(){
    	$id = $_POST['id'];
        $trnId = Crypt::decrypt($id);
    	$transaccion = Inventario_Transaccion::findOrFail($trnId);
    	return Response::json($transaccion);
    }

    public function update(Request $request){
    	$validData = $request->validate([
            'edescripcion'      => 'required',
            'esigno'            => 'required',
            'etipo_transaccion' => 'required'
        ]);

        $transaccion = Inventario_Transaccion::findOrFail($request->eid);
        $transaccion->descripcion      = $validData['edescripcion'];
        $transaccion->signo            = $validData['esigno'];
        $transaccion->tipo_transaccion = $validData['etipo_transaccion'];
        if (isset($request->eestado)) {
        	$transaccion->estado = 1;
        }else{
        	$transaccion->estado = 0;
        }

        $transaccion->save();

        Session::flash('success', 'Transacción Actualizada con exito !!!' );
        return redirect(route('invtransacciones'));
    }
}
