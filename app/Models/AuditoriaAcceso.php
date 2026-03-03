<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaAcceso extends Model
{
    protected $fillable = [
        'user_id', 
        'ip_address', 
        'navegador', 
        'version_navegador', 
        'plataforma', 
        'dispositivo', 
        'url_visitada', 
        'metodo'
    ];

    // Desactivamos timestamps si solo usamos 'fecha_registro'
    public $timestamps = false;
}
