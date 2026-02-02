@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
        .btn-guardar{
            background-color: #A5C890 !important;
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
@section('title', 'Pacientes')

@section('content_header')
    <br>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #E1E8ED;">
                    <div class="row">
                        <div class="col-md-9">
                            <h6>Pacientes</h6>
                        </div>
                        <div class="col-md-3" style="text-align: right;">
                            <a href="{{ route('crear_paciente', ['P', '0'])}}" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Nuevo Registro"><i class="fas fa-plus-circle"></i></a>
                            <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" onclick="confirma_salida(); return false;" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
                        </div>
                    </div>
                </div>
                <form class="form-horizontal">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tblprincipal"  class="table table-sm table-striped" width="100%" style="font-size: 12px;">
                                        <thead class="thead-primary">
                                            <tr>
                                                <th scope="col" class="text-center">Código</th>
                                                <th scope="col" class="text-center">Nombre</th>
                                                <th scope="col" class="text-center">Expediente</th>
                                                <th scope="col" class="text-center">Estado</th>
                                                <th>&nbsp;</th>
                                            </tr>   
                                        </thead>
                                        <tbody>
                                            @foreach($pPacientes as $pPaciente)
                                                <tr class="text-center">
                                                    <td>{{ $pPaciente->codigo_id}}</td>
                                                    <td>{{ $pPaciente->nombre_completo }}</td>
                                                    <td>{{ $pPaciente->expediente_no }}</td>
                                                    @if($pPaciente->estado == 1)
                                                        <td>Activo</td>
                                                    @else
                                                        <td>Baja</td>
                                                    @endif
                                                    @php $pacienteId= Crypt::encrypt($pPaciente->id); @endphp
                                                    <td>
                                                        <a href="{{route('editar_paciente' , $pacienteId )}}" class="btn btn-xs btn-warning rounded-circle elevation-4" title="Editar Empresa"><i class="fas fa-edit"></i></a>
                                                        <a href="{{route('nueva_admision' , ['paciente_id' => $pPaciente->id, 'origen' => 'P' ] )}}" class="btn btn-xs btn-outline-info rounded-circle elevation-4" title="ver Admisiones"><i class="fas fa-book-medical"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
    <!-- <script src="{{asset('assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script> -->
    @if(Session::has('success'))
        <script>
            swal("Trabajo Finalizado", "{!! Session::get('success') !!}", "success")
        </script>
    @endif
    @if(Session::has('error'))
        <script>
            swal("Error !!!", "{!! Session::get('error') !!}", "error")
        </script>
    @endif
    <script type="text/javascript">
        // $(function () {
        //     $('#tblprincipal').DataTable({
        //       "paging": true,
        //       "lengthChange": false,
        //       "searching": true,
        //       "ordering": true,
        //       "info": true,
        //       "autoWidth": false,
        //       language: {
        //             "sProcessing":     "Procesando...",
        //             "sLengthMenu":     "Mostrar _MENU_ registros",
        //             "sZeroRecords":    "No se encontraron resultados",
        //             "sEmptyTable":     "Ningún dato disponible en esta tabla =(",
        //             "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        //             "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        //             "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        //             "sInfoPostFix":    "",
        //             "sSearch":         "Buscar:",
        //             "sUrl":            "",
        //             "sInfoThousands":  ",",
        //             "sLoadingRecords": "Cargando...",
        //             "oPaginate": {
        //                             "sFirst":    "Primero",
        //                             "sLast":     "Último",
        //                             "sNext":     "Siguiente",
        //                             "sPrevious": "Anterior"
        //                         }
        //         },
        //         dom: 'Bfrtip'
        //     });
        // });
        $(function () {
            $('#tblprincipal').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "pageLength": 25,  // Esto establece que por defecto se muestren 25 registros
                "lengthMenu": [ [10, 25, 50, 100], [10, 25, 50, 100] ],  // Esto establece las opciones en el dropdown
                "language": {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla =(",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sSearch": "Buscar:",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    }
                },
                "dom": '<"row"<"col-sm-4"l><"col-sm-4 text-center"B><"col-sm-4"f>>rtip', // Ajuste para disposición
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        className: 'btn btn-md btn-default'
                    }
                ]
            });
        });

        function confirma_salida(){
            swal({
                title: 'Confirmación',
                text: 'Seguro de Salir, si ha realizado cambios estos no seran guardados ?',
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
                        window.location.href = "{{ route('home') }}";
                                    } 
                    else { 
                        swal("Cancelled", "Your imaginary file is safe :)", "error"); 
                        }
            });
        }
    </script>
@endsection