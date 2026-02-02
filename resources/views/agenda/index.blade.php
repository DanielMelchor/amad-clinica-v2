@extends('adminlte::page')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@endsection
@section('title', 'Agenda')

@section('content_header')
    <h3>Agenda</h3>
@endsection

@section('content')
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">
						
					</div>
					<div class="panel-body">
						<table class="table table-bordered table-hover table-response">
							<thead class="thead-dark">
							</thead>
							<tbody>
								<div class="col"></div>
								<div class="col-7" style="font-size: 15px;"> 
									<div id="CalendarioWeb"></div>
								</div>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

		<!-- Modal -->
	
@endsection
@section('scripts')
    <script>
		$(document).ready(function()
		{
			$('#CalendarioWeb').fullCalendar(
			{
				locale:'es',
				height: 700,
				header:{
					left:'today,prev,next,Miboton',
					center:'title',
					right:'agendaDay, month', //basicWeek, basicDay, , month, agendaWeek	
				},
				minTime:'07:00',
				maxTime:'19:00',
				slotDuration:'00:30:00',
				allDaySlot: false,
				/*customButtons:{
					Miboton:{
						text: "Boton 1",
						click:function(){
							alert(event.id);
						}
					}
				},*/
				dayClick:function(date, jsEvent, view){
					//alert("valor seleccionado "+date.format('DD, MM, YYYY'));
					//alert("vista actual: "+view.name);
					//$(this).css('background-color', 'red');
					$('#AEFecha').val(date.format("YYYY-MM-DD"));
					$('#AEtxtFecha').val(date.format("DD/MM/YYYY"));
					$('#AEtxtStart').val(date.format('HH:mm:ss'));
					$('#AEtxtEnd').val(date.format('HH:mm:ss')+30);
					$('#agregarEvento').modal();
				},

				eventSources:[{
					events: {!! $resultado !!},
				}],
				
				eventClick:function(calEvent, jsEvent, view){
					$(this).css('background', 'red');
					$('#EVid').val(calEvent.id);
					$('#EVtituloEvento').html(calEvent.title);
					//$('#descripcionEvento').html(calEvent.descripcion);
					$('#EVtxtTitle').val(calEvent.title);
					$('#EVtxtDescripcion').val(calEvent.descripcion);
					//var start = $.fullCalendar.formatDate(calEvent._i, 'DD.MM.YYYY');
					//alert(calEvent.start.format('hh:mm:ss'));
					FechaHora = calEvent.start._i.split();
					//$('#EVFecha').val(calEvent.start.format('YYYY-MM-DD'));
					$('#EVtxtFecha').val(calEvent.start.format("DD/MM/YYYY"));
					$('#EVFecha').val(calEvent.start.format("DD/MM/YYYY"));

					//$('#EVtxtFecha').val(calEvent.start);
					$('#EVtxtStart').val(calEvent.start.format('hh:mm:ss'));
					$('#EVtxtEnd').val(calEvent.end.format('hh:mm:ss'));

					$('#editarEvento').modal();
					//alert('clic evento');
				}
			});
			$('#CalendarioWeb').fullCalendar('changeView', 'agendaDay');
			$('#CalendarioWeb').css('font-size','20px !important');
		});
	</script>
	<!-- modal evento -->
	<div class="modal fade" id="agregarEvento" role="dialog">
  		<div class="modal-dialog modal-dialog-centered" role="document">
    		<div class="modal-content">
    			<form role="form" action="{{route('grabar_agenda')}}" method="post">
      				@CSRF
	      			<div class="modal-body">	        		
	      				<div class="panel panel-primary">
                            <!-- panel heading -->
                            <div class="panel-heading">
                                <div class="row text-center">
                                    <div class="col-md-12">
                                        <h3>Nueva Cita</h3>
                                    </div>
                                </div>
                            </div>
                            <!-- /panel heading -->
                            <!-- panel body -->
                            <div class="panel-body">
                            	<div class="row">
                            		<div class="form-group">
			            				<input type="hidden" class="form-control" id="AEFecha" name="AEFecha">
			      					</div>
                            	</div>
                            	<div class="row text-center">
		          					<div class="form-group col-md-3 col-md-offset-1">
			            				<label for="AEtxtFecha" class="col-form-label">Fecha:</label>
			            				<input type="text" class="form-control" id="AEtxtFecha" name="AEtxtFecha" readonly="true">
		          					</div>
		          					<div class="form-group col-md-3">
			            				<label for="AEtxtStart" class="col-form-label">Hora Inicio</label>
			            				<select class="custom-select" id="AEtxtStart" name="AEtxtStart" style="width: 100%; height: 35px; font-size: 18px;">
										    <option value="07:00:00">07:00</option>
										    <option value="07:30:00">07:30</option>
										    <option value="08:00:00">08:00</option>
										    <option value="08:30:00">08:30</option>
										    <option value="09:00:00">09:00</option>
										    <option value="09:30:00">09:30</option>
										    <option value="10:00:00">10:00</option>
										    <option value="10:30:00">10:30</option>
										    <option value="11:00:00">11:00</option>
										    <option value="11:30:00">11:30</option>
										    <option value="12:00:00">12:00</option>
										    <option value="12:30:00">12:30</option>
										    <option value="13:00:00">13:00</option>
										    <option value="13:30:00">13:30</option>
										    <option value="14:00:00">14:00</option>
										    <option value="14:30:00">14:30</option>
										    <option value="15:00:00">15:00</option>
										    <option value="15:30:00">15:30</option>
										    <option value="16:00:00">16:00</option>
										    <option value="16:30:00">16:30</option>
										    <option value="17:00:00">17:00</option>
										    <option value="17:30:00">17:30</option>
										    <option value="18:00:00">18:00</option>
										    <option value="18:30:00">18:30</option>
										  </select>
		          					</div>
		          					<div class="form-group col-md-3">
			            				<label for="AEtxtEnd" class="col-form-label">Hora Fin</label>
			            				<select class="custom-select" id="AEtxtEnd" name="AEtxtEnd" style="width: 100px; height: 35px; font-size: 18px;">
										    <option value="07:00:00">07:00</option>
										    <option value="07:30:00">07:30</option>
										    <option value="08:00:00">08:00</option>
										    <option value="08:30:00">08:30</option>
										    <option value="09:00:00">09:00</option>
										    <option value="09:30:00">09:30</option>
										    <option value="10:00:00">10:00</option>
										    <option value="10:30:00">10:30</option>
										    <option value="11:00:00">11:00</option>
										    <option value="11:30:00">11:30</option>
										    <option value="12:00:00">12:00</option>
										    <option value="12:30:00">12:30</option>
										    <option value="13:00:00">13:00</option>
										    <option value="13:30:00">13:30</option>
										    <option value="14:00:00">14:00</option>
										    <option value="14:30:00">14:30</option>
										    <option value="15:00:00">15:00</option>
										    <option value="15:30:00">15:30</option>
										    <option value="16:00:00">16:00</option>
										    <option value="16:30:00">16:30</option>
										    <option value="17:00:00">17:00</option>
										    <option value="17:30:00">17:30</option>
										    <option value="18:00:00">18:00</option>
										    <option value="18:30:00">18:30</option>
										  </select>
		          					</div>
		          				</div>
		          				<div class="row">
		          					<div class="col-md-10 col-md-offset-1">
		          						<div class="form-group">
							            	<label for="AEtxtTitle" class="col-form-label">Paciente:</label>
							            	<input type="text" class="form-control" id="AEtxtTitle" name="AEtxtTitle" style="font-size: 18px;">
							          	</div>
		          					</div>
		          				</div>
		          				<div class="row">
		          					<div class="col-md-10 col-md-offset-1">
		          						<div class="form-group">
							            	<label for="AEtxtDescripcion" class="col-form-label">Descripción:</label>
							            	<textarea class="form-control" id="AEtxtDescripcion" name="AEtxtDescripcion" style="font-size: 18px;"></textarea>
							          	</div>
		          					</div>
		          				</div>
                            </div>
                        	<div class="panel-footer">
                        		<div class="row">
                        			<div class="col-md-3 col-md-offset-9" style="text-align: right;">
                        				@can('grabar-cita')
                        				<button type="submit" id="btnAgregarEvento" class="btn btn-primary btn-lg fa fa-save" title="Grabar"></button>
                        				@endcan
	        							<a href="{{ route('agenda') }}" class="btn btn-warning btn-lg fa fa-sign-out" title="Regresar a Agenda"></a>
                        			</div>
                        		</div>
                        	</div>
                        </div>
                    </div>
	      		</form>
	    	</div>
	  	</div>
	</div>
	<!-- /modal evento -->
	<!-- modal Editar evento -->
	<div class="modal fade" id="editarEvento" role="dialog">
  		<div class="modal-dialog modal-dialog-centered" role="document">
    		<div class="modal-content">
    			<form role="form" action="{{route('actualizar_agenda')}}" method="post">
      				@CSRF
      				<div class="modal-body">	        		
	      				<div class="panel panel-primary">
                            <!-- panel heading -->
                            <div class="panel-heading">
                                <div class="row text-center">
                                    <div class="col-md-12">
                                        <h3>Edición de Cita</h3>
                                    </div>
                                </div>
                            </div>
                            <!-- /panel heading -->
                            <!-- panel body -->
                            <div class="panel-body">
                            	<div class="form-group">
		            				<input type="hidden" class="form-control" id="EVFecha" name="EVFecha">
		            				<input type="hidden" class="form-control" id="EVid" name="EVid">
		      					</div>
		      					<div class="row text-center">
		      						<div class="col-md-3 col-md-offset-1">
		          						<div class="form-group">
				            				<label for="EVtxtFecha" class="col-form-label">Fecha:</label>
				            				<input type="text" class="form-control" id="EVtxtFecha" name="EVtxtFecha" readonly="true">
				      					</div>
		          					</div>
		          					<div class="form-group col-md-3">
			            				<label for="EVtxtStart" class="col-form-label">Hora Inicio</label>
			            				<select class="custom-select"  id="EVtxtStart" name="EVtxtStart" style="width: 100px; height: 35px; font-size: 18px;">
										    <option value="07:00:00">07:00</option>
										    <option value="07:30:00">07:30</option>
										    <option value="08:00:00">08:00</option>
										    <option value="08:30:00">08:30</option>
										    <option value="09:00:00">09:00</option>
										    <option value="09:30:00">09:30</option>
										    <option value="10:00:00">10:00</option>
										    <option value="10:30:00">10:30</option>
										    <option value="11:00:00">11:00</option>
										    <option value="11:30:00">11:30</option>
										    <option value="12:00:00">12:00</option>
										    <option value="12:30:00">12:30</option>
										    <option value="13:00:00">13:00</option>
										    <option value="13:30:00">13:30</option>
										    <option value="14:00:00">14:00</option>
										    <option value="14:30:00">14:30</option>
										    <option value="15:00:00">15:00</option>
										    <option value="15:30:00">15:30</option>
										    <option value="16:00:00">16:00</option>
										    <option value="16:30:00">16:30</option>
										    <option value="17:00:00">17:00</option>
										    <option value="17:30:00">17:30</option>
										    <option value="18:00:00">18:00</option>
										    <option value="18:30:00">18:30</option>
									  	</select>
		          					</div>
		          					<div class="form-group col-md-3">
			            				<label for="EVtxtEnd" class="col-form-label">Hora Fin</label>
			            				<select class="custom-select" id="EVtxtEnd" name="EVtxtEnd" style="width: 100px; height: 35px; font-size: 18px;">
										    <option value="07:00:00">07:00</option>
										    <option value="07:30:00">07:30</option>
										    <option value="08:00:00">08:00</option>
										    <option value="08:30:00">08:30</option>
										    <option value="09:00:00">09:00</option>
										    <option value="09:30:00">09:30</option>
										    <option value="10:00:00">10:00</option>
										    <option value="10:30:00">10:30</option>
										    <option value="11:00:00">11:00</option>
										    <option value="11:30:00">11:30</option>
										    <option value="12:00:00">12:00</option>
										    <option value="12:30:00">12:30</option>
										    <option value="13:00:00">13:00</option>
										    <option value="13:30:00">13:30</option>
										    <option value="14:00:00">14:00</option>
										    <option value="14:30:00">14:30</option>
										    <option value="15:00:00">15:00</option>
										    <option value="15:30:00">15:30</option>
										    <option value="16:00:00">16:00</option>
										    <option value="16:30:00">16:30</option>
										    <option value="17:00:00">17:00</option>
										    <option value="17:30:00">17:30</option>
										    <option value="18:00:00">18:00</option>
										    <option value="18:30:00">18:30</option>
										</select>
		          					</div>
		      					</div>
		      					<div class="row">
		          					<div class="col-md-10 col-md-offset-1">
		          						<div class="form-group">
							            	<label for="EVtxtTitle" class="col-form-label">Paciente:</label>
							            	<input type="text" class="form-control" id="EVtxtTitle" name="EVtxtTitle" style="font-size: 18px;">
							          	</div>
		          					</div>
		          				</div>
		          				<div class="row">
		          					<div class="col-md-10 col-md-offset-1">
		          						<div class="form-group">
							            	<label for="EVtxtDescripcion" class="col-form-label">Descripción:</label>
							            	<textarea class="form-control" id="EVtxtDescripcion" name="EVtxtDescripcion" style="font-size: 18px;"></textarea>
							          	</div>
		          					</div>
		          				</div>
                            </div>
                            <div class="panel-footer">
                            	<div class="row">
                            		<div class="col-md-4 col-md-offset-8" style="text-align: right;">
			        					@can('actualizar-cita')
			        					<button type="submit" id="btnActualizarEvento" class="btn btn-primary btn-lg fa fa-save" title="Grabar cambios"></button>
			        					@endcan
			        					@can('anular-cita')
			        					<a href="{{ route('anular_agenda') }}" class="btn btn-danger btn-lg fa fa-ban" title="Anular Cita"></a>
			        					@endcan
			        					<a href="{{ route('agenda') }}" class="btn btn-warning btn-lg fa fa-sign-out" title="Regresar a Agenda"></a>
			        				</div>
                            	</div>
                            </div>
                        </div>
                    </div>
	      		</form>
	    	</div>
	  	</div>
	</div>
	<!-- /modal Editar evento -->
@endsection
@show