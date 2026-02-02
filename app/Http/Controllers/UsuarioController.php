<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\role_has_permission;
use Auth;
use DB;
use App\Models\Caja;
use App\Models\Empresa;
use App\Models\Sala;
use App\Models\User;

class UsuarioController extends Controller
{
    public function index(){
        $listado = DB::table('users as u')
                   ->leftjoin('empresas as e', 'u.empresa_id', 'e.id')
                   ->leftjoin('cajas as c', 'u.caja_id', 'c.id')
                   ->select('u.id', 'u.username', 'u.name', 'u.estado', 'e.nombre_comercial', 'c.nombre_maquina')
                   ->get();
        return view('users.index', compact('listado'));
    }

    public function create(){
        $empresas = Empresa::where('estado', 'A')->get();
        $cajas    = Caja::where('estado', 'A')->get();
        $salas        = Sala::where('empresa_id', Auth::user()->empresa_id)->get();
        $roles        = Role::all();
        $salas_x_usuario = [];
        $roles_x_usuario = [];

        return view ('users.create', compact('empresas', 'cajas', 'salas', 'roles', 'salas_x_usuario', 'roles_x_usuario'));

    }

    public function store(Request $request){
        $validData = $request->validate([
                      'username'   => 'required',
                      'name'       => 'required'
                     ]);

        $registro             = new User();
        $registro->name       = $validData['name'];
        $registro->username   = $validData['username'];
        $registro->empresa_id = $request->empresa_id;
        if (isset($request->caja_id)) {
            $registro->caja_id = $request->caja_id;
        }
        if (isset($request->sala_principal_id)) {
            $registro->sala_principal_id = $request->sala_principal_id;
        }
        if (isset($request->estado)) {
            $registro->estado = $request->estado;
        }
        $registro->email = '@';
        $registro->password = md5('12345678');
        $registro->save();

    }

    public function perfil(){
        return view('users/profile');
    }

    public function updateProfilePicture(Request $request) 
    {
        $request->validate([
          'urlfoto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $file = $request->file('urlfoto');
        $filename = $file->getClientOriginalExtension();
        
        $user = Auth::user();
        $file->move(public_path('profile_pictures'),$filename);// subimos al servidor
        $user->profile_picture = $filename; // guardamos el nombre en la bd
        $user->save(); // guardamos los cambios.

        return redirect()->route('perfil');
    }

    public function reset(){
        return view('permisos.reset');
    }

}
