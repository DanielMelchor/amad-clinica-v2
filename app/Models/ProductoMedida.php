<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProductoMedida extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'producto_medidas';
    
    protected $fillable = ['producto_id', 'unidad_medida_id', 'cantidad'];
    
    protected $hidden = ['id', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    public static function obtenerFactor($productoId, $medidaId)
    {
        $registro = self::where('producto_id', $productoId)
                        ->where('unidad_medida_id', $medidaId)
                        ->first();

        return $registro ? $registro->cantidad : 1;
    }
}
