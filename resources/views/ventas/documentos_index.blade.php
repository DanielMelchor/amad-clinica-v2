@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style type="text/css">
        #tblprincipal td:nth-child(11), 
        #tblprincipal td:nth-child(12) {
            font-weight: bold;
            color: #28a745; /* Verde para dinero */
        }
    </style>
@endsection
@section('title', 'Facturas')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header" style="background-color: #E1E8ED;">
                <div class="bg-default clearfix">
                    <div class="row">
                        <div class="col-lg-10 col-sm-10">
                            <h6>Facturas</h6>
                        </div>
                        <div class="col-lg-2 col-sm-2" style="text-align: right;">
                            <a href="{{ route('nueva_factura', 0) }}" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Grabar"><i class="fas fa-plus-circle"></i></a>
                            <a href="{{ route('home') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <table id="tblprincipal" class="table table-sm table-striped table-hover" width="100%" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Caja</th>
                                    <th>Corte</th>
                                    <th>Documento</th>
                                    <th>Fecha</th>
                                    <th>Serie</th>
                                    <th>Correlativo</th>
                                    <th>Condición</th>
                                    <th>NIT</th>
                                    <th>Nombre</th>
                                    <th>Paciente</th>
                                    <th>Total</th>
                                    <th>Saldo</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listado as $l)
                                    <tr>
                                        <td>{{ $l->nombre_maquina }}</td>
                                        <td>{{ $l->corte_id }}</td>
                                        <td>{{ $l->descripcion }}</td>
                                        <td data-order="{{ $l->fecha_emision }}">{{ \Carbon\Carbon::parse($l->fecha_emision)->format('d/m/Y') }}</td>
                                        <td>{{ $l->serie }}</td>
                                        <td>{{ $l->correlativo }}</td>
                                        <td>{{ $l->condicion }}</td>
                                        <td>{{ $l->nit }}</td>
                                        <td>{{ $l->nombre }}</td>
                                        <td>{{ $l->paciente_nombre }}</td>
                                        <td style="text-align: right;">{{ number_format($l->total, 2, '.', ',') }}</td>
                                        <td style="text-align: right;">{{ number_format($l->saldo, 2, '.', ',') }}</td>
                                        <td>
                                            @if($l->estado == 'Activo')
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">{{ $l->estado }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php $Id= Crypt::encrypt($l->id); @endphp
                                            <a href="{{ route('editar_factura', [$Id]) }}" class="btn btn-xs btn-warning rounded-circle elevation-4" title="Editar documento">
                                                <i class="nav-icon fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @if(Session::get('type') == 'success')
        @if(Session::has('message'))
            <script>
                setTimeout(function() {
                    swal({
                        title: "Trabajo Finalizado",
                        text: "{!! Session::get('message') !!}",
                        type: "success"
                    });
                }, 1000);
            </script>
        @endif
    @endif
    @if(Session::get('type') == 'error')
        @if(Session::has('message'))
            <script>
                setTimeout(function() {
                    swal({
                        title: "Error",
                        text: "{!! Session::get('message') !!}",
                        type: "error"
                    });
                }, 1000);
            </script>
        @endif
    @endif
    <script type="text/javascript">
        $(function () {
            $('#tblprincipal').DataTable({
                "scrollX": true,
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "pageLength": 25,  // Esto establece que por defecto se muestren 25 registros
                "lengthMenu": [ [10, 25, 50, 100], [10, 25, 50, 100] ],  // Esto establece las opciones en el dropdown
                "order": [[3, 'desc'], [4, 'asc'], [5, 'desc']],
                "language": {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla =(",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sSearch": "Buscar:",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    }
                },
                "dom": '<"row"<"col-sm-4"l><"col-sm-4 text-center"B><"col-sm-4"f>>rtip', // Ajuste para disposición
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        className: 'btn btn-md btn-default'
                    }
                ]
            });
        });
    </script>
@endsection