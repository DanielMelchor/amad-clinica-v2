<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AuditoriaAcceso;

class LimpiarAuditoria extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'limpiar:auditoria';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina registros viejos de auditoría';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Eliminar registros de más de 1 año de antigüedad
        $fechaLimite = now()->subYears(1);
        $borrados = \App\Models\AuditoriaAcceso::where('fecha_registro', '<', $fechaLimite)->delete();
        
        $this->info("Se han limpiado $borrados registros de auditoría.");
    }
}
