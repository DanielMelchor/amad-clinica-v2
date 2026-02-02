<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use Response;
use Session;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Models\Correlativo;
use App\Models\Empresa;

class CorrelativoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
    	if(auth()->user()->hasRole('Super Admin')){
            $empresas = Empresa::where('estado', 1)->get();
            $correlativos = DB::table('correlativos as c')
                            ->join('empresas as e', 'c.empresa_id', 'e.id')
                            ->select('c.id', 'c.empresa_id', 'c.correlativo', DB::raw('CASE when c.tipo = "P" then "Pacientes" else "Admisiones" END as tipo'), 'e.nombre_comercial')
                            ->get();
        }else{
            $empresas = Empresa::where('estado', 1)->where('id', Auth::user()->empresa_id)->get();
            $correlativos = DB::table('correlativos as c')
                            ->join('empresas as e', 'c.empresa_id', 'e.id')
                            ->where('empresa_id', Auth::user()->empresa_id)
                            ->select('c.id', 'c.empresa_id', 'c.correlativo', DB::raw('CASE when c.tipo = "P" then "Pacientes" else "Admisiones" END as tipo'), 'e.nombre_comercial')
                            ->get();
            // $pCorrelativos = Correlativo::where('empresa_id', Auth::user()->empresa_id)->get();
        }
    	return view('correlativos.index', compact('correlativos', 'empresas'));
    }

    public function create(){
    	$empresas = Empresa::where('estado', 1)->get();
        return view('correlativos.create');
    }

    public function store(Request $request){
        $validData = $request->validate([
            'tipo_id' => 'required',
            'correlativo' => 'required'
        ]);

        if (isset($request['empresa_id'])) {
            $empresa_id = $request['empresa_id'];
        }else{
            $empresa_id = Auth::user()->empresa_id;
        }

        DB::beginTransaction();

        try {
            $existe = Correlativo::where('empresa_id', $empresa_id)->where('tipo', $validData['tipo_id'])->count();

            if ($existe >= 1) {
                $message = array(
                    'message' => 'Configuración ya existe !!!',
                    'type'    => 'error'
                );
            }else{
                $registro = new Correlativo;
                if (isset($request['empresa_id'])) {
                    $registro->empresa_id = $empresa_id;
                }else{
                    $registro->empresa_id = $empresa_id;
                }
                
                $registro->tipo = $validData['tipo_id'];
                $registro->correlativo = $validData['correlativo'];
                $registro->save();
                DB::commit();

                $message = array(
                    'message' => 'Registro almacenado con exito !!!',
                    'type'    => 'success'
                );
            }
        }catch (\Exception $e) {
            DB::rollBack();
            $message = array(
                'message' => 'Error al almacenar la información !!!',
                'type'    => 'error'
            );
        }

        // return redirect()->route('correlativos')->with($message);
        return redirect()->back()->with($message);

        // $existe = Correlativo::where('empresa_id', Auth::user()->empresa_id)->where('tipo', $validData['tipo_id'])->count();

        // if ($existe >= 1) {
        // 	return Redirect::back()->withErrors('Correlativo ya existe');
        // }else{
        // 	$corr = new Correlativo;
	    //     $corr->empresa_id = Auth::user()->empresa_id;
	    //     $corr->tipo = $validData['tipo_id'];
	    //     $corr->correlativo = $validData['correlativo'];
	    //     $corr->save();

        //     // Session::flash('success', 'Correlativo Guardado con exito !!!' );
        //     // return redirect(route('correlativos'));

        //     $message = array(
        //         'message' => 'Registro almacenado con exito !!!',
        //         'type'    => 'success'
        //     );

        //     return redirect()->back()->with($message);

	    //     //return Redirect::route('correlativos')->with('message','Correlativo grabado con exito');    
        // }
    }

    public function edit($id){
    	$correlativoId = Crypt::decrypt($id);
        $correlativo = Correlativo::findOrFail($correlativoId);
    	return view('correlativos.edit', compact('correlativo'));
    }

    public function update(Request $request){
        $validData = $request->validate([
            'editcorrelativo' => 'required'
        ]);

        $correlativoId = Crypt::decrypt($request['editid']);
        $corr = Correlativo::findOrFail($correlativoId);
        //$corr->empresa_id = Auth::user()->empresa_id;
        //$corr->tipo = $validData['tipo_id'];
        $corr->correlativo = $validData['editcorrelativo'];
        $corr->save();

        //return Redirect::route('correlativos')->with('message','Correlativo grabado con exito');
        // Session::flash('success', 'Correlativo Actualizado con exito !!!' );
        // return redirect(route('correlativos'));
        $message = array(
            'message' => 'Registro actualizado con exito !!!',
            'type'    => 'success'
        );

        return redirect()->back()->with($message);
    }

    public function trae_correlativo(){
        $id = $_POST['id'];
        $correlativoId = Crypt::decrypt($id);

        $registro = Correlativo::where('id', $correlativoId)->first();

        return Response::json($registro);
    }
}
