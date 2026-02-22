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
    </style>
@endsection
@section('title', 'Pacientes')
@section('content_header')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form role="form" method="POST" action="{{route('grabar_paciente')}}">
                @csrf
                <div class="card">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="row">
                            <div class="col-md-9">
                                <h6>Agregar Paciente</h6>
                            </div>
                            <div class="col-md-3" style="text-align: right;">
                                <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
                                <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="input-group input-group-sm col-md-6 mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Nombres</label>
                                        </div>
                                        <input type="text" class="form-control" id="nombres" name="nombres" autofocus required value="{{ old('nombres')}}" tabindex="10">
                                    </div>
                                    <div class="input-group mb-1 input-group-sm col-md-6">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Código</label>
                                        </div>
                                        <input type="number" class="form-control numero" id="codigo_id" name="codigo_id" style="text-align: right;" disabled tabindex="16">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group input-group-sm col-md-6 mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Apellidos</label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="apellidos" name="apellidos" required value="{{ old('apellidos')}}" tabindex="11">
                                    </div>
                                    <div class="input-group mb-1 input-group-sm col-md-6">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Expediente</label>
                                        </div>
                                        <input type="number" class="form-control form-control-sm numero" id="expediente_no" name="expediente_no" required value="{{ old('expediente_no')}}" style="text-align: right;" tabindex="17" min="0" step="1" onchange="verificar_expediente(); return false;">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group input-group-sm col-md-6 mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Apellido Casada</label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="apellido_casada" name="apellido_casada" value="{{ old('apellido_casada')}}" tabindex="12">
                                    </div>
                                    <div class="input-group mb-1 input-group-sm col-md-6">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Expediente Anterior</label>
                                        </div>
                                        <input type="number" class="form-control form-control-sm numero" id="expediente_anterior_no" name="expediente_anterior_no" value="{{ old('expediente_anterior_no')}}" style="text-align: right;" min="0", step="1" tabindex="18">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group mb-2 input-group-sm col-md-12">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Fecha Nacimiento</label>
                                        </div>
                                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required value="{{ old('fecha_nacimiento') }}" style="text-align: right;" tabindex="13">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-1">
                                        <div class="form-group clearfix">
                                            <label for="masculino">Genero&nbsp;&nbsp;&nbsp;</label>
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="masculino" name="genero" value="M" checked tabindex="14">
                                                <label for="masculino">&nbsp;Masculino&nbsp;&nbsp;&nbsp;</label>
                                            </div>
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="negativo" name="genero" value="F" tabindex="15">
                                                <label for="negativo">&nbsp;Femenino</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group input-group-sm col-md-12 mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="religion">Religion&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        </div>
                                        <select class="custom-select custom-select-sm select2bs4" id="religion" name="religion" tabindex="24">
                                            <option value="">Seleccionar...</option>
                                            <option value="C">Católico</option>
                                            <option value="E">Evangélico</option>
                                            <option value="T">Testigo de Jehova</option>
                                            <option value="M">Mormon</option>
                                            <option value="O">Otros</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group input-group-sm col-md-12 mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Profesion&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        </div>
                                        <input type="text" class="form-control" id="profesion" name="profesion" value="{{ old('profesion')}}" tabindex="29">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group input-group-sm col-md-12 mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Recomendado</label>
                                        </div>
                                        <input type="text" class="form-control" id="referido_por" name="referido_por" value="{{ old('referido_por')}}" tabindex="25">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">                            
                                    <div class="col-md-12">
                                        <ul class="nav nav-pills nav-justified">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#telefonos" data-toggle="tab">Teléfonos</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#direcciones" data-toggle="tab">Direcciones</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#emails" data-toggle="tab">Email</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#seguros" data-toggle="tab">Seguros</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#facturacion" data-toggle="tab">Facturación</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#familia" data-toggle="tab">Familia</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="telefonos">
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-2 offset-md-10" style="text-align: right;">
                                                        <button class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="fnAgregarTelefono();">
                                                            <i class="fas fa-plus-circle"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <br>
                                                <table class="table table-sm table-striped" id="tbltelefonos">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Tipo</th>
                                                            <th>Número</th>
                                                            <th>Extensión</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="direcciones">
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-2 offset-md-10" style="text-align: right;">
                                                        <button class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="fnAgregarDireccion();">
                                                            <i class="fas fa-plus-circle"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <br>
                                                <table class="table table-sm table-striped" id="tbldirecciones">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Dirección</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="emails">
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-2 offset-md-10" style="text-align: right;">
                                                        <button class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="fnAgregarEmail();">
                                                            <i class="fas fa-plus-circle"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <br>
                                                <table class="table table-sm table-striped" id="tblemails">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Correo Electrónico</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="seguros">
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-2 offset-md-10" style="text-align: right;">
                                                        <button class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="fnAgregarSeguro();">
                                                            <i class="fas fa-plus-circle"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <br>
                                                <table class="table table-sm table-striped" id="tblseguros">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Aseguradora</th>
                                                            <th>Número de Póliza</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="facturacion">
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-2 offset-md-10" style="text-align: right;">
                                                        <button class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="fnAgregarFacturacion();">
                                                            <i class="fas fa-plus-circle"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <br>
                                                <table class="table table-sm table-striped" id="tblfacturacion">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>N.I.T.</th>
                                                            <th>Nombre</th>
                                                            <th>Dirección</th>
                                                            <th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="familia">
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-2 offset-md-10" style="text-align: right;">
                                                        <button class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="fnAgregarFamilia();">
                                                            <i class="fas fa-plus-circle"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <br>
                                                <table class="table table-sm table-striped" id="tblfamilia">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Parentesco</th>
                                                            <th>Nombre</th>
                                                            <th>Telefono</th>
                                                            <th>Emergencia</th>
                                                            <th>
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
                        <div class="row">
                            <div class="form-group">
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
                                    <label class="custom-control-label" for="estado">Activar</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <div class="col-md-12">
                                <h4>Antecedentes</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-pills nav-justified">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#medico" data-toggle="tab">Medico</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#quirurgico" data-toggle="tab">Quirurgico - Traumas</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#alergia" data-toggle="tab">Alergias</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#ginecologico" data-toggle="tab">Gineco - Obstetra</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#familiar" data-toggle="tab">Familiares</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#medicamento" data-toggle="tab">Medicamentos</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#habito" data-toggle="tab">Habitos</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="medico">
                                        <br>
                                        <div class="row text-center">
                                            <div class="form-group col-md-10 offset-md-1">
                                                <label for="antmedico_descripcion">Descripción</label>
                                                <textarea class="form-control form-control-sm" id="antmedico_descripcion" name="antmedico_descripcion" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="quirurgico">
                                        <br>
                                        <div class="row text-center">
                                            <div class="form-group col-md-10 offset-md-1">
                                                <label for="antquirurgico_descripcion">Descripción</label>
                                                <textarea class="form-control form-control-sm" id="antquirurgico_descripcion" name="antquirurgico_descripcion" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="alergia">
                                        <br>
                                        <div class="row text-center">
                                            <div class="form-group col-md-10 offset-md-1">
                                                <label for="antalergia_descripcion">Descripción</label>
                                                <textarea class="form-control form-control-sm" id="antalergia_descripcion" name="antalergia_descripcion" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="ginecologico">
                                        <br>
                                        <div class="row text-center">
                                            <div class="form-group col-md-10 offset-md-1">
                                                <label for="antgineco_descripcion">Descripción</label>
                                                <textarea class="form-control form-control-sm" id="antgineco_descripcion" name="antgineco_descripcion" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="familiar">
                                        <br>
                                        <div class="row text-center">
                                            <div class="form-group col-md-10 offset-md-1">
                                                <label for="antfamiliar_descripcion">Descripción</label>
                                                <textarea class="form-control form-control-sm" id="antfamiliar_descripcion" name="antfamiliar_descripcion" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="medicamento">
                                        <br>
                                        <div class="row text-center">
                                            <div class="form-group col-md-10 offset-md-1">
                                                <label for="antmedicamento_descripcion">Descripción</label>
                                                <textarea class="form-control form-control-sm" id="antmedicamento_descripcion" name="antmedicamento_descripcion" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="habito">
                                        <br>
                                        <div class="row text-center">
                                            <div class="col-md-5 offset-md-1">
                                                <div class="card card-secondary">
                                                    <div class="card-header">Fumador</div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="input-group input-group-sm col-md-5 offset-md-1  mb-1">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Cantidad</span>
                                                                </div>
                                                                <input type="number" class="form-control" id="tabaco_cnt" name="tabaco_cnt" value="{{ old('tabaco_cnt')}}" style="text-align: right;">
                                                            </div>
                                                            <div class="input-group mb-1 input-group-sm col-md-5">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Tiempo</span>
                                                                </div>
                                                                <input type="number" class="form-control" id="tabaco_tiempo" name="tabaco_tiempo" value="{{ old('tabaco_tiempo')}}" style="text-align: right;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 offset-md-1">
                                                <div class="card card-secondary">
                                                    <div class="card-header">Bebida</div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="input-group input-group-sm col-md-5 offset-md-1 mb-1">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Cantidad</span>
                                                                </div>
                                                                <input type="number" class="form-control" id="alcohol_cnt" name="alcohol_cnt" value="{{ old('alcohol_cnt')}}" style="text-align: right;">
                                                            </div>
                                                            <div class="input-group mb-1 input-group-sm col-md-5">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Tiempo</span>
                                                                </div>
                                                                <input type="text" class="form-control" id="alcohol_tiempo" name="alcohol_tiempo" value="{{ old('alcohol_cnt')}}" style="text-align: right;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
        var nLinea   = 0;
        var nLineaT  = 0;
        var nLineaD  = 0;
        var nLineaE  = 0;
        var nLineaS  = 0;
        var nLineaF  = 0;
        var nLineaF1 = 0;

        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();
            $('.select2bs4').select2({ theme: 'bootstrap4' });
        });

        function verificar_expediente(){
            var expediente = document.getElementById('expediente_no').value;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('verificar_expediente') }}",
                method: "POST",
                data: {expediente: expediente
                      },
                success: function(response){
                    if (response != 0) {
                        swal({
                              title: "Precaución",   
                              text: "Numero de Expediente ya existe, Favor verifique",   
                              type: "warning" 
                        }, function(){
                            document.getElementById('expediente_no').value = '';
                        });
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });

        }

        function fnAgregarRegistro(){
            var html   = '';
            html += '<tr>';
            html += '<td>';
            html += '<select id="seguros['+nLinea+'][aseguradora_id]" name="seguros['+nLinea+'][aseguradora_id]" class="form-control mi_articulo" data-required="true">';
            html += '<option value="">Seleccionar...</option>';
            @foreach($aseguradoras as $aseguradora)
                html += '<option value="{{ $aseguradora->id }}">{{ $aseguradora->nombre }}</option>';
            @endforeach
            html += '</select>'
            html += '</td>';
            html += '<td>';
            html += '<input type="text" class="form-control ccantidad" id="seguros['+nLinea+'][poliza]" name="seguros['+nLinea+'][poliza]"/>';
            html += '</td>';
            html += '<td style="text-align: right">';
            html += '<button class="btn btn-xs btn-outline-danger rounded-circle elevation-4 eliminar" title="eliminar registro"><i class="fas fa-minus-circle"></i></button>'
            html += '</td>';    
            html += '</tr>';
            $('#tblseguros tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLinea += 1;
        }

        function fnAgregarTelefono(){
            event.preventDefault();
            var html = '';
            html += '<tr>';
            html += '<td>';
            html += '<select class="custom-select custom-select-sm select2bs4" id="telefonos['+nLineaT+'][tipocomunicacion_id]" name="telefonos['+nLineaT+'][tipocomunicacion_id]">';
            html += '<option value="">Seleccionar...</option>';
            @foreach($tipoTelefonos as $tipoTelefono)
                html += '<option value="{{ $tipoTelefono->id }}">{{ $tipoTelefono->nombre }}</option>';
            @endforeach
            html += '</select>'
            html += '</td>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="number" step="1" min="0" class="form-control numero" id="telefonos['+nLineaT+'][numero]" name="telefonos['+nLineaT+'][numero]"/>';
            html += '</div>';
            html += '</td>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="number" step="1" min="0" class="form-control numero" id="telefonos['+nLineaT+'][extension]" name="telefonos['+nLineaT+'][extension]"/>';
            html += '</div>';
            html += '</td>';
            html += '<td style="text-align: right">';
            html += '<button class="btn btn-xs btn-outline-danger rounded-circle elevation-4 eliminar" title="eliminar registro"><i class="fas fa-minus-circle"></i></button>'
            html += '</td>';    
            html += '</tr>';
            $('#tbltelefonos tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLineaT += 1;
        }

        function fnAgregarDireccion(){
            event.preventDefault();
            var html = '';
            html += '<tr>';
            html += '<td>';
            html += '<select id="direcciones['+nLineaD+'][tipodireccion_id]" name="direcciones['+nLineaD+'][tipodireccion_id]" class="custom-select custom-select-sm select2bs4" data-required="true">';
            html += '<option value="">Seleccionar...</option>';
            @foreach($tipoDirecciones as $tipoDireccion)
                html += '<option value="{{ $tipoDireccion->id }}">{{ $tipoDireccion->nombre }}</option>';
            @endforeach
            html += '</select>'
            html += '</td>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="text" class="form-control" id="direcciones['+nLineaD+'][direccion]" name="direcciones['+nLineaD+'][direccion]"/>';
            html += '</div>';
            html += '</td>';
            html += '<td style="text-align: right">';
            html += '<button class="btn btn-xs btn-outline-danger rounded-circle elevation-4 eliminar" title="eliminar registro"><i class="fas fa-minus-circle"></i></button>'
            html += '</td>';    
            html += '</tr>';
            $('#tbldirecciones tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLineaD += 1;
        }

        function fnAgregarEmail(){
            event.preventDefault();
            var html = '';
            html += '<tr>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="email" class="form-control" id="emails['+nLineaD+'][email]" name="emails['+nLineaT+'][email]" required/>';
            html += '</div>';
            html += '</td>';
            html += '<td style="text-align: right">';
            html += '<button class="btn btn-xs btn-outline-danger rounded-circle elevation-4 eliminar" title="eliminar registro"><i class="fas fa-minus-circle"></i></button>'
            html += '</td>';    
            html += '</tr>';
            $('#tblemails tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLineaT += 1;
        }

        function fnAgregarSeguro(){
            event.preventDefault();
            var html = '';
            html += '<tr>';
            html += '<td>';
            html += '<select id="seguros['+nLineaS+'][aseguradora_id]" name="seguros['+nLineaS+'][aseguradora_id]" class="custom-select custom-select-sm select2bs4" data-required="true">';
            html += '<option value="">Seleccionar...</option>';
            @foreach($aseguradoras as $aseguradora)
                html += '<option value="{{ $aseguradora->id }}">{{ $aseguradora->nombre }}</option>';
            @endforeach
            html += '</select>'
            html += '</td>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="text" class="form-control" id="seguros['+nLineaS+'][poliza]" name="seguros['+nLineaS+'][poliza]" required/>';
            html += '</div>';
            html += '</td>';
            html += '<td style="text-align: right">';
            html += '<button class="btn btn-xs btn-outline-danger rounded-circle elevation-4 eliminar" title="eliminar registro"><i class="fas fa-minus-circle"></i></button>'
            html += '</td>';    
            html += '</tr>';
            $('#tblseguros tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLineaS += 1;
        }

        function fnAgregarFacturacion(){
            event.preventDefault();
            var html = '';
            html += '<tr>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="text" class="form-control" id="facturacion['+nLineaF+'][nit]" name="facturacion['+nLineaF+'][nit]" required/>';
            html += '</div>';
            html += '</td>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="text" class="form-control" id="facturacion['+nLineaF+'][nombre]" name="facturacion['+nLineaF+'][nombre]" required/>';
            html += '</div>';
            html += '</td>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="text" class="form-control" id="facturacion['+nLineaF+'][direccion]" name="facturacion['+nLineaF+'][direccion]" required/>';
            html += '</div>';
            html += '</td>';
            html += '<td style="text-align: right">';
            html += '<button class="btn btn-xs btn-outline-danger rounded-circle elevation-4 eliminar" title="eliminar registro"><i class="fas fa-minus-circle"></i></button>'
            html += '</td>';    
            html += '</tr>';
            $('#tblfacturacion tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLineaF += 1;
        }

        function fnAgregarFamilia(){
            event.preventDefault();
            var html = '';
            html += '<tr>';
            html += '<td>';
            html += '<select id="familia['+nLineaF1+'][parentesco_id]" name="familia['+nLineaF1+'][parentesco_id]" class="custom-select custom-select-sm select2bs4" data-required="true">';
            html += '<option value="">Seleccionar...</option>';
            @foreach($parentescos as $parentesco)
                html += '<option value="{{ $parentesco->id }}">{{ $parentesco->nombre }}</option>';
            @endforeach
            html += '</select>'
            html += '</td>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="text" class="form-control" id="familia['+nLineaF1+'][nombre]" name="familia['+nLineaF1+'][nombre]" required/>';
            html += '</div>';
            html += '</td>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="text" class="form-control" id="familia['+nLineaF1+'][telefono]" name="familia['+nLineaF1+'][telefono]" required/>';
            html += '</div>';
            html += '</td>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1 text-center">'
            html += ' <input style="width:30px;" type="checkbox" id="familia['+nLineaF1+'][emergencia]" name="familia['+nLineaF1+'][emergencia]" class="icheck-primary" aria-label="Checkbox for following text input">';
            html += '</div>';
            html += '</td>';
            html += '<td style="text-align: right">';
            html += '<button class="btn btn-xs btn-outline-danger rounded-circle elevation-4 eliminar" title="eliminar registro"><i class="fas fa-minus-circle"></i></button>'
            html += '</td>';    
            html += '</tr>';
            $('#tblfamilia tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLineaF1 += 1;
        }

        function eliminar()
        {
            var whichtr = $(this).closest("tr");
            whichtr.remove(); 
            total_tabla();
            return false;
        }

        function confirma_salida(){
            Swal.fire({
                title: 'Confirmación',
                text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745', // Color success de AdminLTE
                cancelButtonColor: '#dc3545',  // Color danger de AdminLTE
                confirmButtonText: 'Si Salir',
                cancelButtonText: 'No',
                allowEscapeKey: true,
                reverseButtons: true // Opcional: pone el botón de confirmar a la derecha
            }).then((result) => {
                if (result.isConfirmed) { 
                    window.location.href = "{{ route('pacientes') }}";
                } 
            });
        }
    </script>
@endsection