<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PagoMaestro extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'pago_maestros';
    
    protected $fillable = ['empresa_id', 'caja_id', 'caja_corte_id', 'tipo_documento_id', 'resolucion_id', 'fecha_emision', 'serie', 'correlativo', 'motivo_anulacion_id', 'anulacion_observaciones', 'anulacion_usuario_id', 'anulacion_fecha', 'estado'];
    
    protected $hidden = ['id', 'created_at', 'updated_at', 'created_by', 'updated_by'];


    public function PagoDetalles(){
        return $this->hasMany(PagoDetalle::class);
    }

    public function PagoDocumentos(){
        return $this->hasMany(PagoDocumento::class);
    }
}
