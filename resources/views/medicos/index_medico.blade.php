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
        <div class="row justify-content-end">
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
                    <i class="fas fa-search"></i>
                    <span class="d-md-none ml-2">Actualizar</span> </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-3 order-2 order-lg-1">
                <div class="row">
                    <div class="col-12 col-sm-4 col-lg-12 mb-2">
                        <div class="small-box shadow-sm" style="background-color: #C9DFEE; color: #2c3e50;">
                            <div class="inner">
                                <h3 id="estimado">00:00</h3>
                                <p>Estimado</p>
                            </div>
                            <div class="icon"><i class="fas fa-clock"></i></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 col-lg-12 mb-2">
                        <div class="small-box shadow-sm" style="background-color: #C9DFEE; color: #2c3e50;">
                            <div class="inner">
                                <h3 id="promedio">00:00</h3>
                                <p>Promedio</p>
                            </div>
                            <div class="icon"><i class="fas fa-stopwatch"></i></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 col-lg-12 mb-2">
                        <div class="small-box shadow-sm" style="background-color: #C9DFEE; color: #2c3e50;">
                            <div class="inner">
                                <h3 id="actual">00:00</h3>
                                <p>Actual</p>
                            </div>
                            <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-9 order-1 order-lg-2">
                <div class="row">
                    <div class="col-6 col-md-4 mb-2">
                        <div class="small-box bg-success shadow-sm">
                            <div class="inner">
                                <h3 id="finalizados">8</h3>
                                <p>Finalizados</p>
                            </div>
                            <div class="icon"><i class="fas fa-check-circle"></i></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 mb-2">
                        <div class="small-box bg-warning shadow-sm">
                            <div class="inner">
                                <h3 id="pendientes">12</h3>
                                <p>Pendientes</p>
                            </div>
                            <div class="icon"><i class="fas fa-list-ol"></i></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mb-2">
                        <div class="small-box bg-danger shadow-sm">
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
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>

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
                    // 1. Destruir la instancia actual de DataTable para poder reinicializarla
                    if ($.fn.DataTable.isDataTable('#tblPrincipal')) {
                        $('#tblPrincipal').DataTable().destroy();
                    }

                    var html = '';
                    for (var i = 0; i < response.length; i++) {
                        // Validamos que el tiempo de espera no sea nulo
                        let espera = response[i]['tiempo_espera'] ? response[i]['tiempo_espera'] : '00:00';
                        
                        html += '<tr>';
                        html += '  <td>' + response[i]['sala_nombre'] + '</td>';
                        html += '  <td>' + response[i]['horario'] + '</td>';
                        html += '  <td>' + response[i]['nombre_completo'] + '</td>';
                        html += '  <td class="text-danger font-weight-bold">' + espera + '</td>'; // Resaltamos la espera
                        html += '  <td>' + (response[i]['observaciones'] || '') + '</td>';
                        html += '  <td>'
                        if (response[i]['paciente_id'] != null){
                            html += '<a href="'+asset+'medicos/nueva_admision/'+response[i]['paciente_id']+'/A" data-toggle="tooltip" data-placement="top" title="'+replacer(response[i]['observaciones'])+'" target="_blank"><i class="fas fa-eye"></i></a>';
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
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" // Idioma español
                        },
                        "dom": 'Bfrtip', // Opcional: agrega botones si tienes la extensión
                        "pageLength": 10
                    });
                },
                error: function(xhr) {
                    console.error('Error al cargar citas:', xhr);
                }
            });
        }
	</script>
@endsection