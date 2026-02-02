@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/multi-select/css/multi-select.css') }}">
@endsection
@section('title', 'Usuarios')
@section('content_header')
    <br>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form role="form" id="form" method="POST" action="{{ route('usuario_actualizar', $registro->id) }}">
                @csrf
                <div class="card">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="row">
                            <div class="col-md-9">
                                <h6>Usuarios</h6>
                            </div>
                            <div class="col-md-3" style="text-align: right;">
                                <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar los cambios"><i class="fas fa-save"></i></button>
                                <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" onclick="confirma_salida(); return false;" title="Salir"><i class="fas fa-sign-out-alt"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" id="id" name="id" value="{{ $registro->id }}">
                        <input type="hidden" id="usuario_caja_id" name="usuario_caja_id" value="{{ $registro->caja_id }}">
                        <div class="row">
                            <div class="col-md-6 offset-md-1">
                                <div class="row">
                                    <div class="input-group input-group-sm col-md-12 mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" id="basic-addon1">Nombre</label>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" id="name" name="name" value="{{ $registro->name }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group input-group-sm mb-1 col-md-12">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Usuario&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        </div>
                                        <input type="text" class="form-control" placeholder="usuario" aria-label="Username" aria-describedby="basic-addon1" id="username" name="username" autofocus required value="{{ $registro->username }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group input-group-sm mb-1 col-md-12">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="empresa_id">Empresa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="empresa_id" name="empresa_id" required>
                                            <option value="" selected>Seleccionar...</option>
                                            @foreach($empresas as $e)
                                                <option value="{{ $e->id }}" @if($e->id == $registro->empresa_id) selected @endif>{{ $e->nombre_comercial }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group input-group-sm mb-1 col-md-12">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="caja_id">Caja&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        </div>
                                        <select class="custom-select custom-select-sm select2 select2bs4" id="caja_id" name="caja_id" aria-label="caja_id" aria-describedby="basic-addon1">
                                            <option value="">Seleccionar...</option>
                                            @foreach($cajas as $c)
                                                <option value="{{ $c->id }}" @if($c->id == $registro->caja_id) selected @endif>{{ $c->nombre_maquina }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 offset-md-1">
                                <img src="{{ asset('imagenes/predeterminada.jpg') }}" width="200" height="200">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-5 offset-md-1">
                                <div class="card">
                                    <div class="card-header" style="background-color: #c3ab95;">
                                        <h6>Salas</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-10 offset-md-2">
                                                <select id='callbacks' name="callbacks[]" multiple='multiple'>
                                                    @foreach($salas as $s)
                                                        <option value='{{ $s->id}}'>{{ $s->sala_nombre }}</option>
                                                    @endforeach
                                                </select>   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="card">
                                    <div class="card-header" style="background-color: #b9aca2">
                                        <h6>Roles</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-10 offset-md-2">
                                                <select id='callbacksr' name="callbacksr[]" multiple='multiple'>
                                                    @foreach($roles as $r)
                                                        <option value='{{ $r->id}}'>{{ $r->name }}</option>
                                                    @endforeach
                                                </select>   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
@endsection
@section('js')
    <script src="{{ asset('plugins/multi-select/js/jquery.multi-select.js') }}"></script>
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
        //========================================================================
        // inicializar librerias
        //========================================================================
        $(function () {
            $('.select2').select2()
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        });

        $('#callbacks').multiSelect({
            selectableHeader: "<div class='custom-header text-center'><strong>Salas</strong></div>",
            selectionHeader: "<div class='custom-header text-center'><strong>Salas permitidas</strong></div>",
          afterSelect: function(values){
            //alert("Select value: "+values);
          },
          afterDeselect: function(values){
            //alert("Deselect value: "+values);
          }
        });
        var x = [];
        @foreach ($salas_x_usuario as $su)
            x.push("{{ $su['sala_id'] }}");
        @endforeach
        $('#callbacks').multiSelect('select', x);

        $('#callbacksr').multiSelect({
            selectableHeader: "<div class='custom-header text-center'><strong>Roles</strong></div>",
            selectionHeader: "<div class='custom-header text-center'><strong>Roles permitidos</strong></div>",
          afterSelect: function(values){
            //alert("Select value: "+values);
          },
          afterDeselect: function(values){
            //alert("Deselect value: "+values);
          }
        });
        var x = [];
        @foreach ($roles_x_usuario as $ru)
            x.push("{{ $ru['id'] }}");
        @endforeach
        $('#callbacksr').multiSelect('select', x);
    </script>
@endsection