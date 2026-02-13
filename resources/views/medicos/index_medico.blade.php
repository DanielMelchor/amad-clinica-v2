@extends('adminlte::page')

@section('title', 'Dashboard Médico')
@section('css')
	<style type="text/css">
		.table-responsive {
            max-width: 100%; /* Ajusta el ancho según tus necesidades */
            overflow-x: auto; /* Permite el desplazamiento horizontal */
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
            <div class="col-12 col-lg-10 order-1 order-lg-1">
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
		var asset       = '{{ asset('') }}'


        document.addEventListener("DOMContentLoaded", function () {
            
        });

        function replacer(val) {
            if ( val === null ) 
            { 
                return ""; // change null to empty string
            } else {
                return val; // return unchanged
            }
        }

        function actualizarTabla(){
            medico_id = $('#medico_id').val();
            fecha_proceso = $('#fecha_proceso').val();
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
                    console.log(response);
                    // document.getElementById('total_agenda').textContent = response[0]['total_agendados'];
                    // document.getElementById('agenda_presentes').textContent = response[0]['presentes'];
                    // document.getElementById('agenda_ausentes').textContent = response[0]['ausentes'];
                    document.getElementById('finalizados').textContent = response[1]['finalizados'];
                    document.getElementById('pendientes').textContent = response[1]['pendientes'];
                    // 1. Destruir la instancia actual de DataTable para poder reinicializarla
                    if ($.fn.DataTable.isDataTable('#tblPrincipal')) {
                        $('#tblPrincipal').DataTable().destroy();
                    }

                    var html = '';
                    for (var i = 0; i < response[2].length; i++) {
                        // Validamos que el tiempo de espera no sea nulo
                        let espera = response[2][i]['tiempo_espera'] ? response[2][i]['tiempo_espera'] : '00:00';
                        
                        if (response[2][i]['paciente_en_clinica'] == 1) {
                            html += '<tr style="background-color: #E8FDFA;">';
                        }else{
                            html += '<tr>';
                        }
                        
                        html += '  <td>' + response[2][i]['sala_nombre'] + '</td>';
                        html += '  <td>' + response[2][i]['horario'] + '</td>';
                        html += '  <td>' + response[2][i]['nombre_completo'] + '</td>';
                        html += '  <td class="text-danger font-weight-bold">' + espera + '</td>'; // Resaltamos la espera
                        html += '  <td>' + (response[2][i]['observaciones'] || '') + '</td>';
                        html += '  <td>'
                        if (response[2][i]['paciente_id'] != null && response[2][i]['admision_no'] !=  null){
                            html += '<a href="'+asset+'medicos/nueva_admision/'+response[2][i]['paciente_id']+'/A" data-toggle="tooltip" data-placement="top" title="'+replacer(response[2][i]['observaciones'])+'" target="_blank">'+response[2][i]['admision_no']+'</a>';
                        }

                        html += '  </td>';
                        html += '</tr>';
                    }

                    // 2. Limpiar y añadir el nuevo HTML
                    $('#tblPrincipal tbody').html(html);

                    // 3. Inicializar DataTable con configuración Mobile-First
                    $('#tblPrincipal').DataTable({
                        "responsive": true,  // Hace que la tabla sea amigable en móviles
                        "autoWidth": false,
                        "columnDefs": [
                            { "width": "20%", "targets": 0 }, // Sala
                            { "width": "10%", "targets": 1 }, // Horario
                            { "width": "20%", "targets": 2 }, // Paciente
                            { "width": "15%", "targets": 3 }, // Espera
                            { "width": "25%", "targets": 4 }, // Observaciones
                            { "width": "10%", "targets": 5 }  // Acciones (si la hay)
                        ],
                        "order": [[3, "desc"]], // Ordenar por la segunda columna (horario)
                        // "language": {
                        //     "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" // Idioma español
                        // },
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
                        // "dom": 'Bfrtip', // Opcional: agrega botones si tienes la extensión
                        "dom": '<"row"<"col-sm-4"l><"col-sm-4 text-center"B><"col-sm-4"f>>rtip', // Ajuste para disposición
                        "buttons": [
                            {
                                extend: 'excelHtml5',
                                text: 'Excel',
                                className: 'btn btn-md btn-default'
                            }
                        ]
                    });
                },
                error: function(xhr) {
                    console.error('Error al cargar citas:', xhr);
                }
            });
        }
	</script>
@endsection