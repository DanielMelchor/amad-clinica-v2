@extends('admin.layout')

@section('titulo')
	Medicamento
@endsection
@section('subtitulo')
	Listado de Dosis
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
						<div class="row">
							<div class="col-md-11 text-center"><h3>Medicamento {{ $pMedicamento->nombre }}</h3></div>
							<div class="col-md-1">
								<a href="{{route('medicamentos')}}" class="btn btn-sm btn-danger fa fa-close" title="Regresar a lista de medicamentos" style="text-align: right; color: red;"> Cerrar </a>
							</div>
						</div>
					</div>

					<div class="panel-body">
						<div class="row">
							<div class="col-md-10 col-md-offset-1">
								<br>
							</div>
							<table class="table col-md-offset-1">
								<tr>
									<th scope="col">Dosis</th>
									<th scope="col">Estado</th>
								</tr>
								<!-- {{ $pMedicamento }} -->
								@foreach($pMedicamentoDosis as $pMedicamentoDos)
									<tr>
										<!-- <td><h4> {{ $pMedicamentoDos->dosis_descripcion }}</h4></td> -->
										<td class="col-md-4"><h4><a href="{{route('editar_medicamentodosis' , $pMedicamentoDos->id)}}">{{ $pMedicamentoDos->dosis_descripcion }}</a></h4></td>
										@if( $pMedicamentoDos->estado == 'A')
											<td><h4> Alta </h4></td>
										@else
											<td><h4> Baja </h4></td>
										@endif
										<!--  <td>
											<button type="button" class="btn btn-success" title="Editar Dosis" data-toggle="modal" data-target="#ModalEditar">
												Editar
											</button>
										</td> -->
									</tr>
								@endforeach
							</table>
						</div>
						<div class="row">
							<div class="col col-md-offset-1">
								<a href="{{ route('crear_medicamentodosis', $pMedicamento->id) }}" class="btn btn-primary" title="Agregar dosis a medicamento">Agregar</a>
								<!-- <button type="button" class="btn btn-primary" title="Agregar Dosis" data-toggle="modal" data-target="#ModalAgregar">
									Agregar
								</button> -->
							</div>
						</div>
						<!-- {{ trans('adminlte_lang::message.logged') }} -->
					</div>
				</div>
			</div>
		</div>
		<!-- Modal Agregar-->
		<div class="modal fade" id="ModalAgregar" tabindex="-1" role="dialog" aria-labelledby="ModalAgregarLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<h4 class="modal-title" id="ModalAgregarLabel">Agregar Dosis</h4>
		      		</div>
		      	<div class="modal-body">
		        	<div class="container-fluid spark-screen">
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-info">
									<div class="panel-heading">
									</div>
						        	<form role="form" method="post" action="{{ route('grabar_medicamentodosis') }}">
							        	@csrf
							        	<div class="panel-body">
									    	<div class="box box-primary">
										        <div class="row">
										        	<div class="col">
										        		<div class="form-group">
								                  			<input type="hidden" class="form-control" id="medicamento_id" name="medicamento_id" value="{{ $pMedicamento->id }}">
									                	</div>
										        	</div>
										        </div>
										        
							                	<div class="row">
							                		<div class="col">
							                			<div class="form-group col-md-7 col-md-offset-1">
											            	<label>Dosis</label>
										                	<select id ="dosis_id" name="dosis_id" class="form-control has-success select2">
										                  		<option value=""> Seleccionar.... </option>
										                  		@foreach($pDosis as $pDos)
														        	<option value="{{ $pDos->id }}"> {{ $pDos->descripcion}} </option>
														        @endforeach
										                	</select>
									                	</div>
							                		</div>
							                	</div>
							                	<div class="row">
							                		<div class="col-md-2 col-md-offset-1">
							                			<div class="checkbox">
									                  		<label> Estado </label>
								                    		<input type="checkbox" data-toggle="toggle" data-onstyle="info" data-size="lg" id="estado" name="estado" value="A" data-on="Alta" data-off="Baja">
									                  		
								                		</div>
							                		</div>
							                	</div>

							                	<div class="row">
							                		
							                	</div>
											</div>
										</div>
										<div class="panel-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		        							<button type="submit" class="btn btn-primary">Grabar</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
		      	</div>
		      		<div class="modal-footer">
		        		
		      		</div>
		    	</div>
		  	</div>
		</div>
		<!-- /Modal Agregar-->
		<!-- Modal Editar-->
		<div class="modal fade" id="ModalEditar" tabindex="-1" role="dialog" aria-labelledby="ModalEditarLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<h4 class="modal-title" id="ModalEditarLabel">Edición de Dosis</h4>
		      		</div>
		      	<div class="modal-body">

		      	</div>
	      	</div>
	    </div>

	</div>
@endsection