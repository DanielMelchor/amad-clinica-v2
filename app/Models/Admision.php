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

    public function bitacoras()
    {
        return $this->hasMany(AdmisionBitacora::class, 'admision_id');
    }

    public function paciente() {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function medico() {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    public function hospital() {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }

    public function detalles() {
        return $this->hasMany(DetalleMovimiento::class, 'admision_id');
    }
}
