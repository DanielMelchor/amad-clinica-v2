@extends('adminlte::page')
@section('css')
    <style type="text/css">
        .numero {
            text-align: right;
        }
        /* Anchos fijos para que los inputs no se vean aplastados */
        #tblresoluciones th:nth-child(1), #tblresoluciones td:nth-child(1) { width: 25%; min-width: 150px; } /* Tipo Doc */
        #tblresoluciones th:nth-child(2), #tblresoluciones td:nth-child(2) { width: 15%; min-width: 80px; }  /* Serie */
        #tblresoluciones th:nth-child(3), #tblresoluciones td:nth-child(3) { width: 15%; min-width: 100px; } /* Inicial */
        #tblresoluciones th:nth-child(4), #tblresoluciones td:nth-child(4) { width: 15%; min-width: 100px; } /* Final */
        #tblresoluciones th:nth-child(5), #tblresoluciones td:nth-child(5) { width: 15%; min-width: 100px; } /* Actual */
        #tblresoluciones th:nth-child(6), #tblresoluciones td:nth-child(6) { width: 10%; min-width: 60px; }  /* Estado */
        #tblresoluciones th:nth-child(7), #tblresoluciones td:nth-child(7) { width: 5%; }                   /* Botón */
    </style>
@endsection
@section('title', 'Cajas')

@section('content_header')
    <br>
@endsection

@section('content')
    <form class="form-horizontal" role="form" method="POST" id="cajaForm" name="cajaForm" action="{{ route('grabar_caja') }}">
    @csrf
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="card shadow-sm">
                        
                        <div class="card-header d-flex align-items-center" style="background-color: #E1E8ED;">
                            <h6 class="mb-0 flex-grow-1 font-weight-bold text-truncate">Agregar Caja</h6>
                            <div class="d-flex" style="gap: 5px;">
                                <button type="submit" class="btn btn-sm btn-outline-success rounded-circle elevation-2" title="Guardar">
                                    <i class="fas fa-save"></i>
                                </button>
                                <a href="#" class="btn btn-sm btn-outline-danger rounded-circle elevation-2" onclick="confirma_salida();">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card-body p-2 p-md-3">
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-10">
                                    
                                    <div class="form-group mb-3">
                                        <label for="caja_nombre" class="font-weight-bold">Nombre de Caja</label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend d-none d-sm-block">
                                                <span class="input-group-text">Nombre</span>
                                            </div>
                                            <input type="text" class="form-control" 
                                                   placeholder="Ej: Bodega Central" 
                                                   id="caja_nombre" name="caja_nombre" autofocus 
                                                   required value="{{ old('caja_nombre') }}">
                                        </div>
                                        <small class="text-muted d-block mt-1">Nombre identificador de la caja.</small>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 col-sm-6 mb-2">
                                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                <input type="checkbox" class="custom-control-input" id="editar_documento" name="editar_documento" value="S">
                                                <label class="custom-control-label" for="editar_documento">Editar Documento</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 mb-2">
                                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
                                                <label class="custom-control-label" for="estado">Activar Registro</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 col-lg-12 mb-2">
                                            <hr style="border: 1px solid #C8BA90 !important;" class="my-3">

                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <div style="width: 40px;"></div> <h6 class="mb-0 font-weight-bold">Resoluciones</h6>
                                                <button type="button" class="btn btn-sm btn-outline-primary rounded-circle elevation-2" onclick="fnAgregarResolucion();">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>

                                            <hr style="border: 3px double #C8BA90 !important;" class="mt-0">

                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped w-100" id="tblresoluciones">
                                                    <thead class="thead-light">
                                                        <tr class="text-center" style="font-size: 0.85rem;">
                                                            <th>Doc.</th>
                                                            <th>Serie</th>
                                                            <th>Inicial</th>
                                                            <th>Final</th>
                                                            <th>Actual</th>
                                                            <th>Estado</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('js')
    @if(session('message'))
        <script>
            setTimeout(function() {
                Swal.fire({
                    title: "{{ session('type') == 'success' ? '¡Trabajo Finalizado!' : '¡Atención!' }}",
                    text: "{!! session('message') !!}",
                    icon: "{{ session('type', 'info') }}", 
                    confirmButtonText: "Aceptar",
                    customClass: {
                        confirmButton: "btn btn-{{ session('type') == 'success' ? 'success' : 'danger' }} elevation-2"
                    },
                    buttonsStyling: false
                });
            }, 500); // Bajé el tiempo a 500ms para que la respuesta se sienta más rápida
        </script>
    @endif
    <script type="text/javascript">
        var nLineaT = 0;

        //===================================================================
        // Agregar Resolución
        //===================================================================
        function fnAgregarResolucion() {
            event.preventDefault();
            
            // Usamos Template Literals para mayor claridad
            const fila = `
                <tr>
                    <td>
                        <select class="form-control form-control-sm select-comunicacion" 
                                name="resoluciones[${nLineaT}][tipo_documento_id]" required>
                            <option value="">Seleccionar...</option>
                            @foreach($tipo_documentos as $td)
                                <option value="{{ $td->id }}">{{ $td->descripcion }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm text-uppercase" 
                               name="resoluciones[${nLineaT}][serie]" placeholder="Serie">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm numero" 
                               name="resoluciones[${nLineaT}][inicial]" min="0" placeholder="0">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm numero" 
                               name="resoluciones[${nLineaT}][final]" min="0" placeholder="0">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm numero" 
                               name="resoluciones[${nLineaT}][ultimo]" min="0" placeholder="0">
                    </td>
                    <td class="text-center">
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input" 
                                   id="est_${nLineaT}" 
                                   name="resoluciones[${nLineaT}][estado]" value="1" checked>
                            <label class="custom-control-label" for="est_${nLineaT}"></label>
                        </div>
                    </td>
                    <td class="text-right">
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-circle btn-eliminar-fila" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>`;

            const $fila = $(fila);
            $('#tblresoluciones tbody').append($fila);

            // Inicializar Select2 solo en la nueva fila
            $fila.find('.select-comunicacion').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            nLineaT++;
        }

        // Delegación de eventos para eliminar (más eficiente que re-asignar en cada clic)
        $('#tblresoluciones').on('click', '.btn-eliminar-fila', function() {
            $(this).closest('tr').remove();
        });

        //===================================================================
        // Confirmar salida de pantalla
        //===================================================================
        function confirma_salida(){
            Swal.fire({
                title: 'Confirmación',
                text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745', // Color success de AdminLTE
                cancelButtonColor: '#dc3545',  // Color danger de AdminLTE
                confirmButtonText: 'Si Cerrar',
                cancelButtonText: 'No',
                allowEscapeKey: true,
                reverseButtons: true // Opcional: pone el botón de confirmar a la derecha
            }).then((result) => {
                if (result.isConfirmed) { 
                    window.location.href = "{{ route('cajas') }}";
                } 
            });
        }
    </script>
@endsection