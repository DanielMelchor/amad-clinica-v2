@extends('adminlte::page')
@section('css')
	<meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.css')}}">
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
@section('title', 'Unidades de Medida')

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
							<h6>Unidad de Medida</h6>
						</div>
						<div class="col-md-3" style="text-align: right;">
							<button type="button" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Agregar Registro" onclick="fn_agregar(); return false;">
								<i class="fas fa-plus-circle"></i>
							</button>
							<a href="{{ route('home') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></a>
						</div>
					</div>
				</div>
				<form class="form-horizontal">
					<div class="card-body">
						<div class="row">
							<div class="col-md-10 offset-md-1">
								<div class="table-responsive">
									<table class="table table-sm table-striped table-hover" id="tblprincipal">
										<thead class="thead-primary text-center">
											<tr style="font-size: 12px;">
												<th>Descripción</th>
												<th>Abreviatura</th>
												<th>Estado</th>
												<th></th>
											</tr>	
										</thead>
										<tbody>
											@foreach($pUnidadmedidas as $pUnidadmedida)
												<tr class="text-center" style="font-size: 12px;">
													<td>{{ $pUnidadmedida->descripcion}}</td>
													<td>{{ $pUnidadmedida->siglas}}</td>
													@if($pUnidadmedida->estado == 1)
														<td>Alta</td>
													@else
														<td>Baja</td>
													@endif
													<td>
														@php $Id= Crypt::encrypt($pUnidadmedida->id); @endphp
														<a href="#" class="btn btn-xs btn-warning rounded-circle elevation-4" title="Editar" onclick="fn_edicion('{{ $Id }}')"><i class="fas fa-edit"></i></a>
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
	<!-- agregar Modal -->
	<div class="modal fade" id="agregarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    		<div class="modal-content">
      			<form role="form" id="formaNuevoRegistro" method="POST" action="{{route('unidadmedida_grabar')}}">
	      			@csrf
	      			<div class="card">
	      				<div class="card-header" style="background-color: #F4F6F7;">
	      					<div class="row">
		        				<div class="col-md-9">
		        					<h6>Nuevo Registro</h6>
		        				</div>
		        				<div class="col-md-3" style="text-align: right;">
		        					<button type="submit" id="submitButton" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
		        					<button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
		        				</div>
		        			</div>	
	      				</div>
	      				<div class="card-body">
	      					<div class="row text-center">
							    <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">Descripción</label>
								  	</div>
								  	<input type="text" class="form-control" placeholder="Descripción a mostrar" id="descripcion" name="descripcion" autofocus required value="{{ old('descripcion')}}">
								</div>
							</div>
							<div class="row">
								<div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">Siglas&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
								  	</div>
								  	<input type="text" class="form-control" placeholder="Siglas" id="siglas" name="siglas" required value="{{ old('siglas')}}">
								</div>
					    	</div>
					    	<div class="row">
								<div class="form-group input-group-sm offset-md-1 col-md-5">
						            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
						              	<input type="checkbox" class="custom-control-input" id="aplica_receta" name="aplica_receta" value="S">
						          		<label class="custom-control-label" for="aplica_receta">Utilizado en receta</label>
						        	</div>
						      	</div>
								<div class="form-group">
						            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
						              	<input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
						          		<label class="custom-control-label" for="estado">Activar</label>
						        	</div>
						      	</div>
							</div>
	      				</div>
	      			</div>
      			</form>
    		</div>
  		</div>
	</div>
	<!-- /agregar Modal -->
	<!-- editar Modal -->
	<div class="modal fade" id="editarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="editarModalCenterTitle" aria-hidden="true">
  		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    		<div class="modal-content">
      			<form role="form" method="POST" action="{{route('unidadmedida_actualizar')}}">
	      			@csrf
	      			<div class="card">
	      				<div class="card-header" style="background-color: #F4F6F7;">
	      					<div class="row">
		        				<div class="col-md-9">
		        					<h6>Edición de Registro</h6>
		        				</div>
		        				<div class="col-md-3" style="text-align: right;">
		        					<button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
		        					<button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
		        				</div>
		        			</div>	
	      				</div>
	      				<div class="card-body">
	      					<input type="hidden" id="eid" name="eid">
	      					<div class="row text-center">
							    <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">Descripción</label>
								  	</div>
								  	<input type="text" class="form-control" placeholder="Descripción a mostrar" id="edescripcion" name="edescripcion" autofocus required>
								</div>
							</div>
							<div class="row">
								<div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">Siglas&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
								  	</div>
								  	<input type="text" class="form-control" placeholder="Siglas" id="esiglas" name="esiglas" required>
								</div>
					    	</div>
					    	<div class="row">
								<div class="form-group input-group-sm offset-md-1 col-md-5">
						            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
						              	<input type="checkbox" class="custom-control-input" id="eaplica_receta" name="eaplica_receta" value="S">
						          		<label class="custom-control-label" for="eaplica_receta">Utilizado en receta</label>
						        	</div>
						      	</div>
								<div class="form-group">
						            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
						              	<input type="checkbox" class="custom-control-input" id="eestado" name="eestado" value="A">
						          		<label class="custom-control-label" for="eestado">Activar</label>
						        	</div>
						      	</div>
							</div>
	      				</div>
	      			</div>
      			</form>
    		</div>
  		</div>
	</div>
	<!-- /editar Modal -->
@endsection
@section('js')
	<script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
	@if(Session::has('success'))
        <script>
            swal("Trabajo Finalizado", "{!! Session::get('success') !!}", "success")
        </script>
    @endif
    @if(Session::has('error'))
        <script>
            swal("Error !!!", "{!! Session::get('error') !!}", "error")
        </script>
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
		//========================================================================
		// Levantar modal de Agregar
		//========================================================================
		function fn_agregar(){
			document.getElementById('descripcion').value  = '';
        	/*$('#plural').prop('checked', false);
        	$('#estado').prop('checked', false);*/
        	$('#agregarModalCenter').on('shown.bs.modal', function () {
		  		$('#descripcion').trigger('focus');
			});
			jQuery.noConflict();
			$("#agregarModalCenter").modal();
		}

		//========================================================================
		// Levantar modal de edición
		//========================================================================
		function fn_edicion(id){
			console.log(id);
			$.ajax({
	        	url: "{{ route('unidadmedida_editar') }}",
		        type: "POST",
		        dataType: 'json',
		        data: {"_token": "{{ csrf_token() }}", 
		               id : id},
	            success: function(response){
	            	document.getElementById('eid').value           = id;
	            	document.getElementById('edescripcion').value  = response.descripcion;
	            	document.getElementById('esiglas').value       = response.siglas;

	            	if (response.aplica_receta == 'S') {
	            		$('#eaplica_receta').prop('checked', true);
	            	}else{
	            		$('#eaplica_receta').prop('checked', false);
	            	}

	            	if (response.estado == 1) {
	            		$('#eestado').prop('checked', true);
	            	}else{
	            		$('#eestado').prop('checked', false);
	            	}

	            	$('#editarModalCenter').on('shown.bs.modal', function () {
				  		$('#enombre').trigger('focus');
					});
					jQuery.noConflict();
					$("#editarModalCenter").modal();
	            },
	            error: function(error){
		            console.log(error);
		        }
		    });
		}

		$(document).ready(function() {
            $('#formaNuevoRegistro').on('submit', function() {
                // Deshabilitar el botón de submit cuando se envíe el formulario
                $('#submitButton').prop('disabled', true);
                // $('#submitButton').text('Enviando...');
            });
        });
    </script>
@endsection