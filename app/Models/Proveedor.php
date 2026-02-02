<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Proveedor extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'proveedores';
    
    protected $fillable = ['empresa_id', 'razon_social', 'nombre_comercial', 'direccion', 'telefonos', 'email', 'condicion', 'dias_credito', 'estado', 'id'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];
}
