@extends('adminlte::page')

@section('title', 'Dashboard Médico')
@section('css')
    <style type="text/css">
        /* Ajuste de contenedores para pantallas táctiles */
        .btn-md { padding: 0.5rem 1rem; font-size: 1rem; }
        
        @media (max-width: 768px) {
            /* Ocultar encabezados de tabla */
            #tblPrincipal thead { display: none; }

            /* Cada fila se convierte en una "tarjeta" */
            #tblPrincipal tr {
                display: block;
                margin-bottom: 1.2rem;
                border: 1px solid #dee2e6;
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                background: #fff;
                padding: 10px;
            }

            /* Cada celda se convierte en una línea con etiqueta */
            #tblPrincipal td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                text-align: right !important;
                padding: 0.6rem 0.5rem !important;
                border-top: 1px solid #f2f2f2 !important;
                width: 100% !important;
            }

            #tblPrincipal td:first-child { border-top: none !important; }

            /* Inserción de etiquetas móviles usando data-label */
            #tblPrincipal td:before {
                content: attr(data-label);
                font-weight: bold;
                text-align: left;
                color: #495057;
                flex: 1;
                font-size: 0.85rem;
                text-transform: uppercase;
            }

            /* El badge de espera ocupa más espacio en móvil para ser legible */
            .badge { font-size: 0.9rem; padding: 0.5rem !important; }
        }

        /* Animación opcional para tiempos críticos */
        .badge-danger { animation: pulse-red 2s infinite; }
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <br>
        <div class="row">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-calendar-alt mr-1"></i> Fecha</span>
                    </div>
                    <input type="date" class="form-control" id="fecha_proceso" name="fecha_proceso" autofocus required value="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user-md mr-1"></i> Médico</span>
                    </div>
                    <select id="medico_id" name="medico_id" class="form-control" @role('Medico') style="pointer-events: none; background-color: #e9ecef;" @endrole>
                        <option value="">Seleccionar...</option>
                        @foreach($medicos as $medico)
                            <option value="{{ $medico->id }}" @if(auth()->user()->medico_id == $medico->id) selected @endif>{{ $medico->nombre_completo }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-1 col-lg-1 mb-3">
                <a href="#" class="btn btn-md btn-outline-primary rounded elevation-4 w-100 d-flex align-items-center justify-content-center" onclick="actualizarTabla();">
                    <i class="fas fa-retweet"></i>
                    <span class="d-md-none ml-2">Actualizar</span> </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-12 order-1 order-lg-1">
                <div class="row">
                    <div class="col-6 col-md-3 mb-2">
                        <div class="small-box shadow-sm" style="background-color: /*#C9DFEE*/#EDFBFF; color: #2c3e50;">
                            <div class="inner">
                                <h3 id="promedio">00:00</h3>
                                <p>Promedio</p>
                            </div>
                            <div class="icon"><i class="fas fa-stopwatch"></i></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div class="small-box shadow-sm" style="background-color: #AFCFAB; color: #2c3e50;">
                            <div class="inner">
                                <h3 id="finalizados">8</h3>
                                <p>Finalizados</p>
                            </div>
                            <div class="icon"><i class="fas fa-check-circle"></i></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div class="small-box shadow-sm" style="background-color: #CFCFAB; color: #2c3e50;">
                            <div class="inner">
                                <h3 id="pendientes">12</h3>
                                <p>Pendientes</p>
                            </div>
                            <div class="icon"><i class="fas fa-list-ol"></i></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div class="small-box shadow-sm" style="background-color: #CFAEAB; color: #2c3e50;">
                            <div class="inner">
                                <h3 id="cancelados">2</h3>
                                <p>Cancelados</p>
                            </div>
                            <div class="icon"><i class="fas fa-ban"></i></div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover text-center mb-0" id="tblPrincipal" style="font-size: 12px;">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Sala</th>
                                        <th>Horario</th>
                                        <th>Nombre</th>
                                        <th>Espera</th>
                                        <th>Motivo</th>
                                        <th>Admisión</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-2 order-2 order-lg-1">
                <!-- <div class="row">
                    <div class="col-6 col-md-12 mb-2">
                        <div class="small-box shadow-sm" style="background-color: #DCF1C6; color: #2c3e50;">
                            <div class="inner">
                                <h3 id="total_agenda">0</h3>
                                <p>Agendados</p>
                            </div>
                            <div class="icon"><i class="fas fa-user-injured"></i></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-12 mb-2">
                        <div class="small-box shadow-sm" style="background-color: #EAF7DF; color: #2c3e50;">
                            <div class="inner">
                                <h3 id="agenda_presentes">0</h3>
                                <p>Presentes</p>
                            </div>
                            <div class="icon"><i class="fas fa-user-injured"></i></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-12 mb-2">
                        <div class="small-box shadow-sm" style="background-color: #F7E1DF; color: #2c3e50;">
                            <div class="inner">
                                <h3 id="agenda_ausentes">0</h3>
                                <p>ausentes</p>
                            </div>
                            <div class="icon"><i class="fas fa-user-injured"></i></div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        var asset = '{{ asset('') }}';

        $(document).ready(function() {
            // Carga inicial automática
            actualizarTabla();
            
            // Iniciar el cronómetro
            setInterval(refrescarTiemposVisuales, 60000);
        });

        function actualizarTabla() {
            let medico_id = $('#medico_id').val();
            let fecha_proceso = $('#fecha_proceso').val();

            if(!medico_id) return;

            $.ajax({
                url: "{{ route('trae_citas_x_medico') }}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    medico_id: medico_id,
                    fecha: fecha_proceso,
                    estado: 'A'
                },
                success: function(response) {
                    // Actualizar contadores superiores
                    $('#finalizados').text(response[1]['finalizados'] || 0);
                    $('#pendientes').text(response[1]['pendientes'] || 0);

                    if ($.fn.DataTable.isDataTable('#tblPrincipal')) {
                        $('#tblPrincipal').DataTable().destroy();
                    }

                    var html = '';
                    // response[2] contiene el listado de agendas del query de Eloquent
                    response[2].forEach(item => {
                        // Dentro del loop forEach de response[2]
                        let espera = item.tiempo_espera || '00:00';

                        // 1. Definimos si el tiempo debe estar detenido (Atención iniciada)
                        let detenido = (item.admision && item.admision.atencion_medica != 0) ? 'si' : 'no';

                        // 2. Lógica de colores de alerta (Badge)
                        let badgeClass = 'badge-success';
                        let partes = espera.split(':');
                        if (parseInt(partes[0]) > 0 || parseInt(partes[1]) > 30) badgeClass = 'badge-danger';
                        else if (parseInt(partes[1]) > 15) badgeClass = 'badge-warning';

                        // 3. Construcción del HTML integrado
                        html += `
                            <tr ${item.paciente_en_clinica == 1 ? 'style="background-color: #E8FDFA;"' : ''}>
                                <td data-label="Sala">${item.sala.sala_nombre}</td>
                                <td data-label="Horario">${item.horario}</td>
                                <td data-label="Nombre">
                                    <strong>${item.nombre_completo}</strong>
                                </td>
                                <td data-label="Espera">
                                    <span class="badge ${badgeClass} p-2 shadow-sm">
                                        <i class="far fa-clock mr-1"></i>
                                        <span class="tiempo-texto" data-detenido="${detenido}">${espera}</span>
                                    </span>
                                </td>
                                <td data-label="Motivo">${item.observaciones || ''}</td>
                                <td data-label="Admisión">
                                    ${item.admision && item.admision.admision_no 
                                        ? `<a href="${asset}medicos/nueva_admision/${item.paciente_id}/A" target="_blank" class="btn btn-xs btn-primary">${item.admision.admision_no}</a>` 
                                        : '<span class="text-muted small">Sin admisión</span>'}
                                </td>
                            </tr>`;
                    });

                    $('#tblPrincipal tbody').html(html);

                    // Re-inicializar DataTable con soporte móvil
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
                }
            });
        }

        function refrescarTiemposVisuales() {
            $('.tiempo-texto').each(function() {
                let textoActual = $(this).text();
                let partes = textoActual.split(':');
                if(partes.length < 2) return;

                let horas = parseInt(partes[0]);
                let minutos = parseInt(partes[1]);

                minutos++;
                if (minutos >= 60) {
                    minutos = 0;
                    horas++;
                }

                let nuevoTiempo = 
                    (horas < 10 ? '0' + horas : horas) + ':' + 
                    (minutos < 10 ? '0' + minutos : minutos);
                
                $(this).text(nuevoTiempo);

                // Cambio dinámico de color si el paciente espera demasiado
                let badge = $(this).closest('.badge');
                if (horas > 0 || minutos > 30) {
                    badge.removeClass('badge-success badge-warning').addClass('badge-danger');
                } else if (minutos > 15) {
                    badge.removeClass('badge-success').addClass('badge-warning');
                }
            });
        }
    </script>
@endsection