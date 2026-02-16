@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/multi-select/css/multi-select.css') }}">
@endsection
@section('title', 'Roles')
@section('content')
    <br>
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <form role="form" method="POST" action="{{ route('role_actualizar', $role->id) }}">
                @csrf
                <div class="card">
                    <div class="card-header" style="background-color: #E1E8ED;">
                        <div class="row">
                            <div class="col-md-9">
                                <h5>Edición de Role</h5>
                            </div>
                            <div class="col-md-3" style="text-align: right;">
                                <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar"><i class="fas fa-save"></i></button>
                                <a href="{{ route('roles_listado') }}" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                        </div>  
                    </div>
                    <div class="card-body">
                        <input type="hidden" id="id" name="id">
                        <div class="row">
                            <div class="input-group input-group-sm col-lg-6 mb-1">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">Nombre</label>
                                </div>
                                <input type="text" class="form-control" id="name" name="name" autofocus required value="{{ $role->name }}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6">
                                <select id='callbacks' name="callbacks[]" multiple='multiple'>
                                    @foreach($permisos as $p)
                                        <option value='{{ $p->id}}'>{{ $p->name }}</option>
                                    @endforeach
                                </select>   
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('plugins/multi-select/js/jquery.multi-select.js') }}"></script>
    @if(Session::get('type') == 'success')
        @if(Session::has('message'))
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: "¡Trabajo Finalizado!",
                        text: "{!! Session::get('message') !!}",
                        icon: "success", // Cambiado de 'type' a 'icon'
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                        customClass: {
                            confirmButton: 'btn btn-success'
                        },
                        buttonsStyling: false
                    });
                }, 1000);
            </script>
        @endif
    @endif
    @if(Session::get('type') == 'error')
        @if(Session::has('message'))
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: "¡Trabajo Finalizado!",
                        text: "{!! Session::get('message') !!}",
                        icon: "error", // Cambiado de 'type' a 'icon'
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });
                }, 1000);
            </script>
        @endif
    @endif
    <script type="text/javascript">
        $('#callbacks').multiSelect({
            selectableHeader: "<div class='custom-header text-center'>Permisos</div>",
            selectionHeader: "<div class='custom-header text-center'>Otorgados</div>",
          afterSelect: function(values){
            //alert("Select value: "+values);
          },
          afterDeselect: function(values){
            //alert("Deselect value: "+values);
          }
        });
        var x = [];
        @foreach ($permisos_x_role as $pr)
            x.push("{{ $pr['permission_id'] }}");
        @endforeach
        $('#callbacks').multiSelect('select', x);
    </script>
@endsection