@extends('layouts.principal')
@section('css_principal')
@endsection
@section('title')
	Listado de Permisos
@endsection
@section('content')
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
	<br>
	<div class="content-fluid">
		<div class="row justify-content-center">
	        <div class="col-md-12">
	            @php $Id= Crypt::encrypt($permiso->id); @endphp
	            <form role="form" method="POST" action="{{ route('actualizar_permiso', $Id) }}">
		            @csrf
		            <div class="card card-navy">
		                <div class="card-header">
		                    <div class="row">
		                        <div class="col-md-4">Edición de Permiso</div>
	        					<div class="col-md-2 offset-md-4">
	        						<button type="submit" class="btn btn-sm btn-block verde" title="Guardar"><i class="fas fa-save"></i> Guardar</button>
	        					</div>
	        					<div class="col-md-2">
	        						<a href="{{ route('permisos') }}" class="btn btn-sm btn-block rojo" title="Rregresar a lista de familias"><i class="fas fa-sign-out-alt"></i> Cerrar</a>
	        					</div>
		                    </div>
		                </div>

		                <div class="card-body">
		                    <div class="input-group col-md-10 offset-md-1 mb-3">
  								<div class="input-group-prepend">
    								<span class="input-group-text" for="name">Nombre</span>
								</div>
  								<input type="text" class="form-control" placeholder="nombre de permiso" aria-label="Username" aria-describedby="basic-addon1" id="name" name="name" value="{{ $permiso->name }}" autofocus required>
							</div>
		                </div>
		            </div>
	        	</form>
	        </div>
    	</div>
    </div>

@endsection
@section('js_principal')

@endsection