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
@section('title', 'Transacciones de Inventario')

@section('content_header')
	<br>
@endsection

@section('content')
	<div class="container-fluid">
	    <div class="row">
	        <div class="col-12 col-lg-10 offset-lg-1">
	            <div class="card shadow-sm">
	                <div class="card-header py-2" style="background-color: #E1E8ED;">
	                    <div class="d-flex justify-content-between align-items-center">
	                        <h5 class="mb-0 font-weight-bold text-secondary text-truncate" style="font-size: 1.1rem;">
	                            Transacciones de Inventario
	                        </h5>
	                        <div class="d-flex">
	                            <button type="button" class="btn btn-xs btn-outline-primary rounded-circle elevation-2 mr-2" title="Agregar Registro" onclick="fn_agregar(); return false;">
	                                <i class="fas fa-plus-circle"></i>
	                            </button>
	                            <a href="{{ route('home') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-2" title="Cerrar Ventana">
	                                <i class="fas fa-sign-out-alt"></i>
	                            </a>
	                        </div>
	                    </div>
	                </div>

	                <div class="card-body p-0 p-md-3"> <div class="table-responsive">
	                        <table class="table table-sm table-striped table-hover mb-0" id="tblprincipal">
	                            <thead class="bg-light">
	                                <tr class="text-center" style="font-size: 11px; text-transform: uppercase;">
	                                    <th class="text-left pl-3">Descripción</th>
	                                    <th class="d-none d-sm-table-cell">Movimiento</th>
	                                    <th>Tipo</th>
	                                    <th class="d-none d-md-table-cell">Estado</th>
	                                    <th style="width: 40px;">&nbsp;</th>
	                                </tr>	
	                            </thead>
	                            <tbody>
	                                @foreach($listado as $l)
	                                    <tr class="text-center" style="font-size: 12px;">
	                                        <td class="text-left pl-3 align-middle font-weight-md-normal">
	                                            {{ $l->descripcion }}
	                                        </td>
	                                        <td class="align-middle d-none d-sm-table-cell">
	                                            <span class="badge badge-pill {{ $l->signo == 1 ? 'badge-info' : 'badge-warning' }}">
	                                                {{ $l->signo == 1 ? 'Entrada' : 'Salida' }}
	                                            </span>
	                                        </td>
	                                        <td class="align-middle text-uppercase">
	                                            @switch($l->tipo_transaccion)
	                                                @case('C') Compra @break
	                                                @case('V') Venta @break
	                                                @case('A') Ajuste @break
	                                                @case('T') Traslado @break
	                                                @default ? @break
	                                            @endswitch
	                                        </td>
	                                        <td class="align-middle d-none d-md-table-cell">
	                                            <span class="badge {{ $l->estado == '1' ? 'badge-success' : 'badge-danger' }}">
	                                                {{ $l->estado == '1' ? 'Alta' : 'Baja' }}
	                                            </span>
	                                        </td>
	                                        <td class="align-middle">
	                                            @php $Id= Crypt::encrypt($l->id); @endphp
	                                            <button type="button" class="btn btn-xs btn-warning rounded-circle elevation-2" title="Editar" onclick="fn_edicion('{{ $Id }}')">
	                                                <i class="fas fa-edit"></i>
	                                            </button>
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
	@include('inv_transacciones.partials.modals_invtransacciones')
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

	            	if (response.signo == 1) {
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

	            	if (response.estado == 1) {
	            		$('#eestado').prop('checked', true);
	            	}else{
	            		$('#eestado').prop('checked', false);
	            	}

	            	$('#editarModalCenter').on('shown.bs.modal', function () {
				  		$('#edescripcion').trigger('focus')
					});
					
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