@extends('adminlte::page')

@section('title', 'Auditoría')

@section('content')
<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">Filtros de Auditoría</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('auditoria.index') }}" method="GET">
            <div class="row">
                <div class="col-md-4">
                    <label>Nombre del Usuario</label>
                    <input type="text" name="usuario" class="form-control" value="{{ request('usuario') }}" placeholder="Buscar médico o personal...">
                </div>
                <div class="col-md-3">
                    <label>Desde</label>
                    <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                </div>
                <div class="col-md-3">
                    <label>Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover table-sm">
            <thead class="bg-navy">
                <tr>
                    <th>Fecha/Hora</th>
                    <th>Usuario</th>
                    <th>IP</th>
                    <th>Navegador/Plataforma</th>
                    <th>Dispositivo</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($log->fecha_registro)->format('d/m/Y H:i:s') }}</td>
                    <td><b>{{ $log->usuario->name ?? 'Sistema' }}</b></td>
                    <td><code>{{ $log->ip_address }}</code></td>
                    <td>{{ $log->navegador }} / {{ $log->plataforma }}</td>
                    <td>{{ $log->dispositivo }}</td>
                    <td><span class="text-xs">{{ $log->metodo }}: {{ Str::limit($log->url_visitada, 50) }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No se encontraron registros con esos filtros.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{ $logs->links() }}
    </div>
</div>
@stop