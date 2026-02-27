<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Empresa extends Model
{
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'empresas';
    
    protected $fillable = ['id', 'razon_social', 'nombre_comercial', 'direccion', 'pais_id', 'departamento_id', 'municipio_id', 'codigo_postal', 'email', 'telefonos', 'nit_empresa', 'igss_empresa', 'fecha_constitucion', 'ruta_logo', 'afiliacion_iva', 'porcentaje_impuesto', 'alias', 'llave_firma', 'llave_certifica', 'formato', 'estado'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];

    public static function obtenerImpuesto($empresaId)
    {
        $registro = self::where('id', $empresaId)->first();
        if ($registro) {
            // Realizamos la operación para devolver el factor (ej: 1.12)
            return 1 + ($registro->porcentaje_impuesto / 100);
        }

        // Retorno por defecto si no encuentra la empresa (factor 1 = sin impuesto)
        return 1;
    }
}
