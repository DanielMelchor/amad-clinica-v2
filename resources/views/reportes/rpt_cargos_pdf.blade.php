<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Cargos</title>
  <link rel="stylesheet" href="{{asset('assets/bootstrap-4.6.0-dist/css/bootstrap.min.css')}}" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <style>
    @page {
            margin: 0px 0px;
        }
        header {
            position: fixed;
            top: 5mm;
            left: 0cm;
            right: 0cm;
            height: 3cm;
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
        body {
          margin-top: 4cm;
          margin-left: 2cm;
          margin-right: 2cm;
          margin-bottom: 2cm;
          line-height: 15px;
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
            <img src="{{ asset('')}}{{$empresa->ruta_logo}}" alt="logo" height="60%">
          </td>
          <td width="90%">
            <h3>{{ $empresa->nombre_comercial }}</h3>
            <h5>Detalle de Cargos</h5>
          </td>
        </tr>
      </table>
    </header>
    <footer id="footer">
        <p class="page">Pagína # </p>
    </footer>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <table class="table table-sm table-borderless">
            <tbody>
              <tr>
                <th colspan="1">Admision</th><td colspan="2">{{ $encabezado->admision }}</td>
                <th></th><th></th>
                <th colspan="1">Fecha Ingreso</th><td colspan="2">{{ $encabezado->fecha_inicio }}</td>
              </tr>
              <tr>
                <th colspan="1">Paciente</th><td colspan="2">{{ $encabezado->paciente_nombre }}</td>
                <th></th><th></th>
                <th colspan="1">Fecha Egreso</th><td colspan="2">{{ $encabezado->fecha_fin }}</td>
              </tr>
              <tr>
                <th colspan="1">Médico</th><td colspan="2">{{ $encabezado->medico_nombre }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!--<div class="row">
        <div class="col-md-3 offset-md-1 input-group mb-1">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">Admisión</span>
          </div>
          <input type="text" class="form-control" style="text-align: right;" value="{{ $encabezado->admision }}">
        </div>
        <div class="col-md-3 offset-md-4 input-group mb-1">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">Fecha Ingreso</span>
          </div>
          <input type="text" class="form-control" style="text-align: right;" value="{{ $encabezado->fecha_inicio }}">
        </div>
      </div>
      <div class="row">
        <div class="col-md-3 offset-md-1 input-group mb-1">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">Paciente</span>
          </div>
          <input type="text" class="form-control" style="text-align: right;" value="{{ $encabezado->paciente_nombre }}">
        </div>
        <div class="col-md-3 offset-md-4 input-group mb-1">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">Fecha Egreso</span>
          </div>
          <input type="text" class="form-control" style="text-align: right;" value="{{ $encabezado->fecha_fin }}">
        </div>
      </div>
      <div class="row">
        <div class="col-md-3 offset-md-1 input-group mb-1">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">Médico</span>
          </div>
          <input type="text" class="form-control" style="text-align: right;" value="{{ $encabezado->medico_nombre }}">
        </div>
      </div>-->
      <hr>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-sm table-striped table-bordered">
            <thead class="text-center">
              <tr>
                <th scope="1">Fecha</th>
                <th scope="1">Cantidad</th>
                <th scope="3">Producto / Servicio</th>
                <th scope="2">Precio Unitario</th>
                <th scope="2">Total</th>
                <th scope="2">Paciente</th>
                <th scope="2">Aseguradora</th>
              </tr>
            </thead>
            <tbody>
              @foreach($detalle as $d)
              <tr>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($d->created_at)->format('d/m/Y') }}</td>
                <td style="text-align: right;">{{ number_format($d->cantidad,2) }}</td>
                <td style="text-align: center;">{{ $d->producto_descripcion }}</td>
                <td style="text-align: right;">{{ number_format($d->precio_unitario,2) }}</td>
                <td style="text-align: right;">{{ number_format($d->precio_total,2) }}</td>
                <td style="text-align: right;">{{ number_format($d->precio_cliente,2) }}</td>
                <td style="text-align: right;">{{ number_format($d->precio_aseguradora,2) }}</td>
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th style="text-align: right;">{{ number_format($totales->precio_unitario,2) }}</th>
                <th style="text-align: right;">{{ number_format($totales->precio_total,2) }}</th>
                <th style="text-align: right;">{{ number_format($totales->precio_cliente,2) }}</th>
                <th style="text-align: right;">{{ number_format($totales->precio_aseguradora,2) }}</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>

  <script src="{{ asset('assets/bootstrap-4.6.0-dist/js/bootstra.min.js') }}"></script>
</body>
</html>