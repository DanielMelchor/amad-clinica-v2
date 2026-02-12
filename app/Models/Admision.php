<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Admision extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'admisiones';

    protected $fillable = ['id', 'empresa_id', 'agenda_id', 'fecha', 'serie', 'admision', 'paciente_id', 'edad', 'medico_id', 'hospital_id', 'admision_tercero', 'referido_por', 'aseguradora_id', 'poliza_no', 'aseguradora_aut_no', 'coaseguro', 'copago', 'pagado_por_aseguradora', 'fecha_inicio', 'fecha_fin', 'resumen_egreso', 'estado', 'encabezado_revisado', 'inicio_atencion_medica', 'final_atencion_medica', 'atencion_medica', 'segundos_atencion_medica'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];

    public function scopeAdmisionNo(Builder $query, string $AdmisionNo)
    {
        if ($AdmisionNo) {
            return $query->where('admision', 'like', '%'.$AdmisionNo.'%');
        }
    }
}
