@extends('adminlte::page')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style type="text/css">
        /* --- ESTILOS BASE --- */
        .btn-guardar { background-color: #A5C890 !important; }
        .table-responsive { width: 100%; margin-bottom: 1rem; overflow-x: auto; }

        /* --- ESTRATEGIA MOBILE FIRST (Hasta 768px) --- */
        @media (max-width: 768px) {
            #tblprincipal thead { display: none; } /* Ocultar cabecera en móvil */

            #tblprincipal tr {
                display: block;
                margin-bottom: 1.2rem;
                border: 1px solid #dee2e6;
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                background: #fff;
                padding: 10px;
            }

            #tblprincipal td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                text-align: right !important;
                padding: 0.75rem 0.5rem !important;
                border-top: 1px solid #f2f2f2 !important;
                width: 100% !important;
                min-height: 45px;
            }

            #tblprincipal td:first-child { border-top: none !important; }

            /* Generar etiquetas desde el atributo data-label */
            #tblprincipal td:before {
                content: attr(data-label);
                font-weight: bold;
                text-align: left;
                color: #495057;
                flex: 1;
                font-size: 0.85rem;
                text-transform: uppercase;
            }

            /* Botones de acción más grandes para pulgares */
            .btn-xs { padding: 0.5rem 0.7rem; font-size: 1rem; }
            
            /* Ajuste de controles de búsqueda */
            .dataTables_wrapper .row { flex-direction: column; align-items: center; }
        }
    </style>
@endsection

@section('title', 'Traslados')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="row pt-3">
        <div class="col-12 col-md-11 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-header" style="background-color: #E1E8ED;">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h6 class="mb-0 font-weight-bold"><i class="fas fa-shield-alt mr-2"></i>Lista de Traslados</h6>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('crear_traslado') }}" class="btn btn-sm btn-outline-primary rounded-circle elevation-2 mr-2" title="Nuevo Traslado">
                                <i class="fas fa-plus-circle"></i>
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-danger rounded-circle elevation-2" title="Salir">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblprincipal" class="table table-sm table-striped table-hover w-100">
                            <thead class="bg-light">
                                <tr class="text-center" style="font-size: 11px; text-transform: uppercase;">
                                    <th class="text-left pl-3">Transacción / Número</th>
                                    <th class="d-none d-sm-table-cell">Fecha</th>
                                    <th>Bodega Origen</th>
                                    <th>Bodega Destino</th>
                                    <th style="width: 50px;">Acción</th>
                                </tr>   
                            </thead>
                            <tbody>
                                @foreach($lista as $l)
                                    <tr class="text-center" style="font-size: 12px;">
                                        <td class="text-left pl-3 align-middle">
                                            <div class="font-weight-bold text-dark">{{ $l->transaccion_descripcion }}</div>
                                            <small class="text-muted">{{ $l->correlativo }} - {{ $l->anio }}</small>
                                        </td>
                                        <td class="align-middle d-none d-sm-table-cell">
                                            {{ \Carbon\Carbon::parse($l->fecha_emision)->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            <div class="font-weight-bold text-dark">{{ $l->bodegaOrigen->descripcion }}</div>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold text-dark">{{ $l->bodegaDestino->descripcion }}</div>
                                        </td>
                                        <td class="align-middle pr-3">
                                            @php $Id= Crypt::encrypt($l->id); @endphp
                                            <a href="{{ route('editar_traslado', $Id) }}" class="btn btn-sm btn-warning rounded-circle elevation-2" title="Editar Compra">
                                                <i class="fas fa-edit"></i>
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
    </div>

    @include('inventarios.partials.modals_traslados')

@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // Inicialización limpia de DataTable
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

            $('#formaNuevoRegistro').on('submit', function() {
                $('#submitButton').prop('disabled', true);
            });
        });

        function fn_agregar(){
            $('#formaNuevoRegistro')[0].reset();
            $('#agregarModalCenter').modal('show');
        }

        function fn_edicion(id){
            $.ajax({
                url: "{{ route('aseguradora_editar') }}",
                type: "POST",
                data: {"_token": "{{ csrf_token() }}", id : id},
                success: function(res){
                    $('#eid').val(id);
                    $('#enombre').val(res.nombre);
                    $('#edireccion').val(res.direccion);
                    $('#etelefonos').val(res.telefonos);
                    $('#econtacto').val(res.contacto);
                    $('#efacturacion_nit').val(res.facturacion_nit);
                    $('#efacturacion_nombre').val(res.facturacion_nombre);
                    $('#efacturacion_direccion').val(res.facturacion_direccion);
                    $('#eestado').prop('checked', res.estado == 'A');
                    $('#editarModalCenter').modal('show');
                }
            });
        }
    </script>
@endsection