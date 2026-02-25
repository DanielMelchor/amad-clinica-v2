<!-- editar registro -->
    <div class="modal fade" id="editarRegistro" role="dialog" aria-labelledby="editarRegistroModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="editarRegistroForm" name="editarRegistroForm" method="post" action="{{ route('actualizar_nueva_agenda') }}">
                    @csrf
                    <div class="card mb-0">
                        <div class="card-header" style="background-color: #E1E8ED;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 font-weight-bold">Edición de Horario</h6>
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-sm btn-success rounded-circle elevation-2 mr-1" title="Guardar cambios">
                                        <i class="fas fa-save"></i>
                                    </button>
                                    <a href="{{ route('crear_paciente') }}" class="btn btn-sm btn-primary rounded-circle elevation-2 mr-1" title="Crear nuevo Paciente" target="_blank">
                                        <i class="fas fa-plus-circle"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger rounded-circle elevation-2" data-dismiss="modal" title="Cerrar Ventana">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body p-3"> <div class="row">
                                <div class="input-group input-group-sm col-12 col-md-10 offset-md-1 mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="fecha_cita">Fecha</label>
                                    </div>
                                    <input type="datetime-local" class="form-control" id="fecha_cita" name="fecha_cita" readonly>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-12 col-md-10 offset-md-1 mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="edit_paciente_id">Paciente</label>
                                        </div>
                                        <select class="custom-select select2bs4" id="edit_paciente_id" name="edit_paciente_id" onchange="actualiza_nombre_completo();">
                                            <option value="" selected>Paciente Sin Ficha...</option>
                                            @foreach($pacientes as $p)
                                                <option value="{{ $p->id }}"> {{ $p->nombre_completo}} </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" title="Recargar" onclick="fnActualizarPacientes();">
                                                <i class="fas fa-sync"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-group input-group-sm col-12 col-md-10 offset-md-1 mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="edit_nombre_completo">Nombre</label>
                                    </div>
                                    <input type="text" class="form-control" id="edit_nombre_completo" name="edit_nombre_completo" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-group input-group-sm col-12 col-md-10 offset-md-1 mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Teléfono</label>
                                    </div>
                                    <input type="text" class="form-control" id="edit_telefonos" name="edit_telefonos" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-group input-group-sm col-12 col-md-10 offset-md-1 mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="edit_hospital_id">Ubicación</label>
                                    </div>
                                    <select class="custom-select select2bs4" id="edit_hospital_id" name="edit_hospital_id" required>
                                        <option value="">Seleccionar...</option>
                                        @foreach($hospitales as $h)
                                            <option value="{{ $h->id }}" @if($h->principal_agenda == 'S') selected @endif> {{ $h->nombre }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-group input-group-sm col-12 col-md-10 offset-md-1 mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="edit_medico_id">Médico</label>
                                    </div>
                                    <select class="custom-select select2bs4" id="edit_medico_id" name="edit_medico_id" required>
                                        <option value="">Seleccionar...</option>
                                        @foreach($medicos as $m)
                                            <option value="{{ $m->id }}" @if($m->principal == 'S') selected @endif> {{ $m->nombre_completo }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-12 col-md-10 offset-md-1 mt-1">
                                    <label for="edit_observaciones" class="small font-weight-bold">Observaciones</label>
                                    <textarea class="form-control form-control-sm" id="edit_observaciones" name="edit_observaciones" rows="3" maxlength="190"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- /editar registro -->
<!-- Modal nueva admision-->
    <div class="modal fade" id="nuevaAdmisionModal" role="dialog" aria-labelledby="nuevaAdmisionModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="admisionForm" method="post" action="{{ route('grabar_admision') }}">
                    @csrf
                    <div class="card mb-0">
                        <div class="card-header" style="background-color: #E1E8ED;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 font-weight-bold">Nueva Admisión</h6>
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-sm btn-success rounded-circle elevation-2 mr-2" title="Guardar cambios">
                                        <i class="fas fa-save"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger rounded-circle elevation-2" data-dismiss="modal" title="Cerrar Ventana">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="input-group input-group-sm col-12 col-md-10 offset-md-1 mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Fecha Admisión</label>
                                    </div>
                                    <input type="date" class="form-control" id="adm_fecha" name="adm_fecha" disabled required value="{{ $today }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-group input-group-sm col-12 col-md-10 offset-md-1 mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="adm_paciente_id">Paciente</label>
                                    </div>
                                    <select class="custom-select select2bs4" id="adm_paciente_id" name="adm_paciente_id" disabled required>
                                        <option value="">Seleccionar.....</option>
                                        @foreach($pacientes as $p)
                                            <option value="{{ $p->id }}"> {{ $p->nombre_completo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-group input-group-sm col-12 col-md-10 offset-md-1 mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="adm_medico_id">Médico</label>
                                    </div>
                                    <select class="custom-select select2bs4" id="adm_medico_id" name="adm_medico_id" disabled required>
                                        <option value="">Seleccionar.....</option>
                                        @foreach($medicos as $medico)
                                            <option value="{{ $medico->id }}" @if($medico->principal == 'S') selected @endif> {{ $medico->nombre_completo}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-group input-group-sm col-12 col-md-10 offset-md-1 mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="adm_hospital_id">Hospital</label>
                                    </div>
                                    <select class="custom-select select2bs4" id="adm_hospital_id" name="adm_hospital_id" disabled required>
                                        <option value="">Seleccionar.....</option>
                                        @foreach($hospitales as $hospital)
                                            <option value="{{ $hospital->id }}"> {{ $hospital->nombre}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-group input-group-sm col-12 col-md-10 offset-md-1 mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text font-weight-normal">Adm. Terceros</label>
                                    </div>
                                    <input type="number" step="1" min="0" class="form-control" id="admision_tercero" name="admision_tercero" placeholder="Opcional" value="{{ old('admision_tercero')}}">
                                </div>
                            </div>

                            <hr class="my-3 col-md-10 offset-md-1">

                            <div class="row">
                                <div class="input-group input-group-sm col-12 col-md-10 offset-md-1 mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="adm_aseguradora_id">Aseguradora</label>
                                    </div>
                                    <select class="custom-select select2bs4" id="adm_aseguradora_id" name="adm_aseguradora_id" onchange="fn_habilitar_poliza(this.value); return false;">
                                        <option value="" selected>Ninguna / Particular</option>
                                        @foreach($aseguradoras as $aseguradora)
                                            <option value="{{ $aseguradora->id }}"> {{ $aseguradora->nombre}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-group input-group-sm col-12 col-md-10 offset-md-1 mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Póliza No.</label>
                                    </div>
                                    <input type="text" class="form-control" id="poliza_no" name="poliza_no" value="{{ old('poliza_no')}}" disabled placeholder="Requerido si aplica">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group input-group-sm col-12 col-md-10 offset-md-1 mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Autorización</label>
                                    </div>
                                    <input type="text" class="form-control" id="autorizacion_no" name="autorizacion_no" value="{{ old('autorizacion_no')}}" disabled placeholder="Requerido si aplica">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Modal nueva admision-->
    <!-- bloqueo -->
    <div class="modal fade" id="bloqueoModal" role="dialog" aria-labelledby="bloqueoModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="bloqueoForm" name="bloqueo" action="#">
                    @csrf
                    <div class="card mb-0"> <div class="card-header" style="background-color: #E1E8ED;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 font-weight-bold">Bloquear horario</h6>
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-sm btn-success rounded-circle elevation-2 mr-2" title="Guardar cambios">
                                        <i class="fas fa-save"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger rounded-circle elevation-2" data-dismiss="modal" title="Cerrar Ventana">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12 col-md-10 offset-md-1">
                                    <label for="bloqueo_espacio_observaciones" class="small text-muted">Observaciones del bloqueo</label>
                                    <textarea 
                                        class="form-control form-control-sm" 
                                        id="bloqueo_espacio_observaciones" 
                                        name="bloqueo_espacio_observaciones" 
                                        rows="4" 
                                        maxlength="190" 
                                        placeholder="Escriba el motivo del bloqueo aquí..."
                                        required
                                    ></textarea>
                                    <small class="text-muted float-right">Máx. 190 caracteres</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /bloqueo -->
    <!-- Modal Historico -->
    <div class="modal fade" id="historicoModal" tabindex="-1" role="dialog" aria-labelledby="historicoModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="card mb-0">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold">Histórico de Citas</h6>
                            <button type="button" class="btn btn-sm btn-danger rounded-circle elevation-2" data-dismiss="modal" title="Cerrar Ventana">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0"> <div class="table-responsive">
                            <table class="table table-sm table-striped mb-0" id="tblHistorico" style="width:100%; min-width: 600px;">
                                <thead class="bg-light">
                                    <tr class="text-center" style="font-size: 0.8rem;">
                                        <th class="py-2">Fecha</th>
                                        <th class="py-2">Estado</th>
                                        <th class="py-2">Admisión</th>
                                        <th class="py-2">Tipo</th>
                                        <th class="py-2">Médico</th>
                                        <th class="py-2">Hospital</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 0.85rem;">
                                    </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="card-footer py-2 text-right bg-white">
                        <small class="text-muted">Deslice hacia los lados para ver más detalles →</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Modal Historico -->