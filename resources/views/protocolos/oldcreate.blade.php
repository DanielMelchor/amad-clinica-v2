@extends('admin.layout')
@section('css')
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@endsection
@section('titulo')
	Protocólos
@endsection
@section('subtitulo')
	Creación
@endsection

@section('contenido')
	<div class="row">
        <div class="col">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" arial-label="Close"><span aria-hidden="true">x</span>
	    			</button>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <div class="content-fluid">
        <form role="form" method="POST" action="{{ route('grabar_protocolo') }}">
            @csrf
            <div class="card card-navy">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2 offset-md-10" style="text-align: right;">
                            <button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
                            <a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Protocólos" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="background-color: #e3f2fd;">
                    <div class="row">
                        <div class="col-md-5 offset-md-1">
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
                                    <select class="custom-select select2 select2bs4" id="aseguradora_id"  name="aseguradora_id">
                                        <option value="">Seleccionar...</option>
                                        @foreach($aseguradoras as $a)
                                            <option value="{{ $a->id}}">{{ $a->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
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
                                    <input type="number" class="form-control" placeholder="0" aria-label="Username" aria-describedby="basic-addon1" id="frecuencia" name="frecuencia" min="1" value="{{ old('frecuencia')}}" style="text-align: right;" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group mb-2 col-md-6">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Fch. Inicio</label>
                                    </div>
                                    <input type="date" class="form-control text-center" placeholder="DD/MM/AAAA" aria-label="Username" aria-describedby="basic-addon1" id="fecha_inicio" name="fecha_inicio"  value="{{ old('fecha_inicio')}}" required>
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
                    
                    <hr>
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <div class="card card-secondary mb-3" style="width: 100%;">
                                <div class="card-header text-center"><h6>Productos / Servicios / Procedimientos</h6></div>
                                <div class="card-body">
                                    <table class="table table-sm table-striped record_table text-center" id="tblProductos">
                                        <thead><tr><th>Producto / Servicio</th><th>U. M.</th><th>Cantidad</th><th>Precio Unitario</th><th>Total Línea</th></tr></thead>
                                        <tbody>
                                            @foreach($productos as $p)
                                                <!--<tr onclick="seleccionar_productos(this, {{ $p->id }})">-->
                                                <tr>
                                                    <td>
                                                        <input type="hidden" class="form-control" id="productos[{{$p->id}}][id]" name="productos[{{$p->id}}][id]" value="{{$p->id}}">
                                                        {{ $p->descripcion }} {{ $p->id }}
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control select2 select2bs4" id="productos[{{$p->id}}][medida_id]" name="productos[{{$p->id}}][medida_id]" @if($p->clasificacion != 'PROD') disabled @endif>
                                                            @if($p->clasificacion != 'PROD')
                                                                <option value="0">Unidad</option>
                                                            @else
                                                                <option value="">Seleccionar...</option>
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" id="productos[{{$p->id}}][cantidad]" name="productos[{{$p->id}}][cantidad]" style="text-align: right;" min="1" placeholder="0.00">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" id="productos[{{$p->id}}][precio_unitario]" name="productos[{{$p->id}}][precio_unitario]" style="text-align: right;" step="0.01" placeholder="0.00" onchange="actualizarTotal({{ $p->id }}); return false;">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" id="productos[{{$p->id}}][precio_total]" name="productos[{{$p->id}}][precio_total]" style="text-align: right;" step="0.01" placeholder="0.00" readonly>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><h5>Total</h5></td>
                                                <td style="text-align: right;"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <p>Metastasis</p>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-striped record_table text-center">
                                        <thead><tr><th></th><th>Descripción</th></tr></thead>
                                        <tbody>
                                            @foreach($cuerpo_partes->chunk(5) as $cp)
                                                <tr>
                                                    @foreach($cp as $x)
                                                        <td onclick="seleccionar(this, {{ $x->id }})">
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
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="metastasisModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="card card-secondary">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                Metastasis
                            </div>
                            <div class="col-md-4" style="text-align: right;">
                                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fas fa-sign-out-alt"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/adminlte/plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script type="text/javascript">
        var nlinea = 0;
        var total  = 0;

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
        // funcion para ordenar detalle
        //========================================================================
        function compare(a,b){
            const valorA = a.parte_id;
            const valorB = b.parte_id;
            let comparacion = 0;

            if (valorA < valorB) {
                comparacion = -1;
            }else{
                comparacion = 1;
            }
            return comparacion;
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
            today_date = new Date();
            today_year = today_date.getFullYear();
            today_month = today_date.getMonth();
            today_day = today_date.getDate();
            age = today_year - birth_year;

            if (today_month < (birth_month - 1)) {
                age--;
            }
            if (((birth_month - 1) == today_month) && (today_day < birth_day)) {
                age--;
            }
            return(age);
        }

        //========================================================================
        // Seleccionar productos
        //========================================================================
        function seleccionar_productos(tr, value){
            $(function(){
                var tipo=(value%2)?"Par":"Impar";

                if ($("#chkp"+value).attr("checked") == "checked") {
                    $("#chkp"+value).removeAttr("checked");
                     document.getElementById("chkp"+value).checked = false; 
                    if (tipo == 'Impar') {
                        $(tr).css("background-color", "#FAF1F1");   
                    }else{
                        $(tr).css("background-color", "#FAD7D7");
                    }
                    
                }else{
                    $("#chkp"+value).attr("checked", "true");
                    $("#chkp"+value).prop("checked", "true");
                    document.getElementById("chkp"+value).checked = true; 
                    $(tr).css("background-color", "#BEDAE8");
                }
            });
        }

        $(function () {
            $('#tblProductos').DataTable({
              "paging": true,
              "lengthChange": false,
              "searching": true,
              "ordering": true,
              "info": true,
              "autoWidth": false,
              "pageLength": 150,
              language: {
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla =(",
                    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sSearch":         "Buscar:",
                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                                    "sFirst":    "Primero",
                                    "sLast":     "Último",
                                    "sNext":     "Siguiente",
                                    "sPrevious": "Anterior"
                                }
                },
                dom: 'Bfrtip'
            })
        });

        function seleccionar(tr, value){
            $(function(){
                var tipo=(value%2)?"Par":"Impar";
                if ($("#chkm"+value).attr("checked") == "checked") {
                    $("#chkm"+value).removeAttr("checked");
                     document.getElementById("chkm"+value).checked = false; 
                    if (tipo == 'Impar') {
                        $(tr).css("background-color", "#FAF1F1");   
                    }else{
                        $(tr).css("background-color", "#FAD7D7");
                    }
                    
                }else{
                    $("#chkm"+value).attr("checked", "true");
                    $("#chkm"+value).prop("checked", "true");
                    $(tr).css("background-color", "#BEDAE8");
                }
            });
        }

        window.addEventListener('load', function(){
            var productos_db = [];
            localStorage.clear(productos_db);
            $.ajax({
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('trae_productos') }}",
                method: "POST",
                success: function(response){
                    var productos = response;
                    for (var i = 0; i < productos.length; i++) {
                        var linea = {
                            id : productos[i]['id'],
                            articulo_id : productos[i]['id'],
                            articulo_descripcion : productos[i]['descripcion'],
                            articulo_medida_id   : productos[i]['medida_id'],
                            medida_descripcion   : productos[i]['medida_descripcion']
                        };
                        if(!localStorage.productos_db){
                            localStorage.productos_db = JSON.stringify([linea]);
                        }
                        else{
                            var productos_db = JSON.parse(localStorage.productos_db);
                            productos_db.push(linea);
                            localStorage.productos_db = JSON.stringify(productos_db);
                        }
                        /*$.ajax({
                            url: "{{ route('trae_medidas_x_producto') }}",
                            type: "POST",
                            dataType: 'json',
                            data: {"_token": "{{ csrf_token() }}", producto_id : productos[i]['id']},
                            success: function(response){
                                var unidades = response;
                                for (var j = 0; j < unidades.length; j++) {
                                    
                                }
                            },
                            error: function(error){
                                console.log(error);
                            }
                        });*/
                    }
                    //actualizarTablaProductos();
                },
                error: function(error){
                    console.log(error);
                }
            });
        });

        function actualizarTablaProductos(){
            var productos_db = JSON.parse(localStorage.productos_db);
            productos_db.sort(compare);

            var html = '';
            for (var i = 0; i < productos_db.length; i++) {
                html += '<tr>'
                html += '<td>'
                html += '<input type="hidden" class="form-control" id="productos['+productos_db[i]["id"]+'][id]" name="productos['+productos_db[i]["id"]+'][id]" value="'+productos_db[i]["id"]+'">'
                html += productos_db[i]["articulo_descripcion"]
                html += '</td>'
                html += '<td>'
                html += '<select class="custom-select form-control select2 select2bs4" id="productos['+productos_db[i]["id"]+'][medida_id]"  name="productos['+productos_db[i]["id"]+'][medida_id]">'
                html += '<option value="">Seleccionar...</option>'
                html += '</select>'
                html += '</td>'
                html += '<td>'
                html += '<input type="number" class="form-control" id="productos['+productos_db[i]["id"]+'][cantidad]" name="productos['+productos_db[i]["id"]+'][cantidad]" style="text-align: right;" step="0.01" placeholder="0.00">'
                html += '</td>'
                html += '<td>'
                html += '<input type="number" class="form-control" id="productos['+productos_db[i]["id"]+'][precio_unitario]" name="productos['+productos_db[i]["id"]+'][precio_unitario]" style="text-align: right;" step="0.01" placeholder="0.00" onchange="actualizarTotal('+productos_db[i]["id"]+'); return false;">'
                html += '</td>'
                html += '<td>'
                html += '<input type="number" class="form-control" id="productos['+productos_db[i]["id"]+'][precio_total]" name="productos['+productos_db[i]["id"]+'][precio_total]" style="text-align: right;" step="0.01" placeholder="0.00" disabled>'
                html += '</td>'
                html += '</tr>'
            }
            
            $("#tblProductos tbody tr").remove();
            $('#tblProductos tbody').append(html);
        }

        function actualizarTotal(id){
            /*var cantidad        = document.getElementById('productos['+id+'][cantidad]').value;
            var precio_unitario = document.getElementById('productos['+id+'][precio_unitario]').value;
            var total_linea     = parseFloat(cantidad) * parseFloat(precio_unitario);*/
            /*total += total_linea;
            console.log(total);*/
            //document.getElementById('productos['+id+'][precio_total]').value = total_linea;

            var filas = document.querySelectorAll("#tblProductos tbody tr");
            total = 0;
            filas.forEach(function(e) {
                var columnas = e.querySelectorAll("td");
                if (isNaN(parseFloat($(columnas[2]).find('input').val()))) {
                    var total_linea = 0;
                }else{
                    var cantidad        = parseFloat($(columnas[2]).find('input').val());
                    var precio_unitario = parseFloat($(columnas[3]).find('input').val());
                    var total_linea     = parseFloat(cantidad * precio_unitario);
                    //console.log('total linea '+total_linea);
                    if (isNaN(total_linea)) {
                        document.getElementById('productos['+id+'][precio_total]').value = 0;    
                    }else{
                        document.getElementById('productos['+id+'][precio_total]').value = total_linea;
                        total += total_linea;
                    }
                    
                    
                    //console.log('total final '+total);
                }
            });
            filas = document.querySelectorAll("#tblProductos tfoot tr td");
            filas[4].textContent = total.toFixed(2);
        }

    </script>
@endsection