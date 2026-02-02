<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AdmisionCargo extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'admisiones';
    
    protected $fillable = ['id', 'admision_id', 'producto_id', 'descripcion', 'cantidad', 'precio_unitario', 'precio_total', 'total_cliente', 'total_aseguradora'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];

}
