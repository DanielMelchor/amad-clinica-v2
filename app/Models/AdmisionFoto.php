<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AdmisionFoto extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'admision_fotos';
    
    protected $fillable = ['id', 'admision_id',, 'nombre_imagen', 'nombre_imagen_mini', 'informe'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];
}
