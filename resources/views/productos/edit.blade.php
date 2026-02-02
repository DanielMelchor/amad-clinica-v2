@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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
        .select2 {
        	font-size: 12px;
        }
    </style>
@endsection
@section('content_header')
  <br>
@endsection
@section('content')
	<div class="row">
        <div class="col-md-10 offset-md-1">
            <form role="form" method="POST" action="{{route('actualizar_producto')}}">
                @csrf
                <div class="card">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="row">
                            <div class="col-md-9">
                                <h6>Edición de Producto</h6>
                            </div>
                            <div class="col-md-3" style="text-align: right;">
                                <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
                                <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    	<input type="hidden" id="producto_id" name="producto_id" value="{{ $producto->id }}">
                    	<div class="row">
                            <div class="col-md-5 offset-md-1 mb-1">
                                <div class="input-group input-group-sm">
	                                <div class="input-group-prepend">
	                                    <label class="input-group-text" for="inv_clasificacion_id">Clasificación</label>
	                                </div>
	                                <select class="custom-select custom-select-sm select2 select2bs4" id="inv_clasificacion_id"  name="inv_clasificacion_id" required>
	                                    <option value="">Seleccionar...</option>
	                                    @foreach($clasificaciones as $clasificacion)
	                                        <option value="{{ $clasificacion->id}}" @if($clasificacion->id == $producto->clasificacion) selected @endif>{{ $clasificacion->nombre}}</option>
	                                    @endforeach
	                                </select>
	                            </div>
                            </div>
                            <div class="col-md-5 mb-1">
                                <div class="input-group input-group-sm">
	                                <div class="input-group-prepend">
	                                    <label class="input-group-text" for="inv_familia_id">Familia</label>
	                                </div>
	                                <select class="custom-select custom-select-sm select2 select2bs4" id="inv_familia_id"  name="inv_familia_id" required>
	                                    <option value="">Seleccionar...</option>
	                                    @foreach($familias as $familia)
	                                        <option value="{{ $familia->id}}" @if($familia->id == $producto->inv_familia_id) selected @endif>{{ $familia->nombre}}</option>
	                                    @endforeach
	                                </select>
	                            </div>
                            </div>
                        </div>
                        <div class="row">
							<div id="grpsiglas" class="input-group input-group-sm mb-1 col-md-5 offset-md-1">
						  		<div class="input-group-prepend">
							    	<label class="input-group-text">Siglas</label>
							  	</div>
							  	<input type="text" class="form-control" placeholder="solo para procedimientos" aria-label="Username" aria-describedby="basic-addon1" id="siglas" name="siglas" value="{{ $producto->siglas }}">
							</div>
						</div>
						<div class="row">
							<div id="grpdescripcion" class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
						  		<div class="input-group-prepend">
							    	<label class="input-group-text">Descripción</label>
							  	</div>
							  	<input type="text" class="form-control" placeholder="Descripcion interna" aria-label="Username" aria-describedby="basic-addon1" id="descripcion" name="descripcion" required value="{{ $producto->descripcion }}">
							</div>
						</div>
						<div class="row">
							<div id="grpdescripcionm" class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
						  		<div class="input-group-prepend">
							    	<label class="input-group-text">Texto a mostrar</label>
							  	</div>
							  	<input type="text" class="form-control" placeholder="texto a mostrar en documentos del cliente" aria-label="Username" aria-describedby="basic-addon1" id="descripcion_a_mostrar" name="descripcion_a_mostrar" required value="{{ $producto->descripcion_a_mostrar }}">
							</div>
						</div>
						<div class="row">
	                        <div id="grpmedida" class="mb-1 col-md-5 offset-md-1">
	                            <div class="input-group input-group-sm">
	                                <div class="input-group-prepend">
	                                    <label class="input-group-text" for="medida_id">Unidad de médida Mínima</label>
	                                </div>
	                                <select class="custom-select custom-select-sm select2 select2bs4" id="medida_id"  name="medida_id">
	                                    <option value="">Seleccionar...</option>
	                                    @foreach($pUnidades as $U)
	                                        <option value="{{ $U->id}}" @if($producto->medida_id == $U->id) selected @endif>{{ $U->descripcion}}</option>
	                                    @endforeach
	                                </select>
	                            </div>
	                        </div>
	                        <div class="form-group offset-md-1">
					            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
					              	<input type="checkbox" class="custom-control-input" id="premedicacion" name="premedicacion" value="1" @if($producto->premedicacion == 1) checked @endif>
					          		<label class="custom-control-label" for="premedicacion">Pre Medicación</label>
					        	</div>
					      	</div>
	                        <div class="form-group offset-md-1">
					            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
					              	<input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A" @if($producto->estado == 1) checked @endif>
					          		<label class="custom-control-label" for="estado">Activar</label>
					        	</div>
					      	</div>
	                    </div>
						<hr>
						<div class="row" id="panel_productos">
							<div class="col-md-10 offset-md-1">
								<ul class="nav nav-pills nav-fill">
									<li class="nav-item">
				                        <a class="nav-link active" href="#unidades" data-toggle="tab" id="nav-link-unidades">Unidades de Medída</a>
				                    </li>
				                    <li class="nav-item">
				                        <a class="nav-link disabled" href="#caracteristicas" data-toggle="tab" id="nav-link-caracteristicas"><h6>Caracteristicas</h6></a>
				                    </li>
				                    <li class="nav-item">
				                        <a class="nav-link disabled" href="#dosis" data-toggle="tab" id="nav-link-dosis"><h6>Dosis</h6></a>
				                    </li>
				        		</ul>
				        		<div class="tab-content">
				        			<div class="tab-pane active" id="unidades">
				        				<br>
				        				<div class="row">
											<div class="col-md-1 offset-md-11" style="text-align: right;">
			                    				<a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Agregar Unidad de Medída" onclick="fnAgregarMedida(); return false;">
			                    					<i class="fas fa-plus-circle"></i>
			                    				</a>
			                    			</div>
										</div>
										<br>
										<table class="table table-sm table-striped" id="tblmedidas">
                                            <thead>
                                                <tr style="text-align: center; font-size: 12px;">
                                                	<th style="width: 50%">Unidad de Medída</th>
                                                	<th style="width: 45%">Unidades</th>
                                                	<th style="width: 5%"></th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
				        			</div>
				        			<div class="tab-pane" id="caracteristicas">
				        				<br>
				        				<div class="row">
											<div class="col-md-1 offset-md-11" style="text-align: right;">
			                    				<a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Agregar Unidad de Medída" onclick="fnAgregarCaracteristica(); return false;">
			                    					<i class="fas fa-plus-circle"></i>
			                    				</a>
			                    			</div>
										</div>
										<br>
										<table class="table table-sm table-striped" id="tblcaracteristicas">
                                            <thead>
                                                <tr style="text-align: center; font-size: 12px;">
                                                	<th>Descripción</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
				        			</div>
				        			<div class="tab-pane" id="proveedores">
				        			</div>
				        			<div class="tab-pane" id="dosis">
			        					<br>
				        				<div class="row">
											<div class="col-md-1 offset-md-11" style="text-align: right;">
			                    				<a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Agregar Unidad de Medída" onclick="fnAgregarDosis(); return false;">
			                    					<i class="fas fa-plus-circle"></i>
			                    				</a>
			                    			</div>
										</div>
										<br>
										<table class="table table-sm table-striped" id="tbldosis">
                                            <thead>
                                                <tr style="text-align: center; font-size: 12px;"><th>Dosis</th><th>Descripción</th></tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
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
	@if(Session::get('type') == 'success')
        @if(Session::has('message'))
            <script>
                
                setTimeout(function() {
                    Swal.fire({
				        title: "¡Trabajo Finalizado!",
				        text: "{!! Session::get('message') !!}",
				        icon: "success", // Cambiado de 'type' a 'icon'
				        confirmButtonText: "Aceptar",
				        confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
				        customClass: {
				            confirmButton: 'btn btn-success'
				        },
				        buttonsStyling: false
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
				        title: "¡Trabajo Finalizado!",
				        text: "{!! Session::get('message') !!}",
				        icon: "error", // Cambiado de 'type' a 'icon'
				        confirmButtonText: "Aceptar",
				        confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
				        customClass: {
				            confirmButton: 'btn btn-danger'
				        },
				        buttonsStyling: false
				    });
                }, 1000);
            </script>
        @endif
    @endif

    <script type="text/javascript">
    	var nlinea  = 0;
    	var mlinea  = 0;
    	var xlinea  = 0;
    	var nLineaT = 0;
    	var nLineaD = 0;
    	var nLineaC = 0;

        $('select[name="inv_clasificacion_id"]').change(function() {
    		let clasificacion_id = $(this).val();
    		$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('extras') }}",
                method: "POST",
                data: {id: clasificacion_id},
                success: function(response){
                    if (response['definir_medidas'] == 1) {
                    	//document.getElementById('siglas').required = false;
						document.getElementById('medida_id').required = true;
						$("#grpsiglas").hide();
						$("#grpmedida").show();
						$("#panel_productos").show();
                    }else{
                    	//document.getElementById('siglas').required = false;
						document.getElementById('medida_id').required = false;
						$("#grpsiglas").hide();
						$("#grpmedida").hide();
						$("#panel_productos").hide();
                    }

                    if (response['definir_dosis'] == 1) {
                    	$("#nav-link-dosis").removeClass('disabled');
                    }else{
                    	$("#nav-link-dosis").addClass('disabled');
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
		});

        $(document).ready(function() {
        	let clasificacion_id = document.getElementById('inv_clasificacion_id').value;
        	let producto_id = document.getElementById('producto_id').value;
        	$.ajax({
		        headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('trae_medidas_x_producto') }}",
                method: "POST",
                data: {producto_id: producto_id},
		        success: function(response) {
		            response.forEach(function(item, index) {
		            	fnAgregarMedida();
		            	let unidadMedidaInput = $('#medidas\\['+index+'\\]\\[unidad_medida_id\\]');
            			let cantidadInput = $('#medidas\\['+index+'\\]\\[cantidad\\]');
            			if (unidadMedidaInput.length > 0) {
		                    unidadMedidaInput.val(item['unidad_medida_id']).trigger('change');
		                } else {
		                    console.error('No se encontró el input para unidad_medida_id en el índice ' + index);
		                }

		                if (cantidadInput.length > 0) {
		                    cantidadInput.val(item['cantidad']);
		                } else {
		                    console.error('No se encontró el input para cantidad en el índice ' + index);
		                }
		            });
		        },
		        error: function() {
		            // Este bloque se ejecuta si hay un error con la solicitud
		            console.error('Error en la solicitud AJAX:');
		        }
		    });

		    $.ajax({
		        headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('trae_caracteristicas_x_producto') }}",
                method: "POST",
                data: {producto_id: producto_id},
		        success: function(response) {
		            response.forEach(function(item, index) {
		            	fnAgregarCaracteristica();
		            	// let dosisSelect = $('#dosis\\['+index+'\\]\\[dosis_id\\]');
            			let idInput = $('#caracteristica\\['+index+'\\]\\[id\\]');
            			let descripcionInput = $('#caracteristica\\['+index+'\\]\\[descripcion\\]');
		                if (idInput.length > 0) {
		                    idInput.val(item['id']);
		                } else {
		                    console.error('No se encontró el input para cantidad en el índice ' + index);
		                }
		                if (descripcionInput.length > 0) {
		                    descripcionInput.val(item['descripcion']);
		                } else {
		                    console.error('No se encontró el input para cantidad en el índice ' + index);
		                }
		            });
		        },
		        error: function() {
		            // Este bloque se ejecuta si hay un error con la solicitud
		            console.error('Error en la solicitud AJAX:');
		        }
		    });

		    $.ajax({
		        headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('trae_dosis_x_producto') }}",
                method: "POST",
                data: {producto_id: producto_id},
		        success: function(response) {
		            response.forEach(function(item, index) {
		            	fnAgregarDosis();
		            	let dosisSelect = $('#dosis\\['+index+'\\]\\[dosis_id\\]');
            			let descripcionInput = $('#dosis\\['+index+'\\]\\[descripcion\\]');
            			if (dosisSelect.length > 0) {
		                    dosisSelect.val(item['unidad_medida_id']).trigger('change'); // Usamos trigger para aplicar select2
		                } else {
		                    console.error('No se encontró el select para dosis en el índice ' + index);
		                }
		                if (descripcionInput.length > 0) {
		                    descripcionInput.val(item['descripcion']);
		                } else {
		                    console.error('No se encontró el input para cantidad en el índice ' + index);
		                }
		            });
		        },
		        error: function() {
		            // Este bloque se ejecuta si hay un error con la solicitud
		            console.error('Error en la solicitud AJAX:');
		        }
		    });

		    $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('extras') }}",
                method: "POST",
                data: {id: clasificacion_id},
                success: function(response){
                    if (response['definir_medidas'] == 1) {
                    	//document.getElementById('siglas').required = false;
						document.getElementById('medida_id').required = true;
						$("#grpsiglas").hide();
						$("#grpmedida").show();
						$("#panel_productos").show();
                    }else{
                    	//document.getElementById('siglas').required = false;
						document.getElementById('medida_id').required = false;
						$("#grpsiglas").hide();
						$("#grpmedida").hide();
						$("#panel_productos").hide();
                    }

                    if (response['definir_caracteristica'] == 1) {
                    	$("#nav-link-caracteristicas").removeClass('disabled');
                    }else{
                    	$("#nav-link-caracteristicas").addClass('disabled');
                    }

                    if (response['definir_dosis'] == 1) {
                    	$("#nav-link-dosis").removeClass('disabled');
                    }else{
                    	$("#nav-link-dosis").addClass('disabled');
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        });

        function fnAgregarMedida(){
            event.preventDefault();
            var html = '';
            html += '<tr>';
            html += '<td style="width: 50%">';
            html += '<select class="custom-select custom-select-sm select2 select2bs4" id="medidas['+nLineaT+'][unidad_medida_id]" name="medidas['+nLineaT+'][unidad_medida_id]" required>';
            html += '<option value="">Seleccionar...</option>';
            @foreach($pUnidades as $U)
            	html += '<option value="{{ $U->id }}">{{ $U->descripcion }}</option>';
            @endforeach
            html += '</select>'
            html += '</td>';
            html += '<td style="width: 45%">';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="number" step="1" min="0" class="form-control numero" id="medidas['+nLineaT+'][cantidad]" name="medidas['+nLineaT+'][cantidad]" required/>';
            html += '</div>';
            html += '</td>';
            html += '<td style="text-align: right; width: 5%">';
            html += '<button class="btn btn-xs btn-outline-danger rounded-circle elevation-4 eliminar" title="eliminar registro"><i class="fas fa-minus-circle"></i></button>'
            html += '</td>';    
            html += '</tr>';
            $('#tblmedidas tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLineaT += 1;
            $('.select2').select2()
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        }

        function fnAgregarCaracteristica(){
        	event.preventDefault();
        	var html = '';
        	html += '<tr>';
        	html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="hidden" class="form-control" id="caracteristica['+nLineaC+'][id]" name="caracteristica['+nLineaC+'][id]" required/>';
            html += '</div>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="text" class="form-control" id="caracteristica['+nLineaC+'][descripcion]" name="caracteristica['+nLineaC+'][descripcion]" required/>';
            html += '</div>';
            html += '</td>';
            html += '<td style="text-align: right;">';
            html += '<button class="btn btn-xs btn-outline-danger rounded-circle elevation-4 eliminar" title="eliminar registro"><i class="fas fa-minus-circle"></i></button>'
            html += '</td>';    
        	html += '</tr>';
        	$('#tblcaracteristicas tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLineaC += 1;
        }

        function fnAgregarDosis(){
        	event.preventDefault();
            var html = '';
            html += '<tr>';
            html += '<td>';
            html += '<select class="custom-select custom-select-sm select2 select2bs4" id="dosis['+nLineaD+'][dosis_id]" name="dosis['+nLineaD+'][dosis_id]" required>';
            html += '<option value="">Seleccionar...</option>';
            @foreach($dosis as $d)
            html += '<option value="{{$d->id}}">{{$d->descripcion}}</option>';
            @endforeach
            html += '</select>'
            html += '</td>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="text" step="1" min="0" class="form-control" id="dosis['+nLineaD+'][descripcion]" name="dosis['+nLineaD+'][descripcion]" required/>';
            html += '</div>';
            html += '</td>';
            html += '<td style="text-align: right;">';
            html += '<button class="btn btn-xs btn-outline-danger rounded-circle elevation-4 eliminar" title="eliminar registro"><i class="fas fa-minus-circle"></i></button>'
            html += '</td>';    
            html += '</tr>';
            $('#tbldosis tbody').append(html);
            $('#dosis[' + nLineaD + '][dosis_id]').select2({
		        theme: 'bootstrap4'
		    });
            $('.eliminar').on('click',eliminar);
            nLineaD += 1;
            $('.select2').select2()
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        }

        function eliminar(){
            var whichtr = $(this).closest("tr");
            whichtr.remove(); 
            return false;
        }

        function confirma_salida(){
            
            Swal.fire({
			    title: 'Confirmación',
			    text: '¿Seguro de Salir? Si ha realizado cambios, estos no serán guardados.',
			    icon: 'warning', // En v2 'type' cambió a 'icon'
			    showCancelButton: true,
			    confirmButtonText: 'Si',
			    cancelButtonText: 'No',
			    customClass: {
			        confirmButton: 'btn btn-success mx-2', // Clases de Bootstrap
			        cancelButton: 'btn btn-danger mx-2'
			    },
			    buttonsStyling: false, // Permite usar las clases de Bootstrap arriba definidas
			    allowEscapeKey: true
			}).then((result) => {
			    /* result.isConfirmed es el nuevo estándar para verificar el clic en 'Si' */
			    if (result.isConfirmed) { 
			        window.location.href = "{{ route('productos') }}";
			    }
			});
        }
    </script>
@endsection