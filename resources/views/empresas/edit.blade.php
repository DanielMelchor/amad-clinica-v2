@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
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
    </style>
@endsection
@section('title', 'Empresas')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <!-- form start -->
            <form role="form" method="POST" action="{{route('actualizar_empresa', $id )}}" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="row">
                            <div class="col-md-9">
                                <h6>Edición de Empresa</h6>
                            </div>
                            <div class="col-md-3" style="text-align: right;">
                                <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Grabar"><i class="fas fa-save"></i></button>
                                <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Regresar a lista de Empresas" onclick="confirma_salida();"><i class="fas fa-sign-out-alt"></i></a> 
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
                                                <input type="text" class="form-control" placeholder="Razon social" aria-label="Username" aria-describedby="basic-addon1" placeholder="Razón Social" id="razon_social" name="razon_social" autofocus required value="{{ $empresa->razon_social }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm mb-1 col-md-12">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text w-100">Nombre Comercial</label>
                                                </div>
                                                <input type="text" class="form-control" placeholder="nombre comercial" aria-label="Username" aria-describedby="basic-addon1" placeholder="nombre comercial" id="nombre_comercial" name="nombre_comercial" required value="{{ $empresa->nombre_comercial }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm mb-1 col-md-6">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="codigo_postal">Codigo Postal.</label>
                                                </div>
                                                <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="codigo_postal" name="codigo_postal" value="{{ $empresa->codigo_postal }}" required>
                                            </div>
                                            <div class="input-group input-group-sm mb-1 col-md-6">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="email">Email</label>
                                                </div>
                                                <input type="mail" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="email" name="email" value="{{ $empresa->email }}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm mb-1 col-md-12">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="telefonos">Teléfonos</label>
                                                </div>
                                                <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="telefonos" name="telefonos" value="{{ $empresa->telefonos }}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm mb-1 col-md-6">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="nit">N.I.T.</label>
                                                </div>
                                                <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="nit" name="nit" value="{{ $empresa->nit_empresa }}">
                                            </div>
                                            <div class="input-group input-group-sm mb-1 col-md-6">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="igss">I.G.S.S</label>
                                                </div>
                                                <input type="mail" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="igss" name="igss" value="{{ $empresa->igss_empresa }}" >
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm mb-1 col-md-12">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="fecha_constitucion">Fecha Constitución</label>
                                                </div>
                                                <input type="date" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="fecha_constitucion" name="fecha_constitucion" value="{{ $empresa->fecha_constitucion }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-group input-group-sm mb-1 col-md-6">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="afiliacion_iva">Afiliación</label>
                                                </div>
                                                <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="afiliacion_iva" name="afiliacion_iva" value="{{ $empresa->afiliacion_iva }}" required>
                                            </div>
                                            <div class="input-group input-group-sm mb-1 col-md-6">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="porcentaje_impuesto">Porcentaje</label>
                                                </div>
                                                <input type="number" step="1" min="1" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="porcentaje_impuesto" name="porcentaje_impuesto" value="{{ $empresa->porcentaje_impuesto }}" required>
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
                                                        <input type="text" class="form-control" placeholder="direccion" aria-label="Username" aria-describedby="basic-addon1" placeholder="direccion" id="direccion" name="direccion" value="{{ $empresa->direccion }}" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-group input-group-sm col-md-12 mb-1">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="pais_id">País</label>
                                                        </div>
                                                        <select class="custom-select select2 select2bs4" id="pais_id" name="pais_id" required>
                                                            <option value="" selected>Seleccionar...</option>
                                                            @foreach($paises as $p)
                                                                <option value="{{ $p->id }}" @if($empresa->pais_id == $p->id) selected @endif>{{ $p->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-group input-group-sm col-md-12 mb-1">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="departamento_id">Departamento</label>
                                                        </div>
                                                        <select class="custom-select select2 select2bs4" id="departamento_id" name="departamento_id" required>
                                                            <option value="" selected>Seleccionar...</option>
                                                            @foreach($departamentos as $d)
                                                                <option value="{{ $d->id }}" @if($empresa->departamento_id == $d->id) selected @endif>{{ $d->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-group input-group-sm col-md-12 mb-1">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="municipio_id">Municipio</label>
                                                        </div>
                                                        <select class="custom-select select2 select2bs4" id="municipio_id" name="municipio_id" required>
                                                            <option value="" selected>Seleccionar...</option>
                                                            @foreach($municipios as $m)
                                                                <option value="{{ $m->id }}" @if($empresa->municipio_id == $m->id) selected @endif>{{ $m->nombre }}</option>
                                                            @endforeach
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
                                                <h6>Factura Electrónica</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="input-group input-group-sm mb-1 col-md-6">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Firma</label>
                                                        </div>
                                                        <input type="text" class="form-control" placeholder="Firma" aria-label="Username" aria-describedby="basic-addon1" placeholder="llave_firma" id="llave_firma" name="llave_firma" value="{{ $empresa->llave_firma }}">
                                                    </div>
                                                    <div class="input-group input-group-sm mb-1 col-md-6">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Certificación</label>
                                                        </div>
                                                        <input type="text" class="form-control" placeholder="Llave Certificación" aria-label="Username" aria-describedby="basic-addon1" placeholder="llave_certificacion" id="llave_certificacion" name="llave_certificacion" value="{{ $empresa->llave_certifica }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-group input-group-sm mb-1 col-md-6">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="alias">Alias</label>
                                                        </div>
                                                        <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="alias" name="alias" value="{{ $empresa->alias }}">
                                                    </div>
                                                    <div class="input-group input-group-sm mb-1 col-md-6">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="formato">Impresión</label>
                                                        </div>
                                                        <input type="number" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="formato" name="formato" value="{{ $empresa->formato }}" step="1" min="1">
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
                                            <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A" @if($empresa->estado == 'A') checked @endif>
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
                                @if (!empty( $empresa->ruta_logo ))
                                    <div class="image_wrapper col offset-md-5" id="img1">
                                        <img src="{{ asset('') }}{{ $empresa->ruta_logo }}">
                                        <a href="{{ route('borrar_logo', $empresa->id) }}"><i class="fas fa-trash-alt" style="color: red;"></i></a>
                                    </div>
                                @endif
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
                            console.log(response[i]);
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

        function confirma_salida(){
            swal({
                title: 'Confirmación',
                text: 'Seguro de Salir, si ha realizado cambios estos no seran guardados ?',
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
                        window.location.href = "{{ route('empresas') }}";
                                    } 
                    else { 
                        swal("Cancelled", "Your imaginary file is safe :)", "error"); 
                        }
            });
        }
    </script>
@endsection