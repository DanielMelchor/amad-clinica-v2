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
@section('title', 'Productos')

@section('content_header')
    <br>
@endsection

@section('content')
	<div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-10 offset-lg-1">
                <div class="card shadow-sm">
                    <div class="card-header d-flex align-items-center" style="background-color: #E1E8ED;">
                        <h5 class="mb-0 flex-grow-1 font-weight-bold">Productos</h5>
                        
                        <div class="ml-auto">
                            <a href="{{ route('crear_producto')}}" class="btn btn-sm btn-outline-primary rounded-circle elevation-2" title="Crear Producto">
                                <i class="fas fa-plus"></i>
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-danger rounded-circle elevation-2" title="Salir">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-1 p-md-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped table-hover w-100" id="tblprincipal">
                                        <thead class="thead-light" style="font-size: 13px;">
                                            <tr>
                                                <th>Clasificación</th>
                                                <th>Descripción</th>
                                                <th class="text-center">Estado</th>
                                                <th class="text-right">Acciones</th>
                                            </tr>   
                                        </thead>
                                        <tbody style="font-size: 13px;">
                                            @foreach($pProductos as $pProducto)
                                                <tr>
                                                    <td class="align-middle">{{ $pProducto->clasificacion }}</td>
                                                    <td class="align-middle">{{ $pProducto->descripcion }}</td>
                                                    <td class="align-middle text-center">
                                                        <span class="badge {{ $pProducto->estado == 1 ? 'badge-success' : 'badge-danger' }}">
                                                            {{ $pProducto->estado == 1 ? 'Alta' : 'Baja' }}
                                                        </span>
                                                    </td>
                                                    <td class="align-middle text-right">
                                                        @php $productoId= Crypt::encrypt($pProducto->id); @endphp
                                                        <a href="{{ route('editar_producto', $productoId) }}" class="btn btn-xs btn-warning rounded-circle elevation-2" title="Editar">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
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
    <script>
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
    </script>
@endsection