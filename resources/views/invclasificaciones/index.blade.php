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

@section('title', 'Clasificaciones')

@section('content')
    <div class="row pt-3">
        <div class="col-12 col-md-11 mx-auto">
            <div class="card shadow-lg">
                <div class="card-header" style="background-color: #E1E8ED;">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h6 class="mb-0 font-weight-bold"><i class="fas fa-shield-alt mr-2"></i>Clasificaciones</h6>
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
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registros as $registro)
                                    <tr>
                                        <td class="align-middle text-center">{{ $registro->nombre }}</td>
                                        <td class="align-middle text-center">
                                            <span class="badge {{ $registro->estado == 1 ? 'badge-success' : 'badge-danger' }}">
                                                {{ $registro->estado == 1 ? 'Alta' : 'Baja' }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-right">
                                            @php $Id= Crypt::encrypt($registro->id); @endphp
                                            <button class="btn btn-xs btn-warning rounded-circle elevation-2" title="Editar" onclick="fn_edicion('{{ $Id }}')">
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

    @include('invclasificaciones.partials.modals_invclasificaciones')

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

        //========================================================================
        // Levantar modal de Agregar
        //========================================================================
        function fn_agregar(){
            document.getElementById('nombre').value  = '';
            /*$('#plural').prop('checked', false);
            $('#estado').prop('checked', false);*/
            $('#agregarModalCenter').on('shown.bs.modal', function () {
                $('#nombre').trigger('focus');
            });
            $("#agregarModalCenter").modal();
        }

        //========================================================================
        // Levantar modal de edición
        //========================================================================
        function fn_edicion(id){
            $.ajax({
                url: "{{ route('inv_clasificacion_editar') }}",
                type: "POST",
                dataType: 'json',
                data: {"_token": "{{ csrf_token() }}", 
                       id : id},
                success: function(response){
                    console.log(response);
                    document.getElementById('eid').value           = id;
                    document.getElementById('enombre').value       = response.nombre;

                    if (response.definir_caracteristica == 1) {
                        $('#edefinir_caracteristica').prop('checked', true);
                    }else{
                        $('#edefinir_caracteristica').prop('checked', false);
                    }

                    if (response.definir_medidas == 1) {
                        $('#edefinir_medidas').prop('checked', true);
                    }else{
                        $('#edefinir_medidas').prop('checked', false);
                    }

                    if (response.definir_dosis == 1) {
                        $('#edefinir_dosis').prop('checked', true);
                    }else{
                        $('#edefinir_dosis').prop('checked', false);
                    }

                    if (response.estado == 1) {
                        $('#eestado').prop('checked', true);
                    }else{
                        $('#eestado').prop('checked', false);
                    }

                    $('#editarModalCenter').on('shown.bs.modal', function () {
                        $('#enombre').trigger('focus');
                    });
                    $("#editarModalCenter").modal();
                },
                error: function(error){
                    console.log(error);
                }
            });
        }
    </script>
@endsection