<!-- agregar Modal -->
	<div class="modal fade" id="agregarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="agregarModalCenterTitle" aria-hidden="true">
  		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    		<div class="modal-content">
      			<form role="form" id="formaNuevoRegistro" method="POST" action="{{route('inv_familia_grabar')}}">
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
								    	<span class="input-group-text">Nombre</span>
								  	</div>
								  	<input type="text" class="form-control" placeholder="nombre" aria-label="Username" aria-describedby="basic-addon1" id="nombre" name="nombre" autofocus required value="{{ old('nombre')}}">
								</div>
					    	</div>
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
      			<form role="form" method="POST" action="{{route('inv_familia_actualizar')}}">
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
								    	<span class="input-group-text">Nombre</span>
								  	</div>
								  	<input type="text" class="form-control" placeholder="nombre" aria-label="Username" aria-describedby="basic-addon1" id="enombre" name="enombre" autofocus required>
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
	      				</div>
	      			</div>
      			</form>
    		</div>
  		</div>
	</div>
	<!-- /editar Modal -->