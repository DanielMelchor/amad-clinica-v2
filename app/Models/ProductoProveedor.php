<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProductoProveedor extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'producto_proveedores';
    
    protected $fillable = ['producto_id', 'proveedor_id', 'unidad_medida_id', 'precio_inicial', 'fecha_ultima_compra', 'precio_ultima_compra'];
    
    protected $hidden = ['id', 'created_at', 'updated_at', 'created_by', 'updated_by'];
}
