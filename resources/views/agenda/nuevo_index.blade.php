@extends('adminlte::page')

@section('css')
    <style type="text/css">
        /* Alineación profesional de etiquetas */
        .input-group-text {
            min-width: 80px;
            justify-content: center;
        }

        /* Corrección de Select2 para que no colapse en móviles (Ancho 100%) */
        .select2-container--bootstrap4 {
            flex: 1 1 auto !important;
            width: auto !important;
        }

        /* Ajuste de altura para input-group-sm */
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(1.5em + 0.5rem + 2px) !important;
        }

        /* Estilos para botones en móviles */
        @media (max-width: 768px) {
            .btn-group-responsive {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
            }
        }
    </style>
@endsection

@section('title', 'Agenda')

@section('content')
    <div class="container-fluid pt-3">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header border-0" style="background-color: #E1E8ED;">
                        <div class="row align-items-center"> 
                            <div class="col-12 col-lg-3 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Médico</label>
                                    </div>
                                    <select class="form-control select2bs4" id="f_medico_id" name="f_medico_id" onchange="getMedico(this);">
                                        @foreach($medicos as $m)
                                            <option value="{{ $m->id }}" @if($m->principal == 'S') selected @endif> 
                                                {{ $m->nombre_completo }} 
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-lg-2 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Fecha</label>
                                    </div>
                                    <input type="date" class="form-control" id="fecha_filtro" value="{{ $today }}" onchange="getFecha(this);">
                                </div>
                            </div>

                            <div class="col-12 col-lg-3 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Estado</label>
                                    </div>
                                    <select class="form-control select2bs4" id="f_estado_id" name="f_estado_id" onchange="getEstado(this);">
                                        <option value="T">Todas</option>
                                        <option value="A" selected>Activas</option>
                                        <option value="C">Canceladas</option>
                                        <option value="R">Realizadas</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-lg-4 text-right mt-2 mt-lg-0">
                                <div class="btn-group-responsive">
                                    <button class="btn btn-xs btn-outline-primary rounded-circle elevation-2 mx-1" onclick="confirmarPresencia()" title="Asistencia"><i class="fas fa-thumbs-up"></i></button>
                                    <button class="btn btn-xs btn-outline-primary rounded-circle elevation-2 mx-1" title="Cita" onclick="fnEditarCita();"><i class="fas fa-plus"></i></button>
                                    <button class="btn btn-xs btn-outline-success rounded-circle elevation-2 mx-1" title="Finalizar" onclick="fnFinalizar();"><i class="fas fa-check"></i></button>
                                    <button class="btn btn-xs btn-outline-danger rounded-circle elevation-2 mx-1" title="Cancelar" onclick="fnCancelar();"><i class="fas fa-ban"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            // Inicialización robusta para Bootstrap 4
            $('.select2bs4').each(function() {
                $(this).select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: "Seleccionar...",
                    allowClear: true,
                    // Si el select está dentro de un modal, descomenta la siguiente línea:
                    // dropdownParent: $(this).parent() 
                });
            });

            // Corrección de bug de foco en el buscador de Select2
            $(document).on('select2:open', () => {
                let searchField = document.querySelector('.select2-search__field');
                if (searchField) {
                    searchField.focus();
                }
            });
        });
    </script>
@endsection