<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TipoDocumento extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'tipo_documentos';
    
    protected $fillable = ['id','descripcion', 'signo', 'inventario_transaccion_id', 'tipo_interno', 'estado'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];

    public function DocumentoMaestros(){
        return $this->hasMany('App\DocumentoMaestro', 'tipodocumento_id');
    }
}
