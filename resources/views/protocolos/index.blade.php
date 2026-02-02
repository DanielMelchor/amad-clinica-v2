@extends('adminlte::page')
@section('title', 'Protocolos')

@section('content_header')
    <h3>Protocolos / Listado</h3>
@endsection

@section('content')
	<div class="card card-navy">
		<div class="card-header">
			<div class="col-md-1 offset-md-11" style="text-align: right;">
				<a href="{{ route('crear_protocolo')}}" class="btn btn-sm btn-primary" title="Crear Protocolo"><i class="fas fa-plus-circle"></i></a>
			</div>
		</div>
		<form class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-md-10 offset-md-1">
						<div class="table-responsive">
							<table class="table table-sm table-striped table-hover">
								<thead class="thead-primary">
									<tr>
										<th>Paciente</th>
										<th>Diagnostico</th>
										<th>Tratado en</th>
										<th>Tipo Tratamiento</th>
										<th>Ciclos</th>
										<th>Frecuencia</th>
										<th>Ultimo Ciclo</th>
										<th>&nbsp;</th>
									</tr>	
								</thead>
								<tbody>
									@foreach($listado as $l)
										<tr>
											<td>{{ $l->nombre_completo }}</td>
											<td>{{ $l->diagnostico_descripcion }} - {{ $l->parte_cuerpo_nombre }}</td>
											<td>{{ $l->hospital_nombre }}</td>
											<td>{{ $l->tipo_tratamiento }}</td>
											<td>{{ $l->cantidad_ciclos }}</td>
											<td>{{ $l->frecuencia_ciclos }}</td>
											<td></td>
											<td><a href="{{ route('mostrar_ciclos', $l->id) }}" class="btn btn-sm btn-warning" title="Editar"><i class="fa fa-edit"></i></a></td>
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
	<div class="row">
		<div class="col-md-10 offset-md-1">
			<div class="panel panel-primary">
				<div class="panel-header">
				</div>
				<div class="panel-body">
					
					<!-- {{ trans('adminlte_lang::message.logged') }} -->
				</div>
			</div>
		</div>
	</div>
@endsection
@section('js')
	<script src="{{asset('assets/adminlte/plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
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
@endsection