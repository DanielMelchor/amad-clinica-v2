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
@section('content')
	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-12 col-md-10">
	            <form role="form" id="formaNuevoRegistro" method="POST" action="{{route('grabar_producto')}}">
	                @csrf
	                <div class="card shadow-sm">
	                    <div class="card-header" style="background-color: #E1E8ED;">
	                        <div class="d-flex justify-content-between align-items-center">
	                            <h6 class="mb-0">Agregar Producto</h6>
	                            <div>
	                                <button type="submit" id="submitButton" class="btn btn-xs btn-outline-success rounded-circle elevation-2" title="Guardar"><i class="fas fa-save"></i></button>
	                                <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-2" title="Salir" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
	                            </div>
	                        </div>
	                    </div>

	                    <div class="card-body">
	                        <div class="row">
	                            <div class="col-12 col-md-6 mb-3">
	                                <div class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text" for="inv_clasificacion_id">Clasificación</label>
	                                    </div>
	                                    <select class="custom-select select2bs4" id="inv_clasificacion_id" name="inv_clasificacion_id" required>
	                                        <option value="">Seleccionar...</option>
	                                        @foreach($clasificaciones as $clasificacion)
	                                            <option value="{{ $clasificacion->id}}">{{ $clasificacion->nombre}}</option>
	                                        @endforeach
	                                    </select>
	                                </div>
	                            </div>
	                            <div class="col-12 col-md-6 mb-3">
	                                <div class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text" for="inv_familia_id">Familias</label>
	                                    </div>
	                                    <select class="custom-select select2bs4" id="inv_familia_id" name="inv_familia_id" required>
	                                        <option value="">Seleccionar...</option>
	                                        @foreach($familias as $familia)
	                                            <option value="{{ $familia->id}}">{{ $familia->nombre}}</option>
	                                        @endforeach
	                                    </select>
	                                </div>
	                            </div>
	                        </div>

	                        <div class="row">
	                            <div class="col-12 col-md-6 mb-3">
	                                <div id="grpsiglas" class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text">Siglas</label>
	                                    </div>
	                                    <input type="text" class="form-control" placeholder="Solo para procedimientos" id="siglas" name="siglas" value="{{ old('siglas')}}" maxlength="10" style="text-transform: uppercase;">
	                                </div>
	                            </div>
	                        </div>

	                        <div class="row">
	                            <div class="col-12 mb-3">
	                                <div id="grpdescripcion" class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text">Descripción</label>
	                                    </div>
	                                    <input type="text" class="form-control" placeholder="Descripción interna" id="descripcion" name="descripcion" required value="{{ old('descripcion') }}">
	                                </div>
	                            </div>
	                            <div class="col-12 mb-3">
	                                <div id="grpdescripcionm" class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text">Texto a mostrar</label>
	                                    </div>
	                                    <input type="text" class="form-control" placeholder="Texto para documentos del cliente" id="descripcion_a_mostrar" name="descripcion_a_mostrar" required value="{{ old('descripcion_a_mostrar') }}">
	                                </div>
	                            </div>
	                        </div>

	                        <div class="row align-items-center">
	                            <div class="col-12 col-md-6 mb-3">
	                                <div id="grpmedida" class="input-group input-group-sm">
	                                    <div class="input-group-prepend">
	                                        <label class="input-group-text" for="medida_id">U. Medida Mínima</label>
	                                    </div>
	                                    <select class="custom-select select2bs4" id="medida_id" name="medida_id">
	                                        <option value="">Seleccionar...</option>
	                                        @foreach($pUnidades as $U)
	                                            <option value="{{ $U->id}}">{{ $U->descripcion}}</option>
	                                        @endforeach
	                                    </select>
	                                </div>
	                            </div>
	                            <div class="col-12 col-md-6 mb-3 d-flex justify-content-around">
	                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
	                                    <input type="checkbox" class="custom-control-input" id="premedicacion" name="premedicacion" value="1">
	                                    <label class="custom-control-label" for="premedicacion">Pre Medicación</label>
	                                </div>
	                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
	                                    <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
	                                    <label class="custom-control-label" for="estado">Activar</label>
	                                </div>
	                            </div>
	                        </div>

	                        <hr>

	                        <div class="row" id="panel_productos">
	                            <div class="col-12">
	                                <ul class="nav nav-pills nav-fill flex-column flex-sm-row mb-3" id="pills-tab" role="tablist">
	                                    <li class="nav-item">
	                                        <a class="nav-link active py-2" data-toggle="tab" href="#unidades">Unidades</a>
	                                    </li>
	                                    <li class="nav-item">
	                                        <a class="nav-link disabled py-2" data-toggle="tab" href="#caracteristicas">Características</a>
	                                    </li>
	                                    <li class="nav-item">
	                                        <a class="nav-link disabled py-2" data-toggle="tab" href="#dosis">Dosis</a>
	                                    </li>
	                                </ul>

	                                <div class="tab-content border p-2 rounded bg-light">
	                                    <div class="tab-pane fade show active" id="unidades">
	                                        <div class="d-flex justify-content-end mb-2">
	                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-circle" onclick="fnAgregarMedida()"><i class="fas fa-plus"></i></button>
	                                        </div>
	                                        <div class="table-responsive">
	                                            <table class="table table-sm table-striped w-100" id="tblmedidas">
	                                                <thead>
	                                                    <tr class="text-center small">
	                                                        <th style="width: 50%">Unidad de Medida</th>
	                                                        <th style="width: 40%">Unidades</th>
	                                                        <th style="width: 10%"></th>
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
    	
    	var tipo    = 'PROD';
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

        $(document).ready(function() {
            $('#formaNuevoRegistro').on('submit', function() {
                // Deshabilitar el botón de submit cuando se envíe el formulario
                $('#submitButton').prop('disabled', true);
                // $('#submitButton').text('Enviando...');
            });
        });

        window.addEventListener('load', function(){
			ruta = document.referrer;
			var proveedor_db = [];
			var medida_db    = [];
			$("#grpsiglas").hide();
			$("#grpmedida").hide();
			$('#panel_productos').hide();
			document.getElementById('siglas').required = false;
			document.getElementById('medida_id').required = true;
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
                    console.log(response);
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
        }

        function fnAgregarCaracteristica(){
        	event.preventDefault();
        	var html = '';
        	html += '<tr>';
        	html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="text" step="1" min="0" class="form-control" id="caracteristica['+nLineaC+'][descripcion]" name="caracteristica['+nLineaC+'][descripcion]" required/>';
            html += '</div>';
            html += '</td>';
        	html += '</tr>';
        	$('#tblcaracteristicas tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLineaC += 1;
            $('.select2').select2()
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
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

		$(function() {
		    $('input:radio[name="clasificacion_producto"]').change(function(){
		    	tipo = $(this).val();
		    });
		});

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