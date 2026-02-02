 @extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endsection
@section('title', 'Ventas')
@section('content_header')
    <h3>Nueva Nota de Débito</h3>
@endsection
@section('content')
    <div class="row">
        <div class="col">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error}}</li>
                            <button type="button" class="close" data-dismiss="alert" arial-label="Close"><span aria-hidden="true">x</span>
                            </button>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <form class="form-horizontal" id="ndForm" name="ndForm" action="#">
        <div class="card card-navy">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-2 offset-md-10" style="text-align: right;">
                        <button type="submit" class="btn btn-xs btn-success"><i class="fas fa-save" title="Guardar"></i></button>
                        <!--<a href="{{ route('nd_listado') }}" class="btn btn-xs btn-danger" title="Regresar a lista de notas de debito"><i class="fas fa-sign-out-alt"></i></a>-->
                        <a href="#" class="btn btn-xs btn-danger" title="Regresar a lista de Admisiones" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="Busqueda">
                                    <div class="card card-light shadow">
                                        <div class="card-header text-center">Parámetros de busqueda</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="input-group input-group-sm col-md-12 mb-1">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="banco_id">Banco</label>
                                                    </div>
                                                    <select class="custom-select" id="banco_id" name="banco_id">
                                                        <option selected>Seleccionar...</option>
                                                        @foreach($bancos as $b)
                                                            <option value="{{ $b->id }}">{{ $b->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-group input-group-sm col-md-6 mb-1">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="cuenta_no">Cuenta</label>
                                                    </div>
                                                    <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="cuenta_no" name="cuenta_no">
                                                </div>
                                                <div class="input-group input-group-sm col-md-6 mb-1">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="documento_no">Cheque</label>
                                                    </div>
                                                    <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="documento_no" name="documento_no">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mb-1">
                                                    <a href="#" class="btn btn-xs btn-block btn-secondary" onclick="fn_traeReciboId(); return false;"><i class="fas fa-search"></i>&nbsp; Buscar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card card-light shadow">
                                        <div class="card-header text-center">Recibo</div>
                                        <div class="card-body">
                                            <input type="hidden" id="recibo_id" name="recibo_id">
                                            <div class="row">
                                                <div class="input-group input-group-sm col-md-6 mb-1">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="recibo_serie">Serie</label>
                                                    </div>
                                                    <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="recibo_serie" name="recibo_serie" disabled>
                                                </div>
                                                <div class="input-group input-group-sm col-md-6 mb-1">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="recibo_correlativo">Correlativo</label>
                                                    </div>
                                                    <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="recibo_correlativo" name="recibo_correlativo" disabled>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-group input-group-sm col-md-6 mb-1">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="recibo_fecha">Fecha Emisión</label>
                                                    </div>
                                                    <input type="date" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="recibo_fecha" name="recibo_fecha" disabled>
                                                </div>
                                                <div class="input-group input-group-sm col-md-6 mb-1">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="recibo_corte">Numéro de Corte</label>
                                                    </div>
                                                    <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" id="recibo_corte" name="recibo_corte" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card card-light shadow">
                                        <div class="card-header text-center">Documentos Afectos</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-sm table-striped" id="tblAfectos">
                                                        <thead>
                                                            <tr>
                                                                <th>Admisión</th>
                                                                <th>Documento</th>
                                                                <th>Correlativo</th>
                                                                <th>Fecha</th>
                                                                <th>Monto</th>
                                                                <th>N.I.T.</th>
                                                                <th>Nombre</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="documento_cobro">
                                    <input type="hidden" id="paciente_id" name="paciente_id">
                                    <input type="hidden" id="caja_id" name="caja_id" value="{{ $caja->id }}">
                                    <input type="hidden" id="caja_editar_documento" name="caja_editar_documento" value="{{ $caja->editar_documento }}">
                                    <input type="hidden" id="resolucion_id" name="resolucion_id">
                                    <div class="card border-dark shadow mb-3">
                                        <div class="card-header bg-light">Documento</div>
                                        <div class="card-body text-info">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Documento</label>
                                                        </div>
                                                        <select class="custom-select custom-select-sm select2 select2bs4" id="tipo_documento_id" name="tipo_documento_id" required onchange="fn_resolucion_x_serie(); return false;">
                                                            <!--<option value="">Seleccionar...</option>-->
                                                            <option value="{{ $documento->id }}">{{ $documento->descripcion }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 offset-md-1">
                                                    <div class="form-group form-control-sm clearfix">
                                                        <label>Condición de pago</label>&nbsp;&nbsp;
                                                        <div class="icheck-primary d-inline">
                                                            <input type="radio" class="boton" id="contado" name="condicion" value="0" tabindex="4">
                                                            <label for="contado">Contado</label>
                                                        </div>
                                                        &nbsp;&nbsp;&nbsp;
                                                        <div class="icheck-primary d-inline">
                                                            <input type="radio" class="boton" id="credito" name="condicion" value="1"  checked tabindex="5">
                                                            <label for="credito">Credito</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Fecha</label>
                                                        </div>
                                                        <input type="date" class="form-control form-control-sm text-center card-text" id="fecha_emision" name="fecha_emision" value="{{ $hoy }}" tabindex="1">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Serie</label>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-center card-text" id="serie" name="serie" required style="text-transform: uppercase;" onchange="fn_resolucion_x_serie(); return false;">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Correlativo</label>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-center" id="correlativo" name="correlativo" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div id="datos_facturacion">
                                    <div class="card border-dark shadow mb-3">
                                        <div class="card-header bg-light">Datos Receptor</div>
                                        <div class="card-body text-info">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">N.I.T.</label>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-center" id="nit" name="nit" style="text-transform: uppercase;" required tabindex="9">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Nombre</label>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-center" id="nombre" name="nombre" required tabindex="10">
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Dirección</label>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-center" id="direccion" name="direccion" required tabindex="11">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Correo Electrónico</label>
                                                        </div>
                                                        <input type="email" class="form-control form-control-sm text-center" id="email" name="email" required tabindex="11">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-info shadow mb-1">
                            <div class="card-header text-center">
                                <div class="row">
                                    <div class="col-md-9 offset-md-1">
                                        <h3>Detalle</h3>
                                    </div>
                                    <div class="col-md-2" style="text-align: right;">
                                        <a href="#" style="color:black;" class="btn btn-xs btn-warning" title="Agregar Cargo" onclick="fn_cargos(); return false;"><i class="fas fa-plus-circle"></i>&nbsp; Agregar cargo</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-sm table-striped" id="tblDetalle">
                                                <thead class="thead-light">
                                                    <tr class="text-center">
                                                        <th>Cantidad</th>
                                                        <th>Descripción</th>
                                                        <th>U. Medida</th>
                                                        <th>Precio Unitario</th>
                                                        <th>Precio Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr></tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr></tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Cargos Modal -->
    <div class="modal fade" id="cargoModal" tabindex="-1" role="dialog" aria-labelledby="cargoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="cargoForm" name="cargoForm" action="#">
                    <div class="card card-navy">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-9">
                                    Agregar Producto
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <button type="submit" class="btn btn-xs btn-success" title="Grabar"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-xs btn-danger" title="Salir" onclick="cerrar_modal('cargoModal'); return false;"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-1 col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="producto_id">Producto</label>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="producto_id"  name="producto_id" onchange="fn_trae_descripcion(); return false;" required>
                                            <option value="">Seleccionar...</option>
                                            @foreach($productos as $p)
                                                <option value="{{ $p->id}}">{{ $p->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Descripción</label>
                                    </div>
                                    <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group input-group-sm col-md-10 offset-md-1 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="unidad_medida_id">U. Medida</label>
                                    </div>
                                    <select class="custom-select custom-select-sm select2bs4" id="unidad_medida_id" name="unidad_medida_id" required>
                                        <option value="" selected="selected">Seleccionar.....</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-5 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Cantidad</label>
                                    </div>
                                    <input type="number" class="form-control" id="cantidad" name="cantidad" onchange="fn_precio_total(); return false;" style="text-align: right;" value="1" step="any" min="1" required>
                                </div>
                                <div class="input-group mb-1 input-group-sm col-md-5">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Prc. Unitario</label>
                                    </div>
                                    <input type="number" class="form-control" id="precio_unitario" name="precio_unitario" onchange="fn_precio_total(); return false;" step="any" min="0.01" style="text-align: right;" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Precio Total</label>
                                    </div>
                                    <input type="number" class="form-control" id="precio_total" name="precio_total" step="any" style="text-align: right;" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Cargos modal -->
@endsection
@section('js')
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
        var recibo_id = 0;
        var nlinea    = 0;

        //====================================================================================
        // Agregar formato de moneda a campos en tabla
        //====================================================================================
        const formatter = new Intl.NumberFormat('es-GT', {
          style: 'currency',
          currency: 'GTQ',
          minimumFractionDigits: 2
        });

        //====================================================================================
        // Al cargar la pagina
        //====================================================================================
        window.addEventListener('load', function(){
            var caja_id               = document.getElementById('caja_id').value;
            var caja_editar_documento = document.getElementById('caja_editar_documento').value;
            var tipo_documento_id     = document.getElementById('tipo_documento_id').value;
            localStorage.clear();

            fn_resolucion_x_serie();

            //================================================================================
            //Verifica si el usuario puede modificar el numero de serie y correlativo de factura
            //================================================================================
            $("#fecha_emision").prop('disabled', true);
            if (caja_editar_documento == 'N') {
                $("#serie").prop('disabled', true);
                $("#correlativo").prop('disabled', true);
                document.getElementById('nit').focus();
            }else {
                $("#serie").prop('disabled', false);
                $("#correlativo").prop('disabled', false);
                document.getElementById('serie').focus();
            }
        });

        function fn_traeReciboId(){
            var banco_id     = document.getElementById('banco_id').value;
            var cuenta_no    = document.getElementById('cuenta_no').value;
            var documento_no = document.getElementById('documento_no').value;
            var recibo_id    = 0;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('trae_recibo_x_cheque')}}",
                method: "POST",
                data: { banco_id      : banco_id,
                        cuenta_no     : cuenta_no,
                        documento_no  : documento_no
                      },
                success: function(response){
                    if (response == 0) {
                        swal({
                            title: 'Error !!!',
                            text: 'Cheque no localizado en nuestros registros, Favor verifique',
                            type: 'error',
                        });
                    }else{
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{route('trae_generales_x_recibo_id')}}",
                            method: "POST",
                            data: { recibo_id : response },
                            success: function(response){
                                document.getElementById('recibo_id').value = response['id'];
                                document.getElementById('recibo_serie').value = response['serie'];
                                document.getElementById('recibo_correlativo').value = response['correlativo'];
                                document.getElementById('recibo_fecha').value = response['fecha_emision'];
                                document.getElementById('recibo_corte').value = response['corte'];
                            },
                            error: function(error){
                                console.log(error);
                            } 
                        });

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{route('trae_documentos_afectos')}}",
                            method: "POST",
                            data: { recibo_id : response },
                            success: function(response){
                                //console.log(response.length);
                                var html = '';
                                for (var i = 0; i < response.length; i++) {
                                    //console.log(response[i]['descripcion']);
                                    document.getElementById('paciente_id').value = response[i]['paciente_id'];
                                    document.getElementById('nit').value = response[i]['nit'];
                                    document.getElementById('nombre').value = response[i]['nombre'];
                                    document.getElementById('direccion').value = response[i]['direccion'];
                                    document.getElementById('email').value = response[i]['email'];
                                    html += '<tr>'
                                    html += '<td>'
                                    html += response[i]['admision'];
                                    html += '</td>'
                                    html += '<td>'
                                    html += response[i]['descripcion'];
                                    html += '</td>'
                                    html += '<td>'
                                    html += response[i]['serie']+'-'+response[i]['correlativo'];
                                    html += '</td>'
                                    html += '<td>'
                                    html += response[i]['fecha_emision'];
                                    html += '</td>'
                                    html += '<td>'
                                    html += response[i]['total'];
                                    html += '</td>'
                                    html += '<td>'
                                    html += response[i]['nit'];
                                    html += '</td>'
                                    html += '<td>'
                                    html += response[i]['nombre'];
                                    html += '</td>'
                                    html += '</tr>'
                                }
                                $("#tblAfectos tbody tr").remove();
                                $('#tblAfectos tbody').append(html);
                            },
                            error: function(error){
                                console.log(error);
                            } 
                        });
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        //====================================================================================
        // resolucion por tipo de documento
        //====================================================================================
        function fn_resolucion_x_serie(){
            var caja_id                    = document.getElementById('caja_id').value;
            var caja_editar_documento      = document.getElementById('caja_editar_documento').value;
            var tipo_documento_id          = document.getElementById('tipo_documento_id').value;
            var serie                      = document.getElementById('serie').value;
            
            if (caja_editar_documento == 'N') {
                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('resolucion_factura_x_caja')}}",
                    method: "POST",
                    data: { caja_id  : caja_id,
                            tipo_documento_id : tipo_documento_id},
                    success: function(response){
                        var info = response;
                        if (info.resolucion_existe == 'S') {
                            document.getElementById('resolucion_id').value = info.resolucion_id;
                            document.getElementById('serie').value = info.serie;
                            document.getElementById('correlativo').value = info.correlativo;
                        }else{
                            document.getElementById('resolucion_id').value = '';
                            document.getElementById('serie').value = '';
                            document.getElementById('correlativo').value = '';
                            swal({
                                title: 'Error !!!!',
                                text: 'Caja no cuenta con una resolucion activa para el tipo de documento',
                                type: 'error'
                                }
                            );
                        }
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }else{
                if (serie.length > 0) {
                    $.ajax({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{route('resolucion_factura_x_caja')}}",
                        method: "POST",
                        data: { caja_id  : caja_id,
                                tipo_documento_id : tipo_documento_id},
                        success: function(response){
                            var info = response;
                            document.getElementById('resolucion_id').value = info.resolucion_id;
                            document.getElementById('serie').value = info.serie;
                            document.getElementById('correlativo').value = info.correlativo;
                        },
                        error: function(error){
                            console.log(error);
                        }
                    });
                }
            }
        }

        //========================================================================
        // levantar modal cargos
        //========================================================================
        function fn_cargos(){
            document.getElementById('producto_id').value      = '';
            $('#producto_id').change();
            document.getElementById('descripcion').value      = '';
            document.getElementById('unidad_medida_id').value = '';
            $('#unidad_medida_id').change();
            document.getElementById('cantidad').value         = '';
            document.getElementById('precio_unitario').value  = 0;
            document.getElementById('precio_total').value     = 0;
            $("#cargoModal").modal('show');
        }

        //========================================================================
        // Trae descripcion a mostrar en factura
        //========================================================================
        function fn_trae_descripcion(){
            var producto = document.getElementById("producto_id");
            var producto_id = producto.options[producto.selectedIndex].value;
            var producto_descripcion = producto.options[producto.selectedIndex].text;
            if (producto_id != '') {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('descripcion')}}",
                    method: "POST",
                    data: { cod  : producto_id},
                    success: function(response){
                        var info = response;
                        document.getElementById('descripcion').value = info;
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
                $.ajax({
                    url: "{{ route('trae_medidas_x_producto') }}",
                    type: "POST",
                    async: true,
                    data: {"_token": "{{ csrf_token() }}", producto_id: producto_id},
                    success: function(response){
                        if (response.length == 0) {
                            let dropdown = document.getElementById('unidad_medida_id');
                            dropdown.length = 0;
                            let option;
                            option = document.createElement('option');
                            option.text = 'Unidad';
                            option.value = 1;
                            dropdown.add(option);
                        }else{
                            let dropdown = document.getElementById('unidad_medida_id');
                            dropdown.length = 0;
                            let option;
                            option = document.createElement('option');
                            option.text = 'Seleccionar ....';
                            option.value = '';
                            for (let i = 0; i < response.length; i++) {
                                option = document.createElement('option');
                                option.text = response[i].unidad_medida_descripcion;
                                option.value = response[i].unidad_medida_id;
                                dropdown.add(option);
                            }
                        }
                    },
                    error: function(error){
                        console.log(error);
                    } 
                });
            }
        }

        //========================================================================
        // calcula total de producto 
        //========================================================================
        function fn_precio_total(){
            var cantidad = document.getElementById('cantidad').value;
            var precio_unitario = document.getElementById('precio_unitario').value;
            if (cantidad == '' || precio_unitario == '') {
                document.getElementById('precio_total').value = 0;
            }else{
                document.getElementById('precio_total').value = cantidad * precio_unitario;
            }
        }

        //========================================================================
        // Submmit de cargo forma
        //========================================================================
        $(function(){
            $("#cargoForm").submit(function(){
                fn_grabar_local();
                return false;
            })
        });

        //========================================================================
        // grabar de forma local el nuevo cargo
        //========================================================================
        function fn_grabar_local(){
            var producto             = document.getElementById('producto_id');
            var producto_id          = producto.options[producto.selectedIndex].value;
            var producto_descripcion = document.getElementById('descripcion').value;//producto.options[producto.selectedIndex].text;
            var unidad_medida        = document.getElementById('unidad_medida_id');
            var unidad_medida_id     = unidad_medida.options[unidad_medida.selectedIndex].value;
            var unidad_medida_descripcion = unidad_medida.options[unidad_medida.selectedIndex].text;
            var descripcion = document.getElementById('descripcion').value;
            var cantidad    = document.getElementById('cantidad').value;
            var precio_unitario = document.getElementById('precio_unitario').value;
            var precio_total = document.getElementById('precio_total').value;
            var linea = {
                id                    : nlinea,
                admision_id           : 0,
                detalle_movimiento_id : 0,
                producto_id           : producto_id,
                producto_descripcion  : producto_descripcion,
                unidad_medida_id      : unidad_medida_id,
                unidad_medida_descripcion : unidad_medida_descripcion,
                descripcion           : descripcion,
                cantidad              : cantidad,
                precio_unitario       : precio_unitario,
                precio_total          : precio_total
            };

            if(!localStorage.local_db){
                localStorage.local_db = JSON.stringify([linea]);
            }
            else{
                local_db = JSON.parse(localStorage.local_db);
                local_db.push(linea);
                localStorage.local_db = JSON.stringify(local_db);
            }
            actualizarTablaDetalle();
            $('#cargoModal').modal('hide');

            nlinea += 1;
        }

        //========================================================================
        // actualizar tabla detalle
        //========================================================================
        function actualizarTablaDetalle(){
            var local_db = JSON.parse(localStorage.local_db);
            var html = '';
            var html1 = '';
            total_detalle = 0;
            var error = false;
            
            for(var i = 0; i < local_db.length; i++){
                total_detalle += parseFloat(local_db[i]['precio_total']);
                html += '<tr>'
                html += '<td style="text-align: center;">'
                html += local_db[i]['cantidad']
                html += '</td>'
                html += '<td style="text-align: center;">'
                html += local_db[i]['producto_descripcion']
                html += '</td>'
                html += '<td style="text-align: center;">'
                html += local_db[i]['unidad_medida_descripcion']
                html += '</td>'
                html += '<td style="text-align:right;">'
                html += formatter.format(local_db[i]['precio_unitario']);
                html += '</td>'
                html += '<td style="text-align:right;">'
                html += formatter.format(local_db[i]['precio_total']);
                html += '</td>'
                html += '<td style="text-align: right;">'
                        html += '<button class="btn btn-xs btn-danger" onclick="eliminar_registro('+i+');" title="Eliminar Registro"><i class="fa fa-trash-alt"></i></button>'
                        html += '</td>'
                html += '</tr>'
            }
            html1 += '<tr>'
            html1 += '<td></td>'
            html1 += '<td></td>'
            html1 += '<td></td>'
            html1 += '<td><strong>Total Documento</strong></td>'
            html1 += '<td style="text-align:right;">'
            html1 += '<h5>'+formatter.format(total_detalle)+'</h5>'
            html1 += '</td>'
            html1 += '</tr>'

            $("#tblDetalle tfoot tr").remove();
            $("#tblDetalle tbody tr").remove();
            $('#tblDetalle tbody').append(html);
            $('#tblDetalle tfoot').append(html1);
        }

        //========================================================================
        // Eliminar registro detalle de cargos
        //========================================================================
        function eliminar_registro(id){
            total = 0;
            var local_db = JSON.parse(localStorage.local_db);
            for(var i = 0; i < local_db.length; i++){
                if (id == local_db[i]['id']) {
                    local_db.splice(i, 1);
                    localStorage.local_db = JSON.stringify(local_db);
                }
            }
            actualizarTablaDetalle();
        }

        //========================================================================
        // al grabar factura llama a procedimiento ajax para grabar en BD
        //========================================================================
        $(function(){
            $("#ndForm").submit(function(){
                fn_grabar_nd();
                return false;
            })
        });

        function fn_grabar_nd(){
            let resolucion_id = document.getElementById('resolucion_id').value;
            //let admision_id   = document.getElementById('admision_id').value;
            let paciente_id   = document.getElementById('paciente_id').value;
            let tipo_documento_id = document.getElementById('tipo_documento_id').value;
            let recibo_id     = document.getElementById('recibo_id').value;
            let fecha_emision = document.getElementById('fecha_emision').value;
            let serie         = document.getElementById('serie').value;
            let correlativo   = document.getElementById('correlativo').value;
            let condicion     = $('input:radio[name=condicion]:checked').val();
            let nit           = document.getElementById('nit').value;
            let nombre        = document.getElementById('nombre').value;
            let direccion     = document.getElementById('direccion').value;
            let local_db = JSON.parse(localStorage.local_db); 
            let error    = 0;

            if (error == 0) {
                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{route('grabar_nd')}}",
                    method: "POST",
                    data: { resolucion_id     : resolucion_id,
                            recibo_id         : recibo_id,
                            //admision_id       : admision_id,
                            paciente_id       : paciente_id,
                            tipo_documento_id : tipo_documento_id,
                            fecha_emision     : fecha_emision,
                            serie             : serie,
                            correlativo       : correlativo,
                            condicion         : condicion,
                            nit               : nit,
                            nombre            : nombre,
                            direccion         : direccion,
                            local_db          : JSON.stringify(local_db),
                            //pago_db           : JSON.stringify(pago_db)
                        },
                    success: function(response){
                        console.log(response.respuesta);
                        if (response.parametro == 0) {
                            swal({
                                title: 'Confirmación',
                                text: response.respuesta,
                                type: 'success'
                                },
                                function(isConfirm) {
                                    if (isConfirm) { 
                                        swal.close();
                                    }
                                }
                            );
                        }else{
                            swal({
                                title: 'Error !!!!',
                                text: response.respuesta,
                                type: 'error'
                                }
                            );
                        }
                    },
                    error: function(error){
                        console.log(error);
                    }
                });    
            }else{
                swal({
                    title: 'Error !!!!',
                    text: 'Para continuar el valor del documento debe ser igual al monto pagado',
                    type: 'error'
                    }
                );
            }
        }

        function cerrar_modal(modal){
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
                        $('#'+modal).modal('hide');
                        swal.close();
                    }
                }
            );
        }

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
                        window.location.href = "{{ route('nd_listado') }}";
                    }
                }
            );
        }
    </script>
@endsection