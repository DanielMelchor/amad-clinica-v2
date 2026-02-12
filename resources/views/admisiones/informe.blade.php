<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe Clínico</title>
    <style>
        /* Carga de Fuente Montserrat */
        @font-face {
            font-family: 'Montserrat';
            src: url("{{ public_path('fonts/font Montserrat/static/Montserrat-Regular.ttf') }}") format('truetype');
            font-weight: normal;
        }
        @font-face {
            font-family: 'Montserrat';
            src: url("{{ public_path('fonts/font Montserrat/static/Montserrat-Bold.ttf') }}") format('truetype');
            font-weight: bold;
        }

        @page {
            size: 8.5in 11in portrait;
            margin: 100pt 0 20pt 0; /* Margen para header fijo */
        }

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            color: #2c3e50;
            line-height: 1.3;
        }

        /* Header Fijo - Se repite en todas las hojas */
        .header-full {
            position: fixed;
            top: -85pt;
            left: 0;
            width: 100%;
            height: 60pt;
            border-bottom: 2px solid #1a5c8d;
            display: table;
            table-layout: fixed;
            background-color: #fff;
        }

        .header-cell {
            display: table-cell;
            vertical-align: middle;
            padding: 0 40pt;
        }

        .empresa-nombre {
            font-size: 11pt;
            font-weight: bold;
            color: #1a5c8d;
            margin: 0;
            text-transform: uppercase;
        }

        .empresa-datos {
            font-size: 7.5pt;
            color: #7f8c8d;
        }

        /* Contenedor Principal */
        .contenido-principal {
            padding: 0 40pt;
        }

        /* Bloque de Paciente Compacto */
        .paciente-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8pt 12pt;
            margin-bottom: 15pt;
        }

        .label-inline {
            font-size: 7.5pt;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            width: 80pt;
        }

        .valor-inline {
            font-size: 9pt; /* Tamaño reducido para el nombre y datos */
            color: #1e293b;
        }

        /* Secciones con espacio reducido */
        .seccion {
            margin-bottom: 12pt; /* Espacio entre bloques de sección */
        }

        .titulo-seccion {
            font-size: 8.5pt;
            font-weight: bold;
            color: #1a5c8d;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 2pt; /* Espacio mínimo bajo el título */
            margin-bottom: 4pt;  /* Espacio mínimo antes del cuerpo */
        }

        .contenido-texto {
            font-size: 9.5pt;
            text-align: justify;
            margin: 0;
            padding: 0;
        }

        /* Galería de Fotos */
        .grid-fotos {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5pt;
        }

        .img-container {
            border: 1px solid #e2e8f0;
            padding: 3pt;
            background: #fff;
        }

        .img-ajustada {
            width: 100%;
            height: 140px;
            object-fit: cover;
            display: block;
        }

        /* Firma */
        .footer-firma {
            margin-top: 10pt; /* Espacio reducido entre el contenido y la firma */
            text-align: center;
            /* Si quieres que la firma también sea fija al final de la hoja, 
               descomenta las siguientes líneas: */
            /* position: fixed; 
            bottom: -10pt; 
            width: 100%; 
            */
        }

        .linea-firma {
            width: 160pt;
            border-top: 1px solid #2c3e50;
            margin: 2pt auto;
        }

        .nombre-firma {
            font-size: 8.5pt; /* Reducimos ligeramente el tamaño */
            font-weight: bold;
        }
    </style>
</head>
<body>

    <header class="header-full">
        <div class="header-cell" style="width: 35%;">
            @if(!empty($pEmpresa->ruta_logo))
                @php
                    $path = public_path($pEmpresa->ruta_logo);
                    $base64 = (file_exists($path)) ? 'data:image/' . pathinfo($path, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($path)) : null;
                @endphp
                <img src="{{ $base64 }}" style="max-height: 40pt;">
            @endif
        </div>
        <div class="header-cell" style="text-align: right;">
            <h1 class="empresa-nombre">{{ $pEmpresa->nombre_comercial }}</h1>
            <div class="empresa-datos">
                {{ $pEmpresa->direccion }}<br>
                {{ $pEmpresa->telefonos }}
            </div>
        </div>
    </header>

    <main class="contenido-principal">
        
        <div class="paciente-card">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 55%; vertical-align: top;">
                        <table style="width: 100%;">
                            <tr>
                                <td class="label-inline">Paciente:</td>
                                <td class="valor-inline"><strong>{{ $registro->paciente_nombre }}</strong></td>
                            </tr>
                            <tr>
                                <td class="label-inline">Expediente:</td>
                                <td class="valor-inline">{{ $registro->paciente_codigo }}</td>
                            </tr>
                            <tr>
                                <td class="label-inline">Procedimiento:</td>
                                <td class="valor-inline" style="font-weight: bold;">{{ $registro->procedimiento_descripcion }}</td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 45%; vertical-align: top; padding-left: 15pt;">
                        <table style="width: 100%;">
                            <tr>
                                <td class="label-inline">Fecha:</td>
                                <td class="valor-inline">{{ $dia }} / {{ $nombre_mes }} / {{ $anio }}</td>
                            </tr>
                            <tr>
                                <td class="label-inline">Edad:</td>
                                <td class="valor-inline">{{ $registro->paciente_edad }} años</td>
                            </tr>
                            <tr>
                                <td class="label-inline">Hospital:</td>
                                <td class="valor-inline">{{ $registro->nombre_hospital ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="seccion">
            <div class="titulo-seccion">Diagnóstico</div>
            <div class="contenido-texto">{!! $registro->diagnostico !!}</div>
        </div>

        <div class="seccion">
            <div class="titulo-seccion">Indicaciones</div>
            <div class="contenido-texto">{!! $registro->indicacion !!}</div>
        </div>

        @if($fotos->count() > 0)
        <div class="seccion">
            <div class="titulo-seccion">Evidencia Fotográfica</div>
            <table class="grid-fotos" cellpadding="4">
                @foreach($fotos->chunk(3) as $fila)
                    <tr>
                        @foreach($fila as $foto)
                            @php
                                $fPath = public_path('storage/procedimientos/' . $foto->ruta);
                                $b64 = (file_exists($fPath)) ? 'data:image/' . pathinfo($fPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($fPath)) : null;
                            @endphp
                            <td style="width: 33.3%;">
                                <div class="img-container">
                                    @if($b64)
                                        <img src="{{ $b64 }}" class="img-ajustada">
                                    @endif
                                </div>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </table>
        </div>
        @endif

        <div class="seccion">
            <div class="titulo-seccion">Recomendaciones</div>
            <div class="contenido-texto">{!! $registro->indicacion !!}</div>
        </div>

        @if(isset($firma) && !empty($firma->firma))
            <div class="footer-firma">
                @php
                    $fFile = str_replace('firmas/', '', $firma->firma);
                    $fPath = public_path('firmas/' . ltrim($fFile, '/'));
                    $b64F = (file_exists($fPath)) ? 'data:image/' . pathinfo($fPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($fPath)) : null;
                @endphp
                @if($b64F)
                    <img src="{{ $b64F }}" style="max-height: 65pt; margin-bottom: -8pt;">
                @endif
                <div class="linea-firma"></div>
                <div class="nombre-firma">{{ $firma->nombre_profesional }}</div>
            </div>
        @endif

    </main>
</body>
</html>