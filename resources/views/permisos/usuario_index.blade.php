@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endsection
@section('title', 'Usuarios')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #E1E8ED;">
                    <div class="row">
                        <div class="col-md-9">
                            <h6>Usuarios</h6>
                        </div>
                        <div class="col-md-3" style="text-align: right;">
                            <a href="{{ route('usuario_agregar') }}" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Agregar Usuario"><i class="fas fa-plus-square"></i></a>
                            <a href="{{ route('home') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <form class="form-horizontal">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <table class="table table-sm table-striped text-center" id="tblprincipal" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th>Role</th>
                                            <th>Nombre</th>
                                            <th>Usuario</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($listado as $l)
                                            <tr>
                                                <td>{{ $l->role_name }}</td>
                                                <td>{{ $l->name }}</td>
                                                <td>{{ $l->username }}</td>
                                                <td><a href="#" class="btn btn-xs btn-warning rounded-circle elevation-4" onclick="fnEditarUsuario({{$l->id}}); return false;" title="Editar Registro"><i class="fas fa-edit"></i></a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
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

        function fnEditarUsuario($id){
            var url = "{{ route('usuario_editar', ['user_id' => 0]) }}";
            url = url.replace('0', $id);
            location.href = url;
        }
    </script>
@endsection