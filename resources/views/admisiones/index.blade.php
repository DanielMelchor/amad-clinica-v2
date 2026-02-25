@extends('adminlte::page')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style type="text/css">
        /* --- ESTILOS BASE --- */
        .btn-guardar { background-color: #A5C890 !important; }
        .table-responsive { width: 100%; margin-bottom: 1rem; overflow-x: hidden; }

        /* --- ESTRATEGIA MOBILE FIRST (Hasta 768px) --- */
        @media (max-width: 768px) {
            /* Forzamos a la tabla a no comportarse como tabla */
            #tblAdmision, 
            #tblAdmision thead, 
            #tblAdmision tbody, 
            #tblAdmision th, 
            #tblAdmision td, 
            #tblAdmision tr { 
                display: block; 
            }

            /* Ocultar cabecera original */
            #tblAdmision thead tr { 
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            #tblAdmision tr {
                margin-bottom: 1.2rem;
                border: 1px solid #dee2e6;
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                background: #fff;
                padding: 10px;
            }

            #tblAdmision td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                text-align: right !important;
                padding: 0.75rem 0.5rem !important;
                border: none !important;
                border-bottom: 1px solid #f2f2f2 !important;
                position: relative;
            }

            #tblAdmision td:last-child { border-bottom: 0 !important; }

            /* Generar etiquetas desde el atributo data-label */
            #tblAdmision td:before {
                content: attr(data-label);
                font-weight: bold;
                text-align: left;
                color: #495057;
                flex: 1;
                font-size: 0.85rem;
                text-transform: uppercase;
                padding-right: 10px;
            }

            /* Botones de acción más amigables para touch */
            .btn-xs { 
                padding: 0.5rem 0.8rem !important; 
                font-size: 0.9rem !important; 
            }
        }

        /* Control de altura para que no desborde la pantalla */
        #nuevaAdmisionModal .modal-dialog {
            /* Centrado vertical y horizontal */
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
            margin: 0.5rem auto; /* Margen de seguridad en los 4 lados */
        }

        @media (max-width: 576px) {
            #nuevaAdmisionModal .modal-dialog {
                /* Asegura que el modal no sea más ancho que el 95% de la pantalla */
                width: 95vw !important;
                max-width: 95vw !important;
            }
        }

        #nuevaAdmisionModal .modal-content {
            /* Altura máxima para que no se salga por arriba/abajo */
            max-height: 92vh; 
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        #nuevaAdmisionModal .card-body {
            /* Scroll interno suave */
            overflow-y: auto;
            overflow-x: hidden; /* Evita desbordamiento horizontal interno */
            padding: 1rem !important;
            -webkit-overflow-scrolling: touch;
        }

        /* Evitar que las filas (rows) de Bootstrap causen scroll horizontal */
        #nuevaAdmisionModal .row {
            margin-right: -5px;
            margin-left: -5px;
        }
        #nuevaAdmisionModal .col-12, #nuevaAdmisionModal .col-6 {
            padding-right: 5px;
            padding-left: 5px;
        }

        /* Ajustes Mobile First */
        @media (max-width: 576px) {
            #nuevaAdmisionModal .modal-dialog {
                margin: 0.5rem; /* Crea un margen pequeño alrededor para que no toque los bordes */
                max-width: calc(100% - 1rem) !important; /* Fuerza a que no exceda el ancho disponible */
            }

            #nuevaAdmisionModal .modal-content {
                border-radius: 0.5rem; /* Bordes redondeados para suavizar el diseño */
            }

            /* Evitar que el contenedor interno añada padding extra que empuje el contenido */
            #nuevaAdmisionModal .container-fluid {
                padding-right: 10px;
                padding-left: 10px;
            }
        }
    </style>
@endsection

@section('title', 'Admisiones')

@section('content')
    <div class="container-fluid pt-3">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold">
                                <i class="fas fa-shield-alt mr-2"></i>Admisiones
                            </h6>
                            <div>
                                <button class="btn btn-sm btn-outline-info rounded-circle elevation-2 mr-1" onclick="fnAbrirBusqueda();">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button class="btn btn-sm btn-primary rounded-circle elevation-2 mr-1" onclick="fnNuevaAdmision();">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-danger rounded-circle elevation-2">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center p-2 p-md-3">
                        <div class="table-responsive">
                            <table id="tblAdmision" class="table table-sm table-hover" width="100%" style="font-size: 13px;">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th>Admisión</th>
                                        <th>Fecha</th>
                                        <th>Médico</th>
                                        <th>Hospital</th>
                                        <th>Paciente</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Ejemplo de fila con data-labels para Mobile --}}
                                    @foreach($admisiones as $admision)
                                    <tr>
                                        <td data-label="Admisión">{{ $admision->admision_no }}</td>
                                        <td data-label="Fecha">{{ \Carbon\Carbon::parse($admision->fecha)->format('d/m/Y') }}</td>
                                        <td data-label="Médico">{{ $admision->medico->nombre_completo }}</td>
                                        <td data-label="Hospital">{{ $admision->hospital->nombre }}</td>
                                        <td data-label="Paciente">{{ $admision->paciente->nombre_completo }}</td>
                                        <td data-label="Estado"><span class="badge badge-success">Activo</span></td>
                                        <td data-label="Acciones">
                                            <button class="btn btn-xs btn-warning rounded-circle elevation-2"><i class="fas fa-edit"></i></button>
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
    @include('admisiones.partials.modals_admision')
@endsection

