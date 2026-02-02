@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
        .nav-pills .nav-link.active,
        .show>.nav-pills .nav-link{
            background: #7FB3D5 !important;
            color: #000000 !important;
        }
        .numero{
            text-align: right;
        }

        .btn-guardar{
            background-color: #A5C890 !important;
        }
        .btn-salir{
            background-color: #dc3545 !important;
            color: white;
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
        input, textarea, select, button {
            font-size: 12px !important;
        }

        .link-disabled {
            pointer-events: none;  /* bloquea clics */
            cursor: not-allowed;   /* cursor de prohibido */
            color: gray;           /* estilo visual de "inactivo" */
            text-decoration: none; /* opcional, quitar subrayado */
        }

    </style>
@endsection
@section('title', 'Recepción de Pago')
@section('content_header')
    <br>
@endsection
@section('content')
    <form role="form" id="form-factura" method="post" action="#">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="background-color: #E1E8ED;">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="row">
                            <div class="col-sm-9">
                                <h6>Edición de Recibo</h6>
                            </div>
                            <div class="col-sm-3" style="text-align: right;">
                                <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Grabar"><i class="fas fa-save" title="Guardar"></i></button>
                                <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="background-color: white">
                        <input type="hidden" id="tipo_documento_id" name="tipo_documento_id" value="{{ $encabezado->tipo_documento_id }}">
                        <input type="hidden" id="resolucion_id" name="resolucion_id" value="{{ $encabezado->resolucion_id }}">
                        <input type="hidden" id="caja_id" name="caja_id" value="{{ $encabezado->caja_id }}">
                        <input type="hidden" id="caja_editar_documento" name="caja_editar_documento" value="{{ $caja->editar_documento}}">
                        <input type="hidden" id="recibo_estado" name="recibo_estado" value="{{ $encabezado->estado }}">
                        <input type="hidden" id="total_saldo" name="total_saldo">
                        <input type="hidden" id="total_pago" name="total_pago">
                        <input type="hidden" id="recibo_total_pago" name="recibo_total_pago">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div id="documento_cobro">
                                    <div class="card border-dark shadow mb-3">
                                        <div class="card-header bg-light">Documento de pago</div>
                                        <div class="card-body text-info">
                                            <div class="row">
                                                <div class="col-lg-5 offset-lg-1 col-sm-5 offset-sm-1">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Documento</label>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-center" id="documento_descripcion" name="documento_descripcion" value="{{ $documento->descripcion }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-sm-5">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Fecha</label>
                                                        </div>
                                                        <input type="date" class="form-control form-control-sm text-center" id="fecha_emision" name="fecha_emision" value="{{ $encabezado->fecha_emision }}" tabindex="1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-5 offset-lg-1 col-sm-5 offset-sm-1">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Serie</label>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-center" id="serie" name="serie" value="{{ $encabezado->serie }}" style="text-transform: uppercase;" tabindex="2" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-sm-5">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Correlativo</label>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-center" id="correlativo" name="correlativo" value="{{ $encabezado->correlativo }}" tabindex="3">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <!-- <div id="datos_facturacion">
                                    <div class="card border-dark shadow mb-3">
                                        <div class="card-header bg-light">Datos Receptor</div>
                                        <div class="card-body text-info">
                                            <div class="row" style="font-size:12px;">
                                                <div class="mb-1 col-lg-6 offset-lg-1 col-sm-6 offset-sm-1">
                                                    <label for="masculino">Buscar por&nbsp;&nbsp;</label>
                                                    <div class="icheck-primary d-inline">
                                                        <input type="radio" id="paciente" name="busqueda" onchange="handleChange(this);" value="P" checked>
                                                        <label for="paciente">Paciente&nbsp;</label>
                                                    </div>
                                                    <div class="icheck-primary d-inline">
                                                        <input type="radio" id="documento" name="busqueda" onchange="handleChange(this);" value="D">
                                                        <label for="documento">Documento</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 offset-lg-4 col-sm-1 offset-sm-4">
                                                    <button type="button" class="btn btn-xs btn-default rounded-circle elevation-4" onclick="fn_documentos_con_saldo(); return false;" title="Ejecutar criterio de busqueda"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mb-1 col-lg-10 offset-lg-1 col-sm-10 offset-sm-1">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="paciente_id">Paciente</label>
                                                        </div>
                                                        <select class="custom-select  custom-select-sm select2 select2bs4" id="paciente_id"  name="paciente_id" tabindex="8">
                                                            <option value="">Seleccionar...</option>
                                                            @foreach($pacientes as $p)
                                                                <option value="{{ $p->id}}">{{ $p->nombre_completo}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-group input-group-sm col-lg-5 offset-lg-1 col-sm-5 offset-sm-1 mb-1">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text">Serie</label>
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm text-center" id="busqueda_serie" name="busqueda_serie" style="text-transform:uppercase;" disabled>
                                                </div>
                                                <div class="input-group input-group-sm col-lg-5 col-sm-5 mb-1">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text">Correlativo</label>
                                                    </div>
                                                    <input type="number" class="form-control form-control-sm text-center" id="busqueda_correlativo" name="busqueda_correlativo" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                <ul class="nav nav-pills nav-justified p-2">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#detalle_documento" data-toggle="tab" id="tab-detalle">Documentos con Saldo</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#detalle_pago" data-toggle="tab" id="tab-pago">Medio de Pago</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="detalle_documento">
                                        <div class="row">
                                            <div class="col-md-10 offset-md-1">
                                                <div class="table-responsive">
                                                    <table id="tblDocumentos" class="table table-sm table-hover text-center" style="font-size: 12px;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 10%;">Tipo</th>
                                                                <th style="width: 10%;">Fecha</th>
                                                                <th style="width: 10%;">Documento</th>
                                                                <th style="width: 10%;">N.I.T.</th>
                                                                <th style="width: 25%;">Nombre</th>
                                                                <th style="width: 10%;">Total</th>
                                                                <th style="width: 10%;">Saldo</th>
                                                                <th style="width: 10%;">Pago</th>
                                                                <th style="width: 5%;">&nbsp;</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($detalle as $det)
                                                                <tr>
                                                                    <td style="width: 10%;">{{ $det->descripcion }}</td>
                                                                    <td style="width: 10%;">{{ \Carbon\Carbon::parse($det->fecha_emision)->format('d/m/Y') }}</td>
                                                                    <td style="width: 10%;">{{ $det->serie }} - {{ $det->correlativo }}</td>
                                                                    <td style="width: 10%;">{{ $det->nit }}</td>
                                                                    <td style="width: 25%;">{{ $det->nombre }}</td>
                                                                    <td style="width: 10%;">{{ $det->total }}</td>
                                                                    <td style="width: 10%;">{{ $det->saldo_pendiente }}</td>
                                                                    <td style="width: 10%;">{{ $det->monto_aplicado }}</td>
                                                                    <td style="width: 5%;"></td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot></tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="detalle_pago">
                                        <div class="row">
                                            <div class="col-lg-1 offset-lg-10 col-sm-1 col-sm-10" style="text-align: right;">
                                                <a href="#" id="btnAgregarPago" class="btn btn-xs btn-outline-primary rounded-circle elevation-4 link-disabled"  title="Agregar medio de pago"><i class="fas fa-plus-circle"></i></a>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-10 offset-md-1">
                                                <div class="table-responsive">
                                                    <table id="tblPagos" class="table table-sm table-hover text-center" style="font-size: 12px;">
                                                        <thead>
                                                            <tr>
                                                                <th>Forma de pago</th>
                                                                <th>Entidad</th>
                                                                <th>Cuenta</th>
                                                                <th>Documento</th>
                                                                <th>Autorización</th>
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($pagos as $pago)
                                                                <tr>
                                                                    <td>{{ $pago->descripcion }}</td>
                                                                    <td>{{ $pago->nombre }}</td>
                                                                    <td>{{ $pago->cuenta_no }}</td>
                                                                    <td>{{ $pago->documento_no }}</td>
                                                                    <td>{{ $pago->autoriza_no }}</td>
                                                                    <td>{{ $pago->monto }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot></tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
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
    <script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
    @if(Session::get('type') == 'success')
        @if(Session::has('message'))
            <script>
                setTimeout(function() {
                    swal({
                        title: "Trabajo Finalizado",
                        text: "{!! Session::get('message') !!}",
                        type: "success"
                    });
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
                    });
                }, 1000);
            </script>
        @endif
    @endif
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            $('#fecha_emision').prop('readonly', true);
            $('#serie').prop('readonly', true);
            $('#correlativo').prop('readonly', true);
            $("#btnAgregarPago").prop("disabled", true);
        });

        function confirma_salida(){
            swal({
                title: 'Confirmación',
                Swal.fire({

                title: 'Confirmación',

                text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?",

text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?",
                icon: 'warning',

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

                        if (origen == 'P') {

                            window.location.href = "{{ route('pacientes') }}";

                        }

                        if (origen == 'A') {

                            window.location.href = "{{ route('nueva_agenda') }}";

                        }

                        // history.back();

                        

                    } 

                }

            );
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
                        window.location.href = "{{ route('recibos_listado') }}";
                    }
                }
            );
        }
    </script>
@endsection