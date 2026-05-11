<div class="modal fade" id="agregarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalAgregarTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form role="form" id="formaNuevoRegistro" method="POST" action="{{route('bodega_grabar')}}">
                @csrf
                <div class="card mb-0"> 
                    <div class="card-header d-flex align-items-center" style="background-color: #F4F6F7;">
                        <h6 class="mb-0 flex-grow-1">Nuevo Registro</h6>
                        
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
                                    <label for="descripcion">Nombre de la Bodega</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend d-none d-sm-block">
                                            <span class="input-group-text">Nombre</span>
                                        </div>
                                        <input type="text" class="form-control form-control-lg-sm" 
                                               placeholder="Ej: Bodega Central" 
                                               id="descripcion" name="descripcion" 
                                               required value="{{ old('descripcion')}}">
                                    </div>
                                    <small class="text-muted">Nombre con el que se conocerá la bodega.</small>
                                </div>

                                <div class="form-group mb-1">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
                                        <label class="custom-control-label" for="estado">Activar Bodega</label>
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

<div class="modal fade" id="editarModalCenter" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="modalEditarTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form role="form" method="POST" action="{{route('bodega_actualizar')}}">
                @csrf
                <div class="card mb-0">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #F4F6F7;">
                        <h6 class="mb-0">Edición de Registro</h6>
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
                                            <span class="input-group-text">Descripción</span>
                                        </div>
                                        <input type="text" class="form-control" 
                                               id="edescripcion" name="edescripcion" 
                                               required value="{{ old('edescripcion')}}">
                                    </div>
                                </div>

                                <div class="form-group mb-1">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="eestado" name="eestado" value="A">
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

<div class="modal fade" id="configStockModal" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="card-header d-flex align-items-center" style="background-color: #F4F6F7;">
                <h6 class="mb-0 flex-grow-1">Configuración de Stock: <span id="nombreBodegaConfig"></span></h6>
                <button type="button" class="btn btn-sm btn-outline-success rounded-circle mr-2" onclick="fn_guardar_config();">
                    <i class="fas fa-save"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="card-body">
                <input type="hidden" id="config_bodega_id">
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-sm table-striped" id="tblConfigStock">
                        <thead>
                            <tr>
                                <th>Insumo</th>
                                <th width="150">Mínimo</th>
                                <th width="150">Máximo</th>
                                <th width="150">Punto Reorden</th>
                            </tr>
                        </thead>
                        <tbody id="bodyConfigStock">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>