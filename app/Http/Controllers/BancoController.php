<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Redirect;
use Response;
use Session;
use App\Models\Banco;

class BancoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $pBancos = banco::paginate(15);
        return view('bancos.index', compact('pBancos'));
    }

    public function create(){
    	return view('bancos.create');
    }

    public function store(Request $request){
        $validData = $request->validate([
            'nombre' => 'required',
            'tipo_referencia' => 'required'
        ]);
    	$banco = new banco();
    	$banco->nombre = $validData['nombre'];
        $banco->tipo_referencia = $validData['tipo_referencia'];

        if(isset($request->estado)){
    		$banco->estado = 1;
    	}else{
    		$banco->estado = 0;
    	}

    	$banco->save();

        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);

    	//return Redirect::route('bancos')->with('message','Banco grabado con exito');
        // if ($banco->tipo_referencia == 'B') {
        //     Session::flash('success', 'Banco Guardado con exito !!!' );
        // }else{
        //     Session::flash('success', 'Casa Emisora Guardada con exito !!!' );
        // }

        // return redirect(route('bancos'));
    }

    public function edit(){    	
    	$id     = $_POST['id'];
        $bancoId = Crypt::decrypt($id);
        $registro = Banco::findOrFail($bancoId);
        return Response::json($registro);
    }

    public function update(Request $request){
        $validData = $request->validate([
            'enombre' => 'required',
            'etipo_referencia' => 'required'
        ]);


    	$id      = $_POST['eid'];
        $bancoId = Crypt::decrypt($id);
        $banco   = Banco::findOrFail($bancoId);
    	$banco->nombre = $validData['enombre'];
        $banco->tipo_referencia = $validData['etipo_referencia'];
    	if(isset($request->eestado)){
    		$banco->estado = 1;
    	}else{
    		$banco->estado = 0;
    	}

    	$banco->save();

    	//return Redirect::route('bancos')->with('message','Banco grabado con exito');
        // if ($banco->etipo_referencia == 'B') {
        //     Session::flash('success', 'Banco Actualizado con exito !!!' );
        // }else{
        //     Session::flash('success', 'Casa Emisora Actualizada con exito !!!' );
        // }
        
        // return redirect(route('bancos'));

        $message = array(
            'message' => 'Registro actualizado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    public function trae_formas_pago(){
        // print_r('Parametro '.$_POST['parametro']);

        $resultado = DB::table('bancos')
                     ->where('estado', 1)
                     ->where('tipo_referencia', $_POST['parametro'])
                     ->select('id', 'nombre')
                     ->get();
        return Response::json($resultado);
    }
}
