@extends('adminlte::page')
@section('css')
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
	<style type="text/css">
    	.nav-pills .nav-link.active,
		.show>.nav-pills .nav-link{
		    background: #7FB3D5 !important;
		    color: white!important;
		}
		/*.table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
		   background-color: #D0E8F3 !important;
		}*/
		a[disabled] {
		    pointer-events: none;  /* Deshabilita el clic */
		    opacity: 0.5;          /* Hace que se vea deshabilitado */
		}
		.btn-sm{
            padding: 5px 10px; /* Reducir el espacio dentro del botón */
            font-size: 12px; /* Reducir el tamaño de la fuente */
            border-radius: 4px; /* Opcional: redondear las esquinas */
        }
        .table-responsive {
            max-width: 100%; /* Ajusta el ancho según tus necesidades */
            overflow-x: auto; /* Permite el desplazamiento horizontal */
        }
        .btn-xs{
            padding: 4px 7px; /* Reducir el espacio dentro del botón */
            font-size: 10px; /* Reducir el tamaño de la fuente */
            border-radius: 4px; /* Opcional: redondear las esquinas */
        }

        .table-active {
		    background-color: #c3ab95 !important; /* amarillo claro */
		}


		/* Permite que las pestañas de salas se deslicen lateralmente en móvil */
	    #salasTab::-webkit-scrollbar {
	        display: none;
	    }
	    #salasTab {
	        -ms-overflow-style: none;
	        scrollbar-width: none;
	    }

	    /* Aumentar el área de clic en móviles para las filas de la tabla */
	    /*.table-sm td {
	        padding: 0.75rem 0.3rem !important; 
	        vertical-align: middle;
	    }*/

	    /* Ajuste para que el input datetime-local se vea bien en iOS/Android */
	    input[type="datetime-local"] {
	        min-height: 38px;
	    }

	    /* Ajustes de botones para pulgares */
	    .btn-sm.rounded-circle {
	        width: 35px;
	        height: 35px;
	        line-height: 24px;
	        text-align: center;
	        padding: 5px 0;
	    }

	    /* Cambiar el estilo de los botones del SweetAlert */
		.swal2-styled.swal2-confirm {
		    border-radius: 50px !important;
		    background-color: #28a745 !important; /* Verde success */
		    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
		}

		.swal2-popup {
		    border-top: 3px solid #007bff; /* Línea azul arriba como las cards de AdminLTE */
		}

		.mi-clase-personalizada {
		    font-family: 'Source Sans Pro', sans-serif;
		    border: 2px solid #A5C890;
		}

		.mi-boton-redondo {
		    border-radius: 20px !important;
		    text-transform: uppercase;
		    font-weight: bold;
		}

		@media (max-width: 576px) {
		    .swal2-popup {
		        width: 90% !important; /* Que ocupe casi todo el ancho en celular */
		        font-size: 0.8rem !important;
		    }
		    
		    .swal2-title {
		        font-size: 1.2rem !important;
		    }
		}

    </style>
