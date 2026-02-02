@extends('adminlte::page')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
    .btn-reporte{
      background-color: #FF8D33 !important;
    }
    .btn-excel{
      background-color: #A5C890 !important;
    }
    .btn-config{
      background-color: #C8BA90 !important;
    }
    .btn-refactura{
      background-color: #C890A4 !important;
    }
    .btn-renumera{
      background-color: #8B9BC1 !important;
    }
    .btn-anular{
      background-color: #226D7C !important;
      color: white !important;
    }
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
                          <h5>Kardex</h5>
                      </div>
                      <div class="col-md-3" style="text-align: right;">
                      		<button type="button" class="btn btn-xs btn-config rounded-circle elevation-4" title="Parámetros" data-toggle="modal" data-target="#parametrosModal"><i class="fas fa-cog"></i></button>
                      		<a href="#" class="btn btn-xs btn-default rounded-circle elevation-4" title="Generar informe" onclick="generar_pdf();" target="_blank"><i class="far fa-file-pdf"></i></a>
                          <a href="{{ route('home') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
                      </div>
                  </div>
              </div>
              <div class="card-body">
              	<div class="table-responsive">
										<table id="tblDetalle" class="table table-sm table-striped table-bordered" style="width: 100%;">
											<thead>
												<tr style="font-size: 12px">
													<th>Producto</th>
													<th>Saldo Inicial</th>
													<th>Entrada</th>
													<th>Salida</th>
													<th>Saldo Final</th>
												</tr>
											</thead>
											<tbody>
												@foreach($movimientos as $m)
						              <tr style="font-size: 12px">
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
      </div>
  </div>
	<!-- Modal -->
	<div class="modal fade" id="parametrosModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="parametrosModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-dialog-centered">
  			<div class="modal-content">
    				<div class="card">
      					<div class="card-header bg-default" style="background-color: #E1E8ED;">
		      				<div class="row">
		      						<div class="col-md-9">
				      					<h6>Parámetros</h6>
				      				</div>
				      				<div class="col-md-3" style="text-align: right;">
				      					<a href="#" class="btn btn-xs btn-outline-secondary rounded-circle elevation-4" title="filtrar" onclick="fn_buscar(); return false;"><i class="fas fa-search"></i></a>
				      						<button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Salir"><i class="fas fa-sign-out-alt"></i></button>
				      				</div>
		      				</div>
		      			</div>
		      			<div class="card-body">
		      				<div class="row">
		      					<div class="col-md-10 offset-md-1 input-group mb-3">
										  <div class="input-group-prepend">
										    <label class="input-group-text" for="producto_id">Producto</label>
										  </div>
										  <select class="custom-select custom-select-sm select2 select2bs4" id="producto_id">
										    <option value="0" selected>Seleccionar...</option>
										    @foreach($productos as $p)
										    	<option value="{{ $p->producto_id }}">{{ $p->producto_descripcion }}</option>
										    @endforeach
										  </select>
										</div>
		      				</div>
		      				<div class="row">
		      					<div class="col-md-12">
		      						<div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                          <div class="input-group-prepend">
                              <label class="input-group-text">Fecha Inicio</label>
                          </div>
                          <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ $fecha_inicial }}">
                      </div>
		      					</div>
		      				</div>
		      			</div>
		      			<div class="card-footer">
		      				<div class="row">
		      					<div class="col-md-4 offset-md-8" style="text-align: right;">
		      						
		      					</div>
		      				</div>
		      			</div>
		      		</div>
  			</div>
  		</div>
  	</div>
@endsection
@section('js')
    <script src="{{ asset('assets/select2/js/select2.full.min.js')}}"></script>
	  <script type="text/javascript">
		//========================================================================
        // inicializar librerias
        //========================================================================
        $(function () {
            $('.select2').select2()
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        });		

        // $(function () {
			  //   $('#tblDetalle').DataTable({
			  //     "paging": true,
			  //     "lengthChange": false,
			  //     "searching": true,
			  //     "ordering": false,
			  //     "info": true,
			  //     "autoWidth": false,
			  //     language: {
				//         "sProcessing":     "Procesando...",
		    //         	"sLengthMenu":     "Mostrar _MENU_ registros",
		    //         	"sZeroRecords":    "No se encontraron resultados",
		    //         	"sEmptyTable":     "Ningún dato disponible en esta tabla =(",
		    //         	"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		    //         	"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
		    //         	"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
		    //         	"sInfoPostFix":    "",
		    //         	"sSearch":         "Buscar:",
		    //         	"sUrl":            "",
		    //         	"sInfoThousands":  ",",
		    //         	"sLoadingRecords": "Cargando...",
		    //         	"oPaginate": {
		    //             				"sFirst":    "Primero",
		    //             				"sLast":     "Último",
		    //             				"sNext":     "Siguiente",
		    //             				"sPrevious": "Anterior"
		    //     					}
				//     },
				//     dom: 'Bfrtip'
			  //   });
			  // });
			  $(function () {
            $('#tblprincipal').DataTable({
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
                        className: 'btn btn-md btn-default'
                    }
                ]
            });
        });

        // window.onload = function() {
        // 	trae_detalle();
        // }

		function fn_buscar(){
	    	let fecha_inicial = document.getElementById('fecha_inicio').value;
	    	let producto_id   = document.getElementById('producto_id').value;
	    	
	    	if(fecha_inicial == '') return false;
				let url = "{{ route('rpt_kardex_articulos', ['producto_id' => 'x1', 'fecha_inicial' => '2020-01-01']) }}";
		    	url = url.replace('2020-01-01', fecha_inicial);
		    	url = url.replace('x1', producto_id);
		    	location.href = url;
    }

    function generar_pdf(){
    	let fecha_inicial = document.getElementById('fecha_inicio').value;
    	let producto_id   = document.getElementById('producto_id').value;
    	let url = "{{ route('rpt_kardex_articulos_pdf', ['producto_id' => 'x1', 'fecha_inicial' => '2020-01-01']) }}";
		    	url = url.replace('2020-01-01', fecha_inicial);
		    	url = url.replace('x1', producto_id);
		    	// location.href = url;
		    	window.open(url, '_blank');
    }
	</script>
@endsection