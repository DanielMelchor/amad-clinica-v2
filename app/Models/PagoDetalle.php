<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PagoDetalle extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'pago_detalles';
    
    protected $fillable = ['forma_pago', 'banco_id', 'cuenta_no', 'documento_no', 'autoriza_no', 'monto', 'motivo_rechazo_id', 'rechazo_observaciones', 'estado'];
    
    protected $hidden = ['id', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    public function PagoMaestro(){
        return $this->belongsTo(PagoMaestro::class, 'foreign_key', 'pago_maestro_id');
    }
}
