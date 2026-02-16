@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/multi-select/css/multi-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
        /* Alineación profesional de etiquetas sin usar &nbsp; */
        .flex-label {
            min-width: 85px; /* Ajusta este valor según el texto más largo */
            justify-content: center;
        }
        
        @media (max-width: 768px) {
            .flex-label {
                min-width: 75px;
            }
        }

        .select2-container {
            width: 100% !important;
        }

        /* Fuerza a Select2 a ocupar el 100% real del espacio del input-group */
        .select2-container--bootstrap4 {
            flex: 1 1 auto;
            width: auto !important;
        }

        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(1.5em + 0.5rem + 2px) !important; /* Ajuste para input-group-sm */
        }

        /* Evita que el contenedor se desborde */
        .input-group > .select2-container--bootstrap4 {
            width: 0 !important;
            flex: 1 1 auto !important;
        }
    </style>
@endsection
@section('title', 'Usuarios')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <form class="form" method="POST" action="{{ route('usuario_grabar') }}">
                @csrf
                <div class="card">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="row">
                            <div class="col-md-9">
                                <h6>Usuarios</h6>
                            </div>
                            <div class="col-md-3" style="text-align: right;">
                                <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar los cambios"><i class="fas fa-save"></i></button>
                                <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" onclick="confirma_salida(); return false;" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" id="id" name="id" value="{{ $registro->id }}">
                        <input type="hidden" id="usuario_caja_id" name="usuario_caja_id" value="{{ $registro->caja_id }}">
                        <div class="row">
                            <div class="col-12 col-md-4 order-first order-md-last mb-4 mb-md-0 text-center d-none d-md-block">
                                <img src="{{ asset('imagenes/predeterminada.jpg') }}" class="img-fluid rounded shadow-sm" style="max-width: 200px;" alt="Imagen de perfil">
                            </div>
                            <div class="col-12 col-md-6 offset-md-1">
                                <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text flex-label">Nombre</label>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Nombre y Apellidos" id="name" name="name" value="{{ $registro->name }}" required autofocus>
                                </div>

                                <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text flex-label">Usuario</label>
                                    </div>
                                    <input type="text" class="form-control" placeholder="usuario" id="username" name="username" required value="{{ $registro->username }}">
                                </div>
                                <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text flex-label">Empresa</label>
                                    </div>
                                    <select class="form-control select2bs4" id="empresa_id" name="empresa_id">
                                        <option value="">Seleccionar...</option> 
                                        @foreach($empresas as $e)
                                            <option value="{{ $e->id }}" @if($registro->empresa_id == $e->id) selected @endif>{{ $e->nombre_comercial }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text flex-label" for="caja_id">Caja</label>
                                    </div>
                                    <select class="form-control select2bs4" id="caja_id" name="caja_id">
                                        <option value="">Seleccionar...</option>
                                        @foreach($cajas as $c)
                                            <option value="{{ $c->id }}" @if($registro->caja_id == $c->id) selected @endif>{{ $c->nombre_maquina }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text flex-label" for="sala_principal_id">Sala Principal</label>
                                    </div>
                                    <select class="form-control select2bs4" id="sala_principal_id" name="sala_principal_id">
                                        <option value="">Seleccionar...</option>
                                        @foreach($salas as $s)
                                            <option value="{{ $s->id }}" @if($registro->sala_principal_id == $s->id) selected @endif>{{ $s->sala_nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-5 offset-md-1 mb-3">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-header text-white" style="background-color: #c3ab95;">
                                        <h6 class="mb-0">Salas</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <select id='callbacks' name="callbacks[]" multiple='multiple' class="form-control">
                                                    @foreach($salas as $s)
                                                        <option value='{{ $s->id}}'>{{ $s->sala_nombre }}</option>
                                                    @endforeach
                                                </select>   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-5 mb-3">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-header text-white" style="background-color: #b9aca2">
                                        <h6 class="mb-0">Roles</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <select id='callbacksr' name="callbacksr[]" multiple='multiple' class="form-control">
                                                    @foreach($roles as $r)
                                                        <option value='{{ $r->id}}'>{{ $r->name }}</option>
                                                    @endforeach
                                                </select>   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('plugins/multi-select/js/jquery.multi-select.js') }}"></script>
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
        $(document).ready(function() {
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: "Seleccionar...",
                allowClear: true,
                // SI ESTÁS EN UN MODAL, añade esta línea:
                // dropdownParent: $('#nombre_de_tu_modal') 
            });

            // Truco para corregir el bug de falta de foco en el buscador
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
        });

        $('#empresa_id').on('select2:select', function (e) {
            // Obtenemos los datos del elemento seleccionado
            var data = e.params.data;
            var idSeleccionado = data.id;
            var textoSeleccionado = data.text;
            
            if (idSeleccionado !== "") {
                // ***** Actualizacion de Cajas ***** //
                $.ajax({
                    url: "{{ route('cajas_por_empresa') }}", // La ruta de Laravel
                    method: "POST",
                    data: {"_token": "{{ csrf_token() }}",
                           empresa_id: idSeleccionado},
                    success: function(response) {
                        let $cajaSelect = $('#caja_id');
                        $cajaSelect.empty();
                        let nuevaOpcion = new Option('Seleccionar...', '', false, false);
                        $cajaSelect.append(nuevaOpcion);
                        $.each(response, function(index, caja) {
                            // new Option(texto, valor, defaultSelected, selected)
                            let nuevaOpcion = new Option(caja.nombre_maquina, caja.id, false, false);
                            $cajaSelect.append(nuevaOpcion);
                        });

                        // 5. ¡IMPORTANTE! Refrescar Select2 para que pinte los cambios
                        $cajaSelect.trigger('change');
                    },
                    error: function() {
                        console.error("Error obteniendo detalles de empresa");
                    }
                });

                // ***** Actualizacion de Salas (Select Simple y Multi-Select) ***** //
                $.ajax({
                    url: "{{ route('salas_x_empresa') }}",
                    method: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        empresa_id: idSeleccionado
                    },
                    success: function(response) {
                        // 1. Actualizar el Select Simple (Sala Principal)
                        let $salaSelect = $('#sala_principal_id');
                        $salaSelect.empty();
                        $salaSelect.append(new Option('Seleccionar...', '', false, false));

                        // 2. Referencia al Multi-Select de Salas
                        let $multiSelectSalas = $('#callbacks');
                        $multiSelectSalas.empty(); // Limpiamos las opciones actuales del HTML

                        $.each(response, function(index, sala) {
                            // Llenar Select Simple
                            $salaSelect.append(new Option(sala.sala_nombre, sala.id, false, false));
                            
                            // Llenar Multi-Select (Añadimos el <option> al select original)
                            $multiSelectSalas.append('<option value="' + sala.id + '">' + sala.sala_nombre + '</option>');
                        });

                        // 3. ¡CRUCIAL! Refrescar ambas librerías
                        $salaSelect.trigger('change'); // Para Select2
                        $multiSelectSalas.multiSelect('refresh'); // Para la librería Multi-Select
                    },
                    error: function() {
                        console.error("Error obteniendo salas de la empresa");
                    }
                });
            }
        });

        // OPCIONAL: Capturar cuando se limpia el campo (si usas allowClear: true)
        $('#empresa_id').on('select2:unselect', function (e) {
            console.log("Se ha limpiado la selección");
        });

        $('#callbacks').multiSelect({
            selectableHeader: "<div class='custom-header text-center'><strong>Salas</strong></div>",
            selectionHeader: "<div class='custom-header text-center'><strong>Salas permitidas</strong></div>",
          afterSelect: function(values){
            //alert("Select value: "+values);
          },
          afterDeselect: function(values){
            //alert("Deselect value: "+values);
          }
        });
        var x = [];
        @foreach ($salas_x_usuario as $su)
            x.push("{{ $su['sala_id'] }}");
        @endforeach
        $('#callbacks').multiSelect('select', x);

        $('#callbacksr').multiSelect({
            selectableHeader: "<div class='custom-header text-center'><strong>Roles</strong></div>",
            selectionHeader: "<div class='custom-header text-center'><strong>Roles permitidos</strong></div>",
          afterSelect: function(values){
            //alert("Select value: "+values);
          },
          afterDeselect: function(values){
            //alert("Deselect value: "+values);
          }
        });
        var x = [];
        @foreach ($roles_x_usuario as $ru)
            x.push("{{ $ru['id'] }}");
        @endforeach
        $('#callbacksr').multiSelect('select', x);

        //=======================================================================
        // Confirmar Salida de pantalla
        //=======================================================================
        function confirma_salida(){
            Swal.fire({
                title: 'Confirmación',
                text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                // Esto es clave:
                buttonsStyling: false, 
                customClass: {
                    confirmButton: 'btn btn-success mx-2', // Agregamos 'btn' y margen
                    cancelButton: 'btn btn-danger mx-2'
                },
                allowEscapeKey: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('usuario_listado') }}";
                }
            });
        }
    </script>
@endsection