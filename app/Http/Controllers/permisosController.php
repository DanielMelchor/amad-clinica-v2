<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Auth;
use DB;
use Response;
use Redirect;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Caja;
use App\Models\Empresa;
use App\Models\role_has_permission;
use App\Models\Sala;
use App\Models\SalaxUsuario;
use App\Models\User;

class permisosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // *************************************************************************************** //
    // *********************************** Roles ********************************************* //
    // *************************************************************************************** //
    public function role_index(){
        $listado = Role::get();
        return view('permisos.role_index', compact('listado'));
    }

    public function role_store(Request $request){
        $validData = $request->validate([
            'name' => 'required|unique:roles'
        ]);

        $registro = new Role();
        $registro->name = $validData['name'];
        $registro->save();

        //Session::flash('success', 'Admisión Guardada con exito !!!' );

        //return redirect::route('roles_listado');
        $message = array(
            'message' => '! Role guardado con exito !',
            'type'    => 'success'
        );

        return redirect()->back()->with($message);
    }

    public function role_edit($id){
        $roleId = Crypt::decrypt($id);
        $role = Role::findOrFail($roleId);
        $permisos = Permission::all();
        $permisos_x_role = role_has_permission::where('role_id', $roleId)->select('permission_id')->get()->toArray();

        return view('permisos.edit', compact('role', 'permisos', 'permisos_x_role'));  
    }

    public function role_update(Request $request, $id){
        $validData = $request->validate([
            'name' => 'required'
        ]);

        $role = Role::findOrFail($id);
        $role->name = $validData['name'];
        $role->save();

        $permisos = $request->callbacks;

        DB::table('role_has_permissions')->where('role_id', $id)->delete();

        if (isset($permisos)) {
            foreach ($permisos as $key => $permiso) {
                $role_permiso = new role_has_permission();
                $role_permiso->role_id       = $role->id;
                $role_permiso->permission_id = $permiso;
                $role_permiso->save();
            }
        }

        $message = array(
            'message' => '! Role actualizado con exito !',
            'type'    => 'success'
        );

        return redirect()->back()->with($message);
        
    }

    // *************************************************************************************** //
    // *********************************** Permisos ****************************************** //
    // *************************************************************************************** //

    public function permiso_index(){
        $listado = Permission::get();
        return view('permisos.permiso_index', compact('listado'));
    }

    public function permiso_store(Request $request){
        $validData = $request->validate([
            'name' => 'required'
        ]);

        $registro = new Permission();
        $registro->name = $validData['name'];
        $registro->save();

        //Session::flash('success', 'Admisión Guardada con exito !!!' );

        //return redirect::route('roles_listado');
        // return redirect()->back()->with(['success' => 'Permiso Guardado con exito !!!']);
        $message = array(
            'message' => 'Permiso guardado con exito !!!',
            'type'    => 'success'
        );

        return redirect()->back()->with($message);
    }

    public function permiso_update(Request $request){
        $validData = $request->validate([
            'editid'   => 'required',
            'editname' => 'required'
        ]);


        $registro = Permission::findOrFail($validData['editid']);
        $registro->name = $validData['editname'];
        $registro->save();

        //Session::flash('success', 'Admisión Guardada con exito !!!' );

        //return redirect::route('roles_listado');
        // return redirect()->back()->with(['success' => 'Permiso Actualizado con exito !!!']);
        $message = array(
            'message' => 'Permiso actualizado con exito !!!',
            'type'    => 'success'
        );

        return redirect()->back()->with($message);
    }

    public function trae_permiso(){
        $id = $_REQUEST['id'];

        $permiso = Permission::findOrFail($id);

        return response::json($permiso);
    }

    // *************************************************************************************** //
    // *********************************** Usuarios ****************************************** //
    // *************************************************************************************** //

    public function usuario_index(){
        // $listado = User::select('id', 'username', 'name', 'estado')->get();
        $listado = DB::table('users as u')
                   ->join('model_has_roles as mhr', 'u.id', 'mhr.model_id')
                   ->join('roles as r', 'mhr.role_id', 'r.id')
                   ->select('u.id', 'u.username', 'u.name', 'u.estado', 'r.name as role_name')
                   ->get();
        return view('permisos.usuario_index', compact('listado'));
    }

    public function usuario_create(){
        $empresas     = Empresa::all();
        $salas        = Sala::where('empresa_id', Auth::user()->empresa_id)->get();
        $roles        = Role::all();
        $salas_x_usuario = [];
        $roles_x_usuario = [];
        //$documentos   = Documento::where('estado',1)->get();

        return view('permisos.usuario_create', compact('empresas', 'salas', 'roles', 'salas_x_usuario', 'roles_x_usuario'));   
    }

    public function usuario_store(Request $request){
        $validData = $request->validate([
            'name'     => 'required',
            'username' => 'required|unique:users,username',
            'empresa_id' => 'required'
        ], [
            'username.unique' => 'El usuario ya está registrado. Por favor elige otro.',
            'username.required' => 'El campo usuario es obligatorio.',
            'name.required' => 'El nombre es obligatorio.'
        ]);

        $registro = new User();
        $registro->name              = $validData['name'];
        $registro->username          = $validData['username'];
        $registro->empresa_id        = $validData['empresa_id'];
        $registro->caja_id           = ($request->caja_id == 'null' || !$request->caja_id) ? null : $request->caja_id;
        $registro->sala_principal_id = ($request->sala_principal_id == 'null' || !$request->sala_principal_id) ? null : $request->sala_principal_id;
        $registro->password          = md5('123456');
        $registro->estado            = 1;
        $registro->save();

        $salas = $request->callbacks;
        $roles = $request->callbacksr;

        if (isset($salas)) {
            foreach ($salas as $key => $sala) {
                $usuario_sala = new SalaxUsuario();
                $usuario_sala->sala_id   = $sala;
                $usuario_sala->user_id   = $registro->id;
                $usuario_sala->save();
            }
        }

        if (isset($roles)) {
            foreach ($roles as $key => $r) {
                // Buscamos el rol por ID antes de asignarlo
                $roleModel = Role::findById($r, 'web'); 
                $registro->assignRole($roleModel);
            }
        }

        $message = array(
            'message' => '! Usuario creado con Exito !',
            'type'    => 'success'
        );
        return redirect()->back()->with($message);
    }

    public function usuario_edit($id){
        $registro = User::where('id',$id)->select('id', 'name', 'username', 'empresa_id', 'caja_id', 'sala_principal_id', 'estado')->first();
        $empresas     = Empresa::all();
        $cajas        = Caja::where('empresa_id', $registro->empresa_id)->get();
        $salas        = Sala::where('empresa_id', $registro->empresa_id)->get();
        $roles        = Role::all();
        $salas_x_usuario = SalaxUsuario::where('user_id', $id)->get();
        $roles_x_usuario = $registro->roles()->get();
        //$documentos   = Documento::where('estado',1)->get();
        //$documentos_x_usuario = Usuario_documento::where('usuario_id', $id)->get();

        return view('permisos.usuario_edit', compact('registro', 'empresas', 'cajas', 'salas', 'salas_x_usuario', 'roles', 'roles_x_usuario'));
    }

    public function usuario_update(Request $request, $id){
        //dd($request);
        $validData = $request->validate([
            'name'       => 'required',
            'username'   => 'required',
            'empresa_id' => 'required'
        ]);


        $registro = User::where('id', $id)->first();
        $registro->name           = $validData['name'];
        $registro->username       = $validData['username'];
        $registro->empresa_id     = $validData['empresa_id'];
        $registro->caja_id        = $request->caja_id;
        $registro->save();

        $salas = $request->callbacks;
        $roles = $request->callbacksr;

        DB::table('salas_x_usuarios')->where('user_id', $id)->delete();

        if (isset($salas)) {
            foreach ($salas as $key => $s) {
                //dd($s);
                $sala_usuario               = new SalaxUsuario();
                $sala_usuario->user_id      = $id;
                $sala_usuario->sala_id      = $s;
                $sala_usuario->save();
            }
        }

        DB::table('model_has_roles')->where('model_id', $id)->delete();

        if (isset($roles)) {
            foreach ($roles as $key => $r) {
                // Buscamos el rol por ID antes de asignarlo
                $roleModel = Role::findById($r, 'web'); 
                $registro->assignRole($roleModel);
            }
        }

        $message = array(
            'message' => 'Usuario actualizado con exito !!!',
            'type'    => 'success'
        );

        return redirect()->back()->with($message);

        //return redirect(route('permiso_listado'))->with('success', 'Usuario acutalizado con exito!!!');
        // return redirect()->back()->with(['success' => 'Usuario Actualizado con exito !!!']);
    }
}
