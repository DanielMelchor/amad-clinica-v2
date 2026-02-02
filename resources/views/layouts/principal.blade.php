@extends('adminlte::page')
@section('css')
	<link rel="stylesheet" href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/summernote-0.9.0-dist/summernote-bs4.min.css') }}">
	<style type="text/css">
		.verde {
			background-color: #97D700;
		}
		.rojo {
			background-color: #EF3340;
			color: white;
		}
		.azul {
			background-color: #10069F;
			color: white;
		}
        .numero {
            text-align: right;
        }
	</style>
	@yield('css_principal')
@endsection
@section('content')
    @if(Session::has('message'))
        @if(Session::has('success'))
            <script>
                swal("Trabajo Finalizado", "{!! Session::get('success') !!}", "success")
            </script>
        @endif
        @if(Session::has('error'))
            <script>
                swal("Error !!!", "{!! Session::get('error') !!}", "error")
            </script>
        @endif
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
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    @yield('contenido_pagina')
                </div>
            </div>
        </div>
    </div>
    <footer class="main-footer">
        <div class="row text-center">
            <div class="col-md-4">
                <a href="{{ asset('storage/MANUAL-DE-PROCEDIMIENTOS-UDI-2022.pdf')}}" target="_blank"><i class="fas fa-file-pdf"></i> Manual de Procedimientos</a>
            </div>
            <div class="col-md-4">
                <a href="{{ asset('storage/MANUAL UDI.pdf')}}" target="_blank"><i class="fas fa-file-pdf"></i> Manual de Usuario Solicitudes</a>
            </div>
            <div class="col-md-4">
                <a href="{{ asset('storage/Manual LAIP.pdf')}}" target="_blank"><i class="fas fa-file-pdf"></i> Manual de Usuario LAIP</a>
            </div>
        </div>
    </footer>
@endsection
@section('js')
	<script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('plugins/summernote-0.9.0-dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('plugins/summernote-0.9.0-dist/lang/summernote-es-ES.min.js') }}"></script>
	@yield('js_principal')
@endsection