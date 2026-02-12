@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.css')}}">
    <style type="text/css">
        .numero{
            text-align: right;
        }
        .table-responsive {
            max-width: 100%; /* Ajusta el ancho según tus necesidades */
            overflow-x: auto; /* Permite el desplazamiento horizontal */
        }
        .dataTables_wrapper .row {
            display: flex;
            align-items: center; /* Alinea verticalmente los elementos */
            justify-content: flex-start; /* Ajusta los elementos a la izquierda */
        }

        .dataTables_wrapper .row .col-auto {
            display: flex;
            justify-content: flex-start; /* Alinea los elementos dentro de las columnas */
        }

        .dataTables_wrapper .row .col {
            display: flex;
            justify-content: flex-start;
        }

        .control-sidebar {
            width: 500px; /* Ajusta el tamaño de la barra */
        }

        .control-sidebar-open {
            right: 0 !important;
        }

        /* Ajuste inicial */
        .content-wrapper {
            transition: margin-right 0.3s ease-in-out;
            margin-right: 0;
        }

        /* Cuando la barra derecha está abierta */
        .content-wrapper.push-content {
            margin-right: 300px; /* Mueve el contenido a la izquierda */
        }

        /* Ajuste de la barra derecha */
        .control-sidebar {
            width: 300px; /* Tamaño de la barra lateral derecha */
            transition: right 0.3s ease-in-out;
            right: -300px; /* Oculta la barra lateral */
            position: fixed;
            top: 0;
            height: 100vh;
        }

        /* Cuando la barra derecha está visible */
        .control-sidebar.control-sidebar-open {
            right: 0;
        }

        .nav-pills .nav-link.active,
        .show>.nav-pills .nav-link{
            background: #7FB3D5 !important;
            color: #000000 !important;
        }
        .flex-column .nav-link.active,
        .show>.nav-pills .nav-link{
            background: #b9aca2 !important;
            color: #000000 !important;
        }



    </style>
@endsection
@section('title', 'Seguimiento Medico a Admisiones')
@section('content_header')
    <div class="row">
        <div class="col-1 offset-11" style="text-align: right;">
            <button id="toggleRightSidebar" class="btn btn-xs rounded-circle elevation-4" data-widget="control-sidebar" data-slide="true">
                <i class="fas fa-cog"></i>
            </button>
        </div>
    </div>
