@extends('adminlte::page')

@section('css')
    <style type="text/css">
        /* --- DISEÑO MÓVIL (Base) --- */
        
        /* Contenedores de filtros en columna para móviles */
        .filter-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 15px;
        }

        .input-group-text {
            min-width: 70px;
            font-size: 0.85rem;
        }

        /* Select2 corregido para móviles */
        .select2-container--bootstrap4 {
            width: 100% !important;
        }

        /* Tabs tipo scroll horizontal en móvil para no romper el layout */
        .nav-pills {
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 5px;
        }
        
        .nav-pills .nav-link {
            white-space: nowrap;
            margin-bottom: 5px;
        }

        /* Tablas: Forzar scroll horizontal y fuente pequeña */
        .table-responsive {
            font-size: 0.85rem;
        }

        .btn-group-responsive {
            padding: 10px 0;
            flex-wrap: wrap; /* Por si son muchos botones para un celular pequeño */
            gap: 10px;       /* Espacio táctil */
        }

        .btn-group-responsive .btn-xs {
            width: 35px;    /* Más grande en móvil para facilitar el clic */
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        /* --- DISEÑO DESKTOP (Min-width: 768px) --- */
        @media (min-width: 768px) {
            .filter-container {
                flex-direction: row; /* Alineación horizontal en PC */
                align-items: center;
            }
            
            .nav-pills {
                flex-wrap: wrap;
                overflow-x: hidden;
            }

            .input-group-text {
                min-width: 80px;
            }

            .btn-group-responsive {
                padding: 0;
                gap: 5px;
            }

            .btn-group-responsive .btn-xs {
                width: 25px; /* Tamaño estándar compacto para escritorio */
                height: 25px;
                font-size: 0.75rem;
            }
        }

        .nav-pills .nav-link.active {
            background: #7FB3D5 !important;
            color: white !important;
        }

        /* Forzamos que el contenedor de Select2 siempre ocupe el 100% disponible */
        .select2-container {
            width: 100% !important;
            display: block; /* Evita comportamientos de inline-block que causan desborde */
        }

        /* Ajuste específico para cuando Select2 está dentro de un input-group de Bootstrap */
        .input-group > .select2-container--bootstrap4 {
            flex: 1 1 auto !important;
            width: 1% !important; /* Truco de Bootstrap para que flex-grow funcione correctamente */
            min-width: 0; /* Permite que el elemento se encoja más allá de su contenido */
        }

        /* Estética para dispositivos táctiles */
        .select2-selection--single {
            height: calc(1.5em + 0.75rem + 2px) !important; /* Altura estándar de Bootstrap 4 */
            padding: 0.25rem 0.5rem;
        }

        .table-active {
            background-color: #c3ab95 !important; /* amarillo claro */
        }


        /* Permite que las pestañas de salas se deslicen lateralmente en móvil */
        #salasTab::-webkit-scrollbar {
            display: none;
        }
        #salasTab {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Aumentar el área de clic en móviles para las filas de la tabla */
        /*.table-sm td {
            padding: 0.75rem 0.3rem !important; 
            vertical-align: middle;
        }*/

        /* Ajuste para que el input datetime-local se vea bien en iOS/Android */
        input[type="datetime-local"] {
            min-height: 38px;
        }

        /* Ajustes de botones para pulgares */
        .btn-sm.rounded-circle {
            width: 35px;
            height: 35px;
            line-height: 24px;
            text-align: center;
            padding: 5px 0;
        }

        /* Cambiar el estilo de los botones del SweetAlert */
        .swal2-styled.swal2-confirm {
            border-radius: 50px !important;
            background-color: #28a745 !important; /* Verde success */
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .swal2-popup {
            border-top: 3px solid #007bff; /* Línea azul arriba como las cards de AdminLTE */
        }

        button:disabled {
            cursor: not-allowed;
            opacity: 0.65;
            filter: grayscale(1);
        }

        #tblHistorico td {
            vertical-align: middle;
            white-space: nowrap; /* Evita que los nombres largos hagan la fila muy alta */
            padding: 8px 4px;
        }

        /* Zebra striping más suave para lectura prolongada */
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0,0,0,.02);
        }
    </style>
@endsection

@section('title', 'Agenda')

