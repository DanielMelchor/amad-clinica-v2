<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\DocumentoTrait;

use App\Helpers\DocumentoHelper;

class DocumentoMaestro extends Model
{
    use LogsActivity, HasUserstamps, DocumentoTrait;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'documentoventa_maestros';
    
    protected $fillable = ['id', 'empresa_id', 'caja_id', 'corte_id', 'tipodocumento_id', 'resolucion_id', 'fecha_emision', 'serie', 'correlativo', 'paciente_id', 'condicion', 'nit', 'nombre', 'direccion', 'tipodocumentoafecto_id', 'serie_afecta', 'correlativo_afecto', 'motivoanulacion_id', 'fecha_anulacion', 'estado'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];

    public function Detalle(){
        return $this->hasMany('App\DocumentoDetalle', 'documentoventa_maestro_id');
    }

    public function TipoDocumento(){
        return $this->belongsTo(TipoDocumento::class, 'foreign_key');
    }

    public function getSaldoAttribute(){
        return $this->DocumentoSaldo(
            $this->tipodocumento_id,
            $this->serie,
            $this->correlativo
        );
    }

    public function getTotalAttribute(){
        return $this->DocumentoTotal(
            $this->tipodocumento_id,
            $this->serie,
            $this->correlativo
        );
    }
}
