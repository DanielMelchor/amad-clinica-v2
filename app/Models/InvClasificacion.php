<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class InvClasificacion extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'invclasificaciones';
    
    protected $fillable = ['id', 'nombre', 'definir_medidas', 'definir_dosis', 'definir_caracteristica', 'estado'];
    
    protected $hidden = ['empresa_id', 'created_at', 'updated_at', 'created_by', 'updated_by'];
}
