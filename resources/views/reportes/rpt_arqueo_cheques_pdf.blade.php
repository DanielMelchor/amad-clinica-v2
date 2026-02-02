<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="{{ asset('assets/bootstrap-4.5.2-dist/css/bootstrap.min.css') }}">
  <title>Arqueo de Cheques</title>
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
    h5{text-align: center; text-transform: uppercase; font-size:15px !important;
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
          <h3>{{$empresa->nombre_comercial}}</h3>
          <h5>Arqueo de cheques del {{\Carbon\Carbon::parse($fecha_inicial)->format('d/m/Y')}} al {{\Carbon\Carbon::parse($fecha_final)->format('d/m/Y')}}</h5>
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
              <th>Recibo</th><th>Fecha</th><th>Banco</th>
              <th>No. Cuenta</th><th>No. Documento</th><th>No. Autorización</th>
              <th>Monto</th>
            </tr>
          </thead>
          <tbody>
            @foreach($listado as $l)
            <tr>
              <td>{{ $l->serie }}-{{ $l->correlativo }}</td>
              <td>{{ \Carbon\Carbon::parse($l->fecha_emision)->format('d/m/Y') }}</td>
              <td>{{ $l->nombre }}</td>
              <td>{{ $l->cuenta_no }}</td>
              <td>{{ $l->documento_no }}</td>
              <td>{{ $l->autoriza_no }}</td>
              <td>{{ $l->monto }}</td>
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