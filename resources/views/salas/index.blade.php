@extends('adminlte::page')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
        .btn-guardar{ background-color: #A5C890 !important; }
        .numero{ text-align: right; }
        
        /* Contenedor de tabla responsiva optimizado para touch */
        .table-responsive {
            width: 100%;
            margin-bottom: 1rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Etiquetas con ancho mínimo para alineación uniforme en formularios */
        .input-group-text {
            min-width: 110px;
            justify-content: center;
        }

        @media (max-width: 576px) {
            /* Apilar etiquetas encima de los inputs en móviles muy pequeños */
            .input-group {
                flex-direction: column;
            }
            .input-group-prepend, .input-group-text {
                width: 100% !important;
                border-radius: 0.25rem 0.25rem 0 0 !important;
                justify-content: center;
                background-color: #f8f9fa;
            }
            .form-control {
                width: 100% !important;
                border-radius: 0 0 0.25rem 0.25rem !important;
            }
            
            /* Ajuste de fuentes para maximizar espacio en pantalla */
            .table td, .table th { font-size: 11px; padding: 0.5rem 0.25rem; }
            .btn-xs { padding: 0.25rem 0.4rem; }
            .card-header h6 { font-size: 1.1rem; }
        }
    </style>
@endsection

@section('title', 'Salas')

@section('content')
    <div class="container-fluid pt-3">
        <div class="row">
            {{-- Mobile First: col-12 por defecto, col-lg-10 centrado en escritorio --}}
            <div class="col-12 col-lg-10 offset-lg-1">
                <div class="card shadow-sm border-0">
                    <div class="card-header border-0" style="background-color: #E1E8ED;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-secondary">Salas</h6>
                            <div>
                                <button type="button" class="btn btn-xs btn-outline-primary rounded-circle elevation-2" title="Agregar Registro" onclick="fn_agregar(); return false;">
                                    <i class="fas fa-plus-circle"></i>
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-2" title="Salir">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-1 p-md-3"> {{-- Menos padding lateral en móvil --}}
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover text-center w-100" id="tblprincipal">
                                <thead class="thead-light">
                                    <tr style="font-size: 12px;">
                                        <th>Nombre</th>
                                        <th>Inicio</th>
                                        {{-- Ocultamos columnas menos críticas en celulares --}}
                                        <th class="d-none d-md-table-cell">Máx Citas</th>
                                        <th class="d-none d-md-table-cell">Min/Cita</th>
                                        <th>Estado</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 13px;">
                                    @foreach($salas as $s)
                                        <tr>
                                            <td class="align-middle text-left font-weight-bold">{{ $s->sala_nombre }}</td>
                                            <td class="align-middle">{{ \Carbon\Carbon::parse($s->hora_inicio)->format('H:i') }}</td>
                                            <td class="align-middle d-none d-md-table-cell">{{ $s->maximo_registros }}</td>
                                            <td class="align-middle d-none d-md-table-cell">{{ $s->minutos_por_registro }}</td>
                                            <td class="align-middle">
                                                <span class="badge {{ $s->estado == 1 ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $s->estado == 1 ? 'Alta' : 'Baja' }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                @php $Id= Crypt::encrypt($s->id); @endphp
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
            <div class="modal-content border-0">
                <form role="form" id="formaNuevoRegistro" method="POST" action="{{route('sala_grabar')}}">
                    @csrf
                    <div class="card mb-0">
                        <div class="card-header bg-light border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold">Nuevo Registro</h6>
                                <div>
                                    <button type="submit" id="submitButton" class="btn btn-xs btn-outline-success rounded-circle mr-1"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle" data-dismiss="modal"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-3 py-4">
                            <div class="row">
                                <div class="col-12 col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm mb-3 shadow-sm">
                                        <div class="input-group-prepend"><label class="input-group-text">Nombre</label></div>
                                        <input type="text" class="form-control" id="sala_nombre" name="sala_nombre" required placeholder="Ej: Sala A">
                                    </div>
                                    <div class="input-group input-group-sm mb-3 shadow-sm">
                                        <div class="input-group-prepend"><label class="input-group-text">Hora Inicio</label></div>
                                        <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
                                    </div>
                                    <div class="input-group input-group-sm mb-3 shadow-sm">
                                        <div class="input-group-prepend"><label class="input-group-text">Máximo Citas</label></div>
                                        <input type="number" class="form-control text-right" id="maximo_registros" name="maximo_registros" required>
                                    </div>
                                    <div class="input-group input-group-sm mb-3 shadow-sm">
                                        <div class="input-group-prepend"><label class="input-group-text">Minutos/Cita</label></div>
                                        <input type="number" class="form-control text-right" id="minutos_x_cita" name="minutos_x_cita" required>
                                    </div>
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success mt-3">
                                        <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="1" checked>
                                        <label class="custom-control-label" for="estado">Activar Sala</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0">
                <form role="form" method="POST" action="{{route('sala_actualizar')}}">
                    @csrf
                    <div class="card mb-0">
                        <div class="card-header bg-light border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold">Edición de Registro</h6>
                                <div>
                                    <button type="submit" class="btn btn-xs btn-outline-success rounded-circle mr-1"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle" data-dismiss="modal"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-3 py-4">
                            <input type="hidden" id="eid" name="eid">
                            <div class="row">
                                <div class="col-12 col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm mb-3 shadow-sm">
                                        <div class="input-group-prepend"><label class="input-group-text">Nombre</label></div>
                                        <input type="text" class="form-control" id="esala_nombre" name="esala_nombre" required>
                                    </div>
                                    <div class="input-group input-group-sm mb-3 shadow-sm">
                                        <div class="input-group-prepend"><label class="input-group-text">Hora Inicio</label></div>
                                        <input type="time" class="form-control" id="ehora_inicio" name="ehora_inicio" required>
                                    </div>
                                    <div class="input-group input-group-sm mb-3 shadow-sm">
                                        <div class="input-group-prepend"><label class="input-group-text">Máximo Citas</label></div>
                                        <input type="number" class="form-control text-right" id="emaximo_registros" name="emaximo_registros" required>
                                    </div>
                                    <div class="input-group input-group-sm mb-3 shadow-sm">
                                        <div class="input-group-prepend"><label class="input-group-text">Minutos/Cita</label></div>
                                        <input type="number" class="form-control text-right" id="eminutos_x_cita" name="eminutos_x_cita" required>
                                    </div>
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success mt-3">
                                        <input type="checkbox" class="custom-control-input" id="eestado" name="eestado" value="1">
                                        <label class="custom-control-label" for="eestado">Activar Sala</label>
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
        });

        function fn_agregar(){
            $('#formaNuevoRegistro')[0].reset();
            $('#agregarModalCenter').modal('show');
            $('#agregarModalCenter').on('shown.bs.modal', function () {
                $('#sala_nombre').trigger('focus');
            });
        }

        function fn_edicion(id){
            $.ajax({
                url: "{{ route('sala_editar') }}",
                type: "POST",
                dataType: 'json',
                data: {"_token": "{{ csrf_token() }}", id : id},
                success: function(response){
                    $('#eid').val(id);
                    $('#esala_nombre').val(response.sala_nombre);
                    $('#ehora_inicio').val(response.hora_inicio.substring(0,5));
                    $('#emaximo_registros').val(response.maximo_registros);
                    $('#eminutos_x_cita').val(response.minutos_por_registro);
                    $('#eestado').prop('checked', response.estado == 1);
                    $('#editarModalCenter').modal('show');
                }
            });
        }

        $(document).ready(function() {
            $('#formaNuevoRegistro').on('submit', function() {
                $('#submitButton').prop('disabled', true);
            });
        });
    </script>
@endsection