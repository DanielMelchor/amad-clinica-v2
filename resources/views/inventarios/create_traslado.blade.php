@extends('adminlte::page')

@section('css')
    <style>
        /* --- ESTILOS MOBILE FIRST --- */
        .select2-container {
            width: 100% !important;
        }
        
        /* Ajuste de inputs para que no se amontonen en móvil */
        @media (max-width: 768px) {
            .input-group {
                display: flex;
                flex-direction: column;
            }
            .input-group-prepend, .input-group-text {
                width: 100% !important;
                display: block !important;
                border-radius: 0.25rem 0.25rem 0 0 !important;
                text-align: center;
            }
            .input-group > .form-control, 
            .input-group > .select2-container {
                width: 100% !important;
                border-radius: 0 0 0.25rem 0.25rem !important;
            }

            .input-group-sm > .select2-container--default .select2-selection--single {
                border-top-left-radius: 0 !important;
                border-bottom-left-radius: 0 !important;
            }

            /* Transformación de la tabla de detalles en tarjetas */
            #tblDetalle thead { display: none; }
            #tblDetalle tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: 0.5rem;
                padding: 10px;
                background: #fdfdfd;
            }
            #tblDetalle td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border: none !important;
                padding: 0.5rem 0 !important;
                text-align: right !important;
            }
            #tblDetalle td:before {
                content: attr(data-label);
                font-weight: bold;
                text-align: left;
                flex: 1;
            }
            /* Hacer que los inputs dentro de la tabla ocupen el ancho disponible */
            #tblDetalle input {
                max-width: 150px;
            }

            .select2-container--default .select2-selection--single {
                border-radius: 0 0 0.25rem 0.25rem !important;
                height: calc(2.25rem + 2px) !important; /* Altura estándar de AdminLTE */
            }
        }
        @media (max-width: 767.98px) {
            .input-group-prepend, 
            .input-group-text {
                width: 100% !important;
                border-radius: 0.25rem 0.25rem 0 0 !important;
                justify-content: center !important;
            }
            
            .select2-container--default .select2-selection--single {
                border-top-left-radius: 0 !important;
                border-top-right-radius: 0 !important;
                border-bottom-left-radius: 0.25rem !important;
                border-bottom-right-radius: 0.25rem !important;
            }
        }
    </style>
@endsection

@section('title', 'Traslados')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="row pt-3">
        <div class="col-12 col-lg-12">
            <div class="card shadow-lg border-0">
                <form role="form" id="FormaTraslado" method="POST" action="{{route('grabar_traslado')}}">
                    @csrf
                    <div class="card-header py-2" style="background-color: #E1E8ED;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold">
                                <i class="fas fa-tools mr-2"></i>Nuevo Traslado
                            </h6>
                            <div class="d-flex">
                                <button type="submit" id="submitButton" class="btn btn-sm btn-outline-success rounded-circle mr-2 shadow-sm elevation-2" title="Guardar">
                                    <i class="fas fa-save"></i>
                                </button>
                                <a href="#" class="btn btn-sm btn-outline-danger rounded-circle shadow-sm elevation-2" title="Salir" onclick="confirma_salida();">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        {{-- SECCIÓN CABECERA --}}
                        <div class="row mb-3">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="small mb-0">Bodega Origen</label>
                                <select class="form-control form-control-sm select2bs4 border-0" 
                                        name="bodega_origen_id" 
                                        id="bodega_origen_id" 
                                        required 
                                        style="width: 100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach($bodegas as $pBodega)
                                        <option value="{{$pBodega->id}}">{{$pBodega->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="small mb-0">Bodega Destino</label>
                                <select class="form-control form-control-sm select2bs4 border-0" 
                                        name="bodega_destino_id" 
                                        id="bodega_destino_id" 
                                        required 
                                        style="width: 100%;">
                                    <option value="">Seleccione...</option>
                                    @foreach($bodegas as $pBodega)
                                        <option value="{{$pBodega->id}}">{{$pBodega->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light font-weight-bold">Fecha</span>
                                    </div>
                                    <input type="date" class="form-control" name="fecha" id="fecha" value="{{date('Y-m-d')}}" required>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light font-weight-bold">Motivo</span>
                                    </div>
                                    <input type="text" class="form-control" name="comentario" id="comentario" placeholder="Ej. Inventario inicial, corrección..." required>
                                </div>
                            </div>
                        </div>

                        <div class="text-right mb-2">
                            <button type="button" class="btn btn-sm rounded-pill px-3 shadow-sm" style="background-color: #7FB3D5;" onclick="agregarFila();" title="Agregar Insumo">
                                <i class="fas fa-plus mr-1"></i>
                            </button>
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
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @if(Session::has('message'))
        <script>
            Swal.fire({
                title: "{{ Session::get('type') == 'success' ? '¡Éxito!' : 'Atención' }}",
                text: "{{ Session::get('message') }}",
                icon: "{{ Session::get('type') }}", // 'success' o 'error'
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                // Si fue error, habilitamos el botón para que el usuario corrija
                if("{{ Session::get('type') }}" === 'error'){
                    $('#submitButton').prop('disabled', false);
                }
            });
        </script>
        
        {{-- Limpiamos la sesión para que no se repita el mensaje al recargar --}}
        @php Session::forget(['message', 'type']); @endphp
    @endif
    <script>
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
        });


        //========================================================================
        // Variables
        //========================================================================
        var nLinea = 0;
        var nFila  = 1;
        const productos_db = @json($productos);

        function agregarFila() {
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
                                id="productos[${nLinea}][unidad_medida_id]" 
                                name="productos[${nLinea}][unidad_medida_id]" 
                                required>
                            <option value="">Seleccionar...</option>
                        </select>
                    </td>

                    <td style="width: 100px; min-width: 80px;">
                        <input type="number" class="form-control text-right" 
                            placeholder="0" id="productos[${nLinea}][cantidad]" name="productos[${nLinea}][cantidad]" 
                            step="any" required>
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
                    /*$('#submitButton').prop('disabled', false);
                    let mensaje = xhr.responseJSON ? xhr.responseJSON.message : "Error al guardar";
                    Swal.fire("Error", mensaje, "error");*/
                    $('#submitButton').prop('disabled', false);
                    let mensaje = "Ocurrió un error al procesar el traslado.";
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        mensaje = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        title: "No se puede realizar el traslado",
                        text: mensaje, // Aquí aparecerá: "El producto 'X' no está autorizado..."
                        icon: "warning",
                        confirmButtonText: "Revisar Detalle"
                    });
                }
            });
        });
    </script>
@endsection