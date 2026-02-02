@extends('adminlte::page')
@section('css')
	<meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.css')}}">
	<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endsection
@section('title', 'Medicamentos')

@section('content_header')
	<br>
@endsection

@section('content')
	<div class="row">
        <div class="col-md-10 offset-md-1">
        	<div class="card">
				<div class="card-header" style="background-color: #E1E8ED;">
					<div class="row">
						<div class="col-md-9">
							<h5>Medicamentos</h5>
						</div>
						<div class="col-md-3" style="text-align: right;">
							<a href="{{ route('crear_medicamento')}}" class="btn btn-xs btn-primary img-circle elevation-4" title="Crear medicamento"><i class="fas fa-plus-circle"></i></a>
							<a href="{{ route('home') }}" class="btn btn-xs btn-danger img-circle elevation-4" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
						</div>
					</div>
				</div>
				<form class="form-horizontal">
					<div class="card-body">
						<div class="row">
							<div class="col-md-6 offset-md-3">
								<div class="table-responsive">
									<table class="table table-sm table-striped table-hover">
										<thead class="thead-primary text-center">
											<tr>
												<th class="text-center">Nombre</th>
												<th class="text-center">Estado</th>
												<th>&nbsp;</th>
											</tr>	
										</thead>
										<tbody>
											@foreach($pMedicamentos as $pMedicamento)
												<tr class="text-center">
													<td>{{ $pMedicamento->nombre}}</td>
													@if($pMedicamento->estado == 'A')
														<td>Alta</td>
													@else
														<td>Baja</td>
													@endif
													<td>
														<a href="{{route('editar_medicamento' , $pMedicamento->id)}}" class="btn btn-xs btn-warning img-circle elevation-4"><i class="fas fa-edit"></i></a>
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
        </div>
    </div>
@endsection