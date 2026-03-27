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

        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user || !$user->email) {
            return back()->withErrors(['email' => 'No se pudo determinar el destinatario del correo.']);
        }
        $destinatario = $user->email;

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $token = \Illuminate\Support\Str::random(64);

        \App\Models\LoginVerification::create([
            'user_id'    => $user->id,
            'token'      => $token,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'expires_at' => now()->addMinutes(15),
        ]);

        \Illuminate\Support\Facades\Mail::to($user->email)
        ->send(new \App\Mail\LoginConfirmationMail($token));

        session(['auth_user_id' => $user->id]);

        return redirect()->route('login.verify.form');

        //return redirect()->route('login.waiting');

        // $request->session()->regenerate();

        //$user = auth()->user();

        // $url = match (true) {
        //     $user->hasRole('Super Admin')   => route('home'),
        //     $user->hasRole('Administrador') => route('graficas_index'),
        //     $user->hasRole('Medico')        => route('index_medico'),
        //     $user->hasRole('Recepción')     => route('nueva_agenda'), // Con tilde como en tu DB
        //     default                         => route('consultas'),
        // };

        // return redirect()->intended($url);

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

    // Muestra la vista de "Revisa tu correo"
    public function showVerifyForm() {
        return view('auth.verify-login-msg'); // Una vista simple de Breeze
    }

    // Procesa el link del correo
    public function verify($token) {
        $verification = \App\Models\LoginVerification::where('token', $token)
            ->where('is_confirmed', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return redirect()->route('login')->withErrors(['email' => 'El enlace ha expirado o es inválido.']);
        }

        // Marcar como usado y loguear
        $verification->update(['is_confirmed' => true]);

        $user = \App\Models\User::find($verification->user_id);

        if ($user) {
            auth()->login($user); // Aquí es donde se crea la cookie de sesión
            
            // 4. Limpiar la sesión temporal que usamos en el paso anterior
            session()->forget('auth_user_id');
            
            // 5. Redirigir al home o dashboard
            return redirect()->intended(route('home', absolute: false));
        }
    }
}
