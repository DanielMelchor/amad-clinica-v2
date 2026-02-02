@extends('adminlte::page')
@section('css')
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
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
    </style>
@endsection
@section('title', 'Medicos')

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
							<h6>Medicos</h6>
						</div>
						<div class="col-md-3" style="text-align: right;">
							<a href="{{ route('crear_medico') }}" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Agregar Registro">
								<i class="fas fa-plus-circle"></i>
							</a>
							<a href="{{ route('home') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
						</div>
					</div>
				</div>
				<form class="form-horizontal">
					<div class="card-body">
						<div class="row">
							<div class="col-md-10 offset-md-1">
								<div class="table-responsive">
									<table class="table table-sm table-striped table-hover text-center" id="tblprincipal">
										<thead class="thead-primary">
											<tr style="font-size: 12px;">
												<th>Nombres</th>
												<th>Apellidos</th>
												<th>Celular</th>
												<th>Teléfono</th>
												<th>Localizador</th>
												<th>Estado</th>
												<th>&nbsp;</th>
											</tr>	
										</thead>
										<tbody>
											@foreach($pMedicos as $pMedico)
												<tr class="text-center" style="font-size: 12px;">
													<td>{{ $pMedico->nombres}}</td>
													<td>{{ $pMedico->apellidos}}</td>
													<td>{{ $pMedico->celular }}</td>
													<td>{{ $pMedico->telefono }}</td>
													<td>{{ $pMedico->localizador }}</td>
													@if($pMedico->estado == 1)
														<td>Alta</td>
													@else
														<td>Baja</td>
													@endif
													<td>
														@php $Id= Crypt::encrypt($pMedico->id); @endphp
														<a href="{{ route('editar_medico', $Id) }}" class="btn btn-xs btn-warning rounded-circle elevation-4" title="Editar"><i class="fas fa-edit"></i></a>
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- Modal -->
	<div class="modal fade" id="recetaModal" tabindex="-1" role="dialog" aria-labelledby="recetaModalLabel" aria-hidden="true">
  		<div class="modal-dialog" role="document">
	    	<div class="modal-content">
	      		<div class="modal-body">
	        		<div class="panel panel-primary">
						<div class="panel-heading text-center">
							<h3>Impresión de Receta</h3>
						</div>

						<div class="panel-body">
							<div class="row text-center">
								<div class="col-md-12">
									<p class="bg-primary">Pagina</p>
								</div>
							</div>
							<div class="row text-center">
								<div class="col-md-4 offset-md-2">
									<label>Largo</label>
									<input type="number" class="form-control" id="pagina_x" name="pagina_x">
								</div>
								<div class="col-md-4">
									<label>Ancho</label>
									<input type="number" class="form-control" id="pagina_y" name="pagina_y">
								</div>
							</div>
							<div class="row text-center">
								<div class="form-group col-md-4 offset-md-2">
				                	<label>Medida</label>
				                	<select id ="unidad_medida" name="unidad_medida" class="form-control has-success select2" style="width: 100%;">
							        	<option value=""> Seleccionar... </option>
							        	<option value="in"> Pulgadas </option>
							        	<option value="cm"> Centimetros </option>
							        	<option value="mm"> Milimetros </option>
				                	</select>
								</div>
								<div class="form-group col-md-4">
				                	<label>Orientación</label>
				                	<select id ="orientacion" name="orientacion" class="form-control has-success select2" style="width: 100%;">
							        	<option value=""> Seleccionar... </option>
							        	<option value="portrait"> Vertical </option>
							        	<option value="landscape"> Horizontal </option>
				                	</select>
								</div>
							</div>
							<div class="row text-center">
								<div class="col-md-12">
									<p class="bg-primary">Fecha</p>
								</div>
							</div>
							<div class="row text-center">
								<div class="col-md-4">
									<label>Día</label>
								</div>
								<div class="col-md-4">
									<label>Mes</label>
								</div>
								<div class="col-md-4">
									<label>Año</label>
								</div>
							</div>
							<div class="row text-center">
								<div class="col-md-2">
									<input type="number" class="form-control" id="dia_x" name="dia_x">
								</div>
								<div class="col-md-2">
									<input type="number" class="form-control" id="dia_y" name="dia_y">
								</div>
								<div class="col-md-2">
									<input type="number" class="form-control" id="mes_x" name="mes_x">
								</div>
								<div class="col-md-2">
									<input type="number" class="form-control" id="mes_y" name="mes_y">
								</div>
								<div class="col-md-2">
									<input type="number" class="form-control" id="anio_x" name="anio_x">
								</div>
								<div class="col-md-2">
									<input type="number" class="form-control" id="anio_y" name="anio_y">
								</div>
							</div>
							<br>
							<div class="row text-center">
								<div class="col-md-6">
									<p class="bg-primary">Paciente</p>
								</div>
								<div class="col-md-6">
									<p class="bg-primary">Tratamiento</p>
								</div>
							</div>
							<div class="row text-center">
								<div class="col-md-3">
									<input type="number" class="form-control" id="paciente_x" name="paciente_x">
								</div>
								<div class="col-md-3">
									<input type="number" class="form-control" id="paciente_y" name="paciente_y">
								</div>
								<div class="col-md-3">
									<input type="number" class="form-control" id="tratamiento_x" name="tratamiento_x">
								</div>
								<div class="col-md-3">
									<input type="number" class="form-control" id="tratamiento_y" name="tratamiento_y">
								</div>
							</div>
						</div>
						<div class="panel-footer">
							<div class="row">
								<div class="col-md-3 offset-md-9" style="text-align: right;">
									<a href="#" id="actualizar_receta" class="btn btn-sm btn-primary fa fa-save" title="Grabar" onclick="actualizar_config_receta()"></a>
									<a href="#" id="grabar_receta" class="btn btn-sm btn-primary fa fa-save" title="Grabar" onclick="grabar_config_receta()"></a>
									<button type="button" class="btn btn-sm btn-warning fa fa-sign-out" title="Cerrar" data-dismiss="modal"></button>
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
	@if(Session::get('type') == 'success')
        @if(Session::has('message'))
            <script>
                
                setTimeout(function() {
                    swal({
                        title: "Trabajo Finalizado",
                        text: "{!! Session::get('message') !!}",
                        type: "success"
                    }
                    // , function() {
                    //     window.location = "{{ route('empresas') }}";
                    // }
                    );
                }, 1000);
            </script>
        @endif
    @endif
    @if(Session::get('type') == 'error')
        @if(Session::has('message'))
            <script>
                setTimeout(function() {
                    swal({
                        title: "Error",
                        text: "{!! Session::get('message') !!}",
                        type: "error"
                    }
                    // , function() {
                    //     window.location = "{{ route('empresas') }}";
                    // }
                    );
                }, 1000);
            </script>
        @endif
    @endif
	<script type="text/javascript">
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

		function recetaModal(id){
			$.ajax({
				url: "{{ route('existe_config_receta') }}",
				type: "POST",
		        dataType: 'json',
		        data: {"_token": "{{ csrf_token() }}",medico_id : id},
		        success: function(response){
		        	var info = response;
		        	if (info == 0) {
		        		document.getElementById("actualizar_receta").style.display="none";
		        		$('#recetaModal').modal('show');
		        	} else{
		        		document.getElementById("grabar_receta").style.display="none";
		        		document.getElementById("pagina_x").value = 15;
		        		$('#recetaModal').modal('show');
		        	}
		        },
		        error: function(error){
		            console.log(error);
		        }
			});
		}
		function grabar_config_receta(){
			
		}
	</script>
@endsection
