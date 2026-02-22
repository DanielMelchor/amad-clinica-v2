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

@section('title', 'Aseguradoras')

@section('content')
    <div class="row pt-3">
        <div class="col-12 col-md-11 mx-auto">
            <div class="card shadow-lg">
                <div class="card-header" style="background-color: #E1E8ED;">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h6 class="mb-0 font-weight-bold"><i class="fas fa-shield-alt mr-2"></i>Aseguradoras</h6>
                        </div>
                        <div class="col-4 text-right">
                            <button type="button" class="btn btn-xs btn-outline-primary rounded-circle elevation-3" onclick="fn_agregar();">
                                <i class="fas fa-plus"></i>
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-3">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblprincipal" class="table table-sm table-striped table-hover w-100">
                            <thead>
                                <tr class="text-center">
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Teléfonos</th>
                                    <th>Contacto</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pAseguradoras as $pAseguradora)
                                    <tr class="text-center">
                                        {{-- IMPORTANTE: data-label permite la vista móvil --}}
                                        <td data-label="Nombre">{{ $pAseguradora->nombre }}</td>
                                        <td data-label="Dirección">{{ $pAseguradora->direccion }}</td>
                                        <td data-label="Teléfonos">{{ $pAseguradora->telefonos }}</td>
                                        <td data-label="Contacto">{{ $pAseguradora->contacto }}</td>
                                        <td data-label="Estado">
                                            <span class="badge {{ $pAseguradora->estado == 1 ? 'badge-success' : 'badge-danger' }}">
                                                {{ $pAseguradora->estado == 1 ? 'Alta' : 'Baja' }}
                                            </span>
                                        </td>
                                        <td data-label="Acciones">
                                            @php $Id = Crypt::encrypt($pAseguradora->id); @endphp
                                            <button class="btn btn-xs btn-warning rounded-circle elevation-3 shadow-sm" onclick="fn_edicion('{{ $Id }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
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

    @include('aseguradoras.partials.modals_aseguradoras')

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