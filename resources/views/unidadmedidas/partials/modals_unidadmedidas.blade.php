	<!-- agregar Modal -->
	<div class="modal fade" id="agregarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
	        <div class="modal-content border-0 shadow-lg">
	            <form role="form" id="formaNuevoRegistro" method="POST" action="{{route('unidadmedida_grabar')}}">
	                @csrf
	                <div class="card mb-0">
	                    <div class="card-header bg-light py-3">
	                        <div class="d-flex justify-content-between align-items-center">
	                            <h6 class="mb-0 font-weight-bold text-secondary">Nuevo Registro</h6>
	                            <div class="btn-group">
	                                <button type="submit" id="submitButton" class="btn btn-sm btn-outline-success rounded-circle mr-2 shadow-sm" title="Guardar">
	                                    <i class="fas fa-save"></i>
	                                </button>
	                                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle shadow-sm" data-dismiss="modal" title="Cerrar">
	                                    <i class="fas fa-times"></i>
	                                </button>
	                            </div>
	                        </div>
	                    </div>
	                    
	                    <div class="card-body p-3">
	                        <div class="form-group mb-3">
	                            <label class="small font-weight-bold text-muted" for="descripcion">Descripción</label>
	                            <input type="text" class="form-control form-control-sm" placeholder="Ej: Kilogramos, Litros..." id="descripcion" name="descripcion" autofocus required value="{{ old('descripcion')}}">
	                        </div>

	                        <div class="form-group mb-3">
	                            <label class="small font-weight-bold text-muted" for="siglas">Siglas</label>
	                            <input type="text" class="form-control form-control-sm" placeholder="Ej: KG, LT" id="siglas" name="siglas" maxlength="5" required value="{{ old('siglas')}}">
	                        </div>

	                        <div class="row">
	                            <div class="col-12 col-md-6 mb-2">
	                                <div class="border rounded bg-light d-flex align-items-center px-3" style="height: 45px;">
	                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success w-100">
	                                        <input type="checkbox" class="custom-control-input" id="aplica_receta" name="aplica_receta" value="S">
	                                        <label class="custom-control-label small text-secondary d-flex justify-content-between w-100" for="aplica_receta">
	                                            <span>Utilizado en receta</span>
	                                        </label>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="col-12 col-md-6 mb-2">
	                                <div class="border rounded bg-light d-flex align-items-center px-3" style="height: 45px;">
	                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success w-100">
	                                        <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
	                                        <label class="custom-control-label small text-secondary d-flex justify-content-between w-100" for="estado">
	                                            <span>Activar</span>
	                                        </label>
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
	<!-- /agregar Modal -->

	<!-- editar Modal -->
	<div class="modal fade" id="editarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="editarModalCenterTitle" aria-hidden="true">
	    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
	        <div class="modal-content border-0 shadow-lg">
	            <form role="form" method="POST" action="{{route('unidadmedida_actualizar')}}">
	                @csrf
	                <div class="card mb-0">
	                    <div class="card-header py-3" style="background-color: #F4F6F7;">
	                        <div class="d-flex justify-content-between align-items-center">
	                            <h6 class="mb-0 font-weight-bold text-secondary">Edición de Registro</h6>
	                            <div class="btn-group">
	                                <button type="submit" class="btn btn-sm btn-outline-success rounded-circle mr-2 shadow-sm" title="Guardar">
	                                    <i class="fas fa-save"></i>
	                                </button>
	                                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle shadow-sm" data-dismiss="modal" title="Cerrar">
	                                    <i class="fas fa-times"></i>
	                                </button>
	                            </div>
	                        </div>
	                    </div>
	                    
	                    <div class="card-body p-3">
	                        <input type="hidden" id="eid" name="eid">
	                        
	                        <div class="form-group mb-3">
	                            <label class="small font-weight-bold text-muted" for="edescripcion">Descripción</label>
	                            <input type="text" class="form-control form-control-sm" placeholder="Descripción a mostrar" id="edescripcion" name="edescripcion" autofocus required>
	                        </div>

	                        <div class="form-group mb-3">
	                            <label class="small font-weight-bold text-muted" for="esiglas">Siglas</label>
	                            <input type="text" class="form-control form-control-sm" placeholder="Siglas" id="esiglas" name="esiglas" maxlength="5" required>
	                        </div>

	                        <div class="row">
	                            <div class="col-12 col-md-6 mb-2">
	                                <div class="border rounded bg-light d-flex align-items-center px-3" style="height: 45px;">
	                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success w-100">
	                                        <input type="checkbox" class="custom-control-input" id="eaplica_receta" name="eaplica_receta" value="S">
	                                        <label class="custom-control-label small text-secondary d-flex justify-content-between w-100" for="eaplica_receta">
	                                            <span>Utilizado en receta</span>
	                                        </label>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="col-12 col-md-6 mb-2">
	                                <div class="border rounded bg-light d-flex align-items-center px-3" style="height: 45px;">
	                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success w-100">
	                                        <input type="checkbox" class="custom-control-input" id="eestado" name="eestado" value="A">
	                                        <label class="custom-control-label small text-secondary d-flex justify-content-between w-100" for="eestado">
	                                            <span>Activar</span>
	                                        </label>
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
	<!-- /editar Modal -->