@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
	<link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
	<style type="text/css">
        .nav-pills .nav-link.active,
        .show>.nav-pills .nav-link{
            background: #7FB3D5 !important;
            color: #000000 !important;
        }
        .numero{
            text-align: right;
        }
    </style>
@endsection
@section('title', 'Especialidades')

@section('content_header')
  <br>
@endsection

@section('content')
    <div class="row">
		<div class="col-md-10 offset-md-1">
			<form role="form" method="POST" action="{{route('grabar_medico')}}" enctype="multipart/form-data">
		    	@csrf
			    <div class="card">
			    	<div class="card-header" style="background-color: #E1E8ED;">
			    		<div class="row">
							<div class="col-md-9">
								<h5>Nuevo Medico</h5>
							</div>
							<div class="col-md-3" style="text-align: right;">
								<button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
								<a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Regresar a lista de Pacientes" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
							</div>
						</div>
			    	</div>
			    	<div class="card-body">
			    		<ul class="nav nav-pills nav-fill ml-auto p-2">
					  		<li class="nav-item">
						    	<a class="nav-link active" href="#generales" data-toggle="tab">Generales</a>
						  	</li>
						  	<li class="nav-item">
						    	<a class="nav-link" href="#firma" data-toggle="tab">Firma</a>
						  	</li>
						  	<li class="nav-item">
						    	<a class="nav-link" href="#especialidades" data-toggle="tab">Especialidades</a>
						  	</li>
						  	<li class="nav-item">
						    	<a class="nav-link" href="#receta" data-toggle="tab">Receta</a>
						  	</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="generales">
								<br>
								<div class="row text-center">
							    	<div class="input-group input-group-sm mb-1 col-md-5 offset-md-1">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text">Nombres</label>
						          		</div>
						          		<input type="text" class="form-control" placeholder="nombres" id="nombres" name="nombres" autofocus required value="{{ old('nombres')}}">
						        	</div>
						        	<div class="input-group input-group-sm mb-1 col-md-5">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text">Apellidos</label>
						          		</div>
						          		<input type="text" class="form-control" placeholder="Apellidos" id="apellidos" name="apellidos" required value="{{ old('apellidos')}}">
						        	</div>
								</div>
								<div class="row text-center">
									<div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text">Dirección</label>
						          		</div>
						          		<input type="text" class="form-control" placeholder="direccion" id="direccion" name="direccion" value="{{ old('direccion')}}">
						        	</div>
								</div>

								<div class="row text-center">
							    	<div class="input-group input-group-sm mb-1 col-md-5 offset-md-1">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text">Titulo</label>
						          		</div>
						          		<input type="text" class="form-control" placeholder="Titulo" id="titulo" name="titulo" required value="{{ old('titulo')}}">
						        	</div>
						        	<div class="input-group input-group-sm mb-1 col-md-5">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text">N.I.T.</label>
						          		</div>
						          		<input type="text" class="form-control" placeholder="# Identificacion Tributaria" id="nit" name="nit" value="{{ old('nit')}}">
						        	</div>
								</div>

								<div class="row text-center">
							    	<div class="input-group input-group-sm mb-1 col-md-3 offset-md-1">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text">Teléfono</label>
						          		</div>
						          		<input type="text" class="form-control" placeholder="# telefono" id="telefono" name="telefono" value="{{ old('telefono')}}">
						        	</div>
						        	<div class="input-group input-group-sm mb-1 col-md-3">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text">Celular</label>
						          		</div>
						          		<input type="text" class="form-control" placeholder="# celular" id="celular" name="celular" value="{{ old('celular')}}">
						        	</div>
						        	<div class="input-group input-group-sm mb-1 col-md-4">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text">Localizador</label>
						          		</div>
						          		<input type="text" class="form-control" placeholder="# localizador" id="localizador" name="localizador" value="{{ old('localizador')}}">
						        	</div>
								</div>
								<div class="row">
				            		<div class="form-group input-group-sm offset-md-1">
					                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
				                      		<input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
					                      	<label class="custom-control-label" for="estado">Activar</label>
					                    </div>
				                  	</div>
				                  	<div class="form-group input-group-sm offset-md-1">
					                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
				                      		<input type="checkbox" class="custom-control-input" id="principal" name="principal" value="S">
					                      	<label class="custom-control-label" for="principal">Medico principal</label>
					                    </div>
				                  	</div>
				            	</div>
							</div>
							<br>
							<div class="tab-pane" id="firma">
								<div class="row">
			    					<div class="input-group col-md-10 offset-md-1">
					                	<label for="medico_firma">Firma Digital:</label>
					                	<input type="file" class="form-control" id="medico_firma" name="medico_firma" accept="image/*" />
					                </div>
			    				</div>
							</div>
							<div class="tab-pane" id="especialidades">
								<br>
								<div class="row text-center">
									<div class="col-md-6 offset-md-3">
										<div class="table-responsive">
											<table class="table table-sm table-hover">
												<thead>
													<tr>
														<th>
															Especialidad
														</th>
													</tr>
												</thead>
												<tbody>
													@foreach($pEspecialidades as $p)
														<tr>
															<td>{{ $p->descripcion }}</td>
															<td colspan="1">
																<div class="form-check">
																    <input type="checkbox" class="form-check-input form-group icheck-primary d-inline" id="check[]" name="check[]" value="{{ $p->id }}">
																</div>
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="receta">
								<br>
								<div class="row text-center">
									<div class="col-md-12">
										<h5 style="background-color: #cca988;">Pagina</h5>
									</div>
								</div>
								<div class="row">
									<div class="input-group input-group-sm mb-1 col-md-2 offset-md-1">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text">Largo</label>
						          		</div>
						          		<input type="number" class="form-control" placeholder="largo de pagina" id="pagina_x" name="pagina_x" step="0.01" value="{{ old('pagina_x')}}" style="text-align: right;">
						        	</div>
						        	<div class="input-group input-group-sm mb-1 col-md-2">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text">Ancho</label>
						          		</div>
						          		<input type="number" class="form-control" placeholder="Ancho de pagina" id="pagina_y" name="pagina_y" step="0.01" value="{{ old('pagina_y')}}" style="text-align: right;">
						        	</div>
									<div class="input-group input-group-sm mb-1 col-md-3">
								  		<div class="input-group-prepend">
									    	<label class="input-group-text" for="unidad_medida">Medída</label>
									  	</div>
									  	<select class="custom-select custom-select-sm select2 select2bs4" id="unidad_medida" name="unidad_medida">
									    	<option value=""> Seleccionar... </option>
								        	<option value="in"> Pulgadas </option>
								        	<option value="cm"> Centimetros </option>
								        	<option value="mm"> Milimetros </option>
									  	</select>
									</div>
									<div class="input-group input-group-sm mb-1 col-md-3">
								  		<div class="input-group-prepend">
									    	<label class="input-group-text" for="orientacion">Orientación</label>
									  	</div>
									  	<select class="custom-select custom-select-sm select2 select2bs4" id="orientacion" name="orientacion">
									    	<option value=""> Seleccionar... </option>
								        	<option value="P"> Vertical </option>
								        	<option value="L"> Horizontal </option>
									  	</select>
									</div>
								</div>
								<br>
								<div class="row text-center">
									<div class="col-md-12">
										<p class="bg-secondary">Fecha</p>
									</div>
								</div>
								<div class="row">
									<div class="input-group input-group-sm mb-1 col-md-2">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text">Dia X</label>
						          		</div>
						          		<input type="number" class="form-control" id="dia_x" name="dia_x" value="{{ old('dia_x')}}" step="0.01" style="text-align: right;">
						        	</div>
						        	<div class="input-group input-group-sm mb-1 col-md-2">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text">Dia Y</label>
						          		</div>
						          		<input type="number" class="form-control" id="dia_y" name="dia_y" value="{{ old('dia_y')}}" step="0.01" style="text-align: right;">
						        	</div>
						        	<div class="input-group input-group-sm mb-1 col-md-2">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text">Mes X</label>
						          		</div>
						          		<input type="number" class="form-control" id="mes_x" name="mes_x" value="{{ old('mes_x')}}" step="0.01" style="text-align: right;">
						        	</div>
						        	<div class="input-group input-group-sm mb-1 col-md-2">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text">Mes Y</label>
						          		</div>
						          		<input type="number" class="form-control" id="mes_y" name="mes_y" value="{{ old('mes_y')}}" step="0.01" style="text-align: right;">
						        	</div>
						        	<div class="input-group input-group-sm mb-1 col-md-2">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text">Año X</label>
						          		</div>
						          		<input type="number" class="form-control" id="anio_x" name="anio_x" value="{{ old('anio_x')}}" step="0.01" style="text-align: right;">
						        	</div>
						        	<div class="input-group input-group-sm mb-1 col-md-2">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text">Año Y</label>
						          		</div>
						          		<input type="number" class="form-control" id="anio_y" name="anio_y" value="{{ old('anio_y')}}" step="0.01" style="text-align: right;">
						        	</div>
								</div>
								<br>
								<div class="row text-center">
									<div class="col-md-6">
										<p class="bg-secondary">Paciente</p>
									</div>
									<div class="col-md-6">
										<p class="bg-secondary">Tratamiento</p>
									</div>
								</div>
								<div class="row">
									<div class="input-group input-group-sm mb-1 col-md-2 offset-md-1">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text"> X </label>
						          		</div>
						          		<input type="number" class="form-control" id="paciente_x" name="paciente_x" value="{{ old('paciente_x')}}" step="0.01" style="text-align: right;">
						        	</div>
						        	<div class="input-group input-group-sm mb-1 col-md-2">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text"> Y </label>
						          		</div>
						          		<input type="number" class="form-control" id="paciente_y" name="paciente_y" value="{{ old('paciente_y')}}" step="0.01" style="text-align: right;">
						        	</div>
						        	<div class="input-group input-group-sm mb-1 col-md-2 offset-md-2">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text"> X </label>
						          		</div>
						          		<input type="number" class="form-control" id="tratamiento_x" name="tratamiento_x" value="{{ old('tratamiento_x')}}" step="0.01" style="text-align: right;">
						        	</div>
						        	<div class="input-group input-group-sm mb-1 col-md-2">
						          		<div class="input-group-prepend">
						        			<label class="input-group-text"> Y </label>
						          		</div>
						          		<input type="number" class="form-control" id="tratamiento_y" name="tratamiento_y" value="{{ old('tratamiento_y')}}" step="0.01" style="text-align: right;">
						        	</div>
								</div>
							</div>
						</div>
			    	</div>
			    </div>
			</form>
		</div>
	</div>
