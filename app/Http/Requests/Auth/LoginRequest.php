<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\LoginVerification;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Mail\LoginConfirmationMail;
use Illuminate\Support\Facades\Mail;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        /*$this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('username', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());*/
        $this->ensureIsNotRateLimited();

        // 1. Usamos Auth::validate en lugar de Auth::attempt.
        // validate() verifica si el usuario y contraseña son correctos pero NO inicia sesión.
        if (! Auth::validate($this->only('username', 'password'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }

        // 2. Si las credenciales son correctas, obtenemos el usuario
        $user = \App\Models\User::where('username', $this->username)->first();

        // 3. Generamos el token de seguridad
        $token = Str::random(64);

        // 4. Guardamos el registro en la tabla que creamos
        LoginVerification::create([
            'user_id'    => $user->id,
            'token'      => $token,
            'ip_address' => $this->ip(),
            'user_agent' => $this->userAgent(),
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);

        // 5. ENVIAR EL CORREO USANDO GMAIL
        Mail::to($user->email)->send(new LoginConfirmationMail($token));

        // 5. Guardamos el ID del usuario en la sesión para usarlo después
        // pero NO ejecutamos Auth::login() todavía.
        session(['auth_temp_user_id' => $user->id]);

        // 6. Limpiamos el limitador de intentos
        RateLimiter::clear($this->throttleKey());

        // 7. Lanzamos una excepción especial o simplemente dejamos que el 
        // controlador redirija a la página de "espera de correo".
        // En Breeze, el controlador que usa este Request hará la redirección.
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