@section('content')
    <div class="container-fluid pt-3">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header p-2 p-md-3">
                        
                        <div class="row filter-container">
                            <div class="col-12 col-md-4">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">Médico</span>
                                    </div>
                                    <select class="form-control select2bs4" id="medico_filtro" onchange="getMedico(this)">
                                        <option value=''>Seleccionar...</option>
                                        @foreach($medicos as $m)
                                            <option value="{{$m->id}}" @if($m->principal == 'S') selected @endif>{{ $m->nombre_completo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">Fecha</span>
                                    </div>
                                    <input type="date" class="form-control" id="fecha_filtro" 
                                           value="{{ date('Y-m-d') }}" onchange="getFecha(this)">
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">Estado</span>
                                    </div>
                                    <select class="form-control select2bs4" id="estado_filtro" onchange="getEstado(this)">
                                        <option value="T">Todas</option>
                                        <option value="A" selected>Activas</option>
                                        <option value="C">Canceladas</option>
                                        <option value="R">Realizadas</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-center mb-2">
    
                            <div class="d-none d-md-block">
                                <h5 class="mb-0 text-muted"><i class="fas fa-calendar-alt mr-2"></i>Agenda</h5>
                            </div>

                            <div class="btn-group-responsive w-100 w-md-auto d-flex justify-content-center justify-content-md-end">
                                <a href="#" id="btnAsistencia" class="btn btn-xs btn-outline-info rounded-circle elevation-2 mx-1" onclick="confirmarPresencia()" title="Asistencia"><i class="fas fa-user-check"></i></a>
                                <a href="#" id="btnCita" class="btn btn-xs btn-outline-primary rounded-circle elevation-2 mx-1" title="Cita" onclick="fnEditarCita();"><i class="fas fa-calendar-alt"></i></a>
                                <a href="#" id="btnAdmision" class="btn btn-xs btn-outline-primary rounded-circle elevation-2 mx-1" title="Admisión" onclick="fnCrearAdmision();"><i class="fas fa-user-md"></i></a>
                                <a href="#" id="btnFinalizar" class="btn btn-xs btn-outline-success rounded-circle elevation-2 mx-1" title="Finalizar" onclick="fnFinalizar();"><i class="fas fa-check"></i></a>
                                <a href="#" id="btnCancelar" class="btn btn-xs btn-outline-danger rounded-circle elevation-2 mx-1" title="Cancelar" onclick="fnCancelar();"><i class="fas fa-ban"></i></a>
                                <a href="#" id="btnHistorico" class="btn btn-xs btn-outline-secondary rounded-circle elevation-2 mx-1" title="Histórico" onclick="fnHistorico();"><i class="fas fa-book-medical"></i></a>
                                <a href="#" id="btnBloqueo" class="btn btn-xs btn-outline-secondary rounded-circle elevation-2 mx-1" title="Bloquear" onclick="fnBloqueo();"><i class="fas fa-lock"></i></a>
                            </div>
                        </div>

                        <nav class="mb-3">
                            <div class="nav nav-pills nav-fill d-flex flex-nowrap overflow-auto" id="salasTab" role="tablist">
                                @foreach($salas as $index => $s)
                                    <a class="nav-link {{ $index == 0 ? 'active' : '' }} btn-sm" 
                                       id="nav-link-sala{{$s->id}}" 
                                       data-toggle="tab" 
                                       href="#sala{{$s->id}}">
                                        {{ $s->sala_nombre }}
                                    </a>
                                @endforeach
                            </div>
                        </nav>
                    </div>
                    <div class="card-body">
                        <input type="hidden" id="sala_seleccionada_id" name="sala_seleccionada_id" value="{{ $sala_seleccionada }}">
                        <div class="tab-content">
                            @foreach($salas as $sala)
                                @if($sala_seleccionada == $sala->id )
                                    <div class="tab-pane active" id="sala{{ $sala->id}}">
                                @else
                                    <div class="tab-pane" id="sala{{ $sala->id}}">
                                @endif
                                    <div id="contenedor_{{$sala->id}}" class="overflow-auto">
                                        <div class="table-responsive">
                                            <table id="tbl{{$sala->id}}" class="table table-sm table-striped table-hover text-nowrap" width="100%">
                                                <thead>
                                                    <tr class="text-center" style="font-size: 12px;">
                                                        <th colspan="1">Hora</th>
                                                        <th colspan="3">Paciente</th>
                                                        <th colspan="1">Telefono</th>
                                                        <th colspan="1">Expediente</th>
                                                        <th colspan="1"># Admisión</th>
                                                        <th colspan="1">Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('agenda.partials.modals_agenda')
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
        $(document).ready(function() {
            // Inicialización robusta para Bootstrap 4
            $('.select2bs4').each(function() {
                $(this).select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: "Seleccionar...",
                    allowClear: true,
                    // Si el select está dentro de un modal, descomenta la siguiente línea:
                    // dropdownParent: $(this).parent() 
                });
            });

            // Corrección de bug de foco en el buscador de Select2
            $(document).on('select2:open', () => {
                let searchField = document.querySelector('.select2-search__field');
                if (searchField) {
                    searchField.focus();
                }
            });

            actualizarContext();
            actualizarEstadoBotones();
            aplicar_filtro();
        });

        $('.nav-link').on('click', function() {
            actualizarContext();
            actualizarEstadoBotones();
        });

        //===========================================================================
        // definir variables
        //===========================================================================
        var asset       = '{{ asset('') }}'
        var result_sala = 0;
        var userPermissions = @json($permissions);
        var context_agenda_id          = null;
        var context_hospital_id        = null;
        var context_paciente_id        = null;
        var context_paciente_nombre    = null;
        var context_paciente_telefonos = null;
        var context_hora               = null;
        var context_expediente_no      = null;
        var context_admision_no        = null;
        var context_agenda_estado      = null;
        var context_observaciones      = null;
        var context_medico_id          = null;
        var idRegistro                 = null;

        function zeroPad(num, numZeros) { 
            var n = Math.abs(num); 
            var zeros = Math.max(0, numZeros - Math.floor(n).toString().length ); 
            var zeroString = Math.pow(10,zeros).toString().substr(1); 
            if( num < 0 ) { zeroString = '-' + zeroString; } return zeroString+n; 
        }

        function replacer(val) {
            if ( val === null ) 
            { 
                return ""; // change null to empty string
            } else {
                return val; // return unchanged
            }
        }

        //===========================================================================
        // obtener el valor del registro seleccionado
        //===========================================================================
        $(document).ready(function() {
            // Usamos delegación de eventos para que funcione incluso tras recargar la tabla con AJAX
            fnMarcarLinea();
        });

        function fnMarcarLinea(){
            $(document).on('click', 'table[id^="tbl"] tbody tr', function() {
                const fila = $(this); // 'this' es la fila clicada
                
                // 1. Feedback Visual: Resaltar la fila seleccionada
                $('table[id^="tbl"] tbody tr').removeClass('table-active'); // Quitamos a otras
                fila.addClass('table-active'); // Añadimos a la actual

                // 2. Captura de datos (usando los IDs que ya generas en tu función aplicar_filtro)
                // Accedemos por el ID de la celda o por el índice
                idRegistro = fila.find('#idRegistro').text().trim();
                idPaciente = fila.find('#paciente_id').text().trim();

                context_agenda_id           = idRegistro;
                context_hospital_id         = fila.find('#hospital_id').text().trim();
                context_paciente_id         = idPaciente;
                context_paciente_nombre     = fila.find('#paciente_nombre').text().trim();
                context_paciente_telefonos  = fila.find('#telefonos').text().trim();
                
                // Para celdas que no tienen ID, usamos el índice (fila.cells[n])
                const celdas = fila[0].cells;
                context_hora                = celdas[5].textContent.trim();
                context_expediente_no       = celdas[8].textContent.trim();
                context_admision_no         = celdas[9].textContent.trim();
                context_agenda_estado       = celdas[11].textContent.trim();
                context_observaciones       = celdas[12].textContent.trim();
                context_medico_id           = celdas[13].textContent.trim();
                context_paciente_en_clinica = celdas[14].textContent.trim();

                // 3. Actualizar los botones de acción
                actualizarEstadoBotones(context_paciente_id);
            });
        }

        function actualizarContext(){
            context_agenda_id          = null;
            context_hospital_id        = null;
            context_paciente_id        = null;
            context_paciente_nombre    = null;
            context_paciente_telefonos = null;
            context_hora               = null;
            context_expediente_no      = null;
            context_admision_no        = null;
            context_agenda_estado      = null;
            context_observaciones      = null;
            context_medico_id          = null;
        }

        function actualizarEstadoBotones(context_paciente_id) {
            let valorId = context_paciente_id ? context_paciente_id.toString().trim() : '';
            const btnAsistencia = $('#btnAsistencia');
            const btnCita       = $('#btnCita');
            const btnAdmision   = $('#btnAdmision');
            const btnFinalizar  = $('#btnFinalizar');
            const btnCancelar   = $('#btnCancelar');
            const btnHistorico  = $('#btnHistorico');
            const btnBloqueo    = $('#btnBloqueo');

            if (!context_agenda_id || context_agenda_id === '') {
                btnAsistencia.addClass('disabled')
                             .css('pointer-events', 'none') // Evita clics físicos
                             .attr('tabindex', '-1')        // Evita enfoque por teclado
                             .removeClass('btn-outline-info')
                             .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
                btnCita.addClass('disabled')
                             .css('pointer-events', 'none') // Evita clics físicos
                             .attr('tabindex', '-1')        // Evita enfoque por teclado
                             .removeClass('btn-outline-primary')
                             .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
                btnAdmision.addClass('disabled')
                             .css('pointer-events', 'none') // Evita clics físicos
                             .attr('tabindex', '-1')        // Evita enfoque por teclado
                             .removeClass('btn-outline-info')
                             .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
                btnFinalizar.addClass('disabled')
                             .css('pointer-events', 'none') // Evita clics físicos
                             .attr('tabindex', '-1')        // Evita enfoque por teclado
                             .removeClass('btn-outline-success')
                             .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
                btnCancelar.addClass('disabled')
                             .css('pointer-events', 'none') // Evita clics físicos
                             .attr('tabindex', '-1')        // Evita enfoque por teclado
                             .removeClass('btn-outline-danger')
                             .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
                btnHistorico.addClass('disabled')
                             .css('pointer-events', 'none') // Evita clics físicos
                             .attr('tabindex', '-1')        // Evita enfoque por teclado
                             .removeClass('btn-outline-info')
                             .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
                btnBloqueo.addClass('disabled')
                             .css('pointer-events', 'none') // Evita clics físicos
                             .attr('tabindex', '-1')        // Evita enfoque por teclado
                             .removeClass('btn-outline-info')
                             .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
            }else{
                if (context_agenda_estado == 'B') {
                    btnCita.addClass('disabled')
                           .css('pointer-events', 'none') // Evita clics físicos
                           .attr('tabindex', '-1')        // Evita enfoque por teclado
                           .removeClass('btn-outline-primary')
                           .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
                    btnAdmision.addClass('disabled')
                               .css('pointer-events', 'none') // Evita clics físicos
                               .attr('tabindex', '-1')        // Evita enfoque por teclado
                               .removeClass('btn-outline-primary')
                               .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
                }else{
                    btnCita.removeClass('disabled')
                           .css('pointer-events', 'auto') // Evita clics físicos
                           .attr('tabindex', '1')        // Evita enfoque por teclado
                           .removeClass('btn-outline-secondary')
                           .addClass('btn-outline-primary'); // Color gris para indicar estado inactivo
                    btnAdmision.removeClass('disabled')
                               .css('pointer-events', 'auto') // Evita clics físicos
                               .attr('tabindex', '1')        // Evita enfoque por teclado
                               .removeClass('btn-outline-secondary')
                               .addClass('btn-outline-primary'); // Color gris para indicar estado inactivo
                }
                
                if (context_agenda_estado == 'C' || context_agenda_estado == 'R' || context_agenda_estado == 'B' ) {
                    btnAsistencia.addClass('disabled')
                                 .css('pointer-events', 'none') // Evita clics físicos
                                 .attr('tabindex', '-1')        // Evita enfoque por teclado
                                 .removeClass('btn-outline-info')
                                 .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
                    btnFinalizar.addClass('disabled')
                                 .css('pointer-events', 'none') // Evita clics físicos
                                 .attr('tabindex', '-1')        // Evita enfoque por teclado
                                 .removeClass('btn-outline-success')
                                 .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
                    btnCancelar.addClass('disabled')
                                 .css('pointer-events', 'none') // Evita clics físicos
                                 .attr('tabindex', '-1')        // Evita enfoque por teclado
                                 .removeClass('btn-outline-danger')
                                 .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
                    btnBloqueo.addClass('disabled')
                                 .css('pointer-events', 'none') // Evita clics físicos
                                 .attr('tabindex', '-1')        // Evita enfoque por teclado
                                 .removeClass('btn-outline-info')
                                 .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
                }else{
                    if (valorId === '' || valorId === 'null' || valorId === null) {
                        btnAsistencia.addClass('disabled')
                                     .css('pointer-events', 'none') // Evita clics físicos
                                     .attr('tabindex', '-1')        // Evita enfoque por teclado
                                     .removeClass('btn-outline-info')
                                    .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
                        btnHistorico.addClass('disabled')
                                     .css('pointer-events', 'none') // Evita clics físicos
                                     .attr('tabindex', '-1')        // Evita enfoque por teclado
                                     .removeClass('btn-outline-info')
                                     .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
                    }else{
                        btnAsistencia.removeClass('disabled')
                                     .css('pointer-events', 'auto') // Evita clics físicos
                                     .attr('tabindex', '1')        // Evita enfoque por teclado
                                     .removeClass('btn-outline-secondary')
                                     .addClass('btn-outline-info'); // Color gris para indicar estado inactivo
                        btnHistorico.removeClass('disabled')
                                     .css('pointer-events', 'auto') // Evita clics físicos
                                     .attr('tabindex', '1')        // Evita enfoque por teclado
                                     .removeClass('btn-outline-secondary')
                                     .addClass('btn-outline-info'); // Color gris para indicar estado inactivo
                    }

                    if (context_admision_no === '' || context_admision_no === 'null' || context_admision_no === null) {
                        btnFinalizar.addClass('disabled')
                                     .css('pointer-events', 'none') // Evita clics físicos
                                     .attr('tabindex', '-1')        // Evita enfoque por teclado
                                     .removeClass('btn-outline-success')
                                     .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
                        btnCancelar.addClass('disabled')
                                     .css('pointer-events', 'none') // Evita clics físicos
                                     .attr('tabindex', '-1')        // Evita enfoque por teclado
                                     .removeClass('btn-outline-danger')
                                     .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
                    }else{
                        btnFinalizar.removeClass('disabled')
                                     .css('pointer-events', 'auto') // Evita clics físicos
                                     .attr('tabindex', '1')        // Evita enfoque por teclado
                                     .removeClass('btn-outline-secondary')
                                     .addClass('btn-outline-success'); // Color gris para indicar estado inactivo
                        btnCancelar.removeClass('disabled')
                                     .css('pointer-events', 'auto') // Evita clics físicos
                                     .attr('tabindex', '1')        // Evita enfoque por teclado
                                     .removeClass('btn-outline-secondary')
                                     .addClass('btn-outline-danger'); // Color gris para indicar estado inactivo
                    }
                }
            }

            // if (valorId === '' || valorId === 'null' || valorId === null) {
            //     // Deshabilitar
            //     btnAsistencia.addClass('disabled')
            //                  .css('pointer-events', 'none') // Evita clics físicos
            //                  .attr('tabindex', '-1')        // Evita enfoque por teclado
            //                  .removeClass('btn-outline-info')
            //                  .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
            //     btnAdmision.addClass('disabled')
            //                  .css('pointer-events', 'none') // Evita clics físicos
            //                  .attr('tabindex', '-1')        // Evita enfoque por teclado
            //                  .removeClass('btn-outline-info')
            //                  .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
            //     btnHistorico.addClass('disabled')
            //                  .css('pointer-events', 'none') // Evita clics físicos
            //                  .attr('tabindex', '-1')        // Evita enfoque por teclado
            //                  .removeClass('btn-outline-info')
            //                  .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
            //     btnBloqueo.removeClass('disabled')
            //                  .css('pointer-events', 'auto') // Evita clics físicos
            //                  .attr('tabindex', '1')        // Evita enfoque por teclado
            //                  .removeClass('btn-outline-secondary')
            //                  .addClass('btn-outline-info'); // Color gris para indicar estado inactivo
            // } else {
            //     // Habilitar
            //     btnAsistencia.removeClass('disabled')
            //                  .css('pointer-events', 'auto')
            //                  .removeAttr('tabindex')
            //                  .removeClass('btn-outline-secondary')
            //                  .addClass('btn-outline-info');
            //     btnAdmision.removeClass('disabled')
            //                  .css('pointer-events', 'auto') // Evita clics físicos
            //                  .attr('tabindex', '-1')        // Evita enfoque por teclado
            //                  .removeClass('btn-outline-secondary')
            //                  .addClass('btn-outline-primary'); // Color gris para indicar estado inactivo
            //     btnHistorico.removeClass('disabled')
            //                  .css('pointer-events', 'auto') // Evita clics físicos
            //                  .attr('tabindex', '-1')        // Evita enfoque por teclado
            //                  .removeClass('btn-outline-secondary')
            //                  .addClass('btn-outline-info'); // Color gris para indicar estado inactivo
            //     btnBloqueo.addClass('disabled')
            //                  .css('pointer-events', 'none') // Evita clics físicos
            //                  .attr('tabindex', '-1')        // Evita enfoque por teclado
            //                  .removeClass('btn-outline-info')
            //                  .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
            //     if (context_admision_no || context_admision_no > 0) {
            //         btnAdmision.removeClass('disabled')
            //                    .css('pointer-events', 'none') // Evita clics físicos
            //                    .attr('tabindex', '-1')        // Evita enfoque por teclado
            //                    .removeClass('btn-outline-info')
            //                    .addClass('btn-outline-secondary'); // Color gris para indicar estado inactivo
            //         btnFinalizar.removeClass('disabled')
            //                     .css('pointer-events', 'auto') // Evita clics físicos
            //                     .attr('tabindex', '1')        // Evita enfoque por teclado
            //                     .removeClass('btn-outline-secondary')
            //                     .addClass('btn-outline-success'); // Color gris para indicar estado inactivo
            //         btnCancelar.removeClass('disabled')
            //                     .css('pointer-events', 'auto') // Evita clics físicos
            //                     .attr('tabindex', '1')        // Evita enfoque por teclado
            //                     .removeClass('btn-outline-secondary')
            //                     .addClass('btn-outline-danger'); // Color gris para indicar estado inactivo
            //     }
            // }

        }

        //===========================================================================
        // definir la sala de proceso
        //===========================================================================
        function fnDefinirSala(id){
        
            document.getElementById('sala_seleccionada_id').value = id;
            const tabla = document.getElementById("tbl" + id);
            const sala_seleccionada = document.getElementById('sala_seleccionada_id').value;
            $("table[id^='tbl'] tbody tr").removeClass("table-active");
            idRegistro = null;
        }

        //===========================================================================
        // traer citas
        //===========================================================================
        function aplicar_filtro()
        {
            let sala_activa = document.getElementById('sala_seleccionada_id').value;
            var fecha  = $('#fecha_filtro').val();
            var medico = $('#medico_filtro').val();
            var estado = $('#estado_filtro').val();
            // alert(sala_activa+' '+fecha+' '+medico+' '+estado);
            $("#tbl"+sala_activa+" tbody tr").remove();
            var salas = @json($salas);
            $.each(salas, function(index, valor) {
                salaId = valor['id'];
                $("#tbl"+salaId+" tbody tr").remove();
            });
            $('sala1').removeClass('active');
            $.ajax({
                url: "{{ route('trae_citas') }}",
                type: "POST",
                dataType: 'json',
                data: {"_token": "{{ csrf_token() }}",
                       fecha     : fecha, 
                       medico_id : medico, 
                       estado    : estado
                       },
                success: function(response){
                    var html = '';
                    $('#editarRegistro').modal('hide');
                    $('#bloqueoModal').modal('hide');
                    $('#nuevaAdmisionModal').modal('hide');
                    $("#tbl"+result_sala+" tbody tr").remove();
                    for(var i = 0; i < response.length; i++){
                        var fecha = new Date(response[i]['fecha_inicio']);
                        var hora = zeroPad(fecha.getHours(),2)+':'+zeroPad(fecha.getMinutes(),2);
                        if (response[i]['sala_id'] != result_sala) {
                            if (result_sala != 0) {
                                $("#tbl"+result_sala+" tbody tr").remove();
                                $("#tbl"+result_sala+" tbody").append(html);
                            }
                            html = '';
                            result_sala = response[i]['sala_id'];
                        }

                        switch (response[i]['estado']){
                        case 'B':
                            html += '<tr class="text-center" style="font-size: 12px; background-color: #dbcefb;">'
                            break;
                        case 'C':
                            html += '<tr class="text-center" style="font-size: 12px; background-color: #f9c1bf;">'
                            break;
                        case 'R':
                            html += '<tr class="text-center" style="font-size: 12px; background-color: #bff9e4;">'
                            break;
                        case 'Z':
                            html += '<tr class="text-center" style="font-size: 12px; background-color: #d3f8fa;">'
                            break;
                        default:
                            html += '<tr class="text-center" style="font-size: 12px;">';
                            break;
                        }
                        
                        html += '<td style="display:none;" id="idRegistro">'
                        html += response[i]['id']
                        html += '</td>'
                        html += '<td style="display:none;" id="hospital_id">'+response[i]['hospital_id']+'</td>'
                        html += '<td style="display:none;" id="paciente_id">'+response[i]['paciente_id']+'</td>'
                        html += '<td style="display:none;" id="paciente_nombre">'+response[i]['nombre_completo']+'</td>'
                        html += '<td style="display:none;" id="telefonos">'+response[i]['telefonos']+'</td>'
                        html += '<td class="text-center" colspan="1">'
                        html += hora;
                        html += '</td>'
                        html += '<td  colspan="3">'
                        if (response[i]['estado'] == 'B') {
                        html += '<p class="red-tooltip" data-toggle="tooltip" data-placement="top" title="Bloqueado por '+response[i]['usuario_bloqueo']+' el '+response[i]['fecha_bloqueo']+'">'+replacer(response[i]['observaciones_bloqueo'])+'</p>';
                        }else{
                            if (userPermissions.includes('administrar-pantalla-medicos') || userPermissions.includes('administrar-pantalla-vitales')){
                                html += '<a href="'+asset+'medicos/nueva_admision/'+response[i]['paciente_id']+'/A" data-toggle="tooltip" data-placement="top" title="'+replacer(response[i]['observaciones'])+'" target="_blank">'+replacer(response[i]['nombre_completo'])+'</a>'
                            }else{
                                html += '<p class="red-tooltip" data-toggle="tooltip" data-placement="top">'+replacer(response[i]['nombre_completo'])+'</p>';
                            }
                            
                        }
                        html += '</td>'
                        html += '<td colspan="1">'
                        html += replacer(response[i]['telefonos'])
                        html += '</td>'
                        html += '<td colspan="1">'
                        if (response[i]['expediente_no'] != null) {
                            html += response[i]['expediente_no']
                        }
                        html += '</td>'
                        html += '<td colspan="1">'
                        if (response[i]['admision_no'] != null) {
                            // html += response[i]['admision'];
                            var editUrl = "{{ route('editar_admision', ':id') }}";
                            editUrl = editUrl.replace(':id', response[i]['admision_id']);
                            html += '<a href="' + editUrl + '" title="Editar Admisión" target="_blank">'+response[i]['admision_no']+'</a>'
                        }else{
                            html += '';
                        }
                        html += '</td>'
                        html += '<td colspan="1">'
                        switch (response[i]['estado']){
                            case 'A' : html += 'Activa'; break;
                            case 'R' : html += 'Realizada'; break;
                            case 'C' : html += 'Cancelada'; break;
                            case 'B' : html += 'Bloqueado'; break;
                            case 'Z' : html += 'Trasladado'; break;
                            case 'P' : html += 'Disponible'; break;
                            default : 'Disponible'; break;
                        }

                        html += '</td>'
                        html += '<td style="display:none;">'
                        html += response[i]['estado']
                        html += '</td>'
                        html += '<td style="display:none;">'
                        html += response[i]['observaciones'];
                        html += '</td>'
                        html += '<td style="display:none;">'
                        html += medico;
                        html += '</td>'
                        html += '<td style="display:none;">'
                        html += response[i]['paciente_en_clinica'];
                        html += '</td>'
                        html += '</tr>'
                    }
                    // $("#tbl"+result_sala+" tbody tr").remove();
                    $("#tbl"+result_sala+" tbody").append(html);
                    if (idRegistro !== null) {
                        // Buscamos la fila que tenga el ID guardado
                        let filaGuardada = $(`table[id^="tbl"] tbody tr`).filter(function() {
                            return $(this).find('#idRegistro').text().trim() === idRegistro.toString();
                        });

                        if (filaGuardada.length > 0) {
                            filaGuardada.addClass('table-active');
                            // Opcional: Hacer scroll hasta la fila si es una lista larga
                            // filaGuardada[0].scrollIntoView({ block: 'nearest' });
                        }
                    }
                    $("#nav-link-sala"+sala_activa).addClass("active");
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function getMedico(sel){aplicar_filtro();}
        function getFecha(sel){aplicar_filtro();}
        function getEstado(sel){aplicar_filtro();}

        //===========================================================================
        // Confirmar llegada de paciente
        //===========================================================================
        function confirmarPresencia(){
            if (typeof idRegistro !== 'undefined' && idRegistro !== null) {
                Swal.fire({
                        title: 'Confirmación',
                        text: 'Confirma el arribo de '+context_paciente_nombre,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745', // Color success de AdminLTE
                        cancelButtonColor: '#dc3545',  // Color danger de AdminLTE
                        confirmButtonText: 'Si Confirmado',
                        cancelButtonText: 'No',
                        allowEscapeKey: true,
                        reverseButtons: true // Opcional: pone el botón de confirmar a la derecha
                    }).then((result) => {
                        /* result.isConfirmed será verdadero si el usuario hizo clic en "Si Cerrar" */
                        if (result.isConfirmed) { 
                            $.ajax({
                                url: "{{ route('confirmar_ingreso') }}",
                                type: "POST",
                                dataType: 'json',
                                data: {
                                    "_token": "{{ csrf_token() }}", 
                                    cita_id: idRegistro
                                },
                                success: function(response){
                                    Swal.fire({
                                        title: response.type === 'success' ? 'Trabajo Finalizado' : 'Atención',
                                        text: response.message,
                                        icon: response.type // Asegúrate que tu backend envíe 'success', 'error', etc.
                                    }).then(() => {
                                        if(response.type === 'success') {
                                            aplicar_filtro();
                                            actualizarEstadoBotones(context_paciente_id);
                                        }
                                    });
                                },
                                error: function(error){
                                    console.log(error);
                                    Swal.fire('Error', 'No se pudo procesar la solicitud', 'error');
                                }
                            });
                        } 
                    });
            }else{
                Swal.fire({
                    title: 'Error',
                    text:  'Debe seleccionar un horario para continuar',
                    icon:  'error',
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                    customClass: {
                        confirmButton: 'btn btn-success'
                    },
                    buttonsStyling: false,
                });
            }
        }

        //===========================================================================
        // Crear Cita
        //===========================================================================
        function fnEditarCita(){
            if (typeof idRegistro !== 'undefined' && idRegistro !== null) {
                let idASeleccionar = (context_paciente_id != 'null' && context_paciente_id != '') ? context_paciente_id : '';
                fnActualizarPacientes(idASeleccionar);
                let fecha = $('#fecha_filtro').val();
                let fechaHora = fecha + "T" + context_hora;
                $('#fecha_cita').val(fechaHora);

                if (context_paciente_nombre != 'null') {
                    document.getElementById('edit_nombre_completo').value = context_paciente_nombre;
                }else{
                    document.getElementById('edit_nombre_completo').value = '';
                }
                if (context_paciente_telefonos != 'null') {
                    document.getElementById('edit_telefonos').value       = context_paciente_telefonos;
                }else{
                    document.getElementById('edit_telefonos').value       = null;
                }
                if (context_hospital_id != 'null') {
                    document.getElementById('edit_hospital_id').value = context_hospital_id;
                    $('#edit_hospital_id').change();
                }
                if (context_medico_id != 'null') {
                    document.getElementById('edit_medico_id').value   = context_medico_id;
                    $('#edit_medico_id').change();
                }
                if (context_observaciones != 'null') {
                    document.getElementById('edit_observaciones').value   = context_observaciones;
                }else{
                    document.getElementById('edit_observaciones').value   = null;
                }

                var inputs = document.querySelectorAll('#editarRegistroForm input, #editarRegistroForm select, #editarRegistroForm textarea');
                if (context_admision_no != '') {
                    inputs.forEach(function(input) {
                        input.disabled = true;
                    });
                }else{
                    if (context_agenda_estado == 'C' || context_agenda_estado == 'R') {
                        inputs.forEach(function(input) {
                            input.disabled = true;
                        });
                    }else{
                        inputs.forEach(function(input) {
                            input.disabled = false;
                        });
                    }

                    const formulario = $('#editarRegistro');
                    const btn = formulario.find('button[type="submit"]');
                    if (context_agenda_estado == 'C' || context_agenda_estado == 'R' || context_agenda_estado == 'B') {
                        btn.prop('disabled', true); 
                        btn.addClass('disabled'); // Clase de Bootstrap para refuerzo visual
                    }else{
                        btn.prop('disabled', false); 
                        btn.removeClass('disabled'); // Clase de Bootstrap para refuerzo visual
                    }
                }
                $('#editarRegistro').modal('show');
            }else{
                alert('Debe seleccionar un horario para continuar');
            }
        }

        //===========================================================================
        // Abrir Nueva Admisión
        //===========================================================================
        function fnCrearAdmision(){
            // alert(context_admision_no);
            if (typeof idRegistro !== 'undefined' && idRegistro !== null) {
                var inputs = document.querySelectorAll('#nuevaAdmisionModal input, #nuevaAdmisionModal select, #nuevaAdmisionModal textarea');
                const formulario = $('#nuevaAdmisionModal');
                const btn = formulario.find('button[type="submit"]');
                if (context_admision_no != '') {
                    btn.prop('disabled', true); 
                    btn.addClass('disabled'); // Clase de Bootstrap para refuerzo visual
                    inputs.forEach(function(input) {
                        input.disabled = true;
                    });
                }else{
                    if (context_agenda_estado == 'C' || context_agenda_estado == 'R' || context_agenda_estado == 'B'){
                        btn.prop('disabled', true); 
                        btn.addClass('disabled'); // Clase de Bootstrap para refuerzo visual
                        inputs.forEach(function(input) {
                            input.disabled = true;
                        });
                    }else{
                        btn.prop('disabled', false); 
                        btn.removeClass('disabled'); // Clase de Bootstrap para refuerzo visual
                        inputs.forEach(function(input) {
                            input.disabled = false;
                        });
                    }
                }
                    // if (context_admision_no != '') {
                    //     inputs.forEach(function(input) {
                    //         input.disabled = true;
                    //     });
                    //     if (context_agenda_estado == 'C' || context_agenda_estado == 'R' || context_agenda_estado == 'B'){
                    //         btn.prop('disabled', true); 
                    //         btn.addClass('disabled'); // Clase de Bootstrap para refuerzo visual
                    //     }else{
                    //         btn.prop('disabled', false); 
                    //         btn.removeClass('disabled'); // Clase de Bootstrap para refuerzo visual
                    //     }
                    // }else{
                    //     btn.prop('disabled', false); 
                    //     btn.removeClass('disabled'); // Clase de Bootstrap para refuerzo visual
                    //     if (context_agenda_estado == 'C' || context_agenda_estado == 'R' || context_agenda_estado == 'B'){
                    //         inputs.forEach(function(input) {
                    //             input.disabled = true;
                    //         });
                    //     }else{
                    //         inputs.forEach(function(input) {
                    //             input.disabled = false;
                    //         });
                    //     }
                    // }
                    
                    document.getElementById('adm_paciente_id').value = context_paciente_id;
                    $('#adm_paciente_id').change();
                    document.getElementById('adm_hospital_id').value = context_hospital_id;
                    $('#adm_hospital_id').change();
                    document.getElementById('adm_medico_id').value = context_medico_id;
                    $('#adm_medico_id').change();
                    $('#nuevaAdmisionModal').modal('show');
                // }
            }else{
                // alert('Debe seleccionar un horario para continuar');
                Swal.fire({
                    title: 'Error',
                    text:  'Debe seleccionar un horario para continuar',
                    icon:  'error',
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                    customClass: {
                        confirmButton: 'btn btn-success'
                    },
                    buttonsStyling: false,
                });
            }
        }

        function fnActualizarPacientes(idASeleccionar = null){
            let html = '';

            $.ajax({
                url: "{{ route('lista_pacientes') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}"
                      },
                success: function(response){
                    html += '<option value="">Paciente sin ficha...</option>';
                    for (var i = 0; i < response.length; i++) {
                        html += '<option value="'+response[i]['id']+'">'+response[i]['nombre_completo']+'</option>';
                    }
                    $("#edit_paciente_id").empty().append(html);

                    // IMPORTANTE: Asignar el valor AQUÍ, después de que el HTML existe
                    if(idASeleccionar){
                        $("#edit_paciente_id").val(idASeleccionar).trigger('change');
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function actualiza_nombre_completo(){
            if (document.getElementById('edit_paciente_id').value == '') {
                document.getElementById('edit_nombre_completo').value = '';
            }else{
                var paciente = document.getElementById('edit_paciente_id');
                var paciente_id = paciente.options[paciente.selectedIndex].value;
                var paciente_nombre = paciente.options[paciente.selectedIndex].text;
                document.getElementById('edit_nombre_completo').value = paciente_nombre;
                if (document.getElementById('edit_telefonos').value == '') {
                    $.ajax({
                        url: "{{ route('trae_telefonos_x_paciente') }}",
                        type: "POST",
                        dataType: 'json',
                        data: {"_token": "{{ csrf_token() }}",paciente_id : paciente_id},
                        success: function(response){
                            // console.log('recibi como respuesta '+response);
                            document.getElementById('edit_telefonos').value = response;
                        },
                        error: function(error){
                            console.log(error);
                        }
                    });
                }
            }
        }

        // ******************************************************************** //
        // ***** antes de enviar el formulario se agrega el id de la cita ***** //
        // ******************************************************************** //
        $('#editarRegistroForm').on('submit', function(e) {
            e.preventDefault(); // Detiene la recarga de la página

            // Obtenemos los datos del formulario
            var formData = $(this).serializeArray();
            
            // Agregamos el ID de la cita que no está en el HTML
            formData.push({ name: "agenda_id", value: context_agenda_id });

            const btn = $(this).find('button[type="submit"]');
    
            // Lo deshabilitamos y cambiamos el icono por un spinner (opcional)
            btn.prop('disabled', true);
            btn.html('<i class="fas fa-spinner fa-spin"></i>')

            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: formData,
                dataType: 'json', // Esperamos una respuesta JSON
                success: function(response) {
                    // Cerramos el modal
                    $('#editarRegistro').modal('hide');

                    // Mostramos la alerta de éxito
                    Swal.fire({
                        title: "Trabajo Finalizado",
                        text: response.message,
                        icon: response.type,
                        confirmButtonText: 'Aceptar'
                    });

                    // Refrescamos solo la tabla (tu función existente)
                    aplicar_filtro(); 
                    actualizarEstadoBotones();
                },
                error: function(jqXHR) {
                    var errorMsg = 'Hubo un problema al actualizar el registro.';
                    if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        errorMsg = jqXHR.responseJSON.message;
                    }
                    
                    Swal.fire({
                        title: "Error",
                        text: errorMsg,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        });


        $('#admisionForm').on('submit', function(e){
            e.preventDefault(); // Detiene la recarga de la página

            var disabledInputs = $(this).find(':disabled');
            disabledInputs.prop('disabled', false);

            // Obtenemos los datos del formulario
            var formData = $(this).serializeArray();

            disabledInputs.prop('disabled', true);
            
            // Agregamos el ID de la cita que no está en el HTML
            formData.push({ name: "agenda_id", value: context_agenda_id });
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: formData,
                dataType: 'json', // Esperamos una respuesta JSON
                success: function(response) {
                    // Cerramos el modal
                    $('#nuevaAdmisionModal').modal('hide');

                    // Mostramos la alerta de éxito
                    Swal.fire({
                        title: "Trabajo Finalizado",
                        text: response.message,
                        icon: response.type,
                        confirmButtonText: 'Aceptar'
                    });

                    aplicar_filtro();
                    actualizarEstadoBotones();
                },
                error: function(jqXHR) {
                    var errorMsg = 'Hubo un problema al actualizar el registro.';
                    if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        errorMsg = jqXHR.responseJSON.message;
                    }
                    
                    Swal.fire({
                        title: "Error",
                        text: errorMsg,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        });

        //===========================================================================
        // Abrir Bloqueo de horario
        //===========================================================================
        function fnBloqueo(){
            if (typeof idRegistro !== 'undefined' && idRegistro !== null) {
                $('#bloqueoModal').modal('show');
            }else{
                // alert('Debe seleccionar un horario para continuar');
                Swal.fire({
                    title: 'Error',
                    text:  'Debe seleccionar un horario para continuar',
                    icon:  'error',
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                    customClass: {
                        confirmButton: 'btn btn-success'
                    },
                    buttonsStyling: false,
                });
            }
        }

        //=====================================================================
        // Grabar Bloqueo de horario
        //=====================================================================
        $(function(){
            $("#bloqueoForm").submit(function(){
                var cita_id         = context_agenda_id;
                var observaciones   = document.getElementById('bloqueo_espacio_observaciones').value;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('bloquear_espacio') }}",
                    method: "POST",
                    data: {cita_id         : cita_id,
                           observaciones   : observaciones
                    },
                    success: function(response){
                        Swal.fire({
                            title: 'Trabajo Finalizado',
                            text:  response,
                            icon:  'success',
                            confirmButtonText: "Aceptar",
                            confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                            customClass: {
                                confirmButton: 'btn btn-success'
                            },
                            buttonsStyling: false,
                        }).then((result) => {
                            if (result.isConfirmed) { 
                                aplicar_filtro();
                                actualizarEstadoBotones();
                            } 
                        });
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            });
        });

        //=====================================================================
        // Marcar registro como finalizado
        //=====================================================================
        function fnFinalizar(){
            Swal.fire({
                title: 'Confirmación',
                text: "¿Confirmas que deseas finalizar la cita?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745', // Color success de AdminLTE
                cancelButtonColor: '#dc3545',  // Color danger de AdminLTE
                confirmButtonText: 'Si Finalizar',
                cancelButtonText: 'No',
                allowEscapeKey: true,
                reverseButtons: true // Opcional: pone el botón de confirmar a la derecha
            }).then((result) => {
                if (result.isConfirmed) { 
                    $.ajax({
                        url: "{{ route('realizar_cita') }}",
                        type: "POST",
                        async: true,
                        data: {"_token": "{{ csrf_token() }}", 
                               cita_id: idRegistro, 
                               observaciones: 'Prueba cancelacion de cita'
                              },
                        success: function(response){
                            var info = response;
                            Swal.fire({
                                title: response.type === 'success' ? '¡Logrado!' : 'Atención',
                                text: response.message,
                                icon: response.type,
                                showConfirmButton: true,
                                confirmButtonText: 'Aceptar'
                            }).then((result) => {
                                actualizarEstadoBotones();
                                aplicar_filtro();
                            });
                        },
                        error: function(error){
                            console.log(error);
                        }
                    });
                } 
            });
        }

        //=====================================================================
        // Marcar registro como cancelado
        //=====================================================================
        function fnCancelar(){
            Swal.fire({
                title: 'Confirmación',
                text: "Seguro de Cancelar la Cita ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745', // Color success de AdminLTE
                cancelButtonColor: '#dc3545',  // Color danger de AdminLTE
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                allowEscapeKey: true,
                reverseButtons: true // Opcional: pone el botón de confirmar a la derecha
            }).then((result) => {
                if (result.isConfirmed) { 
                    $.ajax({
                        url: "{{ route('cancelar_cita') }}",
                        type: "POST",
                        async: true,
                        data: {"_token": "{{ csrf_token() }}", 
                               cita_id: idRegistro, 
                               observaciones: 'Prueba cancelacion de cita'
                              },
                        success: function(response){
                            var info = response;
                            Swal.fire({
                                title: response.type === 'success' ? '¡Logrado!' : 'Atención',
                                text: response.message,
                                icon: response.type,
                                showConfirmButton: true,
                                confirmButtonText: 'Aceptar'
                            }).then((result) => {
                                actualizarEstadoBotones();
                                aplicar_filtro();
                            });
                        },
                        error: function(error){
                            console.log(error);
                        }
                    });
                } 
            });
        }

        //=====================================================================
        // historico de pacientes
        //=====================================================================
        function fnHistorico(){
            $.ajax({
                url: "{{ route('paciente_citas') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}", 
                       paciente_id: context_paciente_id
                      },
                success: function(response){
                    let html = '';
                    for(var i = 0; i < response.length; i++){
                        html += '<tr class="text-center" style="font-size: 12px;">';
                        html += '<td>'+response[i]['fecha_inicio']+'</td>'
                        html += '<td>'
                        switch (response[i]['estado']){
                            case 'A' : html += 'Activa'; break;
                            case 'R' : html += 'Realizada'; break;
                            case 'C' : html += 'Cancelada'; break;
                            case 'B' : html += 'Bloqueado'; break;
                            case 'Z' : html += 'Trasladado'; break;
                            case 'P' : html += 'Disponible'; break;
                            default : 'Disponible'; break;
                        }
                        html += '</td>'
                        html += '<td>';
                        if (response[i]['admision_no'] != null) {
                            html += response[i]['admision_no']
                        }
                        html += '</td>'
                        html += '<td>'
                        if (response[i]['admision_no'] != null) {
                            html += response[i]['tipo_atencion'];
                        }
                        html += '</td>'
                        html += '<td>'+response[i]['nombre_completo']+'</td>'
                        html += '<td>'+response[i]['nombre']+'</td>'
                        html += '</tr>';    
                    }
                    $("#tblHistorico tbody tr").remove();
                    $("#tblHistorico tbody").append(html);
                    $('#historicoModal').modal('show');
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

    </script>
@endsection