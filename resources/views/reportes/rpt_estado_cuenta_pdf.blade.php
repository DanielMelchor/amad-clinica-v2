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
            <h5>Estado de Cuenta</h5>
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
      <hr>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-sm table-striped table-bordered">
            <thead class="text-center">
              <tr>
                <th scope="3">Referencia</th>
                <th scope="1"></th>
                <th scope="1">Fecha</th>
                <th scope="1">Cargo</th>
                <th scope="2">Abono</th>
              </tr>
            </thead>
            <tbody>
              @foreach($movimientos as $m)
              <tr>
                @if($m['tipo'] == 'A')
                <td style="text-align: center;">{{ $m['documento']}} {{ $m['serie']}}-{{ $m['correlativo'] }}</td>
                @else
                  <td>{{ $m['documento']}} {{ $m['serie']}}-{{ $m['correlativo'] }}</td>
                @endif
                @if($m['estado'] == 'I')
                  <td style="text-align: center; color: red;">Anulado</td>
                @else
                  <td></td>
                @endif
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($m['fecha'])->format('d/m/Y') }}</td>
                @if($m['estado'] == 'I')
                  <td style="text-align: right; color: red;">{{ number_format($m['cargo'],2) }}</td>
                  <td style="text-align: right; color: red;">{{ number_format($m['abono'],2) }}</td>
                @else
                  <td style="text-align: right;">{{ number_format($m['cargo'],2) }}</td>
                  <td style="text-align: right;">{{ number_format($m['abono'],2) }}</td>
                @endif
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right;"><h6>{{ number_format($total_cargos,2) }}</h6></td>
                <td style="text-align: right;"><h6>{{ number_format($total_abonos,2) }}</h6></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>

  <script src="{{ asset('assets/bootstrap-4.6.0-dist/js/bootstra.min.js') }}"></script>
</body>
</html>