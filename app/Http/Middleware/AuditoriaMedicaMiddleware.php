<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditoriaAcceso; // Tu modelo de logs
use Jenssegers\Agent\Agent;      // Si decides usar la librería Agent
use Symfony\Component\HttpFoundation\Response;

class AuditoriaMedicaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            // Solo registrar si es una petición que cambia datos (POST, PUT, DELETE) 
            // o si es una entrada principal, ignorando peticiones AJAX pesadas.
            if ($request->isMethod('GET') && $request->ajax()) {
                return $next($request);
            }

            $agent = new Agent();

            // Capturamos la información del dispositivo y navegador
            $navegador = $agent->browser(); // Ej: Chrome
            $version   = $agent->version($navegador); // Ej: 122.0.0
            $plataforma = $agent->platform(); // Ej: Windows, OS X
            
            // Determinamos el tipo de dispositivo
            $dispositivo = 'Desktop';
            if ($agent->isMobile()) $dispositivo = 'Móvil';
            if ($agent->isTablet()) $dispositivo = 'Tablet';
            if ($agent->isRobot()) $dispositivo = 'Bot/Crawler';

            // Guardamos en la base de datos
            AuditoriaAcceso::create([
                'user_id'           => auth()->id(), // Se guardará como string(50)
                'ip_address'        => $request->ip(),
                'navegador'         => $navegador,
                'version_navegador' => $version,
                'plataforma'        => $plataforma,
                'dispositivo'       => $dispositivo,
                'url_visitada'      => $request->fullUrl(),
                'metodo'            => $request->method(),
            ]);
        }

        return $next($request);
    }
}
