@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('title', 'Usuarios')

@section('content_header')
	<h3>Listado prueba</h3>
@endsection

@section('content')
	<div class="card card-navy">
		<div class="card-header">
			<div class="row">
				<div class="col-md-3 offset-md-9" style="text-align: right;">
					<a href="{{ route('usuario_crear')}}" class="btn btn-sm btn-primary img-circle elevation-4" title="Crear Registro"><i class="fas fa-plus-circle"></i></a>
					<a href="#" class="btn btn-sm btn-danger img-circle elevation-4" onclick="confirma_salida(); return false;" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
				</div>
			</div>
		</div>
		<form class="form-horizontal">
			<div class="card-body">
			</div>
		</form>
	</div>
	<div class="card card-navy">
		<div class="card-header">
			<div class="row">
				
			</div>
		</div>
		<form class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-md-10 offset-md-1">
						<div class="table-responsive">
							<table class="table table-sm table-striped table-hover" id="tblprincipal">
								<thead class="thead-primary">
									<tr>
										<th scope="col" class="text-center">Usuario</th>
										<th scope="col" class="text-center">Colaborador</th>
										<th scope="col" class="text-center">Estado</th>
										<th scope="col" class="text-center">Empresa</th>
										<th scope="col" class="text-center">Caja</th>
										<th>&nbsp;</th>
									</tr>	
								</thead>
								<tbody>
									@foreach($listado as $l)
										<tr class="text-center">
											<td>{{ $l->username}}</td>
											<td>{{ $l->name }}</td>
											@if($l->estado == 'A')
												<td>Alta</td>
											@else
												<td>Baja</td>
											@endif
											<td>{{ $l->nombre_comercial }}</td>
											<td>{{ $l->nombre_maquina }}</td>
											<td><a href="{{route('usuario_editar' , $l->id)}}" class="btn btn-sm btn-warning img-circle elevation-4" title="Editar Usuario"><i class="fas fa-edit"></i></a></td>
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
	<div class="row">
		<div class="col-md-10 offset-md-1">
			<div class="panel panel-primary">
				<div class="panel-header">
				</div>
				<div class="panel-body">
					
					<!-- {{ trans('adminlte_lang::message.logged') }} -->
				</div>
			</div>
		</div>
	</div>
@endsection
@section('js')
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

      	function confirma_salida(){
            swal({
                title: 'Confirmación',
                Swal.fire({

                title: 'Confirmación',

                text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?",

text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?",
                icon: 'warning',

                showCancelButton: true,

                confirmButtonClass: 'btn-success',

                cancelButtonClass: 'btn-danger',

                confirmButtonText: 'Si',

                cancelButtonText: 'No',

                closeOnConfirm: false,

                allowEscapeKey: true

                },

                function(isConfirm) {

                    if (isConfirm) { 

                        if (origen == 'P') {

                            window.location.href = "{{ route('pacientes') }}";

                        }

                        if (origen == 'A') {

                            window.location.href = "{{ route('nueva_agenda') }}";

                        }

                        // history.back();

                        

                    } 

                }

            );
                showCancelButton: true,
                confirmButtonClass: 'btn-success',
                cancelButtonClass: 'btn-danger',
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                closeOnConfirm: false,
                allowEscapeKey: true
                },
                function(isConfirm) {
                    if (isConfirm) { 
                        window.location.href = "{{ route('home') }}";
                                    } 
                    else { 
                        swal("Cancelled", "Your imaginary file is safe :)", "error"); 
                        }
            });
        }
	</script>
@endsection