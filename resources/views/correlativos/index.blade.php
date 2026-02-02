@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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
    </style>
@endsection
@section('title', 'Correlativos')
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
                            <h6>Correlativos</h6>
                        </div>
                        <div class="col-md-3" style="text-align: right;">
                            <!-- <a href="{{ route('crear_empresa', ['P', '0'])}}" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Nuevo Registro"><i class="fas fa-plus-circle"></i></a> -->
                            <button type="button" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" data-toggle="modal" data-target="#correlativoModal" title="Nuevo Registro"><i class="fas fa-plus-circle"></i></button>
                            <a href="{{ route('home') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover text-center" id="tblprincipal">
                                	<thead class="thead-primary">
										<tr style="font-size: 12px;">
											<th class="text-center">Empresa</th>
											<th class="text-center">Tipo</th>
											<th class="text-center">Correlativo</th>
											<th></th>
										</tr>	
									</thead>
									<tbody>
										@foreach($correlativos as $correlativo)
											<tr class="text-center" style="font-size: 12px;">		
												<td>{{ $correlativo->nombre_comercial }}</td>
												<td>{{ $correlativo->tipo }}</td>
												<td class="numero">{{ $correlativo->correlativo }}</td>
												@php $correlativoId= Crypt::encrypt($correlativo->id); @endphp
												<td><a href="#" class="btn btn-xs btn-warning rounded-circle elevation-4" title="Editar Correlativo" onclick="trae_correlativo( '{{$correlativoId}}'); return false;"><i class="fas fa-edit"></i></a></td>
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

    	<!-- Editar Correlativo Modal -->
	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="editarCorrelativoModal" role="dialog" aria-labelledby="editCorrelativoModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-dialog-centered" role="document">
	    	<div class="modal-content">
      			<form role="form" method="POST" action="{{ route('actualizar_correlativo_1') }}">
	      			@csrf
		      		<div class="card">
	        			<div class="card-header" style="background-color: #F4F6F7;">
	        				<div class="row">
	        					<div class="col-md-9">
	        						<h6>Edición de Correlativo</h6>
	        					</div>
	        					<div class="col-md-3" style="text-align: right;">
	        						<button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
	        						<button type="button" class="btn btn-xs btn-outline-danger  rounded-circle elevation-4" data-dismiss="modal" title="Cerrar"> <i class="fas fa-sign-out-alt"></i></button>	
	        					</div>
	        				</div>
	        			</div>
	        			<div class="card-body">
	        				<input type="hidden" id="editid" name="editid">
	        				<!-- Incluir Empresas -->
    						@include('parciales', ['empresas' => $empresas])
	        				<div class="row text-center">
								<div class="form-group input-group col-md-10 offset-md-1">
					            	<div class="input-group-prepend">
								    	<span class="input-group-text" for="edittipo_id">Tipo</span>
								  	</div>
					            	<select id ="edittipo_id" name="edittipo_id" class="form-control select2" disabled required>
					              		<option value="">Seleccionar .....</option>
					              		<option value="A">Admision</option>
					              		<option value="P">Paciente</option>
					            	</select>
								</div>
							</div>
							<div class="row">
								<div class="form-group input-group col-md-10 offset-md-1">
					      			<div class="input-group-prepend">
								    	<span class="input-group-text" for="editcorrelativo">Correlativo</span>
								  	</div>
					  				<input type="number" min="0" step="1" class="form-control" id="editcorrelativo" name="editcorrelativo" placeholder="0" style="text-align: right;" autofocus required>
								</div>
							</div>
	        			</div>
	        		</div>
	      		</form>
	    	</div>
	  	</div>
	</div>
	<!-- /Editar Correlativo Modal -->
    <!-- Agregar Correlativo Modal -->
	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="correlativoModal" role="dialog" aria-labelledby="correlativoModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-dialog-centered" role="document">
	    	<div class="modal-content">
      			<form role="form" id="formaNuevoRegistro" method="POST" action="{{ route('grabar_correlativo_1') }}">
	      			@csrf
		      		<div class="card">
	        			<div class="card-header" style="background-color: #F4F6F7;">
	        				<div class="row">
	        					<div class="col-md-9">
	        						<h6>Nuevo Correlativo</h6>
	        					</div>
	        					<div class="col-md-3" style="text-align: right;">
	        						<button type="submit" id="submitButton" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
	        						<button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar"> <i class="fas fa-sign-out-alt"></i></button>	
	        					</div>
	        				</div>
	        				
	        			</div>
	        			<div class="card-body">
	        				<!-- Incluir Empresas -->
    						@include('parciales', ['empresas' => $empresas])
	        				<div class="row text-center">
								<div class="form-group input-group col-md-10 offset-md-1 mb-1">
					            	<div class="input-group-prepend">
								    	<span class="input-group-text" for="tipo_id">Tipo</span>
								  	</div>
					            	<select id ="tipo_id" name="tipo_id" class="form-control select2" autofocus required>
					              		<option value="">Seleccionar .....</option>
					              		<option value="A">Admision</option>
					              		<option value="P">Paciente</option>
					            	</select>
								</div>
							</div>
							<div class="row">
								<div class="form-group input-group col-md-10 offset-md-1 mb-1">
					      			<div class="input-group-prepend">
								    	<span class="input-group-text" for="tipo_id">Correlativo</span>
								  	</div>
					  				<input type="number" min="0" step="1" class="form-control" id="correlativo" name="correlativo" placeholder="0" value="{{ old('correlativo')}}" style="text-align: right;" required>
								</div>
							</div>
	        			</div>
	        		</div>
	      		</form>
	    	</div>
	  	</div>
	</div>
	<!-- /Agregar Correlativo Modal -->
@endsection
@section('js')
	@if(Session::get('type') == 'success')
        @if(Session::has('message'))
            <script>
                
                setTimeout(function() {
                    Swal.fire({
                        title: "Trabajo Finalizado",
                        text: "{{ Session::get('message') }}",
                        icon: 'success', // En v2 es 'icon', no 'type'
                        confirmButtonColor: '#28a745', // Color success de AdminLTE
                        showConfirmButton: true,
                        confirmButtonText: 'Aceptar'
                    });
                }, 1000);
            </script>
        @endif
    @endif
    @if(Session::get('type') == 'error')
        @if(Session::has('message'))
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: "Error",
                        text: "{!! Session::get('message') !!}",
                        icon: 'error', // En v2 es 'icon', no 'type'
                        showConfirmButton: true,
                        confirmButtonText: 'Aceptar'
                    });
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

      	function trae_correlativo(id){
			$.ajax({
				headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('trae_correlativo') }}",
                method: "POST",
                data: {id : id},
                success: function(response){
                	document.getElementById('editid').value = id;
                	$('#empresa_id').val(response['empresa_id']).trigger('change');
                	$('#empresa_id').prop('disabled', true);
                	document.getElementById('edittipo_id').value = response['tipo'];
                	$('#edittipo_id').change();
                	document.getElementById('editcorrelativo').value = response['correlativo'];
                	$('#editarCorrelativoModal').modal('show')
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

		function confirma_salida(){
            swal({
                title: 'Confirmación',
                text: 'Seguro de Salir, si ha realizado cambios estos no seran guardados ?',
                type: 'warning',
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