@extends('adminlte::page')

@section('css')
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
@endsection
@section('title', 'Perfil')

@section('content_header')
  <h3>Perfil de usuario</h3>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="card mb-3">
                <div class="card-header text-white bg-info">Header</div>
                <div class="card-body">
                    <div class="col-md-12 col-xs-12">
                        <div class="row">
                            <div class="col-md-3">
                                @if (Auth::user()->hasProfilePicture())
                                    <img src="{{ Auth::user()->profile_picture }}" class="img-thumbnail" />
                                @else
                                    <img src="https://via.placeholder.com/150x150" alt="No profile picture" />
                                @endif
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-10 offset-md-1">
                                        <h1>{{ Auth::user()->name }}</h1>
                                        <strong>Role: </strong>&nbsp; Administrador
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-10 offset-md-3">
                                        <div class="card">
                                            <div class="card-header bg-secondary">
                                                <h4>Imagen de Perfil</h4>
                                            </div>
                                            <div class="card-body">
                                                <form method="post" action="{{ route('update_picture') }}" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <input type="file" id="urlfoto" name="urlfoto" class="form-control{{ $errors->has('file') ? ' is-invalid' : '' }}" >
                                                          @if ($errors->has('file'))
                                                              <span class="invalid-feedback" role="alert">
                                                                  <strong>{{ $errors->first('file') }}</strong>
                                                              </span>
                                                          @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-block bg-navy">Grabar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 offset-md-4">
                                        <a href="#" class="btn btn-sm btn-block btn-warning">
                                            <h4>Cambiar Contraseña</h4>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10 offset-md-3">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                            Launch demo modal
                                        </button>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
    <script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
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
                        window.location.href = "{{ route('unidadmedidas') }}";
                    } 
                }
            );
        }

        function fn_abrirModal(){
            $('#cambioContraseña').on('shown.bs.modal', function () {
                $('#myInput').trigger('focus')
            })
        }
    </script>
@endsection