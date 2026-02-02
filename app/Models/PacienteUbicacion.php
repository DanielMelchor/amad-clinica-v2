<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PacienteUbicacion extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'paciente_ubicaciones';
    
    protected $fillable = ['id', 'paciente_id', 'tipo_ubicacion_id', 'direccion', 'municipio_id', 'departamento_id', 'pais_id'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];
}
