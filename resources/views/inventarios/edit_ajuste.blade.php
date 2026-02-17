@extends('adminlte::page')
@section('css')
    <style type="text/css">
        .btn-guardar{
            background-color: #A5C890 !important;
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
        .dataTables_wrapper .row {
            display: flex;
            align-items: center; /* Alinea verticalmente los elementos */
            justify-content: flex-start; /* Ajusta los elementos a la izquierda */
        }

        .dataTables_wrapper .row .col-auto {
            display: flex;
            justify-content: flex-start; /* Alinea los elementos dentro de las columnas */
        }

        .dataTables_wrapper .row .col {
            display: flex;
            justify-content: flex-start;
        }
        .input-group-text {
            height: 30px; /* Ajusta la altura */
            padding: 5px 10px; /* Ajusta el padding */
            font-size: 0.875rem; /* Puedes ajustar el tamaño de la fuente según sea necesario */
        }

        /*.custom-select-sm, .select2bs4 {
            height: 30px; /* Ajusta la altura según lo que necesites */
            padding: 5px; /* Ajusta el padding para que la altura se reduzca */
            font-size: 0.875rem; /* Ajusta el tamaño de la fuente para que todo el conjunto se vea más pequeño */
        }*/

        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(1.8125rem + 2px) !important; /* Altura de un form-control-sm */
        }
    </style>
