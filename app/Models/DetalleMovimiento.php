<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DetalleMovimiento extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'detalle_movimientos';
    
    protected $fillable = ['id', 'maestro_movimiento_id', 'admision_id', 'maestro_protocolo_id', 'maestro_documento_id', 'producto_id', 'descripcion', 'unidad_medida_id', 'producto_caracteristica_id', 'cantidad', 'cantidad_medida', 'cantidad_x_medida', 'precio_unitario', 'precio_bruto', 'descuento', 'recargo', 'precio_neto', 'precio_base', 'precio_impuesto', 'precio_total', 'copago', 'deducible', 'precio_cliente', 'precio_aseguradora', 'estado', 'signo'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];

    public function unidadMedida()
    {
        // El segundo parámetro debe ser la llave foránea en tu tabla detalle_movimientos
        return $this->belongsTo(UnidadMedida::class, 'unidad_medida_id');
    }

    public function maestro()
    {
        // Ajusta 'maestro_id' al nombre real de tu llave foránea
        return $this->belongsTo(MaestroMovimiento::class, 'maestro_movimiento_id');
    }

    public function producto() {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
