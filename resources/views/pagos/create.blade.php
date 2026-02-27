@extends('adminlte::page')
@section('css')
    <style type="text/css">
        .nav-pills .nav-link.active,
        .show>.nav-pills .nav-link{
            background: #7FB3D5 !important;
            color: #000000 !important;
        }
        .table-responsive {
            max-width: 100%; /* Ajusta el ancho según tus necesidades */
            overflow-x: auto; /* Permite el desplazamiento horizontal */
        }
        /* Tabla de escritorio: Resaltar la columna 6 (Monto Aplicado) */
        #tblprincipal td:nth-child(6) {
            font-weight: bold;
            color: #28a745; /* Verde para dinero */
        }
        /* Estilos para resaltar el monto en la vista móvil */
        .mobile-monto {
            font-weight: bold;
            color: #28a745;
            font-size: 1.1rem;
        }

        .numero{
            text-align: right;
        }
        .moneda:after {
            content: attr(data-numero);
        }

        @media (max-width: 767.98px) {
            /* 1. Ocultar los encabezados de la tabla en móviles */
            .table-mobile-cards thead {
                display: none;
            }
            
            /* 2. Convertir cada fila (tr) en una tarjeta con sombra y bordes */
            .table-mobile-cards tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: 0.35rem;
                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
                background-color: #fff;
            }

            /* 3. Convertir cada celda (td) en un renglón dentro de la tarjeta */
            .table-mobile-cards td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #f4f6f9;
                padding: 0.5rem 1rem;
                text-align: right; /* Alineamos el valor a la derecha */
            }

            /* Quitar borde al último elemento (usualmente botones) y centrarlo */
            .table-mobile-cards td:last-child {
                border-bottom: 0;
                justify-content: center; 
                background-color: #f8f9fa; /* Un fondo gris sutil para las acciones */
            }

            /* 4. Leer el atributo data-label y ponerlo como título a la izquierda */
            .table-mobile-cards td::before {
                content: attr(data-label);
                font-weight: bold;
                color: #495057;
                text-align: left;
                padding-right: 15px;
            }
        }
    </style>
