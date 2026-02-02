<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MaestroProtocolo extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'maestro_protocolos';
    
    protected $fillable = ['empresa_id', 'config_maestro_protocolo_id', 'paciente_id', 'medico_id', 'fecha_nacimiento', 'edad', 'diagnostico_id', 'cuerpo_parte_id', 'lugar_tratamiento_id', 'aseguradora_id', 'poliza_no', 'proveedor_medicamento', 'inmunoterapia', 'tipo_tratamiento', 'ciclo', 'fecha_ciclo', 'estado'];
    
    protected $hidden = ['id', 'created_at', 'updated_at', 'created_by', 'updated_by'];
}
