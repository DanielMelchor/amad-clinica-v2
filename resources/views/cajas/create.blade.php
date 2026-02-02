@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
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
@section('title', 'Cajas')

@section('content_header')
    <br>
@endsection
@section('content')
    <form class="form-horizontal" role='form' method="POST" id="cajaForm" name="cajaForm" action="{{ route('grabar_caja')}}">
        @CSRF
        <div class="card">
            <div class="card-header" style="background-color: #E1E8ED;">
                <div class="col-md-2 offset-md-10" style="text-align: right;">
                    <button type="submit" id="submitButton" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
                    <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Regresar a lista de Pacientes" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="input-group mb-1 col-lg-4 col-sm-4 offset-lg-1 offset-sm-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Nombre</span>
                        </div>
                        <input type="text" class="form-control" placeholder="nombre Caja" aria-label="Username" aria-describedby="basic-addon1" id="caja_nombre" name="caja_nombre" autofocus required value="{{ old('caja_nombre')}}">
                    </div>
                
                    <div class="form-group clearfix">
                        <div class="icheck-primary d-inline">
                            <span for="editar_documento">Editar Documento</span> &nbsp;
                            <input type="checkbox" data-bootstrap-switch data-off-color="danger" data-on-color="success" id="editar_documento" name="editar_documento"> 
                        </div>
                    </div>
                    <div class="form-group offset-md-1">
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
                            <label class="custom-control-label" for="estado">Activar</label>
                        </div>
                    </div>
                </div>
                <hr style="border: 1px solid #C8BA90 !important;">
                <div class="row text-center">
                    <div class="col-lg-1 offset-lg-5 col-sm-2 offset-sm-5">
                        <h6>Resoluciones</h6>
                    </div>
                    <div class="col-lg-1 offset-lg-5 col-sm-1 offset-sm-4">
                        <button class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="fnAgregarResolucion();">
                            <i class="fas fa-plus-circle"></i>
                        </button>
                    </div>
                </div>
                <hr style="border: 3px double #C8BA90 !important;">
                <hr>
                <div class="row">
                    <div class="col-lg-10 offset-lg-1">
                        <table class="table table-sm table-striped" id="tblresoluciones">
                            <thead>
                                <tr style="text-align: center;">
                                    <th>Tipo Documento</th>
                                    <th>Serie</th>
                                    <th>Inicial</th>
                                    <th>Final</th>
                                    <th>Actual</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
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
        var nLineaT = 0;

        $(function () {
            $('.select2').select2();
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            });
        });

        function fnAgregarResolucion(){
            event.preventDefault();
            var html = '';
            html += '<tr>';
            html += '<td>';
            html += '<select class="custom-select custom-select-sm select2 select2bs4" id="resoluciones['+nLineaT+'][tipo_documento_id]" name="resoluciones['+nLineaT+'][tipo_documento_id]">';
            html += '<option value="">Seleccionar...</option>';
            @foreach($tipo_documentos as $td)
                html += '<option value="{{ $td->id}}">{{ $td->descripcion }}</option>'
            @endforeach
            html += '</select>'
            html += '</td>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="text" class="form-control" id="resoluciones['+nLineaT+'][serie]" name="resoluciones['+nLineaT+'][serie]" style="text-transform: uppercase;"/>';
            html += '</div>';
            html += '</td>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="number" step="1" min="0" class="form-control numero" id="resoluciones['+nLineaT+'][inicial]" name="resoluciones['+nLineaT+'][inicial]"/>';
            html += '</div>';
            html += '</td>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="number" step="1" min="0" class="form-control numero" id="resoluciones['+nLineaT+'][final]" name="resoluciones['+nLineaT+'][final]"/>';
            html += '</div>';
            html += '</td>';
            html += '<td>';
            html += '<div class="input-group input-group-sm mb-1">'
            html += '<input type="number" step="1" min="0" class="form-control numero" id="resoluciones['+nLineaT+'][ultimo]" name="resoluciones['+nLineaT+'][ultimo]"/>';
            html += '</div>';
            html += '</td>';
            html += '<td>';
            html += '<div class="form-group offset-md-1">'
            html += '<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">'
            html += '<input type="checkbox" class="custom-control-input" id="resoluciones['+nLineaT+'][estado]" name="resoluciones['+nLineaT+'][estado]" value="1">'
            html += '<label class="custom-control-label" for="resoluciones['+nLineaT+'][estado]">&nbsp;</label>'
            html += '</div>'
            html += '</div>';
            html += '</td>';
            html += '<td style="text-align: right">';
            html += '<button class="btn btn-xs btn-outline-danger rounded-circle elevation-4 eliminar" title="eliminar registro"><i class="fas fa-minus-circle"></i></button>'
            html += '</td>';
            html += '</tr>';
            $('#tblresoluciones tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nLineaT += 1;
            $('.select2').select2();
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            });
        }

        function eliminar()
        {
            var whichtr = $(this).closest("tr");
            whichtr.remove(); 
            return false;
        }

        $(document).ready(function() {
            $('#cajaForm').on('submit', function() {
                // Deshabilitar el botón de submit cuando se envíe el formulario
                $('#submitButton').prop('disabled', true);
                // $('#submitButton').text('Enviando...');
            });
        });

        //===================================================================
        // Confirmar salida de pantalla
        //===================================================================
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
                        window.location.href = "{{ route('cajas') }}";
                    } 
                }
            );
        }
    </script>
@endsection