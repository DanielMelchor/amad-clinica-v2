<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Bodega extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'bodegas';
    
    protected $fillable = ['empresa_id', 'id', 'descripcion', 'estado'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];

    public function configuraciones()
    {
        return $this->hasMany(BodegaProductoConfig::class);
    }

    // Relación muchos a muchos con Productos a través de la configuración
    public function productosConfigurados()
    {
        return $this->belongsToMany(Producto::class, 'bodega_producto_config')
                    ->withPivot('stock_minimo', 'stock_maximo', 'punto_reorden', 'estado')
                    ->withTimestamps();
    }
}