@endsection
@section('title', 'Comprobante de Pago')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="container-fluid px-0 px-md-2">
        <form role="form" id="form-factura" method="POST" action="{{ route('recibo_grabar') }}">
            @csrf
            <div class="card shadow-sm">
                <div class="card-header" style="background-color: #E1E8ED;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold">Nuevo Recibos</h6>
                        <div>
                            <button type="submit" class="btn btn-sm btn-outline-success rounded-circle elevation-2" title="Grabar"><i class="fas fa-save"></i></button>
                            <a href="#" class="btn btn-sm btn-outline-danger rounded-circle elevation-2 ml-1" title="Salir" onclick="confirma_salida();">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-2 p-md-3">
                    <input type="hidden" id="tipo_documento_id" name="tipo_documento_id" value="{{ $documento->id }}">
                    <input type="hidden" id="resolucion_id" name="resolucion_id">
                    <input type="hidden" id="caja_id" name="caja_id" value="{{ $caja->id }}">
                    <input type="hidden" id="caja_editar_documento" name="caja_editar_documento" value="{{ $caja->editar_documento}}">
                    <input type="hidden" id="recibo_estado" name="recibo_estado" value="P">
                    <input type="hidden" id="total_saldo" name="total_saldo">
                    <input type="hidden" id="total_pago" name="total_pago">
                    <input type="hidden" id="recibo_total_pago" name="recibo_total_pago">
                    <div class="row">
                        <div class="col-12 col-lg-12">
                            <ul class="nav nav-pills nav-justified p-2">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#documento_pago" data-toggle="tab" id="tab-detalle">Recibo</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#datos_facturacion" data-toggle="tab" id="tab-pago">Busqueda</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="documento_pago">
                                    <div class="card-body">
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
                                <div class="tab-pane" id="datos_facturacion">
                                    <div class="card-body">
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
                        <div class="col-12 col-lg-12">
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
                                                <table id="tblDocumentos" class="table table-sm table-hover text-center table-mobile-cards">
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
                                                <table id="tblPagos" class="table table-sm table-hover text-center table-mobile-cards" style="font-size: 12px;">
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
        </form>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            // 1. Manejar mensajes manuales (Success/Error personalizados)
            @if(session('message'))
                Swal.fire({
                    title: "{{ session('type') == 'success' ? '¡Éxito!' : 'Atención' }}",
                    text: "{!! addslashes(session('message')) !!}",
                    icon: "{{ session('type') }}",
                    confirmButtonColor: '#3085d6'
                });
            @endif

            // 2. Manejar errores de validación automáticos
            @if($errors->any())
                Swal.fire({
                    title: "Error de validación",
                    html: `
                        <ul style="text-align: left;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    `,
                    icon: "error",
                    confirmButtonColor: '#d33'
                });
            @endif
        });
    </script>
    <script>
        $(document).ready(function() {
            @if(session('message'))
                Swal.fire({
                    title: "{{ session('type') == 'success' ? '¡Éxito!' : 'Error' }}",
                    text: "{!! addslashes(session('message')) !!}",
                    icon: "{{ session('type') }}"
                });
            @endif
        });
    </script>
    <script>
        $(document).ready(function() {
            @if(session('message'))
                Swal.fire({
                    title: "{{ session('type') == 'success' ? '¡Éxito!' : 'Error' }}",
                    text: "{!! addslashes(session('message')) !!}",
                    icon: "{{ session('type') }}",
                    confirmButtonColor: '#3085d6'
                });
            @endif 
        });

        var nLineap = 0;
        const listaBancos = @json($bancos);
        const listaTarjetas = @json($tarjetas);

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

        function fn_documentos_con_saldo(){
            var paciente    = document.getElementById('paciente_id');
            var serie       = document.getElementById('busqueda_serie').value;
            var correlativo = document.getElementById('busqueda_correlativo').value;
            var paciente_id = paciente.options[paciente.selectedIndex].value;
            if (paciente_id.length == 0) {
                paciente_id = 0;
            }

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
                    console.log(response);
                    var html = '';
                    for (var i = 0; i < response.length; i++) {
                        html += '<tr style="font-size: 12px;">'
                        html += '<td data-label="Tipo">'
                        html += '<input type="text" class="form-control" id="documentos['+i+'][descripcion]" name="documentos['+i+'][descripcion]" value="'+response[i]['tipodocumento_descripcion']+'" readonly/>';
                        html += '</td>'
                        html += '<td data-label="Fecha">'
                        html += '<input type="date" class="form-control" id="documentos['+i+'][fecha_emision]" name="documentos['+i+'][fecha_emision]" value="'+response[i]['fecha_emision']+'" readonly/>';
                        html += '</td>'
                        html += '<td data-label="Documento">'
                        html += '<input type="text" class="form-control" id="documentos['+i+'][documento]" name="documentos['+i+'][documento]" value="'+response[i]['serie']+' - '+response[i]['correlativo']+'" readonly/>';
                        html += '</td>'
                        html += '<td data-label="N.I.T.">'
                        html += '<input type="text" class="form-control" id="documentos['+i+'][nit]" name="documentos['+i+'][nit]" value="'+response[i]['nit']+'" readonly/>';
                        html += '</td>'
                        html += '<td data-label="Nombre">'
                        html += '<input type="text" class="form-control" id="documentos['+i+'][nombre]" name="documentos['+i+'][nombre]" value="'+response[i]['nombre']+'" readonly/>';
                        html += '</td>'
                        html += '<td data-label="Total">'
                        html += '<input type="number" step="any" min="0.01" class="form-control classtotal numero" id="documentos['+i+'][total]" name="documentos['+i+'][total]" value="'+response[i]['total']+'" readonly/>';
                        html += '</td>'
                        html += '<td data-label="Saldo">'
                        html += '<input type="number" step="any" min="0.01" class="form-control classtotal numero" id="documentos['+i+'][saldo]" name="documentos['+i+'][saldo]" value="'+response[i]['saldo_actual']+'" readonly/>';
                        html += '</td>'
                        html += '<td data-label="Pago">'
                        html += '<input type="hidden" class="form-control classtotal numero" id="documentos['+i+'][id]" name="documentos['+i+'][id]" value="'+response[i]['id']+'"/>';
                        html += '<input type="number" step="any" min="0.01" max="'+response[i]['saldo']+'" class="form-control classtotal numero monto" id="documentos['+i+'][monto]" name="documentos['+i+'][monto]"/>';
                        html += '</td>'
                        html += '<td data-label="Acciones">'
                        html += '</td>'
                        html += '</tr>'
                    }
                    $("#tblDocumentos tbody tr").remove();
                    $('#tblDocumentos tbody').append(html);
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
            html += '<td data-label="Forma de pago">';
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
            html += '<td data-label="Entidad">';
            html += '<select id="mpago['+nLineap+'][casa_id]" name="mpago['+nLineap+'][casa_id]" class="form-control" data-required="true" readonly>';
            html += '<option value="">Seleccionar...</option>';
            html += '</select>';
            html += '</td>';
            html += '<td data-label="Cuenta">';
            html += '<input type="number" step="1" min="1" class="form-control classnumero numero" id="mpago['+nLineap+'][cuenta_no]" name="mpago['+nLineap+'][cuenta_no]" value="1" onchange="fnCalculo('+nLineap+');" readonly/>';
            html += '</td">';
            html += '<td data-label="Documento">';
            html += '<input type="number" step="any" min="1" class="form-control classprecio numero" id="mpago['+nLineap+'][documento_no]" name="mpago['+nLineap+'][documento_no]" readonly/>';
            html += '</td">';
            html += '<td data-label="Autorización">';
            html += '<input type="number" step="any" min="1" class="form-control classprecio numero" id="mpago['+nLineap+'][autoriza_no]" name="mpago['+nLineap+'][autoriza_no]" readonly/>';
            html += '</td">';
            html += '<td data-label="Total" class="font-weight-bold text-success">';
            html += '<input type="number" step="any" min="1" class="form-control classtotal numero montoPago" id="mpago['+nLineap+'][monto]" name="mpago['+nLineap+'][monto]"/>';
            html += '</td">';
            html += '<td width="35px"><a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-1 eliminar"><i class="fas fa-trash-alt"></i></a></td>';
            html += '</tr>';
            $('#tblPagos tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLineap += 1;
        }

        /*=========================================================================================
        Eliminar registro de tabla
        =========================================================================================*/
        function eliminar(){
            var whichtr = $(this).closest("tr");
            whichtr.remove(); 
            return false;
        }

        //=========================================================================================
        // Habilitar registro de pago
        //=========================================================================================
        function habilitarRegistro(id) {
            const $fpagoSelect = $(`#mpago\\[${id}\\]\\[fpago_id\\]`);
            const fpago_id = $fpagoSelect.val();

            if (!fpago_id) return;

            $.ajax({
                url: "{{ route('campos_requeridos') }}",
                type: "POST",
                data: { "_token": "{{ csrf_token() }}", fpago_id: fpago_id },
                success: function(response) {
                    const selectorCasa = `#mpago\\[${id}\\]\\[casa_id\\]`;
                    const $selectCasa = $(selectorCasa);
                    console.log(response);
                    // 1. Determinar si el campo de "Casa/Entidad" debe habilitarse
                    // Basado en tu lógica: Se habilita si viene banco='S' O casa='S' (o como lo maneje tu backend)
                    const habilitarCasa = (response.banco === 'S' || response.casa === 'S');

                    $selectCasa.prop('disabled', !habilitarCasa);
                    $selectCasa.css('background-color', habilitarCasa ? '#fff' : '#e9ecef');

                    if (habilitarCasa) {
                        // 2. Lógica de selección de lista:
                        // Si banco = 'S' y casa = 'N' -> listaBancos, de lo contrario -> listaTarjetas
                        const datosAInsertar = (response.banco === 'S' && response.casa === 'N') 
                                               ? listaBancos 
                                               : listaTarjetas;

                        // 3. Limpiar y llenar el select
                        $selectCasa.empty().append('<option value="">Seleccione Entidad...</option>');
                        
                        $.each(datosAInsertar, function(index, item) {
                            $selectCasa.append($('<option>', {
                                value: item.id,
                                text: item.nombre // Ajusta 'nombre' según el campo de tu tabla bancos
                            }));
                        });
                    } else {
                        $selectCasa.empty().append('<option value="">N/A</option>').val('');
                    }

                    // 4. Otros campos (Cuenta, Documento, Autorización)
                    const otrosCampos = {
                        'cuenta': 'cuenta_no',
                        'documento': 'documento_no',
                        'autorizacion': 'autoriza_no'
                    };

                    $.each(otrosCampos, function(key, suffix) {
                        const selector = `#mpago\\[${id}\\]\\[${suffix}\\]`;
                        const activo = (response[key] === 'S');
                        $(selector).prop('readonly', !activo).val(activo ? $(selector).val() : '');
                        $(selector).css('background-color', activo ? '#fff' : '#e9ecef');
                    });
                },
                error: function(e) {
                    console.error("Error en validación de campos", e);
                }
            });
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
    </script>
@endsection