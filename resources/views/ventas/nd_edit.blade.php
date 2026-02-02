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
                                                        <input type="date" class="form-control form-control-sm text-center card-text" id="fecha_emision" name="fecha_emision" value="{{ $encabezado->fecha_emision }}" tabindex="1">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Serie</label>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-center card-text" id="serie" name="serie" required style="text-transform: uppercase;" value="{{ $encabezado->serie }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Correlativo</label>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-center" id="correlativo" name="correlativo" required value="{{ $encabezado->correlativo}}">
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
                                                        <input type="text" class="form-control form-control-sm text-center" id="nit" name="nit" style="text-transform: uppercase;" required tabindex="9" value="{{ $encabezado->nit }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Nombre</label>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-center" id="nombre" name="nombre" required tabindex="10" value="{{ $encabezado->nombre }}">
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Dirección</label>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-center" id="direccion" name="direccion" required tabindex="11" value="{{ $encabezado->direccion}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mb-1 input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Correo Electrónico</label>
                                                        </div>
                                                        <input type="email" class="form-control form-control-sm text-center" id="email" name="email" required tabindex="11" value="{{ $encabezado->email }}">
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
@endsection
@section('js')
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
    
@endsection