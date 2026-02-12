@extends('adminlte::page')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
        .btn-guardar{ background-color: #A5C890 !important; }
        .numero{ text-align: right; }
        
        /* Ajustes Mobile First */
        .table-responsive {
            width: 100%;
            margin-bottom: 1rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Estilo para que las etiquetas no se amontonen en móviles */
        @media (max-width: 576px) {
            .input-group {
                flex-direction: column;
            }
            .input-group-prepend, .input-group-text {
                width: 100% !important;
                border-radius: 0.25rem 0.25rem 0 0 !important;
                justify-content: center;
            }
            .form-control {
                width: 100% !important;
                border-radius: 0 0 0.25rem 0.25rem !important;
            }
            .card-header h6 {
                font-size: 1.1rem;
                margin-bottom: 10px;
            }
        }
    </style>
@endsection

@section('title', 'Salas')

@section('content')
    <div class="container-fluid pt-3">
        <div class="row">
            <div class="col-12 col-lg-10 offset-lg-1">
                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #E1E8ED;">
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
                    <div class="card-body p-2 p-md-3">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover text-center w-100" id="tblprincipal">
                                <thead class="thead-light">
                                    <tr style="font-size: 12px;">
                                        <th>Nombre</th>
                                        <th>Inicio</th>
                                        <th class="d-none d-md-table-cell">Máx Citas</th>
                                        <th class="d-none d-md-table-cell">Min/Cita</th>
                                        <th>Estado</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 13px;">
                                    @foreach($salas as $s)
                                        <tr>
                                            <td class="align-middle text-left text-md-center">{{ $s->sala_nombre }}</td>
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
                                                <a href="#" class="btn btn-xs btn-warning rounded-circle elevation-2" title="Editar" onclick="fn_edicion('{{ $Id }}')">
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

    <div class="modal fade" id="agregarModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form role="form" id="formaNuevoRegistro" method="POST" action="{{route('sala_grabar')}}">
                    @csrf
                    <div class="card mb-0">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="m-0">Nuevo Registro</h6>
                                <div>
                                    <button type="submit" id="submitButton" class="btn btn-xs btn-outline-success rounded-circle mr-1"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle" data-dismiss="modal"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-3 py-4">
                            <div class="row">
                                <div class="col-12 col-md-10 offset-md-1">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend"><label class="input-group-text">Nombre</label></div>
                                        <input type="text" class="form-control" id="sala_nombre" name="sala_nombre" required>
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend"><label class="input-group-text">Hora Inicio</label></div>
                                        <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend"><label class="input-group-text">Máximo Citas</label></div>
                                        <input type="number" class="form-control text-right" id="maximo_registros" name="maximo_registros" required>
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend"><label class="input-group-text">Minutos/Cita</label></div>
                                        <input type="number" class="form-control text-right" id="minutos_x_cita" name="minutos_x_cita" required>
                                    </div>
                                    <div class="custom-control custom-switch custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
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
            <div class="modal-content">
                <form role="form" method="POST" action="{{route('sala_actualizar')}}">
                    @csrf
                    <div class="card mb-0">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="m-0 text-bold">Edición de Registro</h6>
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
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend"><label class="input-group-text">Nombre</label></div>
                                        <input type="text" class="form-control" id="esala_nombre" name="esala_nombre" required>
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend"><label class="input-group-text">Hora Inicio</label></div>
                                        <input type="time" class="form-control" id="ehora_inicio" name="ehora_inicio" required>
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend"><label class="input-group-text">Máximo Citas</label></div>
                                        <input type="number" class="form-control text-right" id="emaximo_registros" name="emaximo_registros" required>
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend"><label class="input-group-text">Minutos/Cita</label></div>
                                        <input type="number" class="form-control text-right" id="eminutos_x_cita" name="eminutos_x_cita" required>
                                    </div>
                                    <div class="custom-control custom-switch custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="eestado" name="eestado" value="A">
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
    <script type="text/javascript">
        $(function () {
            $('#tblprincipal').DataTable({
                "responsive": true, // Habilita el comportamiento responsivo nativo
                "autoWidth": false,
                "paging": true,
                "lengthChange": true,
                "pageLength": 10,
                // "language": { "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" },
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
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