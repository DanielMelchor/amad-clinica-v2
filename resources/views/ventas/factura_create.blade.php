@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
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
    <form role="form" id="form-factura" method="post" action="{{ route('factura_grabar') }}">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="background-color: #E1E8ED;">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="row">
                            <div class="col-9">
                                <h6>Nueva Factura</h6>
                            </div>
                            <div class="col-3" style="text-align: right;">
                                <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Grabar"><i class="fas fa-save" title="Guardar"></i></button>
                                <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="background-color: white">
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
                                                        <select class="custom-select custom-select-sm select2 select2bs4" id="tipo_documento_id" name="tipo_documento_id" required onchange="fn_resolucion_x_serie(); return false;">
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
                                                <div class="col-lg-5 col-sm-10 offset-lg-6">
                                                    <div class="input-group input-group-sm mb-1">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text"># Admisión</label>
                                                        </div>
                                                        <input type="number" step="1" min="0" class="form-control card-text numero" id="admision_no" name="admision_no" value="">
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-sm-1" style="text-align: right;">
                                                    <a href="#" class="btn btn-xs btn-default rounded-circle elevation-4" title="Realizar Busqueda" onclick="fnAdmision();"><i class="fas fa-search"></i></a>
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
                                                <a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="agregar_cargo(); return false;" title="Agregar Cargo"><i class="fas fa-plus-circle"></i></a>
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
                                                <a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="agregar_pago(); return false;" title="Agregar medio de pago"><i class="fas fa-plus-circle"></i></a>
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
        var verBanco    = 'N';
        var Referencia  = 'T';

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
            html += '<td width="35px"><a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-1 eliminar"><i class="fas fa-trash-alt"></i></a></td>';
            html += '</tr>';
            $('#tblCargos tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLinea += 1;
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
            if ({{$forma_pago->id}} == 1){
                html += '<option value="{{$forma_pago->id}}" selected >{{$forma_pago->descripcion}}</option>';
            }else{
                html += '<option value="{{$forma_pago->id}}">{{$forma_pago->descripcion}}</option>';
            }
            @endforeach
            html += '</select>';
            html += '</td>';
            html += '<td width="125px">';
            html += '<select id="mpago['+nLineap+'][casa_id]" name="mpago['+nLineap+'][casa_id]" class="form-control" data-required="true" disabled>';
            html += '<option value="">Seleccionar...</option>';
            html += '</select>';
            html += '</td>';
            html += '<td width="75px">';
            html += '<input type="number" step="1" min="1" class="form-control classnumero numero" id="mpago['+nLineap+'][cuenta_no]" name="mpago['+nLineap+'][cuenta_no]" value="1" onchange="fnCalculo('+nLineap+');" disabled/>';
            html += '</td">';
            html += '<td width="125px">';
            html += '<input type="number" step="any" min="1" class="form-control classprecio numero" id="mpago['+nLineap+'][documento_no]" name="mpago['+nLineap+'][documento_no]" disabled/>';
            html += '</td">';
            html += '<td width="125px">';
            html += '<input type="number" step="any" min="1" class="form-control classprecio numero" id="mpago['+nLineap+'][autoriza_no]" name="mpago['+nLineap+'][autoriza_no]" disabled/>';
            html += '</td">';
            html += '<td width="125px">';
            html += '<input type="number" step="any" min="1" class="form-control classtotal numero" id="mpago['+nLineap+'][monto]" name="mpago['+nLineap+'][monto]"/>';
            html += '</td">';
            html += '<td width="35px"><a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-1 eliminar"><i class="fas fa-trash-alt"></i></a></td>';
            html += '</tr>';
            $('#tblPagos tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLinea += 1;
        }

        function habilitarRegistro(nLineap) {
            // Obtenemos el valor seleccionado del select de forma de pago
            // Usamos escape para los corchetes [ ] ya que son caracteres especiales en selectores de ID
            var fpago_id = document.getElementById("mpago[" + nLineap + "][fpago_id]").value;

            var fPagoSelect = $('[id="mpago[' + nLineap + '][fpago_id]"]');
            var casaSelect = $('[id="mpago[' + nLineap + '][casa_id]"]');
            var valorSeleccionado = fPagoSelect.val();

            // 2. Si no hay selección, deshabilitar y limpiar
            if (valorSeleccionado === "") {
                casaSelect.prop('disabled', true).html('<option value="">Seleccionar...</option>');
                // Deshabilitar los demás inputs de la fila
                $('[id^="mpago[' + nLineap + ']"]').not(fPagoSelect).prop('disabled', true);
                return;
            }

            if (verBanco == 'S') {
                Referencia = 'B';
            }else{
                Referencia = 'T';
            }

            // Si el usuario seleccionó algo (valor no vacío)
            if (fpago_id !== "") {
                $.ajax({
                    url: "{{ route('campos_requeridos') }}",
                    type: "POST",
                    async: true,
                    data: {"_token": "{{ csrf_token() }}", fpago_id: fpago_id},
                    success: function(response){
                        // console.log(response);
                        verBanco = response.banco;
                        if (response.banco == 'S') {
                            $('[id="mpago[' + nLineap + '][casa_id]"]').prop('required', true);
                            $('[id="mpago[' + nLineap + '][casa_id]"]').prop('disabled', false);
                        }else{
                            $('[id="mpago[' + nLineap + '][casa_id]"]').prop('required', false);
                            $('[id="mpago[' + nLineap + '][casa_id]"]').prop('disabled', true);
                        }
                        if (response.cuenta == 'S') {
                            $('[id="mpago[' + nLineap + '][cuenta_no]"]').prop('required', true);
                            $('[id="mpago[' + nLineap + '][cuenta_no]"]').prop('disabled', false);
                        }else{
                            $('[id="mpago[' + nLineap + '][cuenta_no]"]').prop('required', false);
                            $('[id="mpago[' + nLineap + '][cuenta_no]"]').prop('disabled', true);
                        }
                        if (response.documento == 'S') {
                            $('[id="mpago[' + nLineap + '][documento_no]"]').prop('required', true);
                            $('[id="mpago[' + nLineap + '][documento_no]"]').prop('disabled', false);
                        }else{
                            $('[id="mpago[' + nLineap + '][documento_no]"]').prop('required', false);
                            $('[id="mpago[' + nLineap + '][documento_no]"]').prop('disabled', true);
                        }
                        if (response.autorizacion == 'S') {
                            $('[id="mpago[' + nLineap + '][autoriza_no]"]').prop('required', true);
                            $('[id="mpago[' + nLineap + '][autoriza_no]"]').prop('disabled', false);
                        }else{
                            $('[id="mpago[' + nLineap + '][autoriza_no]"]').prop('required', false);
                            $('[id="mpago[' + nLineap + '][autoriza_no]"]').prop('disabled', true);
                        }

                        $.ajax({
                            url: "{{ route('formas_de_pago') }}",
                            type: "POST",
                            async: true,
                            data: {"_token": "{{ csrf_token() }}", parametro: Referencia},
                            success: function(response){
                                var opciones = '<option value="">Seleccionar...</option>';
                                // Iterar sobre los datos recibidos (asumiendo formato JSON [{id:1, nombre:'Banco X'}, ...])
                                $.each(response, function(key, value) {
                                    opciones += '<option value="' + value.id + '">' + value.nombre + '</option>';
                                });

                                casaSelect.html(opciones);
                            },
                            error: function(error){
                                console.log(error);
                            }
                        });
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
                
                // Opcional: Poner el foco en el siguiente campo automáticamente
                $('[id="mpago[' + nLineap + '][casa_id]"]').focus();
            } else {
                // Si vuelve a seleccionar "Seleccionar...", los volvemos a deshabilitar
                $('[id="mpago[' + nLineap + '][casa_id]"]').prop('disabled', true);
                $('[id="mpago[' + nLineap + '][cuenta_no]"]').prop('disabled', true);
                $('[id="mpago[' + nLineap + '][documento_no]"]').prop('disabled', true);
                $('[id="mpago[' + nLineap + '][autoriza_no]"]').prop('disabled', true);
            }
        }

        // =========================================================================================
        // Eliminar registro de tabla
        // =========================================================================================
        function eliminar(){
            var whichtr = $(this).closest("tr");
            whichtr.remove(); 
            return false;
        }

        // =========================================================================================
        // Actualizar unidad de medida
        // =========================================================================================
        function actualizarMedida(linea){
            var x = document.getElementById("cargos["+linea+"][producto_id]").selectedIndex;
            var y = document.getElementById("cargos["+linea+"][producto_id]").options;
            var producto_id = y[x].value;              
            $.ajax({
                url: "{{ route('descripcion') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}", cod: producto_id},
                success: function(response){
                    document.getElementById("cargos["+linea+"][descripcion]").value = response;
                },
                error: function(error){
                    console.log(error);
                }
            });

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
        Calculo de total en cargos
        =========================================================================================*/
        function fnCalculo(linea){
            var cantidad = document.getElementById("cargos["+linea+"][cantidad]").value;
            var precio   = document.getElementById("cargos["+linea+"][precio]").value;
            var total    = (cantidad * precio).toFixed(2);
            document.getElementById("cargos["+linea+"][total]").value = total;
            total_tabla();
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

        //====================================================================================
        // resolucion por tipo de documento
        //====================================================================================
        function fn_resolucion_x_serie(){
            var caja_id                    = document.getElementById('caja_id').value;
            var caja_editar_documento      = document.getElementById('caja_editar_documento').value;
            var tipo_documento_id = document.getElementById('tipo_documento_id').value;
            var serie                      = document.getElementById('serie').value;

            if (caja_editar_documento == '0') {
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
                            document.getElementById('serie').value = info.serie;
                            document.getElementById('correlativo').value = info.correlativo;
                        }else{
                            document.getElementById('resolucion_id').value = '';
                            document.getElementById('serie').value = '';
                            document.getElementById('correlativo').value = '';
                            Swal.fire({
                                title: "¡Error!",
                                text: "Caja no cuenta con una resolucion activa para el tipo de documento",
                                icon: "error", // Cambiado de 'type' a 'icon'
                                confirmButtonText: "Aceptar",
                                confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                },
                                buttonsStyling: false
                            });
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

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('form-factura');
            const admisionNo = @json($parametro_admision);
            if (admisionNo != 0) {
                $('#admision_no').val(admisionNo);
                fnAdmision();
            }

            console.log("El ID de admisión es:", admisionNo);

            form.addEventListener('submit', function (e) {
                // Previene el envío automático
                e.preventDefault();

                const filasCargos = document.querySelectorAll('#tblCargos tbody tr');

                if (filasCargos.length === 0) {
                    setTimeout(function() {
                        swal({
                            title: "Error",
                            text: "Debe agregar al menos un cargo antes de guardar la factura.",
                            type: "error"
                        });
                    }, 1000);
                    // alert('Debe agregar al menos un cargo antes de guardar la factura.');
                    return;
                }

                const condicionSeleccionada = document.querySelector('input[name="condicion"]:checked').value;

                if (condicionSeleccionada == 0) {
                    const filasPagos = document.querySelectorAll('#tblPagos tbody tr');

                    if (filasPagos.length === 0) {
                        setTimeout(function() {
                            Swal.fire({
                                title: "¡Error!",
                                text: "Pendiente definir medio de pago",
                                icon: "error", // Cambiado de 'type' a 'icon'
                                confirmButtonText: "Aceptar",
                                confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                },
                                buttonsStyling: false
                            });
                        }, 1000);
                        return;
                    }
                }


                 // Si todo está bien, enviar el formulario
                form.submit();
            });
        });

        function fnAdmision(){
            var admision = $('#admision_no').val();
            $.ajax({
                url: "{{ route('get_id_x_admision') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}", admision: admision},
                success: function(response){
                    if (response == undefined || response.length == 0) {
                        setTimeout(function() {
                            swal({
                                title: "Error",
                                text: "Admisión no encontrada, favor verifique",
                                type: "error"
                            });
                        }, 1000);
                        // alert('Debe agregar al menos un cargo antes de guardar la factura.');
                        return;
                    }else{
                        var admision_id = response;
                        $('#admision_id').val(admision_id);

                        $.ajax({
                            url: "{{ route('admision_estado') }}",
                            type: "POST",
                            async: true,
                            data: {"_token": "{{ csrf_token() }}", admision_id: admision_id},
                            success: function(response){
                                if (response['estado'] != 1) {
                                    setTimeout(function() {
                                        swal({
                                            title: "Revisar",
                                            text: "Admisión no ha sido Cerrada, Favor verifique",
                                            type: "error"
                                        });
                                    }, 1000);                                    
                                }else{
                                    $.ajax({
                                        url: "{{ route('trae_cargos_a_facturar') }}",
                                        type: "POST",
                                        async: true,
                                        data: {"_token": "{{ csrf_token() }}", 
                                               admision_id: admision_id},
                                        success: function(response){
                                            $('#tblCargos').DataTable().clear().destroy();
                                            $("#tblCargos tbody").empty();
                                            if (response.length == 0) {
                                                setTimeout(function() {
                                                    swal({
                                                        title: "Error",
                                                        text: "Admisión no cuenta con saldo pendiente de facturar",
                                                        type: "error"
                                                    }, function(){
                                                        $('#admision_no').val(null);
                                                    });
                                                }, 1000);
                                            }else{
                                                for (var i = 0; i < response.length; i++) {
                                                    agregar_cargo();
                                                    $('#cargos\\['+i+'\\]\\[admision_id\\]').val(response[i]['admision_id']);
                                                    $('#cargos\\['+i+'\\]\\[movimiento_id\\]').val(response[i]['detalle_movimiento_id']);
                                                    $('#cargos\\['+i+'\\]\\[producto_id\\]').val(response[i]['producto_id']).trigger('change');
                                                    $('#cargos\\['+i+'\\]\\[descripcion\\]').val(response[i]['descripcion']);
                                                    $('#cargos\\['+i+'\\]\\[medida_id\\]').val(response[i]['unidad_medida_id']).trigger('change');
                                                    $('#cargos\\['+i+'\\]\\[cantidad\\]').val(response[i]['cantidad']);
                                                    $('#cargos\\['+i+'\\]\\[precio\\]').val(response[i]['precio_unitario']);
                                                    $('#cargos\\['+i+'\\]\\[total\\]').val(response[i]['precio_bruto']);
                                                }
                                                total_tabla();
                                                $.ajax({
                                                    url: "{{ route('datos_facturacion_x_admision') }}",
                                                    type: "POST",
                                                    async: true,
                                                    data: {"_token": "{{ csrf_token() }}", admision_id: admision_id},
                                                    success: function(response){
                                                        $('#nit').val(response['nit']);
                                                        $('#nombre').val(response['nombre']);
                                                        $('#direccion').val(response['direccion']);
                                                    },
                                                    error: function(error){
                                                        console.log(error);
                                                    }
                                                });
                                            }
                                        },
                                        error: function(error){
                                            console.log(error);
                                        }
                                    });
                                }
                            },
                            error: function(error){
                                console.log(error);
                            }
                        });
                    }
                    
                },
                error: function(error){
                    console.log(error);
                }
            });
        }
    </script>
@endsection