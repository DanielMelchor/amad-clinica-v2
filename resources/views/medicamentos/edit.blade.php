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
          <form role="form" method="POST" action="{{route('actualizar_medicamento', $pMedicamento->id)}}">
              @csrf
              <div class="card">
                  <div class="card-header" style="background-color: #E1E8ED;">
                      <div class="row">
                          <div class="col-md-9">
                              <h5>Edición de Medicamento</h5>
                          </div>
                          <div class="col-md-3" style="text-align: right;">
                              <button type="submit" class="btn btn-sm btn-success img-circle elevation-4" title="Grabar"><i class="fas fa-save"></i></button>
                              <a href="#" class="btn btn-sm btn-danger img-circle elevation-4" title="Salir" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                          </div>
                      </div>
                  </div>
                  <div class="card-body">
                      <input type="hidden" id="medicamento_id" name="medicamento_id" value="{{ $pMedicamento->id }}">
                      <div class="row">
                          <div class="input-group input-group-sm col-md-5 offset-md-1 mb-1">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text">Nombre</span>
                                  </div>
                                  <input type="text" class="form-control" placeholder="nombre medicamento" aria-label="Username" aria-describedby="basic-addon1" id="nombre" name="nombre" autofocus required value="{{ $pMedicamento->nombre }}">
                              </div>
                          </div>
                          <br>
                          <div class="row">
                              <div class="form-group offset-md-1">
                                  <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                      <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A" @if($pMedicamento->estado == 'A') then checked @endif>
                                      <label class="custom-control-label" for="estado">Activar</label>
                                  </div>
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
                              <table class="table table-striped table-hover text-center" id="tableDestinos">
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

    </script>
@endsection