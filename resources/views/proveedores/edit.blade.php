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
            <div class="col-12 col-md-11 col-lg-10">
                <form role="form" method="POST" action="{{route('actualizar_proveedor')}}">
                    @csrf
                    <div class="card shadow-sm">
                        <div class="card-header" style="background-color: #E1E8ED;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Edición de Proveedor</h6>
                                <div class="btn-group-xs">
                                    <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-2" title="Guardar Cambios">
                                        <i class="fas fa-save"></i>
                                    </button>
                                    <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-2" title="Salir" onclick="confirma_salida(); return false;">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-2 p-md-4">
                            <input type="hidden" id="proveedor_id" name="proveedor_id" value="{{ $proveedor->id }}">
                            
                            <div class="row">
                                <div class="col-12 col-md-6 mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Razón Social</label>
                                        </div>
                                        <input type="text" class="form-control" id="razon_social" name="razon_social" required value="{{ $proveedor->razon_social }}" autofocus>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Nombre Comercial</label>
                                        </div>
                                        <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial" required value="{{ $proveedor->nombre_comercial }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Dirección</label>
                                        </div>
                                        <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $proveedor->direccion }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6 mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Teléfonos</label>
                                        </div>
                                        <input type="text" class="form-control" id="telefonos" name="telefonos" value="{{ $proveedor->telefonos }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Correo Electrónico</label>
                                        </div>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $proveedor->email }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center mb-2">
                                <div class="col-12 col-md-6 mb-2">
                                    <div class="p-2 border rounded bg-light d-flex justify-content-around align-items-center">
                                        <small class="font-weight-bold mr-2">Pago:</small>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="contado" name="condicion" value="0" @if( $proveedor->condicion == 0) checked @endif>
                                            <label for="contado">Contado</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="credito" name="condicion" value="1" @if( $proveedor->condicion == 1) checked @endif>
                                            <label for="credito">Crédito</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-3 mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Días Crédito</label>
                                        </div>
                                        <input type="number" class="form-control text-right" id="dias_credito" name="dias_credito" required value="{{ $proveedor->dias_credito }}" min="1" readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-3 mb-2 text-center text-md-left">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="1" @if($proveedor->estado == 1) checked @endif>
                                        <label class="custom-control-label font-weight-bold" for="estado">Activar</label>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-12">
                                    <ul class="nav nav-pills nav-justified flex-column flex-sm-row mb-3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active py-2" data-toggle="tab" href="#contactos">Contactos</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link py-2 disabled" data-toggle="tab" href="#productos">Productos</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content border rounded p-2 bg-white">
                                        <div class="tab-pane fade show active" id="contactos">
                                            <div class="d-flex justify-content-end mb-2">
                                                <button type="button" class="btn btn-xs btn-outline-primary rounded-circle elevation-2" onclick="nuevoContacto(); return false;">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped w-100" id="tblcontactos">
                                                    <thead class="thead-light small text-center">
                                                        <tr>
                                                            <th>Línea</th>
                                                            <th>Contacto</th>
                                                            <th>Teléfonos</th>
                                                            <th>Correo</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="small"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="productos">
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
        var nLinea = 0;
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


        document.addEventListener('DOMContentLoaded', function() {
            var proveedor_id = $('#proveedor_id').val();
            $.ajax({
                url: "{{route('trae_contactos')}}",
                type: 'POST',
                async: true,
                data: {"_token": "{{ csrf_token() }}",
                       proveedor_id: proveedor_id},
                success: function(response){
                    for (var i = 0; i < response.length; i++) {
                        nuevoContacto();
                        document.getElementById('contactos['+i+'][lineamedica_id]').value = response[i]['lineamedica_id'];
                        document.getElementById('contactos['+i+'][nombre_contacto]').value = response[i]['nombre_contacto'];
                        document.getElementById('contactos['+i+'][contacto_telefonos]').value = response[i]['telefonos'];
                        document.getElementById('contactos['+i+'][contacto_email]').value = response[i]['email'];
                    }
                },
                error: function(error){
                    console.log(error);
                }   
            });
        });

        //========================================================================
        // cuando cambia la condicion de contado a credito habilita campo de dias de credito
        //========================================================================
        $(document).ready(function(){
            $("input[name=condicion]").click(function () {    
                if ($(this).val() == 0) {
                    $("#dias_credito").attr('readonly','readonly'); 
                    $('#dias_credito').removeAttr("required");
                }else{
                    $("#dias_credito").removeAttr('readonly'); 
                    $('#dias_credito').prop("required", true);
                }
            });
        });

        // **************************************** //
        // ***** Agregar contacto a proveedor ***** //
        // **************************************** //
        function nuevoContacto(){
            event.preventDefault();
            var html = '';
            html += '<tr>';
            html += '<td>';
            html += '<select class="custom-select custom-select-sm select2bs4" id="contactos['+nLinea+'][lineamedica_id]" name="contactos['+nLinea+'][lineamedica_id]">';
            html += '<option value="">Seleccionar...</option>';
            @foreach($lineasmedicas as $lineamedica)
                html += '<option value="{{ $lineamedica->id }}">{{ $lineamedica->descripcion }}</option>';
            @endforeach
            html += '</select>'
            html += '</td>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="text" class="form-control" id="contactos['+nLinea+'][nombre_contacto]" name="contactos['+nLinea+'][nombre_contacto]"/>';
            html += '</div>';
            html += '</td>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="text" class="form-control" id="contactos['+nLinea+'][contacto_telefonos]" name="contactos['+nLinea+'][contacto_telefonos]"/>';
            html += '</div>';
            html += '</td>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="text" class="form-control" id="contactos['+nLinea+'][contacto_email]" name="contactos['+nLinea+'][contacto_email]"/>';
            html += '</div>';
            html += '</td>';
            html += '<td style="text-align: right">';
            html += '<button class="btn btn-xs btn-outline-danger rounded-circle elevation-4 eliminar" title="eliminar registro"><i class="fas fa-trash"></i></button>'
            html += '</td>';    
            html += '</tr>';
            $('#tblcontactos tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLinea ++;
        }

        // ***************************************** //
        // ***** Eliminar contacto a proveedor ***** //
        // ***************************************** //
        function eliminar()
        {
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
                    window.location.href = "{{ route('proveedores') }}";
                } 
            });
        }

    </script>
@endsection