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
@section('title', 'Paises')
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
                            <h6>Paises</h6>
                        </div>
                        <div class="col-md-3" style="text-align: right;">
                            <button type="button" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Agregar Registro" onclick="fn_agregar(); return false;">
                                <i class="fas fa-plus-circle"></i>
                            </button>
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
                                                <th>Nombre</th>
                                                <th>Abreviatura</th>
                                                <th>Código</th>
                                                <th>Estado</th>
                                                <th>&nbsp;</th>
                                            </tr>   
                                        </thead>
                                    <tbody>
                                        @foreach($listado as $l)
                                        <tr class="text-center" style="font-size: 12px;">
                                            <td>{{ $l->nombre }}</td>
                                            <td>{{ $l->abreviatura }}</td>
                                            <td>{{ $l->cod_area }}</td>
                                            @if($l->estado == 1)
                                                <td>Alta</td>
                                            @else
                                                <td>Baja</td>
                                            @endif
                                            @php $Id= Crypt::encrypt($l->id); @endphp
                                            <td><a href="#" class="btn btn-xs btn-warning rounded-circle elevation-4" title="Editar Empresa" onclick="fn_edicion('{{$Id}}')"><i class="fas fa-edit"></i></a></td>
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

    <!-- agregar Modal -->
    <div class="modal fade" id="agregarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form role="form" id="formaNuevoRegistro" method="POST" action="{{route('pais_grabar')}}">
                    @csrf
                    <div class="card">
                        <div class="card-header" style="background-color: #F4F6F7;">
                            <div class="row">
                                <div class="col-md-9">
                                    <h6>Nuevo Registro</h6>
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <button type="submit" id="submitButton" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Grabar"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>  
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Nombre</label>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Nombre Páis" aria-label="Username" aria-describedby="basic-addon1" id="nombre" name="nombre" autofocus required value="{{ old('nombre')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Abreviatura</label>
                                    </div>
                                    <input type="text" style="text-transform: uppercase;" class="form-control" placeholder="GT" aria-label="Username" aria-describedby="basic-addon1" id="abreviatura" name="abreviatura" required value="{{ old('abreviatura')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Código de Área</label>
                                    </div>
                                    <input type="number" step="1" min="1" style="text-align: right;" class="form-control" placeholder="502" aria-label="Username" aria-describedby="basic-addon1" id="cod_area" name="cod_area" required value="{{ old('cod_area')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="1">
                                        <label class="custom-control-label" for="estado">Activar</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /agregar Modal -->
    <!-- editar Modal -->
    <div class="modal fade" id="editarModalCenter" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form role="form" method="POST" action="{{route('pais_actualizar')}}">
                    @csrf
                    <div class="card">
                        <div class="card-header" style="background-color: #F4F6F7;">
                            <div class="row">
                                <div class="col-md-9">
                                    <h6>Edición de Registro</h6>
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>  
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="eid" name="eid">
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="enombre">Nombre</label>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Nombre Páis" aria-label="Username" aria-describedby="basic-addon1" id="enombre" name="enombre" autofocus required value="{{ old('enombre')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="eabreviatura">Abreviatura</label>
                                    </div>
                                    <input type="text" style="text-transform: uppercase;" class="form-control" placeholder="GT" aria-label="Username" aria-describedby="basic-addon1" id="eabreviatura" name="eabreviatura" required value="{{ old('eabreviatura')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="ecod_area">Código de Área</label>
                                    </div>
                                    <input type="number" step="1" min="1" style="text-align: right;" class="form-control" placeholder="502" aria-label="Username" aria-describedby="basic-addon1" id="ecod_area" name="ecod_area" required value="{{ old('ecod_area')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="eestado" name="eestado" value="A">
                                        <label class="custom-control-label" for="eestado">Activar</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /editar Modal -->
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
            document.getElementById('nombre').value  = '';
            /*$('#plural').prop('checked', false);
            $('#estado').prop('checked', false);*/
            $('#agregarModalCenter').on('shown.bs.modal', function () {
                $('#nombre').trigger('focus');
            });
            
            $("#agregarModalCenter").modal();
        }

        //========================================================================
        // Levantar modal de edición
        //========================================================================
        function fn_edicion(id){
            $.ajax({
                url: "{{ route('pais_editar') }}",
                type: "GET",
                dataType: 'json',
                data: {"_token": "{{ csrf_token() }}", 
                       id : id},
                success: function(response){
                    document.getElementById('eid').value           = id;
                    document.getElementById('enombre').value       = response.nombre;
                    document.getElementById('eabreviatura').value  = response.abreviatura;
                    document.getElementById('ecod_area').value     = response.cod_area;

                    if (response.estado == '1') {
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


        $(document).ready(function() {
            $('#formaNuevoRegistro').on('submit', function() {
                // Deshabilitar el botón de submit cuando se envíe el formulario
                $('#submitButton').prop('disabled', true);
                // $('#submitButton').text('Enviando...');
            });
        });
    </script>
@endsection