<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AdmisionAtencion extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'admision_atenciones';
    
    protected $fillable = ['id', 'admision_id', 'tipo_atencion_id', 'csubjetivo', 'cobjetivo', 'cimpresion_clinica', 'cplan', 'ctratamiento_id', 'pprocedimiento_id', 'ptolerancia', 'ppremedicacion', 'ppatologo', 'panestesiologo', 'indicacion', 'hallazgos', 'diagnostico', 'recomendaciones', 'hfecha_inicio', 'hfecha_fin', 'hresumen', 'estado'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];

}
