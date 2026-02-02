@extends('adminlte::page')
@section('css')
    <!-- Select2 -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
    <style>
        .enlace_desactivado {
            pointer-events: none;
            cursor: default;
        }
    </style>
@endsection

@section('title', 'Agenda')

@section('content_header')
    <h3>Agenda</h3>
@endsection

@section('content')    
    <div class="row">
        <div class="col">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <form role="form" method="POST" action="{{ route('actualizar_nueva_agenda', $cita->id)}}">
        @CSRF
        <div class="card card-navy">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4 offset-md-8" style="text-align: right;">
                        @if( $cita->estado == 'P')
                            <button type="submit" class="btn btn-sm btn-success" title="Grabar cambios"><i class="fas fa-save"></i></button>
                        @endif
                        <!--<a href="{{ route('nueva_agenda',[$cita->medico_id, 'T', $fecha]) }}" class="btn btn-sm btn-danger" title="Regresar a agenda"><i class="fas fa-sign-out-alt"></i></a> -->
                        <a href="#" class="btn btn-sm btn-danger" title="Regresar a agenda" onclick="confirma_salida({{ $cita->id }}); return false;"><i class="fas fa-sign-out-alt"></i></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <input type="hidden" id="cita_id" name="cita_id" value="{{ $cita->id }}">
                @empty($admision->id)
                    <input type="hidden" id="admision_id" name="admision_id" value="0">
                @else
                    <input type="hidden" id="admision_id" name="admision_id" value="{{ $admision->id }}">
                @endempty
                <input type="hidden" id="maestro_protocolo_id" name="maestro_protocolo_id" value="{{ $cita->maestro_protocolo_id }}">
                <input type="hidden" id="cita_estado" name="cita_estado" value="{{ $cita->estado }}">
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="input-group mb-1 input-group-sm col-md-3 offset-md-1">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">Fecha</label>
                                </div>
                                <input type="date" class="form-control" id="fecha_cita" name="fecha_cita" required value="{{ $fecha }}">
                            </div>
                            <div class="input-group mb-1 input-group-sm col-md-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">Hora Inicio</label>
                                </div>
                                <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required value="{{ $hora_inicio }}">
                            </div>
                            <div class="input-group mb-1 input-group-sm col-md-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">Hora Fin</label>
                                </div>
                                <input type="time" class="form-control" id="hora_final" name="hora_final" required value="{{ $hora_final }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-group mb-1 input-group-sm col-md-5 offset-md-1">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="paciente_id">Paciente</label>
                                </div>
                                <select class="form-control input-group input-group select2 select2bs4" id="paciente_id"  name="paciente_id" onchange="actualiza_nombre_completo();">
                                    <option value="">Seleccionar...</option>
                                    @foreach($pacientes as $p)
                                        <option value="{{ $p->id }}" @if($cita->paciente_id == $p->id) selected @endif> {{ $p->nombre_completo}} </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <a href="{{ route('crear_paciente',['A', $cita->id])}}" class="btn btn-sm btn-primary" title="Crear Paciente"><i class="fas fa-plus-circle"></i></a>
                                </div>
                            </div>

                            <div class="input-group mb-1 input-group col-md-5">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="nombre_completo">Nombre</label>
                                </div>
                                <input type="text" class="form-control input-group input-group-sm" id="nombre_completo" name="nombre_completo" required value="{{ $cita->nombre_completo }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-group mb-1 input-group-sm col-md-5 offset-md-1">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">Telefonos</label>
                                </div>
                                <input type="text" class="form-control" id="telefonos" name="telefonos" required value="{{ $cita->telefonos }}">
                            </div>
                        </div>


                        <div class="row">
                            <div class="mb-1 col-md-5 offset-md-1">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="hospital_id">Lugar</label>
                                    </div>
                                    <select class="custom-select-sm form-control select2 select2bs4" id="hospital_id"  name="hospital_id">
                                        <option value="">Seleccionar...</option>
                                        @foreach($hospitales as $h)
                                            <option value="{{ $h->id }}" @if($cita->hospital_id == $h->id) selected @else @if($h->principal_agenda == 'S') selected @endif @endif> {{ $h->nombre }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-1 col-md-5">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="medico_id">Medico</label>
                                    </div>
                                    <select class="custom-select-sm form-control select2 select2bs4" id="medico_id"  name="medico_id">
                                        <option value="">Seleccionar...</option>
                                        @foreach($medicos as $m)
                                            <option value="{{ $m->id }}" @if($m->id == $cita->medico_id) selected @else @if($m->principal == 'S') selected @endif @endif> {{ $m->nombre_completo }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="form-group col-md-10 offset-md-1">
                                <label for="antmedico_descripcion">Observaciones</label>
                                <textarea class="form-control form-control-sm" id="observaciones" name="observaciones" rows="3" maxlength="190">{{ $cita->observaciones }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-dark mb-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="clearfix">
                                            <h4 class="float-left">Admisión</h4>
                                            @empty( $admision->id )
                                                <h4 class="float-right">0</h4>
                                            @else
                                                <h4 class="float-right">{{ $admision->admision }}</h4>
                                            @endempty
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <a id="btn_admision" href="#" class="btn btn-block btn-primary" onclick="admision(); return false;" title="Crear Admisión"><i class="fas fa-plus-circle"></i>&nbsp; Crear Admisión</a>
                                <a id="btn_bloqueo" href="#" onclick="show_bloqueo(); return false;" class="btn btn-block btn-secondary" title="bloquer horario" disabled="disabled"><i class="fas fa-lock"></i>&nbsp; Bloquear horario</a>
                                <a id="btn_cancelada" href="#" onclick="mensaje('cancelar');" class="btn btn-block btn-danger" title="Marcar cita como cancelada"><i class="fas fa-ban"></i>&nbsp; Marcar cita como cancelada</a>
                                <a id="btn_realizada" href="#" onclick="mensaje('realizar');" class="btn btn-block btn-success" title="Marcar cita como realizada"><i class="fas fa-check-circle"></i>&nbsp; Marcar cita como realizada</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- bloqueo -->
    <div class="modal fade" id="bloqueoModal" role="dialog" aria-labelledby="admisionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="bloqueoForm" name="bloqueo" action="#">
                    @csrf
                    <div class="card card-navy">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6>Bloquear horario</h6>
                                </div>
                                <div class="col-md-4" style="text-align: right;">
                                    <button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" title="Salir"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="form-group col-md-10 offset-md-1">
                                    <label for="bloqueo_observaciones">Observaciones</label>
                                    <textarea class="form-control form-control-sm" id="bloqueo_observaciones" name="bloqueo_observaciones" rows="3" maxlength="190" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /bloqueo -->
    <!-- Modal -->
    <div class="modal fade" id="admisionModal" role="dialog" aria-labelledby="admisionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <!--<form class="form-horizontal" id="crea_admision" name="crea_admision" action="#"> -->
                <div class="modal-content">
                    <div class="card card-navy">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6>Crear Admisión</h6>
                                </div>
                                <div class="col-md-4" style="text-align: right;">
                                    <button type="button" class="btn btn-sm btn-success" title="Grabar" onclick="grabar_admision(); return false;"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" title="Salir"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="form-group form-control-sm clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="consulta" name="tipo_admision" value="C" checked>
                                            <label for="consulta">Consulta</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="procedimiento" name="tipo_admision" value="P">
                                            <label for="procedimiento">Procedimiento</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="hospitalizacion" name="tipo_admision" value="H">
                                            <label for="hospitalizacion">Hospitalización</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- admision terceros -->
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Admisión Terceros</span>
                                    </div>
                                    <input type="text" class="form-control" id="admision_tercero" name="admision_tercero">
                                </div>
                            </div>
                            <!-- /admision terceros -->
                            <!-- aseguradora -->
                            <div class="row">
                                <div class="mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" for="aseguradora_id">Aseguradora</span>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="aseguradora_id" name="aseguradora_id">
                                            <option value="">Seleccionar.....</option>
                                            @foreach($aseguradoras as $aseguradora)
                                                    <option value="{{ $aseguradora->id }}"> {{ $aseguradora->nombre}} </option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /aseguradora -->
                            <!-- poliza -->
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Póliza No.</span>
                                    </div>
                                    <input type="text" class="form-control" id="poliza_no" name="poliza_no">
                                </div>
                            </div>
                            <!-- /poliza -->
                            <!-- autorizacion -->
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Autorización No.</span>
                                    </div>
                                    <input type="text" class="form-control" id="aseguradora_aut_no" name="aseguradora_aut_no">
                                </div>
                            </div>
                            <!-- /autorizacion -->
                            <!-- pagado en su totalidad por la aseguradora -->
                            <div class="row">
                                <div class="col-md-10 offset-md-1 mb-1">
                                    <div class="form-group form-control-sm clearfix">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="pago_aseguradora" name="pago_aseguradora" value="S" readonly onclick="control_otros(this.value);">
                                            <label class="form-check-label" for="pago_aseguradora">
                                                Pagado en su totalidad por la Aseguradora
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /pagado en su totalidad por la aseguradora -->
                            <div class="row">
                                <!-- deducible -->
                                <div class="input-group mb-1 input-group-sm col-md-5 offset-md-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Deducible</span>
                                    </div>
                                    <input type="number" class="form-control" id="deducible" name="deducible" placeholder="0.00" style="text-align: right;">
                                </div>
                                <!-- /deducible -->
                                <!-- co pago -->
                                <div class="input-group mb-1 input-group-sm col-md-5">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Co pago</span>
                                    </div>
                                    <input type="number" class="form-control" id="copago" name="copago" placeholder="0.00" style="text-align: right;">
                                </div>
                                <!-- /co pago -->
                            </div>
                        </div>
                    </div>
                </div>
            <!-- </form> -->
        </div>
    </div>
@endsection
@section('js')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
    @if(Session::has('success'))
        <script>
            swal("Trabajo Finalizado", "{!! Session::get('success') !!}", "success")
        </script>
    @endif
    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        });

        let marcado = 'N';

        //========================================================================
        // al cargar la pantalla
        //========================================================================
        window.onload = function() {
            var cita_estado = document.getElementById('cita_estado').value;
            var admision_id = document.getElementById('admision_id').value;
            var paciente_id = document.getElementById('paciente_id').value;
            var nombre_completo = document.getElementById('nombre_completo').value;

            switch (cita_estado) {
                case 'P':
                    document.getElementById('fecha_cita').disabled = false;
                    document.getElementById('hora_inicio').disabled = false;
                    document.getElementById('hora_final').disabled = false;
                    document.getElementById('paciente_id').disabled = false;
                    document.getElementById('nombre_completo').disabled = false;
                    document.getElementById('telefonos').disabled = false;
                    document.getElementById('hospital_id').disabled = false;
                    document.getElementById('medico_id').disabled = false;
                    document.getElementById('observaciones').disabled = false;
                    if (paciente_id.length != 0 || nombre_completo != 0) {
                        var elemento = document.getElementById("btn_bloqueo");
                        elemento.className += " enlace_desactivado";
                    }
                    /*var elemento = document.getElementById("btn_cancelada");
                    elemento.className += " enlace_desactivado";
                    var elemento = document.getElementById("btn_realizada");
                    elemento.className += " enlace_desactivado";*/
                    break;
                case 'A':
                    document.getElementById('fecha_cita').disabled = true;
                    document.getElementById('hora_inicio').disabled = true;
                    document.getElementById('hora_final').disabled = true;
                    document.getElementById('paciente_id').disabled = true;
                    document.getElementById('nombre_completo').disabled = true;
                    document.getElementById('telefonos').disabled = true;
                    document.getElementById('hospital_id').disabled = true;
                    document.getElementById('medico_id').disabled = true;
                    document.getElementById('observaciones').disabled = true;
                    var elemento = document.getElementById("btn_bloqueo");
                    elemento.className += " enlace_desactivado";
                    var elemento = document.getElementById("btn_admision");
                    elemento.className += " enlace_desactivado";
                    break;
                case 'C':
                    document.getElementById('fecha_cita').disabled = true;
                    document.getElementById('hora_inicio').disabled = true;
                    document.getElementById('hora_final').disabled = true;
                    document.getElementById('paciente_id').disabled = true;
                    document.getElementById('nombre_completo').disabled = true;
                    document.getElementById('telefonos').disabled = true;
                    document.getElementById('hospital_id').disabled = true;
                    document.getElementById('medico_id').disabled = true;
                    document.getElementById('observaciones').disabled = true;
                    var elemento = document.getElementById("btn_bloqueo");
                    elemento.className += " enlace_desactivado";
                    var elemento = document.getElementById("btn_admision");
                    elemento.className += " enlace_desactivado";
                    var elemento = document.getElementById("btn_cancelada");
                    elemento.className += " enlace_desactivado";
                    var elemento = document.getElementById("btn_realizada");
                    elemento.className += " enlace_desactivado";
                    break;
                case 'R':
                    document.getElementById('fecha_cita').disabled = true;
                    document.getElementById('hora_inicio').disabled = true;
                    document.getElementById('hora_final').disabled = true;
                    document.getElementById('paciente_id').disabled = true;
                    document.getElementById('nombre_completo').disabled = true;
                    document.getElementById('telefonos').disabled = true;
                    document.getElementById('hospital_id').disabled = true;
                    document.getElementById('medico_id').disabled = true;
                    document.getElementById('observaciones').disabled = true;
                    var elemento = document.getElementById("btn_bloqueo");
                    elemento.className += " enlace_desactivado";
                    var elemento = document.getElementById("btn_admision");
                    elemento.className += " enlace_desactivado";
                    var elemento = document.getElementById("btn_cancelada");
                    elemento.className += " enlace_desactivado";
                    var elemento = document.getElementById("btn_realizada");
                    elemento.className += " enlace_desactivado";
                    break;
                case 'B':
                    document.getElementById('fecha_cita').disabled = true;
                    document.getElementById('hora_inicio').disabled = true;
                    document.getElementById('hora_final').disabled = true;
                    document.getElementById('paciente_id').disabled = true;
                    document.getElementById('nombre_completo').disabled = true;
                    document.getElementById('telefonos').disabled = true;
                    document.getElementById('hospital_id').disabled = true;
                    document.getElementById('medico_id').disabled = true;
                    document.getElementById('observaciones').disabled = true;
                    var elemento = document.getElementById("btn_bloqueo");
                    elemento.className += " enlace_desactivado";
                    var elemento = document.getElementById("btn_admision");
                    elemento.className += " enlace_desactivado";
                    var elemento = document.getElementById("btn_cancelada");
                    elemento.className += " enlace_desactivado";
                    var elemento = document.getElementById("btn_realizada");
                    elemento.className += " enlace_desactivado";
                    break;
            }
        }

        function admision(){
            var paciente_id          = document.getElementById('paciente_id').value;
            var maestro_protocolo_id = document.getElementById('maestro_protocolo_id').value;
            if (paciente_id == '') {
                swal({
                    title: 'Error !!!',
                    text: 'Para realizar la admisión debe asignar una ficha de pacienta a la cita',
                    type: 'error',
                });
            }else{
                if (maestro_protocolo_id.length > 0) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('trae_datos_protocolo') }}",
                        method: "POST",
                        data: {protocolo_id : maestro_protocolo_id},
                        success: function(response){
                            document.getElementById('aseguradora_id').value = response.aseguradora_id;
                            $('#aseguradora_id').change();
                            document.getElementById('poliza_no').value = response.poliza_no;
                            document.getElementById('aseguradora_aut_no').value = response.aseguradora_aut_no;
                        },
                        error: function(error){
                            console.log(error);
                        }
                    });
                }
                $('#admisionModal').modal('show');
            }
        }

        function show_bloqueo(){
            $('#bloqueoModal').modal('show');
        }

        function grabar_admision(){
            var cita_id = document.getElementById('cita_id').value;
            var tipo_admision = $('input[name="tipo_admision"]:checked').val();
            var admision_tercero = document.getElementById('admision_tercero').value;
            var aseguradora_id   = document.getElementById('aseguradora_id').value;
            var poliza_no        = document.getElementById('poliza_no').value;
            var deducible        = document.getElementById('deducible').value;
            var copago           = document.getElementById('copago').value;
            var paciente_id      = document.getElementById('paciente_id').value;
            var maestro_protocolo_id = document.getElementById('maestro_protocolo_id').value;
            var pago_aseguradora = marcado;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('crea_admision_x_cita') }}",
                method: "POST",
                data: {cita_id              : cita_id,
                       tipo_admision        : tipo_admision,
                       admision_tercero     : admision_tercero,
                       aseguradora_id       : aseguradora_id,
                       poliza_no            : poliza_no,
                       deducible            : deducible,
                       copago               : copago,
                       maestro_protocolo_id : maestro_protocolo_id,
                       pago_aseguradora     : pago_aseguradora
                      },
                success: function(response){
                    var info = JSON.stringify(response);
                    swal({
                        title: 'Trabajo Finalizado',
                        text: info,
                        type: 'success',
                        },
                        function(){
                            return window.location.href = "{{route('nueva_agenda')}}";
                        }
                    );
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function confirma_salida($id){
            swal({
                title: 'Confirmación',
                text: 'Seguro de Salir de cita ?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'btn-success',
                cancelButtonClass: 'btn-danger',
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                closeOnConfirm: false,
                allowEscapeKey: true
                },
                function(isConfirm) {
                    if (isConfirm) { 
                        return window.location.href = "{{route('nueva_agenda')}}";
                    }
                }
            );

            /*alertify.confirm('<i class="fas fa-sign-out-alt"></i> Salir', '<h4>Esta seguro de salir de Cita ? </h4>', function(){ 
            history.back();
                }
                , function(){ alertify.error('Se deja sin efecto')}
            );*/
        }

        function mensaje(proceso){
            var cita_id       = document.getElementById('cita_id').value;
            let observaciones = document.getElementById('observaciones').value;
            if (proceso == 'cancelar') {
                swal({
                    title: 'Confirmación',
                    text: 'Seguro de Cancelar la Cita ?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonClass: 'btn-success',
                    cancelButtonClass: 'btn-danger',
                    confirmButtonText: 'Si',
                    cancelButtonText: 'No',
                    closeOnConfirm: false,
                    allowEscapeKey: true
                    },
                    function(isConfirm) {
                        if (isConfirm) { 
                            $.ajax({
                                url: "{{ route('cancelar_cita') }}",
                                type: "POST",
                                async: true,
                                data: {"_token": "{{ csrf_token() }}", 
                                       cita_id: cita_id, 
                                       observaciones: observaciones
                                      },
                                success: function(response){
                                    var info = response;
                                    swal({
                                        title: 'Trabajo Finalizado',
                                        text: 'Cita Cancelada con Exito !!!',
                                        type: 'success',
                                        },
                                        function(){
                                            location.reload();
                                        }
                                    );
                                    //alertify.success('Compra eliminada con exito');
                                },
                                error: function(error){
                                    console.log(error);
                                }
                            });
                                        } 
                        else { 
                            swal("Cancelled", "Your imaginary file is safe :)", "error"); 
                            }
                    }
                );
            }
            if (proceso == 'realizar') {
                swal({
                    title: 'Confirmación',
                    text: 'Seguro de Finalizar la Cita ?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonClass: 'btn-success',
                    cancelButtonClass: 'btn-danger',
                    confirmButtonText: 'Si',
                    cancelButtonText: 'No',
                    closeOnConfirm: false,
                    allowEscapeKey: true
                    },
                    function(isConfirm) {
                        if (isConfirm) { 
                            $.ajax({
                                url: "{{ route('realizar_cita') }}",
                                type: "POST",
                                async: true,
                                data: {"_token": "{{ csrf_token() }}", 
                                       cita_id: cita_id,
                                       observaciones: observaciones},
                                success: function(response){
                                    var info = response;
                                    swal({
                                        title: 'Trabajo Finalizado',
                                        text: 'Cita Finalizada con Exito !!!',
                                        type: 'success',
                                        },
                                        function(){
                                            location.reload();
                                        }
                                    );
                                    //alertify.success('Compra eliminada con exito');
                                },
                                error: function(error){
                                    console.log(error);
                                }
                            });
                                        } 
                        else { 
                            swal("Cancelled", "Your imaginary file is safe :)", "error"); 
                            }
                    }
                );
            }
        }

        $(function(){
            $("#bloqueoForm").submit(function(){
                bloquear_espacio();
                return false;
            })
        });

        function bloquear_espacio(){
            var cita_id = document.getElementById('cita_id').value;
            var observaciones = document.getElementById('bloqueo_observaciones').value;
            $.ajax({
                url: "{{ route('bloquear_espacio') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}", 
                       cita_id: cita_id, 
                       observaciones: observaciones
                       },
                success: function(response){
                    var info = response;
                    swal({
                        title: 'Trabajo Finalizado',
                        text: 'Cita bloqueada con Exito !!!',
                        type: 'success',
                        },
                        function(){
                            location.reload();
                        }
                    );
                    //alertify.success('Compra eliminada con exito');
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function actualiza_nombre_completo(){
            var paciente = document.getElementById('paciente_id');
            var paciente_id = paciente.options[paciente.selectedIndex].value;
            var paciente_nombre = paciente.options[paciente.selectedIndex].text;
            if (paciente_id == '') {
                document.getElementById('nombre_completo').value = '';
            }else{
                document.getElementById('nombre_completo').value = paciente_nombre;
                $.ajax({
                    url: "{{ route('trae_telefonos_x_paciente') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {"_token": "{{ csrf_token() }}",paciente_id : paciente_id},
                    success: function(response){
                        document.getElementById('telefonos').value = response.telefonos;
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }
        }

        function control_otros(value){
            var isChecked = document.getElementById('pago_aseguradora').checked;
            if(isChecked){
                marcado = 'S';
                $("#deducible").attr("readonly", true); 
                $("#deducible").attr("min", 0); 
                $("#copago").attr("readonly", true); 
                $("#copago").attr("min", 0); 
            }else{
                marcado = 'N';
                $("#deducible").attr("readonly", false); 
                $("#copago").attr("readonly", false); 
                $("#copago").attr("min", 1); 
                $("#deducible").attr("max", 100); 
                $("#copago").attr("max", 100); 
            }
        }
    </script>
@endsection