@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
        .btn-guardar{
            background-color: #A5C890 !important;
        }
        .numero{
            text-align: right;
        }
        .moneda:after {
            content: attr(data-numero);
        }
        .table-responsive {
            max-width: 100%; /* Ajusta el ancho según tus necesidades */
            overflow-x: auto; /* Permite el desplazamiento horizontal */
        }
    </style>
@endsection
@section('title', 'Empresas')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="container-fluid"> {{-- Usar container-fluid para mejor aprovechamiento de espacio --}}
        <div class="row">
            {{-- En móvil ocupa col-12, en escritorio col-10 y se centra con offset --}}
            <div class="col-12 col-md-10 offset-md-1">
                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h6 class="mb-0">Empresas</h6>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('crear_empresa', ['P', '0'])}}" class="btn btn-xs btn-outline-primary rounded-circle elevation-2" title="Nuevo Registro">
                                    <i class="fas fa-plus-circle"></i>
                                </a>
                                <a href="{{ route('home') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-2" title="Salir">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-2 px-md-4"> {{-- Menos padding en móvil --}}
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover" id="tblprincipal" style="width:100%">
                                <thead class="thead-light">
                                    <tr style="font-size: 13px;">
                                        <th>Nombre Comercial</th>
                                        <th class="d-none d-md-table-cell">Dirección</th> {{-- Ocultar en móvil para ganar espacio --}}
                                        <th>Teléfonos</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>   
                                </thead>
                                <tbody style="font-size: 12px;">
                                    @foreach($listado as $l)
                                        <tr>
                                            <td>{{ $l->nombre_comercial}}</td>
                                            <td class="d-none d-md-table-cell">{{ $l->direccion }}</td>
                                            <td>{{ $l->telefonos }}</td>
                                            <td>
                                                <span class="badge {{ $l->estado == 1 ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $l->estado == 1 ? 'Alta' : 'Baja' }}
                                                </span>
                                            </td>
                                            @php $Id= Crypt::encrypt($l->id); @endphp
                                            <td class="text-center">
                                                <a href="{{route('editar_empresa' , $Id )}}" class="btn btn-xs btn-warning rounded-circle shadow-sm">
                                                    <i class="fas fa-edit"></i>
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
                    }
                    // , function() {
                    //     window.location = "{{ route('empresas') }}";
                    // }
                    );
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
                    }
                    // , function() {
                    //     window.location = "{{ route('empresas') }}";
                    // }
                    );
                }, 1000);
            </script>
        @endif
    @endif
    <script type="text/javascript">
        $(function () {
            $('#tblprincipal').DataTable({
                "paging": true,
                "lengthChange": false, // Desactivado en móvil para simplicidad
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true, // Activamos el modo responsivo nativo
                "pageLength": 25,
                "language": {
                    "sSearch": "", // Quitamos el texto "Buscar" para ganar espacio
                    "searchPlaceholder": "Buscar...",
                    "sLengthMenu": "_MENU_",
                    // ... resto de tus traducciones ...
                },
                // Ajuste del DOM para que en móvil los elementos se apilen
                "dom": '<"row"<"col-12 col-md-6"B><"col-12 col-md-6"f>>rt<"row"<"col-12"i><"col-12"p>>',
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-sm btn-default'
                    }
                ]
            });
        });
    </script>
@endsection