@extends('adminlte::page')
@section('css')
	<link rel="stylesheet" href="{{asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
	<link rel="stylesheet" href="{{asset('assets/style.css')}}">
	<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endsection
@section('title', 'Reportes')

@section('content_header')
    <h3>Facturas Emitidas</h3>
@endsection

@section('content')
	<div class="card card-navy">
		<div class="card-header text-center">
			<div class="row">
				<div class="col-md-9 offset-md-1">
				</div>
				<div class="col-md-2" style="text-align: right;">
					<button type="button" class="btn btn-xs btn-config" title="Parámetros" data-toggle="modal" data-target="#parametrosModal"><i class="fas fa-cog"></i></button>
					<a href="{{ route('rpt_arqueo_facturas_pdf', [$fecha_inicial, $fecha_final]) }}" class="btn btn-xs btn-danger" title="Impresión" target="_blank"><i class="fas fa-file-pdf"></i></a>
					<a href="{{ route('rpt_arqueo_facturas_xls', [$fecha_inicial, $fecha_final]) }}" class="btn btn-xs" title="Excel" title="Impresión" style="background-color: #A5C890 !important;"><i class="fas fa-file-excel"></i></a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="tblprincipal" class="table table-sm table-striped text-center">
					<thead>
						<tr>
							<th>No. Corte</th><th>Tipo</th><th>Documento</th><th>Fecha</th><th>Nombre</th><th>Sub Total</th><th>Descuento</th><th>Recargo</th><th>Total</th><th>Saldo</th>
						</tr>
					</thead>
					<tbody>
						@foreach($listado as $l)
							<tr>
								<td>{{ $l->corte }}</td>
								<td>{{ $l->descripcion }}</td>
								<td>{{ $l->serie }}-{{ $l->correlativo }}</td>
								<td>{{ \Carbon\Carbon::parse($l->fecha_emision)->format('d/m/Y') }}</td>
								<td>{{ $l->nombre }}</td>
								<td>{{ number_format($l->precio_bruto,2) }}</td>
								<td>{{ number_format($l->descuento,2) }}</td>
								<td>{{ number_format($l->recargo,2) }}</td>
								<td>{{ number_format($l->precio_total,2) }}</td>
								<td>{{ number_format($l->saldo,2) }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="parametrosModal" tabindex="-1" role="dialog" aria-labelledby="parametrosModalLabel" aria-hidden="true">
  		<div class="modal-dialog">
  			<div class="modal-content">
		      	<div class="modal-body">
		      		<div class="card card-navy">
		      			<div class="card-header text-center">
		      				<h5 class="modal-title" id="parametrosModalLabel">Parámetros</h5>
		      			</div>
		      			<div class="card-body">
		      				<div class="row">
	      						<div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
		                            <div class="input-group-prepend">
		                                <label class="input-group-text">Fecha Inicio</label>
		                            </div>
		                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ $fecha_inicial }}">
		                        </div>
		      				</div>
		      				<div class="row">
	      						<div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
		                            <div class="input-group-prepend">
		                                <label class="input-group-text">Fecha Final</label>
		                            </div>
		                            <input type="date" class="form-control" id="fecha_final" name="fecha_final" value="{{ $fecha_final }}">
		                        </div>
		      				</div>
		      			</div>
		      			<div class="card-footer">
		      				<div class="row">
		      					<div class="col-md-4 offset-md-8" style="text-align: right;">
		      						<a href="#" class="btn btn-xs btn-outline-secondary" title="filtrar" onclick="fn_buscar(); return false;"><i class="fas fa-search"></i></a>
		      						<button type="button" class="btn btn-xs btn-danger" data-dismiss="modal" title="Salir"><i class="fas fa-sign-out-alt"></i></button>
		      					</div>
		      				</div>
		      			</div>
		      		</div>
		      	</div>
  			</div>
  		</div>
  	</div>
@endsection
@section('js')
	<!-- DataTables -->
	<script src="{{asset('assets/adminlte/plugins/datatables/jquery.dataTables.js')}}"></script>
	<script src="{{asset('assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
	<script>
	  $(function () {
	    $('#tblprincipal').DataTable({
	      "paging": true,
	      "lengthChange": false,
	      "searching": true,
	      "ordering": true,
	      "info": true,
	      "autoWidth": false,
	      language: {
		        "sProcessing":     "Procesando...",
            	"sLengthMenu":     "Mostrar _MENU_ registros",
            	"sZeroRecords":    "No se encontraron resultados",
            	"sEmptyTable":     "Ningún dato disponible en esta tabla =(",
            	"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            	"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            	"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            	"sInfoPostFix":    "",
            	"sSearch":         "Buscar:",
            	"sUrl":            "",
            	"sInfoThousands":  ",",
            	"sLoadingRecords": "Cargando...",
            	"oPaginate": {
                				"sFirst":    "Primero",
                				"sLast":     "Último",
                				"sNext":     "Siguiente",
                				"sPrevious": "Anterior"
        					}
		    },
		    dom: 'Bfrtip',
		    buttons: [
	            'copy', 'csv', 'excel', 'pdf', 'print'
	        ]
	    });
	  });

	  function fn_buscar(){
    	var fecha_inicial = document.getElementById('fecha_inicio').value;
    	var fecha_final   = document.getElementById('fecha_final').value;
    
    	if(fecha_inicial == '' || fecha_final == '') return false;
    	var url = "{{ route('rpt_arqueo_facturas', ['fecha_inicial' => '2020-01-01', 'fecha_final' => '2020-01-02', 'tipo_admision' => 'T']) }}";
    	url = url.replace('2020-01-01', fecha_inicial);
    	url = url.replace('2020-01-02', fecha_final);
    	location.href = url;
		/*window.location.href = "{{ route('rpt_admisiones_activas',["+fecha_inicial+","+fecha_final+", 'T']) }}";*/
    }
	</script>
@endsection