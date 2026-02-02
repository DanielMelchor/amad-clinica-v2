@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
        .nav-pills .nav-link.active,
        .show>.nav-pills .nav-link{
            background: #7FB3D5 !important;
            color: white!important;
        }
        .table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
           background-color: #D0E8F3 !important;
        }
        .numero{
            text-align: right;
        }
        .custom-select {
            height: 30px; /* Ajusta la altura según lo necesites */
        }
        .disabled {
            color: gray;
            pointer-events: none; /* Deshabilita el clic */
            text-decoration: none; /* Elimina el subrayado */
        }
    </style>
@endsection
@section('title', 'Admisión')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <br>
            @php $admision_id = Crypt::encrypt( $pAdmision->id ); @endphp
            <form role="form" method="POST" action="{{ route('admision_actualizar', $admision_id) }}" id="FormaGeneral">
                @csrf
                <div class="card">
                <div class="card-header" style="background-color: #E1E8ED;">
                    <div class="row">
                        <div class="col-sm-9">
                            <p style="font-family: sans-serif; font-size: 1.2rem;">Admision # {{ $pAdmision->admision_no }}</p>
                        </div>
                        <div class="col-sm-3" style="text-align: right;">
                            <a href="#" id="btn_aceptar_admision" onclick="aceptarAdmision('{{$admision_id}}'); return false;" class="btn btn-xs btn-outline-info rounded-circle elevation-4 disabled" title="Aceptar Admisión" disabled><i class="fas fa-check-circle"></i></a>
                            <button type="submit" id="btn_guardar_admision" class="btn btn-xs btn-outline-success rounded-circle elevation-4 disabled" title="Guardar"><i class="fas fa-save"></i></button>
                            <a href="#" id="btn_cerrar_admision" onclick="cerrarAdmision('{{$admision_id}}'); return false;" class="btn btn-xs btn-outline-secondary rounded-circle elevation-4" title="Cerrar Admisión"><i class="fas fa-lock"></i></a>
                            <a href="#" id="btn_cobrar_admision" onclick="cobrarAdmision('{{$pAdmision->admision_no}}'); return false;" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Emitir documento de Cobro" hidden><i class="fas fa-file-invoice"></i></a>
                            <a href="#" id="btn_reapertura_admision" onclick="abrirAdmision('{{$admision_id}}'); return false;" class="btn btn-xs btn-outline-secondary rounded-circle elevation-4" title="Abrir Admisión" hidden><i class="fas fa-unlock"></i></a>
                            <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir" onclick="confirma_salida();"><i class="fas fa-sign-out-alt"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="background-color: white;">
                    <div id="admisionForm">
                        <input type="hidden" id="admision_estado" name="admision_estado" value="{{ $pAdmision->estado }}">
                        <input type="hidden" id="encabezado_revisado" name="encabezado_revisado" value="{{ $pAdmision->encabezado_revisado}}">
                        <input type="hidden" id="admision_id" name="admision_id" value="{{ $admision_id }}">
                        <div class="row">
                            <div class="col-lg-5 offset-lg-1 col-sm-12 mb-1">
                                <div class="row">
                                    <div class="form-group form-control-sm clearfix">
                                        @foreach($tipo_admisiones as $tipo_admision)
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="{{$tipo_admision->nombre}}" name="tipo_admision" value="{{$tipo_admision->id}}" @if($tipo_admision->id == $pAdmision->tipo_admision) checked @endif>&nbsp;&nbsp;
                                                <label for="{{$tipo_admision->nombre}}">{{$tipo_admision->nombre}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Fecha Admisión</label>
                                        </div>
                                        <input type="date" class="form-control" id="fecha" name="fecha" required value="{{ $pAdmision->fecha }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="paciente_id">Paciente</label>
                                        </div>
                                        <select class="custom-select select2 select2bs4" id="paciente_id" name="paciente_id" required>
                                            <option value="" selected="selected">Seleccionar.....</option>
                                            @foreach($pPacientes as $p)
                                                <option value="{{ $p->id }}" @if($pAdmision->paciente_id == $p->id) then selected @endif> {{ $p->nombre_completo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="medico_id">Médico</label>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="medico_id" name="medico_id" required>
                                            <option value="" selected="selected">Seleccionar.....</option>
                                            @foreach($pMedicos as $pMedico)
                                                <option value="{{ $pMedico->id }}" @if($pAdmision->medico_id == $pMedico->id) then selected @endif> {{ $pMedico->nombre_completo}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="hospital_id">Hospital</label>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="hospital_id" name="hospital_id" required>
                                            <option value="" selected="selected">Seleccionar.....</option>
                                            @foreach($pHospitales as $pHospital)
                                                <option value="{{ $pHospital->id }}" @if($pAdmision->hospital_id == $pHospital->id) then selected @endif> {{ $pHospital->nombre}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-sm-12 mb-1">
                                <div class="row">
                                    <div class="input-group input-group-sm mb-1 ">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Total Admisión</label>
                                        </div>
                                        <input type="text" style="font-size: 14pt;" class="form-control numero" id="resumenTotal" name="resumenTotal" value="0" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group input-group-sm mb-1 ">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Admisión Terceros</label>
                                        </div>
                                        <input type="text" class="form-control" id="admision_tercero" name="admision_tercero" value="{{ $pAdmision->admision_tercero }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="aseguradora_id">Aseguradora</label>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="aseguradora_id" name="aseguradora_id">
                                            <option value="" selected="selected">Seleccionar.....</option>
                                            @foreach($pAseguradoras as $pAseguradora)
                                                    <option value="{{ $pAseguradora->id }}" @if($pAdmision->aseguradora_id == $pAseguradora->id) selected @endif> {{ $pAseguradora->nombre}} </option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Póliza</label>
                                        </div>
                                        <input type="text" class="form-control" id="poliza_no" name="poliza_no" value="{{ $pAdmision->poliza_no }}" title="Número de poliza del Asegurado">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Autorización</label>
                                        </div>
                                        <input type="text" class="form-control" id="aseguradora_aut_no" name="aseguradora_aut_no" value="{{ $pAdmision->aseguradora_aut_no }}" title="Número de Autorización del Asegurador">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group input-group-sm col-md-6 mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">copago</label>
                                        </div>
                                        <input type="number" class="form-control numero" id="copago" name="copago" value="{{ $pAdmision->copago }}" placeholder="Q." min="0">
                                    </div>
                                    <div class="input-group input-group-sm col-md-6 mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">coaseguro</label>
                                        </div>
                                        <input type="number" class="form-control numero" id="coaseguro" name="coaseguro" value="{{ $pAdmision->coaseguro }}" placeholder="%" min="0" max="100" step="any">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-10 offset-1" style="text-align: right;">
                                @if( $pAdmision->estado == 0)
                                    <div class="row">
                                        <div class="col-md-2 offset-md-10" style="text-align: right;">
                                            <a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="agregar_cargo(); return false;" title="Agregar Cargo">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-10 offset-1">
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped" id="tblCargos"  width="100%"  style="font-size: 12px;">
                                        <thead>
                                            <tr class="text-center">
                                                <th width="10%">Producto</th>
                                                <th width="30%">Descripción</th>
                                                <th width="10%">Medida</th>
                                                <th width="10%">Cnt</th>
                                                <th width="20%">Precio</th>
                                                <th width="20%">Total</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot></tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    <!-- re apertura -->
    <div class="modal fade" id="reapertura" role="dialog" aria-labelledby="reaperturaModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form class="form" method="POST" action="{{route('reapertura_admision', $pAdmision->id)}}">
                    @csrf
                    <div class="card">
                        <div class="card-header" style="background-color: #E1E8ED;">
                            <div class="row">
                                <div class="col-md-9">
                                    <h5>Apertura de Admisión</h5>
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar cambios"><i class="fas fa-save"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @php $admision_id = Crypt::encrypt( $pAdmision->id ); @endphp
                            <input type="hidden" id="reapertura_admision_id" name="reapertura_admision_id" value="{{ $admision_id }}">
                            <div class="row">
                                <div class="mb-1  col-md-10 offset-md-1">
                                    <div class="input-group ">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" for="observacion_id">Clasificación</span>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="observacion_id" name="observacion_id" required>
                                            <option value="" selected="selected">Seleccionar.....</option>
                                            @foreach($pObservaciones as $O)
                                                    <option value="{{ $O->id }}"> {{ $O->descripcion}} </option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-10 offset-md-1">
                                    <label for="observaciones">Observaciones</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /re apertura-->
@endsection
@section('js')
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    @if(Session::get('type') == 'success')
        @if(Session::has('message'))
            <script>
                
                setTimeout(function() {
                    Swal.fire({
                        title: "Trabajo Finalizado",
                        text: "{{ Session::get('message') }}",
                        icon: 'success', // En v2 es 'icon', no 'type'
                        showConfirmButton: true,
                        confirmButtonText: 'Aceptar'
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
                        title: "Error",
                        text: "{!! Session::get('message') !!}",
                        icon: 'error', // En v2 es 'icon', no 'type'
                        showConfirmButton: true,
                        confirmButtonText: 'Aceptar'
                    });
                }, 1000);
            </script>
        @endif
    @endif
    <script type="text/javascript">
        const formatter = new Intl.NumberFormat('es-GT', {
          style: 'currency',
          currency: 'GTQ',
          minimumFractionDigits: 2
        });

        //========================================================================
        // inicializar librerias
        //========================================================================
        $(function () {
            $('.select2').select2()
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        });

        var nLinea = 0;
        var total  = 0;
        var admisionEstado = 0;

        document.addEventListener('DOMContentLoaded', function() {
            let aceptado = document.getElementById('encabezado_revisado').value;
            admisionEstado = document.getElementById('admision_estado').value;
            console.log('Encabezado '+aceptado+' estado Admon '+admisionEstado);
            if (aceptado == 1) {
                $('#admisionForm input, #admisionForm textarea, #admisionForm select').prop('disabled', true);
                $('#Adicionales').css('display', 'block');
            }else{
                $('#admisionForm input, #admisionForm textarea, #admisionForm select').prop('disabled', false);
                $('#Adicionales').css('display', 'none');
            }
            $('#resumenTotal').prop('disabled', true);
            
            traeCargos();
            // traeDocumentos();
            // traeBitacora();
            validarBotones(aceptado);
        });

        // document.getElementById('resumenTotal').innerHTML = '<h4>'+formatter.format(total_documento)+'</h4>';
        // document.getElementById('resumenTotalPaciente').innerHTML = '<h4>'+formatter.format(total_paciente)+'</h4>';
        // document.getElementById('resumenTotalAseguradora').innerHTML = '<h4>'+formatter.format(total_aseguradora)+'</h4>';

        function calcularTotal() {
            // Inicializa la variable para la sumatoria
            var total = 0;

            // Recorre los inputs que contienen los valores a sumar (por ejemplo, con un loop for)
            var inputs = document.querySelectorAll('[id^="cargos["][id$="][paciente]"]');

            // Itera sobre todos los inputs encontrados y suma sus valores
            inputs.forEach(function(input) {
                total += parseFloat(input.value) || 0; // parseFloat para obtener valores numéricos, con fallback a 0 si no es un número
            });

            // Muestra el total en el elemento con id "total"
            // document.getElementById('total').innerText = total;
            document.getElementById('resumenTotal').value = total;
        }

        function validarBotones(estado){
            admisionEstado = document.getElementById('admision_estado').value;
            console.log('Estado '+admisionEstado)
            if (estado != 0) {
                $('#btn_aceptar_admision').addClass('disabled');
            }else{
                $('#btn_aceptar_admision').removeClass('disabled');
            }
            if (admisionEstado == 0) {
                $('#btn_guardar_admision').removeClass('disabled');
                $('#btn_cerrar_admision').prop('hidden', false);
                $('#btn_reapertura_admision').prop('hidden', true);
                $('#btn_cobrar_admision').prop('hidden', true);
                console.log('1');
            }else{
                $('#btn_guardar_admision').addClass('disabled');
                $('#btn_cerrar_admision').prop('hidden', true);
                $('#btn_reapertura_admision').prop('hidden', false);
                $('#btn_cobrar_admision').prop('hidden', false);
                console.log('2');
            }
        }
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
                    window.location.href = "{{ route('admisiones') }}";
                } 
            });
        }

        function aceptarAdmision($id){
            let admision_id = $id;
            $.ajax({
                url: "{{ route('marcar_revisado') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}",
                       admision_id: admision_id},
                success: function(response){
                    Swal.fire({
                        title: "Trabajo Finalizado",
                        text: response.message,
                        icon: 'success', // En v2 es 'icon', no 'type'
                        showConfirmButton: true,
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        location.reload();
                    });
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function fn_openLoad(){
            archivoInput.value = '';
            document.getElementById('visorArchivo').innerHTML = '';
            $('#documentoModal').modal('show')
        }

        function validarExt(){
            let archivoInput  = document.getElementById('archivoInput');
            let archivoRuta   = archivoInput.value;
            let extPermitidas = /(.pdf)$/i;

            if (!extPermitidas.exec(archivoRuta)) {
                Swal.fire({
                    title: "Error",
                    text: "Tipo de archivo no permitido",
                    icon: 'error', // En v2 es 'icon', no 'type'
                    showConfirmButton: true,
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    archivoInput.value = '';
                    document.getElementById('visorArchivo').innerHTML = '';
                });
            }else{
                if (archivoInput.files && archivoInput.files[0]) {
                    let visor = new FileReader();
                    visor.onload = function (e){
                        document.getElementById('visorArchivo').innerHTML = '<embed src="'+e.target.result+'" width="450" height="500">';
                    }
                    visor.readAsDataURL(archivoInput.files[0]);
                }
            }
        }

        function traeDocumentos(){
            let id = document.getElementById('admision_id').value;
            $.ajax({
                url: "{{ route('documentos_x_admision') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}",
                       id: id},
                success: function(response){
                    var assetBaseUrl = '{{ asset("/") }}';
                    var assetUrl = assetBaseUrl + 'storage/documentos/';
                    let html = '';
                    $('#tblDoctos').DataTable().clear().destroy();
                    $("#tblDoctos tbody").empty();
                    table = $('#tblDoctos').DataTable({
                        data: response, // Datos cargados a través de AJAX
                        columns: [
                          { data: 'created_at' },
                          { data: 'name' },
                          { 
                            data: 'titulo',
                            render: function(data, type, row) {
                                // console.log(row['admision']);
                                // Aquí puedes generar el enlace con el valor de 'titulo'
                                return '<a href="'+ assetUrl + row['admision']+'/'+ row['ruta'] + '" target="_blank">' + data + '</a>';
                            }
                          }
                        ],
                        // dom: 'Bfrtip',
                        // buttons: [
                        //     {
                        //         extend: 'excelHtml5',  // Esto es para el botón de Excel
                        //         text: 'Descargar a Excel',  // Texto del botón
                        //         title: 'Datos Exportados',  // Título del archivo Excel
                        //         className: 'btn btn-success'  // Puedes personalizar el estilo del botón
                        //     }
                        // ]
                    });
                    // let html = '';
                    // html += '<div class="row">';
                    // for (var i = 0; i < response.length; i++) {
                    //     var assetImg = assetBaseUrl + 'imagenes/pdf_file.png';
                    //     var assetUrl = assetBaseUrl + 'storage/documentos/' + response[i]['admision']+'/'+response[i]['ruta'];

                    //     html += '<div class="card-group">'
                    //     html += '<div class="card card-light">'
                    //     html += '<div class="card-header text-center">'
                    //     html += '<img src="'+assetImg+'" style="height: 5rem;" alt="pdf">'
                    //     html += '</div>'
                    //     html += '<div class="card-body text-center text-navy">'
                    //     html += '<a href="'+assetUrl+'" target="_blank">'+response[i]['titulo']+' <p>'+response[i]['created_at']+'</p></a>'
                    //     html += '</div>'
                    //     html += '</div>'
                    //     html += '</div>'
                    // }
                    // html += '</div>';
                    $('#detallePDF').html(html);
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function traeBitacora(){
            let id = document.getElementById('admision_id').value;
            $.ajax({
                url: "{{ route('bitacora_x_admision') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}",
                       id: id},
                success: function(response){
                    $('#tblBitacoras').DataTable().clear().destroy();
                    $("#tblBitacoras tbody").empty();
                    table = $('#tblBitacoras').DataTable({
                        data: response, // Datos cargados a través de AJAX
                        columns: [
                          { data: 'name' },
                          { data: 'created_at' },
                          { data: 'observaciones' }
                        ],
                        // dom: 'Bfrtip',
                        // buttons: [
                        //     {
                        //         extend: 'excelHtml5',  // Esto es para el botón de Excel
                        //         text: 'Descargar a Excel',  // Texto del botón
                        //         title: 'Datos Exportados',  // Título del archivo Excel
                        //         className: 'btn btn-success'  // Puedes personalizar el estilo del botón
                        //     }
                        // ]
                    });
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function traeCargos(){
            let id = document.getElementById('admision_id').value;
            $.ajax({
                url: "{{ route('cargos_x_admision') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}",
                       id: id},
                success: function(response){
                    $('#tblCargos').DataTable().clear().destroy();
                    $("#tblCargos tbody").empty();
                    for (var i = 0; i < response.length; i++) {
                        agregar_cargo();
                        $('#cargos\\['+i+'\\]\\[producto_id\\]').val(response[i]['producto_id']).trigger('change');
                        $('#cargos\\['+i+'\\]\\[descripcion\\]').val(response[i]['descripcion']);
                        $('#cargos\\['+i+'\\]\\[medida_id\\]').val(response[i]['unidad_medida_id']).trigger('change');
                        $('#cargos\\['+i+'\\]\\[cantidad\\]').val(response[i]['cantidad']);
                        $('#cargos\\['+i+'\\]\\[precio\\]').val(response[i]['precio_unitario']);
                        $('#cargos\\['+i+'\\]\\[total\\]').val(response[i]['precio_total']);
                        if (admisionEstado == 1) {
                            $('#cargos\\['+i+'\\]\\[producto_id\\]').prop('disabled', true);
                            $('#cargos\\['+i+'\\]\\[descripcion\\]').prop('disabled', true);
                            $('#cargos\\['+i+'\\]\\[medida_id\\]').prop('disabled', true);
                            $('#cargos\\['+i+'\\]\\[cantidad\\]').prop('disabled', true);
                            $('#cargos\\['+i+'\\]\\[precio\\]').prop('disabled', true);
                        }
                    }
                    sumarValores();
                    

                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        $('#cargaDocumentoForm').on('submit', function(event) {
            event.preventDefault(); // Evita el envío normal del formulario
            var formData = new FormData(this); // Serializa los datos del formulario
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            formData.append('_token', csrfToken);
            $.ajax({
                url: '{{ route("Admision_SubirDocumento") }}',  // La ruta a la que enviarás los datos
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken  // Asegúrate de enviar el token en los encabezados
                },
                data: formData,
                contentType: false,  // Impide que jQuery configure el tipo de contenido
                processData: false,
                success: function(response) {
                    console.log(response);
                    $('#documentoModal').modal('hide');
                    traeDocumentos();
                    traeBitacora();
                },
                error: function(xhr, status, error) {
                    // Manejo de errores si la solicitud falla
                    $('#responseMessage').html('<p style="color:red;">Ocurrió un error: ' + error + '</p>');
                }
            });
        });

        // $('#FormaGeneral').on('submit', function(event) {
        //     event.preventDefault(); // Evita el envío normal del formulario
        //     var formData = new FormData(this); // Serializa los datos del formulario
        //     var csrfToken = $('meta[name="csrf-token"]').attr('content');
        //     var admision_id = document.getElementById('admision_id').value;
        //     formData.append('_token', csrfToken);
        //     console.log(formData);
            
        // });


        /*=========================================================================================
        Agregar linea de cargos
        =========================================================================================*/
        function agregar_cargo(){
            var aseguradora_id = document.getElementById('aseguradora_id').value;
            var html = '';
            html += '<tr>';
            html += '<td width="250px">';
            html += '<select id="cargos['+nLinea+'][producto_id]" name="cargos['+nLinea+'][producto_id]" class="form-control classproducto" data-required="true" onchange="actualizarMedida('+nLinea+')">';
            html += '<option value="">Seleccionar...</option>';
            @foreach($pProductos as $producto)
            html += '<option value="{{$producto->id}}">{{$producto->descripcion}}</option>';
            @endforeach
            html += '</select>';
            html += '</td>';
            html += '<td width="250px">';
            html += '<input type="text" class="form-control classdescripcion" id="cargos['+nLinea+'][descripcion]" name="cargos['+nLinea+'][descripcion]" />';
            html += '</td">';
            html += '<td width="125px">';
            html += '<select id="cargos['+nLinea+'][medida_id]" name="cargos['+nLinea+'][medida_id]" class="form-control classmedida" data-required="true">';
            html += '<option value="">Seleccionar...</option>';
            html += '</select>';
            html += '</td>';
            html += '<td width="75px">';
            html += '<input type="number" step="1" min="1" class="form-control classnumero numero" id="cargos['+nLinea+'][cantidad]" name="cargos['+nLinea+'][cantidad]" value="1" onchange="fnCalculo('+nLinea+');" />';
            html += '</td">';
            html += '<td width="125px">';
            html += '<input type="number" step="any" min="1" class="form-control classprecio numero" id="cargos['+nLinea+'][precio]" name="cargos['+nLinea+'][precio]" min="0.01" step="any" onchange="fnCalculo('+nLinea+');" />';
            html += '</td">';
            html += '<td width="75px">';
            html += '<input type="number" step="any" min="1" class="form-control classtotal numero" id="cargos['+nLinea+'][total]" name="cargos['+nLinea+'][total]" readonly/>';
            html += '</td">';
            if (admisionEstado != 1) {
                html += '<td width="35px"><a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4 eliminar" id="btn_eliminar_registro"><i class="fas fa-trash-alt"></i></a></td>';
            }else{
                html += '<td width="35px"></td>';
            }
            html += '</tr>';
            $('#tblCargos tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLinea += 1;
        }

        function eliminar()
        {
            var whichtr = $(this).closest("tr");
            whichtr.remove(); 
            sumarValores();
            return false;
        }

        function actualizarMedida(linea){
            var x = document.getElementById("cargos["+linea+"][producto_id]").selectedIndex;
            var y = document.getElementById("cargos["+linea+"][producto_id]").options;
            var producto_id = y[x].value;              
            $.ajax({
                url: "{{ route('descripcion') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}", cod: producto_id},
                success: function(response){
                    document.getElementById("cargos["+linea+"][descripcion]").value = response;
                },
                error: function(error){
                    console.log(error);
                }
            });

            $.ajax({
                url: "{{ route('trae_medidas_x_producto') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}", producto_id: producto_id},
                success: function(response){
                    if (response.length == 0) {
                        let dropdown = document.getElementById("cargos["+linea+"][medida_id]");
                        dropdown.length = 0;
                        let option;
                        option = document.createElement('option');
                        option.text = 'Unidad';
                        option.value = 1;
                        dropdown.add(option);
                    }else{
                        let dropdown = document.getElementById("cargos["+linea+"][medida_id]");
                        dropdown.length = 0;
                        let option;
                        option = document.createElement('option');
                        option.text = 'Seleccionar ....';
                        option.value = '';
                        for (let i = 0; i < response.length; i++) {
                            option = document.createElement('option');
                            option.text = response[i].unidad_medida_descripcion;
                            option.value = response[i].unidad_medida_id;
                            dropdown.add(option);
                        }
                    }
                },
                error: function(error){
                    console.log(error);
                } 
            });
        }

        function sumarValores() {
            let total = 0;
          
            // Obtener todos los inputs con la clase .input-cantidad
            let inputs = document.querySelectorAll('.classtotal');
          
            // Recorrer los inputs y sumar sus valores
            inputs.forEach(function(input) {
                total += parseFloat(input.value) || 0;  // parseFloat convierte el valor a número, y si no es un número, usamos 0
            });

            // Mostrar el resultado en el span con id "total"
            document.getElementById('resumenTotal').value = formatter.format(total);
            // document.getElementById('resumenTotal').textContent = formatter.format(total);
        }


        function fnCalculo(linea){
            var cantidad = document.getElementById("cargos["+linea+"][cantidad]").value;
            var precio   = document.getElementById("cargos["+linea+"][precio]").value;
            var total    = (cantidad * precio).toFixed(2);
            document.getElementById("cargos["+linea+"][total]").value = total;

            sumarValores();
        }

        //===================================================================
        // Cerrar admisión
        //===================================================================
        function cerrarAdmision(id){
            Swal.fire({
                title: 'Confirmación',
                text: 'Al Cerrar la admisión, esta no podrá tener mas movimientos, Favor confirme',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745', // Color success de AdminLTE
                cancelButtonColor: '#dc3545',  // Color danger de AdminLTE
                confirmButtonText: 'Si Cerrar',
                cancelButtonText: 'No',
                allowEscapeKey: true,
                reverseButtons: true // Opcional: pone el botón de confirmar a la derecha
            }).then((result) => {
                /* result.isConfirmed será verdadero si el usuario hizo clic en "Si Cerrar" */
                if (result.isConfirmed) { 
                    $.ajax({
                        url: "{{ route('cerrar_admision') }}",
                        type: "POST",
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}", 
                            admision_id: id
                        },
                        success: function(response){
                            Swal.fire({
                                title: 'Trabajo Finalizado !!!',
                                text: response.message,
                                icon: response.type // Asegúrate que tu backend envíe 'success', 'error', etc.
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(error){
                            console.log(error);
                            Swal.fire('Error', 'No se pudo procesar la solicitud', 'error');
                        }
                    });
                } 
            });
        }

        //===================================================================
        // Abrir admisión
        //===================================================================
        function abrirAdmision(id){
            $("#reapertura").modal('show');
        }

        //===================================================================
        // Cobrar admisión
        //===================================================================
        function cobrarAdmision(admision){
            window.location.href = "{{ route('nueva_factura', '') }}/" + admision;
        }
    </script>
@endsection