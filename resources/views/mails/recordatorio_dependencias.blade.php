<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Llamado de emergencia</title>
</head>
<body>
    <p>Buenos días estimado(a) <b>{{ $nombre }}</b></p>
    <p>Esperando se encuentre bien, solicito su apoyo con el informe pendiente del expediente <b>{{ $entidad }}-{{ $numero_solicitud }}-{{ $anio }}</b> asignado a {{ $descripcion }} con fecha <b>{{ $fecha }}</b></p>
    <p>Agradeciendo de antemano su acostumbrado apoyo, y quedando a la espera.</p>
    <br>
    <br>
    <p>Saludos cordiales.</p>

    <img src="{{ asset('img/tarjeta_uti.jpg', 'firma_udi.jpg') }}">
</body>
</html>