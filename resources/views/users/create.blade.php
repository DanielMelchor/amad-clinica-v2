@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/multi-select/css/multi-select.css') }}">
    <style type="text/css">
    	/* Ajusta el ancho de los dos cuadros */
		.ms-container {
		    width: 800px; /* ancho total del contenedor */
		}

		/*.ms-container .ms-selectable,
		.ms-container .ms-selection {
		    width: 500px;   /* ancho individual de cada cuadro */
		    min-height: 300px; /* alto opcional */
		}*/

		.ms-container .ms-selectable,
		.ms-container .ms-selection {
		    width: 48%;
		}


    </style>
@endsection
@section('title', 'Usuarios')
@section('content_header')
  <h3>Nuevo Usuario</h3>
@endsection
@section('content')
	<div class="row">
		<div class="col-md-10 offset-md-1">
			<form class="form" method="POST" action="{{ route('usuario_grabar') }}">
				@csrf
				<div class="card card-navy">
					<div class="card-header">
						<div class="row">
							<div class="col-md-1 offset-md-10" style="text-align: right;">
								<button type="submit" class="btn btn-sm btn-block btn-success" title="Guardar los cambios"><i class="fas fa-save">&nbsp;&nbsp;Guardar</i></button>
							</div>
							<div class="col-md-1" style="text-align: right;">
								<a href="#" class="btn btn-sm btn-block btn-danger" title="Regresar a lista de Pacientes" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt">&nbsp;&nbsp;Salir</i></a>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="input-group col-md-6 offset-md-1 mb-3">
						  		<div class="input-group-prepend">
							    	<label class="input-group-text" id="basic-addon1">Colaborador</label>
							  	</div>
							  	<input type="text" class="form-control" placeholder="Nombre de colaborador" aria-label="Username" aria-describedby="basic-addon1" id="name" name="name" value="{{ old('name') }}" required autofocus>
							</div>
						</div>
						<div class="row">
							<div class="input-group mb-3 col-md-6 offset-md-1">
						  		<div class="input-group-prepend">
							    	<label class="input-group-text">Usuario&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
							  	</div>
							  	<input type="text" class="form-control" placeholder="usuario" aria-label="Username" aria-describedby="basic-addon1" id="username" name="username" autofocus required value="{{ old('username')}}">
							</div>
						</div>
						<div class="row">
							<div class="input-group col-md-6 offset-md-1 mb-3">
						  		<div class="input-group-prepend">
							    	<label class="input-group-text" for="empresa_id">Empresa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
							  	</div>
							  	<select class="custom-select select2 select2bs4" id="empresa_id" name="empresa_id">
							    	<option selected>Seleccionar...</option>
							    	@foreach($empresas as $e)
							    		<option value="{{ $e->id }}">{{ $e->nombre_comercial }}</option>
							    	@endforeach
							  	</select>
							</div>
						</div>
						<div class="row">
							<div class="input-group col-md-6 offset-md-1 mb-3">
						  		<div class="input-group-prepend">
							    	<label class="input-group-text" for="caja_id">Caja&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
							  	</div>
							  	<select class="custom-select select2 select2bs4" id="caja_id" name="caja_id" aria-label="caja_id" aria-describedby="basic-addon1">
							    	<option value="">Seleccionar...</option>
							    	@foreach($cajas as $c)
							    		<option value="{{ $c->id }}">{{ $c->nombre_maquina }}</option>
							    	@endforeach
							  	</select>
							</div>
						</div>
						<br>
						<div class="row">
		        			<div class="col-md-5 offset-md-1">
		        				<div class="card card-info">
		        					<div class="card-header text-center">
		        						<h6>Salas</h6>
		        					</div>
		        				</div>
		        				<div class="card-body">
		        					<div class="row">
		        						<div class="col-md-12">
		        							<select id='callbacks' name="callbacks[]" multiple='multiple'>
												@foreach($salas as $s)
													<option value='{{ $s->id}}'>{{ $s->sala_nombre }}</option>
												@endforeach
											</select>	
		        						</div>
		        					</div>
		        				</div>
		        			</div>
		        			<div class="col-md-5">
		        				<div class="card card-info">
		        					<div class="card-header text-center">
		        						<h6>Roles</h6>
		        					</div>
		        				</div>
		        				<div class="card-body">
		        					<div class="row">
		        						<div class="col-md-10 offset-md-2">
		        							<select id='callbacksr' name="callbacksr[]" multiple='multiple'>
												@foreach($roles as $r)
													<option value='{{ $r->id}}'>{{ $r->name }}</option>
												@endforeach
											</select>	
		        						</div>
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
	<script src="{{ asset('assets/multi-select/js/jquery.multi-select.js') }}"></script>
	<script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
	<script type="text/javascript">
		/*=========================================================================================
        Inicialización de librerias
        =========================================================================================*/
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()
            $('.select2bs4').select2({ theme: 'bootstrap4' })
        });

        $('#callbacks').multiSelect({
			selectableHeader: "<div class='custom-header text-center'><strong>Salas</strong></div>",
			selectionHeader: "<div class='custom-header text-center'><strong>Salas permitidas</strong></div>",
	      afterSelect: function(values){
	        //alert("Select value: "+values);
	      },
	      afterDeselect: function(values){
	        //alert("Deselect value: "+values);
	      }
	    });
	    var x = [];
	    @foreach ($salas_x_usuario as $su)
	    	x.push("{{ $su['sala_id'] }}");
	    @endforeach
	    $('#callbacks').multiSelect('select', x);


	    $('#callbacksr').multiSelect({
			selectableHeader: "<div class='custom-header text-center'><strong>Roles</strong></div>",
			selectionHeader: "<div class='custom-header text-center'><strong>Roles permitidos</strong></div>",
	      afterSelect: function(values){
	        //alert("Select value: "+values);
	      },
	      afterDeselect: function(values){
	        //alert("Deselect value: "+values);
	      }
	    });
	    var x = [];
	    @foreach ($roles_x_usuario as $ru)
	    	x.push("{{ $ru['id'] }}");
	    @endforeach
	    $('#callbacksr').multiSelect('select', x);

	    function confirma_salida(){
            swal({
                title: 'Confirmación',
                Swal.fire({

                title: 'Confirmación',

                text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?",

text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?",
                icon: 'warning',

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

                        if (origen == 'P') {

                            window.location.href = "{{ route('pacientes') }}";

                        }

                        if (origen == 'A') {

                            window.location.href = "{{ route('nueva_agenda') }}";

                        }

                        // history.back();

                        

                    } 

                }

            );
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
                        window.location.href = "{{ route('usuario_listado') }}";
                                    } 
                    else { 
                        swal("Cancelled", "Your imaginary file is safe :)", "error"); 
                        }
            });
        }
	</script>
@endsection