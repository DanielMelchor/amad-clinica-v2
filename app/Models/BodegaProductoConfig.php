<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodegaProductoConfig extends Model
{
    use HasFactory;

    protected $table = 'bodega_producto_config';

    protected $fillable = [
        'bodega_id',
        'producto_id',
        'stock_minimo',
        'stock_maximo',
        'punto_reorden',
        'estado',
        'created_by',
        'updated_by'
    ];

    // Relación con la Bodega
    public function bodega()
    {
        return $this->belongsTo(Bodega::class);
    }

    // Relación con el Insumo (Producto)
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
