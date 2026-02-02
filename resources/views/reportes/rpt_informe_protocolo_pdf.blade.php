<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  	<link rel="stylesheet" href="{{ asset('assets/bootstrap-4.5.2-dist/css/bootstrap.min.css') }}">
	<title>Informe de Protocolo</title>
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
        <img src="{{ asset($empresa->ruta_logo)}}" width="20%">
    </header>
    <footer>
        <!--Copyright &copy; <?php echo date("Y");?> -->
    </footer>
    <main>
        <p style="text-align: center;">Gutemala, {{$dia}} de {{$mes}} de {{ $anio }}</p>
        <br>
        <br>
        <p style="line-height: 7px;">Señores</p>
        <p style="line-height: 7px;">{{ $encabezado->aseguradora_nombre}}</p>
        <p style="line-height: 7px;">Presente</p>
        <br>
        <p style="line-height: 7px; text-align: right;"><b>INFORME DE PROTOCOLO</b></p>
        <p style="line-height: 7px; text-align: right;"><b>PACIENTE {{ $encabezado->nombre_paciente }}</b></p>
        <p style="text-align: justify;">
        	{{ $texto_inicial }}
        </p>
        <p style="text-align: justify;">
        	{{ $segunda_linea }}
        </p>
        <p style="text-align: justify;">
        	<b>{{ $tercer_linea }}</b>
        </p>
        <table class="table table-sm table-striped">
        	<thead></thead>
        	<tbody>
        		@foreach($productos as $p)
        			<tr>
        				<td>{{ $p->producto_descripcion }}</td>
        				<td style="text-align: right;">{{ number_format($p->precio_total,2) }}</td>
        			</tr>
        		@endforeach
        	</tbody>
        	<tfoot>
        		<td style="text-align: right;">Total</td>
        		<td style="text-align: right;">{{ number_format($total->total_protocolo,2) }}</td>
        	</tfoot>
        </table>
        <br>
        <p>{{ $cuarta_linea }}</p>
        <br>
        <p>{{ $linea_final }}</p>
        <br>
        
        @if(isset($medico->firma))
        	<p style="text-align: center;">
        		<img src="{{asset('')}}{{$medico->firma}}" style="width: 200px;">
        	</p>
        @endif
        <!--<p style="page-break-after: always;">
            
        </p>
        <p style="page-break-after: always;">
            
        </p>-->
    </main>
</body>
</html>