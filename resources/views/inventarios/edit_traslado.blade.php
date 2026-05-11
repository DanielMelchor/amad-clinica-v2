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
            <form class="form-horizontal" id="FormaTraslado" method="post" action="{{ route('actualizar_traslado')}}">
                @csrf
                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold"><i class="fas fa-paper-plane">&nbsp;Edición Traslado</i></h6>
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
                        <input type="hidden" id="compra_id" name="traslado_id" value="{{ $encabezado->id }}">
                        <div class="row mb-3">
                            <div class="col-12 col-md-6 mb-2">
                                <label class="small mb-0">Bodega Origen</label>
                                <select class="form-control select2bs4" 
                                        name="bodega_origen_id" 
                                        id="bodega_origen_id" 
                                        required 
                                        style="width: 100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach($bodegas as $pBodega)
                                        <option value="{{$pBodega->id}}" @if($pBodega->id == $encabezado->bodega_origen_id) selected @endif>{{$pBodega->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6 mb-2">
                                <label class="small mb-0">Bodega Destino</label>
                                <select class="form-control select2bs4" 
                                        name="bodega_destino_id" 
                                        id="bodega_destino_id" 
                                        required 
                                        style="width: 100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach($bodegas as $pBodega)
                                        <option value="{{$pBodega->id}}"  @if($pBodega->id == $encabezado->bodega_destino_id) selected @endif>{{$pBodega->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light font-weight-bold">Fecha</span>
                                    </div>
                                    <input type="date" class="form-control" name="fecha" id="fecha" value="{{ $encabezado->created_at->format('Y-m-d') }}" required {{ $esSoloLectura ? 'readonly' : '' }}>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light font-weight-bold">Motivo</span>
                                    </div>
                                    <input type="text" class="form-control" name="comentario" id="comentario" placeholder="Ej. Inventario inicial, corrección..." required {{ $esSoloLectura ? 'readonly' : '' }}>
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

                        {{-- SECCIÓN DETALLE --}}
                        <div class="table-responsive" style="-webkit-overflow-scrolling: touch;">
                            <table id="tblDetalle" class="table table-sm table-striped table-hover border">
                                <thead class="bg-light shadow-sm">
                                    <tr>
                                        <th style="width: 50%">Insumo</th>
                                        <th class="text-center">Medida</th>
                                        <th class="text-center" style="width: 15%">Cantidad</th>
                                        <th class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
                $('#bodega_origen_id').prop('disabled', true).trigger('change');
                $('#bodega_destino_id').prop('disabled', true).trigger('change');
                // O para todos los selects de una vez:
                $('.select2bs4').prop('disabled', true).trigger('change');
            @endif
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#bodega_origen_id').select2({
                theme: 'bootstrap4',
                width: 'style', // Esto hace que tome el ancho del elemento original
                placeholder: 'Seleccione...'
            });
            $('.select2bs4').each(function() {
                $(this).select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: "Seleccione...",
                    allowClear: true,
                    // Si el select está dentro de un modal, descomenta la siguiente línea:
                    // dropdownParent: $(this).parent() 
                });
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
                    $(`select[name="productos[${index}][tipo]"]`).val(p.tipo).trigger('change');
                    $(`input[name="productos[${index}][cantidad]"]`).val(p.cantidad);
                    
                    // Como la unidad de medida depende de un AJAX, usamos un pequeño timeout 
                    // o la cargamos manualmente si es necesario
                    setTimeout(() => {
                        $(`select[name="productos[${index}][unidad_medida_id]"]`).val(p.unidad_medida_id).trigger('change');
                    }, 1000);
                });
            @endif

            const detalleExistente = @json($encabezado->detalles);
            const esSoloLectura = {{ $esSoloLectura ? 'true' : 'false' }};

            if (detalleExistente && detalleExistente.length > 0) {
                // 1. Limpiar el cuerpo de la tabla antes de empezar (por seguridad)
                $("#tblDetalle tbody").empty();
                nLinea = 0;

                detalleExistente.forEach(item => {
                    // Llamamos a tu función para crear la fila vacía
                    agregarFila(esSoloLectura, item);

                    // Obtenemos el índice de la fila recién creada (nLinea - 1)
                    let i = nLinea - 1;
                    
                    // Llenamos los valores
                    //$(`select[name="productos[${i}][articulo_id]"]`).val(item.producto_id).trigger('change');
                    //$(`select[name="productos[${i}][tipo]"]`).val(item.tipo).trigger('change');
                    //$(`input[name="productos[${i}][cantidad]"]`).val(item.cantidad);

                    // 5. Cargar la unidad de medida y calcular total de línea
                    cargarUnidadGuardada(i, item.unidad_medida_id);
                });
            }
        });

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

        function agregarFila(esSololectura = false, datos = null) {
            console.log(datos)
            let html = `
                <tr>
                    <input type="hidden" name="productos[${nLinea}][id]" value="${nLinea}">
                    
                    <td style="min-width: 250px;">
                        <select class="custom-select custom-select-sm select2bs4" 
                                id="productos[${nLinea}][articulo_id]" 
                                name="productos[${nLinea}][articulo_id]" 
                                onchange="actualizarMedidas(${nLinea});" required ${esSololectura ? 'disabled' : ''}>
                            <option value="">Seleccionar...</option>
                            ${productos_db.map(p => `
                            <option value="${p.id}" ${(datos && datos.producto_id == p.id) ? 'selected' : ''}>
                                ${p.descripcion}
                            </option>`).join('')}
                        </select>
                    </td>

                    <td style="min-width: 120px;">
                        <select class="custom-select custom-select-sm select2bs4" 
                                id="productos[${nLinea}][unidad_medida_id]" 
                                name="productos[${nLinea}][unidad_medida_id]" 
                                required  ${esSololectura ? 'disabled' : ''}>
                            <option value="">Seleccionar...</option>
                        </select>
                    </td>
                    <td style="width: 100px; min-width: 80px;">
                        <input type="number" class="form-control text-right" 
                            placeholder="0" id="productos[${nLinea}][cantidad]" name="productos[${nLinea}][cantidad]" value="${datos ? datos.cantidad : ''}" 
                            step="any" required ${esSololectura ? 'disabled' : ''}>
                    </td>

                    <td class="text-center" style="width: 50px;">
                        ${!esSololectura ? `
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle shadow-sm" 
                                    onclick="eliminarFila(this)">
                                <i class="fa fa-trash-alt"></i>
                            </button>
                        ` : ''}
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

            $(`select[name="productos[${nLinea}][tipo]"]`).select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            nFila += 1;
            nLinea += 1;
        }

        function eliminarFila(btn) {
            if ($('#tblDetalle tbody tr').length > 1) {
                $(btn).closest('tr').remove();
            } else {
                Swal.fire("Atención", "El ajuste debe tener al menos un producto", "warning");
            }
        }

        function confirma_salida() {
            Swal.fire({
                title: '¿Desea salir?',
                text: "Se perderán los cambios no guardados",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, salir',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('lista_traslados') }}";
                }
            });
        }

        function actualizarMedidas(linea){
            var x = document.getElementById("productos["+linea+"][articulo_id]").selectedIndex;
            var y = document.getElementById("productos["+linea+"][articulo_id]").options;
            var producto_id = y[x].value;              

            $.ajax({
                url: "{{ route('trae_medidas_x_producto') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}", producto_id: producto_id},
                success: function(response){
                    if (response.length == 0) {
                        let dropdown = document.getElementById("productos["+linea+"][unidad_medida_id]");
                        dropdown.length = 0;
                        let option;
                        option = document.createElement('option');
                        option.text = 'Unidad';
                        option.value = 1;
                        dropdown.add(option);
                    }else{
                        let dropdown = document.getElementById("productos["+linea+"][unidad_medida_id]");
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

        $('#FormaTraslado').on('submit', function(e) {
            e.preventDefault();
            $('#submitButton').prop('disabled', true);
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire("Éxito", "Ajuste guardado correctamente", "success")
                        .then(() => window.location.href = "{{ route('lista_traslados') }}");
                },
                error: function(xhr) {
                    $('#submitButton').prop('disabled', false);
                    let mensaje = xhr.responseJSON ? xhr.responseJSON.message : "Error al guardar";
                    Swal.fire("Error", mensaje, "error");
                }
            });
        });
    </script>
@endsection