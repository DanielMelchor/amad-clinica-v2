@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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
@section('title', 'Compras')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <form class="form-horizontal" id="FormaCompra" action="#">
                @csrf
                <div class="card">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="row">
                            <div class="col-md-9">
                                <h6>Nueva Compra</h6>
                            </div>
                            <div class="col-md-3" style="text-align: right;">
                                <!-- <a href="{{ route('crear_compra') }}" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></a> -->
                                <button type="submit" id="btn_guardar_admision" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
                                <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir" onclick="confirma_salida();"><i class="fas fa-sign-out-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card rounded-top">
                                    <div class="card-header" style="background-color: #E1E8ED;">
                                        <h6>Datos de Proveedor</h6>
                                    </div>
                                    <div class="card-body">
                                        <input type="hidden" id="proveedor_id" name="proveedor_id">
                                        <div class="row">
                                            <div class="input-group col-md-10 offset-md-1 mb-1">
                                                <span class="input-group-text" id="basic-addon1">N.I.T.</span>
                                                <input type="text" class="form-control form-control-sm" placeholder="N.I.T." aria-label="nit" aria-describedby="find_proveedor" id="nit" name="nit" onchange="trae_proveedor();" style="text-transform: uppercase;" autofocus required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-xs btn-outline-secondary" type="button" id="find_proveedor" data-toggle="modal" data-target="#proveedorModal"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group col-md-10 offset-md-1 mb-1">
                                                <span class="input-group-text" id="basic-addon1">Nombre</span>
                                                <input type="text" class="form-control form-control-sm" id="proveedor_nombre" name="proveedor_nombre"style="text-transform: uppercase;" disabled>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group col-md-10 offset-md-1 mb-1">
                                                <span class="input-group-text" id="basic-addon1">Dias Crédito</span>
                                                <input type="text" class="form-control form-control-sm" id="dias_credito" name="dias_credito" style="text-align: right;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card rounded-top">
                                    <div class="card-header" style="background-color: #E1E8ED;">
                                        <h6>Datos de Comprobante</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 input-group input-group-sm mb-1">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">Documento</span>
                                                </div>
                                                <select class="custom-select custom-select-sm select2 select2bs4" id="documento_id" name="documento_id" required>
                                                    <option value="" selected>Seleccionar...</option>
                                                    @foreach($tipo_documentos as $td)
                                                        <option value="{{ $td->id }}">{{ $td->descripcion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group mb-1">
                                                    <span class="input-group-text" id="basic-addon1">Serie</span>
                                                    <input type="text" class="form-control form-control-sm" placeholder="Serie" aria-label="serie" aria-describedby="serie" id="serie" name="serie" style="text-transform: uppercase;" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group mb-1">
                                                    <span class="input-group-text" id="basic-addon1">Correlativo</span>
                                                    <input type="number" class="form-control form-control-sm" placeholder="documento" aria-label="documento" aria-describedby="documento" id="numero_documento" name="numero_documento" required style="text-align: right;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group mb-1">
                                                    <span class="input-group-text" id="basic-addon1">Fch. Emisión</span>
                                                    <input type="date" class="form-control form-control-sm" id="fecha_emision" name="fecha_emision" value="{{ $hoy }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group mb-1">
                                                    <span class="input-group-text" id="basic-addon1">Fch. Vencimiento</span>
                                                    <input type="date" class="form-control form-control-sm" id="fecha_vencimiento" name="fecha_vencimiento" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 input-group mb-1">
                                                <span class="input-group-text" id="basic-addon1">Total</span>
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" step="any" id="total" name="total" min="0" required style="text-align: right;">
                                            </div>
                                            <div class="col-md-6 input-group mb-1">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">Bodega</span>
                                                </div>
                                                <select class="custom-select custom-select-sm select2 select2bs4" id="bodega_id" name="bodega_id" required>
                                                    <option value="" selected>Seleccionar...</option>
                                                    @foreach($bodegas as $b)
                                                        <option value="{{ $b->id }}">{{ $b->descripcion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- <div class="col-md-1" style="text-align: right;">
                                                <a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="agregarFila(); return false;" title="Agregar Artículo"><i class="fas fa-plus-circle"></i></a>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 offset-md-11" style="text-align: right;">
                                <a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="agregarFila(); return false;" title="Agregar Artículo"><i class="fas fa-plus-circle"></i></a>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-md-12">
                                <table id="tblDetalle" class="table table-sm table-striped table-hovver text-center">
                                    <thead>
                                        <tr style="font-size: 12px;">
                                            <th style="width: 30%;">Artículo</th>
                                            <th style="width: 20%;">Caracteristica</th>
                                            <th style="width: 10%;">U. Medida</th>
                                            <th style="width: 10%;">Cantidad</th>
                                            <th style="width: 15%;">Prc. Unit.</th>
                                            <th style="width: 15%;">Prc. Total</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
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
                </div>
            </form>
        </div>
    </div>
    <!-- Modal proveedores -->
    <div class="modal fade" id="proveedorModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                                <h6>Lista de Proveedores</h6>
                            </div>
                            <div class="col-md-2" style="text-align: right;">
                                <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Salir"><i class="fas fa-sign-out-alt"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tblproveedores" class="table table-sm table-striped text-center" style="width: 100%;">
                                        <thead>
                                            <tr style="font-size: 12px;">
                                                <th>N.I.T.</th>
                                                <th>Nombre</th>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($proveedores as $p)
                                                <tr style="font-size: 12px;">
                                                    <td>{{ $p->nit }}</td>
                                                    <td>{{ $p->nombre_comercial }}</td>
                                                    <td> <button class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Seleccionar Proveedor" onclick="asignarNit('{{$p->nit}}','{{$p->id}}','{{$p->nombre_comercial}}','{{$p->dias_credito}}');"><i class="fas fa-check-circle"></i></button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /modal proveedores -->
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
                        // if(!localStorage.productos_db){
                        //     localStorage.productos_db = JSON.stringify([linea]);
                        // }
                        // else{
                        //     var productos_db = JSON.parse(localStorage.productos_db);
                        //     productos_db.push(linea);
                        //     localStorage.productos_db = JSON.stringify(productos_db);
                        // }
                    }
                    // console.log(productos_db);
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
                        swal({
                            title: 'Error',
                            text: 'Proveedor no encontrado, favor utilice boton de busqueda',
                            type: 'error'
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
        // asignar datos de proveedor
        //========================================================================
        function asignarNit(nit, proveedor_id, nombre_comercial, dias_credito){
            document.getElementById('nit').value = nit;
            document.getElementById('proveedor_id').value = proveedor_id;
            document.getElementById('proveedor_nombre').value = nombre_comercial;
            document.getElementById('dias_credito').value = dias_credito;
            $('#proveedorModal').hide();
            $('.modal-backdrop').hide();
            //alert(nit+' '+proveedor_id+' '+proveedor_nombre+' '+proveedor_telefono)
        }

        //========================================================================
        // Agregar una nueva fila a la tabla
        //========================================================================

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
            html += '<td style="width: 10%;">'
            html += '<select class="custom-select custom-select-sm select2 select2bs4" id="productos['+nLinea+'][unidad_medida_id]" name="productos['+nLinea+'][unidad_medida_id]">'
            html += '</select>'
            html += '</td>'
            html += '<td style="width: 10%;">'
            html += '<input type="number" class="form-control form-control-sm" placeholder="0" id="productos['+nLinea+'][cantidad]" name="productos['+nLinea+'][cantidad]" step="any" onchange="total_linea('+nLinea+')" required style="text-align: right;">'
            html += '</td>'
            html += '<td style="width: 15%;">'
            html += '<input type="number" class="form-control form-control-sm" placeholder="0" id="productos['+nLinea+'][precio_unitario]" name="productos['+nLinea+'][precio_unitario]" onchange="total_linea('+nLinea+')" step="any" required style="text-align: right;">'
            html += '</td>'
            html += '<td style="width: 15%;">'
            html += '<input type="number" class="form-control form-control-sm" placeholder="0" id="productos['+nLinea+'][precio_total]" name="productos['+nLinea+'][precio_total]" step="any" required style="text-align: right;" readonly>'
            html += '</td>'
            html += '<td>'
            html += '<a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Eliminar Artículo" onclick="eliminarFila(this)"><i class="fa fa-trash-alt"></i></a>'
            html += '</td>'
            html += '</tr>';
            //document.getElementById("tblDetalle").insertRow(-1).innerHTML = html;
            $("#tblDetalle > tbody").append(html);
            nFila += 1;
            nLinea += 1;
            $('.select2').select2()
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        }

        function total_linea(id){
            var cantidad        = document.getElementById('productos['+id+'][cantidad]').value;
            var precio_unitario = document.getElementById('productos['+id+'][precio_unitario]').value;
            document.getElementById('productos['+id+'][precio_total]').value = (cantidad * precio_unitario).toFixed(2);
            total_tabla();
        }

        function total_tabla(){
            var pie = '';
            var total = 0;
            /*$('#tblDetalle tbody').find('tr').each(function (i, el) {
                total += parseFloat($(this).find('td').eq(0).text());
                console.log('Total '+total);
            });*/
            var filas=document.querySelectorAll("#tblDetalle tbody tr");
            filas.forEach(function(e) {
                var columnas = e.querySelectorAll("td");
                total += parseFloat($(columnas[5]).find('input').val());
            });
            total = total.toFixed(2);
            pie += '<tr>'
            pie += '<td style="width: 30%;"></td>'
            pie += '<td style="width: 15%;"></td>'
            pie += '<td style="width: 15%;"></td>'
            pie += '<td style="width: 20%;"><h6><b>Total</b></h6></td>'
            pie += '<td style="width: 20%;"><h6><b>'+total+'</b></h6></td>'
            pie += '</tr>'
            $("#tblDetalle tfoot tr").remove();
            $('#tblDetalle tfoot').append(pie);
        }

        //========================================================================
        // actualizar unidad de medida en base a producto seleccionado
        //========================================================================
        function actualizarMedidas(id){
            var producto_id    = document.getElementById("productos["+id+"][articulo_id]").value;
            var select         = document.getElementById("productos["+id+"][unidad_medida_id]"); 
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
        // eliminar fila especifica de la tabla
        //========================================================================
        function eliminarFila(row){
            var d = row.parentNode.parentNode.rowIndex; 
            document.getElementById('tblDetalle').deleteRow(d);
            total_tabla();
        }

        //========================================================================
        // Guardar Compra
        //========================================================================
        $(function(){
            $("#FormaCompra").submit(function(){
                var total_documento = document.getElementById('total').value;
                var total           = 0;
                var filas=document.querySelectorAll("#tblDetalle tbody tr");
                filas.forEach(function(e) {
                    var columnas = e.querySelectorAll("td");
                    total += parseFloat($(columnas[5]).find('input').val());
                });
                if (total_documento == total) {
                    event.preventDefault(); // Evita el envío normal del formulario
                    var formData = new FormData(this); // Serializa los datos del formulario
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    formData.append('_token', csrfToken);
                    $.ajax({
                        url: "{{ route('grabar_compra') }}",
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
                        type: 'error',
                        showConfirmButton: true,     // Asegura que se muestre el botón "OK"
                        confirmButtonText: 'Aceptar'
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
                        window.location.href = "{{ route('lista_compras') }}";
                    } 
                }
            );
        }
    </script>
@endsection