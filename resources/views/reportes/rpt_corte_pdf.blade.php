<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-4.5.2-dist/css/bootstrap.min.css') }}">
    <title>Corte</title>
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
          bottom: 3.5cm;
          left: 1cm;
          right: 1cm;
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
              <img src="{{ asset('')}}{{$empresa->ruta_logo}}" alt="logo" height="80%">
            </td>
            <td width="90%">
              <h4>{{ $empresa->nombre_comercial }}</h4>
              <h6>Corte de caja # {{ $corte->corte }} </h6>
            </td>
          </tr>
        </table>
    </header>
    <footer>
        <!--Copyright &copy; <?php echo date("Y");?> -->
    </footer>
    <main>
        <h4>Documentos</h4>
        <table class="table table-sm table-striped text-center">
            <thead>
                <tr>
                    <th>Tipo</th><th>Documento</th><th>Fecha</th><th>Nit</th><th>Nombre</th><th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detalle_venta as $l)
                    <tr>
                        <td>{{ $l->tipodocumento_descripcion }}</td>
                        <td>{{ $l->serie }}-{{ $l->correlativo }}</td>
                        <td>{{ \Carbon\Carbon::parse($l->fecha_emision)->format('d/m/Y') }}</td>
                        <td>{{ $l->nit }}</td>
                        <td>{{ $l->nombre }}</td>
                        <td style="text-align: right;">{{ $l->total }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
            </tfoot>
        </table>
        <br>
        <h4>Recibos</h4>
        <table class="table table-sm table-striped text-center">
        	<thead>
                <tr>
                    <th>Documento</th><th>Fecha</th><th>Nit</th><th>Nombre</th><th>Total</th>
                </tr>
            </thead>
            <tbody>
            	@foreach($detalle_pago as $l)
                    <tr>
                        <td>{{ $l->recibo_serie }}-{{ $l->recibo_correlativo }}</td>
                        <td>{{ \Carbon\Carbon::parse($l->fecha_emision)->format('d/m/Y') }}</td>
                        <td>{{ $l->nit }}</td>
                        <td>{{ $l->nombre }}</td>
                        <td>{{ $l->monto_aplicado }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
    <footer>
    	<div class="row">
    		<div class="col-md-4">
    			<table class="table table-sm table-striped">
    				@foreach($resumen_venta as $l)
    					<tr>
    					<th>{{ $l->descripcion }}</th>
    					<th style="text-align: right;">{{ $l->total }}</th>
    					</tr>
    				@endforeach
    			</table>
    		</div>
    		<div class="col-md-4 ml-auto">
    			<table class="table table-sm table-striped">
    				@foreach($resumen_pago as $l)
    					<tr>
    					<th>{{ $l->descripcion }}</th>
    					<th style="text-align: right;">{{ $l->total }}</th>
    					</tr>
    				@endforeach
    			</table>
    		</div>
    	</div>
    </footer>
</body>
</html>