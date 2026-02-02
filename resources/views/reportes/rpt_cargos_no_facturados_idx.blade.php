@extends('adminlte::page')
@section('css')
	<link rel="stylesheet" href="{{asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
	<link rel="stylesheet" href="{{asset('assets/style.css')}}">
	<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endsection
@section('title', 'Reportes')

@section('content_header')
    <h3>Cargos no facturados</h3>
@endsection

@section('content')
	<div class="card card-navy">
		<div class="card-header text-center">
			<div class="row">
				<div class="col-md-9 offset-md-1">
				</div>
				<div class="col-md-2" style="text-align: right;">
					<a href="{{ route('rpt_cargos_no_facturados_pdf') }}" class="btn btn-xs btn-danger" title="Impresión" target="_blank"><i class="fas fa-file-pdf"></i></a>
					<a href="{{ route('rpt_cargos_no_facturados_xls') }}" class="btn btn-xs" title="Excel" title="Impresión" style="background-color: #A5C890 !important;"><i class="fas fa-file-excel"></i></a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="tblprincipal" class="table table-sm table-hover text-center">
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
								<td>{{ number_format($l->total,2) }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
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
		    dom: 'Bfrtip'
	    });
	  });
	</script>
@endsection