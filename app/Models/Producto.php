<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Producto extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'productos';
    
    protected $fillable = ['id', 'empresa_id', 'clasificacion', 'siglas', 'descripcion', 'descripcion_a_mostrar', 'medida_id', 'estado'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];

    public function getFactorConversion($medidaId)
    {
        $registro = self::where('producto_id', $productoId)
                        ->where('unidad_medida_id', $medidaId)
                        ->first();

        // Si existe, devuelve la cantidad (factor), si no, devuelve 1 por defecto
        return $registro ? $registro->cantidad : 1;
    }

    // Relación con Unidad de Medida
    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'medida_id');
    }

    // Relación con Clasificación
    public function clasificacion()
    {
        return $this->belongsTo(InvClasificacion::class, 'inv_clasificacion_id');
    }

    // Relación con Familia
    public function familia()
    {
        return $this->belongsTo(InvFamilia::class, 'inv_familia_id');
    }

    public function saldos()
    {
        // Un artículo puede tener saldos en diferentes bodegas
        return $this->hasMany(InvSaldo::class, 'producto_id');
    }

    public function bodegasConfiguradas()
    {
        return $this->belongsToMany(Bodega::class, 'bodega_producto_config')
                    ->withPivot('stock_minimo', 'stock_maximo', 'punto_reorden', 'estado');
    }

    /**
     * Scope para filtrar la disponibilidad
     */
    public function scopeFiltrar($query, $filtros)
    {
        return $query->when($filtros['articulo_id'] ?? null, function ($q, $id) {
            $q->where('id', $id);
        })
        ->when($filtros['clasificacion_id'] ?? null, function ($q, $id) {
            $q->where('inv_clasificacion_id', $id);
        })
        ->when($filtros['bodega_id'] ?? null, function ($q, $id) {
            // Filtramos a través de la relación de existencias
            $q->whereHas('existencias', function ($sq) use ($id) {
                $sq->where('bodega_id', $id);
            });
        });
    }
}
