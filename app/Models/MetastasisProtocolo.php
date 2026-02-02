<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MetastasisProtocolo extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'metastasis_protocolos';
    
    protected $fillable = ['maestro_protocolo_id', 'cuerpo_parte_id'];
    
    protected $hidden = ['id', 'created_at', 'updated_at', 'created_by', 'updated_by'];
}
