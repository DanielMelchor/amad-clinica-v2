    <div class="modal fade" id="nuevaAdmisionModal" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg mx-auto">
            <div class="modal-content">
                <form id="admisionForm" class="h-100 d-flex flex-column" method="post" action="{{ route('grabar_admision') }}">
                    @csrf
                    <div class="card mb-0 border-0 flex-grow-1">
                        <div class="card-header d-flex justify-content-between align-items-center bg-light sticky-top">
                            <h6 class="mb-0 font-weight-bold"><i class="fas fa-plus-circle mr-2 text-primary"></i>Nueva Admisión</h6>
                            <div class="ml-auto">
                                <button type="submit" class="btn btn-sm btn-outline-success rounded-circle elevation-2 mr-1" title="Guardar">
                                    <i class="fas fa-save mr-1"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle elevation-2" data-dismiss="modal">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body py-3">
                            <input type="hidden" id="agenda_id" name="agenda_id" value="0">
                            
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="small font-weight-bold text-muted">Fecha Admisión</label>
                                    <input type="date" class="form-control form-control-sm shadow-sm" id="adm_fecha" name="adm_fecha" required value="{{ $hoy }}">
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="small font-weight-bold text-muted">Paciente</label>
                                    <select class="form-control form-control-sm select2bs4 shadow-sm" id="adm_paciente_id" name="adm_paciente_id" required style="width: 100%;">
                                        <option value="">Seleccionar...</option>
                                        @foreach($pacientes as $p)
                                            <option value="{{ $p->id }}">{{ $p->nombre_completo }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="small font-weight-bold text-muted">Médico</label>
                                    <select class="form-control form-control-sm select2bs4 shadow-sm" id="adm_medico_id" name="adm_medico_id" style="width: 100%;">
                                        @foreach($medicos as $medico)
                                            <option value="{{ $medico->id }}" {{ $medico->principal == 'S' ? 'selected' : '' }}>{{ $medico->nombre_completo }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="small font-weight-bold text-muted">Hospital</label>
                                    <select class="form-control form-control-sm select2bs4 shadow-sm" id="adm_hospital_id" name="adm_hospital_id" required style="width: 100%">
                                        <option value="">Seleccionar.....</option>
                                        @foreach($hospitales as $hospital)
                                            <option value="{{ $hospital->id }}" @if($hospital->principal_agenda == 'S') selected @endif> {{ $hospital->nombre}} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="small font-weight-bold text-muted">Aseguradora</label>
                                    <select class="form-control form-control-sm select2bs4 shadow-sm" id="adm_aseguradora_id" name="adm_aseguradora_id" style="width: 100%">
                                        <option value="">Seleccionar.....</option>
                                        @foreach($aseguradoras as $aseguradora)
                                            <option value="{{ $aseguradora->id }}" @if($aseguradora->principal_agenda == 'S') selected @endif> {{ $aseguradora->nombre}} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Póliza No.</label>
                                    <input type="text" class="form-control form-control-sm shadow-sm" id="poliza_no" name="poliza_no" placeholder="Ej: 99999" style="width: 100%" readonly>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Autorización No.</label>
                                    <input type="text" class="form-control form-control-sm shadow-sm" id="autorizacion_no" name="autorizacion_no" placeholder="Ej: 000-00" style="width: 100%" readonly>
                                </div>

                                <div class="col-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Copago</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white">Q.</span>
                                        </div>
                                        <input type="number" step="0.01" class="form-control shadow-sm" id="copago" name="copago" placeholder="0.00" value="0" readonly>
                                    </div>
                                </div>

                                <div class="col-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Deducible</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control shadow-sm" placeholder="0" id="coaseguro" name="coaseguro" value="0" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-white">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Modal nueva admision-->
    <!-- Busqueda -->
    <div class="modal fade" id="busquedaModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="busquedaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="card bg-light">
                    <div class="card-header">
                            <div class="row">
                                <div class="col-md-10">
                                    <h6>Busqueda de Expediente</h6>
                                </div>
                                <div class="col-md-2" style="text-align: right;">
                                    <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-1" title="Mostrar coincidencias" onclick="fnRealizarBusqueda();"><i class="fas fa-bars"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-1" title="Cerrar" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"><i class="fas fa-sign-out-alt"></i></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="input-group input-group-sm col-md-4 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="find_admision_no"># Admisión</label>
                                    </div>
                                    <input type="number" class="form-control " aria-label="Username" aria-describedby="basic-addon1" id="find_admision_no" name="find_admision_no" value="{{ old('find_admision_no') }}" autofocus>
                                </div>
                                <div class="input-group input-group-sm col-md-8 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="find_nombre">Nombre</label>
                                    </div>
                                    <input type="text" style="text-transform: uppercase;" class="form-control " aria-label="Username" aria-describedby="basic-addon1" id="find_nombre" name="find_nombre" value="{{ old('find_nombre') }}" required>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Busqueda -->