<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Hospital extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'hospitales';
    
    protected $fillable = ['id', 'nombre', 'direccion', 'telefono', 'contacto', 'principal_agenda', 'referencia', 'estado'];
    
    protected $hidden = ['empresa_id', 'created_at', 'updated_at', 'created_by', 'updated_by'];
}
