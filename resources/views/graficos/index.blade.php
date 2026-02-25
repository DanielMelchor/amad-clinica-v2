@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/highcharts-11.1.0/css/highcharts.css') }}">
    <style type="text/css">
        .nav-pills .nav-link.active,
        .show>.nav-pills .nav-link{
            background: #7FB3D5 !important;
            color: #000000 !important;
        }
        .numero{
            text-align: right;
        }

        * {
            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Helvetica,
                Arial,
                "Apple Color Emoji",
                "Segoe UI Emoji",
                "Segoe UI Symbol",
                sans-serif;
        }

        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 310px;
            max-width: 800px;
            margin: 1em auto;
        }

        #container {
            height: 400px;
        }

        #containerAntiguedad {
            height: 100%;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid var(--highcharts-neutral-color-10, #e6e6e6);
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: var(--highcharts-neutral-color-60, #666);
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tbody tr:nth-child(even) {
            background: var(--highcharts-neutral-color-3, #f7f7f7);
        }

        .highcharts-description {
            margin: 0.3rem 10px;
        }


        @media (prefers-color-scheme: dark) {
            body {
                background-color: #141414;
                color: #ffffff;
            }
        }

    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="input-group input-group-sm col-md-3 offset-md-4 mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">Fecha Inicial</span>
                                </div>
                                <input type="date" class="form-control" placeholder="dd/mm/rrrr" aria-label="Username" aria-describedby="basic-addon1" id="fecha_inicial" name="fecha_inicial" value="{{ $fecha_inicial}}">
                            </div>
                            <div class="input-group input-group-sm col-md-3 mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">Fecha Final</span>
                                </div>
                                <input type="date" class="form-control" placeholder="dd/mm/rrrr" aria-label="Username" aria-describedby="basic-addon1" id="fecha_final" name="fecha_final" value="{{ $fecha_final }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="#" class="btn btn-xs btn-block btn-dark" onclick="fn_datos();">Filtrar</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <ul class="nav nav-pills nav-justified">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="pill" href="#admisiones" role="tab" aria-controls="pills-flamingo" aria-selected="true">Admisiones</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#finanzas" role="tab" aria-controls="pills-ostrich" aria-selected="false">Finanzas</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#inventario" role="tab" aria-controls="pills-cuckoo" aria-selected="false">Inventario</a>
                                    </li>
                                </ul>
                                <div class="tab-content mt-3">
                                    <div class="tab-pane fade show active" id="admisiones" role="tabpanel" aria-labelledby="admisiones-tab">
                                        <div class="row">
                                            <div class="col-lg-3 col-12" style="color: black;">
                                                <div class="small-box" style="background-color: #cca988;">
                                                    <div class="inner">
                                                        <h3><div id="total_admisiones"></div></h3>
                                                        <h4>Cantidad de Admisiones</h4>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fas fa-briefcase-medical"></i>
                                                    </div>
                                                    <a href="#" class="small-box-footer">
                                                        <a href="#" class="small-box-footer" onclick="fnTodasAdmisiones();">"Mas Información"
                                                            <i class="fas fa-arrow-circle-right"></i>
                                                        </a>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-12" style="color: black;">
                                                <div class="small-box" style="background-color: #c3ab95;">
                                                    <div class="inner">
                                                        <h3><div id="admisiones_activas"></div></h3>
                                                        <h4>Admisiones Activas</h4>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fas fa-clipboard-list"></i>
                                                    </div>
                                                    <a href="#" class="small-box-footer">
                                                        <a href="#" class="small-box-footer" onclick="fnAdmisionesActivas();">"Mas Información"
                                                            <i class="fas fa-arrow-circle-right"></i>
                                                        </a>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-12" style="color: black;">
                                                <div class="small-box" style="background-color: #b9aca2;">
                                                    <div class="inner">
                                                        <h3><div id="admisiones_con_saldo"></div></h3>
                                                        <h4>Admisiones con Saldo</h4>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fas fa-coins"></i>
                                                    </div>
                                                    <a href="#" class="small-box-footer">
                                                        <a href="#" class="small-box-footer" onclick="fnAdmisionesConSaldo();">"Mas Información"
                                                            <i class="fas fa-arrow-circle-right"></i>
                                                        </a>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-12" style="color: black;">
                                                <div class="small-box" style="background-color: #adaeaf;">
                                                    <div class="inner">
                                                        <h3><div id="ocupacion_agenda"></div></h3>
                                                        <h4>Agenda Ocupación</h4>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </div>
                                                    <a href="#" class="small-box-footer">
                                                        <a href="#" class="small-box-footer" disabled>
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <figure class="highcharts-figure">
                                                <div id="container"></div>
                                            </figure>
                                            <figure class="highcharts-figure">
                                                <div id="container1"></div>
                                            </figure>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="row">
                                                    <div class="col-md-7" id="circle_admisiones"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-7" id="circle_admisiones_cnt_consulta"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-7" id="circle_admisiones_cnt_procedimiento"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-7" id="circle_admisiones_cnt_hospitalizacion"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-2" id="circle_admisiones_activas"></div>
                                                    <div class="col-md-2" id="circle_admisiones_act_cnt_saldo"></div>
                                                    <div class="col-md-2" id="circle_admisiones_act_monto_saldo"></div>
                                                    <div class="col-md-2" id="circle_citas"></div>
                                                </div>
                                                <br><br>
                                                <div class="row">
                                                    <div class="col-md-10 offset-md-1" id="barraCitas"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade show" id="finanzas" role="tabpanel" aria-labelledby="finanzas-tab">
                                        <div class="row">
                                            <div class="col-lg-9 col-sm-12">
                                                <div class="row">
                                                    <div class="col-md-8 offset-md-2" id="containerAntiguedad"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-12">
                                                <div class="row">
                                                    <div class="col-12" style="color: black;">
                                                        <div class="small-box bg-info elevation-3">
                                                            <div class="inner">
                                                                <a href="#" style="color: white;" class="btn-link" onclick="fnTodasAdmisiones();">Cantidad Doctos.
                                                                    <i class="fas fa-arrow-circle-right"></i>
                                                                </a>
                                                                <h3><div id="total_ingresos">999,392,485.74</div></h3>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-coins"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12" style="color: black;">
                                                        <div class="small-box bg-success elevation-3">
                                                            <div class="inner">
                                                                <h5>Total Facturado</h5>
                                                                <h3><div id="total_facturado">0.00</div></h3>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-coins"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12" style="color: black;">
                                                        <div class="small-box bg-warning elevation-3">
                                                            <div class="inner">
                                                                <h5>Saldo Pendiente</h5>
                                                                <h3><div id="saldo_pendiente"></div></h3>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-coins"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12" style="color: black;">
                                                        <div class="small-box bg-danger elevation-3">
                                                            <div class="inner">
                                                                <h5>Cantidad Anulaciones</h5>
                                                                <h3><div id="total_anulaciones">0.00</div></h3>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-ban"></i>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="detalleAdmisiones" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="detalleAdmisionesLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detalleAdmisionesLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div id="reemplazar"></div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-xs btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-xs btn-primary">Understood</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
    <!-- <script type="text/javascript" src="{{ asset('lib/Highcharts-5.0.14/js/highcharts.js')}}"></script> -->
    <!-- <script src="{{ asset('lib/jquery-plugin-circliful-master/js/jquery.circliful.min.js') }}"></script> -->
    <!-- Highcharts local -->
    <script src="{{ asset('assets/Highcharts-11.1.0/js/highcharts.js') }}"></script>
    <script src="{{ asset('assets/Highcharts-11.1.0/modules/accessibility.js') }}"></script>
    <script src="{{ asset('assets/Highcharts-11.1.0/modules/exporting.js') }}"></script>
    <script src="{{ asset('assets/Highcharts-11.1.0/modules/export-data.js') }}"></script>
    <script src="{{ asset('assets/Highcharts-11.1.0/modules/drilldown.js') }}"></script>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            Highcharts.setOptions({
                colors: ['#cca988', '#c3ab95', '#b9aca2', '#adaeaf']
            });
        });

        function drilldown(pseries, pdrilldown){
            // Data retrieved from https://gs.statcounter.com/browser-market-share#monthly-202201-202201-bar
            // Create the chart
            Highcharts.chart('containerAntiguedad', {
                chart: {
                    type: 'column'
                },
                // colors: ['#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f'],
                title: {
                    text: 'Expedientes por Tipo'
                },
                subtitle: {
                    text: 'Click en cada columna para ver el detalle'
                },
                accessibility: {
                    announceNewData: {
                        enabled: true
                    }
                },
                xAxis: {
                    type: 'category'
                },
                yAxis: {
                    title: {
                        text: 'Cantidad de Expedientes'
                    }

                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            // format: '{point.y:.1f}%'
                            format: '{point.y}'
                        }
                    }
                },

                // tooltip: {
                //     headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                //     pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
                //         '<b>{point.y:.2f}%</b>'
                // },
                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormatter: function() {
                        return '<span style="color:' + this.color + '">' + this.name + '</span>: ' +
                               '<b>' + Highcharts.numberFormat(this.y, 2) + '</b><br/>' +
                               'Cantidad: <b>' + (this.custom ? this.custom.cantidad : '-') + '</b>';
                    }
                },

                // series: [{
                //     name: "Antiguedad",
                //     colorByPoint: true,
                //     data: pseries   // 👈 viene de response[9]
                // }],
                series: [{
                    name: "Antiguedad",
                    colorByPoint: true,
                    data: pseries.map(point => ({
                        name: point.name,
                        y: point.y,
                        drilldown: point.drilldown,
                        color: Highcharts.getOptions().colors[0] // 👈 asignas un color fijo
                    }))
                }],
                drilldown: {
                    series: pdrilldown,
                    colorByPoint: true
                }
            });
        }
        
        function fn_datos(){
            var fecha_inicial = document.getElementById('fecha_inicial').value;
            var fecha_final   = document.getElementById('fecha_final').value;

            $.ajax({
                url: "{{ route('total_admisiones_v2') }}",
                dataType: "json",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}",
                       fecha_inicial : fecha_inicial,
                       fecha_final   : fecha_final},
                success: function(response){
                    console.log(response[6]['ventas']);
                    document.getElementById('total_admisiones').innerHTML  = response[0]['total_adm'];
                    document.getElementById('admisiones_activas').innerHTML = response[1]['total_adm_activas'];
                    document.getElementById('admisiones_con_saldo').innerHTML = response[2]['total_adm_con_saldo']['total_admisiones'];
                    document.getElementById('ocupacion_agenda').innerHTML = response[4]['porcentaje_ocupacion'].toFixed(2);

                    var html = '';
                    html += '<div class="table-responsive">'
                    html += '<table id="tblSaldos" class="display table table-sm table-striped table-hover text-center" style="width:100%">'
                    html += '<thead>'
                    html += '<tr>'
                    html += '<th># Admisión</th><th>Paciente</th><th>Fecha</th>'
                    html += '<th>Total Cargos</th><th>Total Facturado</th><th>Total Pagado</th><th>Saldo</th>'
                    html += '</tr>'
                    html += '</thead>'
                    html += '<tbody>'
                    for (var i = 0; i < response[3]['listado_admisiones_con_saldo'].length; i++) {
                        html += '<tr>';
                        html += '<td style="text-align: right">'+response[3]['listado_admisiones_con_saldo'][i]['admision']+'</td>'
                        html += '<td>'+response[3]['listado_admisiones_con_saldo'][i]['paciente_nombre']+'</td>'
                        html += '<td>'+response[3]['listado_admisiones_con_saldo'][i]['fecha']+'</td>'
                        html += '<td style="text-align: right">'+response[3]['listado_admisiones_con_saldo'][i]['total_cargos']+'</td>'
                        html += '<td style="text-align: right">'+response[3]['listado_admisiones_con_saldo'][i]['total_facturado']+'</td>'
                        html += '<td style="text-align: right">'+response[3]['listado_admisiones_con_saldo'][i]['total_pagado']+'</td>'
                        html += '<td style="text-align: right">'+response[3]['listado_admisiones_con_saldo'][i]['saldo']+'</td>'
                        html += '</tr>';
                        // console.log(response[3]['listado_admisiones_con_saldo'][i]);
                        // html += '<tr';
                        // html += '<td>'+response[3]['listado_admisiones_con_saldo'][i]['admision']+'</td>'
                        // html += '</tr';
                    }
                    html += '</tbody>'
                    html += '</table>'
                    html += '</div>'
                    $("#reemplazar").html(html);
                    var table = $('#tblSaldos').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf'
                        ],
                        autoWidth: false,
                        language: {
                            "decimal": "",
                            "emptyTable": "No hay información",
                            "info": "Mostrando _START_ a _END_ de _TOTAL_ Documentos",
                            "infoEmpty": "Mostrando 0 to 0 of 0 Documentos",
                            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                            "infoPostFix": "",
                            "thousands": ",",
                            "lengthMenu": "Mostrar _MENU_ Documentos",
                            "loadingRecords": "Cargando...",
                            "processing": "Procesando...",
                            "search": "Buscar:",
                            "zeroRecords": "Sin resultados encontrados",
                            "paginate": {
                                "first": "Primero",
                                "last": "Ultimo",
                                "next": "Siguiente",
                                "previous": "Anterior"
                            }
                        }
                    });

                    Highcharts.chart('container', {
                        chart: {
                            type: 'pie',
                            custom: {},
                            events: {
                                render() {
                                    const chart = this,
                                        series = chart.series[0];
                                    let customLabel = chart.options.chart.custom.label;

                                    if (!customLabel) {
                                        customLabel = chart.options.chart.custom.label =
                                            chart.renderer.label(
                                                '<strong>'+response[5]['porcentaje_admisiones'].toFixed(2)+'%</strong>'
                                            )
                                                .css({
                                                    color: '#000',
                                                    textAnchor: 'middle'
                                                })
                                                .add();
                                    }

                                    const x = series.center[0] + chart.plotLeft,
                                        y = series.center[1] + chart.plotTop -
                                        (customLabel.attr('height') / 2);

                                    customLabel.attr({
                                        x,
                                        y
                                    });
                                    // Set font size based on chart diameter
                                    customLabel.css({
                                        fontSize: `${series.center[2] / 5}px`
                                    });
                                }
                            }
                        },
                        accessibility: {
                            point: {
                                valueSuffix: '%'
                            }
                        },
                        title: {
                            text: 'Ocupación del 01/09/2024 al 31/10/2024'
                        },
                        subtitle: {
                            text: ''
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b>'
                        },
                        legend: {
                            enabled: false
                        },
                        plotOptions: {
                            series: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                borderRadius: 8,
                                dataLabels: [{
                                    enabled: true,
                                    distance: 20,
                                    format: '{point.name}'
                                }, {
                                    enabled: true,
                                    distance: -15,
                                    format: '{point.percentage:.0f}%',
                                    style: {
                                        fontSize: '0.9em'
                                    }
                                }],
                                showInLegend: true
                            }
                        },
                        series: [{
                            name: 'Registrations',
                            colorByPoint: false,
                            innerSize: '75%',
                            data: [{
                                name: 'Consultas',
                                y: 75,
                                color: '#9d636e'
                            }, {
                                name: 'Procedimientos',
                                y: 20,
                                color: '#e0f5ed'
                            }, {
                                name: 'Hospitalización',
                                y: 5,
                                color: '#193d89'
                            }]
                        }]
                    });

                    // **************************************************************************************//
                    // *************************************   Finanzas   ***********************************//
                    // **************************************************************************************//
                    document.getElementById('total_ingresos').innerHTML  = response[6]['ventas']['total_documentos'];
                    document.getElementById('total_facturado').innerHTML  = response[6]['ventas']['monto_facturado'];
                    document.getElementById('saldo_pendiente').innerHTML  = response[6]['ventas']['saldo_pendiente'];
                    document.getElementById('total_anulaciones').innerHTML  = response[6]['ventas']['total_anulados'];

                },
                error: function(error){
                    console.log(error);
                }
            });

            // $.ajax({
            //     url: "{{ route('grp_data') }}",
            //     dataType: "json",
            //     type: "POST",
            //     async: true,
            //     data: {"_token": "{{ csrf_token() }}",
            //            fecha_inicial : fecha_inicial,
            //            fecha_final   : fecha_final},
            //     success: function(response){
            //         console.log(response[2]);
            //         document.getElementById('ticket_promedio').innerHTML  = response[0]['ticket_promedio'];
            //         document.getElementById('total_ingresos').innerHTML  = response[1]['total_ventas'];
            //         drilldown(response[3], response[4]);
            //     },
            //     error: function(error){
            //         console.log(error);
            //     }
            // });
        }

        window.onload = function() {
            fn_datos();
        }

        function fnTodasAdmisiones(){
            let fecha_inicial = document.getElementById('fecha_inicial').value;
            let fecha_final   = document.getElementById('fecha_final').value;
            let url = "{{ route('rpt_admisiones_unificado', ['fecha_inicial' => 'x1', 'fecha_final' => 'x2', 'tipo_admision' => 'x3', 'saldo'  => 'x4', 'estado' => 'x5']) }}";
                url = url.replace('x1', fecha_inicial);
                url = url.replace('x2', fecha_final);
                url = url.replace('x3', 0);
                url = url.replace('x4', 'N');
                url = url.replace('x5', 'T');
                // location.href = url;
                window.open(url, '_blank');
        }

        function fnAdmisionesConSaldo(){
            let fecha_inicial = document.getElementById('fecha_inicial').value;
            let fecha_final   = document.getElementById('fecha_final').value;
            let url = "{{ route('rpt_admisiones_unificado', ['fecha_inicial' => 'x1', 'fecha_final' => 'x2', 'tipo_admision' => 'x3', 'saldo'  => 'x4', 'estado' => 'x5']) }}";
                url = url.replace('x1', fecha_inicial);
                url = url.replace('x2', fecha_final);
                url = url.replace('x3', 0);
                url = url.replace('x4', 'S');
                url = url.replace('x5', 'T');
                // location.href = url;
                window.open(url, '_blank');
        }

        function fnAdmisionesActivas(){
            let fecha_inicial = document.getElementById('fecha_inicial').value;
            let fecha_final   = document.getElementById('fecha_final').value;
            let url = "{{ route('rpt_admisiones_unificado', ['fecha_inicial' => 'x1', 'fecha_final' => 'x2', 'tipo_admision' => 'x3', 'saldo'  => 'x4', 'estado' => 'x5']) }}";
                url = url.replace('x1', fecha_inicial);
                url = url.replace('x2', fecha_final);
                url = url.replace('x3', 0);
                url = url.replace('x4', 'N');
                url = url.replace('x5', 'P');
                // location.href = url;
                window.open(url, '_blank');
        }
    </script>
@endsection