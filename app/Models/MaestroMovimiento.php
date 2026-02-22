<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\InventarioTransaccion;

class MaestroMovimiento extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'maestro_movimientos';
    
    protected $fillable = ['empresa_id', 'inventario_transaccion_id', 'signo', 'correlativo', 'anio', 'maestro_documento_id', 'bodega_origen_id', 'bodega_destino_id', 'proveedor_id', 'nit', 'tipo_documento_id', 'serie', 'numero_documento', 'cxp_documento_afecto_id', 'fecha_emision', 'dias_credito', 'fecha_vencimiento', 'total', 'estado', 'id'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];

    public function transaccion()
    {
        // Relación asumiendo que inventario_transaccion_id es la llave foránea
        return $this->belongsTo(InventarioTransaccion::class, 'inventario_transaccion_id');
    }

    // Relación con Proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    // Relación con Tipo de Documento
    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }

    // Relación con Bodega
    public function bodega()
    {
        return $this->belongsTo(Bodega::class, 'bodega_origen_id');
    }

    // Relación con los Detalles (Artículos)
    public function detalles()
    {
        return $this->hasMany(DetalleMovimiento::class, 'maestro_movimiento_id');
    }
}
