@extends('adminlte::page')
@section('css')
@endsection
@section('title', 'Ajustes')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 col-lg-10 offset-lg-1">
            <div class="card shadow-sm border-0">
                <form role="form" id="FormaAjuste" method="POST" action="{{route('grabar_ajuste')}}">
                    @csrf
                    <div class="card-header py-2" style="background-color: #E1E8ED;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold text-secondary">Nuevo Ajuste</h6>
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
                        <div class="row mb-3">
                            <div class="col-12 col-md-4 mb-2">
                                <label class="small font-weight-bold text-muted">Fecha</label>
                                <input type="date" class="form-control form-control-sm" id="fecha_transaccion" name="fecha_transaccion" value="{{ $hoy }}" readonly>
                            </div>
                            <div class="col-12 col-md-6 mb-2">
                                <label class="small font-weight-bold text-muted">Bodega</label>
                                <select class="custom-select custom-select-sm select2bs4" id="bodega_id" name="bodega_id" required>
                                    @foreach($bodegas as $b)
                                        <option value="{{ $b->id }}">{{ $b->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-2 d-flex align-items-end justify-content-end mb-2">
                                <button type="button" class="btn btn-sm btn-primary btn-block d-md-none" onclick="agregarFila();">
                                    <i class="fas fa-plus mr-1"></i> Agregar Artículo
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-primary rounded-circle d-none d-md-inline elevation-2" onclick="agregarFila();" title="Agregar Artículo">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="table-responsive">
                            <table id="tblDetalle" class="table table-sm table-hover">
                                <thead class="bg-light">
                                    <tr class="text-center" style="font-size: 11px; text-transform: uppercase;">
                                        <th style="width: 50%;">Artículo</th>
                                        <th style="width: 15%;">U. Medida</th>
                                        <th style="width: 15%;">Cantidad</th>
                                        <th style="width: 15%;">Motivo</th>
                                        <th style="width: 5%;"></th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyDetalle">
                                    </tbody>
                            </table>
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
        // declaracion de variables
        //========================================================================
        nFila  = 1;
        nLinea = 0;
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
        // Agregar una nueva fila a la tabla
        //========================================================================

        function agregarFila(){
            html = '';
            html += '<tr>'
            html += '<input type="hidden" class="form-control" id="productos['+nLinea+'][id]" name="productos['+nLinea+'][id]" value="'+nLinea+'">'
            html += '<td style="width: 50%;">'
            html += '<select class="custom-select custom-select-sm select2 select2bs4" id="productos['+nLinea+'][articulo_id]" name="productos['+nLinea+'][articulo_id]" onchange="actualizarMedidas('+nLinea+');">'
            html += '<option value="">Seleccionar....</option>'
            productos_db.forEach(function(p, index) {
                html += `<option value="${p.id}">${p.descripcion}</option>`;
            });
            html += '</select>'
            html += '</td>'
            html += '<td style="width: 20%;">'
            html += '<select class="custom-select custom-select-sm select2 select2bs4" id="productos['+nLinea+'][unidad_medida_id]" name="productos['+nLinea+'][unidad_medida_id]">'
            html += '</select>'
            html += '</td>'
            html += '<td style="width: 15%;">'
            html += '<input type="number" class="form-control" placeholder="0" id="productos['+nLinea+'][cantidad]" name="productos['+nLinea+'][cantidad]" step="any" required style="text-align: right;">'
            html += '</td>'
            html += '<td style="width: 10%;">'
            html += '<select class="custom-select custom-select-sm select2 select2bs4" id="productos['+nLinea+'][signo]" name="productos['+nLinea+'][signo]">'
            // html += '<option value="">Seleccionar....</option>'
            html += '<option value="1">Entrada</option>'
            html += '<option value="-1">Salida</option>'
            html += '</select>'
            html += '</td>'
            html += '<td style="width: 5%;">'
            html += '<a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4 eliminar"><i class="fas fa-trash-alt"></i></a>'
            html += '</td>'
            html += '</tr>';
            $("#tblDetalle > tbody").append(html);
            $('.eliminar').on('click',eliminar);

            $(`#productos\\[${nLinea}\\]\\[articulo_id\\]`).select2({ theme: 'bootstrap4' });
            $(`#productos\\[${nLinea}\\]\\[unidad_medida_id\\]`).select2({ theme: 'bootstrap4' });
            $(`#productos\\[${nLinea}\\]\\[signo\\]`).select2({ theme: 'bootstrap4' });

            nFila += 1;
            nLinea += 1;
        }

        //===================================================================
        // Eliminar fila del detalle
        //===================================================================
        function eliminar(){
            var whichtr = $(this).closest("tr");
            whichtr.remove(); 
            return false;
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

        $('#FormaAjuste').on('submit', function(e) {
            e.preventDefault(); // Evita que la página se recargue
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire("Éxito", "Guardado correctamente", "success")
                        .then(() => window.location.href = "{{ route('lista_ajustes') }}");
                },
                error: function(xhr) {
                    // Si el controlador devuelve un error (catch)
                    let mensaje = xhr.responseJSON.message || "Error desconocido";
                    Swal.fire("Error", mensaje, "error");
                }
            });
        });
    </script>
@endsection