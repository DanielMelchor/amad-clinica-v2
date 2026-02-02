<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // dd($request->all());
        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();

        $url = match (true) {
            $user->hasRole('Super Admin')   => route('home'),
            $user->hasRole('Administrador') => route('graficas_index'),
            $user->hasRole('Medico')        => route('pacientes'),
            $user->hasRole('Recepción')     => route('nueva_agenda'), // Con tilde como en tu DB
            default                         => route('consultas'),
        };

        return redirect()->intended($url);

        // return redirect()->intended(route('consultas', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
