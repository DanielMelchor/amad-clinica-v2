<!DOCTYPE html>
<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-4.5.2-dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/jQueryDataTables/DataTables-1.10.21/css/dataTables.bootstrap4.min.css') }}">
    <title>Informe Médico</title>
    <style>
      @page {margin: 0cm 0cm;font-size: 1em;}
      .encabezado {
        position: fixed;
        top: 0cm;
        left: 1cm;
        right: 1cm;
        height: 2cm;
        /*background-color: #F93855;*/
        /*color: white;*/
        text-align: center;
        line-height: 15px;
      }
      .encabezado p {
        font-size: 12px !important;
        line-height: 5px;
      }
      body {
        top: 4cm;
        left: 1cm;
        right: 1cm;
        padding-top: 3cm;
        padding-bottom: 1cm;
        line-height: 12px;
        /*background-color: #CCFF33;*/
      }
      footer {
        position: fixed;
        bottom: 0cm;
        left: 1cm;
        right: 1cm;
        height: 1cm;
        /*background-color: #F93855;*/
        /*color: white;*/
        text-align: center;
        line-height: 15px;
      }
      .foto {padding-left: 0.5cm; float: left; max-height: 75px; display: inline-block;}
      .page-number:before {content: "Pagina " counter(page); position: flex;}
      .upper {text-transform: uppercase;}
      th, td { padding:2px !important; margin:2 !important; font-size: 12px; font-family: "Times New Roman", Times, serif; }
      .row, .col-xs-* { padding: 0 }
      p {
        padding:2px !important; margin:2px !important; font-size: 12px; font-family: "Times New Roman", Times, serif; text-align: justify;
      }
    </style>
</head>
<body>
    <div class="encabezado">
      <div class="row">
        <div class="col-md-1">
          <img class="foto" src="{{ asset('assets') }}/{{ $empresa->ruta_logo }}">
        </div>
        <div class="col-md-10 offset-md-1">
          <div class="row text-center upper">
            <div class="col-md-12">
              <h3>{{ $empresa->nombre_comercial }}</h3>  
            </div>
          </div>
          <div class="row text-center">
            <div class="col-md-12">
              <p>{{ $empresa->direccion}}</p>
            </div>
          </div>
          <div class="row text-center">
            <div class="col-md-12">
              <p>TELEFONOS: {{ $empresa->telefonos}}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-10 offset-md-1">
        <table class="table table-sm table-borderless">
          <tbody>
            <tr>
              <th colspan="1">Código</th><td colspan="1">{{ $admision->paciente_codigo}}</td>
            </tr>
            <tr>
              <th colspan="1">Nombre</th><td colspan="4">{{ $admision->paciente_nombre}}</td>
              <th colspan="1">Edad</th><td colspan="1">{{ $admision->paciente_edad}}</td>
            </tr>
            <tr>
              <th colspan="1">Procedimiento</th><td colspan="4">{{ $admision->procedimiento_descripcion}}</td>
              <th colspan="1">Fecha</th><td colspan="1">{{ \Carbon\Carbon::parse($admision->fecha)->format('d/m/Y') }}</td>
            </tr>
            <tr>
              <th colspan="1">Referido por</th><td colspan="4">{{ $admision->referido_por}}</td>
              <th colspan="1">Hospital</th><td colspan="4">{{ $admision->hospital_nombre }}</td>
            </tr>
            <tr>
              <th colspan="1">Premedicación</th><td colspan="4">{!! $admision->premedicacion !!}</td>
              <th colspan="1">Tolerancia</th><td colspan="1">{{ $admision->tolerancia_descripcion }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="row">
      <div class="col-md-10 offset-md-1">
        <h6><b>Indicación</b></h6>
      </div>
    </div>
    <!-- indicacion -->
    <div class="row">
      <div class="col-md-10 offset-md-1">
        <p>{!! $admision->indicacion !!}</p>
      </div>
    </div>
    <!-- /indicacion -->
    <br>
    <br>
    <!-- imagenes -->
      <div class="row">
        <div class="col-md-3 offset-md-1">
          @foreach($fotos as $f)
            <img class="card-img-top mt-2" src="{{asset('storage')}}/{{ $admision->id }}/mini/{{ $f->nombre_imagen}}" style="width: 225px; height: 160px; margin-right: 10px;">
          @endforeach
        </div>
      </div>
    <!-- /imagenes --> 
    <div class="row">
      <div class="col-md-10 offset-md-1">
        <h6><b>Diagnostico</b></h6>
      </div>
    </div>
    <!-- diagnostico -->
    <div class="row">
      <div class="col-md-10 offset-md-1">
        <p>{{ $admision->diagnostico }}</p>
      </div>
    </div>
    <!-- /diagnostico -->
    <div class="row">
      <div class="col-md-10 offset-md-1">
        <h6><b>Recomendaciones</b></h6>
      </div>
    </div>
    <!-- recomendaciones -->
    <div class="row">
      <div class="col-md-10 offset-md-1">
        <p>{{ $admision->recomendaciones }}</p>
      </div>
    </div>
    <!-- /recomendaciones -->
    @if( !empty($admision->firma))
      <br><br>
      <div class="row text-center">
        <div class="col-md-8 offset-md-2">
          <img src="{{ asset('') }}{{ $admision->firma }}" style="width: 35%">
        </div>
      </div>
    @endif
    <footer><br><div class="page-number"></div></footer>
    <script src="{{ asset('assets/bootstrap-4.5.2-dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/jQueryDataTables/DataTables-1.10.21/js/dataTables.bootstrap.min.js') }}"></script>
</body>
</html>