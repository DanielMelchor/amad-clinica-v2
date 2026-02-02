<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CajaCorte extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'caja_cortes';

    protected $fillable = ['id', 'empresa_id', 'caja_id', 'corte', 'fecha', 'estado'];
    
    protected $hidden = [ 'created_at', 'updated_at', 'created_by', 'updated_by'];
}
