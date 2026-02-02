@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('assets/jquery-step-master/css/main.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/jquery-step-master/css/jquery.steps.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
    <style>
        .wizard > .content {
            min-height: 20em !important;
        }
        .wizard .content > .body {
            width: 100%;
            height: auto;
            padding: 15px;
            position: relative;
        }
    </style>
@endsection
@section('title', 'Protocolos')

@section('content_header')
  <h3>Nuevo Protocolo</h3>
@endsection

@section('content')
    <form id="example-form" action="#">
        <div id="wizard">
            <h4 class="col-md-2">Datos Generales</h4>
            <article>
                <div class="card card-secondary">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="input-group mb-2 col-md-10 offset-md-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="paciente_id">Paciente</label>
                                        </div>
                                        <select class="custom-select select2 select2bs4" id="paciente_id"  name="paciente_id" onchange="fn_complemento_paciente(); return false;" autofocus required>
                                            <option value="">Seleccionar...</option>
                                            @foreach($pacientes as $p)
                                                <option value="{{ $p->id}}">{{ $p->nombre_completo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <input type="hidden" id="fecha_nacimiento" name="fecha_nacimiento">
                                    <input type="hidden" id="edad" name="edad">
                                    <div class="input-group mb-2 col-md-7 offset-md-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Fch. Nacimiento</label>
                                        </div>
                                        <input type="date" class="form-control" placeholder="DD/MM/AAAA" id="showfecha_nacimiento" name="showfecha_nacimiento" value="{{ old('showfecha_nacimiento')}}" disabled>
                                    </div>
                                    <div class="input-group mb-2 col-md-3">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Edad</label>
                                        </div>
                                        <input type="number" class="form-control" id="showedad" name="showedad" value="{{ old('showedad')}}" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group mb-2 col-md-10 offset-md-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="diagnostico_id">Diagnostico</label>
                                        </div>
                                        <select class="custom-select select2 select2bs4" id="diagnostico_id"  name="diagnostico_id" required>
                                            <option value="">Seleccionar...</option>
                                            @foreach($diagnosticos as $d)
                                                <option value="{{ $d->id}}">{{ $d->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group mb-2 col-md-10 offset-md-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="cuerpo_parte_id">Ubicación</label>
                                        </div>
                                        <select class="custom-select select2 select2bs4" id="cuerpo_parte_id"  name="cuerpo_parte_id" required>
                                            <option value="">Seleccionar...</option>
                                            @foreach($cuerpo_partes as $cp)
                                                <option value="{{ $cp->id}}">{{ $cp->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group mb-2 col-md-10 offset-md-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="hospital_id">Tratado en</label>
                                        </div>
                                        <select class="custom-select select2 select2bs4" id="hospital_id"  name="hospital_id" required>
                                            <option value="">Seleccionar...</option>
                                            @foreach($hospitales as $h)
                                                <option value="{{ $h->id}}">{{ $h->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group mb-2 col-md-10 offset-md-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="medico_id">Medico</label>
                                        </div>
                                        <select class="custom-select select2 select2bs4" id="medico_id"  name="medico_id">
                                            <option value="">Seleccionar...</option>
                                            @foreach($medicos as $m)
                                                <option value="{{ $m->id}}" @if($m->principal == 'S') selected @endif>{{ $m->nombre_completo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group mb-2 col-md-10 offset-md-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="aseguradora_id">Aseguradora</label>
                                        </div>
                                        <select class="custom-select select2 select2bs4" id="aseguradora_id" name="aseguradora_id">
                                            <option value="0">Sin Aseguradora...</option>
                                            @foreach($aseguradoras as $a)
                                                <option value="{{ $a->id}}">{{ $a->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group mb-2 col-md-10 offset-md-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="poliza_no">Número de Póliza</label>
                                        </div>
                                        <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" id="poliza_no" name="poliza_no" value="0" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="input-group mb-2 col-md-6">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Fch. Inicio</label>
                                        </div>
                                        <input type="date" class="form-control text-center" placeholder="DD/MM/AAAA" aria-label="Username" aria-describedby="basic-addon1" id="fecha_inicio" name="fecha_inicio"  value="{{ old('fecha_inicio')}}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group mb-2 col-md-4">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Cíclos</label>
                                        </div>
                                        <input type="number" class="form-control" placeholder="0" aria-label="Username" aria-describedby="basic-addon1" id="ciclos" name="ciclos" min="1" value="{{ old('ciclos')}}" style="text-align: right;" required>
                                    </div>
                                    <div class="input-group mb-2 col-md-4">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Frecuencia</label>
                                        </div>
                                        <input type="number" class="form-control" placeholder="0" aria-label="Username" aria-describedby="basic-addon1" id="frecuencia" name="frecuencia" min="1" value="{{ old('frecuencia')}}" style="text-align: right;" onchange="LlenarTablaAgenda(); return false;" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group clearfix">
                                            <label>Proveedor de Medicamentos</label>&nbsp;
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="clinica" name="proveedor_medicamento" value="H" checked>
                                                <label for="clinica">Clinica</label>
                                            </div>
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="aseguradora" name="proveedor_medicamento" value="A">
                                                <label for="aseguradora">Aseguradora</label>
                                            </div>
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="paciente" name="proveedor_medicamento" value="P">
                                                <label for="paciente">Paciente</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group clearfix">
                                            <label>Inmunoterapia</label>&nbsp;
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="no" name="inmunoterapia" value="N" checked>
                                                <label for="no">No</label>
                                            </div>                                    
                                            &nbsp;
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="si" name="inmunoterapia" value="S">
                                                <label for="si">Sí</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group clearfix">
                                            <label>Tipo de Tratamiento</label>&nbsp;
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="ambulatorio" name="tipo_tratamiento" value="A" checked>
                                                <label for="ambulatorio">Ambulatorio</label>
                                            </div>
                                            &nbsp;
                                            <div class="icheck-primary d-inline">
                                                <input type="radio" id="interno" name="tipo_tratamiento" value="I">
                                                <label for="interno">Interno</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <h4>Productos</h4>
            <article>
                <div class="card card-secondary">
                    <div class="card-body">
                        <table class="table table-sm table-striped record_table text-center" id="tblProductos">
                            <thead><tr><th colspan="3">Producto / Servicio</th><th colspan="5">Unidad de Medida</th><th colspan="1">Cantidad</th><th colspan="2">Precio Unitario</th><th colspan="2">Total Línea</th></tr></thead>
                            <tbody>
                                @foreach($productos as $p)
                                    <tr>
                                        <td colspan="3">
                                            <input type="hidden" class="form-control" id="productos[{{$p->id}}][id]" name="productos[{{$p->id}}][id]" value="{{$p->id}}">
                                            {{ $p->descripcion }} {{ $p->id }}
                                        </td>
                                        <td colspan="5">
                                            <select class="custom-select form-control select2 select2bs4" id="productos[{{$p->id}}][medida_id]" name="productos[{{$p->id}}][medida_id]" @if($p->clasificacion != 'PROD') disabled @endif style="width: 100%;">
                                                @if($p->clasificacion != 'PROD')
                                                    <option value="1">Unidad</option>
                                                @else
                                                    <option value="">Seleccionar...</option>
                                                    @foreach($medidas as $m)
                                                        @if($m->producto_id == $p->id)
                                                            <option value="{{ $m->id }}">{{ $m->descripcion }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td colspan="1">
                                            <input type="number" class="form-control" id="productos[{{$p->id}}][cantidad]" name="productos[{{$p->id}}][cantidad]" style="text-align: right;" min="1" placeholder="0.00" onchange="actualizarTotal({{ $p->id }}); return false;" style="width: 10px;">
                                        </td>
                                        <td colspan="2">
                                            <input type="number" class="form-control" id="productos[{{$p->id}}][precio_unitario]" name="productos[{{$p->id}}][precio_unitario]" style="text-align: right;" step="0.01" placeholder="0.00" onchange="actualizarTotal({{ $p->id }}); return false;">
                                        </td>
                                        <td colspan="2">
                                            <input type="number" class="form-control" id="productos[{{$p->id}}][precio_total]" name="productos[{{$p->id}}][precio_total]" style="text-align: right;" step="0.01" placeholder="0.00" readonly>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="5"></td>
                                    <td colspan="1"></td>
                                    <td colspan="2"><h5>Total</h5></td>
                                    <td colspan="2" style="text-align: right;"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </article>
            <h4>Metastasis</h4>
            <article>
                <div class="card card-secondary">
                    <div class="card-body">
                        <table class="table table-sm table-striped record_table text-center" id="tblmetastasis">
                            <thead><tr><th></th><th>Descripción</th></tr></thead>
                            <tbody>
                                @foreach($cuerpo_partes->chunk(5) as $cp)
                                    <tr>
                                        @foreach($cp as $x)
                                            <!--<td onclick="seleccionar({{ $x->id }})"> -->
                                            <td>
                                                <input type="hidden" class="form-control" id="metastasis[{{$x->id}}][id]" name="metastasis[{{$x->id}}][id]" value="{{$x->id}}">
                                                <input type="checkbox" id="chkm{{$x->id}}" name="metastasis[{{$x->id}}][checked]">
                                            <td>{{ $x->nombre }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>            
            <h4>Agenda</h4>
            <article>
                <div class="card card-secondary">
                    <div class="card-body">
                        <table class="table table-sm table-striped record_table text-center" id="tblAgenda">
                            <thead>
                                <tr>
                                    <th colspan="1">No. Ciclo</th>
                                    <th colspan="2">Sala</th>
                                    <th colspan="2">Fecha</th>
                                    <th colspan="2">Horario</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>
        </div>       
    </form>
@endsection
@section('js')
    <script src="{{ asset('assets/jquery-step-master/js/jquery-1.9.1.min.js')}}"></script>
    <script src="{{ asset('assets/jquery-step-master/js/jquery.validate.js')}}"></script>
    <script src="{{ asset('assets/jquery-step-master/js/jquery.steps.js')}}"></script>
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{ asset('assets/adminlte/plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{ asset('assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
    @if(Session::has('success'))
        <script>
            swal("Trabajo Finalizado", "{!! Session::get('success') !!}", "success")
        </script>
    @endif
    <script>
        var temp_metastasis_db = [];
        localStorage.clear(temp_metastasis_db);

        //========================================================================
        // inicializar librerias
        //========================================================================
        $(function () {
            $('.select2').select2()
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        });

        //========================================================================
        // al cargar la pagina trae las salas por usuario
        //========================================================================
        window.addEventListener('load', function(){
            var salas_db = [];
            localStorage.clear(salas_db);
            $.ajax({
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('trae_salas') }}",
                method: "POST",
                success: function(response){
                    for (var i = 0; i < response.length; i++) {
                        var linea = {
                            sala_id     : response[i]['id'],
                            sala_nombre : response[i]['sala_nombre']
                        };    
                        if(!localStorage.salas_db){
                            localStorage.salas_db = JSON.stringify([linea]);
                        }
                        else{
                            var salas_db = JSON.parse(localStorage.salas_db);
                            salas_db.push(linea);
                            localStorage.salas_db = JSON.stringify(salas_db);
                        }
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });
        });

        //========================================================================
        // Confirmar salida de pantalla
        //========================================================================
        function confirma_salida(){
            swal({
                title: 'Confirmación',
                Swal.fire({

                title: 'Confirmación',

                text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?",

text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?",
                icon: 'warning',

                showCancelButton: true,

                confirmButtonClass: 'btn-success',

                cancelButtonClass: 'btn-danger',

                confirmButtonText: 'Si',

                cancelButtonText: 'No',

                closeOnConfirm: false,

                allowEscapeKey: true

                },

                function(isConfirm) {

                    if (isConfirm) { 

                        if (origen == 'P') {

                            window.location.href = "{{ route('pacientes') }}";

                        }

                        if (origen == 'A') {

                            window.location.href = "{{ route('nueva_agenda') }}";

                        }

                        // history.back();

                        

                    } 

                }

            );
                showCancelButton: true,
                confirmButtonClass: 'btn-success',
                cancelButtonClass: 'btn-danger',
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                closeOnConfirm: false,
                allowEscapeKey: true
                },
                function(isConfirm) {
                    if (isConfirm) { 
                        window.location.href = "{{ route('protocolos') }}";
                    } 
                }
            );
        }

        //========================================================================
        // funcion para llenar datos complementarios del paciente
        //========================================================================
        function fn_complemento_paciente(){
            var paciente_id = document.getElementById('paciente_id').value;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('trae_fecha_nacimiento') }}",
                method: "POST",
                data: {paciente_id: paciente_id},
                success: function(response){
                    var fecha = response.fecha_nacimiento;
                    var values = fecha.split("-");
                    var dia = values[2];
                    var mes = values[1];
                    var ano = values[0];
                    var anios = calculate_age(mes, dia, ano);
                    document.getElementById('fecha_nacimiento').value = fecha;
                    document.getElementById('showfecha_nacimiento').value = fecha;
                    document.getElementById('edad').value = anios;
                    document.getElementById('showedad').value = anios;
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        //========================================================================
        // calculo de edad del paciente
        //========================================================================
        function calculate_age(birth_month, birth_day, birth_year) {
            today_date  = new Date();
            today_year  = today_date.getFullYear();
            today_month = today_date.getMonth();
            today_day   = today_date.getDate();
            age = today_year - birth_year;

            if (today_month < (birth_month - 1)) {
                age--;
            }
            if (((birth_month - 1) == today_month) && (today_day < birth_day)) {
                age--;
            }
            return(age);
        }

        /*$("#wizard").steps({
            headerTag: "h2",
            bodyTag: "article",
            transitionEffect: "fade",
            labels: 
            {
                current: "Actual",
                pagination: "Paginación",
                finish: "Finalizar",
                next: "Siguiente",
                previous: "Anterior",
                loading: "Cargando ..."
            }
        });*/

        var form = $("#example-form");
        form.validate({
            errorPlacement: function errorPlacement(error, element) { element.before(error); },
            rules: {
                confirm: {
                    equalTo: "#password"
                }
            }
        });
        form.children("div").steps({
            headerTag: "h4",
            bodyTag: "article",
            transitionEffect: "slideLeft",
            labels: 
            {
                current: "Actual",
                pagination: "Paginación",
                finish: "Finalizar",
                next: "Siguiente",
                previous: "Anterior",
                loading: "Cargando ..."
            },
            onStepChanging: function (event, currentIndex, newIndex)
            {
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onFinishing: function (event, currentIndex)
            {
                form.validate().settings.ignore = ":disabled";
                /*tabla productos*/
                var filas = document.querySelectorAll("#tblProductos tbody tr");
                filas.forEach(function(e) {
                    var columnas = e.querySelectorAll("td");
                    var cantidad = 0;
                    
                    if (!isNaN(parseFloat($(columnas[2]).find('input').val()))) {
                        var producto  = parseFloat($(columnas[0]).find('input').val());
                        var dropdown  = document.getElementById("productos["+producto+"][medida_id]");
                        var medida    = dropdown.options[dropdown.selectedIndex].value;
                        var cantidad  = parseFloat($(columnas[2]).find('input').val());
                        var preciouni = parseFloat($(columnas[3]).find('input').val());
                        var preciotot = parseFloat($(columnas[4]).find('input').val());
                        var linea = {
                            producto_id : producto,
                            medida_id   : medida,
                            cantidad    : cantidad,
                            preciouni   : preciouni,
                            preciotot   : preciotot
                        };
                        if(!localStorage.productos_db){
                            localStorage.productos_db = JSON.stringify([linea]);
                        }
                        else{
                            var productos_db = JSON.parse(localStorage.productos_db);
                            productos_db.push(linea);
                            localStorage.productos_db = JSON.stringify(productos_db);
                        }
                    }
                });
                /*/tabla productos*/
                /*tabla metastasis*/
                var filas = document.querySelectorAll("#tblmetastasis tbody tr");
                filas.forEach(function(e) {
                    var columnas = e.querySelectorAll("td");
                    for (var i = 0; i < 10; i+=2) {
                        if (!isNaN(parseFloat($(columnas[i]).find('input').val()))) {
                            var x  = parseFloat($(columnas[i]).find('input').val());
                            var marcado = document.getElementById("chkm"+x).checked;
                            if (marcado) {
                                var linea = {
                                    cuerpo_parte_id     : x
                                };    
                                if(!localStorage.metastasis_db){
                                    localStorage.metastasis_db = JSON.stringify([linea]);
                                }
                                else{
                                    var metastasis_db = JSON.parse(localStorage.metastasis_db);
                                    metastasis_db.push(linea);
                                    localStorage.metastasis_db = JSON.stringify(metastasis_db);
                                }
                            }
                        }
                    }
                });
                /*/tabla metastasis*/
                /*tabla agenda*/
                var filas = document.querySelectorAll("#tblAgenda tbody tr");
                filas.forEach(function(e) {
                    var columnas = e.querySelectorAll("td");
                    var cantidad = 0;
                    
                    if (!isNaN(parseFloat($(columnas[1]).find('input').val()))) {
                        var x  = parseFloat($(columnas[1]).find('input').val());
                        var fecha   = document.getElementById('agenda['+x+'][fecha_ciclo]').value;
                        var sala    = document.getElementById('agenda['+x+'][sala_id]').value;
                        var horario = document.getElementById('agenda['+x+'][horario]').value;
                        var linea = {
                            ciclo_no    : x,
                            fecha_ciclo : fecha,
                            sala_id     : sala,
                            horario     : horario
                        }
                        if(!localStorage.agenda_db){
                            localStorage.agenda_db = JSON.stringify([linea]);
                        }
                        else{
                            var agenda_db = JSON.parse(localStorage.agenda_db);
                            agenda_db.push(linea);
                            localStorage.agenda_db = JSON.stringify(agenda_db);
                        }                        
                    }
                });
                /*/tabla agenda*/
                return form.valid();
            },
            onFinished: function (event, currentIndex)
            {
                var productos_db      = JSON.parse(localStorage.productos_db);
                var metastasis_db     = JSON.parse(localStorage.metastasis_db);
                var agenda_db         = JSON.parse(localStorage.agenda_db);
                var paciente_id       = document.getElementById('paciente_id').value;
                var medico_id         = document.getElementById('medico_id').value;
                var fecha_nacimiento  = document.getElementById('fecha_nacimiento').value;
                var edad              = document.getElementById('edad').value;
                var diagnostico_id    = document.getElementById('diagnostico_id').value;
                var cuerpo_parte_id   = document.getElementById('cuerpo_parte_id').value;
                var hospital_id       = document.getElementById('hospital_id').value;
                var aseguradora_id    = document.getElementById('aseguradora_id').value;
                var poliza_no         = document.getElementById('poliza_no').value;
                const pm_seleccionado = document.querySelectorAll('input[name="proveedor_medicamento"]');
                const in_seleccionado = document.querySelectorAll('input[name="inmunoterapia"]');
                const tt_seleccionado = document.querySelectorAll('input[name="tipo_tratamiento"]');
                var proveedor_medicamento;
                for (const pms of pm_seleccionado) {
                    if (pms.checked) {
                        proveedor_medicamento = pms.value;
                        break;
                    }
                }
                var inmunoterapia;
                for (const inm of in_seleccionado) {
                    if (inm.checked) {
                        inmunoterapia = inm.value;
                        break;
                    }
                }
                var tipo_tratamiento;
                for (const tt of tt_seleccionado) {
                    if (tt.checked) {
                        tipo_tratamiento = tt.value;
                        break;
                    }
                }

                var cantidad_ciclos   = document.getElementById('ciclos').value;
                var frecuencia_ciclos = document.getElementById('frecuencia').value;
                var fecha_inicio      = document.getElementById('fecha_inicio').value;

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('grabar_protocolo_ajax') }}",
                    method: "POST",
                    data: {paciente_id       : paciente_id,
                           medico_id         : medico_id,
                           fecha_nacimiento  : fecha_nacimiento,
                           edad              : edad,
                           diagnostico_id    : diagnostico_id,
                           cuerpo_parte_id   : cuerpo_parte_id,
                           hospital_id       : hospital_id,
                           aseguradora_id    : aseguradora_id,
                           poliza_no         : poliza_no,
                           proveedor_medicamento : proveedor_medicamento,
                           inmunoterapia     : inmunoterapia,
                           tipo_tratamiento  : tipo_tratamiento,
                           cantidad_ciclos   : cantidad_ciclos,
                           frecuencia_ciclos : frecuencia_ciclos,
                           fecha_inicio      : fecha_inicio,
                           productos_db      : JSON.stringify(productos_db),
                           metastasis_db     : JSON.stringify(metastasis_db),
                           agenda_db         : JSON.stringify(agenda_db)
                    },
                    success: function(response){
                        swal({
                            title: 'Trabajo Finalizado',
                            text: response,
                            type: 'success'
                            },
                            function(isConfirm) {
                                if (isConfirm) { 
                                    window.location.href = "{{ route('protocolos') }}";
                                } 
                            }
                        );  
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }
        });

        function getDate() {
          var today = new Date();
          var dd = today.getDate();
          var mm = today.getMonth()+1; //January is 0!
          var yyyy = today.getFullYear();

          if(dd<10) {
              dd = '0'+dd
          } 

          if(mm<10) {
              mm = '0'+mm
          } 

          today = yyyy + '-' + mm + '-' + dd;
          return (today);
        }

        function LlenarTablaAgenda(){
            var salas_db     = JSON.parse(localStorage.salas_db);
            var html         = '';
            var ciclos       = document.getElementById('ciclos').value;
            var frecuencia   = document.getElementById('frecuencia').value;
            var fecha_inicio = document.getElementById('fecha_inicio').value;
            var fecha_ciclo  = new Date(fecha_inicio);

            var dia = fecha_ciclo.getDate();
            var mes = ("0" + (fecha_ciclo.getMonth() + 1));
            var anio = fecha_ciclo.getFullYear();
            fecha_ciclo = anio + "-"+ mes +"-" + dia;

            for (var i = 1; i <= ciclos; i++) {
                html += '<tr>'
                html += '<td colspan="1">'
                html += i
                html += '</td>'
                html += '<td colspan="2">'
                html += '<input type="hidden" class="form-control" id="agenda['+i+'][id]" name="agenda['+i+'][id]" value="'+i+'">'
                html += '<input type="date" class="form-control" id="agenda['+i+'][fecha_ciclo]" name="agenda['+i+'][fecha_ciclo]" value="'+fecha_ciclo+'" style="width:50%;" required>'
                html += '</td>'
                html += '<td colspan="2">'
                html += '<select class="custom-select form-control select2 select2bs4" id="agenda['+i+'][sala_id]"  name="agenda['+i+'][sala_id]" onchange="trae_horario('+i+'); return false;" style="width:50%;" required>'
                html += '<option value="">Seleccionar...</option>'
                for (var j = 0; j < salas_db.length; j++) {
                    html += '<option value="'+salas_db[j]['sala_id']+'">'+salas_db[j]['sala_nombre']+'</option>'
                }
                html += '</select>'                
                html += '</td>'
                html += '<td colspan="2">'
                html += '<select class="custom-select form-control select2 select2bs4" id="agenda['+i+'][horario]"  name="agenda['+i+'][horario]" style="width:50%;" required>'
                html += '<option value="">Seleccionar...</option>'
                html += '</select>'
                html += '</td>'
                html += '</tr>'

                var d = new Date(fecha_ciclo);
                fecha_ciclo = sumarDias(d, frecuencia);
                /*var fecha_enviar = new Date(anio+'-'+mes+'-'+dia+'T00:00:00+05:30');
                console.log('enviando '+fecha_enviar+' frecuencia '+frecuencia);
                fecha_ciclo = sumarDias(fecha_enviar, frecuencia);
                console.log(fecha_ciclo);*/
                //alert(date.getDate() + '-' +(date.getMonth() + 1) + '-' +  date.getFullYear());
            }
            
            $("#tblAgenda tbody tr").remove();
            $('#tblAgenda tbody').append(html);
        }

        function sumarDias(fecha, dias){
            var fechaR = new Date(fecha);
            var diaR  = parseInt(dias) + 1;
            var day   = fechaR.getDate();
            var month = fechaR.getMonth() + 1;
            var year  = fechaR.getFullYear();
            //document.write("fecha recibida "+day+"/"+month+"/"+year);
            var tiempo = fecha.getTime();
            var milisegundos = parseInt(diaR*24*60*60*1000);
            total = fechaR.setTime(tiempo+milisegundos);
            day   = fechaR.getDate();
            if (String(day).length == 1){
                day   = ("0" + fechaR.getDate());    
            }
            if (String(month).length == 1) {
                month = ("0" + (fechaR.getMonth() + 1));    
            }
            
            
            year  = fechaR.getFullYear();
            //document.write("fecha actualizada "+day+"/"+month+"/"+year);
            return year+'-'+month+'-'+day;
        }
 

        function trae_horario(id){
            var fecha   = document.getElementById('agenda['+id+'][fecha_ciclo]').value;
            var sala_id = document.getElementById('agenda['+id+'][sala_id]').value;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('trae_horarios') }}",
                method: "POST",
                data: {sala_id: sala_id,
                       fecha: fecha},
                success: function(response){
                    let dateDropdown = document.getElementById("agenda["+id+"][horario]"); 
                    for (var i = 0; i < response.length; i++) {
                        let opcion = document.createElement('option');
                        opcion.text = response[i]['hora_inicio']+' - '+response[i]['hora_final'];
                        opcion.value = response[i]['id'];
                        dateDropdown.add(opcion);   
                    }
                },
                error: function(error){
                    console.log(error);
                }
            });     
        }

        function actualizarTotal(id){
            var filas = document.querySelectorAll("#tblProductos tbody tr");
            total = 0;
            var cantidad_linea   = parseFloat(document.getElementById('productos['+id+'][cantidad]').value);
            var preciounit_linea = parseFloat(document.getElementById('productos['+id+'][precio_unitario]').value);
            document.getElementById('productos['+id+'][precio_total]').value = cantidad_linea * preciounit_linea;
            filas.forEach(function(e) {
                var columnas = e.querySelectorAll("td");
                var total_linea = 0;
                if (isNaN(parseFloat($(columnas[2]).find('input').val()))) {
                    total_linea = 0;
                }else{
                    var cantidad        = parseFloat($(columnas[2]).find('input').val());
                    var precio_unitario = parseFloat($(columnas[3]).find('input').val());
                    if (isNaN(cantidad) || isNaN(precio_unitario)) {
                        total_linea = 0;
                    }else{
                        total_linea     = parseFloat(cantidad * precio_unitario);
                    }
                    total += total_linea;
                    //console.log('total linea '+total_linea);
                    /*if (isNaN(total_linea)) {
                        document.getElementById('productos['+id+'][precio_total]').value = 0;    
                    }else{
                        document.getElementById('productos['+id+'][precio_total]').value = total_linea;
                        total += total_linea;
                    }*/
                    
                    
                    //console.log('total final '+total);
                }
            });
            if (isNaN(total)) {
                total = 0;
            }
            filas = document.querySelectorAll("#tblProductos tfoot tr td");
            filas[4].textContent = total.toFixed(2);
        }

        function seleccionar(id){
            var x = document.getElementById("chkm"+id).checked;
            if (x) {
                var linea = {
                    cuerpo_parte_id     : id
                };    
                if(!localStorage.temp_metastasis_db){
                    localStorage.temp_metastasis_db = JSON.stringify([linea]);
                }
                else{
                    var temp_metastasis_db = JSON.parse(localStorage.temp_metastasis_db);
                    temp_metastasis_db.push(linea);
                    localStorage.temp_metastasis_db = JSON.stringify(temp_metastasis_db);
                }
            }else{
                for (var i = 0; i < temp_metastasis_db.length; i++) {
                    if (temp_metastasis_db[i]['cuerpo_parte_id'] == id) {
                        temp_metastasis_db.splice(i, 1);
                        localStorage.temp_metastasis_db = JSON.stringify(temp_metastasis_db);
                    }
                }
            }
        };
    </script>
@endsection