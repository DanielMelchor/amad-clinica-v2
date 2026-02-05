<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
// use JeroenNoten\LaravelAdminLte\Traits\AdminLteUser;
use Illuminate\Support\Facades\Auth; // <--- AÑADE ESTA LÍNEA
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    use HasRoles, Notifiable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'medico_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function adminlte_image(){
        return Auth::user()->profile_picture;
    }

    public function adminlte_desc(){
        return 'Administrador';
    }

    public function adminlte_profile_url()
    {
        return 'perfil';
    }

    public function hasProfilePicture(): bool {
        return !is_null($this->attributes['profile_picture']) 
        && !empty($this->attributes['profile_picture']);
    }

    public function getProfilePictureAttribute() {
        return url('profile_pictures/' . $this->attributes['profile_picture']);
     }

    public function getAllPermissionsAttribute()
    {
        // Si el usuario tiene el rol 'Super Admin', devuelve todos los permisos.
        if ($this->hasRole('Super Admin')) {
            return Permission::pluck('name');
        }

        // Si no es Super Admin, devuelve los permisos asignados.
        return $this->getPermissionNames();
    }

    public function getPermissions(){
        // Obtener el usuario autenticado.
        $user = Auth::user();

        // Variable para almacenar la colección de permisos.
        $permissions = collect();

        // Verificar si el usuario tiene el rol 'Super Admin'.
        if ($user->hasRole('Super Admin')) {
            // Si es 'Super Admin', obtener todos los permisos.
            $permissions = Permission::all();
        } else {
            // Si no es 'Super Admin', obtener los permisos del usuario.
            // La función getAllPermissions() de Spatie incluye tanto los permisos directos como los heredados de sus roles.
            $permissions = $user->getAllPermissions();
        }

        // Opcional: Si solo necesitas los nombres de los permisos en un array.
        $permissionNames = $permissions->pluck('name');

        // Puedes pasar la colección de permisos a una vista o devolverla como JSON.
        // return view('mi_vista', compact('permissions'));
        return $permissionNames;
    }

    public function getRouteKeyName()
    {
        return 'username';
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function routeNotificationForTelegram()
    {
        return $this->telegram_chat_id;
    }
}
