@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
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
    </style>
@endsection
@section('title', 'Recepción de Pago')
@section('content_header')
    <br>
@endsection
@section('content')
    <form role="form" id="form-factura" method="post" action="{{ route('recibo_grabar') }}" novalidate>
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="background-color: #E1E8ED;">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="row">
                            <div class="col-sm-9">
                                <h6>Recibo de Pago</h6>
                            </div>
                            <div class="col-sm-3" style="text-align: right;">
                                <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Grabar"><i class="fas fa-save" title="Guardar"></i></button>
                                <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="background-color: white">
                        <input type="hidden" id="tipo_documento_id" name="tipo_documento_id" value="{{ $documento->id }}">
                        <input type="hidden" id="resolucion_id" name="resolucion_id">
                        <input type="hidden" id="caja_id" name="caja_id" value="{{ $caja->id }}">
                        <input type="hidden" id="caja_editar_documento" name="caja_editar_documento" value="{{ $caja->editar_documento}}">
                        <input type="hidden" id="recibo_estado" name="recibo_estado" value="P">
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
                                                        <input type="date" class="form-control form-control-sm text-center" id="fecha_emision" name="fecha_emision" value="{{ $hoy }}" @if($caja->editar_documento == 'N') then disabled @endif tabindex="1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-5 offset-lg-1 col-sm-5 offset-sm-1">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Serie</label>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-center" id="serie" name="serie" style="text-transform: uppercase;" onchange="fn_resolucion_x_serie(); return false;" @if($caja->editar_documento == 'N') then disabled @endif tabindex="2">
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-sm-5">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Correlativo</label>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-center" id="correlativo" name="correlativo" @if($caja->editar_documento == 'N') then disabled @endif tabindex="3">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div id="datos_facturacion">
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
                                </div>
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
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table id="tblDocumentos" class="table table-sm table-hover text-center">
                                                        <thead style="font-size: 12px;">
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
                                                        <tbody></tbody>
                                                        <tfoot></tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="detalle_pago">
                                        <div class="row">
                                            <div class="col-lg-1 offset-lg-11 col-sm-1 col-sm-10" style="text-align: right;">
                                                <a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="agregar_pago(); return false;" title="Agregar medio de pago"><i class="fas fa-plus-circle"></i></a>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-12">
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
                                                        <tbody></tbody>
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
    </form>
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
        var nLineap = 0;
        var nLinea  = 0;

        $(function(){
            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            var tipoDocumentoId = $('#tipo_documento_id').val();
            var cajaId = $('#caja_id').val();
            var editarDocumento = $('#caja_editar_documento').val();
            var serie = null;
            
            if (editarDocumento == 0) {
                $('#fecha_emision').prop('readonly', true);
                $('#serie').prop('readonly', true);
                $('#correlativo').prop('readonly', true);
            }else{
                $('#fecha_emision').prop('readonly', false);
                $('#serie').prop('readonly', false);
                $('#correlativo').prop('readonly', false);
            }

            $.ajax({
                url: "{{ route('resolucion_recibo_x_caja') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}",
                       caja_id: cajaId},
                success: function(response){
                    $('#resolucion_id').val(response.resolucion_id);
                    $('#serie').val(response.serie);
                    $('#correlativo').val(response.correlativo);
                    serie = response.serie;
                },
                error: function(error){
                    console.log(error);
                }
            });
        });

        function handleChange(src) {
            if (src.value == 'P') {
                document. getElementById("paciente_id").removeAttribute("disabled");
                document. getElementById("busqueda_serie").value = '';
                document. getElementById("busqueda_correlativo").value = '';
                document. getElementById("busqueda_serie").setAttribute("disabled", "disabled");
                document. getElementById("busqueda_correlativo").setAttribute("disabled", "disabled");
            }else{
                $('#paciente_id').val("").trigger('change');
                document. getElementById("paciente_id").setAttribute("disabled", "disabled");
                document. getElementById("busqueda_serie").removeAttribute("disabled");
                document. getElementById("busqueda_correlativo").removeAttribute("disabled");
            }
        }

        //=======================================================================
        // Confirmar Salida de pantalla
        //=======================================================================
        function confirma_salida(){
            Swal.fire({
                title: 'Confirmación',
                text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?",
                icon: 'warning',
                showCancelButton: true,
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                allowEscapeKey: true
                }).then((result) => {
                    /* result.isConfirmed es el nuevo estándar */
                    if (result.isConfirmed) { 
                        window.location.href = "{{ route('recibos_listado') }}";
                    }
                });
        }

        function fn_documentos_con_saldo(){
            var paciente    = document.getElementById('paciente_id');
            var serie       = document.getElementById('busqueda_serie').value;
            var correlativo = document.getElementById('busqueda_correlativo').value;
            var paciente_id = paciente.options[paciente.selectedIndex].value;
            if (paciente_id.length == 0) {
                paciente_id = 0;
            }
            // localStorage.clear();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('documentos_con_saldo')}}",
                method: "POST",
                data: { paciente_id : paciente_id,
                        serie: serie,
                        correlativo : correlativo
                },
                success: function(response){
                    event.preventDefault();
                    if (response.type == 'error') {
                        Swal.fire({
                            title: "¡Trabajo Finalizado!",
                            text: response.message,
                            icon: "error", // Cambiado de 'type' a 'icon'
                            confirmButtonText: "Aceptar",
                            confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        });
                    }else{
                        var html = '';
                        for (var i = 0; i < response.length; i++) {
                            html += '<tr style="font-size: 12px;">'
                            html += '<td style="width: 10%;">'
                            html += '<input type="text" class="form-control" id="documentos['+i+'][descripcion]" name="documentos['+i+'][descripcion]" value="'+response[i]['descripcion']+'" readonly/>';
                            html += '</td>'
                            html += '<td style="width: 10%;">'
                            html += '<input type="date" class="form-control" id="documentos['+i+'][fecha_emision]" name="documentos['+i+'][fecha_emision]" value="'+response[i]['fecha_emision']+'" readonly/>';
                            html += '</td>'
                            html += '<td style="width: 10%;">'
                            html += '<input type="text" class="form-control" id="documentos['+i+'][documento]" name="documentos['+i+'][documento]" value="'+response[i]['serie']+' - '+response[i]['correlativo']+'" readonly/>';
                            html += '</td>'
                            html += '<td style="width: 10%;">'
                            html += '<input type="text" class="form-control" id="documentos['+i+'][nit]" name="documentos['+i+'][nit]" value="'+response[i]['nit']+'" readonly/>';
                            html += '</td>'
                            html += '<td style="width: 25%;">'
                            html += '<input type="text" class="form-control" id="documentos['+i+'][nombre]" name="documentos['+i+'][nombre]" value="'+response[i]['nombre']+'" readonly/>';
                            html += '</td>'
                            html += '<td style="width: 10%;">'
                            html += '<input type="number" class="form-control classtotal numero" id="documentos['+i+'][total]" name="documentos['+i+'][total]" value="'+response[i]['total']+'" readonly/>';
                            html += '</td>'
                            html += '<td style="width: 10%;">'
                            html += '<input type="number" step="any" class="form-control classtotal numero" id="documentos['+i+'][saldo]" name="documentos['+i+'][saldo]" value="'+response[i]['saldo']+'" readonly/>';
                            html += '</td>'
                            html += '<td style="width: 10%;">'
                            html += '<input type="hidden" class="form-control classtotal numero" id="documentos['+i+'][id]" name="documentos['+i+'][id]" value="'+response[i]['id']+'"/>';
                            html += '<input type="number" step="any" min="0.01" max="'+response[i]['saldo']+'" class="form-control classtotal numero monto" id="documentos['+i+'][monto]" name="documentos['+i+'][monto]"/>';
                            html += '</td>'
                            html += '<td>'
                            html += '</td>'
                            html += '</tr>'
                        }
                        $("#tblDocumentos tbody tr").remove();
                        $('#tblDocumentos tbody').append(html);
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        //=========================================================================================
        // Agregar linea de pago
        //=========================================================================================
        function agregar_pago(){
            var html = '';
            html += '<tr style="font-size: 12px;">';
            html += '<td width="150px">';
            html += '<select id="mpago['+nLineap+'][fpago_id]" name="mpago['+nLineap+'][fpago_id]" class="form-control classproducto" data-required="true" onchange="habilitarRegistro('+nLineap+')">';
            html += '<option value="">Seleccionar...</option>';
            @foreach($formas_pago as $forma_pago)
            if ({{$forma_pago->id}} == 1){
                html += '<option value="{{$forma_pago->id}}" selected >{{$forma_pago->descripcion}}</option>';
            }else{
                html += '<option value="{{$forma_pago->id}}">{{$forma_pago->descripcion}}</option>';
            }
            @endforeach
            html += '</select>';
            html += '</td>';
            html += '<td width="125px">';
            html += '<select id="mpago['+nLineap+'][casa_id]" name="mpago['+nLineap+'][casa_id]" class="form-control" data-required="true" readonly>';
            html += '<option value="">Seleccionar...</option>';
            html += '</select>';
            html += '</td>';
            html += '<td width="75px">';
            html += '<input type="number" step="1" min="1" class="form-control classnumero numero" id="mpago['+nLineap+'][cuenta_no]" name="mpago['+nLineap+'][cuenta_no]" value="1" onchange="fnCalculo('+nLineap+');" readonly/>';
            html += '</td">';
            html += '<td width="125px">';
            html += '<input type="number" step="any" min="1" class="form-control classprecio numero" id="mpago['+nLineap+'][documento_no]" name="mpago['+nLineap+'][documento_no]" readonly/>';
            html += '</td">';
            html += '<td width="125px">';
            html += '<input type="number" step="any" min="1" class="form-control classprecio numero" id="mpago['+nLineap+'][autoriza_no]" name="mpago['+nLineap+'][autoriza_no]" readonly/>';
            html += '</td">';
            html += '<td width="125px">';
            html += '<input type="number" step="any" min="1" class="form-control classtotal numero montoPago" id="mpago['+nLineap+'][monto]" name="mpago['+nLineap+'][monto]"/>';
            html += '</td">';
            html += '<td width="35px"><a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-1 eliminar"><i class="fas fa-trash-alt"></i></a></td>';
            html += '</tr>';
            $('#tblPagos tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLineap += 1;
        }

        //=========================================================================================
        // Habilitar registro
        //=========================================================================================
        function habilitarRegistro(id){
            // var x = document.getElementById("mpago["+id+"][fpago_id]").selectedIndex;
            // var y = document.getElementById("mpago["+id+"][fpago_id]").options;
            // var fpago_id = y[x].value;

            // $.ajax({
            //     url: "{{ route('campos_requeridos') }}",
            //     type: "POST",
            //     async: true,
            //     data: {"_token": "{{ csrf_token() }}", fpago_id: fpago_id},
            //     success: function(response){
            //         console.log(response);
            //         if (response['casa'] == 'S') {
            //             $('#mpago['+id+'][casa_id]').prop('readonly', false);
            //         }else{
            //             $('#mpago['+id+'][casa_id]').prop('readonly', true);
            //         }
            //         if (response['cuenta'] == 'S') {
            //             $('#mpago['+id+'][cuenta_no]').prop('readonly', false);
            //         }else{
            //             $('#mpago['+id+'][cuenta_no]').prop('readonly', true);
            //         }
            //         if (response['documento'] == 'S') {
            //             $('#mpago['+id+'][documento_no]').prop('readonly', false);
            //         }else{
            //             $('#mpago['+id+'][documento_no]').prop('readonly', true);
            //         }
            //         if (response['autorizacion'] == 'S') {
            //             $('#mpago['+id+'][autoriza_no]').prop('readonly', false);
            //         }else{
            //             $('#mpago['+id+'][autoriza_no]').prop('readonly', true);
            //         }
            //     },
            //     error: function(error){
            //         console.log(error);
            //     }
            // });
        }

        /*=========================================================================================
        Eliminar registro de tabla
        =========================================================================================*/
        function eliminar(){
            var whichtr = $(this).closest("tr");
            whichtr.remove(); 
            return false;
        }

        /*=========================================================================================
        Calcula total cargos
        =========================================================================================*/

        function total_cargos(){
            let totalDocumentos = 0;
            $("#tblDocumentos tbody .monto").each(function(){
                let val = parseFloat($(this).val()) || 0;
                totalDocumentos += val;
                // console.log(totalDocumentos);
            });

            // $("#total").text(total.toFixed(2));


            var pie = '';
            totalDocumentos = totalDocumentos.toFixed(2);
            pie += '<tr>'
            pie += '<td ></td>'
            pie += '<td ></td>'
            pie += '<td ></td>'
            pie += '<td ></td>'
            pie += '<td ></td>'
            pie += '<td ></td>'
            pie += '<td ><h6><b>Total</b></h6></td>'
            pie += '<td style="text-align: right"><h6><b>'+totalDocumentos+'</b></h6></td>'
            pie += '</tr>'
            $("#tblDocumentos tfoot tr").remove();
            $('#tblDocumentos tfoot').append(pie);
            $('#total_saldo').val(totalDocumentos);
        }

        /*=========================================================================================
        Calcula total Pagos
        =========================================================================================*/

        function total_pagos(){
            let totalPagos = 0;
            $("#tblPagos tbody .montoPago").each(function(){
                let val = parseFloat($(this).val()) || 0;
                totalPagos += val;
            });

            var pie = '';
            totalPagos = totalPagos.toFixed(2);
            pie += '<tr>'
            pie += '<td ></td>'
            pie += '<td ></td>'
            pie += '<td ></td>'
            pie += '<td ></td>'
            pie += '<td ><h6><b>Total</b></h6></td>'
            pie += '<td style="text-align: right"><h6><b>'+totalPagos+'</b></h6></td>'
            pie += '<td ></td>'
            pie += '</tr>'
            $("#tblPagos tfoot tr").remove();
            $('#tblPagos tfoot').append(pie);
            $('#total_pago').val(totalPagos);
        }

        // detectar cambios en cualquier input de la tabla
        $(document).on("input", "#tblDocumentos .monto", function(){
            total_cargos();
        });

        // detectar cambios en cualquier input de la tabla
        $(document).on("input", "#tblPagos .montoPago", function(){
            total_pagos();
        });


        $(document).ready(function(){
            $("#form-factura").on("submit", function(e){
                var saldo = $('#total_saldo').val();
                var pago  = $('#total_pago').val();
                var errorMax = false;

                if(saldo !== pago){
                    e.preventDefault(); // evita que el formulario se envíe

                    Swal.fire({
                        title: "¡ Error !",
                        text: "Valor a cancelar no concuerda con el valor a aplicar a los documentos",
                        icon: "error", // Cambiado de 'type' a 'icon'
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });
                }

                $(".monto").each(function() {
                    var valor = parseFloat($(this).val()) || 0;
                    var maximo = parseFloat($(this).attr('max'));
                    var minimo = parseFloat($(this).attr('min'));

                    if (valor > maximo || valor < minimo) {
                        errorMax = true;
                        $(this).addClass('is-invalid'); // Marca el input en rojo (Bootstrap)
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (errorMax) {
                    e.preventDefault();

                    $('.nav-pills a[href="#detalle_documento"]').tab('show');

                    Swal.fire({
                        title: "¡Monto excedido!",
                        text: "Uno o más documentos tienen un monto no permitido.",
                        icon: "error",
                        confirmButtonText: "Revisar",
                        customClass: { confirmButton: 'btn btn-danger' },
                        buttonsStyling: false
                    });
                    return; // Detiene la ejecución
                }
            });
        });

    </script>
@endsection