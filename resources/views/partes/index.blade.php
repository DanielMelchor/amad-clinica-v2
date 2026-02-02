@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
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
@section('title', 'Partes del Cuerpo')

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
                            <h6>Partes del cuerpo</h6>
                        </div>
                        <div class="col-md-3" style="text-align: right;">
                            <button type="button" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Agregar Registro" onclick="fn_agregar(); return false;"><i class="fas fa-plus-circle"></i></button>
                            <a href="{{ route('home') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
                        </div>
                    </div>
                </div>
                <form class="form-horizontal">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped table-hover" id="tblprincipal">
                                        <thead class="thead-primary text-center">
                                            <tr class="text-center" style="font-size: 12px;">
                                                <th>Nombre</th>
                                                <th>Plural</th>
                                                <th>Estado</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($partes as $p)
                                                <tr class="text-center" style="font-size: 12px;">
                                                    <td>{{ $p->nombre }}</td>
                                                    @if($p->plural == 'S')
                                                        <td>Sí</td>
                                                    @else
                                                        <td>No</td>
                                                    @endif
                                                    @if($p->estado == 1)
                                                        <td>Alta</td>
                                                    @else
                                                        <td>Baja</td>
                                                    @endif
                                                    <td>
                                                        @php $Id= Crypt::encrypt($p->id); @endphp
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
                </form>
            </div>
        </div>
    </div>
    <!-- agregar Modal -->
    <div class="modal fade" id="agregarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="agregarModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form role="form" method="POST" action="{{route('parte_grabar')}}">
                    @csrf
                    <div class="card">
                        <div class="card-header" style="background-color: #F4F6F7;">
                            <div class="row">
                                <div class="col-md-9">
                                    <h6>Nuevo Registro</h6>
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>  
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Nombre</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="parte del cuerpo" aria-label="Username" aria-describedby="basic-addon1" id="nombre" name="nombre" autofocus required value="{{ old('nombre')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-6 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Plural</span>
                                    </div>
                                    <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" id="texto_plural" name="texto_plural" value="{{ old('texto_plural')}}" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 offset-md-1">
                                    <div class="form-group form-group-sm offset-md-1">
                                        <div class="custom-control custom-control-sm custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="plural" name="plural" value="S">
                                            <label class="custom-control-label" for="plural">Plural</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group offset-md-1">
                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
                                            <label class="custom-control-label" for="estado">Activar</label>
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
    <!-- /agregar Modal -->

    <!-- editar Modal -->
    <div class="modal fade" id="editarModalCenter" data-backdrop="static" data-keyboard="false"  tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form role="form" method="POST" action="{{route('parte_actualizar')}}">
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
                                        <span class="input-group-text">Nombre</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="parte del cuerpo" aria-label="Username" aria-describedby="basic-addon1" id="enombre" name="enombre" autofocus required value="{{ old('enombre')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-6 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Plural</span>
                                    </div>
                                    <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" id="etexto_plural" name="etexto_plural" value="{{ old('etexto_plural')}}" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 offset-md-1 mb-1">
                                    <div class="form-group form-group-sm offset-md-1">
                                        <div class="custom-control custom-control-sm custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="eplural" name="eplural" value="S">
                                            <label class="custom-control-label" for="eplural">Plural</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group offset-md-1">
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
    <script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
    @if(Session::get('type') == 'success')
        @if(Session::has('message'))
            <script>
                
                setTimeout(function() {
                    swal({
                        title: "Trabajo Finalizado",
                        text: "{!! Session::get('message') !!}",
                        type: "success"
                    }
                    // , function() {
                    //     window.location = "{{ route('empresas') }}";
                    // }
                    );
                }, 1000);
            </script>
        @endif
    @endif
    @if(Session::get('type') == 'error')
        @if(Session::has('message'))
            <script>
                setTimeout(function() {
                    swal({
                        title: "Error",
                        text: "{!! Session::get('message') !!}",
                        type: "error"
                    }
                    // , function() {
                    //     window.location = "{{ route('empresas') }}";
                    // }
                    );
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
        // si se habilita que la parte del cuerpo tiene plural solicita el texto
        // a agregar al final de la parte del cuerpo
        //========================================================================
        $(document).ready(function(){
            $("input[name=plural]").click(function () {    
                if (document.getElementById("plural").checked == true) {
                    $("#texto_plural").removeAttr('disabled'); 
                    $('#texto_plural').prop("required", true);
                    document.getElementById('texto_plural').value = 's';
                }else{
                    $("#texto_plural").attr('disabled','disabled'); 
                    $('#texto_plural').removeAttr("required");
                    document.getElementById('texto_plural').value = '';
                }
            });
         });

        //========================================================================
        // si se habilita que la parte del cuerpo tiene plural solicita el texto
        // a agregar al final de la parte del cuerpo en edición
        //========================================================================
        $(document).ready(function(){
            $("input[name=eplural]").click(function () {    
                if (document.getElementById("eplural").checked == true) {
                    $("#etexto_plural").removeAttr('disabled'); 
                    $('#etexto_plural').prop("required", true);
                    document.getElementById('etexto_plural').value = 's';
                }else{
                    $("#etexto_plural").attr('disabled','disabled'); 
                    $('#etexto_plural').removeAttr("required");
                    document.getElementById('etexto_plural').value = '';
                }
            });
         });

        //========================================================================
        // Levantar modal de Agregar Parte del cuerpo
        //========================================================================
        function fn_agregar(){
            document.getElementById('nombre').value       = '';
            document.getElementById('texto_plural').value = '';
            $('#plural').prop('checked', false);
            $('#estado').prop('checked', false);
            $('#agregarModalCenter').on('shown.bs.modal', function () {
                $('#nombre').trigger('focus')
            });
            jQuery.noConflict();
            $("#agregarModalCenter").modal();
        }

        //========================================================================
        // Levantar modal de edición de Parte del cuerpo
        //========================================================================
        function fn_edicion(id){
            $.ajax({
                url: "{{ route('parte_editar') }}",
                type: "POST",
                dataType: 'json',
                data: {"_token": "{{ csrf_token() }}", id : id},
                success: function(response){
                    document.getElementById('eid').value           = id;
                    document.getElementById('enombre').value       = response.nombre;
                    document.getElementById('etexto_plural').value = response.texto_plural;
                    if (response.plural == 'S') {
                        $('#eplural').prop('checked', true);
                        $("#etexto_plural").removeAttr('disabled'); 
                        $('#etexto_plural').prop("required", true);
                    }else{
                        $('#eplural').prop('checked', false);
                        $("#etexto_plural").attr('disabled','disabled'); 
                        $('#etexto_plural').removeAttr("required");
                    }
                    if (response.estado == 1) {
                        $('#eestado').prop('checked', true);
                    }else{
                        $('#eestado').prop('checked', false);
                    }
                    $('#editarModalCenter').on('shown.bs.modal', function () {
                        $('#enombre').trigger('focus')
                    });
                    jQuery.noConflict();
                    $("#editarModalCenter").modal();
                },
                error: function(error){
                    console.log(error);
                }
            });
        }
    </script>
@endsection