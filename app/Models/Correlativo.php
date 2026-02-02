<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Correlativo extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'correlativos';
    
    protected $fillable = ['id', 'empresa_id', 'tipo', 'correlativo'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];
}
