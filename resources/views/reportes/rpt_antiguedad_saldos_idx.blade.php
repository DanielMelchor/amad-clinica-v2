@extends('adminlte::page')
@section('css')
	<link rel="stylesheet" href="{{asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
	<link rel="stylesheet" href="{{asset('assets/style.css')}}">
	<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
	<style type="text/css">
		body {
		  font: 90%/1.45em "Helvetica Neue", HelveticaNeue, Verdana, Arial, Helvetica, sans-serif;
		  margin: 0;
		  padding: 0;
		  color: #333;
		  background-color: #fff;
		}
		th { white-space: nowrap; }
	</style>
@endsection
@section('title', 'Reportes')

@section('content_header')
    <h3>Antiguedad de Saldos</h3>
@endsection

@section('content')
	<div class="card card-navy">
		<div class="card-header text-center">
			<div class="row">
				<div class="col-md-9 offset-md-1">
				</div>
				<div class="col-md-2" style="text-align: right;">
					<a href="{{ route('rpt_antiguedad_saldos_pdf') }}" class="btn btn-xs btn-reporte" title="Impresión" target="_blank"><i class="fas fa-file-pdf"></i></a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="tblprincipal" class="table table-sm table-striped text-center">
					<thead>
						<tr>
							<th>Admisión</th><th>Cliente</th><th>Factura</th><th>Fecha</th><th>a 30 dias</th><th>a 60 dias</th><th>a 90 dias</th><th>a 120 dias</th><th>mas de 120 dias</th>
						</tr>
					</thead>
					<tbody>
						@foreach($listado as $l)
							<tr>
								<td>{{ $l->admision }}</td>
								<td>{{ $l->nombre }}</td>
								<td>{{ $l->descripcion }} {{ $l->serie }}-{{ $l->correlativo}}</td>
								<td>{{ \Carbon\Carbon::parse($l->fecha_emision)->format('d/m/Y') }}</td>
								<td style="text-align: right;">{{ number_format($l->treinta_dias,2) }}</td>
								<td style="text-align: right;">{{ number_format($l->sesenta_dias,2) }}</td>
								<td style="text-align: right;">{{ number_format($l->noventa_dias,2) }}</td>
								<td style="text-align: right;">{{ number_format($l->cientoveinte_dias,2) }}</td>
								<td style="text-align: right;">{{ number_format($l->mayor_cientoveinte_dias,2) }}</td>
							</tr>
						@endforeach
					</tbody>
					<tfoot>
				        <tr>
				            <th colspan="4" style="text-align:right">Total:</th>
				            <th style="text-align: right;"></th>
				            <th style="text-align: right;"></th>
				            <th style="text-align: right;"></th>
				            <th style="text-align: right;"></th>
				            <th style="text-align: right;"></th>
				        </tr>
				    </tfoot>
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
		  //var table = $('#tblprincipal').DataTable();

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
		    order: [[4, 'asc']],
		    buttons: [
                {
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Kopieren'
	            },
	                {
	                    extend: 'excelHtml5',
	                    text: '<i class="fa fa-file-excel-o"></i>',
	                    titleAttr: 'Excel'
	            },
	                {
	                    extend: 'csvHtml5',
	                    text: '<i class="fa fa-file-text-o"></i>',
	                    titleAttr: 'CSV'
	            },
	                {
	                    extend: 'pdfHtml5',
	                    text: '<i class="fa fa-file-pdf-o"></i>',
	                    titleAttr: 'PDF'
	            }, {
	                    extend: 'print',
	                    text: '<i class="fa fa-print"></i>',
	                    titleAttr: 'Drucken'
	            },
            ],

            "footerCallback": function ( row, data, start, end, display ) {
	            var api = this.api();
	            // Remove the formatting to get integer data for summation
	            var intVal = function ( i ) {
	                return typeof i === 'string' ?
	                    i.replace(/[\$,]/g, '')*1 :
	                    typeof i === 'number' ?
	                        i : 0;
	            };
	 
	            // Total over all pages
	            var total4 = api
	                .column( 4 )
	                .data()
	                .reduce( function (a, b) {
	                    return intVal(a) + intVal(b);
	                } );

	            var total5 = api
	                .column( 5 )
	                .data()
	                .reduce( function (a, b) {
	                    return intVal(a) + intVal(b);
	                } );

	            var total6 = api
	                .column( 6 )
	                .data()
	                .reduce( function (a, b) {
	                    return intVal(a) + intVal(b);
	                } );

	            var total7 = api
	                .column( 7 )
	                .data()
	                .reduce( function (a, b) {
	                    return intVal(a) + intVal(b);
	                } );

	            var total8 = api
	                .column( 8 )
	                .data()
	                .reduce( function (a, b) {
	                    return intVal(a) + intVal(b);
	                } );
	 
	            // Total over this page
	            var pageTotal4 = api
	                .column( 4, { page: 'current'} )
	                .data()
	                .reduce( function (a, b) {
	                    return intVal(a) + intVal(b);
	                } );

	            var pageTotal5 = api
	                .column( 5, { page: 'current'} )
	                .data()
	                .reduce( function (a, b) {
	                    return intVal(a) + intVal(b);
	                } );

	            var pageTotal6 = api
	                .column( 6, { page: 'current'} )
	                .data()
	                .reduce( function (a, b) {
	                    return intVal(a) + intVal(b);
	                } );

	            var pageTotal7 = api
	                .column( 7, { page: 'current'} )
	                .data()
	                .reduce( function (a, b) {
	                    return intVal(a) + intVal(b);
	                } );

	            var pageTotal8 = api
	                .column( 8, { page: 'current'} )
	                .data()
	                .reduce( function (a, b) {
	                    return intVal(a) + intVal(b);
	                } );
	 
	            // Update footer
	            $( api.column( 4 ).footer() ).html(
	                'Q.'+pageTotal4
	            );

	            $( api.column( 5 ).footer() ).html(
	                'Q.'+pageTotal5
	            );

	            $( api.column( 6 ).footer() ).html(
	                'Q.'+pageTotal6
	            );

	            $( api.column( 7 ).footer() ).html(
	                'Q.'+pageTotal7
	            );

	            $( api.column( 8 ).footer() ).html(
	                'Q.'+pageTotal8
	            );
	        }
            
	    });

	    /*$("#tblprincipal").append(
		       $('<tfoot/>').append( $("#tblprincipal thead tr").clone() )
		   );*/

	  });


	</script>
@endsection