@extends('adminlte::page')
@section('css')
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
@endsection
@section('title', 'Especialidades')

@section('content_header')
  <h3>Edición de Hospital</h3>
@endsection

@section('content')
  @if(Session::has('message'))
      <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" arial-label="Close"><span aria-hidden="true">x</span>
        </button>
          {{ Session::get('message') }}  
      </div>
  @endif
  <div class="row">
        <div class="col">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

  <form role="form" method="POST" action="{{route('actualizar_hospital', $pHospital->id)}}">
    @csrf
    <div class="card card-navy">
      <div class="card-header">
        <div class="row">
          <div class="col-md-2 offset-md-10" style="text-align: right;">
            <button type="submit" class="btn btn-sm btn-success" title="Grabar"><i class="fas fa-save"></i></button>
            <!--<a href="{{route('hospitales')}}" class="btn btn-sm btn-danger" title="Regresar a lista de Hospitales"><i class="fas fa-sign-out-alt"></i></a>-->
            <a href="#" class="btn btn-sm btn-danger" title="Regresar a lista de Pacientes" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
          </div>
        </div>
      </div>
      <form class="form-horizontal">
        <div class="card-body">
          <div class="row text-center">
            <div class="input-group mb-5 col-md-5 offset-md-1">
              <div class="input-group-prepend">
                <span class="input-group-text">Nombre</span>
              </div>
              <input type="text" class="form-control" placeholder="nombre hospital" aria-label="Username" aria-describedby="basic-addon1" id="nombre" name="nombre" autofocus required value="{{ $pHospital->nombre }}">
            </div>
            <div class="input-group mb-5 col-md-5">
              <div class="input-group-prepend">
                <span class="input-group-text">Dirección</span>
              </div>
              <input type="text" class="form-control" placeholder="Direccion Hospital" aria-label="Username" aria-describedby="basic-addon1" id="direccion" name="direccion" value="{{ $pHospital->direccion }}">
            </div>
          </div>
          <div class="row text-center">
            <div class="input-group mb-5 col-md-5 offset-md-1">
              <div class="input-group-prepend">
                <span class="input-group-text">Teléfonos</span>
              </div>
              <input type="text" class="form-control" placeholder="Telefonos" aria-label="Username" aria-describedby="basic-addon1" id="telefonos" name="telefonos" value="{{ $pHospital->telefonos }}">
            </div>
            <div class="input-group mb-5 col-md-5">
              <div class="input-group-prepend">
                <span class="input-group-text">Contacto</span>
              </div>
              <input type="text" class="form-control" placeholder="nombre contacto" aria-label="Username" aria-describedby="basic-addon1" id="contacto" name="contacto" value="{{ $pHospital->contacto }}">
            </div>
          </div>
          <div class="row">
            <div class="form-group offset-md-1">
              <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" class="custom-control-input" id="referencia" name="referencia" value="S" @if($pHospital->referencia == 'S') then checked @endif>
                <label class="custom-control-label" for="referencia">Referencia</label>
              </div>
            </div>
            <div class="form-group offset-md-1">
              <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" class="custom-control-input" id="estado" name="estado" value="A" @if($pHospital->estado == 'A') then checked @endif>
                <label class="custom-control-label" for="estado">Activar</label>
              </div>
            </div>
            <div class="form-group offset-md-1">
              <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" class="custom-control-input" id="principal_agenda" name="principal_agenda" value="S" @if($pHospital->principal_agenda == 'S') then checked @endif>
                <label class="custom-control-label" for="principal_agenda">Definir como principal en agenda</label>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </form>
@endsection
@section('js')
<script src="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.min.js') }}"></script>
<script type="text/javascript">
  function confirma_salida(){
    swal({
        title: 'Confirmación',
        text: "Confirmar salida: Se perderán las modificaciones no guardadas. ¿Desea continuar?"
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
                window.location.href = "{{ route('hospitales') }}";
                            } 
            else { 
                swal("Cancelled", "Your imaginary file is safe :)", "error"); 
                }
        }
    );
  }
</script>
@endsection