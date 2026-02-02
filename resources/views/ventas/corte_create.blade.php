@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
        .nav-pills .nav-link.active,
        .show>.nav-pills .nav-link{
            background: #7FB3D5 !important;
            color: #000000 !important;
        }
        .numero{
            text-align: right;
        }
    </style>
@endsection
@section('title', 'Ventas')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="container-fluid">
        <form role="form" method="POST" action="{{ route('grabar_corte') }}">
            @csrf
            <div class="card">
                <div class="card-header" style="background-color: #E1E8ED;">
                    <div class="row">
                        <div class="col-9">
                            <h6>Nuevo Corte de Caja</h6>
                        </div>
                        <div class="col-3" style="text-align: right;">
                            <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Grabar"><i class="fas fa-save" title="Guardar"></i></button>
                            <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <input type="hidden" id="corte_id" name="corte_id" value="">
                    <div class="row">
                        <div class="col-9">
                            <div class="row">
                                <div class="col-5 mb-1">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" id="basic-addon1">Fecha</label>
                                        </div>
                                        <input type="date" class="form-control" id="fecha" name="fecha" value="{{ $hoy }}" style="text-align: center;">
                                    </div>
                                </div>
                                <div class="col-5 mb-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="caja_id">Caja</label>
                                        </div>
                                        <select class="custom-select custom-select-sm select2bs4" id="caja_id"  name="caja_id">
                                            <option value="0"> Todas </option>
                                            @foreach($cajas as $c)
                                                <option value="{{ $c->id }}" @if($caja->id == $c->id) selected @endif> {{ $c->nombre_maquina }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2 mb-1">
                                    <a href="#" class="btn btn-xs btn-outline-secondary rounded-circle elevation-4" onclick="fn_trae_datos(); return false;" title="Filtrar"><i class="fas fa-search"></i></a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-pills nav-justified p-2">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#detalle_documento" data-toggle="tab" id="tab-detalle">Documentos</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#detalle_pago" data-toggle="tab" id="tab-pago">Medio de Pago</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="detalle_documento">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <form class="form-horizontal">
                                                                <div class="row text-center">
                                                                    <div class="col-md-12">
                                                                        <div class="table-responsive">
                                                                        <table id="tblDocumentos" class="table table-sm table-striped table-hover" style="font-size: 12px;">
                                                                            <thead>
                                                                                <tr class="text-center">
                                                                                    <th>Tipo</th>
                                                                                    <th>Documento</th>
                                                                                    <th>Fecha</th>
                                                                                    <th>N.I.T.</th>
                                                                                    <th>Nombre</th>
                                                                                    <th>Total</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr></tr>
                                                                            </tbody>
                                                                            <tfoot></tfoot>
                                                                        </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="detalle_pago">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <form class="form-horizontal">
                                                                <div class="row text-center">
                                                                    <div class="col-md-12">
                                                                        <div class="table-responsive">
                                                                        <table id="tblPagos" class="table table-sm table-striped table-hover" style="font-size: 12px;">
                                                                            <thead>
                                                                                <tr class="text-center">
                                                                                    <th>Documento</th><th>Fecha</th><th>N.I.T.</th><th>Nombre</th><th>Total</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr></tr>
                                                                            </tbody>
                                                                            <tfoot></tfoot>
                                                                        </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-default">
                                        <div class="card-header text-center" style="background-color: #E1E8ED;">
                                            <h6>Documentos</h6>
                                        </div>
                                        <div class="card-body">
                                            <table id="tblResumenDocumentos" class="table table-sm table-hover table-striped" style="font-size: 12px;">
                                                <tbody>
                                                    @foreach($documentos as $documento)
                                                        <tr>
                                                            <td>{{ $documento->descripcion }}</td>
                                                            <td style="text-align: right;">0.00</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot style="background-color: aliceblue;">
                                                    <tr>
                                                        <th><h4>Total</h4></th>
                                                        <td style="text-align: right;">0.00</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card card-default">
                                        <div class="card-header text-center" style="background-color: #E1E8ED;">
                                            <h6>Formas Pago</h6>
                                        </div>
                                        <div class="card-body">
                                            <table id="tblResumenPago" class="table table-sm table-hover table-striped" style="font-size: 12px;">
                                                <tbody>
                                                    @foreach($formas_pago as $forma_pago)
                                                        <tr>
                                                            <td>{{ $forma_pago->descripcion }}</td>
                                                            <td style="text-align: right;">0.00</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot style="background-color: aliceblue;">
                                                    <tr>
                                                        <th><h4>Total</h4></th>
                                                        <td style="text-align: right;">0.00</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
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
    @if(Session::get('type') == 'success')
        @if(Session::has('message'))
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: "¡Trabajo Finalizado!",
                        text: "{!! Session::get('message') !!}",
                        icon: "success", // Cambiado de 'type' a 'icon'
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                        customClass: {
                            confirmButton: 'btn btn-success'
                        },
                        buttonsStyling: false
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
                        title: "¡Trabajo Finalizado!",
                        text: "{!! Session::get('message') !!}",
                        icon: "error", // Cambiado de 'type' a 'icon'
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });
                }, 1000);
            </script>
        @endif
    @endif
    <script type="text/javascript">
        // $(function () {
        //     $('#tblDocumentos').DataTable({
        //         "scrollX": true,
        //         "paging": true,
        //         "lengthChange": true,
        //         "searching": true,
        //         "ordering": true,
        //         "info": true,
        //         "autoWidth": false,
        //         "pageLength": 25,  // Esto establece que por defecto se muestren 25 registros
        //         "lengthMenu": [ [10, 25, 50, 100], [10, 25, 50, 100] ],  // Esto establece las opciones en el dropdown
        //         "order": [[1, 'desc'], [2, 'asc']],
        //         "language": {
        //             "sProcessing": "Procesando...",
        //             "sLengthMenu": "Mostrar _MENU_ registros",
        //             "sZeroRecords": "No se encontraron resultados",
        //             "sEmptyTable": "Ningún dato disponible en esta tabla =(",
        //             "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        //             "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        //             "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        //             "sSearch": "Buscar:",
        //             "oPaginate": {
        //                 "sFirst": "Primero",
        //                 "sLast": "Último",
        //                 "sNext": "Siguiente",
        //                 "sPrevious": "Anterior"
        //             }
        //         },
        //         "dom": '<"row"<"col-sm-4"l><"col-sm-4 text-center"B><"col-sm-4"f>>rtip', // Ajuste para disposición
        //         "buttons": [
        //             {
        //                 extend: 'excelHtml5',
        //                 text: 'Excel',
        //                 className: 'btn btn-md btn-default'
        //             }
        //         ]
        //     });
        // });

        const formatter = new Intl.NumberFormat('es-GT', {
          style: 'currency',
          currency: 'GTQ',
          minimumFractionDigits: 2
        });

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
        // Trae datos de facturacion
        //========================================================================

        function fn_trae_datos(){
            var caja_id = document.getElementById('caja_id').value;
            var fecha   = document.getElementById('fecha').value;
            var corte_id = document.getElementById('corte_id').value;
            var info = {};

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('trae_resumen_documentos')}}",
                method: "POST",
                data: { caja_id  : caja_id, 
                        fecha    : fecha,
                        corte_id : corte_id },
                dataSrc: "",
                success: function(response){
                    var signo = 1;
                    let html = '';
                    let htmlf = '';
                    let total = 0;
                    for(var i = 0; i < response[0].length; i++){
                        signo = response[0][i]['signo'];
                        html += '<tr>';
                        html += '<td>';
                        html += response[0][i]['descripcion'];
                        html += '</td>';
                        html += '<td style="text-align: right;">';
                        html += formatter.format(response[0][i]['totalsinsigno']);
                        html += '<td>';
                        html += '</tr>';
                        if (response[0][i]['total'] != null) {
                            total += parseFloat(response[0][i]['total']);
                        }
                    }
                    htmlf += '<tr>'
                    htmlf += '<th style="text-align: left;"><h4>Total</h4></th>'
                    htmlf += '<td style="text-align: right;">'
                    htmlf += '<strong>'+'<h4>'+formatter.format(Math.abs(total))+'</h4>'+'</strong>'
                    htmlf += '</td>'
                    htmlf += '</tr>'

                    $("#tblResumenDocumentos tfoot tr").remove();
                    $("#tblResumenDocumentos tbody tr").remove();
                    $('#tblResumenDocumentos tbody').append(html);
                    $('#tblResumenDocumentos tfoot').append(htmlf);

                    html  = '';
                    htmlf = '';
                    total = 0;
                    for(var i = 0; i < response[1].length; i++){
                        html += '<tr>';
                        html += '<th>';
                        html += response[1][i]['descripcion'];
                        html += '</th>';
                        html += '<td style="text-align: right;">';
                        html += formatter.format(signo * response[1][i]['total']);
                        html += '<td>';
                        html += '</tr>';
                        if (response[1][i]['total'] != null && response[1][i]['id'] != 0) {
                            total += parseFloat(signo * response[1][i]['total']);
                        }
                    }
                    htmlf += '<tr>'
                    htmlf += '<th style="text-align: left;"><h4>Total</h4></th>'
                    htmlf += '<td style="text-align: right;">'
                    htmlf += '<strong>'+'<h4>'+formatter.format(total)+'</h4>'+'</strong>'
                    htmlf += '</td>'
                    htmlf += '</tr>'
                    $("#tblResumenPago tfoot tr").remove();
                    $("#tblResumenPago tbody tr").remove();
                    $('#tblResumenPago tbody').append(html);
                    $('#tblResumenPago tfoot').append(htmlf);

                    html  = '';
                    htmlf = '';
                    total = 0;
                    for (var i = 0; i < response[2].length; i++) {
                        html += '<tr>'
                        html += '<td>'
                        html += response[2][i]['tipodocumento_descripcion']
                        html += '</td>'
                        html += '<td>'
                        html += response[2][i]['serie']+'-'+response[2][i]['correlativo']
                        html += '</td>'
                        html += '<td>'
                        html += response[2][i]['fecha_emision']
                        html += '</td>'
                        html += '<td>'
                        html += response[2][i]['nit']
                        html += '</td>'
                        html += '<td>'
                        html += response[2][i]['nombre']
                        html += '</td>'
                        html += '<td style="text-align: right;">'
                        html += formatter.format(signo * response[2][i]['totalsinsigno']);
                        html += '</td>'
                        html += '</tr>'
                        if (response[2][i]['total'] != null) {
                            total += parseFloat(signo * response[2][i]['total']);
                        }
                    }
                    htmlf += '<tr>'
                    htmlf += '<td></td>'
                    htmlf += '<td></td>'
                    htmlf += '<td></td>'
                    htmlf += '<td></td>'
                    htmlf += '<td style="text-align: right;"><h4>Total</h4></td>'
                    htmlf += '<td style="text-align: right;">'
                    htmlf += '<strong>'+'<h4>'+formatter.format(Math.abs(total))+'</h4>'+'</strong>'
                    htmlf += '</td>'
                    htmlf += '</tr>'
                    $("#tblDocumentos tfoot tr").remove();
                    $("#tblDocumentos tbody tr").remove();
                    $('#tblDocumentos tbody').append(html);
                    $('#tblDocumentos tfoot').append(htmlf);

                    html  = '';
                    htmlf = '';
                    total = 0;
                    for (var i = 0; i < response[3].length; i++) {
                        html += '<tr>';
                        html += '<td>';
                        html += response[3][i]['recibo_serie']+'-'+response[3][i]['recibo_correlativo'];
                        html += '</td>';
                        html += '<td>';
                        html += response[3][i]['fecha_emision'];
                        html += '</td>';
                        html += '<td>';
                        html += response[3][i]['nit'];
                        html += '</td>';
                        html += '<td>';
                        html += response[3][i]['nombre'];
                        html += '</td>';
                        html += '<td style="text-align: right;">';
                        html += formatter.format(response[3][i]['monto_aplicado']);
                        html += '</td>';
                        html += '</tr>';
                        if (response[3][i]['monto_aplicado'] != null) {
                            total += parseFloat(response[3][i]['monto_aplicado']);
                        }
                    }
                    htmlf += '<tr>'
                    htmlf += '<td></td>'
                    htmlf += '<td></td>'
                    htmlf += '<td></td>'
                    htmlf += '<th style="text-align: right;"><h4>Total</h4></th>'
                    htmlf += '<td style="text-align: right;">'
                    htmlf += '<strong>'+'<h4>'+formatter.format(total)+'</h4>'+'</strong>'
                    htmlf += '</td>'
                    htmlf += '</tr>'
                    $("#tblPagos tfoot tr").remove();
                    $("#tblPagos tbody tr").remove();
                    $('#tblPagos tbody').append(html);
                    $('#tblPagos tfoot').append(htmlf);

                },
                error: function(error){
                    console.log(error);
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
                        window.location.href = "{{ route('listado_cortes') }}";
                    }
                });
        }
    </script>
@endsection