@section('js')
    {{-- Capturar Errores de Validación (como el unique:username) --}}
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Revisar Formulario',
                    html: `<ul style="text-align: left;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>`,
                    confirmButtonText: "Aceptar",
                    customClass: { confirmButton: 'btn btn-danger' },
                    buttonsStyling: false
                });
            });
        </script>
    @endif

    {{-- Verifica los mensajes manuales del Controlador --}}
    @if(session('type'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: "{{ session('type') == 'success' ? '¡Trabajo Finalizado!' : '¡Error!' }}",
                    text: "{!! session('message') !!}",
                    icon: "{{ session('type') }}",
                    confirmButtonText: "Aceptar",
                    customClass: { 
                        confirmButton: "{{ session('type') == 'success' ? 'btn btn-success' : 'btn btn-danger' }}" 
                    },
                    buttonsStyling: false
                });
            });
        </script>
    @endif
    <script type="text/javascript">
        $(document).ready(function() {
            // Si usas DataTables, asegúrate de asignar los data-labels dinámicamente
            // o mediante la propiedad 'createdRow'
            $('#adm_aseguradora_id').on('change', function() {
                const id = $(this).val();
                fn_habilitar_poliza(id);
            });
        });

        function fnNuevaAdmision(){
            $('#nuevaAdmisionModal').modal('show');
        }

        function fn_habilitar_poliza(id) {
            // 1. Determinamos el estado basado en si el ID tiene valor
            const tieneId = (id !== '' && id !== null && id !== undefined);
            
            // 2. Agrupamos los campos que comparten el mismo comportamiento
            const $camposSeguro = $('#poliza_no, #autorizacion_no, #copago, #coaseguro');

            // 3. Aplicamos los cambios de una sola vez
            // El estado 'readonly' es lo opuesto a 'tieneId'
            $camposSeguro.prop('readonly', !tieneId);
            $camposSeguro.prop('required', tieneId);

            // 4. Limpieza (Opcional pero recomendado)
            // Si se deshabilita, podrías querer limpiar los valores previos
            if (!tieneId) {
                $camposSeguro.val('');
            }
        }

        //=====================================================================
        // Función para abrir parametros de busqueda
        //=====================================================================
        function fnAbrirBusqueda(){
            event.preventDefault();
            $('#busquedaModal').find('input[type="text"], input[type="email"], input[type="number"], textarea').val('');
            $('#busquedaModal').modal('show');
        }

        //=====================================================================
        // Realizar busqueda
        //=====================================================================
        function fnRealizarBusqueda(){
            event.preventDefault();
            var table;
            $('#busquedaModal').modal('hide');
            // $('#tblAdmision').css('display','block');
            var admision_no = document.getElementById('find_admision_no').value;
            var nombre     = document.getElementById('find_nombre').value;
            $.ajax({
                url: "{{ route('listado_admisiones') }}",
                type: "POST",
                dataType: 'json',
                data: {"_token": "{{ csrf_token() }}",
                       admision_no : admision_no,
                       nombre : nombre},
                success: function(response){
                    if ($.fn.DataTable && $.fn.DataTable.isDataTable('#tblAdmision')) {
                        $('#tblAdmision').DataTable().destroy();
                    }
                    $("#tblAdmision tbody").empty();
                    table = $('#tblAdmision').DataTable({
                        data: response, // Datos cargados a través de AJAX
                        columns: [
                          { data: 'admision_no' },
                          { data: 'fecha' },
                          // { data: 'tipo_admision' },
                          { data: 'medico_nombre' },
                          { data: 'hospital_nombre' },
                          { data: 'paciente_nombre' },
                          { data: 'estado' },
                          {
                                // Esta columna contiene el botón de editar
                                render: function(data, type, row) {
                                    // Crear el enlace de editar con la URL dinámica
                                    var editUrl = "{{ route('editar_admision', ':id') }}";
                                    editUrl = editUrl.replace(':id', row['id']); // Reemplazar :id con el id de la fila actual

                                    return '<a href="' + editUrl + '" class="btn btn-xs btn-warning rounded-circle elevation-4" title="Editar" target="_blank"><i class="fas fa-edit"></i></a>';
                                }
                          }
                        ],
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'excelHtml5',  // Esto es para el botón de Excel
                                // text: 'Descargar <span class="fa-stack fa-1x" style="vertical-align: middle; font-size: 0.8em;"><i class="fas fa-circle fa-stack-2x" style="color: #28a745;"></i><i class="fas fa-file-excel fa-stack-1x fa-inverse"></i></span>',  // Texto del botón

                                // title: 'Datos Exportados',  // Título del archivo Excel
                                // className: 'btn btn-default'  // Puedes personalizar el estilo del botón
                                text: '<i class="fas fa-file-excel"></i>', 
                                titleAttr: 'Descargar a Excel', // El tooltip que sale al pasar el mouse
                                // Aplicamos las clases que solicitaste
                                className: 'btn btn-xs btn-default',
                                // Forzamos dimensiones para que sea un círculo perfecto y no un óvalo
                                attr: {
                                    style: 'width: 25px; height: 25px; display: inline-flex; align-items: center; justify-content: center; padding: 0;'
                                }
                            }
                        ],
                        order: [[0, 'desc']]
                    });
                    // Limpiamos el contenedor del header por si había un botón viejo
                    $('#contenedor-boton-excel').empty();
                    // Movemos el contenedor de botones de la tabla al header del modal
                    table.buttons().container().appendTo('#contenedor-boton-excel');
                    $('#contenedor-boton-excel').show().removeAttr('hidden').css('display', 'inline-flex');
                },
                error: function(error){
                    console.log(error);
                }
            });
        }
    </script>
@endsection