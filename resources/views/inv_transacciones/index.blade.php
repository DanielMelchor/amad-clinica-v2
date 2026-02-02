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
@section('title', 'Transacciones de Inventario')

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
							<h5>Transaccíones de Inventario</h5>
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
									<table class="table table-sm table-striped table-hover text-center" id="tblprincipal">
										<thead class="thead-primary">
											<tr style="font-size: 12px;">
												<th>Descripción</th>
												<th>Movimiento</th>
												<th>Tipo</th>
												<th>Estado</th>
												<th>&nbsp;</th>
											</tr>	
										</thead>
										<tbody>
											@foreach($listado as $l)
												<tr style="font-size: 12px;">
													<td>{{ $l->descripcion }}</td>
													<td>
														@if($l->signo == 1)
															Entrada
														@else
															Salida
														@endif
													</td>
													<td>
														@switch($l->tipo_transaccion)
															@case('C')
																Compra
																@break
															@case('V')
																Venta
																@break
															@case('A')
																Ajuste
																@break
															@case('T')
																Traslado
																@break
															@default
																No definido
																@break
														@endswitch
													</td>
													<td>
														@if($l->estado == 'A')
															Alta
														@else
															Baja
														@endif
													</td>
													<td>
														@php $Id= Crypt::encrypt($l->id); @endphp
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
      			<form role="form" id="formaNuevoRegistro" method="POST" action="{{route('grabar_invtransaccion')}}">
	      			@csrf
	      			<div class="card">
	      				<div class="card-header" style="background-color: #F4F6F7;">
	      					<div class="row">
		        				<div class="col-md-9">
		        					<h5>Nuevo Registro</h5>
		        				</div>
		        				<div class="col-md-3" style="text-align: right;">
		        					<button type="submit" id="submitButton" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
		        					<button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
		        				</div>
		        			</div>
	      				</div>
	      				<div class="card-body">
	      					<div class="row">
								<div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
							  		<div class="input-group-prepend">
								    	<span class="input-group-text">Descripción</span>
								  	</div>
								  	<input type="text" class="form-control" placeholder="Descripción" aria-label="Username" aria-describedby="basic-addon1" id="descripcion" name="descripcion" autofocus required value="{{ old('descripcion')}}">
								</div>
							</div>
							<div class="row">
								<div class="col-md-10 offset-md-1">
		                            <div class="form-group form-control-sm clearfix">
		                                <label for="entrada">Movimiento</label>
		                                &nbsp;
		                                <div class="icheck-primary d-inline">
		                                    <input type="radio" id="entrada" name="signo" value="1" checked>
		                                    <label for="entrada">Entrada</label>
		                                </div>
		                                &nbsp;
		                                <div class="icheck-primary d-inline">
		                                    <input type="radio" id="negativo" name="signo" value="-1">
		                                    <label for="negativo">Salida</label>
		                                </div>
		                            </div>
		                        </div>
							</div>
							<div class="row">
								<div class="col-md-11 offset-md-1">
		                            <div class="form-group form-control-sm clearfix">
		                                <label for="compra">Tipo</label>
		                                &nbsp;
		                                <div class="icheck-primary d-inline">
		                                    <input type="radio" id="compra" name="tipo_transaccion" value="C" checked>
		                                    <label for="compra">Compra</label>
		                                </div>
		                                &nbsp;
		                                <div class="icheck-primary d-inline">
		                                    <input type="radio" id="venta" name="tipo_transaccion" value="V">
		                                    <label for="venta">Venta</label>
		                                </div>
		                                &nbsp;
		                                <div class="icheck-primary d-inline">
		                                    <input type="radio" id="ajuste" name="tipo_transaccion" value="A">
		                                    <label for="ajuste">Ajuste</label>
		                                </div>
		                                &nbsp;
		                                <div class="icheck-primary d-inline">
		                                    <input type="radio" id="traslado" name="tipo_transaccion" value="T">
		                                    <label for="traslado">Traslado</label>
		                                </div>
		                            </div>
		                        </div>
							</div>
							<div class="row">
                                <div class="form-group input-group-sm mb-1 col-md-10 offset-md-1">
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
	<div class="modal fade" id="editarModalCenter" role="dialog" aria-labelledby="editarModalCenterTitle" aria-hidden="true">
  		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    		<div class="modal-content">
      			<form role="form" method="POST" action="{{route('actualizar_invtransaccion')}}">
	      			@csrf
	      			<div class="card">
	      				<div class="card-header" style="background-color: #F4F6F7;">
	      					<div class="row">
		        				<div class="col-md-9">
		        					<h5>Edición de Registro</h5>
		        				</div>
		        				<div class="col-md-3" style="text-align: right;">
		        					<button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
		        					<button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
		        				</div>
		        			</div>
	      				</div>
	      				<div class="card-body">
	      					<input type="hidden" id="eid" name="eid">
	      					<div class="row">
								<div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
							  		<div class="input-group-prepend">
								    	<span class="input-group-text">Descripción</span>
								  	</div>
								  	<input type="text" class="form-control" placeholder="Descripción" aria-label="Username" aria-describedby="basic-addon1" id="edescripcion" name="edescripcion" autofocus required>
								</div>
							</div>
							<div class="row">
								<div class="col-md-10 offset-md-1">
		                            <div class="form-group form-control-sm clearfix">
		                                <label for="entrada">Movimiento</label>
		                                &nbsp;
		                                <div class="icheck-primary d-inline">
		                                    <input type="radio" id="eentrada" name="esigno" value="1" checked>
		                                    <label for="eentrada">Entrada</label>
		                                </div>
		                                &nbsp;
		                                <div class="icheck-primary d-inline">
		                                    <input type="radio" id="esalida" name="esigno" value="-1">
		                                    <label for="esalida">Salida</label>
		                                </div>
		                            </div>
		                        </div>
							</div>
							<div class="row">
								<div class="col-md-11 offset-md-1">
		                            <div class="form-group form-control-sm clearfix">
		                                <label for="compra">Tipo</label>
		                                &nbsp;
		                                <div class="icheck-primary d-inline">
		                                    <input type="radio" id="ecompra" name="etipo_transaccion" value="C" checked>
		                                    <label for="ecompra">Compra</label>
		                                </div>
		                                &nbsp;
		                                <div class="icheck-primary d-inline">
		                                    <input type="radio" id="eventa" name="etipo_transaccion" value="V">
		                                    <label for="eventa">Venta</label>
		                                </div>
		                                &nbsp;
		                                <div class="icheck-primary d-inline">
		                                    <input type="radio" id="eajuste" name="etipo_transaccion" value="A">
		                                    <label for="eajuste">Ajuste</label>
		                                </div>
		                                &nbsp;
		                                <div class="icheck-primary d-inline">
		                                    <input type="radio" id="etraslado" name="etipo_transaccion" value="T">
		                                    <label for="etraslado">Traslado</label>
		                                </div>
		                            </div>
		                        </div>
							</div>
							<div class="row">
                                <div class="form-group mb-1 col-md-10 offset-md-1">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="eestado" name="eestado" value="A">
                                        <label class="custom-control-label" for="eestado">Activar</label>
                                    </div>
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
		  		$('#descripcion').trigger('focus')
			});
			jQuery.noConflict();
			$("#agregarModalCenter").modal();
		}

		//========================================================================
		// Levantar modal de edición
		//========================================================================
		function fn_edicion(id){
			document.getElementById('eentrada').enable = true;
			document.getElementById('esalida').enable = true;
			$.ajax({
	        	url: "{{ route('editar_transaccion') }}",
		        type: "POST",
		        dataType: 'json',
		        data: {"_token": "{{ csrf_token() }}", 
		               id : id},
	            success: function(response){
	            	document.getElementById('eid').value           = response.id;
	            	document.getElementById('edescripcion').value  = response.descripcion;

	            	if (response.signo == '1') {
	            		$('#eentrada').prop('checked', true);
	            	}else{
	            		$('#esalida').prop('checked', true);
	            	}

	            	switch (response.tipo_transaccion){
	            		case 'C':
	            			$('#ecompra').prop('checked', true);
	            			break;
	            		case 'V':
	            			$('#eventa').prop('checked', true);
	            			break;
	            		case 'A':
	            			$('#eajuste').prop('checked', true);
	            			break;
	            		case 'T':
	            			$('#etraslado').prop('checked', true);
	            			break;
	            		default:
	            			$('#ecompra').prop('checked', true);
	            			break;
	            	}

	            	if (response.estado == 'A') {
	            		$('#eestado').prop('checked', true);
	            	}else{
	            		$('#eestado').prop('checked', false);
	            	}

	            	$('#editarModalCenter').on('shown.bs.modal', function () {
				  		$('#edescripcion').trigger('focus')
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