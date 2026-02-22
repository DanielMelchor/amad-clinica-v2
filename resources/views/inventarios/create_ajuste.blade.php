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

@section('title', 'Ajustes')

@section('content')
    <div class="row pt-3">
        <div class="col-12 col-lg-10 offset-lg-1">
            <div class="card shadow-lg border-0">
                <form role="form" id="FormaAjuste" method="POST" action="{{route('grabar_ajuste')}}">
                    @csrf
                    <div class="card-header py-2" style="background-color: #E1E8ED;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold text-secondary">
                                <i class="fas fa-tools mr-2"></i>Nuevo Ajuste
                            </h6>
                            <div class="d-flex">
                                <button type="submit" id="submitButton" class="btn btn-xs btn-outline-success rounded-circle mr-2 shadow-sm elevation-2" title="Guardar">
                                    <i class="fas fa-save"></i>
                                </button>
                                <a href="#" class="btn btn-xs btn-outline-danger rounded-circle shadow-sm elevation-2" title="Salir" onclick="confirma_salida();">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        {{-- SECCIÓN CABECERA --}}
                        <div class="row mb-3">
                            <div class="col-12 col-md-6 mb-3">
                                <div class="input-group input-group-sm flex-column flex-md-row">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light font-weight-bold justify-content-center" 
                                              style="min-width: 100px; height: 100%;">
                                            Bodega
                                        </span>
                                    </div>
                                    <select class="form-control select2" name="bodega_id" id="bodega_id" required>
                                        <option value="">Seleccione...</option>
                                        @foreach($bodegas as $pBodega)
                                            <option value="{{$pBodega->id}}">{{$pBodega->descripcion}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mb-2">
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

                        <hr>

                        {{-- SECCIÓN DETALLE --}}
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="tblDetalle">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 50%">Producto</th>
                                        <th class="text-center">Tipo</th>
                                        <th class="text-center" style="width: 15%">Cantidad</th>
                                        <th class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="fila_0">
                                        <td data-label="Producto">
                                            <select class="form-control form-control-sm select2" name="id_producto[]" required>
                                                <option value="">Seleccionar...</option>
                                                @foreach($productos as $pProducto)
                                                    <option value="{{$pProducto->id}}">{{$pProducto->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td data-label="Tipo" class="text-center">
                                            <select class="form-control form-control-sm" name="tipo_ajuste[]">
                                                <option value="I">Ingreso (+)</option>
                                                <option value="E">Egreso (-)</option>
                                            </select>
                                        </td>
                                        <td data-label="Cantidad" class="text-center">
                                            <input type="number" class="form-control form-control-sm text-right" name="cantidad[]" step="0.01" min="0.01" required>
                                        </td>
                                        <td data-label="Acción" class="text-center">
                                            <button type="button" class="btn btn-xs btn-danger rounded-circle elevation-2" onclick="eliminarFila(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-sm btn-primary mt-2" onclick="agregarFila()">
                            <i class="fas fa-plus mr-1"></i> Agregar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('.select2').each(function() {
                $(this).select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: "Seleccione...",
                    allowClear: true,
                    // Si el select está dentro de un modal, descomenta la siguiente línea:
                    // dropdownParent: $(this).parent() 
                });
            });

            // Fix para que el buscador reciba el foco automáticamente al abrir
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
            
            $('#bodega_id').select2({
                theme: 'bootstrap4',
                width: '100%', // Esto soluciona el desbordamiento
                placeholder: "Seleccione una bodega"
            });
        });

        function agregarFila() {
            let id = Date.now();
            let fila = `
                <tr id="fila_${id}">
                    <td data-label="Producto">
                        <select class="form-control form-control-sm select2" name="id_producto[]" required>
                            <option value="">Seleccionar...</option>
                            @foreach($productos as $pProducto)
                                <option value="{{$pProducto->id}}">{{$pProducto->descripcion}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td data-label="Tipo" class="text-center">
                        <select class="form-control form-control-sm" name="tipo_ajuste[]">
                            <option value="I">Ingreso (+)</option>
                            <option value="E">Egreso (-)</option>
                        </select>
                    </td>
                    <td data-label="Cantidad" class="text-center">
                        <input type="number" class="form-control form-control-sm text-right" name="cantidad[]" step="0.01" min="0.01" required>
                    </td>
                    <td data-label="Acción" class="text-center">
                        <button type="button" class="btn btn-xs btn-danger rounded-circle elevation-2" onclick="eliminarFila(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
            $('#tblDetalle tbody').append(fila);
            $('.select2').select2({ width: '100%' });
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
                    window.location.href = "{{ route('lista_ajustes') }}";
                }
            });
        }

        $('#FormaAjuste').on('submit', function(e) {
            e.preventDefault();
            $('#submitButton').prop('disabled', true);
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire("Éxito", "Ajuste guardado correctamente", "success")
                        .then(() => window.location.href = "{{ route('lista_ajustes') }}");
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