@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.css')}}">
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
@section('title', 'Documentos')
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
                            <h6>Documentos</h6>
                        </div>
                        <div class="col-md-3" style="text-align: right;">
                            <button type="button" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Agregar Registro" onclick="fn_agregar(); return false;">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                            <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" onclick="confirma_salida(); return false;" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
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
                                            <th>Descripción</th>
                                            <th>Estado</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($listado as $l)
                                            <tr class="text-center" style="font-size: 12px;">
                                                <td>{{$l->descripcion}}</td>
                                                @if($l->estado == 1)
                                                    <td>Alta</td>
                                                @else
                                                    <td>Baja</td>
                                                @endif
                                                <td>
                                                    @php $Id= Crypt::encrypt($l->id); @endphp
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
    <div class="modal fade" id="agregarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="editarModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <form role="form" id="formaNuevoRegistro" method="POST" action="{{route('tipodocumento_grabar')}}">
                    @csrf
                    <div class="card">
                        <div class="card-header" style="background-color: #F4F6F7;">
                            <div class="row">
                                <div class="col-md-9">
                                    <h6>Nuevo Registro</h6>
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <button type="submit" id="submitButton" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>  
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Descripción</label>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Observaciones" id="descripcion" name="descripcion" autofocus required value="{{ old('descripcion')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1 mb-1">
                                    <div class="form-group clearfix">
                                        <label for="positivo">Signo del documento</label>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="positivo" name="signo" value="1" checked>
                                            <label for="positivo">Positivo</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="negativo" name="signo" value="-1">
                                            <label for="negativo">Negativo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1 input-group input-group-sm mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Transaccion de Inventario</span>
                                    </div>
                                    <select class="custom-select custom-select-sm select2 select2bs4" id="inventario_transaccion_id" name="inventario_transaccion_id">
                                        <option value="" selected>Seleccionar...</option>
                                        @foreach($inv_trn as $ti)
                                            <option value="{{ $ti->id }}">{{ $ti->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1 mb-1">
                                    <div class="form-group clearfix">
                                        <label for="positivo">Movimiento Interno</label>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="venta" name="tipo_interno" value="VT" checked>
                                            <label for="venta">Venta</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="nota_credito" name="tipo_interno" value="NC">
                                            <label for="nota_credito">NC</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="nota_debito" name="tipo_interno" value="ND">
                                            <label for="nota_debito">ND</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="recibo_pago" name="tipo_interno" value="RP">
                                            <label for="recibo_pago">Recibo de pago</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1 input-group mb-2">
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
    <!-- edicion Modal -->
    <div class="modal fade" id="editarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="editarModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <form role="form" method="POST" action="{{route('tipodocumento_actualizar')}}">
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
                                <div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Descripción</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Descripcion a mostrar" id="edescripcion" name="edescripcion" autofocus required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1 mb-1">
                                    <div class="form-group clearfix">
                                        <label for="positivo">Signo del documento</label>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="epositivo" name="esigno" value="1" checked>
                                            <label for="epositivo">Positivo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="enegativo" name="esigno" value="-1">
                                            <label for="enegativo">Negativo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1 input-group input-group-sm mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Transaccion de Inventario</span>
                                    </div>
                                    <select class="custom-select custom-select-sm select2 select2bs4" id="einventario_transaccion_id" name="einventario_transaccion_id">
                                        <option value="" selected>Seleccionar...</option>
                                        @foreach($inv_trn as $ti)
                                            <option value="{{ $ti->id }}">{{ $ti->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1 mb-1">
                                    <div class="form-group clearfix">
                                        <label for="positivo">Movimiento &nbsp;</label>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="eventa" name="etipo_interno" value="VT" checked>
                                            <label for="eventa">Venta&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="enota_credito" name="etipo_interno" value="NC">
                                            <label for="enota_credito">Nota de crédito&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="enota_debito" name="etipo_interno" value="ND">
                                            <label for="enota_debito">Nota de débito&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="erecibo_pago" name="etipo_interno" value="RP">
                                            <label for="erecibo_pago">Recibo de pago</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1 input-group mb-1">
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
    <!-- /edicion Modal -->
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
        /*=========================================================================================
        Inicialización de librerias
        =========================================================================================*/
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()
            $('.select2bs4').select2({ theme: 'bootstrap4' })
        });

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
            jQuery.noConflict();
            $("#agregarModalCenter").modal();
        }

        //========================================================================
        // Levantar modal de edición
        //========================================================================
        function fn_edicion(id){
            $.ajax({
                url: "{{ route('tipodocumento_editar') }}",
                type: "POST",
                dataType: 'json',
                data: {"_token": "{{ csrf_token() }}", 
                       id : id},
                success: function(response){
                    console.log(response);
                    document.getElementById('eid').value                        = id;
                    document.getElementById('edescripcion').value               = response.descripcion;
                    document.getElementById('einventario_transaccion_id').value = response.inventario_transaccion_id;
                    $('#einventario_transaccion_id').change();

                    if (response.signo == 1) {
                        $("#epositivo").prop("checked", true);
                    }else{
                        $("#enegativo").prop("checked", true);
                    }

                    switch (response.tipo_interno){
                    case 'VT':
                        $("#eventa").prop("checked", true);
                        break;
                    case 'NC':
                        $("#enota_credito").prop("checked", true);
                        break;
                    case 'ND':
                        $("#enota_debito").prop("checked", true);
                        break;
                    default :
                        $("#erecibo_pago").prop("checked", true);
                        break;
                    }

                    if (response.estado == 1) {
                        $('#eestado').prop('checked', true);
                    }else{
                        $('#eestado').prop('checked', false);
                    }

                    $('#editarModalCenter').on('shown.bs.modal', function () {
                        $('#enombre').trigger('focus');
                    });
                    jQuery.noConflict();
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