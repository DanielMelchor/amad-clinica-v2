@extends('adminlte::page')
@section('css')
	<link rel="stylesheet" href="{{asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
	<link rel="stylesheet" href="{{ asset('assets/style.css')}}">
	<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endsection
@section('title', 'Reportes')

@section('content_header')
    <h3>Admisiones por Fecha</h3>
@endsection

@section('content')
	<div class="card card-navy">
		<div class="card-header text-center">
			<div class="row">
				<div class="col-md-9 offset-md-1">
				</div>
				<div class="col-md-2" style="text-align: right;">
					<button type="button" class="btn btn-xs btn-config" title="Parámetros" data-toggle="modal" data-target="#parametrosModal"><i class="fas fa-cog"></i></button>
					<a href="{{ route('rpt_admisiones_por_fecha_pdf',[$fecha_inicial, $fecha_final, $tipo_admision]) }}" class="btn btn-xs btn-reporte" title="Impresión" target="_blank"><i class="fas fa-file-pdf"></i></a>
					<a href="{{ route('rpt_admisiones_por_fecha_xls',[$fecha_inicial, $fecha_final, $tipo_admision]) }}" class="btn btn-xs btn-excel" title="Excel"><i class="fas fa-file-excel"></i></a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="tbladmisiones" class="table table-sm table-hover table-striped text-center">
					<thead>
						<tr>
							<th># Admisión</th><th>Fecha</th><th>Creada Por</th>
							<th>Tipo</th><th>Entrada</th><th>Salida</th>
							<th>Paciente</th><th>Facturas</th>
						</tr>
					</thead>
					<tbody>
						@foreach($admisiones as $a)
							<tr>
								<td>{{ $a->admision }}</td>
								<td>{{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}</td>
								<td>{{ $a->usuario_nombre }}</td>
								<td>@if($a->tipo_admision == 'C') Consulta @endif @if($a->tipo_admision == 'P') Procedimiento @endif @if($a->tipo_admision == 'H') Hospitalización @endif</td>
								<td>@if($a->fecha_inicio != ''){{ \Carbon\Carbon::parse($a->fecha_inicio)->format('d/m/Y') }} @endif</td>
								<td>@if($a->fecha_fin != ''){{ \Carbon\Carbon::parse($a->fecha_fin)->format('d/m/Y') }} @endif</td>
								<td>{{ $a->paciente_nombre }}</td>
								<td>{{ $a->numero_documento }}</td>
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
		      					<div class="col-md-12 text-center">
		      						<div class="form-group form-control-sm clearfix">
		                                <div class="icheck-primary d-inline">
		                                    <input type="radio" id="todas" name="tipo_consulta" value="T" checked>
		                                    <label for="todas">Todo</label>
		                                </div>
		                                <div class="icheck-primary d-inline">
		                                    <input type="radio" id="consulta" name="tipo_consulta" value="C">
		                                    <label for="consulta">Consulta</label>
		                                </div>
		                                <div class="icheck-primary d-inline">
		                                    <input type="radio" id="procedimiento" name="tipo_consulta" value="P">
		                                    <label for="procedimiento">Procedimiento</label>
		                                </div>
		                                <div class="icheck-primary d-inline">
		                                    <input type="radio" id="hospitalizacion" name="tipo_consulta" value="C">
		                                    <label for="hospitalizacion">Hospital</label>
		                                </div>
		                            </div>
		      					</div>
		      				</div>
		      				<div class="row">
		      					<div class="col-md-12">
		      						<div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
			                            <div class="input-group-prepend">
			                                <span class="input-group-text">Fecha Inicio</span>
			                            </div>
			                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ $fecha_inicial }}">
			                        </div>
		      					</div>
		      				</div>
		      				<div class="row">
		      					<div class="col-md-12">
		      						<div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
			                            <div class="input-group-prepend">
			                                <span class="input-group-text">Fecha Final</span>
			                            </div>
			                            <input type="date" class="form-control" id="fecha_final" name="fecha_final" value="{{ $fecha_final }}">
			                        </div>
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
	<link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
	<script>
	  tipo_admision = 'T';

	  $(function () {
	    $('#tbladmisiones').DataTable({
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
		    dom: 'Bfrtip'
	    });
	  });

	function fn_buscar(){
    	var fecha_inicial = document.getElementById('fecha_inicio').value;
    	var fecha_final   = document.getElementById('fecha_final').value;
    	if(fecha_inicial == '' || fecha_final == '') return false;
			var url = "{{ route('rpt_admisiones_por_fecha', ['fecha_inicial' => '2020-01-01', 'fecha_final' => '2020-01-02', 'tipo_admision' => 'T']) }}";
	    	url = url.replace('2020-01-01', fecha_inicial);
	    	url = url.replace('2020-01-02', fecha_final);
	    	url = url.replace('T', tipo_admision);
	    	location.href = url;
    }

    $(document).on('change','input[type="radio"]' ,function(e) {
	    if(this.id=="todas") {
	        tipo_admision = 'T';
	    }
	    if(this.id=="consulta") {
	        tipo_admision = 'C';
	    }
	    if(this.id=="procedimiento") {
	        tipo_admision = 'P';
	    }
	    if(this.id=="hospitalizacion") {
	        tipo_admision = 'H';
	    }
	});

	</script>
@endsection