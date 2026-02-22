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
    
    protected $fillable = ['empresa_id', 'sala_id', 'medico_id', 'hospital_id', 'paciente_id', 'maestro_protocolo_id', 'fecha_inicio', 'fecha_final', 'nombre_completo', 'telefonos', 'observaciones', 'fecha_bloqueo', 'usuario_bloqueo', 'observaciones_bloqueo', 'estado', 'id', 'paciente_en_clinica'];

    protected $appends = ['espera_detenida', 'tiempo_espera_color'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];

    // App\Models\Agenda.php
    public function sala() { return $this->belongsTo(Sala::class); }
    public function paciente() { return $this->belongsTo(Paciente::class); }
    public function usuarioBloqueo() { return $this->belongsTo(User::class, 'usuario_bloqueo'); }
    public function admision() { return $this->hasOne(Admision::class, 'agenda_id'); }
    public function getTiempoEsperaColorAttribute()
    {
        if (!$this->fecha_en_clinica) return 'success';

        // Convertimos el string HH:mm a minutos totales para evaluar el color
        // Nota: Asumimos que tiempo_espera ya viene calculado desde el Query
        $partes = explode(':', $this->tiempo_espera ?? '00:00');
        $minutosTotales = ($partes[0] * 60) + $partes[1];

        if ($minutosTotales >= 45) return 'danger';  // Rojo
        if ($minutosTotales >= 20) return 'warning'; // Amarillo
        return 'success';                            // Verde
    }
    public function getEsperaDetenidaAttribute()
    {
        // Si hay admisión y atención_medica es != 0, el tiempo se detiene
        return $this->admision && $this->admision->atencion_medica != 0;
    }
}
