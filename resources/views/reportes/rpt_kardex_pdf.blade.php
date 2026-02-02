<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Kardex</title>
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
            <h5>Kardex de Productos a partir del {{ \Carbon\Carbon::parse($fecha_inicial)->format('d/m/Y') }}</h5>
          </td>
        </tr>
      </table>
    </header>
    <footer id="footer">
        <p class="page">Pagína # </p>
    </footer>
    <div class="container-fluid">
      <hr>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-sm table-striped table-bordered">
            <thead class="text-center">
              <tr>
                <th scope="1">Producto</th>
                <th scope="1">Saldo Inicial</th>
                <th scope="3">Entrada</th>
                <th scope="2">Salida</th>
                <th scope="2">Saldo Final</th>
              </tr>
            </thead>
            <tbody>
              @foreach($movimientos as $m)
                <tr>
                  @if($m['tipo'] == 'P')
                    <td>{{ $m['producto_descripcion'] }}</td>
                  @else
                    <td style="text-align: right;">{{ $m['producto_descripcion'] }}</td>
                  @endif
                  <td style="text-align: right;">{{ number_format($m['saldo_inicial'],2) }}</td>
                  <td style="text-align: right;">{{ $m['ingreso'] }}</td>
                  <td style="text-align: right;">{{ $m['egreso'] }}</td>
                  <td style="text-align: right;">{{ number_format($m['saldo_final'],2) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

  <script src="{{ asset('assets/bootstrap-4.6.0-dist/js/bootstra.min.js') }}"></script>
</body>
</html>