@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
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
    </style>
@endsection
@section('title', 'Ajustes')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <form class="form-horizontal" id="FormaAjuste" action="#">
                @csrf
                <div class="card">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="row">
                            <div class="col-md-9">
                                <h6>Edición de Ajuste</h6>
                            </div>
                            <div class="col-md-3" style="text-align: right;">
                                <button type="submit" id="btn_guardar_admision" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
                                <a href="" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir" onclick="confirma_salida();"><i class="fas fa-sign-out-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" id="compra_id" name="compra_id" value="{{ $encabezado->id }}">
                        <div class="row">
                            <div class="col-md-3 offset-md-1 input-group input-group-sm mb-1">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">Fecha</label>
                                </div>
                                <input type="date" class="form-control form-control-sm" placeholder="DD/MM/AAAA" id="fecha_transaccion" name="fecha_transaccion" value="{{ $encabezado->fecha_emision }}" readonly>
                            </div>
                            <div class="col-md-5 input-group input-group-sm mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">Bodega</span>
                                </div>
                                <select class="custom-select custom-select-sm select2 select2bs4" id="bodega_id" name="bodega_id" required>
                                    <option value="" selected>Seleccionar...</option>
                                    @foreach($bodegas as $b)
                                        <option value="{{ $b->id }}" @if($b->id == $encabezado->bodega_origen_id) selected @endif>{{ $b->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 offset-md-1" style="text-align: right;">
                                <a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="agregarFila(); return false;" title="Agregar Artículo"><i class="fas fa-plus-circle"></i></a>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <div class="col-md-12">
                                <table id="tblDetalle" class="table table-sm table-striped table-hovver text-center">
                                    <thead>
                                        <tr style="font-size: 12px;">
                                            <th style="width: 30%;">Artículo</th>
                                            <th style="width: 20%;">Caracteristica</th>
                                            <th style="width: 20%;">U. Medida</th>
                                            <th style="width: 15%;">Cantidad</th>
                                            <th style="width: 10%;">Motivo</th>
                                            <th style="width: 5%;">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/select2/js/select2.full.min.js')}}"></script>
    <script type="text/javascript">
        //========================================================================
        // declaracion de variables
        //========================================================================
        nFila  = 1;
        nLinea = 0;
        var productos_db = [];

        //========================================================================
        // inicializar librerias
        //========================================================================
        $(function () {
            $('.select2').select2()
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        });

        //========================================================================
        // al cargar la pagina trae los productos
        //========================================================================
        document.addEventListener("DOMContentLoaded",function(event){
            //FUNCION
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('trae_productos') }}",
                method: "POST",
                success: function(response){
                    for (var i = 0; i < response.length; i++) {
                        var linea = {
                            linea                     : nLinea,
                            articulo_id               : response[i]['id'],
                            articulo_descripcion      : response[i]['descripcion'],
                            medida_minima_id          : response[i]['medida_id'],
                            medida_minima_descripcion : response[i]['medida_descripcion']
                        }
                        productos_db.push(linea);
                    }
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

        function agregarFila(){
            // var productos_db = JSON.parse(localStorage.productos_db);
            productos_db.sort(compare);
            html = '';
            html += '<tr>'
            html += '<input type="hidden" class="form-control" id="productos['+nLinea+'][id]" name="productos['+nLinea+'][id]" value="'+nLinea+'">'
            html += '<td style="width: 30%;">'
            html += '<select class="custom-select custom-select-sm select2 select2bs4" id="productos['+nLinea+'][articulo_id]" name="productos['+nLinea+'][articulo_id]" onchange="actualizarMedidas('+nLinea+');">'
            html += '<option value="">Seleccionar....</option>'
            for (var i = 0; i < productos_db.length; i++) {
                html += '<option value="'+productos_db[i]['articulo_id']+'">'+productos_db[i]['articulo_descripcion']+'</option>'
            }
            html += '</select>'
            html += '</td>'
            html += '<td style="width: 20%;">'
            html += '<select class="custom-select custom-select-sm select2 select2bs4" id="productos['+nLinea+'][articulo_caracteristica_id]" name="productos['+nLinea+'][articulo_caracteristica_id]">'
            html += '<option value="">Seleccionar....</option>'
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
            
            var producto_id = document.getElementById("productos["+id+"][articulo_id]").value;
            var select      = document.getElementById("productos["+id+"][unidad_medida_id]"); 
            var caracteristica = document.getElementById("productos["+id+"][articulo_caracteristica_id]"); 
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('trae_caracteristicas_x_producto') }}",
                method: "POST",
                data: {producto_id: producto_id},
                success: function(response) {
                    caracteristica.innerHTML = '';
                    var opt = response.length;
                    var el1 = document.createElement("option");
                    el1.textContent = 'Seleccionar...';
                    el1.value = null;
                    caracteristica.appendChild(el1);
                    for (var i = 0; i < response.length; i++) {
                        var opt = response.length;
                        var el1 = document.createElement("option");
                        el1.textContent = response[i]['descripcion'];
                        el1.value = response[i]['id'];
                        caracteristica.appendChild(el1);
                    }
                },
                error: function() {
                    // Este bloque se ejecuta si hay un error con la solicitud
                    console.error('Error en la solicitud AJAX:');
                }
            });

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

        //========================================================================
        // Guardar Compra
        //========================================================================
        $(function(){
            $("#FormaCompra").submit(function(){
                var total_documento = parseFloat(document.getElementById('total').value);
                total_documento = total_documento.toFixed(2);
                var total           = 0;
                var filas=document.querySelectorAll("#tblDetalle tbody tr");
                filas.forEach(function(e) {
                    var columnas = e.querySelectorAll("td");
                    if (!isNaN(parseFloat($(columnas[5]).find('input').val())) ) {
                        total += parseFloat($(columnas[5]).find('input').val());
                    }
                });
                total = total.toFixed(2);
                if (total_documento == total) {
                    event.preventDefault(); // Evita el envío normal del formulario
                    var formData = new FormData(this); // Serializa los datos del formulario
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    formData.append('_token', csrfToken);
                    // console.log(formData);
                    // alert('revisar');
                    $.ajax({
                        url: "{{ route('actualizar_ajuste') }}",
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        contentType: false,  // Impide que jQuery configure el tipo de contenido
                        processData: false,
                        success: function(response){
                            swal({
                                title: 'Trabajo Finalizado',
                                text: response.message,
                                type: 'success',
                                },
                                function(){
                                    return window.location.href = "{{route('lista_compras')}}";
                                }
                            );
                        },
                        error: function(error){
                            console.log(error);
                        }
                    });
                }else{
                    swal({
                        title: 'Error',
                        text: 'Para continuar el total de productos debe coincidir con el valor del documento de compra',
                        type: 'error'
                    });
                }
            })
        });

        //===================================================================
        // Confirmar salida de pantalla
        //===================================================================
        function confirma_salida(){
            event.preventDefault(); // Evita el envío normal del formulario
            swal({
                title: 'Confirmación',
                Swal.fire({

                title: 'Confirmación',

                text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?",

text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?",
                icon: 'warning',

                showCancelButton: true,

                confirmButtonClass: 'btn-success',

                cancelButtonClass: 'btn-danger',

                confirmButtonText: 'Si',

                cancelButtonText: 'No',

                closeOnConfirm: false,

                allowEscapeKey: true

                },

                function(isConfirm) {

                    if (isConfirm) { 

                        if (origen == 'P') {

                            window.location.href = "{{ route('pacientes') }}";

                        }

                        if (origen == 'A') {

                            window.location.href = "{{ route('nueva_agenda') }}";

                        }

                        // history.back();

                        

                    } 

                }

            );
                showCancelButton: true,
                confirmButtonClass: 'btn-success',
                cancelButtonClass: 'btn-danger',
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                closeOnConfirm: false,
                allowEscapeKey: true
                },
                function(isConfirm) {
                    if (isConfirm) { 
                        window.location.href = "{{ route('lista_ajustes') }}";
                    } 
                }
            );
        }
    </script>
@endsection