@extends('adminlte::page')
@section('css')
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
@section('title', 'Hospitales')

@section('content_header')
	<br>
@endsection

@section('content')
	<div class="container-fluid">
		<div class="row">
	        <div class="col-12 col-lg-10 offset-lg-1">
	            <div class="card shadow-sm">
	                <div class="card-header d-flex align-items-center" style="background-color: #E1E8ED;">
                        <h6 class="mb-0 flex-grow-1 font-weight-bold">Centros de Atención</h6>
                        
                        <div class="ml-auto">
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-circle elevation-2 btn-fixed-size" onclick="fn_agregar();">
                                <i class="fas fa-plus"></i>
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-danger rounded-circle elevation-2 btn-fixed-size">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                        </div>
	                </div>
	                <div class="card-body p-1 p-md-3">
	                	<div class="row">
							<div class="col-12">
								<div class="table-responsive">
									<table id="tblprincipal" class="table table-sm table-striped table-hover w-100">
										<thead class="thead-light" style="font-size: 13px;">
											<tr>
												<th>Nombre</th>
												<th>Dirección</th>
												<th>Teléfonos</th>
												<th>Estado</th>
												<th></th>
											</tr>
										</thead>
										<tbody style="font-size: 13px;">
											@foreach($pHospitales as $pHospital)
												<tr class="text-center">
													<td>
														{{ $pHospital->nombre}}
													</td>
													<td>
														{{ $pHospital->direccion}}
													</td>
													<td>
														{{ $pHospital->telefonos}}
													</td>
													<td>
														<span class="badge {{ $pHospital->estado == 1 ? 'badge-success' : 'badge-danger' }}">
		                                                    {{ $pHospital->estado == 1 ? 'Alta' : 'Baja' }}
		                                                </span>
	                                            	</td>
													<td>
														@php $Id= Crypt::encryptString($pHospital->id); @endphp
														<a href="#" class="btn btn-xs btn-warning rounded-circle elevation-2" title="Editar" onclick="fn_edicion('{{ $Id }}')"><i class="fas fa-edit"></i></a>
													</td>
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
    </div>
    @include('hospitales.partials.modals_hospitales')
@endsection
@section('js')
	@if(session('message'))
	    <script>
	        setTimeout(function() {
	            Swal.fire({
	                title: "{{ session('type') == 'success' ? '¡Trabajo Finalizado!' : '¡Atención!' }}",
	                text: "{!! session('message') !!}",
	                icon: "{{ session('type', 'info') }}", 
	                confirmButtonText: "Aceptar",
	                customClass: {
	                    confirmButton: "btn btn-{{ session('type') == 'success' ? 'success' : 'danger' }} elevation-2"
	                },
	                buttonsStyling: false
	            });
	        }, 500); // Bajé el tiempo a 500ms para que la respuesta se sienta más rápida
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
			document.getElementById('nombre').value  = '';
        	$('#agregarModalCenter').on('shown.bs.modal', function () {
		  		$('#nombre').trigger('focus');
			});
			$("#agregarModalCenter").modal();
		}

		//========================================================================
		// Levantar modal de edición
		//========================================================================
		function fn_edicion(id){
			var url = "{{ route('hospital_editar', ':id') }}";
			url = url.replace(':id', id);
			$.ajax({
	        	url: url,
		        type: "GET",
		        dataType: 'json',
		        data: {"_token": "{{ csrf_token() }}", 
		               id : id},
	            success: function(response){
	            	document.getElementById('eid').value         = id;
	            	document.getElementById('enombre').value     = response.nombre;
	            	document.getElementById('edireccion').value  = response.direccion;
	            	document.getElementById('etelefonos').value  = response.telefonos;
	            	document.getElementById('econtacto').value   = response.contacto;

	            	if (response.referencia == 'S') {
	            		$('#ereferencia').prop('checked', true);
	            	}else{
	            		$('#ereferencia').prop('checked', false);
	            	}

	            	if (response.principal_agenda == 'S') {
	            		$('#eprincipal_agenda').prop('checked', true);
	            	}else{
	            		$('#eprincipal_agenda').prop('checked', false);
	            	}

	            	if (response.estado == 1) {
	            		$('#eestado').prop('checked', true);
	            	}else{
	            		$('#eestado').prop('checked', false);
	            	}

	            	$('#editarModalCenter').on('shown.bs.modal', function () {
				  		$('#enombre').trigger('focus');
					});

					$("#editarModalCenter").modal();
	            },
	            error: function(error){
		            console.log(error);
		        }
		    });
		}
	</script>
@endsection