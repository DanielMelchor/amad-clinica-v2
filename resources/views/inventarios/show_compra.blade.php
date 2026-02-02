@extends('admin.layout')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection
@section('titulo')
    Nueva Compra
@endsection
@section('contenido')
	@if (session('success'))
        <div class="col-sm-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <div class="container-fluid">
        <div class="card card-navy">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-3 offset-md-9" style="text-align: right;">
                        <a href="{{ route('lista_compras') }}" class="btn btn-sm btn-danger" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 offset-md-1">
                        <div class="card card-secondary rounded-top">
                            <div class="card-header">
                                <p>Datos de Proveedor</p>
                            </div>
                            <div class="card-body">
                                <input type="hidden" id="proveedor_id" name="proveedor_id" value="{{ $encabezado->proveedor_id }}">
                                <div class="row">
                                    <div class="input-group col-md-10 offset-md-1 mb-1">
                                        <span class="input-group-text" id="basic-addon1">N.I.T.</span>
                                        <input type="text" class="form-control" placeholder="N.I.T." aria-label="nit" aria-describedby="find_proveedor" id="nit" name="nit" value="{{ $encabezado->nit }}" style="text-transform: uppercase;" disabled>
                                        <div class="input-group-append">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="find_proveedor" data-toggle="modal" data-target="#proveedorModal" disabled><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group col-md-10 offset-md-1 mb-1">
                                        <span class="input-group-text" id="basic-addon1">Nombre</span>
                                        <input type="text" class="form-control" id="proveedor_nombre" name="proveedor_nombre" value=" {{ $encabezado->nombre_comercial }}" style="text-transform: uppercase;" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group col-md-6 offset-md-1 mb-1">
                                        <span class="input-group-text" id="basic-addon1">Dias Crédito</span>
                                        <input type="text" class="form-control" id="dias_credito" name="dias_credito" value="{{ $encabezado->dias_credito }}" style="text-align: right;" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-10 input-group mb-2">
                                <span class="input-group-text" id="basic-addon1">Documento</span>
                                <input type="text" class="form-control" id="documento_id" name="documento_id" value="{{ $encabezado->tipo_documento_descripcion }}" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="input-group mb-2">
                                    <span class="input-group-text" id="basic-addon1">Serie</span>
                                    <input type="text" class="form-control" placeholder="Serie" aria-label="serie" aria-describedby="serie" id="serie" name="serie" style="text-transform: uppercase;" required>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group mb-2">
                                    <span class="input-group-text" id="basic-addon1">Correlativo</span>
                                    <input type="number" class="form-control" placeholder="documento" aria-label="documento" aria-describedby="documento" id="documento" name="documento" required style="text-align: right;">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="input-group mb-2">
                                    <span class="input-group-text" id="basic-addon1">Fch. Emisión</span>
                                    <input type="date" class="form-control" id="fecha_emision" name="fecha_emision" value="{{ $encabezado->fecha_emision }}" required>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group mb-2">
                                    <span class="input-group-text" id="basic-addon1">Fch. Vencimiento</span>
                                    <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" value="{{ $encabezado->fecha_vencimiento}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 input-group mb-2">
                                <span class="input-group-text" id="basic-addon1">Total</span>
                                <input type="number" class="form-control" placeholder="0.00" id="total" name="total" min="0" required style="text-align: right;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 input-group mb-2">
                                <span class="input-group-text" id="basic-addon1">Bodega</span>
                                <input type="text" class="form-control" id="bodega_id" name="bodega_id" value="{{ $encabezado->bodega_descripcion }}" disabled>
                            </div>
                            <div class="col-md-1" style="text-align: right;">
                                <a href="#" class="btn btn-sm btn-primary" onclick="agregarFila(); return false;" title="Agregar Artículo" disabled><i class="fas fa-plus-circle"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection