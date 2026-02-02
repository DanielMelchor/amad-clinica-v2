@extends('adminlte::page')
@section('css')
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
@endsection
@section('title', 'Observaciones')

@section('content_header')
  <h3>Nueva Observación</h3>
@endsection

@section('content')
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
	<form role="form" method="POST" action="{{route('grabar_observacion')}}">
		@csrf
		<div class="card card-navy">
			<div class="card-header">
				<div class="row">
					<div class="col-md-2 offset-md-10" style="text-align: right;">
						<button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
						<!--<a href="{{route('observaciones')}}" class="btn btn-sm btn-danger" title="Regresar a lista Observaciones de Admisión"><i class="fas fa-sign-out-alt"></i></a>-->
						<a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Pacientes" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
					</div>
				</div>
			</div>
			<form class="form-horizontal">
				<div class="card-body">
					<div class="row">
						<div class="input-group mb-5 col-md-5 offset-md-1">
					  		<div class="input-group-prepend">
						    	<span class="input-group-text">Descripción</span>
						  	</div>
						  	<input type="text" class="form-control" placeholder="Observaciones" id="descripcion" name="descripcion" autofocus required value="{{ old('descripcion')}}">
						</div>
						<div class="col-md-5">
							<div class="form-group clearfix">
			              		<div class="icheck-primary d-inline">
			                    	<input type="radio" id="proceso1" name="proceso" value="APERTURA" checked>
			                        <label for="proceso1">Apertura</label>
			                  	</div>
			                  	<div class="icheck-primary d-inline">
			                    	<input type="radio" id="proceso2" name="proceso" value="REAPERTURA">
			                        <label for="proceso2">Re Apertura</label>
			                  	</div>
			              	</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group offset-md-1">
				            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
				              	<input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
				          		<label class="custom-control-label" for="estado">Activar</label>
				        	</div>
				      	</div>
					</div>
				</div>
			</form>
		</div>
    </form>
@endsection
@section('js')
	<script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
	<script type="text/javascript">
		function confirma_salida(){
	        swal({
	            title: 'Confirmación',
	            text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?"
	            type: 'warning',
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
	                    window.location.href = "{{ route('observaciones') }}";
                    } 
	            }
	        );
	    }
	</script>
@endsection