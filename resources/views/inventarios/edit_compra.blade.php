@extends('adminlte::page')
@section('css')
    <style>
        /* Estilos Mobile First para la tabla de detalles */
        @media (max-width: 768px) {
            #tblDetalle, #tblDetalle thead, #tblDetalle tbody, #tblDetalle th, #tblDetalle td, #tblDetalle tr {
                display: block;
            }
            #tblDetalle thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            #tblDetalle tr {
                border: 1px solid #ccc;
                margin-bottom: 10px;
                padding: 10px;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }
            #tblDetalle td {
                border: none;
                position: relative;
                padding-left: 50% !important;
                text-align: left !important;
                margin-bottom: 5px;
                width: 100% !important;
                display: block !important;
                padding-left: 10px !important;
            }
            #tblDetalle input[type="number"], 
            #tblDetalle input[type="text"],
            #tblDetalle .select2-container {
                width: 100% !important;
                display: block !important;
            }

            #tblDetalle td:before {
                position: absolute;
                left: 10px;
                width: 45%;
                white-space: nowrap;
                font-weight: bold;
                color: #495057;
                font-size: 11px;
                text-transform: uppercase;
            }
            /* Etiquetas para cada celda en móvil */
            #tblDetalle td:nth-of-type(1):before { content: "Artículo"; }
            #tblDetalle td:nth-of-type(2):before { content: "U.M."; }
            #tblDetalle td:nth-of-type(3):before { content: "Cantidad"; }
            #tblDetalle td:nth-of-type(4):before { content: "Unitario"; }
            #tblDetalle td:nth-of-type(5):before { content: "Subtotal"; }
            
            #tblDetalle td:last-child {
                padding-left: 0 !important;
                text-align: center !important;
                border-top: 1px solid #eee;
                margin-top: 10px;
                padding-top: 10px !important;
            }
            .form-control-sm {
                margin-bottom: 8px;
            }
        }

        @media (max-width: 768px) {
            .form-control-sm, .custom-select-sm {
                height: calc(1.5em + 1rem + 2px) !important; /* Más alto para dedos */
                font-size: 16px !important; /* Evita que iOS haga zoom automático al enfocar */
            }
            .btn-sm {
                padding: 0.5rem !important; /* Botones más grandes */
                font-size: 1rem !important;
            }
            /* El botón de agregar artículo debe ser ancho completo en móvil */
            .btn-primary.rounded-pill {
                width: 100%;
                padding: 12px !important;
            }
        }

        .card-header {
            position: sticky;
            top: 0;
            z-index: 1020;
            background-color: #E1E8ED !important;
        }
    </style>
