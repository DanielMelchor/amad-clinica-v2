@extends('adminlte::master') {{-- O tu layout base de AdminLTE --}}

@section('body')
<div class="d-flex align-items-center justify-content-center" style="min-height: 100vh; background-color: #f4f6f9;">
    <div class="login-box" style="width: 450px;">
        <div class="card card-outline card-primary shadow">
            <div class="card-header text-center">
                @php
                    // Usamos la variable $rutaLogo que limpiamos en el controlador
                    $pathLogo = public_path('img/jerico_icono.png');
                    $logoBase64 = null;
                    if (file_exists($pathLogo)) {
                        $type = pathinfo($pathLogo, PATHINFO_EXTENSION);
                        $data = file_get_contents($pathLogo);
                        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    }
                @endphp
                @if($logoBase64)
                    <div class="logo-empresa">
                        <img src="{{ $logoBase64 }}" alt="Logo" style="max-width: 90pt; max-height: 50pt;">
                    </div>
                @endif
            </div>
            <div class="card-body">

                <h4 class="text-center mb-3">Verificación Requerida</h4>
                <p class="text-muted text-center">
                    Hemos enviado un correo de confirmación. Por favor, revise su bandeja de entrada y presione <b>"Aceptar Acceso"</b>.
                </p>

                <div class="alert alert-warning mt-4">
                    <i class="icon fas fa-info-circle"></i>
                    Esta ventana se actualizará automáticamente una vez que confirmes en tu correo.
                </div>

                <div class="d-flex justify-content-center mt-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <small class="text-muted">¿No recibiste el correo? Revisa tu carpeta de Spam.</small>
            </div>
        </div>
    </div>
</div>

<script>
    // Lógica para monitorear el estado del acceso sin refrescar
    setInterval(function() {
        fetch("{{ route('login.check-status') }}")
            .then(response => response.json())
            .then(data => {
                if (data.confirmed) {
                    // Redirigir al home si la verificación es exitosa
                    window.location.href = "{{ route('home') }}";
                }
            })
            .catch(error => console.error('Error verificando estado:', error));
    }, 3000); // Consulta cada 3 segundos
</script>
@stop