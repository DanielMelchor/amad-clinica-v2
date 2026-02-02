@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
        .btn-guardar{
            background-color: #A5C890 !important;
        }
        .btn-salir{
            background-color: #dc3545 !important;
            color: white;
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
@section('title', 'Admisiones')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <br>
            <div class="card" style="background-color: #E1E8ED;">
                <div class="card-header d-flex align-items-center justify-content-between" style="background-color: #E1E8ED; display: flex; align-items: center; justify-content: space-between;">
                    <h6 class="m-0">Admisiones</h6>

                    <div class="d-flex align-items-center ml-auto">
                        <a href="#" class="btn btn-xs btn-outline-info rounded-circle elevation-4 mr-2" title="Criterio de Busqueda" onclick="fnAbrirBusqueda();">
                            <i class="fas fa-search"></i>
                        </a>
                        <a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4 mr-2" title="Crear Admisión" onclick="fnNuevaAdmision();">
                            <i class="fas fa-plus-circle"></i>
                        </a>
                        
                        <div id="contenedor-boton-excel" class="mr-2" hidden></div>

                        <a href="{{ route('home') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body" style="background-color: white">
                    <div class="table-responsive">
                        <table id="tblAdmision" class="table table-sm table-striped" width="100%" style="font-size: 12px;">
                            <thead>
                                <tr class="text-center">
                                    <th>Admisión</th>
                                    <th>Fecha</th>
                                    <th>Medico</th>
                                    <th>Hospital</th>
                                    <th>Paciente</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal nueva admision-->
    <div class="modal fade" id="nuevaAdmisionModal" role="dialog" aria-labelledby="nuevaAdmisionModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="admisionForm" name="admision" action="#">
                <!--<form class="form" method="POST" action="{{route('grabar_admision')}}">-->
                    @csrf
                    <div class="card">
                        <div class="card-header" style="background-color: #F4F6F7;">
                            <div class="row">
                                <div class="col-md-9">
                                    <h5>Nueva Admisión</h5>
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar"> <i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="adm_agenda_id" name="adm_agenda_id" value="0">
                            <!-- fecha admision -->
                            <div class="row">
                                <div class="input-group input-group-sm col-md-10 offset-md-1 mb-1 ">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Fecha Admisión&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    </div>
                                    <input type="date" class="form-control form-control-sm" id="adm_fecha" name="adm_fecha" required value="{{ $hoy }}" autofocus>
                                </div>
                            </div>
                            <!-- /fecha admision -->
                            <!-- paciente -->
                            <div class="row">
                                <div class="col-md-10 offset-md-1 input-group input-group-sm mb-1 ">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="adm_paciente_id">Paciente</label>
                                    </div>
                                    <select class="custom-select custom-select-sm select2 select2bs4" type="select" id="adm_paciente_id" name="adm_paciente_id" required>
                                        <option value="">Seleccionar.....</option>
                                        @foreach($pacientes as $p)
                                            <option value="{{ $p->id }}"> {{ $p->nombre_completo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- /paciente -->
                            <!-- medico -->
                            <div class="row">
                                <div class="col-md-10 offset-md-1 input-group input-group-sm mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="adm_medico_id">Médico</label>
                                    </div>
                                    <select class="custom-select custom-select-sm select2 select2bs4" id="adm_medico_id" name="adm_medico_id" required>
                                        <option value="" selected="selected">Seleccionar.....</option>
                                        @foreach($medicos as $medico)
                                            <option value="{{ $medico->id }}" @if($medico->principal == 'S') selected @endif> {{ $medico->nombre_completo}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- /medico -->
                            <!-- hospital -->
                            <div class="row">
                                <div class="col-md-10 offset-md-1 input-group input-group-sm mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="adm_hospital_id">Hospital</label>
                                    </div>
                                    <select class="custom-select custom-select-sm select2 select2bs4" id="adm_hospital_id" name="adm_hospital_id" required>
                                        <option value="" selected="selected">Seleccionar.....</option>
                                        @foreach($hospitales as $hospital)
                                            <option value="{{ $hospital->id }}" @if($hospital->principal_agenda == 'S') selected @endif> {{ $hospital->nombre}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- /hospital -->
                            <!-- admision terceros -->
                            <div class="row">
                                <div class="input-group mb-1 input-group-sm col-md-10 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Admisión Terceros</label>
                                    </div>
                                    <input type="text" class="form-control" id="admision_tercero" name="admision_tercero" value="{{ old('admision_tercero')}}">
                                </div>
                            </div>
                            <!-- /admision terceros -->
                            <!-- aseguradora -->
                            <div class="row">
                                <div class="input-group-sm col-md-10 offset-md-1 input-group input-group-sm mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="adm_aseguradora_id">Aseguradora</label>
                                    </div>
                                    <select class="custom-select custom-select-sm select2 select2bs4" id="adm_aseguradora_id" name="adm_aseguradora_id" onchange="fn_habilitar_poliza(this.value); return false;">
                                        <option value="" selected="selected">Seleccionar.....</option>
                                        @foreach($aseguradoras as $aseguradora)
                                                <option value="{{ $aseguradora->id }}"> {{ $aseguradora->nombre}} </option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- /aseguradora -->
                            <!-- poliza -->
                            <div class="row">
                                <div class="col-md-10 offset-md-1 input-group input-group-sm mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Póliza No.</label>
                                    </div>
                                    <input type="text" class="form-control" id="poliza_no" name="poliza_no" value="{{ old('poliza_no')}}" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1 input-group input-group-sm mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Autorización No.</label>
                                    </div>
                                    <input type="text" class="form-control" id="autorizacion_no" name="autorizacion_no" value="{{ old('autorizacion_no')}}" disabled>
                                </div>
                            </div>
                            <!-- /poliza -->
                            <div class="row">
                                <div class="col-md-5 offset-md-1 input-group input-group-sm mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">copago&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    </div>
                                    <input type="text" class="form-control numero" id="copago" name="copago" placeholder="Q." disabled>
                                </div>
                                <div class="col-md-5 input-group input-group-sm mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">coaseguro</label>
                                    </div>
                                    <input type="text" class="form-control numero" id="coaseguro" name="coaseguro" placeholder="%" min="0" max="100" step="any" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Modal nueva admision-->
    <!-- Busqueda -->
    <div class="modal fade" id="busquedaModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="busquedaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="card bg-light">
                    <div class="card-header">
                            <div class="row">
                                <div class="col-md-10">
                                    <h6>Busqueda de Expediente</h6>
                                </div>
                                <div class="col-md-2" style="text-align: right;">
                                    <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-1" title="Mostrar coincidencias" onclick="fnRealizarBusqueda();"><i class="fas fa-bars"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-1" title="Cerrar" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"><i class="fas fa-sign-out-alt"></i></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="input-group input-group-sm col-md-4 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="find_admision_no"># Admisión</label>
                                    </div>
                                    <input type="number" class="form-control " aria-label="Username" aria-describedby="basic-addon1" id="find_admision_no" name="find_admision_no" value="{{ old('find_admision_no') }}" autofocus>
                                </div>
                                <div class="input-group input-group-sm col-md-8 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="find_nombre">Nombre</label>
                                    </div>
                                    <input type="text" style="text-transform: uppercase;" class="form-control " aria-label="Username" aria-describedby="basic-addon1" id="find_nombre" name="find_nombre" value="{{ old('find_nombre') }}" required>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Busqueda -->
@endsection
@section('js')
    <script type="text/javascript">
        //========================================================================
        // inicializar librerias
        //========================================================================
        $(function () {
            $('.select2').select2()
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        });


        function fnNuevaAdmision(){
            $('#nuevaAdmisionModal').modal('show');
        }

        function fn_habilitar_poliza(id){
            if (id != '') {
                alert('1');
                $('#poliza_no').prop('disabled', false);
                $('#autorizacion_no').prop('disabled', false);
                $('#copago').prop('disabled', false);
                $('#copago').prop('required', true);
                $('#coaseguro').prop('disabled', false);
                $('#coaseguro').prop('required', true);
                $('#poliza_no').prop('required', true);
                $('#autorizacion_no').prop('required', true);
            }else{
                alert('2');
                $('#poliza_no').prop('disabled', true);
                $('#autorizacion_no').prop('disabled', true);
                $('#copago').prop('disabled', true);
                $('#copago').prop('required', false);
                $('#coaseguro').prop('disabled', true);
                $('#coaseguro').prop('required', false);
                $('#poliza_no').prop('required', false);
                $('#autorizacion_no').prop('required', false);
            }
        }

        //=====================================================================
        // Submit nueva admision
        //=====================================================================
        $(function(){
            $("#admisionForm").submit(function(){
                grabarAdmision();
                return false;
            })
        });

        //=====================================================================
        // Función para grabar admision
        //=====================================================================
        function grabarAdmision(){
            var agenda_id          = document.getElementById('adm_agenda_id').value;
            var tipo_admision      = $('input:radio[name=tipo_admision]:checked').val();
            var adm_fecha          = document.getElementById('adm_fecha').value;
            var adm_paciente_id    = document.getElementById('adm_paciente_id').value;
            var adm_medico_id      = document.getElementById('adm_medico_id').value;
            var adm_hospital_id    = document.getElementById('adm_hospital_id').value;
            var admision_tercero   = document.getElementById('admision_tercero').value;
            var adm_aseguradora_id = document.getElementById('adm_aseguradora_id').value;
            var poliza_no          = document.getElementById('poliza_no').value;
            var autorizacion_no    = document.getElementById('autorizacion_no').value;
            var copago             = document.getElementById('copago').value;
            var coaseguro          = document.getElementById('coaseguro').value;
            $.ajax({
                url: "{{ route('grabar_admision') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}", 
                       agenda_id          : agenda_id,
                       tipo_admision      : tipo_admision,
                       fecha              : adm_fecha, 
                       paciente_id        : adm_paciente_id,
                       medico_id          : adm_medico_id,
                       hospital_id        : adm_hospital_id,
                       admision_tercero   : admision_tercero,
                       aseguradora_id     : adm_aseguradora_id,
                       poliza_no          : poliza_no,
                       autorizacion_no    : autorizacion_no,
                       copago             : copago,
                       coaseguro          : coaseguro
                       },
                success: function(response){
                    console.log(response);
                    var admision_id = response['admision_id'];
                    Swal.fire({
                        title: response['respuesta'],
                        text: 'Trabajo Finalizado',
                        icon: 'success', // En v2 es 'icon', no 'type'
                        showConfirmButton: true,
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        // Esta es la forma correcta de manejar el clic en el botón en SweetAlert2
                        if (result.isConfirmed) {
                            // Usamos la ruta de Laravel de forma dinámica
                            var ruta = "{{ route('editar_admision', ':id') }}";
                            ruta = ruta.replace(':id', admision_id);
                            
                            window.location.href = ruta;
                        }
                    });
                    //alertify.success('Compra eliminada con exito');
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        //=====================================================================
        // Función para abrir parametros de busqueda
        //=====================================================================
        function fnAbrirBusqueda(){
            event.preventDefault();
            $('#busquedaModal').find('input[type="text"], input[type="email"], input[type="number"], textarea').val('');
            $('#busquedaModal').modal('show');
        }

        function fnRealizarBusqueda(){
            event.preventDefault();
            var table;
            $('#busquedaModal').modal('hide');
            // $('#tblAdmision').css('display','block');
            var admision_no = document.getElementById('find_admision_no').value;
            var nombre     = document.getElementById('find_nombre').value;
            $.ajax({
                url: "{{ route('listado_admisiones') }}",
                type: "POST",
                dataType: 'json',
                data: {"_token": "{{ csrf_token() }}",
                       admision_no : admision_no,
                       nombre : nombre},
                success: function(response){
                    if ($.fn.DataTable && $.fn.DataTable.isDataTable('#tblAdmision')) {
                        $('#tblAdmision').DataTable().destroy();
                    }
                    $("#tblAdmision tbody").empty();
                    table = $('#tblAdmision').DataTable({
                        data: response, // Datos cargados a través de AJAX
                        columns: [
                          { data: 'admision_no' },
                          { data: 'fecha' },
                          // { data: 'tipo_admision' },
                          { data: 'medico_nombre' },
                          { data: 'hospital_nombre' },
                          { data: 'paciente_nombre' },
                          { data: 'estado' },
                          {
                                // Esta columna contiene el botón de editar
                                render: function(data, type, row) {
                                    // Crear el enlace de editar con la URL dinámica
                                    var editUrl = "{{ route('editar_admision', ':id') }}";
                                    editUrl = editUrl.replace(':id', row['id']); // Reemplazar :id con el id de la fila actual

                                    return '<a href="' + editUrl + '" class="btn btn-xs btn-warning rounded-circle elevation-4" title="Editar" target="_blank"><i class="fas fa-edit"></i></a>';
                                }
                          }
                        ],
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'excelHtml5',  // Esto es para el botón de Excel
                                // text: 'Descargar <span class="fa-stack fa-1x" style="vertical-align: middle; font-size: 0.8em;"><i class="fas fa-circle fa-stack-2x" style="color: #28a745;"></i><i class="fas fa-file-excel fa-stack-1x fa-inverse"></i></span>',  // Texto del botón

                                // title: 'Datos Exportados',  // Título del archivo Excel
                                // className: 'btn btn-default'  // Puedes personalizar el estilo del botón
                                text: '<i class="fas fa-file-excel"></i>', 
                                titleAttr: 'Descargar a Excel', // El tooltip que sale al pasar el mouse
                                // Aplicamos las clases que solicitaste
                                className: 'btn btn-xs btn-outline-success rounded-circle elevation-4',
                                // Forzamos dimensiones para que sea un círculo perfecto y no un óvalo
                                attr: {
                                    style: 'width: 25px; height: 25px; display: inline-flex; align-items: center; justify-content: center; padding: 0;'
                                }
                            }
                        ],
                        order: [[0, 'desc']]
                    });
                    // Limpiamos el contenedor del header por si había un botón viejo
                    $('#contenedor-boton-excel').empty();
                    // Movemos el contenedor de botones de la tabla al header del modal
                    table.buttons().container().appendTo('#contenedor-boton-excel');
                    $('#contenedor-boton-excel').show().removeAttr('hidden').css('display', 'inline-flex');
                },
                error: function(error){
                    console.log(error);
                }
            });
        }
    </script>
@endsection