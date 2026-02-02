@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
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
        .dataTables_wrapper .row {
            display: flex;
            align-items: center; /* Alinea verticalmente los elementos */
            justify-content: flex-start; /* Ajusta los elementos a la izquierda */
        }

        .dataTables_wrapper .row .col-auto {
            display: flex;
            justify-content: flex-start; /* Alinea los elementos dentro de las columnas */
        }

        .dataTables_wrapper .row .col {
            display: flex;
            justify-content: flex-start;
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
@section('title', 'Proveedores')

@section('content_header')
    <br>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <form class="form-horizontal" id="proveedorForm" name="proveedorForm" action="#">
                @csrf
                <div class="card">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="row">
                            <div class="col-md-9">
                                <h6>Edición de Proveedor</h6>
                            </div>
                            <div class="col-md-3" style="text-align: right;">
                                <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Agregar Registro"><i class="fas fa-save"></i></button>
                                <a href="{{ route('proveedores') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <form class="form-horizontal">
                        <div class="card-body">
                            <input type="hidden" id="proveedor_id" name="proveedor_id" value="{{ $proveedor->id }}">
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-5 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Razón Social</label>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="razon_social" name="razon_social" required value="{{ $proveedor->razon_social }}" autofocus>
                                </div>
                                <div class="input-group input-group-sm mb-1 col-md-5">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Nombre Comercial</label>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="nombre_comercial" name="nombre_comercial" required value="{{ $proveedor->nombre_comercial }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Dirección</label>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="direccion" name="direccion" value="{{ $proveedor->direccion }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group input-group-sm mb-1 col-md-5 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Teléfonos</label>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="telefonos" name="telefonos" value="{{ $proveedor->telefonos }}">
                                </div>
                                <div class="input-group input-group-sm mb-1 col-md-5">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Correo Electrónico</label>
                                    </div>
                                    <input type="email" class="form-control form-control-sm" id="email" name="email" value="{{ $proveedor->email }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group mb-1 form-control-sm clearfix col-md-5 offset-md-1">
                                    <label for="masculino">Condición de pago</label>
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="contado" name="condicion" value="0" @if( $proveedor->condicion == 0) checked @endif>
                                        <label for="contado">Contado</label>
                                    </div>
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="credito" name="condicion" value="1" @if( $proveedor->condicion == 1) checked @endif>
                                        <label for="credito">Crédito</label>
                                    </div>
                                </div>

                                <div class="input-group input-group-sm mb-1 col-md-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Dias Crédito</label>
                                    </div>
                                    <input type="number" class="form-control form-control-sm" id="dias_credito" name="dias_credito" required value="{{ $proveedor->dias_credito }}" placeholder="0" min="1" readonly style="text-align: right;">
                                </div>

                                <div class="form-group mb-1 offset-md-1">
                                    <div class="custom-control form-control-sm custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="1" @if($proveedor->estado == 1) checked @endif>
                                        <label class="custom-control-label" for="estado">Activar</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-pills nav-justified flex-column flex-sm-row">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#contactos" data-toggle="tab">Contactos</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#productos" data-toggle="tab">Productos</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="contactos">
                                            <div class="row">
                                                <div class="col-md-1 offset-md-11" style="text-align: right;">
                                                    <a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Agregar Contacto" onclick="nuevoContacto(); return false;">
                                                        <i class="fas fa-plus-circle"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <br>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-10 offset-md-1">
                                                    <table class="table table-sm table-striped" id="tblcontactos">
                                                        <thead>
                                                            <tr style="text-align: center; font-size: 12px;">
                                                                <th>Linea</th>
                                                                <th>Contacto</th>
                                                                <th>Teléfonos</th>
                                                                <th>Correo</th>
                                                                <th>Estado</th>
                                                                <th>&nbsp;</th>
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
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
        var nLinea = 0;
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
                        let checkboxes = document.querySelectorAll('table input[type="checkbox"]');
                        if (response[i]['estado'] == 1) {
                            checkboxes[i].checked = true;
                        }
                        // if (response[i]['estado'] == 1) {
                        //     $('#contactos['+i+'][contacto_estado').prop('checked', true);
                        // }
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
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1 text-center">'
            html += ' <input style="width:30px;" type="checkbox" id="contactos['+nLinea+'][contacto_estado]" name="contactos['+nLinea+'][contacto_estado]" class="icheck-primary" aria-label="Checkbox for following text input">';
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

        function eliminar()
        {
            var whichtr = $(this).closest("tr");
            whichtr.remove(); 
            return false;
        }

        $(function(){
            $("#proveedorForm").submit(function(){
                event.preventDefault(); // Evita el envío normal del formulario
                $('#submitButton').prop('disabled', true);
                var formData = new FormData(this); // Serializa los datos del formulario
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                formData.append('_token', csrfToken);

                $.ajax({
                    url: "{{route('actualizar_proveedor')}}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    contentType: false,  // Impide que jQuery configure el tipo de contenido
                    processData: false,
                    success: function(response){
                        swal({
                            title: 'Buen Trabajo',
                            text: response.message,
                            type: 'success',
                            },
                            function(){
                                return window.location.href = "{{route('proveedores')}}";
                            }
                        );
                    },
                    error: function(error){
                        console.log(error);
                    }   
                });
            });
        });
    </script>
@endsection