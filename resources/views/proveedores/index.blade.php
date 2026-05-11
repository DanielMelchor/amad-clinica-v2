@extends('adminlte::page')
@section('css')
@endsection
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-11 col-lg-10">
                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Proveedores</h6>
                            <div class="btn-group-xs">
                                <a href="{{ route('crear_proveedor') }}" class="btn btn-sm btn-outline-primary rounded-circle elevation-2" title="Crear Proveedor">
                                    <i class="fas fa-plus-circle"></i>
                                </a>
                                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-danger rounded-circle elevation-2" title="Salir">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-0 p-sm-3"> <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover mb-0" id="tblprincipal">
                                <thead class="thead-light">
                                    <tr style="font-size: 12px; text-align: center;">
                                        <th>NIT</th>
                                        <th>Nombre</th>
                                        <th class="d-none d-md-table-cell">Dirección</th>
                                        <th>Teléfonos</th>
                                        <th class="d-none d-lg-table-cell">Correo Electrónico</th>
                                        <th>Estado</th>
                                        <th style="width: 50px;"> Acciones </th>
                                    </tr>   
                                </thead>
                                <tbody style="font-size: 13px;">
                                    @foreach($proveedores as $p)
                                        <tr class="text-center">
                                            <td>{{ $p->nit }}</td>
                                            <td class="align-middle text-left text-md-center font-weight-md-normal">
                                                {{ $p->nombre_comercial }}
                                            </td>
                                            <td class="align-middle d-none d-md-table-cell">{{ $p->direccion }}</td>
                                            <td class="align-middle small">{{ $p->telefonos }}</td>
                                            <td class="align-middle d-none d-lg-table-cell">{{ $p->email }}</td>
                                            <td class="align-middle">
                                                <span class="badge badge-pill {{ $p->estado == '1' ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $p->estado == '1' ? 'Alta' : 'Baja' }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('editar_proveedor', $p->id) }}" class="btn btn-sm btn-warning rounded-circle shadow-sm" title="Editar">
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
    </script>
@endsection