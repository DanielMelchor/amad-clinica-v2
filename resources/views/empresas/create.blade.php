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
        .nav-link {
            font-family: monospace;
            font-size: 10pt;
        }
        .numero{
            text-align: right;
        }
        .moneda:after {
            content: attr(data-numero);
        }
        .table-responsive {
            max-width: 100%; /* Ajusta el ancho según tus necesidades */
            overflow-x: auto; /* Permite el desplazamiento horizontal */
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
@section('title', 'Empresas')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <form role="form" id="formaEmpresa" method="POST" action="{{ route('grabar_empresa') }}" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="row">
                            <div class="col-md-9">
                                <h6>Agregar Empresa</h6>
                            </div>
                            <div class="col-md-3" style="text-align: right;">
                                <button type="submit" id="submitButton" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
                                <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" onclick="confirma_salida(); return false;" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-pills nav-justified">
                            <li class="nav-item">
                                <a class="nav-link active" href="#generales" data-toggle="tab">Generales</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#logotipo" data-toggle="tab">Logotipo</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="generales">
                                <br>
                                <input type="hidden" name="form_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="input-group input-group-sm mb-1 col-md-12">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text w-100">Razon social</label>
                                                </div>
                                                <input type="text" class="form-control" placeholder="Razon social" aria-label="Username" aria-describedby="basic-addon1" placeholder="Razón Social" id="razon_social" name="razon_social" autofocus required value="{{ old('razon_social')}}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm mb-1 col-md-12">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text w-100">Nombre Comercial</label>
                                                </div>
                                                <input type="text" class="form-control" placeholder="nombre comercial" aria-label="Username" aria-describedby="basic-addon1" placeholder="nombre comercial" id="nombre_comercial" name="nombre_comercial" required value="{{ old('nombre_comercial')}}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm mb-1 col-md-6">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="codigo_postal">Codigo Postal.</label>
                                                </div>
                                                <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal')}}" required>
                                            </div>
                                            <div class="input-group input-group-sm mb-1 col-md-6">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="email">Email</label>
                                                </div>
                                                <input type="mail" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="email" name="email" value="{{ old('email')}}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm mb-1 col-md-12">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="telefonos">Teléfonos</label>
                                                </div>
                                                <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="telefonos" name="telefonos" value="{{ old('telefonos')}}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm mb-1 col-md-6">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="nit">N.I.T.</label>
                                                </div>
                                                <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="nit" name="nit" value="{{ old('nit')}}">
                                            </div>
                                            <div class="input-group input-group-sm mb-1 col-md-6">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="igss">I.G.S.S</label>
                                                </div>
                                                <input type="mail" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="igss" name="igss" value="{{ old('igss')}}" >
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm mb-1 col-md-12">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="fecha_constitucion">Fecha Constitución</label>
                                                </div>
                                                <input type="date" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="fecha_constitucion" name="fecha_constitucion" value="{{ old('fecha_constitucion')}}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm mb-1 col-md-6">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="afiliacion_iva">Afiliación</label>
                                                </div>
                                                <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="afiliacion_iva" name="afiliacion_iva" value="{{ old('afiliacion_iva')}}">
                                            </div>
                                            <div class="input-group input-group-sm mb-1 col-md-6">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="porcentaje_impuesto">Porcentaje</label>
                                                </div>
                                                <input type="number" step="1" min="1" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="porcentaje_impuesto" name="porcentaje_impuesto" value="{{ old('porcentaje_impuesto')}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header" style="background-color: #E1E8ED;">
                                                <h6>Ubicación</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="input-group input-group-sm mb-1 col-md-12">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Dirección</label>
                                                        </div>
                                                        <input type="text" class="form-control" placeholder="direccion" aria-label="Username" aria-describedby="basic-addon1" placeholder="direccion" id="direccion" name="direccion" value="{{ old('direccion')}}" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-group input-group-sm col-md-12 mb-1">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="pais_id">País</label>
                                                        </div>
                                                        <select class="custom-select select2bs4" id="pais_id" name="pais_id" required>
                                                            <option value="" selected>Seleccionar...</option>
                                                            @foreach($paises as $p)
                                                                <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-group input-group-sm col-md-12 mb-1">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="departamento_id">Departamento</label>
                                                        </div>
                                                        <select class="custom-select select2bs4" id="departamento_id" name="departamento_id" required>
                                                            <option value="" selected>Seleccionar...</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-group input-group-sm col-md-12 mb-1">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="municipio_id">Municipio</label>
                                                        </div>
                                                        <select class="custom-select select2bs4" id="municipio_id" name="municipio_id" required>
                                                            <option value="" selected>Seleccionar...</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-10 offset-md-1">
                                        <div class="card">
                                            <div class="card-header" style="background-color: #E1E8ED;">
                                                <h6>Correlativos</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="input-group input-group-sm mb-1 col-md-12">
                                                                <div class="input-group-prepend">
                                                                    <label class="input-group-text w-100">Correlativo Pacientes</label>
                                                                </div>
                                                                <input type="number" step="1" min="0" class="form-control numero" placeholder="000000" aria-label="Username" aria-describedby="basic-addon1" placeholder="Razón Social" id="correlativo_pacientes" name="correlativo_pacientes" autofocus required value="{{ old('correlativo_pacientes')}}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="input-group input-group-sm mb-1 col-md-12">
                                                                <div class="input-group-prepend">
                                                                    <label class="input-group-text w-100">Correlativo Admisiones</label>
                                                                </div>
                                                                <input type="number" step="1" min="0" class="form-control numero" placeholder="000000" aria-label="Username" aria-describedby="basic-addon1" id="correlativo_admisiones" name="correlativo_admisiones" required value="{{ old('correlativo_admisiones')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-10 offset-md-1">
                                        <div class="card">
                                            <div class="card-header" style="background-color: #E1E8ED;">
                                                <h6>Factura Electrónica</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="input-group input-group-sm mb-1 col-md-6">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Firma</label>
                                                        </div>
                                                        <input type="text" class="form-control" placeholder="Firma" aria-label="Username" aria-describedby="basic-addon1" placeholder="llave_firma" id="llave_firma" name="llave_firma" value="{{ old('llave_firma')}}">
                                                    </div>
                                                    <div class="input-group input-group-sm mb-1 col-md-6">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Certificación</label>
                                                        </div>
                                                        <input type="text" class="form-control" placeholder="Llave Certificación" aria-label="Username" aria-describedby="basic-addon1" placeholder="llave_certificacion" id="llave_certificacion" name="llave_certificacion" value="{{ old('llave_certificacion')}}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-group input-group-sm mb-1 col-md-6">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="alias">Alias</label>
                                                        </div>
                                                        <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="alias" name="alias" value="{{ old('alias')}}">
                                                    </div>
                                                    <div class="input-group input-group-sm mb-1 col-md-6">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="formato">Impresión</label>
                                                        </div>
                                                        <input type="number" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="formato" name="formato" value="{{ old('formato')}}" step="1" min="1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="form-group offset-md-1">
                                        <div class="custom-control custom-control-sm custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
                                            <label class="custom-control-label" for="estado">Activar</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="logotipo">
                                <br>
                                <br>
                                <div class="row">
                                    <div class="form-group col offset-md-1">
                                        <label for="logo_empresa">Seleccionar:</label>
                                        <input type="file" name="logo_empresa" id="logo_empresa" accept="image/*" />
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
    {{-- Capturar Errores de Validación (como el unique:username) --}}
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Revisar Formulario',
                // Unimos todos los mensajes de error en una lista
                html: `
                    <ul style="text-align: left;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                confirmButtonText: "Aceptar",
                confirmButtonColor: "#dc3545",
                customClass: {
                    confirmButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });
        </script>
    @endif
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
        
        $(document).ready(function() {
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: "Seleccionar...",
                allowClear: true,
                // SI ESTÁS EN UN MODAL, añade esta línea:
                // dropdownParent: $('#nombre_de_tu_modal') 
            });

            // Truco para corregir el bug de falta de foco en el buscador
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
        });

        $("#pais_id").change(function(){
            var pais_id = document.getElementById('pais_id').value;
            if (pais_id.length > 0) {
                var html = '<option value="" selected>Seleccionar...</option>'
                $.ajax({
                    url: "{{ route('departamentos_x_pais') }}",
                    type: "POST",
                    async: true,
                    data: {"_token": "{{ csrf_token() }}", 
                           pais_id : pais_id},
                    success: function(response){
                        for (var i = 0; i < response.length; i++) {
                            // console.log(response[i]);
                            html += '<option value="'+response[i]['id']+'">'+response[i]['nombre']+'</option>'
                        }
                        $("#departamento_id").empty().append(html);
                        $("#municipio_id").empty();
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }
        });

        $("#departamento_id").change(function(){
            var departamento_id = document.getElementById('departamento_id').value;
            if (departamento_id.length > 0) {
                var html = '<option value="" selected>Seleccionar...</option>'
                $.ajax({
                    url: "{{ route('municipios_x_departamento') }}",
                    type: "POST",
                    async: true,
                    data: {"_token": "{{ csrf_token() }}", 
                           departamento_id : departamento_id},
                    success: function(response){
                        for (var i = 0; i < response.length; i++) {
                            html += '<option value="'+response[i]['id']+'">'+response[i]['nombre']+'</option>'
                        }
                        $("#municipio_id").empty().append(html);
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }
        });

        $(document).ready(function() {
            $('#formaEmpresa').on('submit', function() {
                // Deshabilitar el botón de submit cuando se envíe el formulario
                $('#submitButton').prop('disabled', true);
                // $('#submitButton').text('Enviando...');
            });
        });

        //=======================================================================
        // Confirmar Salida de pantalla
        //=======================================================================
        function confirma_salida(){
            Swal.fire({
                title: 'Confirmación',
                text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                // Esto es clave:
                buttonsStyling: false, 
                customClass: {
                    confirmButton: 'btn btn-success mx-2', // Agregamos 'btn' y margen
                    cancelButton: 'btn btn-danger mx-2'
                },
                allowEscapeKey: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('empresas') }}";
                }
            });
        }
    </script>
@endsection