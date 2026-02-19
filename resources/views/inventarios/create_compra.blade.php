@extends('adminlte::page')
@section('css')
@endsection
@section('title', 'Compras')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 col-lg-12">
            <form class="form-horizontal" id="FormaCompra" action="#">
                @csrf
                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold">Nueva Compra</h6>
                            <div>
                                <button type="submit" id="btn_guardar_admision" class="btn btn-xs btn-outline-success rounded-circle elevation-2" title="Guardar">
                                    <i class="fas fa-save"></i>
                                </button>
                                <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-2" title="Salir" onclick="confirma_salida();">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-2 p-md-3">
                        <div class="row">
                            <div class="col-12 col-md-5 col-lg-4 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-header py-1" style="background-color: #f8f9fa;">
                                        <small class="font-weight-bold">Datos de Proveedor</small>
                                    </div>
                                    <div class="card-body p-2">
                                        <input type="hidden" id="proveedor_id" name="proveedor_id">
                                        
                                        <label class="small mb-0">N.I.T.</label>
                                        <div class="input-group input-group-sm mb-2">
                                            <input type="text" class="form-control" placeholder="Buscar..." id="nit" name="nit" onchange="trae_proveedor();" style="text-transform: uppercase;" autofocus required>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#proveedorModal">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <label class="small mb-0">Nombre</label>
                                        <input type="text" class="form-control form-control-sm mb-2" id="proveedor_nombre" disabled>

                                        <label class="small mb-0">Días Crédito</label>
                                        <input type="text" class="form-control form-control-sm" id="dias_credito" readonly style="text-align: right;">
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
                                                <select class="custom-select custom-select-sm select2bs4" id="documento_id" name="documento_id" required>
                                                    <option value="" selected>Seleccionar...</option>
                                                    @foreach($tipo_documentos as $td)
                                                        <option value="{{ $td->id }}">{{ $td->descripcion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <label class="small mb-0">Serie</label>
                                                <input type="text" class="form-control form-control-sm text-uppercase" id="serie" name="serie" required>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <label class="small mb-0">Correlativo</label>
                                                <input type="number" class="form-control form-control-sm text-right" id="numero_documento" name="numero_documento" required>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <label class="small mb-0">Fch. Emisión</label>
                                                <input type="date" class="form-control form-control-sm" id="fecha_emision" name="fecha_emision" value="{{ $hoy }}" required>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <label class="small mb-0">Fch. Vencimiento</label>
                                                <input type="date" class="form-control form-control-sm" id="fecha_vencimiento" disabled>
                                            </div>
                                            <div class="col-12">
                                                <label class="small mb-0 text-primary">Bodega de Ingreso</label>
                                                <select class="custom-select custom-select-sm select2bs4" id="bodega_id" name="bodega_id" required>
                                                    <option value="" selected>Seleccionar...</option>
                                                    @foreach($bodegas as $b)
                                                        <option value="{{ $b->id }}">{{ $b->descripcion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right mb-2">
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" onclick="agregarFila();">
                                <i class="fas fa-plus mr-1"></i> Agregar Artículo
                            </button>
                        </div>

                        <div class="table-responsive" style="-webkit-overflow-scrolling: touch;">
                            <table id="tblDetalle" class="table table-sm table-striped table-hover border">
                                <thead class="bg-light shadow-sm">
                                    <tr style="font-size: 11px; text-transform: uppercase;">
                                        <th style="min-width: 200px;">Artículo</th>
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
                                        <th style="width: 80px;"</th>
                                        <th style="width: 80px;"></th>
                                        <th style="width: 100px;">
                                            <label class="small mb-0 text-primary">Total:</label>
                                        </th>
                                        <td style="width: 100px;">
                                            <div class="col-6 text-right">
                                                <label id="txtTotal" class="small mb-0 text-primary font-weight-bold text-center">0.00</label>
                                            </div>
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
    @if(Session::get('type') == 'success')
        @if(Session::has('message'))
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: "Trabajo Finalizado",
                        text: "{{ Session::get('message') }}",
                        icon: 'success', // En v2 es 'icon', no 'type'
                        confirmButtonColor: '#28a745', // Color success de AdminLTE
                        showConfirmButton: true,
                        confirmButtonText: 'Aceptar'
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
                        title: "Error",
                        text: "{!! Session::get('message') !!}",
                        icon: 'error', // En v2 es 'icon', no 'type'
                        showConfirmButton: true,
                        confirmButtonText: 'Aceptar'
                    });
                }, 1000);
            </script>
        @endif
    @endif
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
        });

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
                        <button type="button" class="btn btn-xs btn-outline-danger rounded-circle shadow-sm" 
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
            $("#total").val(totalFormateado);
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
                    window.location.href = "{{ route('lista_ajustes') }}";
                } 
            });
        }
    </script>
@endsection