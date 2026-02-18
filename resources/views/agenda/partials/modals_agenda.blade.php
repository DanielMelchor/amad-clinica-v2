<!-- editar registro -->
<div class="modal fade" id="editarRegistro" role="dialog" aria-labelledby="editarRegistroModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form class="form-horizontal" id="editarRegistroForm" name="editarRegistroForm" method="post" action="{{ route('actualizar_nueva_agenda') }}">
                @csrf
                <div class="card">
                    <div class="card-header" style="background-color: #E1E8ED;">
                    	<div class="row">
                    		<div class="col-md-9">
	                            <h6>Edición de Horario</h6>
	                        </div>
	                        <div class="col-md-3" style="text-align: right;">
								<button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar cambios"><i class="fas fa-save"></i></button>
								<a href="{{ route('crear_paciente',['P', 0]) }}" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Crear nuevo Paciente" target="_blank"><i class="fas fa-plus-circle"></i></a>
								<button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
							</div>
                    	</div>
					</div>
                    <div class="card-body">
						<div class="row">
							<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
								<div class="input-group-prepend">
									<label class="input-group-text" for="fecha_cita">Fecha&nbsp;&nbsp;</label>
								</div>
								<input type="datetime-local" class="form-control" id="fecha_cita" name="fecha_cita" readonly>
							</div>
						</div>
						<div class="row">
							<div class="input-group input-group-sm col-10 offset-1 mb-1">
								<div class="input-group-prepend">
                                    <label class="input-group-text" for="edit_paciente_id">Paciente&nbsp;&nbsp;&nbsp;</label>
                                </div>
                                <select class="custom-select custom-select-sm select2bs4" id="edit_paciente_id" name="edit_paciente_id" onchange="actualiza_nombre_completo();">
                                    <option value="" selected>Paciente Sin Ficha...</option>
                                    @foreach($pacientes as $p)
                                        <option value="{{ $p->id }}"> {{ $p->nombre_completo}} </option>
                                    @endforeach
                                </select>
							</div>
							<div class="col-lg-1 col-sm-1" style="text-align: right;">
								<a href="#" class="btn btn-xs btn-outline-secondary rounded-circle elevation-4" title="Recargar Pacientes" onclick="fnActualizarPacientes();"><i class="fas fa-sync"></i></a>
							</div>
						</div>
						<div class="row">
							<div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="edit_nombre_completo">Nombre&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                </div>
                                <input type="text" class="form-control" id="edit_nombre_completo" name="edit_nombre_completo" required value="">
                            </div>
						</div>
						<div class="row">
                            <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">Telefono&nbsp;&nbsp;&nbsp;</label>
                                </div>
                                <input type="text" class="form-control" id="edit_telefonos" name="edit_telefonos" required value="{{ old('edit_telefonos') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-1 col-md-10 offset-md-1">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="edit_hospital_id">Ubicación</label>
                                    </div>
                                    <select class="custom-select  custom-select-sm select2bs4 form-control" id="edit_hospital_id" name="edit_hospital_id" required>
                                        <option value="" selected>Seleccionar...</option>
                                        @foreach($hospitales as $h)
                                            <option value="{{ $h->id }}" @if($h->principal_agenda == 'S') selected @endif> {{ $h->nombre }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        	<div class="mb-1 col-md-10 offset-md-1">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="edit_medico_id">Medico&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    </div>
                                    <select class="custom-select  custom-select-sm select2bs4 form-control" id="edit_medico_id" name="edit_medico_id" required>
                                        <option value="">Seleccionar...</option>
                                        @foreach($medicos as $m)
                                            <option value="{{ $m->id }}"@if($m->principal == 'S') selected @endif> {{ $m->nombre_completo }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    	<div class="row text-center">
                            <div class="form-group col-md-10 offset-md-1">
                                <label for="antmedico_descripcion">Observaciones</label>
                                <textarea class="form-control form-control-sm" id="edit_observaciones" name="edit_observaciones" rows="3" maxlength="190">{{ old('observaciones') }}</textarea>
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
                <!--<form class="form" method="POST" action="{{route('grabar_admision')}}">-->
                    @csrf
                    <div class="card">
                        <div class="card-header" style="background-color: #E1E8ED;">
                        	<div class="row">
                        		<div class="col-md-9">
		                            <h6>Nueva Admisión</h6>
		                        </div>
		                        <div class="col-md-3" style="text-align: right;">
									<button type="submit" class="btn btn-xs btn-success rounded-circle elevation-4" title="Guardar cambios"><i class="fas fa-save"></i></button>
									<button type="button" class="btn btn-xs btn-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
								</div>
                        	</div>
						</div>
                        <div class="card-body">
                            <!-- fecha admision -->
                            <div class="row">
                                <div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Fecha Admisión</label>
                                    </div>
                                    <input type="date" class="form-control form-control-sm" id="adm_fecha" name="adm_fecha" disabled required value="{{ $today }}">
                                </div>
                            </div>
                            <!-- /fecha admision -->
                            <!-- paciente -->
                            <div class="row">
                                <div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="adm_paciente_id">Paciente</label>
                                    </div>
                                    <select class="custom-select custom-select-sm select2bs4" id="adm_paciente_id" name="adm_paciente_id" disabled required>
                                        <option value="">Seleccionar.....</option>
                                        @foreach($pacientes as $p)
                                            <option value="{{ $p->id }}"> {{ $p->nombre_completo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- /paciente -->
                            <!-- medico -->
                            <div class="row">
                                <div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="adm_medico_id">Médico</label>
                                    </div>
                                    <select class="custom-select custom-select-sm select2bs4" id="adm_medico_id" name="adm_medico_id" disabled required>
                                        <option value="" selected="selected">Seleccionar.....</option>
                                        @foreach($medicos as $medico)
                                            <option value="{{ $medico->id }}" @if($medico->principal == 'S') selected @endif> {{ $medico->nombre_completo}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- /medico -->
                            <!-- hospital -->
                            <div class="row">
                                <div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="adm_hospital_id">Hospital</label>
                                    </div>
                                    <select class="custom-select custom-select-sm select2bs4" id="adm_hospital_id" name="adm_hospital_id" disabled required>
                                        <option value="" selected="selected">Seleccionar.....</option>
                                        @foreach($hospitales as $hospital)
                                            <option value="{{ $hospital->id }}"> {{ $hospital->nombre}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- /hospital -->
                            <!-- admision terceros -->
                            <div class="row">
                                <div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Admisión Terceros</label>
                                    </div>
                                    <input type="number" step="1" min="0" class="form-control" id="admision_tercero" name="admision_tercero" value="{{ old('admision_tercero')}}">
                                </div>
                            </div>
                            <!-- /admision terceros -->
                            <!-- aseguradora -->
                            <div class="row">
                                <div class="input-group-sm col-md-10 offset-md-1 mb-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="adm_aseguradora_id">Aseguradora</label>
                                        </div>
                                        <select class="custom-select custom-select-sm select2bs4" id="adm_aseguradora_id" name="adm_aseguradora_id" onchange="fn_habilitar_poliza(this.value); return false;">
                                            <option value="" selected="selected">Seleccionar.....</option>
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
                                        <label class="input-group-text">Póliza No.</label>
                                    </div>
                                    <input type="text" class="form-control" id="poliza_no" name="poliza_no" value="{{ old('poliza_no')}}" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Autorización No.</label>
                                    </div>
                                    <input type="text" class="form-control" id="autorizacion_no" name="autorizacion_no" value="{{ old('autorizacion_no')}}" disabled>
                                </div>
                            </div>
                            <!-- /poliza -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Modal nueva admision-->