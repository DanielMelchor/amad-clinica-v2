<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class InvSaldo extends Model
{
    use LogsActivity, HasUserstamps;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']); // Registra cambios en todos los campos
    }

    protected $table = 'inv_saldos';
    
    protected $fillable = ['id', 'bodega_id', 'producto_id', 'stock_actual'];
    
    protected $hidden = ['created_at', 'updated_at', 'created_by', 'updated_by'];

    // Relación con Bodega
    public function bodega()
    {
        // Asegúrate de que el FK sea 'bodega_id' o cámbialo según tu tabla
        return $this->belongsTo(Bodega::class, 'bodega_id');
    }

    // Relación con Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
