    <!-- agregar Modal -->
    <div class="modal fade" id="agregarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg">
                <form role="form" id="formaNuevoRegistro" method="POST" action="{{route('grabar_invtransaccion')}}">
                    @csrf
                    <div class="card mb-0">
                        <div class="card-header bg-light py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 font-weight-bold text-secondary">Nuevo Registro</h6>
                                <div>
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
                                <label class="small font-weight-bold text-muted">Descripción</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Ingrese descripción" id="descripcion" name="descripcion" autofocus required value="{{ old('descripcion')}}">
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-12 mb-3">
                                    <label class="small font-weight-bold text-muted d-block">Movimiento</label>
                                    <div class="d-flex justify-content-around p-2 border rounded bg-light">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="entrada" name="signo" value="1" checked>
                                            <label for="entrada">Entrada</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="negativo" name="signo" value="-1">
                                            <label for="negativo">Salida</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="small font-weight-bold text-muted d-block">Estado</label>
                                    <div class="border rounded bg-light d-flex align-items-center" style="height: calc(1.5em + .75rem + 2px); padding: 0 0.75rem;">
                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="1">
                                            <label class="custom-control-label small text-secondary" for="estado">Activar</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label class="small font-weight-bold text-muted d-block">Tipo de Transacción</label>
                                <div class="d-flex flex-wrap justify-content-between p-2 border rounded bg-light">
                                    <div class="icheck-primary mx-2 my-1">
                                        <input type="radio" id="compra" name="tipo_transaccion" value="C" checked>
                                        <label for="compra">Compra</label>
                                    </div>
                                    <div class="icheck-primary mx-2 my-1">
                                        <input type="radio" id="venta" name="tipo_transaccion" value="V">
                                        <label for="venta">Venta</label>
                                    </div>
                                    <div class="icheck-primary mx-2 my-1">
                                        <input type="radio" id="ajuste" name="tipo_transaccion" value="A">
                                        <label for="ajuste">Ajuste</label>
                                    </div>
                                    <div class="icheck-primary mx-2 my-1">
                                        <input type="radio" id="traslado" name="tipo_transaccion" value="T">
                                        <label for="traslado">Traslado</label>
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
    <div class="modal fade" id="editarModalCenter" role="dialog" aria-labelledby="editarModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg">
                <form role="form" method="POST" action="{{route('actualizar_invtransaccion')}}">
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
                                <input type="text" class="form-control form-control-sm" placeholder="Descripción del movimiento" id="edescripcion" name="edescripcion" autofocus required>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-12 mb-3">
                                    <label class="small font-weight-bold text-muted d-block">Movimiento</label>
                                    <div class="d-flex justify-content-around p-2 border rounded bg-light">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="eentrada" name="esigno" value="1">
                                            <label for="eentrada">Entrada</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="esalida" name="esigno" value="-1">
                                            <label for="esalida">Salida</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 mb-3">
                                    <label class="small font-weight-bold text-muted d-block">Estado</label>
                                    <div class="border rounded bg-light d-flex align-items-center" style="height: calc(1.5em + .75rem + 2px); padding: 0 0.75rem;">
                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="eestado" name="eestado" value="1">
                                            <label class="custom-control-label small text-secondary" for="eestado">Activar</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label class="small font-weight-bold text-muted d-block">Tipo de Transacción</label>
                                <div class="d-flex flex-wrap justify-content-between p-2 border rounded bg-light">
                                    <div class="icheck-primary mx-2 my-1">
                                        <input type="radio" id="ecompra" name="etipo_transaccion" value="C">
                                        <label for="ecompra">Compra</label>
                                    </div>
                                    <div class="icheck-primary mx-2 my-1">
                                        <input type="radio" id="eventa" name="etipo_transaccion" value="V">
                                        <label for="eventa">Venta</label>
                                    </div>
                                    <div class="icheck-primary mx-2 my-1">
                                        <input type="radio" id="eajuste" name="etipo_transaccion" value="A">
                                        <label for="eajuste">Ajuste</label>
                                    </div>
                                    <div class="icheck-primary mx-2 my-1">
                                        <input type="radio" id="etraslado" name="etipo_transaccion" value="T">
                                        <label for="etraslado">Traslado</label>
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