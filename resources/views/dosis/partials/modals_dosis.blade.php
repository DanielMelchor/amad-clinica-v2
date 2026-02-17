<div class="modal fade" id="agregarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalAgregarTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form role="form" id="formaNuevoRegistro" method="POST" action="{{route('dosis_grabar')}}">
                @csrf
                <div class="card mb-0">
                    <div class="card-header d-flex align-items-center" style="background-color: #F4F6F7;">
                        <h6 class="mb-0 flex-grow-1 font-weight-bold">Nuevo Registro (Dosis)</h6>
                        <div class="ml-auto">
                            <button type="submit" id="submitButton" class="btn btn-sm btn-outline-success rounded-circle elevation-2" title="Guardar">
                                <i class="fas fa-save"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle elevation-2" data-dismiss="modal" title="Cerrar Ventana">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-10 offset-md-1">
                                <div class="form-group mb-3">
                                    <label for="descripcion">Descripción de la Dosis</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend d-none d-sm-block">
                                            <span class="input-group-text"><i class="fas fa-vial"></i></span>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Ej: 500mg, 1 tableta, etc." 
                                               id="descripcion" name="descripcion" 
                                               required autofocus value="{{ old('descripcion')}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="1">
                                        <label class="custom-control-label" for="estado">Activar Dosis</label>
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

<div class="modal fade" id="editarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalEditarTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form role="form" method="POST" action="{{route('dosis_actualizar')}}">
                @csrf
                <div class="card mb-0">
                    <div class="card-header d-flex align-items-center" style="background-color: #F4F6F7;">
                        <h6 class="mb-0 flex-grow-1 font-weight-bold">Edición de Registro</h6>
                        <div class="ml-auto">
                            <button type="submit" class="btn btn-sm btn-outline-success rounded-circle elevation-2" title="Guardar Cambios">
                                <i class="fas fa-save"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle elevation-2" data-dismiss="modal" title="Cerrar Ventana">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <input type="hidden" id="eid" name="eid">
                        <div class="row">
                            <div class="col-12 col-md-10 offset-md-1">
                                <div class="form-group mb-3">
                                    <label for="edescripcion">Descripción</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend d-none d-sm-block">
                                            <span class="input-group-text"><i class="fas fa-vial"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="edescripcion" name="edescripcion" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="eestado" name="eestado" value="1">
                                        <label class="custom-control-label" for="eestado">Estado Activo</label>
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