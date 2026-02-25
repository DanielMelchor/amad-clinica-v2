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

@section('title', 'Pacientes')

@section('content')
    <div class="row pt-3">
        <div class="col-12 col-lg-12 mx-auto">
            <div class="card shadow-lg">
                <div class="card-header" style="background-color: #E1E8ED;">
                    <div class="row align-items-center flex-nowrap">
                        <div class="col-auto flex-grow-1">
                            <h6 class="mb-0 font-weight-bold text-truncate">
                                <i class="fas fa-shield-alt mr-1 mr-sm-2"></i>Lista de Pacientes
                            </h6>
                        </div>

                        <div class="col-auto text-right">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('crear_paciente') }}" class="btn btn-sm btn-outline-primary rounded-circle elevation-2 mr-2" title="Nuevo Paciente">
                                    <i class="fas fa-plus"></i>
                                </a>
                                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-danger rounded-circle elevation-2" title="Salir">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblprincipal" class="table table-sm table-striped table-hover w-100">
                            <thead class="bg-light">
                                <tr class="text-center" style="font-size: 12px; text-transform: uppercase;">
                                    <th class="text-left pl-3">Código</th>
                                    <th class="text-center pl-3">Nombre</th>
                                    <th class="text-center pl-3">Expediente</th>
                                    <th class="text-center pl-3">Estado</th>
                                    <th style="width: 50px;">Acción</th>
                                </tr>   
                            </thead>
                            <tbody>
                                @foreach($pPacientes as $pPaciente)
                                    <tr class="text-center" style="font-size: 11px;">
                                        <td>{{ $pPaciente->codigo_id}}</td>
                                        <td>{{ $pPaciente->nombre_completo }}</td>
                                        <td>{{ $pPaciente->expediente_no }}</td>
                                        @if($pPaciente->estado == 1)
                                            <td>Activo</td>
                                        @else
                                            <td>Baja</td>
                                        @endif
                                        @php $pacienteId= Crypt::encryptString($pPaciente->id); @endphp
                                        <td>
                                            <a href="{{route('editar_paciente' , (string)$pacienteId )}}" class="btn btn-xs btn-warning rounded-circle elevation-4" title="Editar Empresa"><i class="fas fa-edit"></i></a>
                                            <a href="{{route('nueva_admision' , ['paciente_id' => $pacienteId] )}}" class="btn btn-xs btn-outline-info rounded-circle elevation-4" title="ver Admisiones"><i class="fas fa-book-medical"></i></a>
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
        });
    </script>
@endsection