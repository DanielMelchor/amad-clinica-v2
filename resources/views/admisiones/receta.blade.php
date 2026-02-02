<!DOCTYPE html>
<html>
<head>
  <title>Receta</title>
  <style type="text/css">
    @page {
        margin: 1cm; /* Sin márgenes físicos */
    }
    * {
        box-sizing: border-box; /* Fuerza a que el borde se dibuje HACIA ADENTRO */
    }
    body {
        margin: 0;
        padding: 0;
        font-family: Helvetica, sans-serif;
    }

    .marco {
        position: relative;
        /* Al usar @page { margin: 1cm }, el área de dibujo se reduce automáticamente. 
           Ponemos 100% para que el cuadro negro se ajuste a ese nuevo límite. */
        width: 100%;
        height: 100%;
        border: 2pt solid black; 
        display: block;
        overflow: hidden;
    }
    .fecha {
        position: absolute;
        display: block;
        min-width: 10px;
        min-height: 10px;
        /* z-index alto para que el texto siempre esté por encima del borde */
        z-index: 100;
        line-height: 1.2;
    }
    .paciente {
        position: absolute;
        font-size: 11pt;
        font-weight: bold;
    }

  </style>
</head>
<body>
    <div class="marco">
        
        <div class="fecha" style="left: {{ floatval($posiciones['dia']['x']) }}pt; top: {{ floatval($posiciones['dia']['y']) }}pt;">
            {{ $dia }} de {{ $nombre_mes }} del {{ $anio }}
        </div>

        <div class="paciente" style="left: {{ floatval($posiciones['paciente']['x']) }}pt; top: {{ floatval($posiciones['paciente']['y']) }}pt;">
            {{ $pConsulta->paciente_nombre }}
        </div>

        <div class="tratamiento">
            {!! $pConsulta->ctratamiento !!}
        </div>

    </div>
</body>
</html>