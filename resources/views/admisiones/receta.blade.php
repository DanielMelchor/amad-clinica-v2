<!DOCTYPE html>
<html>
<head>
    <title>Receta</title>
    <style type="text/css">
        @page {
            size: 8.5in 5.5in portrait;
            margin: 0;
        }
        
        body {
            margin: 0.25in; 
            width: 4.8in;
            height: 7.8in;
            font-family: Helvetica, sans-serif;
            font-size: 10px;
            overflow: hidden;
            position: relative;
            text-align: justify;
        }

        .marco {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
        }

        .contenedor-indicaciones {
            position: absolute;
            left: 30pt; 
            top: 80pt; 
            width: 90%;
            max-height: 280pt; 
        }

        .medico-firma {
            margin-top: 15pt;
            display: block;
            text-align: center;
        }

        .fecha, .paciente {
            position: absolute;
        }
    </style>
</head>
<body>
    <div class="marco">
        {{-- 1. Logo de la Empresa --}}
        @php
            // Usamos la variable $rutaLogo que limpiamos en el controlador
            $pathLogo = public_path($rutaLogo);
            $logoBase64 = null;
            if (file_exists($pathLogo)) {
                $type = pathinfo($pathLogo, PATHINFO_EXTENSION);
                $data = file_get_contents($pathLogo);
                $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        @endphp

        @if($logoBase64)
            <div class="logo-empresa" style="position: absolute; left: 10pt; top: 10pt;">
                <img src="{{ $logoBase64 }}" alt="Logo" style="max-width: 100pt; max-height: 60pt;">
            </div>
        @endif

        {{-- Fecha --}}
        <div class="fecha" style="position: absolute; right: {{ floatval($posiciones['dia']['x']) }}pt; top: {{ floatval($posiciones['dia']['y']) }}pt;">
            {{ $dia }} de {{ $nombre_mes }} del {{ $anio }}
        </div>

        {{-- Paciente --}}
        <div class="paciente" style="position: absolute; right: {{ floatval($posiciones['paciente']['x']) }}pt; top: {{ floatval($posiciones['paciente']['y']) }}pt; text-align: right;">
            {{ $pConsulta->paciente_nombre }}
        </div>

        {{-- Tratamiento + Firma --}}
        <div class="contenedor-indicaciones">
            <div class="tratamiento">
                {!! $pConsulta->ctratamiento !!}
            </div>

            {{-- Usamos la firma que procesamos en el controlador --}}
            @if(isset($firmaBase64) && $firmaBase64)
                <div class="medico-firma">
                    <img src="{{ $firmaBase64 }}" alt="Firma Médico" style="max-width: 150pt; max-height: 80pt;">
                    <br>
                    <span>{{ $medico->nombre }}</span> {{-- Opcional: Nombre del médico --}}
                </div>
            @endif
        </div>
    </div>
</body>
</html>