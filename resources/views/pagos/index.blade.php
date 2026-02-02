@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
        .numero{
            text-align: right;
        }

    </style>
@endsection
@section('title', 'Facturas')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header" style="background-color: #E1E8ED;">
                <div class="bg-default clearfix">
                    <div class="row">
                        <div class="col-lg-10 col-sm-10">
                            <h6>Pago de Documentos</h6>
                        </div>
                        <div class="col-lg-2 col-sm-2" style="text-align: right;">
                            <a href="{{ route('nuevo_recibo') }}" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Nuevo Registro de Pago"><i class="fas fa-plus-circle"></i></a>
                            <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <table id="tblprincipal" class="table table-sm table-striped table-hover" style="font-size: 12px;">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>Fecha</th>
                                    <th>Serie</th>
                                    <th>Correlativo</th>
                                    <th>Beneficiario</th>
                                    <th>Monto Aplicado</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listado as $l)
                                    <tr style="text-align: center;">
                                        <td data-order="{{$l->fecha_emision}}">{{ \Carbon\Carbon::parse($l->fecha_emision)->format('d/m/Y') }}</td>
                                        <td>{{ $l->serie }}</td>
                                        <td class="numero">{{ $l->correlativo }}</td>
                                        <td></td>
                                        <td class="numero">{{ $l->monto }}</td>
                                        <td>{{ $l->estado_descripcion }}</td>
                                        <td><a href="{{ route('editar_recibo', [$l->id,0]) }}" class="btn btn-xs btn-warning rounded-circle elevation-4" title="Editar Documento"><i class="fas fa-edit"></i></a></td>
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
@endsection
@section('js')
    <script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
    @if(Session::get('type') == 'success')
        @if(Session::has('message'))
            <script>
                
                setTimeout(function() {
                    swal({
                        title: "Trabajo Finalizado",
                        text: "{!! Session::get('message') !!}",
                        type: "success"
                    });
                }, 1000);
            </script>
        @endif
    @endif
    @if(Session::get('type') == 'error')
        @if(Session::has('message'))
            <script>
                setTimeout(function() {
                    swal({
                        title: "Error",
                        text: "{!! Session::get('message') !!}",
                        type: "error"
                    });
                }, 1000);
            </script>
        @endif
    @endif
    <script type="text/javascript">
         $(function () {
            $('#tblprincipal').DataTable({
                "scrollX": true,
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "pageLength": 25,  // Esto establece que por defecto se muestren 25 registros
                "lengthMenu": [ [10, 25, 50, 100], [10, 25, 50, 100] ],  // Esto establece las opciones en el dropdown
                "order": [[0, 'desc'], [1, 'asc'], [2, 'asc']],
                "language": {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla =(",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sSearch": "Buscar:",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    }
                },
                "dom": '<"row"<"col-sm-4"l><"col-sm-4 text-center"B><"col-sm-4"f>>rtip', // Ajuste para disposición
                columnDefs: [
                    { width: "10%", targets: 0 }, // Columna 0 (primera columna)
                    { width: "10%", targets: 1 }, // Columna 1
                    { width: "10%", targets: 2 },  // Columna 2
                    { width: "40%", targets: 3 },  // Columna 2
                    { width: "20%", targets: 4 },
                    { width: "10%", targets: 5 },  // Columna 5
                ],
                autoWidth: false,
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        className: 'btn btn-md btn-default'
                    }
                ]
            });
        });
        //=======================================================================
        // Confirmar Salida de pantalla
        //=======================================================================
        function confirma_salida(){
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
                        window.location.href = "{{ route('home') }}";
                    } 
                }
            );
        }
    </script>
@endsection