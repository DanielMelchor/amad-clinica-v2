<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <!--<link rel="stylesheet" type="text/css" href="{{ asset('assets/adminlte/plugins/bootstrap/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
  <link rel="stylesheet" href="{{ asset('assets/bootstrap-4.5.2-dist/css/bootstrap.min.css') }}">
  <style>
    @page {
        margin: 0px 0px;
        font-family: Arial;
    }

    body {
        margin: 3cm 2cm 2cm;
    }

    header {
        position: fixed;
        top: 0cm;
        left: 0cm;
        right: 0cm;
        height: 2cm;
        background-color: white;
        color: white;
        text-align: center;
        line-height: 30px;
    }

    footer {
        position: fixed;
        bottom: 0cm;
        left: 0cm;
        right: 0cm;
        height: 1.5cm;
        background-color: white;
        color: black;
        text-align: center;
        line-height: 25px;
    }
    h3{text-align: center; text-transform: uppercase; font-size:20px !important;
        }
    h5{text-align: center; text-transform: uppercase; font-size:12px !important;
        }
    th{ font-size: 11px !important; }
    td{ font-size: 10px !important; }
    #footer .page:after { content: counter(page); }
</style>
</head>
<body>
<header>
    <table class="table table-borderless">
      <tr>
        <td width="10%">
          <img src="{{ asset('')}}{{$empresa->ruta_logo}}" alt="logo" height="80%">
        </td>
        <td width="90%">
          <h3>{{ $empresa->nombre_comercial }}</h3>
          <h5>Admisiones con Saldo Pendiente</h5>
        </td>
      </tr>
    </table>
</header>
<main>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-sm table-striped text-center">
          <thead>
            <tr>
              <th># Admisión</th><th>Paciente</th><th>Fecha</th>
              <th>Total Cargos</th><th>Total Facturado</th><th>Total Pagado</th><th>Saldo</th>
            </tr>
          </thead>
          <tbody>
            @foreach($admisiones as $a)
              <tr>
                <td>{{ $a->admision }}</td>
                <td>{{ $a->paciente_nombre }}</td>
                <td>{{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}</td>
                <td style="text-align: right;">{{ number_format($a->total_cargos,2) }}</td>
                <td style="text-align: right;">{{ number_format($a->total_facturado,2) }}</td>
                <td style="text-align: right;">{{ number_format($a->total_pagado,2) }}</td>
                <td style="text-align: right;">{{ number_format($a->saldo,2) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
</main>
<footer id="footer">
    <p class="page">Pagína # </p>
</footer>
</body>
</html>