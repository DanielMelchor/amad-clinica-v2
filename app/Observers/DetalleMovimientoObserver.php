<?php

namespace App\Observers;

use App\Models\DetalleMovimiento;
use App\Models\InvSaldo;
use App\Models\BodegaProductoConfig;

class DetalleMovimientoObserver
{
    public function creating(DetalleMovimiento $detalle)
    {
        $maestro = $detalle->maestro;

        $nombreProducto = $detalle->producto->descripcion ?? 'Producto ID: ' . $detalle->producto_id;

        // 1. Validar Autorización y Máximo en Destino (si es traslado)
        if ($maestro->bodega_destino_id) {
            $config = BodegaProductoConfig::where('bodega_id', $maestro->bodega_destino_id)
                ->where('producto_id', $detalle->producto_id)
                ->first();

            if (!$config || $config->estado == 0) {
                throw new \Exception("Insumo '{$nombreProducto}' no autorizado para la bodega destino.");
            }

            // Validar Máximo
            if ($config->stock_maximo > 0) {
                $saldo = InvSaldo::where('bodega_id', $maestro->bodega_destino_id)
                    ->where('producto_id', $detalle->producto_id)
                    ->first();
                $actual = $saldo ? $saldo->stock_actual : 0;
                $nuevoTotal = $actual + $detalle->cantidad_x_medida;
                
                /*if (($actual + $detalle->cantidad_x_medida) > $config->stock_maximo) {
                    throw new \Exception("El insumo {$nombreProducto}' supera el stock máximo permitido en destino ({$config->stock_maximo}).");
                }*/
                if ($nuevoTotal > $config->stock_maximo) {
                    throw new \Exception(
                        "El insumo '{$nombreProducto}' superaría el stock máximo en destino. " .
                        "Actual: {$actual}, Nuevo ingreso: {$detalle->cantidad_x_medida}. " .
                        "Límite permitido: {$config->stock_maximo} unidades."
                    );
                }
            }
        }

        // Si el signo es negativo (es una salida o un traslado)
        if ($detalle->signo < 0) {
            $saldo = InvSaldo::where('bodega_id', $maestro->bodega_origen_id)
                     ->where('producto_id', $detalle->producto_id)
                     ->first();

            $stockDisponible = $saldo ? $saldo->stock_actual : 0;

            // Si intenta sacar más de lo que hay, abortamos
            if ($stockDisponible < $detalle->cantidad_x_medida) {
                throw new \Exception("Stock insuficiente para '{$nombreProducto}' en Bodega Origen ({$maestro->bodega_origen_id}). Disponible: {$stockDisponible}");
            }
        }
    }
    /**
     * Handle the DetalleMovimiento "created" event.
     */
    public function created(DetalleMovimiento $detalle)
    {
        $maestro = $detalle->maestro;
        $cantidad = $detalle->cantidad_x_medida;

        // 1. AFECTAR BODEGA ORIGEN (Siempre ocurre)
        // Usamos el signo del detalle para determinar si suma o resta
        $this->actualizarSaldo(
            $maestro->bodega_origen_id, 
            $detalle->producto_id, 
            ($cantidad * $detalle->signo)
        );

        // 2. AFECTAR BODEGA DESTINO (Solo si es un traslado)
        // Si existe bodega_destino_id, significa que es un traslado entre bodegas.
        if ($maestro->bodega_destino_id) {
            // En la bodega destino, la operación SIEMPRE es inversa a la del origen.
            // Si en origen restó (signo -1), en destino debe sumar (valor positivo).
            $valorDestino = $cantidad * ($detalle->signo * -1);
            
            $this->actualizarSaldo(
                $maestro->bodega_destino_id, 
                $detalle->producto_id, 
                $valorDestino
            );
        }
    }

