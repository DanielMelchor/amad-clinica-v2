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
@section('title', 'Bancos')
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
							<h5>Bancos y Casas Emisoras</h5>
						</div>
						<div class="col-md-3" style="text-align: right;">
							<button type="button" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Agregar Registro" onclick="fn_agregar(); return false;"><i class="fas fa-plus-circle"></i></button>
							<a href="{{ route('home') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
						</div>
					</div>
				</div>
				<form class="form-horizontal">
					<div class="card-body">
						<div class="row">
							<div class="col-md-10 offset-md-1">
								<div class="table-responsive">
									<table id="tblprincipal" class="table table-sm table-striped table-hover">
										<thead class="thead-primary" style="font-size: 12px;">
											<tr>
												<th scope="col" class="text-center">Descripción</th>
												<th scope="col" class="text-center">Referencia</th>
												<th scope="col" class="text-center">Estado</th>
												<th></th>
											</tr>	
										</thead>
										<tbody>
											@foreach($pBancos as $pBanco)
												<tr class="text-center" style="font-size: 12px;">
													<td>{{ $pBanco->nombre}}</td>
													@if($pBanco->tipo_referencia == 'B')
														<td>Banco</td>
													@else
														<td>Emisor Tarjeta</td>
													@endif
													@if($pBanco->estado == 1)
														<td>Alta</td>
													@else
														<td>Baja</td>
													@endif
													<td>
														@php $Id= Crypt::encrypt($pBanco->id); @endphp
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
					<div class="card-footer">
					</div>
				</form>
			</div>
        </div>
    </div>
	<!-- agregar Modal -->
	<div class="modal fade" id="agregarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="agregarModalCenterTitle" aria-hidden="true">
  		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    		<div class="modal-content">
      			<form role="form" id="formaNuevoRegistro" method="POST" action="{{route('banco_grabar')}}">
	      			@csrf
	      			<div class="card">
	        			<div class="card-header" style="background-color: #F4F6F7;">
	        				<div class="row">
	        					<div class="col-md-9">
	        						<h5>Nuevo Registro</h5>
	        					</div>
	        					<div class="col-md-3" style="text-align: right;">
	        						<button type="submit" id="submitButton" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
	        						<button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar"> <i class="fas fa-sign-out-alt"></i></button>	
	        					</div>
	        				</div>
	      				</div>
	      				<div class="card-body">
	      					<div class="row">
								<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
							  		<div class="input-group-prepend">
								    	<span class="input-group-text">Nombre</span>
								  	</div>
								  	<input type="text" class="form-control" placeholder="nombre aseguradora" aria-label="Username" aria-describedby="basic-addon1" id="nombre" name="nombre" autofocus required value="{{ old('nombre')}}">
								</div>

								<div class="form-group form-control-sm clearfix offset-md-1">
		                            <label for="tipo_referencia01">Referencia&nbsp;&nbsp;&nbsp;</label>
		                            <div class="icheck-primary d-inline">
		                                <input type="radio" id="tipo_referencia01" name="tipo_referencia" value="B" checked>
		                                <label for="tipo_referencia01">Banco&nbsp;&nbsp;&nbsp;</label>
		                            </div>
		                            <div class="icheck-primary d-inline">
		                                <input type="radio" id="tipo_referencia02" name="tipo_referencia" value="T">
		                                <label for="tipo_referencia02">Casa Emisora</label>
		                            </div>
		                        </div>
					    	</div>
							<div class="form-group offset-md-1">
					            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
					              	<input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
					          		<label class="custom-control-label" for="estado">Activar</label>
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
      			<form role="form" method="POST" action="{{route('banco_actualizar')}}">
	      			@csrf
	      			<div class="card">
	        			<div class="card-header" style="background-color: #F4F6F7;">
	      					<div class="row">
	        					<div class="col-md-9">
	        						<h5>Edición de Registro</h5>
	        					</div>
	        					<div class="col-md-3" style="text-align: right;">
	        						<button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
	        						<button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar"> <i class="fas fa-sign-out-alt"></i></button>
	        					</div>
	        				</div>
	      				</div>
	      				<div class="card-body">
	      					<input type="hidden" id="eid" name="eid">
	      					<div class="row">
								<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
							  		<div class="input-group-prepend">
								    	<span class="input-group-text">Nombre</span>
								  	</div>
								  	<input type="text" class="form-control" placeholder="nombre aseguradora" aria-label="Username" aria-describedby="basic-addon1" id="enombre" name="enombre" autofocus required>
								</div>

								<div class="form-group form-control-sm clearfix offset-md-1">
		                            <label for="tipo_referencia01">Referencia&nbsp;&nbsp;&nbsp;</label>
		                            <div class="icheck-primary d-inline">
		                                <input type="radio" id="etipo_referencia01" name="etipo_referencia" value="B" checked>
		                                <label for="etipo_referencia01">Banco&nbsp;&nbsp;&nbsp;</label>
		                            </div>
		                            <div class="icheck-primary d-inline">
		                                <input type="radio" id="etipo_referencia02" name="etipo_referencia" value="T">
		                                <label for="etipo_referencia02">Casa Emisora</label>
		                            </div>
		                        </div>
					    	</div>
							<div class="form-group offset-md-1">
					            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
					              	<input type="checkbox" class="custom-control-input" id="eestado" name="eestado" value="A">
					          		<label class="custom-control-label" for="eestado">Activar</label>
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
		//========================================================================
		// Levantar modal de Agregar
		//========================================================================
		function fn_agregar(){
			document.getElementById('nombre').value  = '';
        	/*$('#plural').prop('checked', false);
        	$('#estado').prop('checked', false);*/
        	$('#agregarModalCenter').on('shown.bs.modal', function () {
		  		$('#nombre').trigger('focus');
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
	        	url: "{{ route('banco_editar') }}",
		        type: "POST",
		        dataType: 'json',
		        data: {"_token": "{{ csrf_token() }}", 
		               id : id},
	            success: function(response){
	            	document.getElementById('eid').value           = id;
	            	document.getElementById('enombre').value       = response.nombre;

	            	if (response.tipo_referencia == 'B') {
	            		$('#etipo_referencia01').prop('checked', true);
	            	}else{
	            		$('#etipo_referencia02').prop('checked', true);
	            	}

	            	if (response.estado == 1) {
	            		$('#eestado').prop('checked', true);
	            	}else{
	            		$('#eestado').prop('checked', false);
	            	}

	            	$('#editarModalCenter').on('shown.bs.modal', function () {
				  		$('#enombre').trigger('focus');
					});
					jQuery.noConflict()
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