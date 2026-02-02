@extends('admin.layout')

@section('titulo')
	Hospitales
@endsection
@section('subtitulo')
	Agregar Correlativo de Facturas
@endsection

@section('contenido')
	@if(Session::has('message'))
   		<div class="alert alert-success alert-dismissible" role="alert">
	    	<button type="button" class="close" data-dismiss="alert" arial-label="Close"><span aria-hidden="true">x</span>
	    	</button>
	        {{ Session::get('message') }}  
	    </div>
	@endif
	<div class="row">
        <div class="col">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-info">
					<div class="panel-heading">
						<a href="{{route('hospitales')}}" class="btn btn-sm btn-danger fa fa-close" title="Regresar a lista de medicos" style="text-align: right; color: red;"> Cerrar </a>
					</div>

					<div class="panel-body">
						<div class="row">
						    <div class="col-lg-12">
						    	<div class="box box-primary">
						        	<!-- form start -->
					            	<form role="form" method="POST" action="">
					            		@csrf
					            		<div class="box-header">
					            			<div class="row">
					            				<div class="col-md-5 col-md-offset-1">
					            					<label for="nombre">Nombre</label>
						                  			<input type="text" class="form-control" id="nombre" name="nombre" value="{{ $pHospital->nombre }}" disabled="true">
					            				</div>
					            				<div class="col-md-5">
					            					<label for="telefonos">Teléfonos</label>
						                  			<input type="text" class="form-control" id="telefonos" name="telefonos" value="{{ $pHospital->telefonos }}" disabled="true">
					            				</div>
					            			</div>
					            			<div class="row">
					            				<div class="col-md-10 col-md-offset-1">
					            					<label for="direccion">Dirección</label>
						                  			<input type="text" class="form-control" id="direccion" name="direccion" value="{{ $pHospital->direccion }}" disabled="true">
					            				</div>
					            			</div>
					            			<div class="row">
					            				<div class="col-md-5 col-md-offset-1">
					            					<label for="contacto">Contacto</label>
						                  			<input type="text" class="form-control" id="contacto" name="contacto" value="{{ $pHospital->contacto }}" disabled="true">
					            				</div>
					            			</div>
					            		</div>
					              		<div class="box-body">
						                	<div class="row">
						                		<div class="col-md-10 col-md-offset-1">
						                			<table class="table table-hover">
								                		<thead>
								                			<tr>
								                				<th scope="col">SERIE</th>
								                				<th scope="col">CORR. INICIAL</th>
								                				<th scope="col">CORR. FINAL</th>
								                				<th scope="col">ESTADO</th>
								                			</tr>
								                			@if (@isset($pcorrelativos))
									                			@foreach($pCorrelativos as $pCorrelativo)
									                			<tr class="text-center">
									                				<td scope="col"><H4>{{ $pCorrelativo->serie}}</H4></td>
									                				<td scope="col"><H4>{{ $pCorrelativo->correlativo_inicial}}</H4></td>
									                				<td scope="col"><H4>{{ $pCorrelativo->correlativo_final}}</H4></td>
									                				@if($pCorrelativo->estado == 'A')
																		<td class="col-md-1"><h4>Alta</h4></td>
																	@else
																		<td class="col-md-1"><h4>Baja</h4></td>
																	@endif
									                				<td>
									                					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalEdit">
																		  Editar
																		</button>
																	</td>
									                			</tr>
									                			@endforeach
								                			@endif
								                		</thead>
								                	</table>
						                		</div>
						                	</div>
						                	<div class="row text-center">
						                		
						                	</div>
						                	<div class="row">
						                		<div class="col col-md-offset-1">
						                			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalcreate">
													  Agregar Correlativo
													</button>

						                		</div>
						                	</div>
						                </div>
						            </form>
						        </div>
						        <!-- Modal -->
								<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							  		<div class="modal-dialog modal-dialog-centered" role="document">
								    	<div class="modal-content">
								      		<div class="modal-header">
								        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								          			<span aria-hidden="true">&times;</span>
								        		</button>
								      		</div>
							      			<div class="modal-body">
								      			<div class="box box-primary">
								      				<div class="box-header">
								      					<h3 class="modal-title" id="exampleModalCenterTitle">Correlativo de Facturas</h3>
								      				</div>
								      				<div class="box-body">
								      					<form role="form" method="POST" action="{{ route('actualizar_correlativo', $pCorrelativo->id) }}">
									        			@csrf
										        		<div class="row">
										        			<div class="col">
									        					<input type="hidden" class="form-control" id="hospital_id" name="hospital_id" value="{{ $pHospital->id }}">
										        			</div>
										        		</div>
										        		@if (@isset($pcorrelativos))
											        		@foreach($pCorrelativos as $pCorrelativo)
											        		<div class="row">
									        					<input type="hidden" class="form-control" id="correlativo_id" name="correlativo_id" value="{{ $pCorrelativo->id }}">										        			
								            					<div class="col-md-2 col-md-offset-2">
								            						<label for="serie">SERIE</label>
									                  				<input type="text" class="form-control" id="serie" name="serie"autofocus="true" value="{{ $pCorrelativo->serie }}" style="text-transform:uppercase;">
								            					</div>
								            					<div class="col-md-3">
								            						<label for="correlativo_inicial">CORR. INICIAL</label>
									                  				<input type="number" class="form-control" id="correlativo_inicial" name="correlativo_inicial" value="{{ $pCorrelativo->correlativo_inicial }}" style="text-align: right;">
								            					</div>
								            					<div class="col-md-3">
								            						<label for="correlativo_final">CORR. FINAL</label>
									                  				<input type="number" class="form-control" id="correlativo_final" name="correlativo_final" value="{{ $pCorrelativo->correlativo_final }}" style="text-align: right;">
								            					</div>
								            				</div>
								            				@endforeach
							            				@endif
							            				<div class="modal-footer">
												        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
												        	<button type="submit" class="btn btn-primary">Grabar</button>
												    	</div>
										        	</form>
								      			</div>
								      		</div>
							    		</div>
							  		</div>
								</div>
								<!-- Modal Edit -->
						        <!-- Modal Create-->
								<div class="modal fade" id="modalcreate" tabindex="-1" role="dialog" aria-labelledby="modalcreateTitle" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" role="document">
								    	<div class="modal-content">
								      		<div class="modal-header">
								        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								          			<span aria-hidden="true">&times;</span>
								        		</button>
								      		</div>
							      			<div class="modal-body">
								      			<div class="box box-primary">
								      				<div class="box-header">
								      					<h3 class="modal-title" id="exampleModalCenterTitle">Correlativo de Facturas</h3>
								      				</div>
								      				<div class="box-body">
								      					<form role="form" method="POST" action="{{ route('grabar_correlativo') }}">
									        			@csrf
										        		<div class="row">
										        			<div class="col">
									        					<input type="hidden" class="form-control" id="hospital_id" name="hospital_id" value="{{ $pHospital->id }}">
										        			</div>
										        		</div>
										        		<div class="row">
							            					<div class="col-md-2 col-md-offset-2">
							            						<label for="serie">SERIE</label>
								                  				<input type="text" class="form-control" id="serie" name="serie"autofocus="true" value="{{ old('serie')}}" style="text-transform:uppercase;">
							            					</div>
							            					<div class="col-md-3">
							            						<label for="correlativo_inicial">CORR. INICIAL</label>
								                  				<input type="number" class="form-control" id="correlativo_inicial" name="correlativo_inicial" value="{{ old('correlativo_inicial')}}" style="text-align: right;">
							            					</div>
							            					<div class="col-md-3">
							            						<label for="correlativo_final">CORR. FINAL</label>
								                  				<input type="number" class="form-control" id="correlativo_final" name="correlativo_final" value="{{ old('correlativo_final')}}" style="text-align: right;">
							            					</div>
							            				</div>
							            				<div class="modal-footer">
												        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
												        	<button type="submit" class="btn btn-primary">Grabar</button>
												    	</div>
										        	</form>
								      			</div>
								      		</div>
							    		</div>
							  		</div>
								</div>
								<!-- /modal create -->
							</div>
						</div>
						<!-- {{ trans('adminlte_lang::message.logged') }} -->
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection