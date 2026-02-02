<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Session;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Models\TipoDocumento;
use App\Models\Inventario_Transaccion;

class TipoDocumentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $listado = TipoDocumento::all();
        $inv_trn = Inventario_Transaccion::where('empresa_id', Auth::user()->empresa_id)->where('estado', 'A')->get();
        return view('tipo_documentos.index', compact('listado', 'inv_trn'));
    }

    public function create(){
    	$inv_trn = Inventario_Transaccion::where('empresa_id', Auth::user()->empresa_id)->where('estado', 'A')->get();
        return view('tipo_documentos.create', compact('inv_trn'));
    }

    public function store(Request $request){
        $validData = $request->validate([
            'descripcion' => 'required',
            'signo'       => 'required'
        ]);

        $Tipo_Documento = new TipoDocumento();
        $Tipo_Documento->descripcion = $validData['descripcion'];
        $Tipo_Documento->signo       = $validData['signo'];
        $Tipo_Documento->inventario_transaccion_id = $request->inventario_transaccion_id;
        $Tipo_Documento->tipo_interno = $request->tipo_interno;

        if(isset($request->estado)){
            $Tipo_Documento->estado = 1;
        }else{
            $Tipo_Documento->estado = 0;
        }

        $Tipo_Documento->save();

        $message = array(
            'message' => 'Registro almacenado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    public function edit(){
    	$id       = $_POST['id'];
        $tipoId   = Crypt::decrypt($id);
        $registro = TipoDocumento::findOrFail($tipoId);
        return Response::json($registro);
    }

    public function update(Request $request){
        $validData = $request->validate([
            'edescripcion' => 'required',
            'esigno'       => 'required'
        ]);

        $id       = $request['eid'];
        $tipoId   = Crypt::decrypt($id);

        $Tipo_Documento = TipoDocumento::findOrFail($tipoId);
        $Tipo_Documento->descripcion = $validData['edescripcion'];
        $Tipo_Documento->signo       = $validData['esigno'];
        $Tipo_Documento->inventario_transaccion_id = $request->einventario_transaccion_id;
        $Tipo_Documento->tipo_interno = $request->etipo_interno;

        if(isset($request->eestado)){
            $Tipo_Documento->estado = 1;
        }else{
            $Tipo_Documento->estado = 0;
        }

        $Tipo_Documento->save();

        $message = array(
            'message' => 'Registro actualizado con exito !!!',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    public function get_documentos(){
        $listado = TipoDocumento::where('estado', 'A')->select('id', 'descripcion', 'orden')->orderBy('orden')->get();
        return Response::json($listado);
    }
}
