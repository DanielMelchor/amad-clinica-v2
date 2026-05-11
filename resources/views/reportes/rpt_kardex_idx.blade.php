@extends('adminlte::page')
@section('css')
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
@section('title', 'Reportes')

@section('content_header')
    <br>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header" style="background-color: #E1E8ED;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold">
                        <i class="fas fa-file mr-2"></i>Kardex de Artículos
                    </h6>
                    <div>
                        <button class="btn btn-sm btn-outline-info rounded-circle elevation-2 mr-1" onclick="fnAbrirBusqueda();">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-danger rounded-circle elevation-2">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="tblprincipal" class="table table-bordered table-striped table-sm" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>Familia</th>
                            <th>Insumo</th>
                            <th>Fecha</th>
                            <th>Movimiento</th>
                            <th class="text-right">Saldo Inicial</th>
                            <th class="text-right">Entrada</th>
                            <th class="text-right">Salida</th>
                            <th class="text-right">Saldo Final</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kardexFinal as $kardex)
                            <tr>
                                <td>{{ $kardex['familia'] }}</td>
                                <td>{{ $kardex['producto'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($kardex['fecha'])->format('d/m/Y H:i') }}</td>
                                <td>{{ $kardex['transaccion_descripcion'] }}</td>
                                <td class="numero moneda">{{ $kardex['saldo_inicial'] }}</td>
                                <td class="numero moneda">{{ $kardex['entrada'] }}</td>
                                <td class="numero moneda">{{ $kardex['salida'] }}</td>
                                <td class="numero moneda">{{ $kardex['saldo_final'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- Incluimos el modal pasando la ruta de movimientos y activando fechas --}}
    @include('reportes.partials.modals_reportes', [
        'routeAction' => route('rpt_kardex_articulos'),
        'mostrarFechas' => true
    ])
@endsection
@section('js')
    <script>
        //=====================================================================
        // Función para abrir parametros de busqueda
        //=====================================================================
        function fnAbrirBusqueda(){
            event.preventDefault();
            $('#busquedaModal').find('input[type="text"], input[type="email"], input[type="number"], textarea').val('');
            $('#busquedaModal').modal('show');
            $('#bodega_id').select2({
                theme: 'bootstrap4',
                width: 'style', // Esto hace que tome el ancho del elemento original
                placeholder: 'Seleccionar ...'
            });
            $('#familia_id').select2({
                theme: 'bootstrap4',
                width: 'style', // Esto hace que tome el ancho del elemento original
                placeholder: 'Seleccionar ...'
            });
            $('#producto_id').select2({
                theme: 'bootstrap4',
                width: 'style', // Esto hace que tome el ancho del elemento original
                placeholder: 'Seleccionar ...'
            });
        }

        $(function () {
            $('#tblprincipal').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "order": [[0, 'asc'], [1, 'asc'], [2, 'asc']],
                "info": true,
                "autoWidth": false,
                "pageLength": 25,  // Esto establece que por defecto se muestren 25 registros
                "lengthMenu": [ [10, 25, 50, 100], [10, 25, 50, 100] ],  // Esto establece las opciones en el dropdown
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
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        className: 'btn btn-md btn-default'
                    }
                ]
            });
        });
    </script>
@endsection