    /**
     * Handle the DetalleMovimiento "updating" event.
     * Validamos ANTES de que se guarde el cambio en la DB.
     */
    public function updating(DetalleMovimiento $detalle): void
    {
        // Usamos getOriginal para comparar con lo que había antes en la DB
        $cantidadVieja = $detalle->getOriginal('cantidad_x_medida');
        $cantidadNueva = $detalle->cantidad_x_medida;
        //dd('entre con '.$cantidadVieja.' - '.$cantidadNueva.' signo '.$detalle->signo);
        $diferencia = $cantidadNueva - $cantidadVieja;

        // Si el signo es -1 (Salida/Traslado) y están aumentando la cantidad
        if ($detalle->signo == -1 && $diferencia > 0) {
            $saldo = InvSaldo::where('bodega_id', $detalle->maestro->bodega_origen_id)
                ->where('producto_id', $detalle->producto_id)
                ->first();

            $disponible = $saldo ? $saldo->stock_actual : 0;

            if ($disponible < $diferencia) {
                throw new \Exception("Stock insuficiente para aumentar la cantidad. Disponible adicional: {$disponible}");
            }
        }
    }

    /**
     * Handle the DetalleMovimiento "updated" event.
     * Aplicamos el ajuste de saldos DESPUÉS de guardar.
     */
    public function updated(DetalleMovimiento $detalle): void
    {
        // 1. DETECTAR ELIMINACIÓN LÓGICA (Cambio de estado 1 a 0)
        if ($detalle->getOriginal('estado') == 1 && $detalle->estado == 0) {
            $this->reversarSaldoCompleto($detalle);
            return; // Salimos para que no ejecute la lógica de cambio de cantidad
        }

        $maestro = $detalle->maestro;
        
        // Calculamos cuánto cambió realmente la cantidad
        $cantidadVieja = $detalle->getOriginal('cantidad_x_medida');
        if ($detalle->estado == 0) {
            $cantidadNueva = 0;
        }else{
            $cantidadNueva = $detalle->cantidad_x_medida;
        }
        
        $diferencia = $cantidadNueva - $cantidadVieja;
        // Si no hubo cambio en la cantidad, no procesamos nada
        if ($diferencia == 0) return;

        // 1. Ajustar Bodega Origen
        // Si diferencia es positiva, resta más. Si es negativa, devuelve stock.
        $this->actualizarSaldo(
            $maestro->bodega_origen_id, 
            $detalle->producto_id, 
            ($diferencia * $detalle->signo)
        );

        // 2. Ajustar Bodega Destino (Solo si es traslado)
        if ($maestro->bodega_destino_id) {
            $this->actualizarSaldo(
                $maestro->bodega_destino_id, 
                $detalle->producto_id, 
                ($diferencia * $detalle->signo * -1)
            );
        }
    }

    /**
     * Función auxiliar para limpiar el código
     */
    private function reversarSaldoCompleto($detalle)
    {
        $maestro = $detalle->maestro;
        $cantidad = $detalle->cantidad_x_medida;

        // Reversar Origen (Si el signo era -1, aquí multiplicamos por -1 para que sume)
        $this->actualizarSaldo(
            $maestro->bodega_origen_id, 
            $detalle->producto_id, 
            ($cantidad * $detalle->signo * -1)
        );

        // Reversar Destino (Si es traslado)
        if ($maestro->bodega_destino_id) {
            $this->actualizarSaldo(
                $maestro->bodega_destino_id, 
                $detalle->producto_id, 
                ($cantidad * $detalle->signo)
            );
        }
    }

    /**
     * Handle the DetalleMovimiento "deleted" event.
     */
    public function deleted(DetalleMovimiento $detalle)
    {
        $bodega_id = $detalle->maestro->bodega_origen_id;

        $saldo = InvSaldo::where('producto_id', $detalle->producto_id)
                         ->where('bodega_id', $bodega_id)
                         ->first();
        
        if ($saldo) {
            // Revertimos el movimiento
            $saldo->decrement('stock_actual', ($detalle->cantidad * $detalle->signo));
        }
    }

    /**
     * Handle the DetalleMovimiento "restored" event.
     */
    public function restored(DetalleMovimiento $detalleMovimiento): void
    {
        //
    }

    /**
     * Handle the DetalleMovimiento "force deleted" event.
     */
    public function forceDeleted(DetalleMovimiento $detalleMovimiento): void
    {
        //
    }

    private function actualizarSaldo($bodegaId, $productoId, $cantidad)
    {
        $saldo = InvSaldo::firstOrCreate(
            ['bodega_id' => $bodegaId, 'producto_id' => $productoId],
            ['stock_actual' => 0]
        );

        $saldo->increment('stock_actual', $cantidad);
    }
}
