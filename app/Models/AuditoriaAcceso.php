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

    public function usuario()
    {
        // Si tu llave foránea en la tabla se llama 'user_id', Laravel la detecta solo.
        return $this->belongsTo(User::class, 'user_id');
    }

    // Desactivamos timestamps si solo usamos 'fecha_registro'
    public $timestamps = false;
}
