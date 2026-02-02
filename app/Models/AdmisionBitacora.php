<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class AdmisionBitacora extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'admision_bitacoras';
    
    protected $fillable = ['id', 'admision_id', 'proceso', 'observacion_id', 'observaciones'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];
}
