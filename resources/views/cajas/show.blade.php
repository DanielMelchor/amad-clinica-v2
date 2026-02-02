@extends('admin.layout')

@section('titulo')
	Cajas
@endsection
@section('subtitulo')
	Resoluciones
@endsection

@section('contenido')
	@if(Session::has('message'))
   		<div class="alert alert-success alert-dismissible" role="alert">
	    	<button type="button" class="close" data-dismiss="alert" arial-label="Close"><span aria-hidden="true">x</span>
	    	</button>
	        {{ Session::get('message') }}  
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
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-primary">
					<div class="panel-heading text-center">
						<h3>Resoluciones {{ $pCaja->nombre_maquina }}</h3>
					</div>

					<div class="panel-body">
						<div class="row">
							<div class="col-md-2 col-md-offset-10">
								<a href="{{ route('crear_resolucion', $pCaja->id) }}" class="btn btn-success btn-sm fa fa-plus" title="Agregar Resolución"></a>
								<a href="{{ route('cajas') }}" class="btn btn-warning btn-sm fa fa-sign-out" title="Regresar a lista de cajas"></a>
							</div>
						</div>
						<br>
						<div class="row">
						    <div class="col-md-10 col-md-offset-1">
								<table class="table table-hover text-center">
									<thead>
										<tr>
											<th>Documento</th>
											<th>Serie</th>
											<th>Corr. Inicial</th>
											<th>Corr. final</th>
											<th>Ultimo Docto</th>
											<th>Estado</th>	
										</tr>
									</thead>
									<tbody>
										@foreach($pResoluciones as $pResolucion)
										<tr>
											<td><a href="{{ route('editar_resolucion', $pResolucion->id) }}">{{ $pResolucion->descripcion }}</a></td>
											<td><a href="{{ route('editar_resolucion', $pResolucion->id) }}">{{ $pResolucion->serie }}</a></td>
											<td><a href="{{ route('editar_resolucion', $pResolucion->id) }}">{{ $pResolucion->correlativo_inicial }}</a></td>
											<td><a href="{{ route('editar_resolucion', $pResolucion->id) }}">{{ $pResolucion->correlativo_final }}</a></td>
											<td><a href="{{ route('editar_resolucion', $pResolucion->id) }}">{{ $pResolucion->ultimo_correlativo }}</a></td>
											<td><a href="{{ route('editar_resolucion', $pResolucion->id) }}">{{ $pResolucion->estado }}</a></td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection