@extends('adminlte::page')
@section('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-4.6.2-dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/dataTables_buttons_2.4.2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datatables_bootstrap_4.5.2_css_bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/dataTables_buttons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
	<style type="text/css">
	    .btn-reporte{
	      background-color: #FF8D33 !important;
	    }
	    .btn-excel{
	      background-color: #A5C890 !important;
	    }
	    .btn-config{
	      background-color: #C8BA90 !important;
	    }
	    .btn-refactura{
	      background-color: #C890A4 !important;
	    }
	    .btn-renumera{
	      background-color: #8B9BC1 !important;
	    }
	    .btn-anular{
	      background-color: #226D7C !important;
	      color: white !important;
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
@section('title', 'Reportes')
@section('content_header')
    <br>
@endsection
@section('content')
	<div class="row" class="table-responsive">
		<div class="col-md-12">
			<div class="card" data-spy="scroll">
                <div class="card-header" style="background-color: #E1E8ED;">
                    <div class="row">
                        <div class="col-md-9">
                            <h5>Admisiones</h5>
                        </div>
                        <div class="col-md-3" style="text-align: right;">
                            <a href="#" class="btn btn-xs btn-config rounded-circle elevation-4" title="Agregar Registro" onclick="abrirModal(); return false;"><i class="fas fa-plus-circle"></i></a>
                            <a href="{{ route('home') }}" class="btn btn-xs btn-danger rounded-circle elevation-4" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                	<table class="table table-sm table-striped" id="tblPrincipal">
                		<thead>
                			<th># Admisión</th>
                			<th>Fecha</th>
                			<th>Creado</th>
                			<th>Paciente</th>
                			<th>Expediente</th>
                			<th>Tipo</th>
                			<th style="display: none;">Hospital</th>
                			<th style="display: none;">Procedimiento</th>
                			<th style="display: none;">Ingreso</th>
                			<th style="display: none;">Egreso</th>
                			<th>Cargos</th>
                			<th>Facturado</th>
                			<th>Pagado</th>
                			<th>Saldo</th>
                			<th>Facturas</th>
                			<th>Estado</th>
                		</thead>
                		<tbody>
                			@foreach($registros as $registro)
                			<tr style="font-size: 12px;">
	                			<td class="numero">{{ $registro->admision }}</td>
	                			<td>{{ \Carbon\Carbon::parse($registro->fecha)->format('d/m/Y') }}</td>
                				<td>{{ $registro->username }}</td>
                				<td>{{ $registro->nombre_completo }}</td>
                				<td class="numero">{{ $registro->expediente_no }}</td>
                				<td>{{ $registro->tipo_admision }}</td>
                				<td style="display: none;">{{ $registro->hospital_nombre }}</td>
	                			<td style="display: none;">{{ $registro->procedimiento_nombre }}</td>
	                			<td style="display: none;"></td>
	                			<td style="display: none;"></td>
	                			<td class="numero">{{ $registro->total_cargos }}</td>
	                			<td class="numero">{{ $registro->total_facturado }}</td>
	                			<td class="numero">{{ $registro->total_pagado }}</td>
	                			<td class="numero">{{ $registro->saldo }}</td>
	                			<td>{{ $registro->facturas }}</td>
	                			<td>{{ $registro->estado }}</td>
                			</tr>
        					@endforeach
                		</tbody>
    				</table>
                </div>
            </div>
        </div>
    </div>
	<!-- Modal Config-->
    <div class="modal fade" id="configModal" role="dialog" aria-labelledby="nuevaAdmisionModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    		<div class="modal-content">
    			<form class="form-horizontal" id="admisionForm" name="admision" action="#">
    				@csrf
    				<div class="card">
    					<div class="card-header" style="background-color: #F4F6F7;">
                            <div class="row">
                                <div class="col-md-9">
                                    <h5>Criterio de Busqueda</h5>
                                </div>
                                <div class="col-md-3" style="text-align: right;">
                                    <a href="#" class="btn btn-xs btn-outline-secondary rounded-circle elevation-4" title="filtrar" onclick="fn_buscar(); return false;"><i class="fas fa-search"></i></a>
                                    <button type="button" class="btn btn-xs btn-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar"> <i class="fas fa-sign-out-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                        	<div class="row">
                        		<div class="input-group input-group-sm col-md-5 offset-md-1">
                        			<div class="input-group-prepend">
                                        <label class="input-group-text">Fecha Inicio</label>
                                    </div>
                                    <input type="date" class="form-control form-control-sm" id="fecha_inicial" name="fecha_inicial" required value="{{ $fecha_inicial }}" autofocus>
                        		</div>
                        		<div class="input-group input-group-sm col-md-5">
                        			<div class="input-group-prepend">
                                        <label class="input-group-text">Fecha Fin</label>
                                    </div>
                                    <input type="date" class="form-control form-control-sm" id="fecha_final" name="fecha_final" required value="{{ $fecha_final }}">
                        		</div>
                        	</div>
                        	<br>
                        	<div class="row">
                                <div class="col-md-6 offset-md-1">
                                    <div class="form-group form-control-sm clearfix">
                                    	<div class="icheck-primary d-inline">
                                            <input type="radio" id="Todos" name="tipo_admision" value="0" checked>&nbsp;&nbsp;
                                            <label for="Todos">Todos</label>
                                        </div>
                                    	@foreach($tipo_admisiones as $tipo_admision)
                                    		<div class="icheck-primary d-inline">
	                                            <input type="radio" id="{{$tipo_admision->nombre}}" name="tipo_admision" value="{{$tipo_admision->id}}">&nbsp;&nbsp;
	                                            <label for="{{$tipo_admision->nombre}}">{{$tipo_admision->nombre}}</label>
	                                        </div>
                                    	@endforeach
                                    </div>
                                </div>
                                <div class="col-md-3 offset-md-1">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="saldo" name="saldo" value="A" title="Incluir admisiones sin saldo">
                                        <label class="custom-control-label" for="saldo">Sin Saldo</label>
                                    </div>
                                </div>
                            </div>
                        </div>
    				</div>
    			</form>
    		</div>
    	</div>
    </div>
    <!-- /Modal Config-->
@endsection
@section('js')
	<script src="{{ asset('plugins/code.jquery.com_jquery-3.7.0.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-4.6.2-dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery.dataTables_1.13.6.min.js') }}"></script>
    <script src="{{ asset('plugins/dataTables.buttons_2.4.2.min.js') }}"></script>
    <script src="{{ asset('plugins/ajax_libs_jszip_3.10.1.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake_0.1.53.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake_0.1.53_vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/dataTables.buttons_2.4.2.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables_buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/buttons_2.4.2_js_buttons.print.min.js') }}"></script>
    <script type="text/javascript">
    	$(function () {
			$('#tblPrincipal').DataTable({
      			"paging": true,
	      		"lengthChange": false,
	      		"searching": true,
	      		"ordering": true,
	      		"info": true,
	      		"autoWidth": false,
	      		buttons: [
			        'colvis',
			        'excel'
			    ],
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
		    });
  		});

  		//=====================================================================
        // Modal nueva admision
        //=====================================================================
    	function abrirModal(){
            // jQuery.noConflict();
            // $('#admisionForm input').val('');
            // $('#admisionForm select').val(''); // Para limpiar los selects si los tienes
            // $('#admisionForm textarea').val(''); // Para limpiar los textareas si los tienes
            $('#configModal').modal('show');
        }

        function fn_buscar(){
	    	let fecha_inicial = document.getElementById('fecha_inicial').value;
	    	let fecha_final   = document.getElementById('fecha_final').value;
	    	// let tipo_admision = document.getElementById('tipo_admision').value;
	    	let tipo_admision = $('input[name="tipo_admision"]:checked').val();
	    	let saldo         = 'N';
	    	if ($('#saldo').is(':checked')) {
	            saldo = 'S'
	        }
	        if(fecha_inicial == '') return false;

	        let url = "{{ route('rpt_admisiones_unificado', ['fecha_inicial' => 'x1', 'fecha_final' => 'x2', 'tipo_admision' => 'x3', 'saldo'  => 'x4', 'estado' => 'x5']) }}";
                url = url.replace('x1', fecha_inicial);
                url = url.replace('x2', fecha_final);
                url = url.replace('x3', tipo_admision);
                url = url.replace('x4', saldo);
                url = url.replace('x5', 'T');

            location.href = url;
	    }
    </script>
@endsection