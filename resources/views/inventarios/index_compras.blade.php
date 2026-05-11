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
    </style>
@endsection
@section('title', 'Compras')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-11 mx-auto">
                <div class="card shadow-lg border-0">
                    <div class="card-header py-2" style="background-color: #E1E8ED;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold text-secondary">Listado de Compras</h6>
                            <div class="d-flex">
                                <a href="{{ route('crear_compra') }}" class="btn btn-sm btn-outline-primary rounded-circle elevation-2 mr-2" title="Nueva Compra">
                                    <i class="fas fa-plus-circle"></i>
                                </a>
                                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-danger rounded-circle elevation-2" title="Salir">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0 p-md-3">
                        <div class="table-responsive">
                            <table id="tblprincipal" class="table table-sm table-striped table-hover mb-0">
                                <thead class="bg-light">
                                    <th class="text-left pl-3">Transacción</th>
                                    <th class="d-none d-sm-table-cell">Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Soporte</th>
                                    <th>Total</th>
                                    <th style="width: 50px;">Acción</th>
                                </thead>
                                <tbody>
                                    @foreach($lista as $l)
                                        <tr style="font-size: 12px;">
                                            <td>{{ $l->correlativo}} - {{ $l->anio }}</td>
                                            <td>{{ \Carbon\Carbon::parse($l->created_at)->format('d/m/Y') }}</td>
                                            <td>{{ $l->nombre_comercial }}</td>
                                            <td>{{ $l->serie }} - {{ $l->numero_documento}}</td>
                                            <td class="numero">Q {{ number_format($l->total, 2, '.', ',') }}</td>
                                            <td>
                                                @php $Id= Crypt::encrypt($l->id); @endphp
                                                <a href="{{ route('editar_compra', $Id) }}" class="btn btn-sm btn-warning rounded-circle elevation-4" title="Editar Compra"> <i class="fas fa-edit"></i></a>
                                            </td>
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
        $(function () {
            $('#tblprincipal').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
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

        const formatter = new Intl.NumberFormat('es-GT', {
          style: 'currency',
          currency: 'GTQ',
          minimumFractionDigits: 2
        });
    </script>
@endsection