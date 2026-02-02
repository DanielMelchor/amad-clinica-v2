<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class AdmisionConsulta extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'admision_consultas';
    
    protected $fillable = ['id', 'admision_vital_id', 'paciente_id', 'subjetivo', 'objetivo', 'impresion_clinica', 'plan', 'tratamiento', 'peso', 'talla', 'pulso', 'temperatura', 'respiracion', 'presion_sistolica', 'presion_diastolica', 'bmi'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];
}
