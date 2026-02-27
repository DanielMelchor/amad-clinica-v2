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

    public function PagoDocumentos(){
        return $this->hasMany(PagoDocumento::class);
    }

    public function detalles() {
        return $this->hasMany(PagoDetalle::class, 'pago_maestro_id');
    }

    public function tipoDocumento() {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }

    // Accessor para el estado
    public function getEstadoDescripcionAttribute() {
        return $this->estado == '1' ? 'Vigente' : 'Anulado';
    }
}
