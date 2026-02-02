@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
        select[readonly] {
          pointer-events: none;
          background-color: #e9ecef; /* estilo como readonly */
        }
        input, textarea, select, button {
            font-size: 12px !important;
        }

        /* Fondo del modal */
        .sweet-alert {
          background-color: #F0FFFF;   /* fondo oscuro */
          border-radius: 12px;        /* bordes más redondeados */
          color: #fff;                /* texto blanco */
          font-family: 'Roboto', sans-serif;
        }

        /* Título */
        .sweet-alert h2 {
          font-size: 22px;
          font-weight: bold;
          color: #ffd369; /* amarillo */
        }

        /* Texto del cuerpo */
        .sweet-alert p {
          font-size: 16px;
          color: #dcdcdc;
        }

        /* Botón de confirmación */
        .sweet-alert button.confirm {
          background-color: #4caf50 !important;
          border-radius: 6px;
          padding: 8px 20px;
          font-weight: bold;
        }

        /* Botón de cancelación (si usas cancelButtonText) */
        .sweet-alert button.cancel {
          background-color: #f44336 !important;
          border-radius: 6px;
          padding: 8px 20px;
          font-weight: bold;
        }

        /* Icono de éxito */
        .sweet-alert .sa-icon.sa-success {
          border-color: #4caf50;
        }
    </style>
@endsection
@section('title', 'Facturas')
@section('content_header')
    <br>
