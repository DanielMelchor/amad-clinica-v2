<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasUserstamps
{
    /**
     * Se ejecuta automáticamente al iniciar el Trait en el Modelo
     */
    protected static function bootHasUserstamps()
    {
        // Al crear un nuevo registro
        static::creating(function ($model) {
            if (Auth::check()) {
                if (!$model->created_by) {
                    $model->created_by = Auth::id();
                }
                if (!$model->updated_by) {
                    $model->updated_by = Auth::id();
                }
            }
        });

        // Al actualizar un registro existente
        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    /**
     * Definir relaciones semánticas (opcional)
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}