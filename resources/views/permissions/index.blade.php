@extends('layouts.principal')
@section('css_principal')
	<meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endsection
@section('title')
	Listado de Permisos
@endsection
@section('content')
	<div class="content-fluid">
		<div class="row justify-content-center">
	        <div class="col-md-12">
	            <div class="card">
	                <div class="card-header">
	                    <div class="row">
	                        <div class="col-md-4">
								<h3>{{ __('Permisos') }}</h3>
							</div>
							<div class="col-md-2 offset-md-4">
	                            <button type="button" class="btn btn-sm btn-block btn-primary" data-toggle="modal" data-target="#permisoModal" title="Crear nuevo permiso"><i class="fas fa-plus"></i> Agregar</button>
	                        </div>
							<div class="col-md-2">
								<a href="{{ route('home') }}" class="btn btn-sm btn-block rojo"><i class="fas fa-sign-out-alt"></i> Salir</a>
							</div>
	                    </div>
	                </div>

	                <div class="card-body">
	                    <div class="row text-center">
							<div class="col-md-5 offset-md-4">
								<table id="tblpermisos" class="table table-sm table-bordered table-striped table-hover">
									<thead style="bg-navy">
										<tr>
											<th>Permiso</th>
											<th>&nbsp;</th>
										</tr>
									</thead>
									<tbody>
										@foreach($permisos as $p)
										<tr>
											<td>{{ $p->name }}</td>
											@php $Id= Crypt::encrypt($p->id); @endphp
											<td><a href="{{ route('editar_permiso', $Id) }}" class="btn btn-sm btn-warning" title="Editar Permiso"><i class="fa fa-edit"></i> Editar</a></td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
	                </div>
	            </div>
	        </div>
    	</div>
    </div>
	<!-- Modal -->
	<div class="modal fade" id="permisoModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="permisoModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
	    	<div class="modal-content">
	      		<form role="form" method="POST" action="{{ route('grabar_permiso') }}">
	      			@csrf
	        		<div class="card card-navy">
	        			<div class="card-header">
	        				<div class="row">
	        					<div class="col-md-4">
	        						Nuevo Permiso
	        					</div>
	        					<div class="col-md-2 offset-md-4">
	        						<button type="submit" class="btn btn-sm btn-block verde" title="Grabar"><i class="fas fa-save"></i> Guardar</button>
	        					</div>
	        					<div class="col-md-2">
	        						<button type="button" class="btn btn-sm btn-block rojo" title="Regresar a lista de familias" data-dismiss="modal"><i class="fas fa-sign-out-alt"></i> Cerrar</button>
	        					</div>
	        				</div>
	        			</div>
	        			<div class="card-body">
	        				<br>
	        				<div class="row text-center">
	        					<div class="col-md-10 offset-md-1">
	        						<label for="name">Nombre</label>
	        					</div>
	        				</div>
	        				<div class="row text-center">
			        			<div class="input-group col-md-10 offset-md-1">
			        				<input type="text" class="form-control" placeholder="nombre de permiso" aria-label="name" aria-describedby="addon-wrapping" id="name" name="name" required>
			        			</div>
			        		</div>	
	        			</div>
	        		</div>
	      		</form>
	    	</div>
	  	</div>
	</div>
@endsection
@section('js_principal')
	<script type="text/javascript">
		$('#permisoModal').on('shown.bs.modal', function() {
		  $('#name').focus();
		});

		$(function () {
	        $('#tblpermisos').DataTable({
	          "paging": true,
	          "lengthChange": false,
	          "searching": true,
	          "ordering": true,
	          "info": true,
	          "autoWidth": true,
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
	        });
    	});
	</script>
@endsection