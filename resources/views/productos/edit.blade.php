@extends('adminlte::page')
@section('css')
	<style type="text/css">
		.nav-pills .nav-link.active,
        .show>.nav-pills .nav-link{
            background: #7FB3D5 !important;
            color: #000000 !important;
        }
        .numero{
            text-align: right;
        }
		.select2-container {
            width: 100% !important;
        }

        /* Fuerza a Select2 a ocupar el 100% real del espacio del input-group */
        .select2-container--bootstrap4 {
            flex: 1 1 auto;
            width: auto !important;
        }

        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(1.5em + 0.5rem + 2px) !important; /* Ajuste para input-group-sm */
        }

        /* Evita que el contenedor se desborde */
        .input-group > .select2-container--bootstrap4 {
            width: 0 !important;
            flex: 1 1 auto !important;
        }
	</style>
@endsection
@section('content_header')
    <br>
@endsection
@section('content')
	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-12 col-md-10 col-lg-8">
	            <form role="form" method="POST" action="{{route('actualizar_producto')}}">
	                @csrf
	                <div class="card shadow-sm">
	                    <div class="card-header" style="background-color: #E1E8ED;">
	                        <div class="d-flex justify-content-between align-items-center">
	                            <h6 class="mb-0">Edición de Producto</h6>
	                            <div>
	                                <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-2" title="Guardar">
	                                    <i class="fas fa-save"></i>
	                                </button>
	                                <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-2" title="Salir" onclick="confirma_salida(); return false;">
	                                    <i class="fas fa-sign-out-alt"></i>
	                                </a>
	                            </div>
	                        </div>
	                    </div>

	                    <div class="card-body">
	                        <input type="hidden" id="producto_id" name="producto_id" value="{{ $producto->id }}">
	                        
	                        <div class="row">
	                            <div class="col-12 col-md-6 mb-2">
	                                <div class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text" for="inv_clasificacion_id">Clasificación</label>
	                                    </div>
	                                    <select class="custom-select select2 select2bs4" id="inv_clasificacion_id" name="inv_clasificacion_id" required>
	                                        <option value="">Seleccionar...</option>
	                                        @foreach($clasificaciones as $clasificacion)
	                                            <option value="{{ $clasificacion->id}}" @if($clasificacion->id == $producto->clasificacion) selected @endif>{{ $clasificacion->nombre}}</option>
	                                        @endforeach
	                                    </select>
	                                </div>
	                            </div>
	                            <div class="col-12 col-md-6 mb-2">
	                                <div class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text" for="inv_familia_id">Familia</label>
	                                    </div>
	                                    <select class="custom-select select2 select2bs4" id="inv_familia_id" name="inv_familia_id" required>
	                                        <option value="">Seleccionar...</option>
	                                        @foreach($familias as $familia)
	                                            <option value="{{ $familia->id}}" @if($familia->id == $producto->inv_familia_id) selected @endif>{{ $familia->nombre}}</option>
	                                        @endforeach
	                                    </select>
	                                </div>
	                            </div>
	                        </div>

	                        <div class="row">
	                            <div class="col-12 col-md-4 mb-2">
	                                <div id="grpsiglas" class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text">Siglas</label>
	                                    </div>
	                                    <input type="text" class="form-control" placeholder="..." id="siglas" name="siglas" value="{{ $producto->siglas }}">
	                                </div>
	                            </div>
	                            <div class="col-12 mb-2">
	                                <div id="grpdescripcion" class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text">Descripción</label>
	                                    </div>
	                                    <input type="text" class="form-control" id="descripcion" name="descripcion" required value="{{ $producto->descripcion }}">
	                                </div>
	                            </div>
	                        </div>

	                        <div class="row">
	                            <div class="col-12 mb-2">
	                                <div id="grpdescripcionm" class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text">Texto a mostrar</label>
	                                    </div>
	                                    <input type="text" class="form-control" id="descripcion_a_mostrar" name="descripcion_a_mostrar" required value="{{ $producto->descripcion_a_mostrar }}">
	                                </div>
	                            </div>
	                        </div>

	                        <div class="row align-items-center">
	                            <div class="col-12 col-md-6 mb-2">
	                                <div class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text">U. Medida Mínima</label>
	                                    </div>
	                                    <select class="custom-select select2 select2bs4" id="medida_id" name="medida_id">
	                                        <option value="">Seleccionar...</option>
	                                        @foreach($pUnidades as $U)
	                                            <option value="{{ $U->id}}" @if($producto->medida_id == $U->id) selected @endif>{{ $U->descripcion}}</option>
	                                        @endforeach
	                                    </select>
	                                </div>
	                            </div>
	                            <div class="col-12 col-md-6 mb-2 d-flex justify-content-around">
	                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
	                                    <input type="checkbox" class="custom-control-input" id="premedicacion" name="premedicacion" value="1" @if($producto->premedicacion == 1) checked @endif>
	                                    <label class="custom-control-label" for="premedicacion">Pre Medicación</label>
	                                </div>
	                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
	                                    <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A" @if($producto->estado == 1) checked @endif>
	                                    <label class="custom-control-label" for="estado">Activar</label>
	                                </div>
	                            </div>
	                        </div>

	                        <hr>

	                        <div class="row" id="panel_productos">
	                            <div class="col-12">
	                                <ul class="nav nav-pills nav-fill flex-column flex-sm-row mb-3" role="tablist">
	                                    <li class="nav-item">
	                                        <a class="nav-link active small py-2" data-toggle="tab" href="#unidades">Unidades</a>
	                                    </li>
	                                    <li class="nav-item">
	                                        <a class="nav-link disabled small py-2" data-toggle="tab" href="#caracteristicas">Características</a>
	                                    </li>
	                                    <li class="nav-item">
	                                        <a class="nav-link disabled small py-2" data-toggle="tab" href="#dosis">Dosis</a>
	                                    </li>
	                                </ul>

	                                <div class="tab-content border rounded p-2 bg-light">
	                                    <div class="tab-pane fade show active" id="unidades">
	                                        <div class="d-flex justify-content-end mb-2">
	                                            <button type="button" class="btn btn-xs btn-outline-primary rounded-circle" onclick="fnAgregarMedida()">
	                                                <i class="fas fa-plus"></i>
	                                            </button>
	                                        </div>
	                                        <div class="table-responsive">
	                                            <table class="table table-sm table-striped w-100" id="tblmedidas">
	                                                <thead class="thead-light">
	                                                    <tr class="small text-center">
	                                                        <th>Unidad</th>
	                                                        <th>Cantidad</th>
	                                                        <th></th>
	                                                    </tr>
	                                                </thead>
	                                                <tbody></tbody>
	                                            </table>
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
	</div>
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
                        confirmButtonColor: '#28a745', // Color success de AdminLTE
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
    	var nlinea  = 0;
    	var mlinea  = 0;
    	var xlinea  = 0;
    	var nLineaT = 0;
    	var nLineaD = 0;
    	var nLineaC = 0;

    	//========================================================================
        // inicializar librerias
        //========================================================================
        $(document).ready(function() {
            // Inicialización robusta para Bootstrap 4
            $('.select2bs4').each(function() {
                $(this).select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: "Seleccionar...",
                    allowClear: true,
                    // Si el select está dentro de un modal, descomenta la siguiente línea:
                    // dropdownParent: $(this).parent() 
                });
            });

            // Corrección de bug de foco en el buscador de Select2
            $(document).on('select2:open', () => {
                let searchField = document.querySelector('.select2-search__field');
                if (searchField) {
                    searchField.focus();
                }
            });
        });

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

		    // $.ajax({
		    //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     },
            //     url: "{{ route('trae_caracteristicas_x_producto') }}",
            //     method: "POST",
            //     data: {producto_id: producto_id},
		    //     success: function(response) {
		    //         response.forEach(function(item, index) {
		    //         	fnAgregarCaracteristica();
		    //         	// let dosisSelect = $('#dosis\\['+index+'\\]\\[dosis_id\\]');
            // 			let idInput = $('#caracteristica\\['+index+'\\]\\[id\\]');
            // 			let descripcionInput = $('#caracteristica\\['+index+'\\]\\[descripcion\\]');
		    //             if (idInput.length > 0) {
		    //                 idInput.val(item['id']);
		    //             } else {
		    //                 console.error('No se encontró el input para cantidad en el índice ' + index);
		    //             }
		    //             if (descripcionInput.length > 0) {
		    //                 descripcionInput.val(item['descripcion']);
		    //             } else {
		    //                 console.error('No se encontró el input para cantidad en el índice ' + index);
		    //             }
		    //         });
		    //     },
		    //     error: function() {
		    //         // Este bloque se ejecuta si hay un error con la solicitud
		    //         console.error('Error en la solicitud AJAX:');
		    //     }
		    // });

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

        //===================================================================
        // Confirmar salida de pantalla
        //===================================================================
        function confirma_salida(){
            Swal.fire({
                title: 'Confirmación',
                text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745', // Color success de AdminLTE
                cancelButtonColor: '#dc3545',  // Color danger de AdminLTE
                confirmButtonText: 'Si, Salir',
                cancelButtonText: 'No',
                allowEscapeKey: true,
                reverseButtons: true // Opcional: pone el botón de confirmar a la derecha
            }).then((result) => {
                if (result.isConfirmed) { 
                    window.location.href = "{{ route('productos') }}";
                } 
            });
        }
    </script>
@endsection