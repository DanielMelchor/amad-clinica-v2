@extends('adminlte::page')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.css')}}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
        /* Ajustes para pantallas pequeñas */
        @media (max-width: 576px) {
            .card-header h5 { font-size: 1.1rem; }
            .btn-xs { padding: .25rem .4rem; font-size: .875rem; }
            .input-group-text { width: 100% !important; border-radius: 0.25rem 0.25rem 0 0 !important; }
            .input-group-prepend { width: 100%; }
        }

        .btn-guardar { background-color: #A5C890 !important; }
        .table-responsive { width: 100%; margin-bottom: 1rem; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        
        /* Centrado de controles de DataTable en móvil */
        .dataTables_wrapper .row { display: flex; flex-wrap: wrap; justify-content: center; }
    </style>
@endsection

@section('title', 'Bodegas')

@section('content_header')
    <div class="mb-2"></div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-10 offset-lg-1">
                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Bodegas</h5>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-circle elevation-2" title="Agregar Registro" onclick="fn_agregar();">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-danger rounded-circle elevation-2" title="Salir">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body px-2 px-md-4">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover w-100" id="tblprincipal">
                                <thead class="thead-light">
                                    <tr style="font-size: 13px;">
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>	
                                </thead>
                                <tbody style="font-size: 13px;">
                                    @foreach($listado as $l)
                                        <tr>
                                            <td>{{ $l->descripcion }}</td>
                                            <td>
                                                <span class="badge {{ $l->estado == 1 ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $l->estado == 1 ? 'Alta' : 'Baja' }}
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                @php $Id= Crypt::encrypt($l->id); @endphp
                                                <button class="btn btn-xs btn-warning rounded-circle elevation-2" onclick="fn_edicion('{{ $Id }}')">
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

    {{-- Los modales se mantienen similares pero usamos clases de padding de Bootstrap 4 --}}
    @include('bodegas.partials.modals_bodegas') {{-- Sugerencia: Mover modales a un partial para limpiar el index --}}
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
		  		$('#descripcion').trigger('focus');
			});
			$("#agregarModalCenter").modal();
		}

		//========================================================================
		// Levantar modal de edición
		//========================================================================
		function fn_edicion(id){
			$.ajax({
	        	url: "{{ route('bodega_editar') }}",
		        type: "POST",
		        dataType: 'json',
		        data: {"_token": "{{ csrf_token() }}", id : id},
	            success: function(response){
	            	document.getElementById('eid').value           = id;
	            	document.getElementById('edescripcion').value  = response.descripcion;

	            	if (response.estado == 1) {
	            		$('#eestado').prop('checked', true);
	            	}else{
	            		$('#eestado').prop('checked', false);
	            	}

	            	$('#editarModalCenter').on('shown.bs.modal', function () {
				  		$('#edescripcion').trigger('focus');
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