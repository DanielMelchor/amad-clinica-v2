<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Paciente extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'pacientes';
    
    protected $fillable = ['id', 'expediente_no', 'expediente_anterior_no', 'codigo_id', 'nombres', 'apellidos', 'apellido_casada', 'nombre_completo', 'genero', 'fecha_nacimiento', 'direccion', 'ciudad', 'telefonos', 'fax', 'celular', 'correo_electronico', 'profesion', 'trabajo_nombre', 'trabajo_telefono', 'estado_civil', 'conyugue_nombre', 'conyugue_ocupacion', 'emergencia_parentesco_id', 'emergencia_nombre', 'emergencia_telefonos', 'referido_por', 'religion', 'aseguradora_id', 'seguro_no', 'recordar_cita', 'antmedico_descripcion', 'antquirurgico_descripcion', 'antalergia_descripcion', 'antgineco_descripcion', 'antfamiliar_descripcion', 'antmedicamento_descripcion', 'tabaco_cnt', 'tabaco_tiempo', 'alcohol_cnt', 'alcohol_tiempo', 'antecedente_importante', 'factura_nit', 'factura_nombre', 'factura_direccion', 'cadena'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];

    protected static function booted()
    {
        static::creating(function ($paciente) {
            $apellidoStr = $paciente->apellido_casada ? " de {$paciente->apellido_casada}" : "";
            $paciente->nombre_completo = "{$paciente->nombres} {$paciente->apellidos}{$apellidoStr}";
        });
    }
}