@endsection
@section('title', 'Ajustes')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="container-fluid">
        <form role="form" id="FormaAjuste" method="POST" action="{{route('actualizar_ajuste')}}">
            @csrf
            <div class="row">
                <div class="col-12 col-lg-10 offset-lg-1">
                    <div class="card shadow-sm border-0">
                        <div class="card-header py-2" style="background-color: #E1E8ED;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 font-weight-bold text-secondary">Edición de Ajuste</h6>
                                <div class="d-flex">
                                    <button type="submit" id="btn_guardar_admision" class="btn btn-sm btn-outline-success rounded-circle mr-2 shadow-sm" title="Guardar">
                                        <i class="fas fa-save"></i>
                                    </button>
                                    <a href="#" class="btn btn-sm btn-outline-danger rounded-circle shadow-sm" title="Salir" onclick="confirma_salida();">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-3">
                            <input type="hidden" id="maestro_id" name="maestro_id" value="{{ $encabezado->id }}">
                            
                            <div class="row mb-3">
                                <div class="col-12 col-md-4 mb-2">
                                    <label class="small font-weight-bold text-muted">Fecha</label>
                                    <input type="date" class="form-control form-control-sm" id="fecha_transaccion" name="fecha_transaccion" value="{{ $encabezado->fecha_emision }}" readonly>
                                </div>
                                <div class="col-12 col-md-6 mb-2">
                                    <label class="small font-weight-bold text-muted">Bodega</label>
                                    <select class="custom-select custom-select-sm select2 select2bs4" id="bodega_id" name="bodega_id" required>
                                        <option value="">Seleccionar...</option>
                                        @foreach($bodegas as $b)
                                            <option value="{{ $b->id }}" {{ $b->id == $encabezado->bodega_origen_id ? 'selected' : '' }}>
                                                {{ $b->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-2 d-flex align-items-end mb-2">
                                    <button type="button" class="btn btn-sm btn-primary btn-block d-md-none shadow-sm" onclick="agregarFila();">
                                        <i class="fas fa-plus-circle mr-1"></i> Agregar Ítem
                                    </button>
                                    <button type="button" class="btn btn-outline-primary rounded-circle d-none d-md-inline elevation-2 ml-auto" onclick="agregarFila();" title="Agregar Artículo">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <hr class="my-3">

                            <div class="table-responsive" style="overflow-x: hidden;">
                                <table id="tblDetalle" class="table table-sm table-hover border-0">
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
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        //========================================================================
        // declaracion de variables
        //========================================================================
        nFila  = 1;
        nLinea = 0;

        const productos_db = @json($productos);
        // console.log(productos_db);

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

            // $('#bodega_id').select2({
            //     theme: 'bootstrap4',
            //     width: '100%' // Esto evita que el span de Select2 se vea pequeño o roto
            // });
        });

        //========================================================================
        // al cargar la pagina trae los productos
        //========================================================================
        document.addEventListener("DOMContentLoaded",function(event){
            let maestroId = $('#maestro_id').val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('trae_detalle_ajuste') }}",
                method: "POST",
                data: {"_token": "{{ csrf_token() }}",
                   ajuste_id: maestroId},
                success: function(response){
                    response.forEach(function(p, index) {
                        agregarFila(p);
                        // $(`#productos\\[${index}\\]\\[producto_id\\]`).val(response[index]['producto_id']);
                        $(`#productos\\[${index}\\]\\[producto_id\\]`).val(response[index]['producto_id']).trigger('change');
                        $(`#productos\\[${index}\\]\\[unidad_medida_id\\]`).val(response[index]['unidad_medida_id']);
                        $(`#productos\\[${index}\\]\\[cantidad\\]`).val(response[index]['cantidad']);
                    });
                },
                error: function(error){
                    console.log(error);
                }
            });
        });

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

        function agregarFila(datos = null){
            html = '';
            html += `<tr id="fila_${nLinea}">`;
            html += `<input type="hidden" id="productos[${nLinea}][id]" name="productos[${nLinea}][id]" value="${nLinea}">`;
            html += '<td style="width: 30%;">'
            html += `<select class="custom-select custom-select-sm select2 select2bs4" id="productos[${nLinea}][producto_id]" name="productos[${nLinea}][producto_id]" onchange="actualizarMedidas(${nLinea});" required>`;
            html += '<option value="">Seleccionar....</option>'
            productos_db.forEach(function(p) {
                // let selected = (datos && datos.producto_id == p.id) ? 'selected' : '';
                // html += `<option value="${p.id}" ${selected}>${p.descripcion}</option>`;
                html += `<option value="${p.id}">${p.descripcion}</option>`;
            });
            html += '</select>'
            html += '</td>'
            // html += '<td style="width: 20%;">'
            // html += '<select class="custom-select custom-select-sm select2 select2bs4" id="productos['+nLinea+'][articulo_caracteristica_id]" name="productos['+nLinea+'][articulo_caracteristica_id]">'
            // html += '<option value="">Seleccionar....</option>'
            // html += '</select>'
            // html += '</td>'
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
            nFila += 1;
            nLinea += 1;
            $('.select2').select2()
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        }

        function eliminar(){
            var whichtr = $(this).closest("tr");
            whichtr.remove(); 
            return false;
        }

        //========================================================================
        // actualizar unidad de medida en base a producto seleccionado
        //========================================================================
        function actualizarMedidas(id){
            console.log('enttre con '+id)
            var producto_id = document.getElementById("productos["+id+"][producto_id]").value;
            var select      = document.getElementById("productos["+id+"][unidad_medida_id]"); 
            var caracteristica = document.getElementById("productos["+id+"][articulo_caracteristica_id]"); 
            
            // $.ajax({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     },
            //     url: "{{ route('trae_caracteristicas_x_producto') }}",
            //     method: "POST",
            //     data: {producto_id: producto_id},
            //     success: function(response) {
            //         caracteristica.innerHTML = '';
            //         var opt = response.length;
            //         var el1 = document.createElement("option");
            //         el1.textContent = 'Seleccionar...';
            //         el1.value = null;
            //         caracteristica.appendChild(el1);
            //         for (var i = 0; i < response.length; i++) {
            //             var opt = response.length;
            //             var el1 = document.createElement("option");
            //             el1.textContent = response[i]['descripcion'];
            //             el1.value = response[i]['id'];
            //             caracteristica.appendChild(el1);
            //         }
            //     },
            //     error: function() {
            //         // Este bloque se ejecuta si hay un error con la solicitud
            //         console.error('Error en la solicitud AJAX:');
            //     }
            // });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('trae_medidas_x_producto') }}",
                method: "POST",
                data: {producto_id: producto_id},
                success: function(response){
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
                    window.location.href = "{{ route('productos') }}";
                } 
            });
        }
    </script>
@endsection