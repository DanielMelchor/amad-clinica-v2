<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Receta_Medico extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'receta_medicos';
    
    protected $fillable = ['medico_id', 'pagina_alto', 'pagina_ancho', 'orientacion', 'unidad_medida', 'dia_x', 'dia_y', 'mes_x', 'mes_y', 'anio_x', 'anio_y', 'paciente_x', 'paciente_y', 'tratamiento_x', 'tratamiento_y', 'proxima_cita_x', 'proxima_cita_y'];
    
    protected $hidden = ['id', 'created_at', 'updated_at', 'created_by', 'updated_by'];
}
