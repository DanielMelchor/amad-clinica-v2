<!-- agregar Modal -->
	<div class="modal fade" id="agregarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="editarModalCenterTitle" aria-hidden="true">
	    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
	        <div class="modal-content">
	            <form role="form" method="POST" action="{{route('hospital_grabar')}}">
	                @csrf
	                <div class="card mb-0"> <div class="card-header" style="background-color: #F4F6F7;">
	                        <div class="row align-items-center">
	                            <div class="col-8 col-md-9">
	                                <h6 class="mb-0">Nuevo Registro</h6>
	                            </div>
	                            <div class="col-4 col-md-3 text-right">
	                                <button type="submit" class="btn btn-sm btn-success rounded-circle elevation-2" title="Guardar">
	                                    <i class="fas fa-save"></i>
	                                </button>
	                                <button type="button" class="btn btn-sm btn-danger rounded-circle elevation-2" data-dismiss="modal" title="Cerrar">
	                                    <i class="fas fa-sign-out-alt"></i>
	                                </button>
	                            </div>
	                        </div>
	                    </div>

	                    <div class="card-body">
	                        <div class="row justify-content-center">
	                            
	                            <div class="col-12 col-md-10 mb-2">
	                                <div class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text">Nombre</label>
	                                    </div>
	                                    <input type="text" class="form-control" placeholder="Nombre hospital" id="nombre" name="nombre" autofocus required value="{{ old('nombre')}}">
	                                </div>
	                            </div>

	                            <div class="col-12 col-md-10 mb-2">
	                                <div class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text">Dirección</label>
	                                    </div>
	                                    <input type="text" class="form-control" placeholder="Dirección Hospital" id="direccion" name="direccion" value="{{ old('direccion')}}">
	                                </div>
	                            </div>

	                            <div class="col-12 col-md-10 mb-2">
	                                <div class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text">Teléfonos</label>
	                                    </div>
	                                    <input type="text" class="form-control" placeholder="Teléfonos" id="telefonos" name="telefonos" value="{{ old('telefonos')}}">
	                                </div>
	                            </div>

	                            <div class="col-12 col-md-10 mb-3">
	                                <div class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text">Contacto</label>
	                                    </div>
	                                    <input type="text" class="form-control" placeholder="Nombre contacto" id="contacto" name="contacto" value="{{ old('contacto')}}">
	                                </div>
	                            </div>

	                            <div class="col-12 col-md-10">
	                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success mb-2">
	                                    <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
	                                    <label class="custom-control-label" for="estado">Activar</label>
	                                </div>

	                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success mb-2">
	                                    <input type="checkbox" class="custom-control-input" id="principal_agenda" name="principal_agenda" value="S">
	                                    <label class="custom-control-label" for="principal_agenda">Definir como principal en agenda</label>
	                                </div>
	                            </div>

	                        </div> </div> </div> </form>
	        </div>
	    </div>
	</div>
	<!-- /agregar Modal -->
	<!-- editar Modal -->
	<div class="modal fade" id="editarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    		<div class="modal-content">
      			<form role="form" method="POST" action="{{route('hospital_actualizar')}}">
	      			@csrf
	      			<div class="card">
	        			<div class="card-header" style="background-color: #F4F6F7;">
	      					<div class="row">
	        					<div class="col-md-9">
	        						<h6>Edición de Registro</h6>
	        					</div>
	        					<div class="col-md-3" style="text-align: right;">
	        						<button type="submit" class="btn btn-sm btn-success img-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
	        						<button type="button" class="btn btn-sm btn-danger img-circle elevation-4" data-dismiss="modal" title="Cerrar"> <i class="fas fa-sign-out-alt"></i></button>
	        					</div>
	        				</div>
	      				</div>
	      				<div class="card-body">
	      					<input type="hidden" id="eid" name="eid">
	      					<div class="row text-center">
            					<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
              						<div class="input-group-prepend">
                						<label class="input-group-text">Nombre</label>
              						</div>
              						<input type="text" class="form-control" placeholder="nombre hospital" aria-label="Username" aria-describedby="basic-addon1" id="enombre" name="enombre" autofocus required>
        						</div>
        					</div>
        					<div class="row">
        						<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
              						<div class="input-group-prepend">
                						<label class="input-group-text">Dirección</label>
              						</div>
              						<input type="text" class="form-control" placeholder="Direccion Hospital" aria-label="Username" aria-describedby="basic-addon1" id="edireccion" name="edireccion">
            					</div>
        					</div>
        					<div class="row text-center">
            					<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
	              					<div class="input-group-prepend">
	                					<label class="input-group-text">Teléfonos</label>
	              					</div>
              						<input type="text" class="form-control" placeholder="Telefonos" aria-label="Username" aria-describedby="basic-addon1" id="etelefonos" name="etelefonos">
            					</div>
            				</div>
            				<div class="row">
            					<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
              						<div class="input-group-prepend">
                						<label class="input-group-text">Contacto&nbsp;</label>
              						</div>
              						<input type="text" class="form-control" placeholder="nombre contacto" aria-label="Username" aria-describedby="basic-addon1" id="econtacto" name="econtacto">
            					</div>
            				</div>
            				<div class="row">
            					<div class="form-group offset-md-1">
              						<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                						<input type="checkbox" class="custom-control-input" id="eestado" name="eestado" value="A">
                						<label class="custom-control-label" for="eestado">Activar</label>
              						</div>
            					</div>
            				</div>
            				<div class="row">
            					<div class="form-group offset-md-1">
              						<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                						<input type="checkbox" class="custom-control-input" id="eprincipal_agenda" name="eprincipal_agenda" value="S">
            							<label class="custom-control-label" for="eprincipal_agenda">Definir como principal en agenda</label>
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