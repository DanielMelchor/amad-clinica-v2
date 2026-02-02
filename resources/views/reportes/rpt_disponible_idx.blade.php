@extends('adminlte::page')
@section('css')
	<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
        .btn-guardar{
            background-color: #A5C890 !important;
        }
        .numero{
            text-align: right;
        }
        .moneda:after {
            content: attr(data-numero);
        }
        .table-responsive {
            max-width: 100%; /* Ajusta el ancho según tus necesidades */
            overflow-x: auto; /* Permite el desplazamiento horizontal */
        }
        .dataTables_wrapper .row {
            display: flex;
            align-items: center; /* Alinea verticalmente los elementos */
            justify-content: flex-start; /* Ajusta los elementos a la izquierda */
        }

        .dataTables_wrapper .row .col-auto {
            display: flex;
            justify-content: flex-start; /* Alinea los elementos dentro de las columnas */
        }

        .dataTables_wrapper .row .col {
            display: flex;
            justify-content: flex-start;
        }
        .input-group-text {
            height: 30px; /* Ajusta la altura */
            padding: 5px 10px; /* Ajusta el padding */
            font-size: 0.875rem; /* Puedes ajustar el tamaño de la fuente según sea necesario */
        }

        /*.custom-select-sm, .select2bs4 {
            height: 30px; /* Ajusta la altura según lo que necesites */
            padding: 5px; /* Ajusta el padding para que la altura se reduzca */
            font-size: 0.875rem; /* Ajusta el tamaño de la fuente para que todo el conjunto se vea más pequeño */
        }*/
    </style>
@endsection
@section('title', 'Reportes')

@section('content_header')
    <br>
@endsection

@section('content')
	<div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header" style="background-color: #E1E8ED;">
                    <div class="row">
                        <div class="col-md-9">
                            <h5>Disponibilidad de Artículos</h5>
                        </div>
                        <div class="col-md-3" style="text-align: right;">
                        	<a href="{{ route('rpt_disponible_pdf') }}" class="btn btn-xs btn-default rounded-circle elevation-4" title="Impresión" target="_blank"><i class="fas fa-file-pdf"></i></a>
                            <a href="{{ route('home') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                	<div class="table-responsive">
						<table id="tbldisponible" class="table table-sm table-hover text-center">
							<thead>
								<tr style="font-size: 12px;">
									<th>Producto</th><th>Unidad de medida</th><th>Disponible</th>
								</tr>
							</thead>
							<tbody>
								@foreach($detalle as $d)
									<tr style="font-size: 12px;">
										<td>{{ $d->producto_descripcion }}</td>
										<td>{{ $d->unidad_medida_descripcion }}</td>
										<td>{{ $d->disponible }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
	<script>
	  $(function () {
	    // $('#tbldisponible').DataTable({
	    //   "paging": true,
	    //   "lengthChange": false,
	    //   "searching": true,
	    //   "ordering": true,
	    //   "info": true,
	    //   "autoWidth": false,
	    //   language: {
		//         "sProcessing":     "Procesando...",
        //     	"sLengthMenu":     "Mostrar _MENU_ registros",
        //     	"sZeroRecords":    "No se encontraron resultados",
        //     	"sEmptyTable":     "Ningún dato disponible en esta tabla =(",
        //     	"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        //     	"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        //     	"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        //     	"sInfoPostFix":    "",
        //     	"sSearch":         "Buscar:",
        //     	"sUrl":            "",
        //     	"sInfoThousands":  ",",
        //     	"sLoadingRecords": "Cargando...",
        //     	"oPaginate": {
        //         				"sFirst":    "Primero",
        //         				"sLast":     "Último",
        //         				"sNext":     "Siguiente",
        //         				"sPrevious": "Anterior"
        // 					}
		//     },
		//     dom: 'Bfrtip'
	    // });
	    $('#tbldisponible').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "pageLength": 25,  // Esto establece que por defecto se muestren 25 registros
                "lengthMenu": [ [10, 25, 50, 100], [10, 25, 50, 100] ],  // Esto establece las opciones en el dropdown
                "language": {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla =(",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sSearch": "Buscar:",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    }
                },
                "dom": '<"row"<"col-sm-4"l><"col-sm-4 text-center"B><"col-sm-4"f>>rtip', // Ajuste para disposición
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        className: 'btn btn-xs btn-default'
                    }
                ]
            });
	  });
	</script>
@endsection