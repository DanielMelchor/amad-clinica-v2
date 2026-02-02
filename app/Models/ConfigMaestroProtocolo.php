<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ConfigMaestroProtocolo extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'config_maestro_protocolos';
    
    protected $fillable = ['empresa_id', 'paciente_id', 'medico_id', 'fecha_nacimiento', 'edad', 'diagnostico_id', 'cuerpo_parte_id', 'lugar_tratamiento_id', 'aseguradora_id', 'poliza_no', 'proveedor_medicamento', 'inmunoterapia', 'tipo_tratamiento', 'cantidad_ciclos', 'frecuencia_ciclos', 'fecha_inicio', 'estado'];
    
    protected $hidden = ['id', 'created_at', 'updated_at', 'created_by', 'updated_by'];
}
