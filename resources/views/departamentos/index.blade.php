@extends('adminlte::page')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
        .btn-guardar{ background-color: #A5C890 !important; }
        .numero{ text-align: right; }
        
        /* Contenedor de tabla responsiva optimizado */
        .table-responsive {
            width: 100%;
            margin-bottom: 1rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Etiquetas de ancho fijo para formularios alineados */
        .input-group-text {
            min-width: 100px;
            justify-content: center;
        }

        @media (max-width: 768px) {
            /* Ajuste de fuentes y padding para móviles */
            .table td, .table th { font-size: 11px; padding: 0.5rem 0.25rem; }
            .btn-xs { padding: 0.25rem 0.4rem; }
            
            /* Los modales ocupan casi todo el ancho en móvil */
            .modal-dialog { margin: 0.5rem; }
            
            /* Apilar elementos de input-group si es necesario en pantallas muy pequeñas */
            .input-group-text { min-width: 80px; font-size: 11px; }
        }
    </style>
@endsection

@section('title', 'Departamentos')

@section('content')
    <div class="container-fluid pt-3"> {{-- container-fluid para mejor uso del espacio lateral --}}
        <div class="row">
            {{-- Mobile First: col-12 por defecto, col-lg-10 centrado en escritorio --}}
            <div class="col-12 col-lg-10 offset-lg-1">
                <div class="card shadow-sm border-0">
                    <div class="card-header border-0" style="background-color: #E1E8ED;">
                        <div class="row align-items-center">
                            <div class="col-7 col-md-9">
                                <h6 class="mb-0 font-weight-bold">Departamentos</h6>
                            </div>
                            <div class="col-5 col-md-3 text-right">
                                <button type="button" class="btn btn-xs btn-outline-primary rounded-circle elevation-2" title="Agregar Registro" onclick="fn_agregar(); return false;">
                                    <i class="fas fa-plus-circle"></i>
                                </button>
                                <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-2" onclick="confirma_salida(); return false;" title="Salir">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-1 px-md-3"> {{-- Menos padding en móvil --}}
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover text-center w-100" id="tblprincipal">
                                <thead class="thead-light">
                                    <tr style="font-size: 12px;">
                                        <th>País</th>
                                        <th>Departamento</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>   
                                </thead>
                                <tbody style="font-size: 12px;">
                                    @foreach($listado as $l)
                                    <tr>
                                        <td class="align-middle text-left">{{ $l->pais_nombre }}</td>
                                        <td class="align-middle text-left font-weight-bold">{{ $l->departamento_nombre }}</td>
                                        <td class="align-middle">
                                            <span class="badge {{ $l->estado == '1' ? 'badge-success' : 'badge-danger' }}">
                                                {{ $l->estado == '1' ? 'Alta' : 'Baja' }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            @php $Id= Crypt::encrypt($l->id); @endphp
                                            <button class="btn btn-xs btn-warning rounded-circle elevation-2" onclick="fn_edicion('{{ $Id }}')">
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
                <form role="form" id="formaNuevoRegistro" method="POST" action="{{route('departamento_grabar')}}">
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
                            <div class="row mb-2">
                                <div class="col-12 col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">País</span></div>
                                        <select class="custom-select select2 select2bs4" id="pais_id" name="pais_id" required>
                                            <option value="" selected>Seleccionar...</option>
                                            @foreach($paises as $p)
                                                <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12 col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">Nombre</span></div>
                                        <input type="text" class="form-control" placeholder="Nombre Departamento" id="nombre" name="nombre" required value="{{ old('nombre')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-10 offset-md-1 mt-2">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="1">
                                        <label class="custom-control-label" for="estado">Activar Registro</label>
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
                <form role="form" method="POST" action="{{route('departamento_actualizar')}}">
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
                                        <div class="input-group-prepend"><span class="input-group-text">País</span></div>
                                        <select class="custom-select select2 select2bs4" id="epais_id" name="epais_id" required>
                                            <option value="" selected>Seleccionar...</option>
                                            @foreach($paises as $p)
                                                <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12 col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">Nombre</span></div>
                                        <input type="text" style="text-transform: uppercase;" class="form-control" id="enombre" name="enombre" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-10 offset-md-1 mt-2">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="eestado" name="eestado" value="1">
                                        <label class="custom-control-label" for="eestado">Activar Registro</label>
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
    {{-- Mantén tus scripts de Swal.fire aquí --}}
    <script type="text/javascript">
        $(function () {
            $('#tblprincipal').DataTable({
                "responsive": true, {{-- Activa el modo responsivo de DataTables --}}
                "autoWidth": false,
                {{-- DOM adaptado para apilar controles en móvil --}}
                "dom": '<"row"<"col-12 col-md-4"l><"col-12 col-md-4 text-center"B><"col-12 col-md-4"f>>rt<"row"<"col-12 col-md-5"i><"col-12 col-md-7"p>>',
                "paging": true,
                "language": {
                    "sSearch": "",
                    "searchPlaceholder": "Buscar..."
                },
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-sm btn-default mb-2'
                    }
                ]
            });

            $('.select2bs4').select2({ theme: 'bootstrap4' });
        });

        function fn_agregar(){
            $('#formaNuevoRegistro')[0].reset();
            $('#pais_id').val('').trigger('change');
            $("#agregarModalCenter").modal('show');
            $('#agregarModalCenter').on('shown.bs.modal', function () {
                $('#pais_id').focus();
            });
        }

        function fn_edicion(id){
            $.ajax({
                url: "{{ route('departamento_editar') }}",
                type: "POST",
                dataType: 'json',
                data: {"_token": "{{ csrf_token() }}", id : id},
                success: function(response){
                    $('#eid').val(id);
                    $('#epais_id').val(response.pais_id).trigger('change');
                    $('#enombre').val(response.nombre);
                    $('#eestado').prop('checked', response.estado == '1');
                    $("#editarModalCenter").modal('show');
                }
            });
        }

        {{-- Mantén la función confirma_salida() y el submit handler --}}
    </script>
@endsection