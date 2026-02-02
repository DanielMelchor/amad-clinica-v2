@extends('adminlte::page')
@section('css')
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
@endsection
@section('title', 'Protocolos')

@section('content_header')
  <h3>Ciclos</h3>
@endsection

@section('content')
	<div class="row">
        <div class="col">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" arial-label="Close"><span aria-hidden="true">x</span>
	    			</button>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <div class="content-fluid">
        <div class="row">
        @foreach($protocolos as $p)
                <div class="col-md-4">
                    @if($p->estado == 'A')
                        <div class="card card-info">
                    @else
                        <div class="card card-secondary">
                    @endif
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-10">
                                    <h5>Ciclo No. {{ $p->ciclo }}</h5>
                                </div>
                                <div class="col-md-1" style="text-align: right;">
                                    <td><a href="{{ route('editar_protocolo', $p->id) }}" class="btn btn-sm btn-warning" title="Editar"><i class="fa fa-edit"></i></a></td>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-sm table-striped">
                                        <tbody>
                                            <tr><th>Paciente</th><td>{{ $p->nombre_completo }}</td></tr>
                                            <tr><th>Diagnostico</th><td>{{ $p->diagnostico_descripcion }} - {{ $p->cuerpo_parte_nombre}}</td></tr>
                                            <tr><th>Tratado En</th><td>{{ $p->hospital_nombre }}</td></tr>
                                            <tr><th>Aseguradora</th><td>{{ $p->aseguradora_nombre }}</td></tr>
                                            <tr><th>Fecha</th><td>{{ \Carbon\Carbon::parse($p->fecha_ciclo)->format('d/m/Y') }}</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        @endforeach
        </div>
        </ul>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js')}}"></script>
    @if(Session::has('success'))
        <script>
            swal("Trabajo Finalizado", "{!! Session::get('success') !!}", "success")
        </script>
    @endif
@endsection