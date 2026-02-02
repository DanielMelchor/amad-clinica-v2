@extends('adminlte::page')
@section('css')
@endsection
@section('title', 'Error')
@section('content')
	<div class="text-center mt-5">
    <h1 class="display-4">403</h1>
    <p class="lead">No tienes permiso para acceder a esta página.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Volver al inicio</a>
@endsection
@section('js')
@endsection