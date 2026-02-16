@extends('adminlte::page')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
        .btn-guardar{ background-color: #A5C890 !important; }
        .numero{ text-align: right; }
        
        /* Contenedor de tabla responsiva */
        .table-responsive {
            width: 100%;
            margin-bottom: 1rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Mobile First: Inputs al 100% y etiquetas alineadas */
        .input-group-text {
            min-width: 120px;
            justify-content: center;
        }

        @media (max-width: 768px) {
            /* Ajuste de fuentes para ganar espacio en móvil */
            .table td, .table th { font-size: 11px; padding: 0.5rem 0.25rem; }
            .btn-xs { padding: 0.25rem 0.4rem; }
            
            /* Los modales ocupan más espacio en móvil */
            .modal-dialog { margin: 0.5rem; }
        }
    </style>
@endsection

@section('title', 'Paises')

@section('content')
    <div class="container-fluid pt-3">
        <div class="row">
            {{-- Mobile First: col-12 por defecto, col-lg-10 centrado en pantallas grandes --}}
            <div class="col-12 col-lg-10 offset-lg-1">
                <div class="card shadow-sm border-0">
                    <div class="card-header border-0" style="background-color: #E1E8ED;">
                        <div class="row align-items-center">
                            <div class="col-7 col-md-9">
                                <h6 class="mb-0 font-weight-bold">Paises</h6>
                            </div>
                            <div class="col-5 col-md-3 text-right">
                                <button type="button" class="btn btn-xs btn-outline-primary rounded-circle elevation-2" onclick="fn_agregar();">
                                    <i class="fas fa-plus-circle"></i>
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-2">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-1 px-md-3">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover text-center w-100" id="tblprincipal">
                                <thead class="thead-light">
                                    <tr style="font-size: 12px;">
                                        <th>Nombre</th>
                                        <th class="d-none d-sm-table-cell">Abreviatura</th> {{-- Oculto en móviles XS --}}
                                        <th>Código</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>   
                                </thead>
                                <tbody>
                                    @foreach($listado as $l)
                                    <tr style="font-size: 12px;">
                                        <td class="text-left font-weight-bold">{{ $l->nombre }}</td>
                                        <td class="d-none d-sm-table-cell">{{ $l->abreviatura }}</td>
                                        <td>{{ $l->cod_area }}</td>
                                        <td>
                                            <span class="badge {{ $l->estado == 1 ? 'badge-success' : 'badge-danger' }}">
                                                {{ $l->estado == 1 ? 'Alta' : 'Baja' }}
                                            </span>
                                        </td>
                                        @php $Id= Crypt::encrypt($l->id); @endphp
                                        <td>
                                            <button class="btn btn-xs btn-warning rounded-circle elevation-2" onclick="fn_edicion('{{$Id}}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
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

    <div class="modal fade" id="agregarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow">
                <form role="form" id="formaNuevoRegistro" method="POST" action="{{route('pais_grabar')}}">
                    @csrf
                    <div class="card mb-0">
                        <div class="card-header border-0" style="background-color: #F4F6F7;">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h6 class="mb-0">Nuevo Registro</h6>
                                </div>
                                <div class="col-4 text-right">
                                    <button type="submit" id="submitButton" class="btn btn-xs btn-outline-success rounded-circle elevation-2"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-2" data-dismiss="modal"><i class="fas fa-times"></i></button>
                                </div>
                            </div>  
                        </div>
                        <div class="card-body">
                            {{-- Estructura de filas col-12 para que en móvil ocupen todo el ancho --}}
                            <div class="row mb-2">
                                <div class="col-12 col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">Nombre</span></div>
                                        <input type="text" class="form-control" id="nombre" name="nombre" autofocus required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12 col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">Abreviatura</span></div>
                                        <input type="text" style="text-transform: uppercase;" class="form-control" id="abreviatura" name="abreviatura" maxlength="3" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12 col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">Cód. Área</span></div>
                                        <input type="number" class="form-control text-right" id="cod_area" name="cod_area" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12 col-md-10 offset-md-1">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="1" checked>
                                        <label class="custom-control-label" for="estado">Activar País</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editarModalCenter" data-backdrop="static" data-keyboard="false" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow">
                <form role="form" method="POST" action="{{route('pais_actualizar')}}">
                    @csrf
                    <div class="card mb-0">
                        <div class="card-header border-0" style="background-color: #F4F6F7;">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h6 class="mb-0">Edición de Registro</h6>
                                </div>
                                <div class="col-4 text-right">
                                    <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-2"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-2" data-dismiss="modal"><i class="fas fa-times"></i></button>
                                </div>
                            </div>  
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="eid" name="eid">
                            <div class="row mb-2">
                                <div class="col-12 col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">Nombre</span></div>
                                        <input type="text" class="form-control" id="enombre" name="enombre" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12 col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">Abreviatura</span></div>
                                        <input type="text" style="text-transform: uppercase;" class="form-control" id="eabreviatura" name="eabreviatura" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12 col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">Cód. Área</span></div>
                                        <input type="number" class="form-control text-right" id="ecod_area" name="ecod_area" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12 col-md-10 offset-md-1">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="eestado" name="eestado" value="1">
                                        <label class="custom-control-label" for="eestado">Activar País</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    {{-- Capturar Errores de Validación (como el unique:username) --}}
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Revisar Formulario',
                // Unimos todos los mensajes de error en una lista
                html: `
                    <ul style="text-align: left;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                confirmButtonText: "Aceptar",
                confirmButtonColor: "#dc3545",
                customClass: {
                    confirmButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });
        </script>
    @endif
    @if(Session::get('type') == 'success')
        @if(Session::has('message'))
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: "¡Trabajo Finalizado!",
                        text: "{!! Session::get('message') !!}",
                        icon: "success", // Cambiado de 'type' a 'icon'
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                        customClass: {
                            confirmButton: 'btn btn-success'
                        },
                        buttonsStyling: false
                    });
                }, 1000);
            </script>
        @endif
    @endif
    @if(Session::get('type') == 'error')
        @if(Session::has('message'))
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: "¡Trabajo Finalizado!",
                        text: "{!! Session::get('message') !!}",
                        icon: "error", // Cambiado de 'type' a 'icon'
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });
                }, 1000);
            </script>
        @endif
    @endif
    <script type="text/javascript">
        $(function () {
            $('#tblprincipal').DataTable({
                "responsive": true,
                "autoWidth": false,
                {{-- DOM optimizado para móviles: buscador y botones se apilan --}}
                "dom": '<"row"<"col-12 col-md-4"l><"col-12 col-md-4 text-center"B><"col-12 col-md-4"f>>rt<"row"<"col-12 col-md-5"i><"col-12 col-md-7"p>>',
                "paging": true,
                "pageLength": 10,
                "language": {
                    "sSearch": "",
                    "searchPlaceholder": "Buscar país..."
                },
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-sm btn-default mb-2'
                    }
                ]
            });
        });

        function fn_agregar(){
            $('#formaNuevoRegistro')[0].reset();
            $("#agregarModalCenter").modal('show');
            $('#agregarModalCenter').on('shown.bs.modal', function () {
                $('#nombre').focus();
            });
        }

        function fn_edicion(id){
            $.ajax({
                url: "{{ route('pais_editar') }}",
                type: "GET",
                data: {id : id},
                success: function(response){
                    $('#eid').val(id);
                    $('#enombre').val(response.nombre);
                    $('#eabreviatura').val(response.abreviatura);
                    $('#ecod_area').val(response.cod_area);
                    $('#eestado').prop('checked', response.estado == 1);
                    
                    $("#editarModalCenter").modal('show');
                    $('#editarModalCenter').on('shown.bs.modal', function () {
                        $('#enombre').focus();
                    });
                }
            });
        }

        $('#formaNuevoRegistro').on('submit', function() {
            $('#submitButton').prop('disabled', true);
        });
    </script>
@endsection