@endsection
@section('title', 'Compras')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 col-lg-12">
            <form class="form-horizontal" id="FormaCompra" method="post" action="{{ route('actualizar_compra')}}">
                @csrf
                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold"><i class="fas fa-shopping-cart">&nbsp;Edición Compra</i></h6>
                            <div>
                                @if(!$esSoloLectura)
                                <button type="submit" class="btn btn-sm btn-outline-success rounded-circle elevation-2" title="Guardar">
                                    <i class="fas fa-save"></i>
                                </button>
                                @endif
                                <a href="#" class="btn btn-sm btn-outline-danger rounded-circle elevation-2" title="Salir" onclick="confirma_salida();">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-1 p-sm-3">
                        <div class="row">
                            <div class="col-12 col-md-5 col-lg-4 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-header py-1" style="background-color: #f8f9fa;">
                                        <small class="font-weight-bold">Datos de Proveedor</small>
                                    </div>
                                    <div class="card-body p-2">
                                        <input type="hidden" id="compra_id" name="compra_id" value="{{ $encabezado->id }}">
                                        <input type="hidden" id="proveedor_id" name="proveedor_id" value="{{ old('proveedor_id', $encabezado->proveedor_id) }}">
                                        
                                        <label class="small mb-0">N.I.T.</label>
                                        <div class="input-group input-group-sm mb-2">
                                            <input type="text" class="form-control" placeholder="Buscar..." id="nit" name="nit" value="{{ old('nit', $encabezado->nit) }}" onchange="trae_proveedor();" style="text-transform: uppercase;" autofocus required {{ $esSoloLectura ? 'readonly' : '' }}>
                                            <div class="input-group-append">
                                                <button class="btn" style="background-color: #7FB3D5;" id="actaulizarProveedor" type="button" data-toggle="modal" data-target="#proveedorModal">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <label class="small mb-0">Nombre</label>
                                        <input type="text" class="form-control form-control-sm mb-2" id="proveedor_nombre" value="{{ $encabezado->proveedor->nombre_comercial }}" disabled>

                                        <label class="small mb-0">Días Crédito</label>
                                        <input type="text" class="form-control form-control-sm" id="dias_credito" name="dias_credito" value="{{ old('dias_credito', $encabezado->dias_credito) }}" readonly style="text-align: right;" onchange="calcularVencimiento();">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-7 col-lg-8 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-header py-1" style="background-color: #f8f9fa;">
                                        <small class="font-weight-bold">Datos de Comprobante</small>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <label class="small mb-0">Documento</label>
                                                <select class="custom-select custom-select-sm select2bs4" {{ $esSoloLectura ? 'disabled' : '' }} id="documento_id" name="documento_id" required>
                                                    <option value="" selected>Seleccionar...</option>
                                                    @foreach($tipo_documentos as $td)
                                                        <option value="{{ $td->id }}" 
                                                            {{ (old('documento_id', $encabezado->tipo_documento_id) == $td->id) ? 'selected' : '' }}>
                                                            {{ $td->descripcion }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <label class="small mb-0">Serie</label>
                                                <input type="text" class="form-control form-control-sm text-uppercase" id="serie" name="serie" value="{{ old('serie', $encabezado->serie) }}" required {{ $esSoloLectura ? 'readonly' : '' }}>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <label class="small mb-0">Correlativo</label>
                                                <input type="number" class="form-control form-control-sm text-right" id="numero_documento" name="numero_documento" value="{{ old('numero_documento', $encabezado->numero_documento) }}" required {{ $esSoloLectura ? 'readonly' : '' }}>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <label class="small mb-0">Fch. Emisión</label>
                                                <input type="date" class="form-control form-control-sm" id="fecha_emision" name="fecha_emision" value="{{ old('fecha_emision', $encabezado->fecha_emision) }}" onchange="calcularVencimiento();" required {{ $esSoloLectura ? 'readonly' : '' }}>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <label class="small mb-0">Fch. Vencimiento</label>
                                                <input type="date" class="form-control form-control-sm" id="fecha_vencimiento" name="fecha_vencimiento" value="{{ old('fecha_vencimiento', $encabezado->fecha_vencimiento) }}" readonly>
                                            </div>
                                            <div class="col-6">
                                                <label class="small mb-0 text-primary">Bodega de Ingreso</label>
                                                <select class="custom-select custom-select-sm select2bs4" id="bodega_id" name="bodega_id" required {{ $esSoloLectura ? 'readonly' : '' }}>
                                                    <option value="" selected>Seleccionar...</option>
                                                    @foreach($bodegas as $b)
                                                        <option value="{{ $b->id }}" 
                                                            {{ (old('bodega_id', $encabezado->bodega_origen_id) == $b->id) ? 'selected' : '' }}>
                                                            {{ $b->descripcion }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <label class="small mb-0">Monto</label>
                                                <input type="number" step="any" min="0.01" class="form-control form-control-sm text-right" id="total" name="total" value="{{ old('total', $encabezado->total) }}" required {{ $esSoloLectura ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right mb-2">
                            @if(!$esSoloLectura)
                            <button type="button" class="btn btn-sm rounded-pill px-3 shadow-sm" style="background-color: #7FB3D5;" onclick="agregarFila();" title="Agregar Insumo">
                                <i class="fas fa-plus mr-1"></i>
                            </button>
                            @endif
                        </div>

                        <div class="table-responsive" style="-webkit-overflow-scrolling: touch;">
                            <table id="tblDetalle" class="table table-sm table-striped table-hover border">
                                <thead class="bg-light shadow-sm">
                                    <tr style="font-size: 11px; text-transform: uppercase;">
                                        <th style="min-width: 200px;">Insumo</th>
                                        <th style="width: 80px;">U.M.</th>
                                        <th style="width: 80px;">Cant.</th>
                                        <th style="width: 100px;">Unitario</th>
                                        <th style="width: 100px;">Subtotal</th>
                                        <th style="width: 40px;"></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <td style="min-width: 200px;"></td>
                                        <th style="width: 80px;"></th>
                                        <th style="width: 80px;"></th>
                                        <th style="width: 100px;">
                                            <label class="small mb-0 text-primary">Total:</label>
                                        </th>
                                        <td style="width: 100px; text-align: right;">
                                            <label id="txtTotal" class="small mb-0 text-primary font-weight-bold text-center">0.00</label>
                                            <input type="hidden" id="inputTotalHidden" name="total_final" value="0.00">
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            // Manejo de mensajes de éxito/error desde la sesión
            @if(session('message'))
                Swal.fire({
                    title: "{{ session('type') == 'success' ? '¡Éxito!' : 'Error' }}",
                    text: "{!! session('message') !!}",
                    icon: "{{ session('type') ?? 'info' }}",
                    confirmButtonColor: "{{ session('type') == 'success' ? '#28a745' : '#dc3545' }}",
                    confirmButtonText: 'Aceptar'
                });
            @endif

            // Mostrar errores de validación de Laravel (importante si no guarda)
            @if ($errors->any())
                Swal.fire({
                    title: "Errores de validación",
                    html: `<ul style="text-align: left;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                           </ul>`,
                    icon: 'error',
                    confirmButtonText: 'Corregir'
                });
            @endif

            @if($esSoloLectura)
                $('#documento_id').prop('disabled', true).trigger('change');
                // O para todos los selects de una vez:
                $('.select2bs4').prop('disabled', true).trigger('change');
            @endif
        });
    </script>
    <script type="text/javascript">
        //========================================================================
        // inicializar librerias
        //========================================================================
        $(document).ready(function() {
            // Inicialización robusta para Bootstrap 4
            $('.select2bs4').each(function() {
                $(this).select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: "Seleccionar...",
                    allowClear: true,
                    // Si el select está dentro de un modal, descomenta la siguiente línea:
                    // dropdownParent: $(this).parent() 
                });
            });

            // Corrección de bug de foco en el buscador de Select2
            $(document).on('select2:open', () => {
                let searchField = document.querySelector('.select2-search__field');
                if (searchField) {
                    searchField.focus();
                }
            });

            $('#FormaCompra').on('submit', function(e) {
                // 1. Obtener los valores crudos para comparar
                // Usamos parseFloat para asegurar que la comparación sea numérica
                const montoManual = parseFloat($('#total').val()) || 0;
                const montoCalculado = parseFloat($('#inputTotalHidden').val()) || 0;

                // 2. Validar si son diferentes (usamos un margen de 0.01 por decimales)
                if (Math.abs(montoManual - montoCalculado) > 0.01) {
                    
                    // 3. Detener el envío del formulario
                    e.preventDefault(); 
                    e.stopPropagation();

                    // 4. Mostrar el mensaje con SweetAlert2
                    Swal.fire({
                        title: "¡Montos no coinciden!",
                        html: `El total de la factura (<b>Q ${montoManual.toFixed(2)}</b>) no coincide con el total de artículos (<b>Q ${montoCalculado.toFixed(2)}</b>).`,
                        icon: 'warning',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Revisar'
                    });

                    return false;
                }
                
                // Si coinciden, el formulario se envía normalmente
            });

            // Verificar si hay datos anteriores (después de un error de validación)
            @if(old('productos'))
                const productosAnteriores = @json(old('productos'));
                
                Object.values(productosAnteriores).forEach(p => {
                    // Llamamos a tu función existente para crear la fila
                    agregarFila();
                    
                    // Llenamos los campos de la última fila creada
                    let index = nLinea - 1;
                    $(`select[name="productos[${index}][articulo_id]"]`).val(p.articulo_id).trigger('change');
                    $(`input[name="productos[${index}][cantidad]"]`).val(p.cantidad);
                    $(`input[name="productos[${index}][precio_unitario]"]`).val(p.precio_unitario);
                    $(`input[name="productos[${index}][precio_total]"]`).val(p.precio_total);
                    
                    // Como la unidad de medida depende de un AJAX, usamos un pequeño timeout 
                    // o la cargamos manualmente si es necesario
                    setTimeout(() => {
                        $(`select[name="productos[${index}][unidad_medida_id]"]`).val(p.unidad_medida_id).trigger('change');
                    }, 1000);
                });

                // Recalcular el total de la tabla
                total_tabla();
            @endif

            const detalleExistente = @json($encabezado->detalles);
            const esSoloLectura = {{ $esSoloLectura ? 'true' : 'false' }};

            if (detalleExistente && detalleExistente.length > 0) {
                // 1. Limpiar el cuerpo de la tabla antes de empezar (por seguridad)
                $("#tblDetalle tbody").empty();
                nLinea = 0;

                detalleExistente.forEach(item => {
                    // Llamamos a tu función para crear la fila vacía
                    agregarFila(esSoloLectura);

                    // Obtenemos el índice de la fila recién creada (nLinea - 1)
                    let i = nLinea - 1;
                    
                    // Llenamos los valores
                    $(`select[name="productos[${i}][articulo_id]"]`).val(item.producto_id).trigger('change');
                    $(`input[name="productos[${i}][cantidad]"]`).val(item.cantidad);
                    $(`input[name="productos[${i}][precio_unitario]"]`).val(item.precio_unitario);

                    // 5. Cargar la unidad de medida y calcular total de línea
                    cargarUnidadGuardada(i, item.unidad_medida_id);
                    total_linea(i);
                });
            }
        });

        function llenarUnidadMedidaEdicion(filaId, productoId, unidadSeleccionadaId) {
            var select = document.getElementById("productos[" + filaId + "][unidad_medida_id]");
            
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('trae_medidas_x_producto') }}",
                method: "POST",
                data: {producto_id: productoId},
                success: function(response) {
                    $(select).empty().append('<option value="">Seleccionar...</option>');
                    
                    response.forEach(function(res) {
                        let selected = (res.unidad_medida_id == unidadSeleccionadaId) ? 'selected' : '';
                        $(select).append(`<option value="${res.unidad_medida_id}" ${selected}>${res.unidad_medida_descripcion}</option>`);
                    });
                    
                    // Refrescar Select2 para mostrar el valor seleccionado
                    $(select).trigger('change');
                }
            });
        }

        function cargarUnidadGuardada(filaId, unidadGuardadaId) {
            // 1. Obtener el ID del artículo de esa fila específica
            var producto_id = document.getElementById("productos[" + filaId + "][articulo_id]").value;
            var select = document.getElementById("productos[" + filaId + "][unidad_medida_id]"); 
            
            if (producto_id) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('trae_medidas_x_producto') }}",
                    method: "POST",
                    data: { producto_id: producto_id },
                    success: function(response) {
                        // Limpiar el select
                        $(select).empty().append('<option value="">Seleccionar...</option>');
                        
                        // Llenar con las opciones del servidor
                        for (var i = 0; i < response.length; i++) {
                            var unitId = response[i]['unidad_medida_id'];
                            var unitDesc = response[i]['unidad_medida_descripcion'];
                            
                            // Comprobar si esta es la unidad que debe estar seleccionada
                            var isSelected = (unitId == unidadGuardadaId) ? 'selected' : '';
                            
                            $(select).append(`<option value="${unitId}" ${isSelected}>${unitDesc}</option>`);
                        }
                        
                        // Refrescar Select2 para que muestre el cambio visualmente
                        $(select).trigger('change');
                    },
                    error: function(error) {
                        console.log("Error al cargar unidades:", error);
                    }
                });
            }
        }

        //========================================================================
        // Variables
        //========================================================================
        var nLinea = 0;
        var nFila  = 1;
        const productos_db = @json($productos);
        productos_db.sort(compare);

        //========================================================================
        // funcion para ordenar detalle
        //========================================================================

        function compare(a,b){
            const valorA = a.linea;
            const valorB = b.linea;
            let comparacion = 0;

            if (valorA < valorB) {
                comparacion = -1;
            }else{
                comparacion = 1;
            }
            return comparacion;
        }

        //========================================================================
        // trae datos de proveedor en base a nit ingresado
        //========================================================================
        function trae_proveedor(){
            var vnit = document.getElementById('nit').value;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('trae_generales') }}",
                method: "POST",
                data: {nit: vnit},
                success: function(response){
                    var proveedor_encontrado = (Object.keys(response).length);
                    if (proveedor_encontrado == 0) {
                        document.getElementById('nit').value = '';
                        document.getElementById('proveedor_id').value = '';
                        document.getElementById('proveedor_nombre').value = '';
                        document.getElementById('dias_credito').value = '';
                        Swal.fire({
                            title: "Error",
                            text: "! Proveedor no encontrado!",
                            icon: 'error', // En v2 es 'icon', no 'type'
                            showConfirmButton: true,
                            confirmButtonText: 'Aceptar'
                        });
                    }else{
                        document.getElementById('proveedor_id').value = response.id;
                        document.getElementById('proveedor_nombre').value = response.nombre_comercial;
                        document.getElementById('dias_credito').value = response.dias_credito;
                        document.getElementById('fecha_vencimiento').value = document.getElementById('fecha_emision').value + response.dias_credito;
                        calcularVencimiento();
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        //========================================================================
        // Agregar una nueva fila a la tabla
        //========================================================================

        /*function agregarFila() {
            let html = `
                <tr>
                    <input type="hidden" name="productos[${nLinea}][id]" value="${nLinea}">
                    
                    <td style="min-width: 250px;">
                        <select class="custom-select custom-select-sm select2bs4" 
                                id="productos[${nLinea}][articulo_id]" 
                                name="productos[${nLinea}][articulo_id]" 
                                onchange="actualizarMedidas(${nLinea});" required>
                            <option value="">Seleccionar...</option>
                            ${productos_db.map(p => `<option value="${p.id}">${p.descripcion}</option>`).join('')}
                        </select>
                    </td>

                    <td style="min-width: 120px;">
                        <select class="custom-select custom-select-sm select2bs4" 
                                id="productos[${nLinea}][unidad_medida_id]" name="productos[${nLinea}][unidad_medida_id]" required>
                        </select>
                    </td>

                    <td style="width: 100px; min-width: 80px;">
                        <input type="number" class="form-control form-control-sm text-right" 
                            placeholder="0" id="productos[${nLinea}][cantidad]" name="productos[${nLinea}][cantidad]" 
                            step="any" onchange="total_linea(${nLinea})" required>
                    </td>

                    <td style="width: 120px; min-width: 100px;">
                        <input type="number" class="form-control form-control-sm text-right" 
                            placeholder="0" id="productos[${nLinea}][precio_unitario]" name="productos[${nLinea}][precio_unitario]" 
                            onchange="total_linea(${nLinea})" step="any" required>
                    </td>

                    <td style="width: 120px; min-width: 100px;">
                        <input type="number" class="form-control form-control-sm text-right bg-light" 
                            placeholder="0" id="productos[${nLinea}][precio_total]" name="productos[${nLinea}][precio_total]" 
                            step="any" required readonly>
                    </td>

                    <td class="text-center" style="width: 50px;">
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-circle shadow-sm" 
                                onclick="eliminarFila(this)">
                            <i class="fa fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>`;

            $("#tblDetalle > tbody").append(html);
            
            // Inicializar Select2 específicamente para los nuevos elementos
            $(`select[name="productos[${nLinea}][articulo_id]"]`).select2({
                theme: 'bootstrap4',
                width: '100%'
            });
            
            $(`select[name="productos[${nLinea}][unidad_medida_id]"]`).select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            nFila += 1;
            nLinea += 1;
        }*/
        function agregarFila(esSoloLectura = false) { 
            let html = `
                <tr>
                    <input type="hidden" name="productos[${nLinea}][id]" value="${nLinea}">
                    
                    <td style="min-width: 250px;">
                        <select class="custom-select custom-select-sm select2bs4" 
                                id="productos[${nLinea}][articulo_id]" 
                                name="productos[${nLinea}][articulo_id]" 
                                onchange="actualizarMedidas(${nLinea});" 
                                required ${esSoloLectura ? 'disabled' : ''}> 
                            <option value="">Seleccionar...</option>
                            ${productos_db.map(p => `<option value="${p.id}">${p.descripcion}</option>`).join('')}
                        </select>
                    </td>

                    <td style="min-width: 120px;">
                        <select class="custom-select custom-select-sm select2bs4" 
                                id="productos[${nLinea}][unidad_medida_id]" 
                                name="productos[${nLinea}][unidad_medida_id]" 
                                required ${esSoloLectura ? 'disabled' : ''}> 
                        </select>
                    </td>

                    <td style="width: 100px;">
                        <input type="number" class="form-control form-control-sm text-right" 
                            id="productos[${nLinea}][cantidad]" name="productos[${nLinea}][cantidad]" 
                            onchange="total_linea(${nLinea})" required 
                            ${esSoloLectura ? 'readonly' : ''}> 
                    </td>

                    <td style="width: 120px;">
                        <input type="number" class="form-control form-control-sm text-right" 
                            id="productos[${nLinea}][precio_unitario]" 
                            name="productos[${nLinea}][precio_unitario]" 
                            onchange="total_linea(${nLinea})" required 
                            ${esSoloLectura ? 'readonly' : ''}> 
                    </td>

                    <td style="width: 120px;">
                        <input type="number" class="form-control form-control-sm text-right bg-light" 
                            id="productos[${nLinea}][precio_total]" 
                            name="productos[${nLinea}][precio_total]" 
                            readonly> 
                    </td>

                    <td class="text-center" style="width: 50px;">
                        ${!esSoloLectura ? `
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle shadow-sm" 
                                    onclick="eliminarFila(this)">
                                <i class="fa fa-trash-alt"></i>
                            </button>
                        ` : ''}
                    </td>
                </tr>`;

            $("#tblDetalle > tbody").append(html);
            
            // Inicializar Select2 para los nuevos elementos
            $(`#productos\\[${nLinea}\\]\\[articulo_id\\]`).select2({ theme: 'bootstrap4', width: '100%' });
            $(`#productos\\[${nLinea}\\]\\[unidad_medida_id\\]`).select2({ theme: 'bootstrap4', width: '100%' });

            nLinea++;
        }

        //========================================================================
        // actualizar unidad de medida en base a producto seleccionado
        //========================================================================
        function actualizarMedidas(id){
            var producto_id = document.getElementById("productos["+id+"][articulo_id]").value;
            var select      = document.getElementById("productos["+id+"][unidad_medida_id]"); 
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('trae_medidas_x_producto') }}",
                method: "POST",
                data: {producto_id: producto_id},
                success: function(response){
                    var el = document.createElement("option");
                    el.textContent = 'Seleccionar...';
                    el.value = '';
                    select.appendChild(el);
                    for (var i = 0; i < response.length; i++) {
                        var opt = response.length;
                        var el = document.createElement("option");
                        el.textContent = response[i]['unidad_medida_descripcion'];
                        el.value = response[i]['unidad_medida_id'];
                        select.appendChild(el);
                    }
                },
                error: function(error){
                    console.log(error);
                }       
            });
        }

        //===================================================================
        // Total de la linea
        //===================================================================
        function total_linea(id){
            var cantidad        = document.getElementById('productos['+id+'][cantidad]').value;
            var precio_unitario = document.getElementById('productos['+id+'][precio_unitario]').value;
            document.getElementById('productos['+id+'][precio_total]').value = (cantidad * precio_unitario).toFixed(2);
            total_tabla();
        }

        //===================================================================
        // Total de la tabla
        //===================================================================
        function total_tabla() {
            let total = 0;
            
            // Es más rápido buscar directamente los inputs de subtotal
            // Asumiendo que tus inputs terminan en [precio_total]
            $('#tblDetalle tbody tr').each(function() {
                // Buscamos el input que contiene el subtotal de la fila
                // Usamos un selector que busque el final del nombre del input
                let valorInput = $(this).find('input[name$="[precio_total]"]').val();
                
                // Si no usas nombres dinámicos, puedes usar una clase: .input-subtotal
                let subtotal = parseFloat(valorInput);
                
                if (!isNaN(subtotal)) {
                    total += subtotal;
                }
            });

            let totalFormateado = total.toFixed(2);

            // Formato visual para el usuario (con comas y puntos)
            let formatoMoneda = new Intl.NumberFormat('es-GT', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(total);

            // Actualización de la interfaz
            $('#txtTotal').text(formatoMoneda);
            $('#inputTotalHidden').val(totalFormateado);
            //$("#total").val(totalFormateado);
        }

        //========================================================================
        // eliminar fila especifica de la tabla
        //========================================================================
        function eliminarFila(row){
            var d = row.parentNode.parentNode.rowIndex; 
            document.getElementById('tblDetalle').deleteRow(d);
            total_tabla();
        }

        //===================================================================
        // Confirmar salida de pantalla
        //===================================================================
        function confirma_salida(){
            Swal.fire({
                title: 'Confirmación',
                text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745', // Color success de AdminLTE
                cancelButtonColor: '#dc3545',  // Color danger de AdminLTE
                confirmButtonText: 'Si, Salir',
                cancelButtonText: 'No',
                allowEscapeKey: true,
                reverseButtons: true // Opcional: pone el botón de confirmar a la derecha
            }).then((result) => {
                if (result.isConfirmed) { 
                    window.location.href = "{{ route('lista_compras') }}";
                } 
            });
        }

        //===================================================================
        // Calcular fecha de vencimiento
        //===================================================================
        function calcularVencimiento() {
            const fchEmision = document.getElementById('fecha_emision').value;
            const dias = parseInt(document.getElementById('dias_credito').value) || 0;
            const campoVencimiento = document.getElementById('fecha_vencimiento');

            if (fchEmision) {
                // Creamos la fecha a partir del string YYYY-MM-DD
                let fecha = new Date(fchEmision + 'T00:00:00'); 
                
                // Sumamos los días de crédito
                fecha.setDate(fecha.getDate() + dias);

                // Formateamos de vuelta a YYYY-MM-DD para el input
                const yyyy = fecha.getFullYear();
                const mm = String(fecha.getMonth() + 1).padStart(2, '0');
                const dd = String(fecha.getDate()).padStart(2, '0');
                
                campoVencimiento.value = `${yyyy}-${mm}-${dd}`;
            }
        }
    </script>
@endsection