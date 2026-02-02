<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Agenda extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'agendas';
    
    protected $fillable = ['empresa_id', 'sala_id', 'medico_id', 'hospital_id', 'paciente_id', 'maestro_protocolo_id', 'fecha_inicio', 'fecha_final', 'nombre_completo', 'telefonos', 'observaciones', 'fecha_bloqueo', 'usuario_bloqueo', 'observaciones_bloqueo', 'estado', 'id'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];
}
