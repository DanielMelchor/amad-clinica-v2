<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DocumentoDetalle extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'documentoventa_detalles';
    
    protected $fillable = ['documentoventa_maestro_id', 'detalle_movimiento_id', 'tipo_facturacion', 'cantidad', 'cantidad_medida', 'cantidad_x_medida',  'precio_unitario', 'precio_bruto', 'descuento', 'recargo', 'precio_neto', 'precio_base', 'precio_impuesto', 'estado'];
    
    protected $hidden = ['id', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    public function Maestro()
    {
        return $this->belongsTo('App\DocumentoMaestro');
    }
}
