<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-4.5.2-dist/css/bootstrap.min.css') }}">
    <title>Cargos no facturados</title>
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
            height: 2cm;
        }
        body {
            margin-top: 4cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
            line-height: 15px;
        }
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
              <h4>{{ $empresa->nombre_comercial }}</h4>
              <h6>Cargos no facturados </h6>
            </td>
          </tr>
        </table>
    </header>
    <footer>
        <!--Copyright &copy; <?php echo date("Y");?> -->
    </footer>
    <main>
        <table class="table table-sm table-striped text-center">
            <thead>
                <tr>
                    <th>Admisión</th><th>Fecha</th><th>Paciente</th><th>Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($listado as $l)
                    <tr>
                        <td>{{ $l->admision }}</td>
                        <td>{{ \Carbon\Carbon::parse($l->fecha)->format('d/m/Y') }}</td>
                        <td>{{ $l->nombre_completo }}</td>
                        <td>{{ $l->total }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
            </tfoot>
        </table>
    </main>
</body>
</html>