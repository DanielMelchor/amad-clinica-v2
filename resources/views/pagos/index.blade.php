@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style type="text/css">
        /* Tabla de escritorio: Resaltar la columna 5 (Monto Aplicado) */
        #tblprincipal td:nth-child(5) {
            font-weight: bold;
            color: #28a745; /* Verde para dinero */
        }
        /* Estilos para resaltar el monto en la vista móvil */
        .mobile-monto {
            font-weight: bold;
            color: #28a745;
            font-size: 1.1rem;
        }

        .numero{
            text-align: right;
        }
        .moneda:after {
            content: attr(data-numero);
        }
    </style>
@endsection
@section('title', 'Comprobante de Pago')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="container-fluid px-0 px-md-2">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color: #E1E8ED;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">Recibos</h6>
                    <div>
                        <a href="{{ route('nuevo_recibo') }}" class="btn btn-sm btn-outline-primary rounded-circle elevation-2" title="Grabar">
                            <i class="fas fa-plus"></i>
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-danger rounded-circle elevation-2 ml-1" title="Salir">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-2 p-md-3">
                
                <div class="d-block d-md-none">
                    @forelse($listado as $l)
                        @php $Id = Crypt::encrypt($l->id); @endphp
                        <div class="card mb-3 border shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">
                                        <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($l->fecha_emision)->format('d/m/Y') }}
                                    </span>
                                    <span>
                                        @if($l->estado == 1)
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Anulado</span>
                                        @endif
                                    </span>
                                </div>
                                
                                <h6 class="font-weight-bold mb-1">Doc: {{ $l->serie }} - {{ $l->correlativo }}</h6>
                                <p class="mb-2 text-muted small">Beneficiario: <span class="font-italic">No especificado</span></p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                    <span class="mobile-monto">{{ $l->monto }}</span>
                                    <a href="{{ route('editar_recibo', [$Id]) }}" class="btn btn-sm btn-warning rounded-circle elevation-2" title="Editar documento">
                                        <i class="fas fa-edit text-dark"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center p-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                            No hay facturas registradas.
                        </div>
                    @endforelse
                </div>

                <div class="d-none d-md-block table-responsive">
                    <table id="tblprincipal" class="table table-sm table-striped table-hover w-100" style="font-size: 13px;">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 12%;">Fecha</th>
                                <th style="width: 10%;">Serie</th>
                                <th style="width: 12%;">Correlativo</th>
                                <th style="width: 36%;">Beneficiario</th>
                                <th style="width: 12%;">Monto Aplicado</th>
                                <th style="width: 10%;">Estado</th>
                                <th style="width: 8%;" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($listado as $l)
                                <tr>
                                    <td data-order="{{$l->fecha_emision}}">{{ \Carbon\Carbon::parse($l->fecha_emision)->format('d/m/Y') }}</td>
                                    <td>{{ $l->serie }}</td>
                                    <td class="numero">{{ $l->correlativo }}</td>
                                    <td></td>
                                    <td class="numero">{{ $l->monto }}</td>
                                    <td>
                                        @if($l->estado == 1)
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Anulado</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php $Id= Crypt::encrypt($l->id); @endphp
                                        <a href="{{ route('editar_recibo', [$Id]) }}" class="btn btn-xs btn-warning rounded-circle elevation-2 monto" title="Editar documento">
                                            <i class="fas fa-edit text-dark"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            @if(session('message'))
                Swal.fire({
                    title: "{{ session('type') == 'success' ? '¡Éxito!' : 'Error' }}",
                    text: "{!! addslashes(session('message')) !!}",
                    icon: "{{ session('type') }}"
                });
            @endif
        });
    </script>
    <script>
        $(document).ready(function() {
            @if(session('message'))
                Swal.fire({
                    title: "{{ session('type') == 'success' ? '¡Éxito!' : 'Error' }}",
                    text: "{!! addslashes(session('message')) !!}",
                    icon: "{{ session('type') }}",
                    confirmButtonColor: '#3085d6'
                });
            @endif
            
            // Si usas DataTables, inicialízalo solo si estás en vista de escritorio
            // para no afectar el rendimiento en móviles
            if ($(window).width() >= 768) {
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
                "order": [[3, 'desc'], [4, 'asc'], [5, 'desc']],
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
            }
        });
    </script>
    <script type="text/javascript">
    </script>
@endsection