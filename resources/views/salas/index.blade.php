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
@section('title', 'Salas')
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
                            <h6>Salas</h6>
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
                                        <tr class="text-center" style="font-size: 12px;">
                                            <th>Nombre</th>
                                            <th>Hora Inicio</th>
                                            <th>Maximo Citas</th>
                                            <th>Minutos por Cita</th>
                                            <th>Estado</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($salas as $s)
                                            <tr class="text-center" style="font-size: 12px;">
                                                <td>{{ $s->sala_nombre }}</td>
                                                <td>{{ \Carbon\Carbon::parse($s->hora_inicio)->format('H:i:s') }}</td>
                                                <td>{{ $s->maximo_registros }}</td>
                                                <td>{{ $s->minutos_por_registro }}</td>
                                                @if($s->estado == 1)
                                                    <td>Alta</td>
                                                @else
                                                    <td>Baja</td>
                                                @endif
                                                @php $Id= Crypt::encrypt($s->id); @endphp
                                                <td>
                                                    <a href="#" class="btn btn-xs btn-warning rounded-circle elevation-4" title="Editar" onclick="fn_edicion('{{ $Id }}')"><i class="fas fa-edit"></i></a>
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

    <!-- agregar Modal -->
    <div class="modal fade" id="agregarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form role="form" id="formaNuevoRegistro" method="POST" action="{{route('sala_grabar')}}">
                    @csrf
                    <div class="card">
                        <div class="card-header" style="background-color: #F4F6F7;">
                            <div class="row">
                                <div class="col-md-9"><h6>Nuevo Registro</h6></div>
                                <div class="col-md-3" style="text-align: right;">
                                    <button type="submit" id="submitButton" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>  
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text w-100">Nombre</label>
                                    </div>
                                    <input type="text" class="form-control" placeholder="nombre Sala" aria-label="Username" aria-describedby="basic-addon1" id="sala_nombre" name="sala_nombre" autofocus required value="{{ old('sala_nombre')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text w-100">Hora Inicio</label>
                                    </div>
                                    <input type="time" class="form-control" aria-label="Username" aria-describedby="basic-addon1" id="hora_inicio" name="hora_inicio" autofocus required value="{{ old('hora_inicio')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text w-100">Máximo de Cítas</label>
                                    </div>
                                    <input type="number" min="1" step="1" class="form-control" placeholder="0" aria-label="Username" aria-describedby="basic-addon1" id="maximo_registros" name="maximo_registros" autofocus required value="{{ old('maximo_registros')}}" style="text-align: right;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group input-group-sm mb-3 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text w-100">Minútos por Cíta</label>
                                    </div>
                                    <input type="number" class="form-control" placeholder="0" aria-label="Username" aria-describedby="basic-addon1" id="minutos_x_cita" name="minutos_x_cita" autofocus required value="{{ old('minutos_x_cita')}}" style="text-align: right;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
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
    <div class="modal fade" id="editarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="editarModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form role="form" method="POST" action="{{route('sala_actualizar')}}">
                    @csrf
                    <div class="card">
                        <div class="card-header" style="background-color: #F4F6F7;">
                            <div class="row">
                                <div class="col-md-9">
                                    <h6>Edición de Registro</h6>
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Grabar"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>  
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="eid" name="eid">
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text w-100">Nombre</label>
                                    </div>
                                    <input type="text" class="form-control" placeholder="nombre Sala" aria-label="Username" aria-describedby="basic-addon1" id="esala_nombre" name="esala_nombre" autofocus required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text w-100">Hora Inicio</label>
                                    </div>
                                    <input type="time" class="form-control" aria-label="Username" aria-describedby="basic-addon1" id="ehora_inicio" name="ehora_inicio" autofocus required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text w-100">Máximo de Cítas</label>
                                    </div>
                                    <input type="number" min="1" step="1" class="form-control" placeholder="0" aria-label="Username" aria-describedby="basic-addon1" id="emaximo_registros" name="emaximo_registros" autofocus required style="text-align: right;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text w-100">Minútos por Cíta</label>
                                    </div>
                                    <input type="number" class="form-control" placeholder="0" aria-label="Username" aria-describedby="basic-addon1" id="eminutos_x_cita" name="eminutos_x_cita" autofocus required style="text-align: right;">
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
        // inicializar librerias
        //========================================================================
        $(function () {
            $('.select2').select2()
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        });

        //========================================================================
        // Levantar modal de Agregar
        //========================================================================
        function fn_agregar(){
            document.getElementById('sala_nombre').value  = '';
            /*$('#plural').prop('checked', false);
            $('#estado').prop('checked', false);*/
            $('#agregarModalCenter').on('shown.bs.modal', function () {
                $('#sala_nombre').trigger('focus');
            });
            
            $("#agregarModalCenter").modal();
        }

        //========================================================================
        // Levantar modal de edición
        //========================================================================
        function fn_edicion(id){
            $.ajax({
                url: "{{ route('sala_editar') }}",
                type: "POST",
                dataType: 'json',
                data: {"_token": "{{ csrf_token() }}", 
                       id : id},
                success: function(response){
                    document.getElementById('eid').value               = id;
                    document.getElementById('esala_nombre').value      = response.sala_nombre;
                    document.getElementById('ehora_inicio').value      = response.hora_inicio.substring(0,5);
                    document.getElementById('emaximo_registros').value = response.maximo_registros;
                    document.getElementById('eminutos_x_cita').value   = response.minutos_por_registro;

                    if (response.estado == 1) {
                        $('#eestado').prop('checked', true);
                    }else{
                        $('#eestado').prop('checked', false);
                    }

                    $('#editarModalCenter').on('shown.bs.modal', function () {
                        $('#esala_nombre').trigger('focus');
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