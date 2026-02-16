@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endsection
@section('title', 'Permisos')
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
                            <h6>Permisos</h6>
                        </div>
                        <div class="col-md-3" style="text-align: right;">
                            <button type="button" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" data-toggle="modal" data-target="#permisoModal" title="Crear nuevo Permiso"><i class="fas fa-plus-circle"></i></button>
                            <a href="{{ route('home') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <form class="form-horizontal">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                <table class="table table-sm table-striped text-center" id="tblprincipal" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th>Permiso</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($listado as $l)
                                            <tr>
                                                <td>{{ $l->name }}</td>
                                                <td><a href="" class="btn btn-xs btn-warning rounded-circle elevation-4" onclick="trae_permiso({{$l->id}}); return false;" title="Editar"><i class="fas fa-edit"></i></a></td>
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

    <!-- Permiso Modal -->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="permisoModal" role="dialog" aria-labelledby="permisoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form role="form" method="POST" action="{{ route('permiso_grabar') }}">
                    @csrf
                    <div class="card">
                        <div class="card-header" style="background-color: #E1E8ED">
                            <div class="row">
                                <div class="col-md-9">
                                    <h5 class="subtitulo">Nuevo Permiso</h5>
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir" data-dismiss="modal"> <i class="fas fa-sign-out-alt"></i></button> 
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="input-group col-md-10 offset-md-1 mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Nombre</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="nombre de permiso" id="name" name="name" aria-label="Username" aria-describedby="basic-addon1" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Permiso Modal -->
    <!-- Editar Permiso Modal -->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="editarPermisoModal" role="dialog" aria-labelledby="editarPermisoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form role="formEdit" id="formEdit" method="POST" action="{{ route('permiso_actualizar') }}">
                    @csrf
                    <div class="card">
                        <div class="card-header" style="background-color: #E1E8ED;">
                            <div class="row">
                                <div class="col-md-9">
                                    <h6 class="subtitulo">Edición de Permiso</h6>
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir" data-dismiss="modal"> <i class="fas fa-sign-out-alt"></i></button> 
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="editid" name="editid">
                            <div class="row">
                                <div class="input-group col-md-10 offset-md-1 mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Nombre</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="nombre de permiso" id="editname" name="editname" aria-label="Username" aria-describedby="basic-addon1" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Editar Permiso Modal -->
@endsection
@section('js')
    @if(Session::get('type') == 'success')
        @if(Session::has('message'))
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: "¡Trabajo Finalizado!",
                        text: "{!! Session::get('message') !!}",
                        icon: "success", // Cambiado de 'type' a 'icon'
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                        customClass: {
                            confirmButton: 'btn btn-success'
                        },
                        buttonsStyling: false
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
                        title: "¡Trabajo Finalizado!",
                        text: "{!! Session::get('message') !!}",
                        icon: "error", // Cambiado de 'type' a 'icon'
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
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

        function trae_permiso(id){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('trae_permiso') }}",
                method: "POST",
                data: {id : id},
                success: function(response){
                    document.getElementById('editid').value = response.id;
                    document.getElementById('editname').value = response.name;
                    $('#editarPermisoModal').modal('show')
                },
                error: function(error){
                    console.log(error);
                }
            });
        }
    </script>
@endsection