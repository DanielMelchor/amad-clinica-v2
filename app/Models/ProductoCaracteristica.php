<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProductoCaracteristica extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'producto_caracteristicas';
    
    protected $fillable = ['producto_id', 'descripcion', 'estado'];
    
    protected $hidden = ['id', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    public function producto(){
        return $this->belongsTo(Producto::class);
    }
}
