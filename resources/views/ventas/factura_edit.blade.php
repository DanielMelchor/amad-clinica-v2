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
        /*html, body {
            font-size: 12px;
        }*/

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
@section('title', 'Ventas')
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
                                <h6>Edición de Documento</h6>
                            </div>
                            <div class="col-sm-3" style="text-align: right;">
                                <button type="submit" id="btnGuardar" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Grabar"><i class="fas fa-save" title="Guardar"></i></button>
                                <a href="#" id="btnAnular" class="btn btn-xs btn-outline-secondary rounded-circle elevation-4" title="Anular Documento" onclick="fnAnularDocumento();"><i class="fas fa-ban"></i></a>
                                <a href="#" id="btnSalir" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="background-color: white">
                        <input type="hidden" id="id" name="id" value="{{ $encabezado->id }}">
                        <input type="hidden" id="caja_id" name="caja_id" value="{{ $caja->id }}">
                        <input type="hidden" id="caja_editar_documento" name="caja_editar_documento" value="{{ $caja->editar_documento}}">
                        <input type="hidden" id="resolucion_id" name="resolucion_id">
                        <input type="hidden" id="paciente_id" name="paciente_id" value="">
                        <input type="hidden" class="form-control" id="admision_id" name="admision_id" value="">
                        <input type="hidden" id="tipo_facturacion" name="tipo_facturacion" value="">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div id="documento_cobro">
                                    <div class="card border-dark shadow mb-3">
                                        <div class="card-header bg-light">Documento de cobro</div>
                                        <div class="card-body text-info">
                                            <div class="row">
                                                <div class="col-lg-8 col-sm-8">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Documento</label>
                                                        </div>
                                                        <select class="custom-select custom-select-sm select2 select2bs4" id="tipo_documento_id" name="tipo_documento_id" required readonly>
                                                            <option value="">Seleccionar...</option>
                                                            @foreach($documento as $d)
                                                                <option value="{{ $d->id}}">{{ $d->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-4">
                                                    <div class="input-group mb-1">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Fecha</label>
                                                        </div>
                                                        <input type="date" class="form-control text-center card-text" id="fecha_emision" name="fecha_emision" value="{{ $hoy }}" tabindex="1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12">
                                                    <div class="input-group input-group-sm mb-1">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Serie</label>
                                                        </div>
                                                        <input type="text" style="text-transform: uppercase;" class="form-control card-text" id="serie" name="serie" value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12">
                                                    <div class="input-group input-group-sm mb-1">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Correlativo</label>
                                                        </div>
                                                        <input type="number" step="1" min="0" class="form-control card-text numero" id="correlativo" name="correlativo" value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12">
                                                    <div class="form-group form-control-sm clearfix">
                                                        <label>Condición de pago</label>&nbsp;&nbsp;
                                                        <div class="icheck-primary d-inline">
                                                            <input type="radio" class="boton" id="contado" name="condicion" value="0" checked tabindex="4">
                                                            <label for="contado">Contado</label>
                                                        </div>
                                                        <div class="icheck-primary d-inline">
                                                            <input type="radio" class="boton" id="credito" name="condicion" value="1" tabindex="5">
                                                            <label for="credito">Credito</label>
                                                        </div>
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
                                            <div class="row">
                                                <div class="col-lg-5 col-sm-5 offset-lg-6 offset-sm-6">
                                                    <div class="input-group input-group-sm mb-1">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text"># Admisión</label>
                                                        </div>
                                                        <input type="number" step="1" min="0" class="form-control card-text numero" id="admision_no" name="admision_no" value="">
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-sm-1" style="text-align: right;">
                                                    <a href="#" id="btnBuscar" class="btn btn-xs btn-default rounded-circle elevation-4" title="Realizar Busqueda"><i class="fas fa-search"></i></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">N.I.T.</label>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-center" id="nit" name="nit" style="text-transform: uppercase;" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Nombre</label>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-center" id="nombre" name="nombre" style="text-transform: uppercase;" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Dirección</label>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-center" id="direccion" name="direccion" style="text-transform: uppercase;" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-pills nav-justified p-2">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#detalle_documento" data-toggle="tab" id="tab-detalle">Detalle</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#detalle_pago" data-toggle="tab" id="tab-pago">Medio de Pago</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="detalle_documento">
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-1 offset-lg-11 col-sm-1 offset-sm-11" style="text-align: right;">
                                                <!-- <a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="agregar_cargo(); return false;" title="Agregar Cargo"><i class="fas fa-plus-circle"></i></a> -->
                                                <br>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-sm table-striped" id="tblCargos" style="font-size: 12px;">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Producto</th>
                                                            <th>Descripción</th>
                                                            <th>Medída</th>
                                                            <th>Cantidad</th>
                                                            <th>Precio</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td><h5><b>Total</b></h5></td>
                                                            <td style="text-align: right;"><h5><b>0.00</b></h5></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="detalle_pago">
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-1 offset-lg-11 col-sm-1 offset-sm-11" style="text-align: right;">
                                                <!-- <a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="agregar_pago(); return false;" title="Agregar medio de pago"><i class="fas fa-plus-circle"></i></a> -->
                                                <br>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-sm table-striped" id="tblPagos" style="font-size: 12px;">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Medio</th>
                                                            <th>Casa</th>
                                                            <th>No. Cuenta</th>
                                                            <th>No. Documento</th>
                                                            <th>No. Autorización</th>
                                                            <th>Monto</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
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
    </form>
    <!-- Modal -->
    <div class="modal fade" id="anulacionModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="anulacionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form class="form-horizontal" id="anulacionForm" name="anulacionForm" method="POST" action="{{ route('documento_anular') }}">
                    @csrf
                    <div class="card">
                        <div class="card-header" style="background-color: #F4F6F7;">
                            <div class="row">
                                <div class="col-md-8">
                                    Anulación de Documento
                                </div>
                                <div class="col-md-3 offset-md-1" style="text-align: right;">
                                    <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>&nbsp;
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar"> <i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="documento_id" name="documento_id">
                            <div class="row">
                                <div class="input-group input-group-sm col-10 offset-1 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="motivo_id">Motivo</label>
                                    </div>
                                    <select class="custom-select custom-select-sm select2 select2bs4" id="motivo_id" name="motivo_id" required>
                                        <option value="" selected="selected">Seleccionar.....</option>
                                        @foreach($motivos_anulacion as $motivo_anulacion)
                                        <option value="{{ $motivo_anulacion->id }}">{{ $motivo_anulacion->descripcion }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-10 offset-1">
                                    <span for="observaciones">Observaciones</span>
                                    <textarea class="form-control" id="observacion" name="observacion" rows="3" required></textarea>
                                </div>
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
        var nLinea      = 0;
        var nLineap     = 0;
        var admision_id = 0;
        var paciente_id = 0;
        var tipo_facturacion = 'N';

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
            const datos = @json($encabezado);
            const detalle = @json($detalle);
            const pago = @json($pago);
            $("#tipo_documento_id").val(datos.tipodocumento_id).trigger("change");
            $("#fecha_emision").val(datos.fecha_emision);
            $("#serie").val(datos.serie);
            $("#correlativo").val(datos.correlativo);
            $("input[name='condicion'][value='" + datos.condicion + "']").prop("checked", true);
            $("#contado").prop("disabled", true);
            $("#credito").prop("disabled", true);
            $("#admision_no").val(datos.no_admision);
            $("#nit").val(datos.nit);
            $("#nombre").val(datos.nombre);
            $("#direccion").val(datos.direccion);
            $("#tipo_documento_id").prop("disabled", true);
            $("#fecha_emision").prop("disabled", true);
            $("#serie").prop("disabled", true);
            $("#correlativo").prop("disabled", true);
            $("#admision_no").prop("disabled", true);
            $("#nit").prop("disabled", true);
            $("#nombre").prop("disabled", true);
            $("#direccion").prop("disabled", true);

            $("#btnGuardar").prop("disabled", true);
            $("#btnBuscar").prop("disabled", true);
            // console.log(datos);

            detalle.forEach(function(valor, indice) {
                agregar_cargo();
                $('#cargos\\['+indice+'\\]\\[admision_id\\]').val(datos.admision_id).trigger('change');
                $('#cargos\\['+indice+'\\]\\[producto_id\\]').val(valor.producto_id).trigger('change');
                $('#cargos\\['+indice+'\\]\\[descripcion\\]').val(valor.producto_descripcion);
                $('#cargos\\['+indice+'\\]\\[cantidad\\]').val(valor.cantidad);
                $('#cargos\\['+indice+'\\]\\[precio\\]').val(valor.precio_unitario);
                $('#cargos\\['+indice+'\\]\\[total\\]').val(valor.precio_neto);

                $('#cargos\\['+indice+'\\]\\[producto_id\\]').prop("disabled", true);
                $('#cargos\\['+indice+'\\]\\[descripcion\\]').prop("disabled", true);
                $('#cargos\\['+indice+'\\]\\[medida_id\\]').prop("disabled", true);
                $('#cargos\\['+indice+'\\]\\[cantidad\\]').prop("disabled", true);
                $('#cargos\\['+indice+'\\]\\[precio\\]').prop("disabled", true);

                total_tabla();
            });

            pago.forEach(function(valor, indice) {
                agregar_pago();
                $('#mpago\\['+indice+'\\]\\[fpago_id\\]').val(valor.forma_pago).trigger('change');
                $('#mpago\\['+indice+'\\]\\[cuenta_no\\]').val(valor.cuenta_no);
                $('#mpago\\['+indice+'\\]\\[documento_no\\]').val(valor.documento_no);
                $('#mpago\\['+indice+'\\]\\[autoriza_no\\]').val(valor.autoriza_no);
                $('#mpago\\['+indice+'\\]\\[monto\\]').val(valor.monto);
                // $('#mpago\\['+indice+'\\]\\[casa_id\\]').val(valor.forma_pago).trigger('change');
                // $('#cargos\\['+indice+'\\]\\[producto_id\\]').val(valor.producto_id).trigger('change');
                // $('#cargos\\['+indice+'\\]\\[descripcion\\]').val(valor.producto_descripcion);
                // $('#cargos\\['+indice+'\\]\\[cantidad\\]').val(valor.cantidad);
                // $('#cargos\\['+indice+'\\]\\[precio\\]').val(valor.precio_unitario);
                // $('#cargos\\['+indice+'\\]\\[total\\]').val(valor.precio_neto);

                $('#mpago\\['+indice+'\\]\\[fpago_id\\]').prop("disabled", true);
                $('#mpago\\['+indice+'\\]\\[casa_id\\]').prop("disabled", true);
                $('#mpago\\['+indice+'\\]\\[documento_no\\]').prop("disabled", true);
                $('#mpago\\['+indice+'\\]\\[autoriza_no\\]').prop("disabled", true);
                $('#mpago\\['+indice+'\\]\\[monto\\]').prop("disabled", true);
                // $('#cargos\\['+indice+'\\]\\[descripcion\\]').prop("disabled", true);
                // $('#cargos\\['+indice+'\\]\\[medida_id\\]').prop("disabled", true);
                // $('#cargos\\['+indice+'\\]\\[cantidad\\]').prop("disabled", true);
                // $('#cargos\\['+indice+'\\]\\[precio\\]').prop("disabled", true);

                // total_tabla();
            });
        });

        /*=========================================================================================
        Agregar linea de cargos
        =========================================================================================*/
        function agregar_cargo(){
            var html = '';
            html += '<tr>';
            html += '<td style="visibility:hidden; display:none">';
            html += '<input type="number" step="1" min="1" class="form-control classnumero numero" id="cargos['+nLinea+'][admision_id]" name="cargos['+nLinea+'][admision_id]" />';
            html += '</td">';
            html += '<td style="visibility:hidden; display:none">';
            html += '<input type="number" step="1" min="1" class="form-control classnumero numero" id="cargos['+nLinea+'][movimiento_id]" name="cargos['+nLinea+'][movimiento_id]" />';
            html += '</td">';
            html += '<td width="150px">';
            html += '<select id="cargos['+nLinea+'][producto_id]" name="cargos['+nLinea+'][producto_id]" class="form-control classproducto" data-required="true" onchange="actualizarMedida('+nLinea+')">';
            html += '<option value="">Seleccionar...</option>';
            @foreach($productos as $producto)
            html += '<option value="{{$producto->id}}">{{$producto->descripcion}}</option>';
            @endforeach
            html += '</select>';
            html += '</td>';
            html += '<td width="250px">';
            html += '<input type="text" class="form-control classdescripcion" id="cargos['+nLinea+'][descripcion]" name="cargos['+nLinea+'][descripcion]" />';
            html += '</td">';
            html += '<td width="125px">';
            html += '<select id="cargos['+nLinea+'][medida_id]" name="cargos['+nLinea+'][medida_id]" class="form-control classmedida" data-required="true">';
            html += '<option value="">Seleccionar...</option>';
            html += '</select>';
            html += '</td>';
            html += '<td width="75px">';
            html += '<input type="number" step="1" min="1" class="form-control classnumero numero" id="cargos['+nLinea+'][cantidad]" name="cargos['+nLinea+'][cantidad]" value="1" onchange="fnCalculo('+nLinea+');" />';
            html += '</td">';
            html += '<td width="125px">';
            html += '<input type="number" step="any" min="1" class="form-control classprecio numero" id="cargos['+nLinea+'][precio]" name="cargos['+nLinea+'][precio]" onchange="fnCalculo('+nLinea+');" />';
            html += '</td">';
            html += '<td width="125px">';
            html += '<input type="number" step="any" min="1" class="form-control classtotal numero" id="cargos['+nLinea+'][total]" name="cargos['+nLinea+'][total]" readonly/>';
            html += '</td">';
            html += '<td">';
            html += '</td">';
            // html += '<td width="35px"><a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-1 eliminar"><i class="fas fa-trash-alt"></i></a>';
            // html += '</td>';
            html += '</tr>';
            $('#tblCargos tbody').append(html);
            $('.eliminar').on('click',eliminar);
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

        /*=========================================================================================
        Eliminar registro de tabla
        =========================================================================================*/
        function eliminar(){
            var whichtr = $(this).closest("tr");
            whichtr.remove(); 
            return false;
        }

        /*=========================================================================================
        Agregar linea de pago
        =========================================================================================*/
        function agregar_pago(){
            var html = '';
            html += '<tr>';
            html += '<td width="150px">';
            html += '<select id="mpago['+nLineap+'][fpago_id]" name="mpago['+nLineap+'][fpago_id]" class="form-control classproducto" data-required="true" onchange="habilitarRegistro('+nLineap+')">';
            html += '<option value="">Seleccionar...</option>';
            @foreach($formas_pago as $forma_pago)
                html += '<option value="{{$forma_pago->id}}">{{$forma_pago->descripcion}}</option>';
            // if ({{$forma_pago->id}} == 1){
            //     html += '<option value="{{$forma_pago->id}}" selected >{{$forma_pago->descripcion}}</option>';
            // }else{
            //     html += '<option value="{{$forma_pago->id}}">{{$forma_pago->descripcion}}</option>';
            // }
            @endforeach
            html += '</select>';
            html += '</td>';
            html += '<td width="125px">';
            html += '<select id="mpago['+nLineap+'][casa_id]" name="mpago['+nLineap+'][casa_id]" class="form-control" data-required="true">';
            html += '<option value="">Seleccionar...</option>';
            html += '</select>';
            html += '</td>';
            html += '<td width="75px">';
            html += '<input type="number" step="1" min="1" class="form-control classnumero numero" id="mpago['+nLineap+'][cuenta_no]" name="mpago['+nLineap+'][cuenta_no]" value="1" onchange="fnCalculo('+nLineap+');"/>';
            html += '</td">';
            html += '<td width="125px">';
            html += '<input type="number" step="any" min="1" class="form-control classprecio numero" id="mpago['+nLineap+'][documento_no]" name="mpago['+nLineap+'][documento_no]" />';
            html += '</td">';
            html += '<td width="125px">';
            html += '<input type="number" step="any" min="1" class="form-control classprecio numero" id="mpago['+nLineap+'][autoriza_no]" name="mpago['+nLineap+'][autoriza_no]" />';
            html += '</td">';
            html += '<td width="125px">';
            html += '<input type="number" step="any" min="1" class="form-control classtotal numero" id="mpago['+nLineap+'][monto]" name="mpago['+nLineap+'][monto]"/>';
            html += '</td">';
            html += '<td">';
            html += '</td">';
            // html += '<td width="35px"><a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-1 eliminar"><i class="fas fa-trash-alt"></i></a></td>';
            html += '</tr>';
            $('#tblPagos tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLinea += 1;
        }

        function habilitarRegistro(id){
            var x = document.getElementById("mpago["+id+"][fpago_id]").selectedIndex;
            var y = document.getElementById("mpago["+id+"][fpago_id]").options;
            var fpago_id = y[x].value;

            $.ajax({
                url: "{{ route('campos_requeridos') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}", fpago_id: fpago_id},
                success: function(response){
                    if (response['casa'] == 'S') {
                        document.getElementById('mpago['+id+'][casa_id]').setAttribute('disabled', false);
                    }else{
                        document.getElementById('mpago['+id+'][casa_id]').setAttribute('disabled', true);
                    }
                    if (response['cuenta'] == 'S') {
                        document.getElementById('mpago['+id+'][cuenta_no]').setAttribute('disabled', false);
                    }else{
                        document.getElementById('mpago['+id+'][cuenta_no]').setAttribute('disabled', true);
                    }
                    if (response['documento'] == 'S') {
                        document.getElementById('mpago['+id+'][documento_no]').setAttribute('disabled', false);
                    }else{
                        document.getElementById('mpago['+id+'][documento_no]').setAttribute('disabled', true);
                    }
                    if (response['autorizacion'] == 'S') {
                        document.getElementById('mpago['+id+'][autoriza_no]').setAttribute('disabled', false);
                    }else{
                        document.getElementById('mpago['+id+'][autoriza_no]').setAttribute('disabled', true);
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function total_tabla(){
            var pie = '';
            var total = 0;
            var filas=document.querySelectorAll("#tblCargos tbody tr");
            filas.forEach(function(e) {
                var columnas = e.querySelectorAll("td");
                total += parseFloat($(columnas[7]).find('input').val());
            });
            total = total.toFixed(2);
            pie += '<tr>'
            pie += '<td ></td>'
            pie += '<td ></td>'
            pie += '<td ></td>'
            pie += '<td ></td>'
            pie += '<td ><h6><b>Total</b></h6></td>'
            pie += '<td style="text-align: right"><h6><b>'+total+'</b></h6></td>'
            pie += '</tr>'
            $("#tblCargos tfoot tr").remove();
            $('#tblCargos tfoot').append(pie);
        }

        function fnAnularDocumento(){
            $('#documento_id').val($('#id').val());
            jQuery.noConflict();
            $('#anulacionModal').modal('show')
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
                        // if (origen == 'P') {
                        //     window.location.href = "{{ route('pacientes') }}";
                        // } else if (origen == 'A') {
                        //     window.location.href = "{{ route('nueva_agenda') }}";
                        // }
                        window.location.href = "{{ route('documentos_listado') }}";
                    }
                });
        }
    </script>
@endsection