@endsection
@section('content')
    <div class="container-fluid">
        <input type="hidden" id="admision_id" name="admision_id" class="form-control input-group input-group-sm" value="{{ $admision_id }}">
        <div class="card">
            <div class="card-header" style="background-color: #E1E8ED;">
                <div class="bg-default clearfix">
                    <div class="row">
                        <div class="col-lg-9 col-sm-12">
                            <h6>{{ $encabezado->nombre_completo }}</h6>
                        </div>
                        <div class="col-lg-1 col-sm-12" style="text-align: right;">
                            <h6 id="EdadHtml"></h6>
                        </div>
                        <div class="col-lg-2 col-sm-12" style="text-align: right;">
                            <a href="#" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Grabar"><i class="fas fa-save"></i></a>
                            <a class="btn btn-xs btn-outline-info rounded-circle elevation-4" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1" title="Datos Generales"><i class="fas fa-chevron-down"></i>
                            </a>
                            <!-- <a href="#" id="informe" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" target="_blank" title="Informe Medico"><i class="fas fa-file-pdf"></i></a> -->
                            <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Datos complementarios de Admisión -->
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="collapse multi-collapse" id="multiCollapseExample1">
                            <div class="card card-body">
                                <div class="row">
                                    <div class="input-group input-group-sm col-md-4 mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" id="basic-addon1"># Admisión</label>
                                        </div>
                                        <input type="text" class="form-control alineacion_deracha" aria-label="Username" aria-describedby="basic-addon1" id="admision" name="admision" value="{{ $encabezado->admision_no }}" readonly>
                                    </div>
                                    <div class="input-group input-group-sm col-md-4 mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" id="basic-addon1">Hospital</label>
                                        </div>
                                        <input type="text" class="form-control alineacion_deracha" aria-label="Username" aria-describedby="basic-addon1" id="admision_hospital" name="admision_hospital" value="{{ $encabezado->hospital_nombre }}" readonly>
                                    </div>
                                    <div class="input-group input-group-sm col-md-4 mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" id="basic-addon1">Aseguradora</label>
                                        </div>
                                        <input type="text" class="form-control alineacion_deracha" aria-label="Username" aria-describedby="basic-addon1" id="admision_aseguradora" name="admision_aseguradora" value="{{ $encabezado->aseguradora_nombre }}" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group input-group-sm col-md-4 mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" id="basic-addon1">Fecha</label>
                                        </div>
                                        <input type="date" class="form-control alineacion_deracha" aria-label="Username" aria-describedby="basic-addon1" id="admision_fecha" name="admision_fecha" value="{{ $encabezado->fecha }}" readonly>
                                    </div>
                                    <div class="input-group input-group-sm col-md-4 mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" id="basic-addon1">Médico</label>
                                        </div>
                                        <input type="text" class="form-control alineacion_deracha" aria-label="Username" aria-describedby="basic-addon1" id="admision_medico" name="admision_medico" value="{{ $encabezado->medico_nombre }}" readonly>
                                    </div>
                                    <div class="input-group input-group-sm col-md-4 mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" id="basic-addon1">Póliza</label>
                                        </div>
                                        <input type="text" class="form-control alineacion_deracha" aria-label="Username" aria-describedby="basic-addon1" id="admision_poliza" name="admision_poliza" value="{{ $encabezado->poliza_no }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Datos complementarios de Admisión -->
                <!-- tabs principales -->
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-pills nav-justified">
                            <li class="nav-item inhabilitar"  id="tabConsulta">
                                <a class="nav-link active" href="#consulta" id="linkConsulta" data-toggle="tab" onclick="SeleccionarTab('C');">Consulta</a>
                            </li>
                            <li class="nav-item inhabilitar" id="tabHospitalizacion">
                                <a class="nav-link" href="#hospitalizacion" id="linkHospitalizacion" data-toggle="tab" onclick="SeleccionarTab('H');">Hospitalización</a>
                            </li>
                            <li class="nav-item inhabilitar" id="tabProcedimiento">
                                <a class="nav-link" href="#procedimiento" id="linkProcedimiento" data-toggle="tab" onclick="SeleccionarTab('P');">Procedimiento</a>
                            </li>
                        </ul>
                        <div class="tab-content"  style="background-color: #E8EAEC;">
                            <div class="tab-pane active" id="consulta">
                                <!-- Detalle de consulta -->
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-2 col-sm-12">
                                                <ul class="nav flex-column" style="font-size: 12px; !important">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" href="#vitales" data-toggle="tab">Vitales</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#subjetivo" data-toggle="tab">Subjetivos</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#objetivo" data-toggle="tab">Objetivos</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#impresion_clinica" data-toggle="tab">Impresión Clinica</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#plan" data-toggle="tab">Plan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#tratamiento" data-toggle="tab">Tratamiento</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-10 col-sm-12">
                                                <div class="tab-content">
                                                    <!-- Signos Vitales -->
                                                    <div class="active tab-pane" id="vitales">
                                                        <div class="row">
                                                            <div class="col-md-8 offset-md-1">
                                                                <div class="row">
                                                                    <div class="input-group mb-4 input-group-sm col-md-4">
                                                                        <div class="input-group-prepend">
                                                                            <label class="input-group-text">Peso</label>
                                                                        </div>
                                                                        <input type="text" id="peso" name="peso" class="form-control" style="text-align: right;" onchange="fn_calcula_bmi(); return false;">
                                                                    </div>
                                                                    <div class="input-group mb-4 input-group-sm col-md-4">
                                                                        <div class="input-group-prepend">
                                                                            <label class="input-group-text">Talla</label>
                                                                        </div>
                                                                        <input type="text" id="talla" name="talla" class="form-control" style="text-align: right;" onchange="fn_calcula_bmi(); return false;">
                                                                    </div>
                                                                    <div class="input-group mb-4 input-group-sm col-md-4">
                                                                        <div class="input-group-prepend">
                                                                            <label class="input-group-text">Pulso</label>
                                                                        </div>
                                                                        <input type="text" id="pulso" name="pulso" class="form-control" style="text-align: right;">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="input-group mb-4 input-group-sm col-md-4">
                                                                        <div class="input-group-prepend">
                                                                            <label class="input-group-text">Temperatura</label>
                                                                        </div>
                                                                        <input type="text" id="temperatura" name="temperatura" class="form-control" style="text-align: right;">
                                                                    </div>
                                                                    <div class="input-group mb-4 input-group-sm col-md-4">
                                                                        <div class="input-group-prepend">
                                                                            <label class="input-group-text">Respiraciones</label>
                                                                        </div>
                                                                        <input type="text" step="1" min="0" id="respiracion" name="respiracion" class="form-control" style="text-align: right;" placeholder="00">
                                                                    </div>
                                                                    <div class="input-group mb-4 input-group-sm col-md-4">
                                                                        <div class="input-group-prepend">
                                                                            <label class="input-group-text">Presion Arterial</label>
                                                                        </div>
                                                                        <input type="text" id="presion" name="presion" class="form-control" style="text-align: right;" placeholder="000 / 000">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="card text-center">
                                                                            <div class="card-header" style="background-color: #b9aca2">
                                                                                BMI
                                                                            </div>
                                                                            <div class="card-body bg-light">
                                                                                <input type="hidden" id="bmi" name="bmi" class="form-control" style="text-align: right;" value="0.00">
                                                                                <div id="bmi_show"><b>__.__</b></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /Signos Vitales -->
                                                    <!-- Datos Subjetivos -->
                                                    <div class="tab-pane" id="subjetivo">
                                                        <div class="row text-center">
                                                            <div class="form-group col-md-10 offset-md-1">
                                                                <label for="consulta_subjetivo">Descripción</label>
                                                                <textarea class="form-control form-control-sm" id="consulta_subjetivo" name="consulta_subjetivo" rows="8" maxlength="975" style="text-align: justify;" placeholder="Se obtienen a través de la comunicación directa con el paciente"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /Datos Subjetivos -->
                                                    <!-- Datos Objetivos -->
                                                    <div class="tab-pane" id="objetivo">
                                                        <div class="row text-center">
                                                            <div class="form-group col-md-10 offset-md-1">
                                                                <label for="consulta_objetivo">Descripción</label>
                                                                <textarea class="form-control form-control-sm" id="consulta_objetivo" name="consulta_objetivo" rows="8" placeholder="Se basan en pruebas y exámenes médicos cuantificables, como análisis de sangre, radiografías o mediciones de presión arterial"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /Datos Objetivos -->
                                                    <!-- Impresion Clinica -->
                                                    <div class="tab-pane" id="impresion_clinica">
                                                        <div class="row text-center">
                                                            <div class="form-group col-md-10 offset-md-1">
                                                                <label for="consulta_impresion_clinica">Descripción</label>
                                                                <textarea class="form-control form-control-sm" id="consulta_impresion_clinica" name="consulta_impresion_clinica" rows="8" placeholder="La impresión clínica global es una medida subjetiva de la gravedad de los síntomas y de la eficacia del tratamiento realizada por su médico a partir de su experiencia."></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /Impresion Clinica -->
                                                    <!-- Plan -->
                                                    <div class="tab-pane" id="plan">
                                                        <div class="row text-center">
                                                            <div class="form-group col-md-10 offset-md-1">
                                                                <label for="consulta_plan">Descripción</label>
                                                                <textarea class="form-control form-control-sm" id="consulta_plan" name="consulta_plan" rows="8" placeholder="Plan detallado que se entrega a un paciente después de terminar el tratamiento; contiene un resumen del tratamiento y recomendaciones para el seguimiento de la atención."></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /Plan -->
                                                    <!-- Tratamiento -->
                                                    <div class="tab-pane" id="tratamiento">
                                                        <div class="row">
                                                            <div class="form-group col-md-10 offset-md-1">
                                                                <label for="consulta_tratamiento"></label>
                                                                <textarea class="form-control form-control-sm" id="consulta_tratamiento" name="consulta_tratamiento" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-1 offset-10 mb-1" style="text-align: right;">
                                                                <a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="agregarFila(); return false;"><i class="fas fa-plus-circle"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="row">'
                                                            <div class="col-lg-10 col-sm-10 offset-lg-1 offset-sm-1 mb-1">
                                                                <table class="table table-sm table-stripped" id="tblReceta" style="font-size:12px;">
                                                                    <thead>
                                                                        <tr style="text-align: center;">
                                                                            <th>Medicamento</th>
                                                                            <th>Dosís</th>
                                                                            <th>Observaciones</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /Tratamiento -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="hospitalizacion">
                                b
                            </div>
                            <div class="tab-pane" id="procedimiento">
                                c
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- barra lateral derecha -->
        <aside class="control-sidebar">
            <div class="p-3">
                <div class="card mb-1">
                    <div class="card-header text-center" style="background-color: #b9aca2">Admisiones</div>
                    <div class="card-body">
                        <table class="table table-sm table-striped text-center" id="tblListado">
                            <thead>
                                <tr style="font-size: 12px;"><th>Admisión</th><th>Fecha</th><th>Tipo</th></tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </aside>
        <!-- /barra lateral derecha --> -->
        <!-- barra lateral derecha -->
        <aside class="control-sidebar">
            <div class="p-3">
                <div class="card mb-1">
                    <div class="card-header text-center" style="background-color: #b9aca2">Admisiones</div>
                    <div class="card-body">
                        <table class="table table-sm table-striped text-center" id="tblListado">
                            <thead>
                                <tr style="font-size: 12px;"><th>Admisión</th><th>Fecha</th><th>Tipo</th></tr>
                            </thead>
                            <tbody>
                                @foreach($listado as $l)
                                    <tr style="font-size: 12px;">
                                        <td>
                                            <a href="#" onclick="actualizarPagina({{$l->id}}, 'X')" > {{ $l->admision_no }}</a>
                                        </td>
                                        <td>{{ $l->fecha }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </aside>
        <!-- /barra lateral derecha -->
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
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
        var nLinea = 0;


        document.getElementById('toggleRightSidebar').addEventListener('click', function () {
            let sidebar = document.querySelector('.control-sidebar');
            let content = document.querySelector('.content-wrapper');

            // Alternar la clase para abrir/cerrar la barra lateral derecha
            sidebar.classList.toggle('control-sidebar-open');
            content.classList.toggle('push-content');
        });

        //=======================================================================
        // Calcular indice de masa corporal
        //=======================================================================

        function fn_calcula_bmi(){
            var peso  = document.getElementById('peso').value;
            var talla = document.getElementById('talla').value;
            var bmi   = 0;
            if (peso != '' ||talla != '') {
                if (peso > 0 && talla > 0) {
                    peso = peso / 2.2;
                    talla *= talla;
                    bmi = (peso / talla).toFixed(2);
                    //console.log(peso+' - '+talla+' - '+bmi);
                    document.getElementById('bmi').value = peso / talla;
                    document.getElementById('bmi_show').innerHTML = '<h3>'+bmi+'</h3>';
                }else{
                    document.getElementById('bmi').value = 0;
                    document.getElementById('bmi_show').innerHTML = '<h3>'+0+'</h3>';    
                }
            }else{
                document.getElementById('bmi').value = 0;
                document.getElementById('bmi_show').innerHTML = '<h3>'+0+'</h3>';
            }
        }

        //========================================================================
        // Agregar una nueva fila a la tabla
        //========================================================================

        function agregarFila(){
            const medicamentos = @json($medicamentos);
            html = '';
            html += '<tr>'
            html += '<input type="hidden" class="form-control" id="medicamentos['+nLinea+'][id]" name="medicamentos['+nLinea+'][id]" value="'+nLinea+'">'
            html += '<td style="width: 30%;">'
            html += '<select class="custom-select custom-select-sm select2 select2bs4" id="medicamentos['+nLinea+'][medicamento_id]" name="medicamentos['+nLinea+'][medicamento_id]" onchange="actualizarMedidas('+nLinea+');">'
            html += '<option value="">Seleccionar....</option>'
            for (var i = 0; i < medicamentos.length; i++) {
                html += '<option value="'+medicamentos[i]['id']+'">'+medicamentos[i]['descripcion']+'</option>'
            }
            html += '</select>'
            html += '</td>'
            html += '<td style="width: 20%;">'
            html += '<select class="custom-select custom-select-sm select2 select2bs4" id="medicamentos['+nLinea+'][dosis_id]" name="medicamentos['+nLinea+'][dosis_id]" onchange="copiarDosis('+nLinea+');">'
            html += '<option value="">Seleccionar....</option>'
            html += '</select>'
            html += '</td>'
            html += '<td style="width: 40%;">'
            html += '<input type="text" class="form-control form-control-sm" placeholder="" id="medicamentos['+nLinea+'][consulta_observaciones]" name="medicamentos['+nLinea+'][consulta_observaciones]" required">'
            html += '</td>'
            html += '<td style="width: 10%;">'
            html += '<a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Eliminar Artículo" onclick="eliminarFila(this)"><i class="fa fa-trash-alt"></i></a>'
            html += '</td>'
            html += '</tr>';
            //document.getElementById("tblDetalle").insertRow(-1).innerHTML = html;
            $("#tblReceta > tbody").append(html);
            nLinea += 1;
            $('.select2').select2()
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        }

        //========================================================================
        // actualizar unidad de medida en base a producto seleccionado
        //========================================================================
        function actualizarMedidas(id){
            
            var medicamento_id = document.getElementById("medicamentos["+id+"][medicamento_id]").value;
            var dosis          = document.getElementById("medicamentos["+id+"][dosis_id]"); 
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('trae_dosis_x_producto') }}",
                method: "POST",
                data: {producto_id: medicamento_id},
                success: function(response){
                    // html += '<option value="">Seleccionar....</option>'
                    // var el = document.createElement("option");
                    // el.textContent = 'Seleccionar...';
                    // el.value = '';
                    // dosis.appendChild(el);
                    for (var i = 0; i < response.length; i++) {
                        var opt = response.length;
                        var el = document.createElement("option");
                        el.textContent = response[i]['unidad_medida_descripcion'];
                        el.value = response[i]['unidad_medida_id'];
                        dosis.appendChild(el);
                    }
                },
                error: function(error){
                    console.log(error);
                }       
            });
        }

        function copiarDosis(x){
            // alert(x);
            // var medicamento_id = $("#medicamentos["+x+"][medicamento_id]").val();
            var selector = "[name='medicamentos[" + x + "][medicamento_id]']";
            var medicamento_id = $(selector).val();
            var medicamento_descripcion = $(selector).find('option:selected').text();
            var selector1 = "[name='medicamentos[" + x + "][dosis_id]']";
            var dosis_id = $(selector1).val();
            var dosis_descripcion = $(selector1).find('option:selected').text();
            // var x = document.getElementById("medicamento_id").selectedIndex;
            // var y = document.getElementById("tratamiento_medicamento_id").options;
            // var medicamento_descripcion = y[x].text;
            // var medicamento_id = $('#tratamiento_medicamento_id').val();
            // var dosisId = $('#tratamiento_dosis_id').val();

            if(dosis_id == ''){
                    alert('Seleccione una dosis.');
                    return;
            }else{
                $.ajax({
                    url: "{{ route('receta_descripcion') }}",
                    type: "POST",
                    async: true,
                    data: {"_token" : "{{ csrf_token() }}", 
                           medicamento_id : medicamento_id,
                           dosis_id       : dosis_id},
                    success: function(response){
                        console.log(response.descripcion);
                        if (response !== 'null') {
                            var selector2 = "[name='medicamentos[" + x + "][consulta_observaciones]']";
                            $(selector2).val(medicamento_descripcion+' - '+response.descripcion);
                            // var anterior = document.getElementById("consulta_tratamiento").value;
                            // $("#consulta_tratamiento").summernote("code", anterior+' '+medicamento_descripcion+' '+info.descripcion);

                            // var textarea = document.getElementById('consulta_observaciones');
                            // var textoNuevo = info.descripcion;
                            // textarea.value += medicamento_descripcion+' - '+textoNuevo;
                        }
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }

        }

        //========================================================================
        // eliminar fila especifica de la tabla
        //========================================================================
        function eliminarFila(row){
            var d = row.parentNode.parentNode.rowIndex; 
            document.getElementById('tblReceta').deleteRow(d);
            total_tabla();
        }

    </script>
@endsection