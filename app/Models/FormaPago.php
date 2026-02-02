<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FormaPago extends Model
{
    
    use LogsActivity, HasUserstamps;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'formas_pago';

    protected $fillable = ['id', 'letra', 'descripcion', 'banco', 'casa', 'cuenta', 'documento', 'autorizacion', 'recibos', 'estado'];

    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];
}