@endsection
@section('content')
    <form role="form" id="form-factura" method="post" action="{{ route('grabar_nc') }}">
        @csrf
        <div class="container-fluid">
            <div class="card">
                <div class="card-header" style="background-color: #E1E8ED;">
                    <div class="bg-default clearfix">
                        <div class="row">
                            <div class="col-lg-10 col-sm-10">
                                <h6>Nueva Nota de Crédito</h6>
                            </div>
                            <div class="col-lg-2 col-sm-2" style="text-align: right;">
                                <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Grabar"><i class="fas fa-save" title="Guardar"></i></button>
                                <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <input type="hidden" id="resolucion_id" name="resolucion_id">
                    <input type="hidden" id="caja_id" name="caja_id" value="{{ $caja->id }}">
                    <input type="hidden" id="caja_editar_documento" name="caja_editar_documento" value="{{ $caja->editar_documento}}">
                    <input type="hidden" id="tipo_documento_id" name="tipo_documento_id" value="{{ $documento->id }}">
                    <input type="hidden" id="paciente_id" name="paciente_id">
                    <input type="hidden" id="factura_estado" name="factura_estado" value="P">

                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div id="documento_cobro">
                                        <div class="card border-dark shadow mb-3">
                                            <div class="card-header bg-light">Documento</div>
                                            <div class="card-body text-info">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="input-group mb-1 input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Documento</label>
                                                            </div>
                                                            <select class="custom-select custom-select-sm select2 select2bs4 disabled" id="tipo_documento_id" name="tipo_documento_id" required disabled>
                                                                <option value="" selected>Seleccionar...</option>
                                                                <option value="{{ $documento->id }}" selected>{{ $documento->descripcion }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group form-control-sm clearfix">
                                                            <label>Condición</label>&nbsp;&nbsp;
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" class="boton" id="contado" name="condicion" value="0" checked disabled>
                                                                <label for="contado">Contado</label>
                                                            </div>
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" class="boton" id="credito" name="condicion" value="1" disabled>
                                                                <label for="credito">Credito</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="input-group mb-1 input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Fecha</label>
                                                            </div>
                                                            <input type="date" class="form-control form-control-sm text-center card-text" id="fecha_emision" name="fecha_emision" value="{{ $hoy }}" tabindex="1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-group mb-1 input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Serie</label>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm text-center card-text" id="serie" name="serie" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-group mb-1 input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Correlativo</label>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm text-center" id="correlativo" name="correlativo" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div id="documentos_afecto">
                                        <div class="card border-dark shadow mb-3">
                                            <div class="card-header bg-light">Documento Afecto</div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group form-control-sm clearfix">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" id="factura" name="tipodocumento" value="1" checked tabindex="3">
                                                                <label for="factura">Factura</label>
                                                            </div>
                                                            &nbsp;&nbsp;&nbsp;
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" id="Debito" name="tipodocumento" value="5" tabindex="4">
                                                                <label for="Debito">Nota de Debito</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-5 mb-1">
                                                        <div class="input-group input-group-sm mb-1">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Serie</label>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm text-center" id="serie_afecta" name="serie_afecta" style="text-transform: uppercase;" tabindex="5">
                                                        </div>
                                                    </div>
                                                    <div class="col-6 mb-1">
                                                        <div class="input-group input-group-sm mb-1">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Correlativo</label>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm text-center" id="documento_afecto" name="documento_afecto" tabindex="6">
                                                        </div>
                                                    </div>
                                                    <div class="col-1 mb-1">
                                                        <a href="#" class="btn btn-xs btn-default rounded-circle elevation-4" onclick="fn_buscar(); return false;" title="Buscar" tabindex="6"><i class="fas fa-search"></i></a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 mb-1">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">N.I.T.</label>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm text-center" id="nit" name="nit" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-7 mb-1">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Fecha</label>
                                                            </div>
                                                            <input type="date" class="form-control text-center" id="fecha" name="fecha" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 mb-1">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Nombre</label>
                                                            </div>
                                                            <input type="text" class="form-control text-center" id="nombre" name="nombre" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 mb-1">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Dirección</label>
                                                            </div>
                                                            <input type="text" class="form-control text-center" id="direccion" name="direccion" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 mb-1">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Correo Electrónico</label>
                                                            </div>
                                                            <input type="text" class="form-control text-center" id="email" name="email" readonly>
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
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header text-center" style="background-color: #E1E8ED;">
                                            <div class="row">
                                                <div class="col-md-10 offset-md-1">
                                                    <h5>Detalle</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="tblDetalle" class="table table-sm table-striped text-center" style="font-size: 12px;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 150px;">Producto</th>
                                                                    <th style="width: 250px;">Descripción</th>
                                                                    <th>Medída</th>
                                                                    <th>Cantidad</th>
                                                                    <th>Precio Unitario</th>
                                                                    <th>Precio total</th>
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
        </div>
    </form>
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
        var nlinea = 0;
        var nLinea = 0;
        var nlineaPago = 0;
        var total_detalle = 0;
        var linea = {};
        var statSend = false;
        var condicion = 0;
        var forma_pago = 'E';

        $(function(){
            $('.select2').select2();
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            });
        });

        //====================================================================================
        // Agregar formato de moneda a campos en tabla
        //====================================================================================
        const formatter = new Intl.NumberFormat('es-GT', {
          style: 'currency',
          currency: 'GTQ',
          minimumFractionDigits: 2
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

        document.addEventListener('DOMContentLoaded', function () {
            var caja_editar_documento      = document.getElementById('caja_editar_documento').value;
            // $('#tipo_documento_id').val(4).change();
            // const select = document.getElementById("tipo_documento_id");
            // select.addEventListener("mousedown", e => e.preventDefault());
            $("#fecha_emision").prop('readonly', true);
            if (caja_editar_documento == '0') {
                $("#serie").prop('readonly', true);
                $("#correlativo").prop('readonly', true);
                document.getElementById('nit').focus();
            }else {
                $("#serie").prop('readonly', false);
                $("#correlativo").prop('readonly', false);
                document.getElementById('serie').focus();
            }
        });

        //====================================================================================
        // resolucion por tipo de documento
        //====================================================================================
        function fn_resolucion_x_serie(){
            var caja_id                    = document.getElementById('caja_id').value;
            var caja_editar_documento      = document.getElementById('caja_editar_documento').value;
            var tipo_documento_id = document.getElementById('tipo_documento_id').value;
            var serie                      = document.getElementById('serie').value;

            if (caja_editar_documento == '0') {
                $('#fecha_emision').attr('readonly', true);
                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('resolucion_factura_x_caja')}}",
                    method: "POST",
                    data: { caja_id  : caja_id,
                            tipo_documento_id : tipo_documento_id},
                    success: function(response){
                        var info = response;
                        if (info.resolucion_existe == 'S') {
                            document.getElementById('resolucion_id').value = info.resolucion_id;
                            // document.getElementById('serie').value = info.serie;
                            // document.getElementById('correlativo').value = info.correlativo;
                        }else{
                            document.getElementById('resolucion_id').value = '';
                            // document.getElementById('serie').value = '';
                            // document.getElementById('correlativo').value = '';
                            swal({
                                title: 'Error !!!!',
                                text: 'Caja no cuenta con una resolucion activa para el tipo de documento',
                                type: 'error'
                                }
                            );
                        }
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }else{
                if (serie.length > 0) {
                    $.ajax({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{route('trae_resolucion_x_serie')}}",
                        method: "POST",
                        data: { caja_id           : caja_id,
                                tipo_documento_id : tipo_documento_id,
                                serie             : serie},
                        success: function(response){
                            var info = response;
                            if (info.resolucion_id != 0) {
                                document.getElementById('resolucion_id').value = info.resolucion_id;
                                //document.getElementById('serie').value = info.serie;
                                document.getElementById('correlativo').value = info.correlativo;
                            }else{
                                swal({
                                    title: 'Error !!!!',
                                    text: 'Caja no cuenta con una resolución activa con la serie ingresada, Favor verifique',
                                    type: 'error'
                                    }
                                );
                                document.getElementById('serie').value = '';
                                document.getElementById('correlativo').value = '';
                            }
                        },
                        error: function(error){
                            console.log(error);
                        }
                    });
                }
            }
        }

        //=======================================================================
        // Confirmar Salida de pantalla
        //=======================================================================
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
                        window.location.href = "{{ route('nc_listado') }}";
                    } 
                }
            );
        }

        function fn_buscar(){
            if (document.getElementById("factura").checked == true) {
                var tipodocumento_id       = '1';
            }else{
                var tipodocumento_id       = '5';
            }

            var serieAfecta = $('#serie_afecta').val();
            var documentoAfecto = $('#documento_afecto').val();

            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('nc_doctos_aplicar')}}",
                method: "POST",
                data: { tipodocumento_id  : tipodocumento_id,
                        serie             : serieAfecta,
                        correlativo       : documentoAfecto},
                success: function(response){
                    console.log(response.type);
                    if (response.type == 'error') {
                        setTimeout(function() {
                            swal({
                                title: "Favor verificar !!!",
                                text: response.message,
                                type: "error"
                            });
                        }, 1000);
                    }else{
                        //===============================================================================
                        // Datos encabezado de nota
                        //===============================================================================
                        $('#nit').val(response['encabezado']['nit']);
                        $('#fecha').val(response['encabezado']['fecha_emision']);
                        $('#nombre').val(response['encabezado']['nombre']);
                        $('#direccion').val(response['encabezado']['direccion']);
                        $('#email').val(response['encabezado']['email']);
                        //===============================================================================
                        // Detalle nota de crédito
                        //===============================================================================
                        var html = '';
                        for (var i = 0; i < response['detalle'].length; i++) {
                            console.log(response['detalle'][i]);
                            agregar_cargo();
                            $('#cargos\\['+i+'\\]\\[producto_id\\]').val(response['detalle'][i]['producto_id']).trigger('change');
                            $('#cargos\\['+i+'\\]\\[descripcion\\]').val(response['detalle'][i]['descripcion']);
                            $('#cargos\\['+i+'\\]\\[cantidad\\]').val(response['detalle'][i]['cantidad']);
                            $('#cargos\\['+i+'\\]\\[precio_unitario\\]').val(response['detalle'][i]['precio_unitario']);
                            $('#cargos\\['+i+'\\]\\[precio_total\\]').val(response['detalle'][i]['precio_bruto']);
                            // console.log(response['detalle'][i]);
                            // html += '<tr>'
                            // html += '<td>'
                            // html += response['detalle'][i]['cantidad']
                            // html += '</td>'
                            // html += '<td>'
                            // html += response['detalle'][i]['descripcion']
                            // html += '</td>'
                            // html += '<td>'
                            // html += response['detalle'][i]['medida_descripcion']
                            // html += '</td>'
                            // html += '<td style="text-align: right">'
                            // html += formatter.format(response['detalle'][i]['precio_unitario'])
                            // html += '</td>'
                            // html += '<td style="text-align: right">'
                            // html += formatter.format(response['detalle'][i]['precio_neto'])
                            // html += '</td>'
                            // html += '</tr>'
                        }
                        // $('#tblDetalle tbody').append(html);
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        /*=========================================================================================
        Agregar linea de cargos
        =========================================================================================*/
        function agregar_cargo(){
            var html = '';
            html += '<tr>';
            html += '<td width="150px">';
            html += '<select id="cargos['+nLinea+'][producto_id]" name="cargos['+nLinea+'][producto_id]" class="form-control classproducto" data-required="true" onchange="actualizarMedida('+nLinea+')">';
            html += '<option value="">Seleccionar...</option>';
            @foreach($productos as $producto)
            html += '<option value="{{$producto->id}}">{{$producto->descripcion}}</option>';
            @endforeach
            html += '</select>';
            html += '</td>';
            html += '<td>';
            html += '<input type="text" class="form-control classdescripcion" id="cargos['+nLinea+'][descripcion]" name="cargos['+nLinea+'][descripcion]" />';
            html += '</td>';
            html += '<td width="125px">';
            html += '<select id="cargos['+nLinea+'][medida_id]" name="cargos['+nLinea+'][medida_id]" class="form-control classmedida" data-required="true">';
            html += '<option value="">Seleccionar...</option>';
            html += '</select>';
            html += '</td>';
            html += '<td>';
            html += '<input type="number" step="1" class="form-control classnumero numero" id="cargos['+nLinea+'][cantidad]" name="cargos['+nLinea+'][cantidad]" />';
            html += '</td>';
            html += '<td>';
            html += '<input type="number" step="any" min="0.01" class="form-control classnumero numero" id="cargos['+nLinea+'][precio_unitario]" name="cargos['+nLinea+'][precio_unitario]" />';
            html += '</td>';
            html += '<td>';
            html += '<input type="number" step="any" min="0.01" class="form-control classnumero numero" id="cargos['+nLinea+'][precio_total]" name="cargos['+nLinea+'][precio_total]" />';
            html += '</td>';
            html += '</tr>';
            $('#tblDetalle tbody').append(html);
            // $('.eliminar').on('click',eliminar);
            nLinea += 1;
        }

        /*=========================================================================================
        Actualizar unidad de medida
        =========================================================================================*/
        function actualizarMedida(linea){
            var x = document.getElementById("cargos["+linea+"][producto_id]").selectedIndex;
            var y = document.getElementById("cargos["+linea+"][producto_id]").options;
            var producto_id = y[x].value;              

            $.ajax({
                url: "{{ route('trae_medidas_x_producto') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}", producto_id: producto_id},
                success: function(response){
                    if (response.length == 0) {
                        let dropdown = document.getElementById("cargos["+linea+"][medida_id]");
                        dropdown.length = 0;
                        let option;
                        option = document.createElement('option');
                        option.text = 'Unidad';
                        option.value = 1;
                        dropdown.add(option);
                    }else{
                        let dropdown = document.getElementById("cargos["+linea+"][medida_id]");
                        dropdown.length = 0;
                        let option;
                        option = document.createElement('option');
                        option.text = 'Seleccionar ....';
                        option.value = '';
                        for (let i = 0; i < response.length; i++) {
                            option = document.createElement('option');
                            option.text = response[i].unidad_medida_descripcion;
                            option.value = response[i].unidad_medida_id;
                            dropdown.add(option);
                        }
                    }
                },
                error: function(error){
                    console.log(error);
                } 
            });
        }
    </script>
@endsection