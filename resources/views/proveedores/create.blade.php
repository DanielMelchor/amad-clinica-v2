@extends('adminlte::page')
@section('css')
    <style type="text/css">
        .numero{
            text-align: right;
        }
        .moneda:after {
            content: attr(data-numero);
        }
        .nav-pills .nav-link.active,
        .show>.nav-pills .nav-link{
            background: #7FB3D5 !important;
            color: #000000 !important;
        }
        .nav-link {
            font-family: monospace;
            font-size: 10pt;
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
                <form role="form" id="formaNuevoRegistro" method="POST" action="{{route('grabar_proveedor')}}">
                    @csrf
                    <div class="card shadow-sm">
                        <div class="card-header" style="background-color: #E1E8ED;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Nuevo Proveedor</h6>
                                <div class="btn-group-xs">
                                    <button type="submit" id="submitButton" class="btn btn-sm btn-outline-success rounded-circle elevation-2" title="Agregar Registro">
                                        <i class="fas fa-save"></i>
                                    </button>
                                    <a href="{{ route('proveedores') }}" class="btn btn-sm btn-outline-danger rounded-circle elevation-2" title="Salir">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-2 p-md-4">
                            <div class="row">
                                <div class="col-12 col-md-2 mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">N.I.T.</label>
                                        </div>
                                        <input type="text" class="form-control" id="nit" name="nit" required value="{{ old('nit') }}" autofocus>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5 mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Razón Social</label>
                                        </div>
                                        <input type="text" class="form-control" id="razon_social" name="razon_social" required value="{{ old('razon_social') }}" autofocus>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5 mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Nombre Comercial</label>
                                        </div>
                                        <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial" required value="{{ old('nombre_comercial') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Dirección</label>
                                        </div>
                                        <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6 mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Teléfonos</label>
                                        </div>
                                        <input type="text" class="form-control" id="telefonos" name="telefonos" value="{{ old('telefonos') }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mb-2">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Correo Electrónico</label>
                                        </div>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center mb-2">
                                <div class="col-12 col-md-7 mb-2 mb-md-0">
                                    <div class="p-2 border rounded bg-light d-flex align-items-center justify-content-around">
                                        <small class="font-weight-bold">Pago:</small>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="contado" name="condicion" value="0" checked>
                                            <label for="contado">Contado</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="credito" name="condicion" value="1">
                                            <label for="credito">Crédito</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Días Crédito</label>
                                        </div>
                                        <input type="number" class="form-control text-right" id="dias_credito" name="dias_credito" required value="{{ old('dias_credito') }}" placeholder="0" min="0" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success my-2">
                                        <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A" title="Activar Proveedor">
                                        <label class="custom-control-label" for="estado">Activar</label>
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
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-circle elevation-2" onclick="nuevoContacto(); return false;">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped w-100" id="tblcontactos">
                                                    <thead class="thead-light">
                                                        <tr class="small text-center">
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
                        </div> </div>
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
        // cuando cambia la condicion de contado a credito habilita campo de dias de credito
        //========================================================================
        $(document).ready(function(){
            $("input[name=condicion]").click(function () {    
                if ($(this).val() == 0) {
                    $("#dias_credito").attr('disabled','disabled'); 
                    $('#dias_credito').removeAttr("required");
                }else{
                    $("#dias_credito").removeAttr('disabled'); 
                    $('#dias_credito').prop("required", true);
                }
            });
        });

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
            html += '<input type="text" class="form-control" id="contactos['+nLinea+'][contacto_email]" name="contactos['+nLinea+'][contacto_email]" required/>';
            html += '</div>';
            html += '</td>';
            html += '<td style="text-align: right">';
            html += '<button class="btn btn-sm btn-outline-danger rounded-circle elevation-4 eliminar" title="eliminar registro"><i class="fas fa-trash"></i></button>'
            html += '</td>';    
            html += '</tr>';
            $('#tblcontactos tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLinea += 1;
        }

        function eliminar()
        {
            var whichtr = $(this).closest("tr");
            whichtr.remove(); 
            return false;
        }

        $(document).ready(function() {
            $('#formaNuevoRegistro').on('submit', function() {
                // Deshabilitar el botón de submit cuando se envíe el formulario
                $('#submitButton').prop('disabled', true);
                // $('#submitButton').text('Enviando...');
            });
        });
    </script>
@endsection