@endsection
@section('js')
	<script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
	@if(Session::get('type') == 'success')
        @if(Session::has('message'))
            <script>
                
                setTimeout(function() {
                    swal({
                        title: "Trabajo Finalizado",
                        text: "{!! Session::get('message') !!}",
                        type: "success"
                    }
                    // , function() {
                    //     window.location = "{{ route('empresas') }}";
                    // }
                    );
                }, 1000);
            </script>
        @endif
    @endif
    @if(Session::get('type') == 'error')
        @if(Session::has('message'))
            <script>
                setTimeout(function() {
                    swal({
                        title: "Error",
                        text: "{!! Session::get('message') !!}",
                        type: "error"
                    }
                    // , function() {
                    //     window.location = "{{ route('empresas') }}";
                    // }
                    );
                }, 1000);
            </script>
        @endif
    @endif
<script type="text/javascript">
	function confirma_salida(){
        swal({
            title: 'Confirmación',
            text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?"
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn-success',
            cancelButtonClass: 'btn-danger',
            confirmButtonText: 'Si',
            cancelButtonText: 'No',
            closeOnConfirm: false,
            allowEscapeKey: true
            },
            function(isConfirm) {
                if (isConfirm) { 
                    window.location.href = "{{ route('medicos') }}";
                                } 
                /*else { 
                    swal("Cancelled", "Your imaginary file is safe :)", "error"); 
                    }*/
            }
        );
    }
</script>
@endsection