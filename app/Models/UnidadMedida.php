<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class UnidadMedida extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'unidad_medidas';
    
    protected $fillable = ['descripcion', 'siglas', 'estado'];
    
    protected $hidden = ['id', 'created_at', 'updated_at', 'created_by', 'updated_by'];
}
