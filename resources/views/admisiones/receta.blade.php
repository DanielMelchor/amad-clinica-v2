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
            border: 0pt solid black; /* Cambiar a 1pt si quieres ver el borde */
            box-sizing: border-box;
        }

        /* 1. Contenedor para que la firma siga al texto */
        .contenedor-indicaciones {
            position: absolute;
            left: 30pt; 
            top: 80pt; 
            width: 90%;
            /* Evita que el conjunto crezca tanto que cree una página 2 */
            max-height: 280pt; 
        }

        .tratamiento {
            position: relative; /* Cambiado de absolute a relative */
            width: 100%;
            line-height: 1.4;
            display: block;
        }

        .medico-firma {
            position: relative; /* Fluye después del tratamiento */
            margin-top: 15pt;
            display: block;
        }

        .tratamiento p {
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Clases auxiliares para otros elementos absolutos */
        .fecha, .paciente {
            position: absolute;
        }
    </style>
</head>
<body>
    <div class="marco">
        {{-- Logo --}}
        @if(!empty($pEmpresa->ruta_logo))
            <div class="logo-empresa" style="position: absolute; left: 10pt; top: 10pt;">
                <img src="{{ public_path($pEmpresa->ruta_logo) }}" style="max-width: 100pt; max-height: 60pt;">
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

        {{-- 2. BLOQUE DINÁMICO: Tratamiento + Firma --}}
        <div class="contenedor-indicaciones">
            <div class="tratamiento">
                {!! $pConsulta->ctratamiento !!}
            </div>

            @if(!empty($firma->firma))
                <div class="medico-firma" style="text-align: center;">
                    <img src="{{ public_path($firma->firma) }}" style="max-width: 120pt; max-height: 80pt;">
                </div>
            @endif
        </div>
    </div>
</body>
</html>