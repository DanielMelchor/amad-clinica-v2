@extends('adminlte::page')
@section('css')
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
@endsection
@section('title', 'Protocolos')

@section('content_header')
  <h3>Edición de Protocolo</h3>
@endsection

@section('content')
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
        <div id="maindiv">
        <form role="form" method="POST" action="{{ route('actualizar_protocolo', $protocolo->id )}}">
            @csrf
            <div class="card card-navy">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2 offset-md-10" style="text-align: right;">
                            <button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
                            <a href="#" class="btn btn-sm btn-reporte" title="Detalle de Protocolo" target="_blank"><i class="fas fa-file-pdf"></i></a>
                            <a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Protocólos" onclick="confirma_salida({{ $protocolo->config_maestro_protocolo_id }}); return false;"><i class="fas fa-sign-out-alt"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="background-color: #e3f2fd;">
                    <input type="hidden" id="estado" name="estado" value="{{ $protocolo->estado }}">
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
                                            <option value="{{ $p->id}}" @if($protocolo->paciente_id == $p->id) selected @endif>{{ $p->nombre_completo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ $protocolo->fecha_nacimiento }}">
                                <input type="hidden" id="edad" name="edad" value="{{ $protocolo->edad }}">
                                <div class="input-group mb-2 col-md-7 offset-md-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Fch. Nacimiento</label>
                                    </div>
                                    <input type="date" class="form-control" placeholder="DD/MM/AAAA" id="showfecha_nacimiento" name="showfecha_nacimiento" value="{{ $protocolo->fecha_nacimiento }}" disabled>
                                </div>
                                <div class="input-group mb-2 col-md-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Edad</label>
                                    </div>
                                    <input type="number" class="form-control" id="showedad" name="showedad" value="{{ $protocolo->edad }}" disabled>
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
                                            <option value="{{ $d->id}}" @if($protocolo->diagnostico_id == $d->id) selected @endif>{{ $d->descripcion }}</option>
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
                                            <option value="{{ $cp->id}}" @if($protocolo->cuerpo_parte_id == $cp->id) selected @endif>{{ $cp->nombre }}</option>
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
                                            <option value="{{ $h->id}}" @if($protocolo->lugar_tratamiento_id == $h->id) selected @endif>{{ $h->nombre }}</option>
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
                                            <option value="{{ $a->id}}" @if($protocolo->aseguradora_id == $a->id) selected @endif>{{ $a->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="input-group mb-2 col-md-4">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Cíclo</label>
                                    </div>
                                    <input type="number" class="form-control" placeholder="0" aria-label="Username" aria-describedby="basic-addon1" id="ciclo" name="ciclo" min="1" value="{{ $protocolo->ciclo }}" style="text-align: right;" readonly required>
                                </div>
                                <div class="input-group mb-2 col-md-6">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Fecha Ciclo</label>
                                    </div>
                                    <input type="date" class="form-control text-center" placeholder="DD/MM/AAAA" aria-label="Username" aria-describedby="basic-addon1" id="fecha_inicio" name="fecha_inicio"  value="{{ $protocolo->fecha_ciclo }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group clearfix">
                                        <label>Proveedor de Medicamentos</label>&nbsp;
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="clinica" name="proveedor_medicamento" value="H" @if($protocolo->proveedor_medicamento == 'H') checked @endif>
                                            <label for="clinica">Clinica</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="aseguradora" name="proveedor_medicamento" value="A" @if($protocolo->proveedor_medicamento == 'A') checked @endif>
                                            <label for="aseguradora">Aseguradora</label>
                                        </div>
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="paciente" name="proveedor_medicamento" value="P" @if($protocolo->proveedor_medicamento == 'P') checked @endif>
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
                                            <input type="radio" id="no" name="inmunoterapia" value="N" @if($protocolo->inmunoterapia == 'N') checked @endif>
                                            <label for="no">No</label>
                                        </div>                                    
                                        &nbsp;
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="si" name="inmunoterapia" value="S" @if($protocolo->inmunoterapia == 'S') checked @endif>
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
                                            <input type="radio" id="ambulatorio" name="tipo_tratamiento" value="A" @if($protocolo->tipo_tratamiento == 'A') checked @endif>
                                            <label for="ambulatorio">Ambulatorio</label>
                                        </div>
                                        &nbsp;
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="interno" name="tipo_tratamiento" value="I" @if($protocolo->tipo_tratamiento == 'I') checked @endif>
                                            <label for="interno">Interno</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <a href="{{route('rpt_informe_protocolo_pdf', $protocolo->id)}}" class="btn btn-sm btn-primary" title="Informe de Protocolo" target="_blank">Informe de Protocolo</a>
                                    <a href="{{route('rpt_informe_protocolo_pdf', $protocolo->id)}}" class="btn btn-sm btn-primary" title="Informe de Protocolo" target="_blank">Informe Medico</a>
                                    <a href="{{route('rpt_informe_protocolo_pdf', $protocolo->id)}}" class="btn btn-sm btn-primary" title="Informe de Protocolo" target="_blank">Detalle de cargos</a>
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
                                                        {{ $p->descripcion }}
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
                                                        <input type="number" class="form-control" id="productos[{{$p->id}}][cantidad]" name="productos[{{$p->id}}][cantidad]" style="text-align: right;" min="1" placeholder="0.00" value="{{ $p->cantidad }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" id="productos[{{$p->id}}][precio_unitario]" name="productos[{{$p->id}}][precio_unitario]" style="text-align: right;" step="0.01" placeholder="0.00" value="{{ $p->precio_unitario}}" onchange="actualizarTotal({{ $p->id }}); return false;">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control" id="productos[{{$p->id}}][precio_total]" name="productos[{{$p->id}}][precio_total]" style="text-align: right;" step="0.01" placeholder="0.00" value="{{ $p->precio_total }}" readonly>
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
                                                            <input type="checkbox" id="chkm{{$x->id}}" name="metastasis[{{$x->id}}][checked]" @if($x->existe == 'S') checked @endif>
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
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/adminlte/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/adminlte/plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
        var nlinea = 0;
        var total  = 0;

        window.addEventListener('load', function(){
            var estado = document.getElementById('estado').value;
            if (estado == 'F') {
                /*$('#maindiv').fadeTo('slow',.6); 
                $('#maindiv').append('<div style="position: absolute;top:0;left:0;width: 100%;height:100%;z-index:2;opacity:0.4;filter: alpha(opacity = 50)"></div>');*/

                var nodes = document.getElementById("maindiv").getElementsByTagName('*'); 
                for(var i = 0; i < nodes.length; i++){ nodes[i].disabled = true; }
            }
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

        //========================================================================
        // Confirmar salida de pantalla
        //========================================================================
        function confirma_salida(id){
            swal({
                title: 'Confirmación',
                text: 'Seguro de Salir, si ha realizado cambios estos no seran guardados  ?'+id,
                type: 'warning',
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
                        window.location.href = "{{ route('mostrar_ciclos', 5) }}";
                    } 
                }
            );
        }
    </script>
@endsection
