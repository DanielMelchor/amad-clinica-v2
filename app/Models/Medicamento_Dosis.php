<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Medicamento_Dosis extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'medicamento_dosis';
    
    protected $fillable = ['medicamento_id', 'dosis_id', 'descripcion_receta', 'estado'];
    
    protected $hidden = ['id', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    public function producto(){
        return $this->belongsTo(Producto::class);
    }

    public function dosis(){
        return $this->belongsTo(dosis::class);
    }
}
