@extends('adminlte::page')
@section('css')
	<meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endsection
@section('title', 'Diagnostico Médico')

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
							<h5>Diagnostico Médico</h5>
						</div>
						<div class="col-md-3" style="text-align: right;">
							<button type="button" class="btn btn-xs btn-primary img-circle elevation-4" title="Agregar Registro" onclick="fn_agregar(); return false;"><i class="fas fa-plus-circle"></i></button>
							<a href="{{ route('home') }}" class="btn btn-xs btn-danger img-circle elevation-4" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
						</div>
					</div>
				</div>
				<form class="form-horizontal">
					<div class="card-body">
						<div class="row">
							<div class="col-md-10 offset-md-1">
								<div class="table-responsive">
									<table id="tblprincipal" class="table table-sm table-striped table-hover">
										<thead class="thead-primary text-center">
											<tr>
												<th>Diagnostico</th>
												<th>Estado</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											@foreach($diagnosticos as $d)
												<tr class="text-center">
													<td>{{ $d->descripcion }}</td>
													@if($d->estado == 'A')
														<td>Alta</td>
													@else
														<td>Baja</td>
													@endif
													<td>
														@php $Id= Crypt::encrypt($d->id); @endphp
														<a href="#" class="btn btn-xs btn-warning img-circle elevation-4" title="Editar" onclick="fn_edicion('{{ $Id }}')"><i class="fas fa-edit"></i></a>
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
      			<form role="form" method="POST" action="{{route('diagnostico_grabar')}}">
	      			@csrf
	      			<div class="card">
	        			<div class="card-header" style="background-color: #F4F6F7;">
	      					<div class="row">
	        					<div class="col-md-9">
	        						<h5>Nuevo Registro</h5>
	        					</div>
	        					<div class="col-md-3" style="text-align: right;">
	        						<button type="submit" class="btn btn-xs btn-success img-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
	        						<button type="button" class="btn btn-xs btn-danger img-circle elevation-4" data-dismiss="modal" title="Cerrar"> <i class="fas fa-sign-out-alt"></i></button>	
	        					</div>
	        				</div>
	      				</div>
	      				<div class="card-body">
	      					<div class="row">
								<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
							  		<div class="input-group-prepend">
								    	<span class="input-group-text">Descripción</span>
								  	</div>
								  	<input type="text" class="form-control" placeholder="Descripción" aria-label="Username" aria-describedby="basic-addon1" id="descripcion" name="descripcion" autofocus required value="{{ old('descripcion')}}">
								</div>
							</div>
							<div class="row">
								<div class="col-md-2 offset-md-1">
									<div class="form-group offset-md-1">
					                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
					                      	<input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
				                      		<label class="custom-control-label" for="estado">Activar</label>
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
	<!-- /agregar Modal -->
	<!-- editar Modal -->
	<div class="modal fade" id="editarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="editarModalCenterTitle" aria-hidden="true">
  		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    		<div class="modal-content">
      			<form role="form" method="POST" action="{{route('diagnostico_actualizar')}}">
	      			@csrf
	      			<div class="card">
	        			<div class="card-header" style="background-color: #F4F6F7;">
	      					<div class="row">
	        					<div class="col-md-9">
	        						<h5>Edición de Registro</h5>
	        					</div>
	        					<div class="col-md-3" style="text-align: right;">
	        						<button type="submit" class="btn btn-xs btn-success img-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
	        						<button type="button" class="btn btn-xs btn-danger img-circle elevation-4" data-dismiss="modal" title="Cerrar"> <i class="fas fa-sign-out-alt"></i></button>
	        					</div>
	        				</div>
	      				</div>
	      				<div class="card-body">
	      					<input type="hidden" id="eid" name="eid">
	      					<div class="row">
								<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
							  		<div class="input-group-prepend">
								    	<span class="input-group-text">Descripción</span>
								  	</div>
								  	<input type="text" class="form-control" placeholder="Descripción" aria-label="Username" aria-describedby="basic-addon1" id="edescripcion" name="edescripcion" autofocus required>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2 offset-md-1">
									<div class="form-group offset-md-1">
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

		//========================================================================
		// Levantar modal de Agregar Parte del cuerpo
		//========================================================================
		function fn_agregar(){
			document.getElementById('descripcion').value = '';
        	$('#estado').prop('checked', false);
        	$('#agregarModalCenter').on('shown.bs.modal', function () {
		  		$('#descripcion').trigger('focus')
			});
			jQuery.noConflict()
			$("#agregarModalCenter").modal();
		}

		//========================================================================
		// Levantar modal de edición de Parte del cuerpo
		//========================================================================
		function fn_edicion(id){
			$.ajax({
	        	url: "{{ route('diagnostico_editar') }}",
		        type: "POST",
		        dataType: 'json',
		        data: {"_token": "{{ csrf_token() }}", id : id},
	            success: function(response){
	            	document.getElementById('eid').value           = id;
	            	document.getElementById('edescripcion').value  = response.descripcion;
	            	if (response.estado == 'A') {
	            		$('#eestado').prop('checked', true);
	            	}else{
	            		$('#eestado').prop('checked', false);
	            	}
	            	$('#editarModalCenter').on('shown.bs.modal', function () {
				  		$('#edescripcion').trigger('focus')
					});
					jQuery.noConflict()
					$("#editarModalCenter").modal();
	            },
	            error: function(error){
		            console.log(error);
		        }
		    });
		}
    </script>
@endsection