@endsection
@section('title', 'Agenda')
@section('content')
	<div class="row">
		<div class="col-md-12">
			<br><link rel="stylesheet" href="{{ asset('css/custom.css') }}">
			<div class="card">
		  		<div class="card-header" style="background-color: #E1E8ED;">
			    	<div class="row align-items-center"> <div class="col-12 col-lg-2 mb-2">
				        <div class="input-group input-group-sm">
				            <div class="input-group-prepend">
				                <label class="input-group-text">Médico</label>
				            </div>
				            <select class="custom-select select2 select2bs4" id="f_medico_id" onchange="getMedico(this);">
				                @foreach($medicos as $m)
				                    <option value="{{ $m->id }}" @if($m->principal == 'S') selected @endif> {{ $m->nombre_completo}} </option>
				                @endforeach
				            </select>
				        </div>
				    </div>

				    <div class="col-12 col-lg-2 mb-2">
				        <div class="input-group input-group-sm"> <div class="input-group-prepend">
				                <label class="input-group-text">Fecha</label>
				            </div>
				            <input type="date" class="form-control" id="fecha_filtro" value="{{ $today }}" onchange="getFecha(this);">
				        </div>
				    </div>

				    <div class="col-12 col-lg-2 mb-2">
				        <div class="input-group input-group-sm">
				            <div class="input-group-prepend">
				                <label class="input-group-text">Estado</label>
				            </div>
				            <select class="custom-select select2 select2bs4" id="estado" onchange="getEstado(this);">
				                <option value="T">Todas</option>
				                <option value="A" selected>Activas</option>
				                <option value="C">Canceladas</option>
				                <option value="R">Realizadas</option>
				            </select>
				        </div>
				    </div>

				    <div class="col-12 col-lg-6 ml-auto d-flex justify-content-center justify-content-lg-end mt-2 mt-lg-0">
				        <div class="btn-group-responsive">
				            <a href="#" id="btnAsistencia" class="btn btn-xs btn-outline-primary rounded-circle elevation-2 mx-1" onclick="confirmarPresencia()" title="Asistencia"><i class="fas fa-thumbs-up"></i></a>
				            <a href="#" id="btnCita" class="btn btn-xs btn-outline-primary rounded-circle elevation-2 mx-1" title="Cita" onclick="fnEditarCita();"><i class="fas fa-plus"></i></a>
				            <a href="#" id="btnAdmision" class="btn btn-xs btn-outline-primary rounded-circle elevation-2 mx-1" title="Admisión" onclick="fnCrearAdmision();"><i class="fas fa-wheelchair"></i></a>
				            <a href="#" id="btnFinalizar" class="btn btn-xs btn-outline-success rounded-circle elevation-2 mx-1" title="Finalizar" onclick="fnFinalizar();"><i class="fas fa-thumbs-up"></i></a>
				            <a href="#" id="btnCancelar" class="btn btn-xs btn-outline-danger rounded-circle elevation-2 mx-1" title="Cancelar" onclick="fnCancelar();"><i class="fas fa-ban"></i></a>
				            <a href="#" id="btnHistorico" class="btn btn-xs btn-outline-secondary rounded-circle elevation-2 mx-1" title="Histórico" onclick="fnHistorico();"><i class="fas fa-book-medical"></i></a>
				            <a href="#" id="btnBloqueo" class="btn btn-xs btn-outline-secondary rounded-circle elevation-2 mx-1" title="Bloquear" onclick="fnBloqueo();"><i class="fas fa-lock"></i></a>
				        </div>
				    </div>
				</div>
			  	</div>
			  	<div class="card-body">
			    	<input type="hidden" id="sala_seleccionada_id" name="sala_seleccionada_id" value="{{ $sala_seleccionada }}">
			    	<div class="card">
				    	<div class="card-header p-2" style="background-color: #f4f6f9;">
						    <ul class="nav nav-pills nav-fill d-flex flex-nowrap overflow-auto" id="salasTab" style="white-space: nowrap;-webkit-overflow-scrolling: touch;">
						        @foreach($salas as $sala)
						            <li class="nav-item">
						                <a class="nav-link py-1 px-3 {{ $sala_seleccionada == $sala->id ? 'active' : '' }}" 
						                   href="#sala{{ $sala->id}}" 
						                   data-toggle="tab" 
						                   id="nav-link-sala{{$sala->id}}" 
						                   onclick="fnDefinirSala({{ $sala->id}});">
						                   {{ $sala->sala_nombre }}
						                </a>
						            </li>
						        @endforeach
						    </ul>
						</div>
				    	<div class="card-body">
				    		<div class="tab-content">
				    			@foreach($salas as $sala)
				    				@if($sala_seleccionada == $sala->id )
				    					<div class="tab-pane active" id="sala{{ $sala->id}}">
				    				@else
				    					<div class="tab-pane" id="sala{{ $sala->id}}">
				    				@endif
					    				<div id="contenedor_{{$sala->id}}" class="overflow-auto">
											<div class="table-responsive">
											    <table id="tbl{{$sala->id}}" class="table table-sm table-striped table-hover text-nowrap" width="100%">
											    	<thead>
														<tr class="text-center" style="font-size: 12px;">
															<th colspan="1">Hora</th>
															<th colspan="3">Paciente</th>
															<th colspan="1">Telefono</th>
															<th colspan="1">Expediente</th>
															<th colspan="1"># Admisión</th>
															<th colspan="1">Estado</th>
														</tr>
													</thead>
													<tbody>
														<tr></tr>
													</tbody>
										        </table>
											</div>
										</div>
				    				</div>
				    			@endforeach
				    		</div>
				    	</div>
				    </div>
			  	</div>
			</div>
		</div>
	</div>
	<!-- editar registro -->
    <div class="modal fade" id="editarRegistro" role="dialog" aria-labelledby="editarRegistroModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="editarRegistroForm" name="editarRegistroForm" action="#">
                    @csrf
                    <div class="card">
                        <div class="card-header" style="background-color: #E1E8ED;">
                        	<div class="row">
                        		<div class="col-md-9">
		                            <h6>Edición de Horario</h6>
		                        </div>
		                        <div class="col-md-3" style="text-align: right;">
									<button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar cambios"><i class="fas fa-save"></i></button>
									<a href="{{ route('crear_paciente',['P', 0]) }}" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Crear nuevo Paciente" target="_blank"><i class="fas fa-plus-circle"></i></a>
									<button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
								</div>
                        	</div>
						</div>
                        <div class="card-body">
							<div class="row">
								<div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
									<div class="input-group-prepend">
										<label class="input-group-text" for="fecha_cita">Fecha&nbsp;&nbsp;</label>
									</div>
									<input type="datetime-local" class="form-control" id="fecha_cita" name="fecha_cita" readonly>
								</div>
							</div>
							<div class="row">
								<div class="input-group input-group-sm col-10 offset-1 mb-1">
									<div class="input-group-prepend">
	                                    <label class="input-group-text" for="edit_paciente_id">Paciente&nbsp;&nbsp;&nbsp;</label>
	                                </div>
	                                <select class="custom-select custom-select-sm select2bs4" id="edit_paciente_id" name="edit_paciente_id" onchange="actualiza_nombre_completo();">
	                                    <option value="" selected>Paciente Sin Ficha...</option>
	                                    @foreach($pacientes as $p)
	                                        <option value="{{ $p->id }}"> {{ $p->nombre_completo}} </option>
	                                    @endforeach
	                                </select>
								</div>
								<div class="col-lg-1 col-sm-1" style="text-align: right;">
									<a href="#" class="btn btn-xs btn-outline-secondary rounded-circle elevation-4" title="Recargar Pacientes" onclick="fnActualizarPacientes();"><i class="fas fa-sync"></i></a>
								</div>
							</div>
							<div class="row">
								<div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
	                                <div class="input-group-prepend">
	                                    <label class="input-group-text" for="edit_nombre_completo">Nombre&nbsp;&nbsp;&nbsp;&nbsp;</label>
	                                </div>
	                                <input type="text" class="form-control" id="edit_nombre_completo" name="edit_nombre_completo" required value="">
	                            </div>
							</div>
							<div class="row">
	                            <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
	                                <div class="input-group-prepend">
	                                    <label class="input-group-text">Telefono&nbsp;&nbsp;&nbsp;</label>
	                                </div>
	                                <input type="text" class="form-control" id="edit_telefonos" name="edit_telefonos" required value="{{ old('edit_telefonos') }}">
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="mb-1 col-md-10 offset-md-1">
	                                <div class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text" for="edit_hospital_id">Ubicación</label>
	                                    </div>
	                                    <select class="custom-select  custom-select-sm select2bs4 form-control" id="edit_hospital_id" name="edit_hospital_id" required>
	                                        <option value="" selected>Seleccionar...</option>
	                                        @foreach($hospitales as $h)
	                                            <option value="{{ $h->id }}" @if($h->principal_agenda == 'S') selected @endif> {{ $h->nombre }} </option>
	                                        @endforeach
	                                    </select>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="row">
	                        	<div class="mb-1 col-md-10 offset-md-1">
	                                <div class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text" for="edit_medico_id">Medico&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	                                    </div>
	                                    <select class="custom-select  custom-select-sm select2bs4 form-control" id="edit_medico_id" name="edit_medico_id" required>
	                                        <option value="">Seleccionar...</option>
	                                        @foreach($medicos as $m)
	                                            <option value="{{ $m->id }}"@if($m->principal == 'S') selected @endif> {{ $m->nombre_completo }} </option>
	                                        @endforeach
	                                    </select>
	                                </div>
	                            </div>
	                        </div>
                        	<div class="row text-center">
	                            <div class="form-group col-md-10 offset-md-1">
	                                <label for="antmedico_descripcion">Observaciones</label>
	                                <textarea class="form-control form-control-sm" id="edit_observaciones" name="edit_observaciones" rows="3" maxlength="190">{{ old('observaciones') }}</textarea>
	                            </div>
	                        </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /editar registro -->
    <!-- bloqueo -->
    <div class="modal fade" id="bloqueoModal" role="dialog" aria-labelledby="admisionModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="bloqueoForm" name="bloqueo" action="#">
                    @csrf
                    <div class="card">
                        <div class="card-header" style="background-color: #E1E8ED;">
                            <div class="row">
                                <div class="col-md-9">
                                    <h6>Bloquear horario</h6>
                                </div>
                                <div class="col-md-3" style="text-align: right;">
									<button type="submit" class="btn btn-xs btn-success rounded-circle elevation-4" title="Guardar cambios"><i class="fas fa-save"></i></button>
									<button type="button" class="btn btn-xs btn-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
								</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="form-group col-md-10 offset-md-1">
                                    <label for="bloqueo_espacio_observaciones">Observaciones</label>
                                    <textarea class="form-control form-control-sm" id="bloqueo_espacio_observaciones" name="bloqueo_espacio_observaciones" rows="3" maxlength="190" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /bloqueo -->
    <!-- Modal nueva admision-->
    <div class="modal fade" id="nuevaAdmisionModal" role="dialog" aria-labelledby="nuevaAdmisionModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="admisionForm" action="#">
                <!--<form class="form" method="POST" action="{{route('grabar_admision')}}">-->
                    @csrf
                    <div class="card">
                        <div class="card-header" style="background-color: #E1E8ED;">
                        	<div class="row">
                        		<div class="col-md-9">
		                            <h6>Nueva Admisión</h6>
		                        </div>
		                        <div class="col-md-3" style="text-align: right;">
									<button type="submit" class="btn btn-xs btn-success rounded-circle elevation-4" title="Guardar cambios"><i class="fas fa-save"></i></button>
									<button type="button" class="btn btn-xs btn-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
								</div>
                        	</div>
						</div>
                        <div class="card-body">
                            <!-- fecha admision -->
                            <div class="row">
                                <div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Fecha Admisión</label>
                                    </div>
                                    <input type="date" class="form-control form-control-sm" id="adm_fecha" name="adm_fecha" disabled required value="{{ $today }}">
                                </div>
                            </div>
                            <!-- /fecha admision -->
                            <!-- paciente -->
                            <div class="row">
                                <div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="adm_paciente_id">Paciente</label>
                                    </div>
                                    <select class="custom-select custom-select-sm select2bs4" id="adm_paciente_id" name="adm_paciente_id" disabled required>
                                        <option value="">Seleccionar.....</option>
                                        @foreach($pacientes as $p)
                                            <option value="{{ $p->id }}"> {{ $p->nombre_completo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- /paciente -->
                            <!-- medico -->
                            <div class="row">
                                <div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="adm_medico_id">Médico</label>
                                    </div>
                                    <select class="custom-select custom-select-sm select2bs4" id="adm_medico_id" name="adm_medico_id" disabled required>
                                        <option value="" selected="selected">Seleccionar.....</option>
                                        @foreach($medicos as $medico)
                                            <option value="{{ $medico->id }}" @if($medico->principal == 'S') selected @endif> {{ $medico->nombre_completo}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- /medico -->
                            <!-- hospital -->
                            <div class="row">
                                <div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="adm_hospital_id">Hospital</label>
                                    </div>
                                    <select class="custom-select custom-select-sm select2bs4" id="adm_hospital_id" name="adm_hospital_id" disabled required>
                                        <option value="" selected="selected">Seleccionar.....</option>
                                        @foreach($hospitales as $hospital)
                                            <option value="{{ $hospital->id }}"> {{ $hospital->nombre}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- /hospital -->
                            <!-- admision terceros -->
                            <div class="row">
                                <div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Admisión Terceros</label>
                                    </div>
                                    <input type="number" step="1" min="0" class="form-control" id="admision_tercero" name="admision_tercero" value="{{ old('admision_tercero')}}">
                                </div>
                            </div>
                            <!-- /admision terceros -->
                            <!-- aseguradora -->
                            <div class="row">
                                <div class="input-group-sm col-md-10 offset-md-1 mb-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="adm_aseguradora_id">Aseguradora</label>
                                        </div>
                                        <select class="custom-select custom-select-sm select2bs4" id="adm_aseguradora_id" name="adm_aseguradora_id" onchange="fn_habilitar_poliza(this.value); return false;">
                                            <option value="" selected="selected">Seleccionar.....</option>
                                            @foreach($aseguradoras as $aseguradora)
                                                    <option value="{{ $aseguradora->id }}"> {{ $aseguradora->nombre}} </option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /aseguradora -->
                            <!-- poliza -->
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Póliza No.</label>
                                    </div>
                                    <input type="text" class="form-control" id="poliza_no" name="poliza_no" value="{{ old('poliza_no')}}" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Autorización No.</label>
                                    </div>
                                    <input type="text" class="form-control" id="autorizacion_no" name="autorizacion_no" value="{{ old('autorizacion_no')}}" disabled>
                                </div>
                            </div>
                            <!-- /poliza -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Modal nueva admision-->
    <!-- Historico modal -->
	<div class="modal fade" id="historicoModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="card">
					<div class="card-header" style="background-color: #E1E8ED;">
						<div class="row">
							<div class="col-md-9">
                                    <h6>Historico de Citas</h6>
                                </div>
                                <div class="col-md-3" style="text-align: right;">
									<button type="button" class="btn btn-xs btn-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
								</div>
						</div>
					</div>
					<div class="card-body">
						<table class="table table-sm table-striped" id="tblHistorico">
							<thead>
								<tr class="text-center">
									<th>Fecha</th><th>Estado</th><th>Admisión</th><th>Tipo</th><th>Médico</th><th>Hospital</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /Historico modal -->
@endsection
@section('js')
	@if(Session::get('type') == 'success')
        @if(Session::has('message'))
            <script>
                
                setTimeout(function() {
                    Swal.fire({
                        title: "Trabajo Finalizado",
                        text: "{{ Session::get('message') }}",
                        icon: 'success', // En v2 es 'icon', no 'type'
                        showConfirmButton: true,
                        confirmButtonText: 'Aceptar'
                    });
                }, 1000);
            </script>
        @endif
    @endif
    @if(Session::get('type') == 'error')
        @if(Session::has('message'))
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: "Error",
                        text: "{!! Session::get('message') !!}",
                        icon: 'error', // En v2 es 'icon', no 'type'
                        showConfirmButton: true,
                        confirmButtonText: 'Aceptar'
                    });
                }, 1000);
            </script>
        @endif
    @endif
    <script type="text/javascript">
    	var asset       = '{{ asset('') }}'
    	var result_sala = 0;
    	var idRegistro                 = null;
    	var idPaciente                 = null;
    	var context_agenda_id          = null;
		var context_hospital_id        = null;
		var context_paciente_id        = null;
		var context_paciente_nombre    = null;
		var context_paciente_telefonos = null;
		var context_hora               = null;
		var context_expediente_no      = null;
		var context_admision_no        = null;
		var context_agenda_estado      = null;
		var context_observaciones      = null;
		var context_medico_id          = null;
		var valorCelda                 = null;

		//========================================================================
      	// inicializar librerias
      	//========================================================================
      	$(function () {
      		$('.select2').select2()
      		$('.select2bs4').select2({
        		theme: 'bootstrap4'
      		})
  		});

  		var userPermissions = @json($permissions);

    	$(document).ready(function() {
			aplicar_filtro();
			var sala_seleccionada_id = $('#sala_seleccionada_id').val();
			fnDefinirSala(sala_seleccionada_id);
			// fnInhabilitaBotones();
			document.getElementById("btnCancelar").classList.add("disabled");
			document.getElementById("btnFinalizar").classList.add("disabled");
			// document.getElementById("opcTrasladar").classList.add("disabled");
			document.getElementById("btnHistorico").classList.add("disabled");
		});

		function zeroPad(num, numZeros) { 
			var n = Math.abs(num); 
			var zeros = Math.max(0, numZeros - Math.floor(n).toString().length ); 
			var zeroString = Math.pow(10,zeros).toString().substr(1); 
			if( num < 0 ) { zeroString = '-' + zeroString; } return zeroString+n; 
		}

		function replacer(val) {
	    	if ( val === null ) 
	     	{ 
	        	return ""; // change null to empty string
	     	} else {
	        	return val; // return unchanged
	     	}
	    }

	    function getMedico(sel){aplicar_filtro();}

		function getFecha(sel){aplicar_filtro();}

	    function getEstado(sel){aplicar_filtro();}

	    //===========================================================================
	    // Inhabilitar todos los botones
	    //===========================================================================
	    function fnInhabilitaBotones(){
	    	$('#btnCita').attr('disabled', 'disabled');
	    	$('#btnBloqueo').attr('disabled', 'disabled');
	    	$('#btnAdmision').attr('disabled', 'disabled');
	    	$('.dropdown-toggle').prop('disabled', true);
	    }

	    //===========================================================================
	    // Validar botones
	    //===========================================================================
	    function fnValidaBotones(){
	    	if (typeof idRegistro !== 'undefined' && idRegistro !== null) {
	    		if (context_agenda_estado == 'P') {
	    			$('#btnCita').removeClass('disabled');
	    			$('#btnBloqueo').removeClass('disabled');
	    			$('#btnAdmision').addClass('disabled');
	    		}

	    		if (context_agenda_estado == 'B') {
	    			$('#btnCita').addClass('disabled');
	    			$('#btnBloqueo').addClass('disabled');
	    			$('#btnAdmision').addClass('disabled');
	    		}

	    		if (context_agenda_estado == 'A') {
	    			$('#btnBloqueo').addClass('disabled');
	    			if (context_admision_no !== '') {
	    				if (context_paciente_id != 'undefined' || context_paciente_id != '') {
	    					$('#btnAdmision').addClass('disabled');
	    				}else{
	    					$('#btnAdmision').removeClass('disabled');
	    				}
	    			}else{
	    				if (context_paciente_id != 'undefined' || context_paciente_id != '' || context_paciente_id != null) {
	    					$('#btnAdmision').removeClass('disabled');
	    				}else{
	    					$('#btnAdmision').addClass('disabled');
	    				}
	    			}
	    		}

	    		if (context_agenda_estado == 'C' || context_agenda_estado == 'R') {
	    			$('#btnCita').removeClass('disabled');
	    			$('#btnBloqueo').addClass('disabled');
	    			$('#btnAdmision').addClass('disabled');
	    			$('.dropdown-toggle').prop('disabled', false);
	    		}
	    	}else{
	    		fnInhabilitaBotones();
	    	}
	    }

		//===========================================================================
		// definir la sala de proceso
		//===========================================================================
	    function fnDefinirSala(id){
		
			document.getElementById('sala_seleccionada_id').value = id;
			const tabla = document.getElementById("tbl" + id);
			const sala_seleccionada = document.getElementById('sala_seleccionada_id').value;
			$("table[id^='tbl'] tbody tr").removeClass("table-active");
			idRegistro = null;
		}

		//===========================================================================
		// traer citas
		//===========================================================================
		function aplicar_filtro()
		{
			let sala_activa = document.getElementById('sala_seleccionada_id').value;
			var fecha  = $('#fecha_filtro').val();
			var medico = $('#f_medico_id').val();
			var estado = $('#estado').val();
			// alert(sala_activa+' '+fecha+' '+medico+' '+estado);
			$("#tbl"+sala_activa+" tbody tr").remove();
			var salas = @json($salas);
		    $.each(salas, function(index, valor) {
	    		salaId = valor['id'];
	    	 	$("#tbl"+salaId+" tbody tr").remove();
		    });
			$('sala1').removeClass('active');
			$.ajax({
				url: "{{ route('trae_citas') }}",
		        type: "POST",
		        dataType: 'json',
		        data: {"_token": "{{ csrf_token() }}",
		               fecha     : fecha, 
		               medico_id : medico, 
		               estado    : estado
		               },
		        success: function(response){
		        	var html = '';
		        	$('#editarRegistro').modal('hide');
                    $('#bloqueoModal').modal('hide');
                    $('#nuevaAdmisionModal').modal('hide');
		        	$("#tbl"+result_sala+" tbody tr").remove();
		        	for(var i = 0; i < response.length; i++){
		        		var fecha = new Date(response[i]['fecha_inicio']);
		        		var hora = zeroPad(fecha.getHours(),2)+':'+zeroPad(fecha.getMinutes(),2);
		        		if (response[i]['sala_id'] != result_sala) {
		        			if (result_sala != 0) {
		        				$("#tbl"+result_sala+" tbody tr").remove();
            					$("#tbl"+result_sala+" tbody").append(html);
            					//console.log(result_sala);
		        			}
		        			html = '';
		        			result_sala = response[i]['sala_id'];
		        		}

		        		switch (response[i]['estado']){
		        		case 'B':
		        			html += '<tr class="text-center" style="font-size: 12px; background-color: #dbcefb;">'
		        			break;
		        		case 'C':
		        			html += '<tr class="text-center" style="font-size: 12px; background-color: #f9c1bf;">'
		        			break;
		        		case 'R':
		        			html += '<tr class="text-center" style="font-size: 12px; background-color: #bff9e4;">'
		        			break;
		        		case 'Z':
		        			html += '<tr class="text-center" style="font-size: 12px; background-color: #d3f8fa;">'
		        			break;
		        		default:
		        			html += '<tr class="text-center" style="font-size: 12px;">';
		        			break;
		        		}
		        		
		        		html += '<td style="display:none;" id="idRegistro">'
						html += response[i]['id']
						html += '</td>'
						html += '<td style="display:none;" id="hospital_id">'+response[i]['hospital_id']+'</td>'
						html += '<td style="display:none;" id="paciente_id">'+response[i]['paciente_id']+'</td>'
						html += '<td style="display:none;" id="paciente_nombre">'+response[i]['nombre_completo']+'</td>'
						html += '<td style="display:none;" id="telefonos">'+response[i]['telefonos']+'</td>'
		        		html += '<td class="text-center" colspan="1">'
		        		html += hora;
		        		html += '</td>'
		        		html += '<td  colspan="3">'
		        		if (response[i]['estado'] == 'B') {
	                	html += '<p class="red-tooltip" data-toggle="tooltip" data-placement="top" title="Bloqueado por '+response[i]['usuario_bloqueo']+' el '+response[i]['fecha_bloqueo']+'">'+replacer(response[i]['observaciones_bloqueo'])+'</p>';
		                }else{
		                	if (userPermissions.includes('administrar-pantalla-medicos') || userPermissions.includes('administrar-pantalla-vitales')){
  								html += '<a href="'+asset+'medicos/nueva_admision/'+response[i]['paciente_id']+'/A" data-toggle="tooltip" data-placement="top" title="'+replacer(response[i]['observaciones'])+'" target="_blank">'+replacer(response[i]['nombre_completo'])+'</a>'
  							}else{
  								html += '<p class="red-tooltip" data-toggle="tooltip" data-placement="top">'+replacer(response[i]['nombre_completo'])+'</p>';
  							}
		                	
		                }
		        		html += '</td>'
		        		html += '<td colspan="1">'
		                html += replacer(response[i]['telefonos'])
		                html += '</td>'
		                html += '<td colspan="1">'
		                if (response[i]['expediente_no'] != null) {
		                	html += response[i]['expediente_no']
		                }
		                html += '</td>'
		                html += '<td colspan="1">'
		                if (response[i]['admision_no'] != null) {
		                	// html += response[i]['admision'];
		                	var editUrl = "{{ route('editar_admision', ':id') }}";
		                	editUrl = editUrl.replace(':id', response[i]['admision_id']);
		                	html += '<a href="' + editUrl + '" title="Editar Admisión" target="_blank">'+response[i]['admision_no']+'</a>'
		                }else{
		                	html += '';
		                }
		                html += '</td>'
		                html += '<td colspan="1">'
		                switch (response[i]['estado']){
		                	case 'A' : html += 'Activa'; break;
		                	case 'R' : html += 'Realizada'; break;
		                	case 'C' : html += 'Cancelada'; break;
		                	case 'B' : html += 'Bloqueado'; break;
		                	case 'Z' : html += 'Trasladado'; break;
	                		case 'P' : html += 'Disponible'; break;
		                	default : 'Disponible'; break;
		                }

		                html += '</td>'
						html += '<td style="display:none;">'
						html += response[i]['estado']
						html += '</td>'
						html += '<td style="display:none;">'
						html += response[i]['observaciones'];
						html += '</td>'
						html += '<td style="display:none;">'
						html += medico;
						html += '</td>'
						html += '<td style="display:none;">'
						html += response[i]['paciente_en_clinica'];
						html += '</td>'
		        		html += '</tr>'
		        	}
		        	// $("#tbl"+result_sala+" tbody tr").remove();
					$("#tbl"+result_sala+" tbody").append(html);
					// document.querySelector('.tab-pane.active').classList.remove('active');
					$("#nav-link-sala"+sala_activa).addClass("active");
		        },
		        error: function(error){
		            console.log(error);
		        }
			});
		}

		function actualiza_nombre_completo(){
			if (document.getElementById('edit_paciente_id').value == '') {
            	document.getElementById('edit_nombre_completo').value = '';
            }else{
            	var paciente = document.getElementById('edit_paciente_id');
            	var paciente_id = paciente.options[paciente.selectedIndex].value;
	           	var paciente_nombre = paciente.options[paciente.selectedIndex].text;
            	document.getElementById('edit_nombre_completo').value = paciente_nombre;
	            if (document.getElementById('edit_telefonos').value == '') {
	            	$.ajax({
	            		url: "{{ route('trae_telefonos_x_paciente') }}",
				        type: "POST",
				        dataType: 'json',
				        data: {"_token": "{{ csrf_token() }}",paciente_id : paciente_id},
				        success: function(response){
				        	// console.log('recibi como respuesta '+response);
				        	document.getElementById('edit_telefonos').value = response;
				        },
				        error: function(error){
				            console.log(error);
				        }
	            	});
	            }
            }
		}

		//===========================================================================
		// Crear Cita
		//===========================================================================
		function fnEditarCita(){
			if (typeof idRegistro !== 'undefined' && idRegistro !== null) {
				fnActualizarPacientes();
				let fecha = $('#fecha_filtro').val();
				let fechaHora = fecha + "T" + context_hora;
				$('#fecha_cita').val(fechaHora);
				if (context_paciente_id != 'null') {
	            	console.log('edicion 1');
	            	document.getElementById('edit_paciente_id').value     = context_paciente_id;
	            }else{
	            	console.log('edicion 2');
	            	document.getElementById('edit_paciente_id').value     = '';
	            }
	            $('#edit_paciente_id').change();
	            if (context_paciente_nombre != 'null') {
	            	document.getElementById('edit_nombre_completo').value = context_paciente_nombre;
	        	}else{
	        		document.getElementById('edit_nombre_completo').value = '';
	        	}
	        	if (context_paciente_telefonos != 'null') {
	            	document.getElementById('edit_telefonos').value       = context_paciente_telefonos;
	            }else{
	            	document.getElementById('edit_telefonos').value       = null;
	            }
	            if (context_hospital_id != 'null') {
	                document.getElementById('edit_hospital_id').value = context_hospital_id;
	            	$('#edit_hospital_id').change();
	            }
	            if (context_medico_id != 'null') {
	            	document.getElementById('edit_medico_id').value   = context_medico_id;
	            	$('#edit_medico_id').change();
	            }
	            if (context_observaciones != 'null') {
	            	document.getElementById('edit_observaciones').value   = context_observaciones;
	            }else{
	            	document.getElementById('edit_observaciones').value   = null;
	            }

	            var inputs = document.querySelectorAll('#editarRegistroForm input, #editarRegistroForm select, #editarRegistroForm textarea');
	            if (context_admision_no != '') {
	            	inputs.forEach(function(input) {
			            input.disabled = true;
			        });
	            }else{
	            	if (context_agenda_estado == 'C' || context_agenda_estado == 'R') {
	            		inputs.forEach(function(input) {
				            input.disabled = true;
				        });
	            	}else{
		            	inputs.forEach(function(input) {
				            input.disabled = false;
				        });
	            	}
	            }
				$('#editarRegistro').modal('show');
			}else{
				alert('Debe seleccionar un horario para continuar');
			}
		}

		//===========================================================================
		// Confirmar llegada de paciente
		//===========================================================================
		function confirmarPresencia(){
			if (typeof idRegistro !== 'undefined' && idRegistro !== null) {
				Swal.fire({
		                title: 'Confirmación',
		                text: 'Confirma el arribo de '+context_paciente_nombre,
		                icon: 'warning',
		                showCancelButton: true,
		                confirmButtonColor: '#28a745', // Color success de AdminLTE
		                cancelButtonColor: '#dc3545',  // Color danger de AdminLTE
		                confirmButtonText: 'Si Confirmado',
		                cancelButtonText: 'No',
		                allowEscapeKey: true,
		                reverseButtons: true // Opcional: pone el botón de confirmar a la derecha
		            }).then((result) => {
		                /* result.isConfirmed será verdadero si el usuario hizo clic en "Si Cerrar" */
		                if (result.isConfirmed) { 
		                    $.ajax({
		                        url: "{{ route('confirmar_ingreso') }}",
		                        type: "POST",
		                        dataType: 'json',
		                        data: {
		                            "_token": "{{ csrf_token() }}", 
		                            cita_id: idRegistro
		                        },
		                        success: function(response){
		                            console.log(response);
		                            Swal.fire({
		                                title: 'Trabajo Finalizado !!!',
		                                text: response.message,
		                                icon: response.type // Asegúrate que tu backend envíe 'success', 'error', etc.
		                            }).then(() => {
		                                location.reload();
		                            });
		                        },
		                        error: function(error){
		                            console.log(error);
		                            Swal.fire('Error', 'No se pudo procesar la solicitud', 'error');
		                        }
		                    });
		                } 
		            });
			}else{
				Swal.fire({
                    title: 'Error',
                    text:  'Debe seleccionar un horario para continuar',
                    icon:  'error',
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                    customClass: {
                        confirmButton: 'btn btn-success'
                    },
                    buttonsStyling: false,
                });
			}
		}

		//=====================================================================
	    // Grabar cita
	    //=====================================================================
	    $(function(){
            $("#editarRegistroForm").submit(function(){
                var cita_id         = context_agenda_id;
                var paciente_id     = document.getElementById('edit_paciente_id').value;
                var nombre_completo = document.getElementById('edit_nombre_completo').value;
                var telefonos       = document.getElementById('edit_telefonos').value;
                var hospital_id     = document.getElementById('edit_hospital_id').value;
                var medico_id       = document.getElementById('edit_medico_id').value;
                var observaciones   = document.getElementById('edit_observaciones').value;
                $.ajax({
                	headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('actualizar_nueva_agenda') }}",
                    method: "POST",
                    data: {cita_id         : cita_id,
                    	   paciente_id     : paciente_id,
                    	   nombre_completo : nombre_completo,
                    	   telefonos       : telefonos,
                    	   hospital_id     : hospital_id,
                    	   medico_id       : medico_id,
                    	   observaciones   : observaciones
                    },
                    success: function(response){
                        Swal.fire({
	                        title: 'Trabajo Finalizado',
	                        text:  'Cita Actualizada con exito !!!',
	                        icon:  'success',
	                        confirmButtonText: "Aceptar",
	                        confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
	                        customClass: {
	                            confirmButton: 'btn btn-success'
	                        },
	                        buttonsStyling: false,
                        }).then((result) => {
			                if (result.isConfirmed) { 
			                    aplicar_filtro();
			                } 
			            });
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
                return false;
            })
        });

        //===========================================================================
		// Abrir Bloqueo de horario
		//===========================================================================
		function fnBloqueo(){
			if (typeof idRegistro !== 'undefined' && idRegistro !== null) {
				$('#bloqueoModal').modal('show');
			}else{
				// alert('Debe seleccionar un horario para continuar');
				Swal.fire({
                    title: 'Error',
                    text:  'Debe seleccionar un horario para continuar',
                    icon:  'error',
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                    customClass: {
                        confirmButton: 'btn btn-success'
                    },
                    buttonsStyling: false,
                });
			}
		}

		//===========================================================================
		// Abrir Nueva Admisión
		//===========================================================================
		function fnCrearAdmision(){
			// alert(context_admision_no);
			if (typeof idRegistro !== 'undefined' && idRegistro !== null) {
				if (context_paciente_en_clinica == 0) {
					Swal.fire({
	                    title: 'Error',
	                    text:  'Paciente aun no aparece disponible',
	                    icon:  'error',
	                    confirmButtonText: "Aceptar",
	                    confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
	                    customClass: {
					        popup: 'mi-clase-personalizada',
					        confirmButton: 'mi-boton-redondo btn-guardar', // Puedes usar tus clases existentes
					        cancelButton: 'btn-danger'
					    },
					    buttonsStyling: false,
	                });
				}else{
					document.getElementById('adm_paciente_id').value = context_paciente_id;
					$('#adm_paciente_id').change();
					document.getElementById('adm_hospital_id').value = context_hospital_id;
					$('#adm_hospital_id').change();
					document.getElementById('adm_medico_id').value = context_medico_id;
					$('#adm_medico_id').change();
					$('#nuevaAdmisionModal').modal('show');
				}
			}else{
				// alert('Debe seleccionar un horario para continuar');
				Swal.fire({
                    title: 'Error',
                    text:  'Debe seleccionar un horario para continuar',
                    icon:  'error',
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                    customClass: {
                        confirmButton: 'btn btn-success'
                    },
                    buttonsStyling: false,
                });
			}
		}

		//=====================================================================
	    // Grabar Bloqueo de horario
	    //=====================================================================
		$(function(){
			$("#bloqueoForm").submit(function(){
				var cita_id         = context_agenda_id;
                var observaciones   = document.getElementById('bloqueo_espacio_observaciones').value;
				$.ajax({
                	headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('bloquear_espacio') }}",
                    method: "POST",
                    data: {cita_id         : cita_id,
                    	   observaciones   : observaciones
                    },
                    success: function(response){
                    	Swal.fire({
	                        title: 'Trabajo Finalizado',
	                        text:  response,
	                        icon:  'success',
	                        confirmButtonText: "Aceptar",
	                        confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
	                        customClass: {
	                            confirmButton: 'btn btn-success'
	                        },
	                        buttonsStyling: false,
                        }).then((result) => {
			                if (result.isConfirmed) { 
			                    aplicar_filtro();
			                } 
			            });
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
			});
		});

		//=====================================================================
	    // Submit nueva admision
	    //=====================================================================
		$(function(){
            $("#admisionForm").submit(function(){
                grabarAdmision();
                return false;
            })
        });

        function grabarAdmision(){
            var agenda_id          = context_agenda_id;
            var tipo_admision      = $('input:radio[name=tipo_admision]:checked').val();
            var adm_fecha          = document.getElementById('adm_fecha').value;
            var adm_paciente_id    = document.getElementById('adm_paciente_id').value;
            var adm_medico_id      = document.getElementById('adm_medico_id').value;
            var adm_hospital_id    = document.getElementById('adm_hospital_id').value;
            var admision_tercero   = document.getElementById('admision_tercero').value;
            var adm_aseguradora_id = document.getElementById('adm_aseguradora_id').value;
            var poliza_no          = document.getElementById('poliza_no').value;
            var autorizacion_no    = document.getElementById('autorizacion_no').value;
            
            if (adm_paciente_id == undefined) {
            	alert('1');
            }
            if (adm_paciente_id == null) {
            	alert('2');
            }
            if (adm_paciente_id == '') {
            	Swal.fire({
                    title: 'Error',
                    text:  'Debe seleccionar un horario para continuar',
                    icon:  'error',
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    },
                    buttonsStyling: false,
                });
            }
            $.ajax({
                url: "{{ route('grabar_admision') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}", 
                       agenda_id          : agenda_id,
                       tipo_admision      : tipo_admision,
                       fecha              : adm_fecha, 
                       paciente_id        : adm_paciente_id,
                       medico_id          : adm_medico_id,
                       hospital_id        : adm_hospital_id,
                       admision_tercero   : admision_tercero,
                       aseguradora_id     : adm_aseguradora_id,
                       poliza_no          : poliza_no,
                       autorizacion_no    : autorizacion_no
                       },
                success: function(response){
                    // console.log(response);
                    Swal.fire({
                        title: 'Trabajo Finalizado',
                        text: response['respuesta'],
                        icon: 'success',
                        }).then((result) => {
                            aplicar_filtro();
                        });
                    //alertify.success('Compra eliminada con exito');
                },
                error: function(error){
                    console.log(error);
                }
            });
        }
		//===========================================================================
		// obtener el valor del registro seleccionado
		//===========================================================================
		document.addEventListener("DOMContentLoaded", function() {
		    const sala_seleccionada_id = document.getElementById('sala_seleccionada_id').value;

		    var salas = @json($salas);
		    $.each(salas, function(index, valor) {
                // $('#listaSalas').append('<li>' + valor + '</li>');
                var tabla = document.getElementById("tbl"+valor['id']);
			    // console.log(tabla);
			    if (tabla) {
			        tabla.addEventListener('click', function() {
			            const fila = event.target.closest('tr');
						if (fila) {
							// Obtener todos los <td> en la fila
			    			const celdas = fila.getElementsByTagName('td');
			    			const valores = Array.from(celdas).map(td => td.textContent);
			    			
			    			idRegistro = valores[0];
			    			idPaciente = valores[2];

			        		context_agenda_id           = fila.cells[0].textContent;
			        		context_hospital_id         = fila.cells[1].textContent;
			        		context_paciente_id         = fila.cells[2].textContent;
			        		context_paciente_nombre     = fila.cells[3].textContent;
			        		context_paciente_telefonos  = fila.cells[4].textContent;
			        		context_hora                = fila.cells[5].textContent;
			        		context_expediente_no       = fila.cells[8].textContent;
			        		context_admision_no         = fila.cells[9].textContent;
			        		context_agenda_estado       = fila.cells[11].textContent;
			        		context_observaciones       = fila.cells[12].textContent;
			        		context_medico_id           = fila.cells[13].textContent;
			        		context_paciente_en_clinica = fila.cells[14].textContent;

			        		if (context_admision_no == '') {
			        			if (context_agenda_estado == 'P' || context_agenda_estado == 'C' || context_agenda_estado == 'B') {
			        				// document.getElementById("opcCancelar").classList.add("disabled");
			        			}else{
			        				// document.getElementById("opcCancelar").classList.remove("disabled");
			        			}
			        			
			        			// document.getElementById("opcFinalizar").classList.add("disabled");
			        			// document.getElementById("opcTrasladar").classList.remove("disabled");
			        		}else{
			        			// document.getElementById("opcCancelar").classList.add("disabled");
			        			if (context_agenda_estado != 'R') {
			        				// document.getElementById("opcFinalizar").classList.remove("disabled");
			        			}else{
			        				// document.getElementById("opcFinalizar").classList.add("disabled");
			        			}
			        			// document.getElementById("opcTrasladar").classList.add("disabled");
			        		}

			        		if (context_paciente_id != 'null') {
			        			// document.getElementById("opcHistorico").classList.remove("disabled");
			        		}else{			        			
			        			// document.getElementById("opcHistorico").classList.add("disabled");
			        		}
			    			fnValidaBotones();
						}
			        });
			    } else {
			        console.error("No se encontró la tabla con ID: tbl" + sala_seleccionada_id);
			    }
            });

            // document.querySelectorAll("table[id^='tbl']").forEach(table => {
		    //     table.addEventListener("click", function (e) {
		    //         const row = e.target.closest("tr");
		    //         if (!row || row.parentNode.tagName !== "TBODY") return;

		    //         // quitar selección previa en la misma tabla
		    //         table.querySelectorAll("tbody tr").forEach(r => r.classList.remove("table-active"));

		    //         // marcar la fila seleccionada
		    //         row.classList.add("table-active");
		    //     });
		    // });
		});

		$(document).on("click", "table[id^='tbl'] tbody tr", function() {
		    // quitar selección previa SOLO en la tabla correspondiente
		    // $(this).closest("tbody").find("tr").removeClass("table-active");
		    $("table[id^='tbl'] tbody tr").removeClass("table-active");
		    // marcar fila seleccionada
		    $(this).addClass("table-active");
		});

		function fn_habilitar_poliza(valor){
            if (valor.length != 0) {
                document.getElementById('poliza_no').disabled = false;
                document.getElementById('poliza_no').setAttribute('required', true);
                document.getElementById('autorizacion_no').disabled = false;
                document.getElementById('autorizacion_no').setAttribute('required', true);
            }else{
                document.getElementById('poliza_no').value = '';
                document.getElementById('poliza_no').setAttribute('required', false);
                document.getElementById('poliza_no').disabled = true;
                document.getElementById('autorizacion_no').value = '';
                document.getElementById('autorizacion_no').setAttribute('required', false);
                document.getElementById('autorizacion_no').disabled = true;
            }
        }

        function fnCancelar(){
        	Swal.fire({
                title: 'Confirmación',
                text: "Seguro de Cancelar la Cita ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745', // Color success de AdminLTE
                cancelButtonColor: '#dc3545',  // Color danger de AdminLTE
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                allowEscapeKey: true,
                reverseButtons: true // Opcional: pone el botón de confirmar a la derecha
            }).then((result) => {
                if (result.isConfirmed) { 
                    $.ajax({
                        url: "{{ route('cancelar_cita') }}",
                        type: "POST",
                        async: true,
                        data: {"_token": "{{ csrf_token() }}", 
                               cita_id: idRegistro, 
                               observaciones: 'Prueba cancelacion de cita'
                              },
                        success: function(response){
                            var info = response;
                            Swal.fire({
                                title: 'Trabajo Finalizado',
                                text: 'Cita Cancelada con Exito !!!',
                                icon: 'success',
                            }).then((result) =>{
                                aplicar_filtro();
                        	});
                        },
                        error: function(error){
                            console.log(error);
                        }
                    });
                } 
            });
        }

        function fnFinalizar(){
        	Swal.fire({
                title: 'Confirmación',
                text: "¿Confirmas que deseas finalizar la cita?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745', // Color success de AdminLTE
                cancelButtonColor: '#dc3545',  // Color danger de AdminLTE
                confirmButtonText: 'Si Finalizar',
                cancelButtonText: 'No',
                allowEscapeKey: true,
                reverseButtons: true // Opcional: pone el botón de confirmar a la derecha
            }).then((result) => {
                if (result.isConfirmed) { 
                    $.ajax({
                        url: "{{ route('realizar_cita') }}",
                        type: "POST",
                        async: true,
                        data: {"_token": "{{ csrf_token() }}", 
                               cita_id: idRegistro, 
                               observaciones: 'Prueba cancelacion de cita'
                              },
                        success: function(response){
                            var info = response;
                            Swal.fire({
		                        title: "Trabajo Finalizado",
		                        text: '! Cita Finalizada con Exito !',
		                        icon: 'success', // En v2 es 'icon', no 'type'
		                        showConfirmButton: true,
		                        confirmButtonText: 'Aceptar'
		                    }).then((result) => {
		                        aplicar_filtro();
		                    });
                        },
                        error: function(error){
                            console.log(error);
                        }
                    });
                } 
            });
        }

        function fnHistorico(){
        	$.ajax({
                url: "{{ route('paciente_citas') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}", 
                       paciente_id: context_paciente_id
                      },
                success: function(response){
                    console.log(response);
                    let html = '';
                    for(var i = 0; i < response.length; i++){
                    	html += '<tr class="text-center" style="font-size: 12px;">';
                    	html += '<td>'+response[i]['fecha_inicio']+'</td>'
                    	html += '<td>'
                    	switch (response[i]['estado']){
		                	case 'A' : html += 'Activa'; break;
		                	case 'R' : html += 'Realizada'; break;
		                	case 'C' : html += 'Cancelada'; break;
		                	case 'B' : html += 'Bloqueado'; break;
		                	case 'Z' : html += 'Trasladado'; break;
	                		case 'P' : html += 'Disponible'; break;
		                	default : 'Disponible'; break;
		                }
                    	html += '</td>'
                    	html += '<td>';
                    	if (response[i]['admision_no'] != null) {
                    		html += response[i]['admision_no']
                    	}
                    	html += '</td>'
                    	html += '<td>'
                    	if (response[i]['admision_no'] != null) {
                    		html += response[i]['tipo_atencion'];
                    	}
                    	html += '</td>'
                    	html += '<td>'+response[i]['nombre_completo']+'</td>'
                    	html += '<td>'+response[i]['nombre']+'</td>'
                    	html += '</tr>';	
                    }
                    $("#tblHistorico tbody tr").remove();
                    $("#tblHistorico tbody").append(html);
                    $('#historicoModal').modal('show');
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function fnActualizarPacientes(){
        	let html = '';

        	$.ajax({
                url: "{{ route('lista_pacientes') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}"
                      },
                success: function(response){
                	console.log(response);
                	html += '<option value="">Paciente sin ficha...</option>'
                	$("#edit_paciente_id").empty().append(html);
                	for (var i = 0; i < response.length; i++) {
                        html += '<option value="'+response[i]['id']+'">'+response[i]['nombre_completo']+'</option>'
                    }
                    $("#edit_paciente_id").append(html);
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        // window.addEventListener('focus', function() {
	  	// 	fnActualizarPacientes();
		// });
    </script>
@endsection