	<!-- agregar Modal -->
	<div class="modal fade" id="agregarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    		<div class="modal-content">
      			<form role="form" id="formaNuevoRegistro" method="POST" action="{{route('aseguradora_grabar')}}">
	      			@csrf
	      			<div class="card">
	        			<div class="card-header" style="background-color: #F4F6F7;">
	      					<div class="row">
	        					<div class="col-md-9">
	        						<h6>Nuevo Registro</h6>
	        					</div>
	        					<div class="col-md-3" style="text-align: right;">
	        						<button type="submit" id="submitButton" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
	        						<button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar"> <i class="fas fa-sign-out-alt"></i></button>
	        					</div>
	        				</div>
	      				</div>
	      				<div class="card-body">
	      					<div class="row">
								<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">Nombre</label>
								  	</div>
								  	<input type="text" class="form-control" placeholder="nombre aseguradora" aria-label="Username" aria-describedby="basic-addon1" id="nombre" name="nombre" autofocus required value="{{ old('nombre')}}">
								</div>
							</div>
							<div class="row">
								<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">Dirección</label>
								  	</div>
								  	<input type="text" class="form-control" placeholder="direccion" aria-label="Username" aria-describedby="basic-addon1" id="direccion" name="direccion" value="{{ old('direccion')}}">
								</div>
			            	</div>
			            	<div class="row">
								<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">Teléfonos</label>
								  	</div>
								  	<input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" placeholder="telefonos" id="telefonos" name="telefonos" value="{{ old('telefonos')}}">
								</div>
							</div>
							<div class="row">
								<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">Contacto&nbsp;</label>
								  	</div>
								  	<input type="text" class="form-control" placeholder="nombre contacto" aria-label="Username" aria-describedby="basic-addon1" id="contacto" name="contacto" value="{{ old('contacto')}}">
								</div>
			            	</div>
			            	<hr>
			            	<div class="row text-center">
			            		<div class="col-md-4 offset-md-4">
			            			<h6>Datos de Facturación</h6>
			            		</div>
			            	</div>
			            	<div class="row">
								<div class="input-group input-group-sm mb-2 col-md-10 offset-md-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">N.I.T.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
								  	</div>
								  	<input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" id="facturacion_nit" name="facturacion_nit" value="{{ old('facturacion_nit')}}">
								</div>
							</div>
							<div class="row">
								<div class="input-group input-group-sm mb-2 col-md-10 offset-md-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">Nombre&nbsp;&nbsp;&nbsp;</label>
								  	</div>
								  	<input type="text" class="form-control" placeholder="nombre para Factura" aria-label="Username" aria-describedby="basic-addon1" id="facturacion_nombre" name="facturacion_nombre" value="{{ old('facturacion_nombre')}}">
								</div>
							</div>
							<div class="row">
								<div class="input-group input-group-sm mb-2 col-md-10 offset-md-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">Dirección</label>
								  	</div>
								  	<input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" id="facturacion_direccion" name="facturacion_direccion" value="{{ old('facturacion_direccion')}}">
								</div>					
							</div>
							<hr>
							<div class="row">
								<div class="form-group offset-md-1">
				                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
				                      	<input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
			                      		<label class="custom-control-label" for="estado">Activar</label>
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
    		<div class="modal-content">
      			<form role="form" method="POST" action="{{route('aseguradora_actualizar')}}">
	      			@csrf
	      			<div class="card">
	        			<div class="card-header" style="background-color: #F4F6F7;">
	      					<div class="row">
	        					<div class="col-md-9">
	        						<h6>Edición de Registro</h6>
	        					</div>
	        					<div class="col-md-3" style="text-align: right;">
	        						<button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
	        						<button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar"> <i class="fas fa-sign-out-alt"></i></button>	
	        					</div>
	        				</div>
	      				</div>
	      				<div class="card-body">
	      					<input type="hidden" id="eid" name="eid">
	      					<div class="row">
								<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">Nombre</label>
								  	</div>
								  	<input type="text" class="form-control" placeholder="nombre aseguradora" aria-label="Username" aria-describedby="basic-addon1" id="enombre" name="enombre" autofocus required>
								</div>
							</div>
							<div class="row">
								<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">Dirección</label>
								  	</div>
								  	<input type="text" class="form-control" placeholder="direccion" aria-label="Username" aria-describedby="basic-addon1" id="edireccion" name="edireccion">
								</div>
			            	</div>
			            	<div class="row">
								<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">Teléfonos</label>
								  	</div>
								  	<input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" placeholder="telefonos" id="etelefonos" name="etelefonos">
								</div>
							</div>
							<div class="row">
								<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">Contacto</label>
								  	</div>
								  	<input type="text" class="form-control" placeholder="nombre contacto" aria-label="Username" aria-describedby="basic-addon1" id="econtacto" name="econtacto">
								</div>
			            	</div>
			            	<hr>
			            	<div class="row text-center">
			            		<div class="col-md-4 offset-md-4">
			            			<h6>Datos de Facturación</h6>
			            		</div>
			            	</div>
			            	<div class="row">
								<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">N.I.T.</label>
								  	</div>
								  	<input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" id="efacturacion_nit" name="efacturacion_nit">
								</div>
							</div>
							<div class="row">
								<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">Nombre</label>
								  	</div>
								  	<input type="text" class="form-control" placeholder="nombre para Factura" aria-label="Username" aria-describedby="basic-addon1" id="efacturacion_nombre" name="efacturacion_nombre">
								</div>
							</div>
							<div class="row">
								<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
							  		<div class="input-group-prepend">
								    	<label class="input-group-text">Dirección</label>
								  	</div>
								  	<input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" id="efacturacion_direccion" name="efacturacion_direccion">
								</div>					
							</div>
							<hr>
							<div class="row">
								<div class="form-group offset-md-1">
				                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
				                      	<input type="checkbox" class="custom-control-input" id="eestado" name="eestado" value="A">
			                      		<label class="custom-control-label" for="eestado">Activar</label>
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