@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
@endsection
@section('title', 'Medicamentos')

@section('content_header')
  <br>
@endsection

@section('content')
	<div class="row">
        <div class="col-md-10 offset-md-1">
            <form role="form" method="POST" action="{{route('grabar_medicamento')}}">
                @csrf
                <div class="card">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="row">
                            <div class="col-md-9">
                                <h5>Nuevo Medicamento</h5>
                            </div>
                            <div class="col-md-3" style="text-align: right;">
                                <button type="submit" class="btn btn-sm btn-success img-circle elevation-4" title="Grabar"><i class="fas fa-save"></i></button>
                                <a href="#" class="btn btn-sm btn-danger img-circle elevation-4" title="Salir" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class="row">
                                <div class="input-group input-group-sm col-md-5 offset-md-1 mb-1">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Nombre</label>
                                    </div>
                                    <input type="text" class="form-control" placeholder="nombre medicamento" aria-label="Username" aria-describedby="basic-addon1" id="nombre" name="nombre" autofocus required value="{{ old('nombre')}}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group offset-md-1">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A">
                                        <label class="custom-control-label" for="estado">Activar</label>
                                    </div>
                                </div>
                            
                                <div class="col-md-1 offset-md-11" style="text-align: right;">
                                    <a href="#" class="btn btn-sm btn-primary img-circle elevation-4" title="Agregar Dosis" onclick="agregarDestino(); return false;">
                                        <i class="fas fa-plus-circle"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="table-responsive">
                                        <table class="table table-stripped table-hover" id="tableDestinos">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Dosis</th>
                                                    <th class="text-center">Descripción</th>
                                                    <th class="text-center">Estado</th>
                                                    <th width="35px"></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </form>
        </div>
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
        var nDestino = 0;
        let ruta = '';
        
        window.onload = function() {
          ruta = document.referrer;
        };

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
                        window.location.href = "{{ route('medicamentos') }}";
                                    } 
                    else { 
                        swal("Cancelled", "Your imaginary file is safe :)", "error"); 
                        }
                }
            );
        }


        function agregarDestino()
        {
            var html = '';
            html += '<tr>';
            html += '<td width="125px">';
            html += '<select name="destinos['+nDestino+'][destino_id]" class="form-control" data-required="true">';
            html += '<option value="">Seleccionar...</option>';
            @foreach($destinos as $destino)
            html += '<option value="{{$destino->id}}">{{$destino->descripcion}}</option>';
            @endforeach
            html += '</select>';
            html += '</td>';
            
            html += '<td width="250px">';
            html += '<input type="text" class="form-control" step="any" name="destinos['+nDestino+'][descripcion]" />';
            html += '</td>';
            html += '<td width="50px" style="text-align: center;">';
            html += '<h4> Alta </h4>';
            html += '</td>';

            html += '<td width="35px"><a href="#" class="btn btn-sm btn-danger eliminar"><i class="fas fa-trash-alt"></i></a></td>';
            
            html += '</tr>';
            $('#tableDestinos tbody').append(html);
            $('.eliminar').on('click',eliminar);
            nDestino++;
        }
        function eliminar()
        {
            var whichtr = $(this).closest("tr");
            whichtr.remove(); 
            return false;
        }

    </script>
@endsection