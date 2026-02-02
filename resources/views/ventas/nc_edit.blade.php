@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('assets/bootstrap-sweetalert-master/dist/sweetalert.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style type="text/css">
        select[readonly] {
          pointer-events: none;
          background-color: #e9ecef; /* estilo como readonly */
        }
        input, textarea, select, button {
            font-size: 12px !important;
        }

        /* Fondo del modal */
        .sweet-alert {
          background-color: #F0FFFF;   /* fondo oscuro */
          border-radius: 12px;        /* bordes más redondeados */
          color: #fff;                /* texto blanco */
          font-family: 'Roboto', sans-serif;
        }

        /* Título */
        .sweet-alert h2 {
          font-size: 22px;
          font-weight: bold;
          color: #ffd369; /* amarillo */
        }

        /* Texto del cuerpo */
        .sweet-alert p {
          font-size: 16px;
          color: #dcdcdc;
        }

        /* Botón de confirmación */
        .sweet-alert button.confirm {
          background-color: #4caf50 !important;
          border-radius: 6px;
          padding: 8px 20px;
          font-weight: bold;
        }

        /* Botón de cancelación (si usas cancelButtonText) */
        .sweet-alert button.cancel {
          background-color: #f44336 !important;
          border-radius: 6px;
          padding: 8px 20px;
          font-weight: bold;
        }

        /* Icono de éxito */
        .sweet-alert .sa-icon.sa-success {
          border-color: #4caf50;
        }
    </style>
@endsection
@section('title', 'Facturas')
@section('content_header')
    <br>
@endsection
@section('content')
    <form role="form" id="form-factura" method="post" action="#">
        @csrf
        <div class="container-fluid">
            <div class="card">
                <div class="card-header" style="background-color: #E1E8ED;">
                    <div class="bg-default clearfix">
                        <div class="row">
                            <div class="col-lg-10 col-sm-10">
                                <h6>Edición Nota de Crédito</h6>
                            </div>
                            <div class="col-lg-2 col-sm-2" style="text-align: right;">
                                <button type="submit" id="btnGuardar" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Grabar"><i class="fas fa-save" title="Guardar"></i></button>
                                <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Salir" onclick="confirma_salida(); return false;"><i class="fas fa-sign-out-alt"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <input type="hidden" id="resolucion_id" name="resolucion_id">
                    <input type="hidden" id="caja_id" name="caja_id" value="{{ $caja->id }}">
                    <input type="hidden" id="caja_editar_documento" name="caja_editar_documento" value="{{ $caja->editar_documento}}">
                    <input type="hidden" id="tipo_documento_id" name="tipo_documento_id" value="{{ $documento->id }}">
                    <input type="hidden" id="paciente_id" name="paciente_id">
                    <input type="hidden" id="factura_estado" name="factura_estado" value="P">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div id="documento_cobro">
                                        <div class="card border-dark shadow mb-3">
                                            <div class="card-header bg-light">Documento</div>
                                            <div class="card-body text-info">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="input-group mb-1 input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Documento</label>
                                                            </div>
                                                            <select class="custom-select custom-select-sm select2 select2bs4 disabled" id="tipo_documento_id" name="tipo_documento_id" required disabled>
                                                                <option value="" selected>Seleccionar...</option>
                                                                <option value="{{ $documento->id }}" selected>{{ $documento->descripcion }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group form-control-sm clearfix">
                                                            <label>Condición</label>&nbsp;&nbsp;
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" class="boton" id="contado" name="condicion" value="0" checked disabled>
                                                                <label for="contado">Contado</label>
                                                            </div>
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" class="boton" id="credito" name="condicion" value="1" disabled>
                                                                <label for="credito">Credito</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="input-group mb-1 input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Fecha</label>
                                                            </div>
                                                            <input type="date" class="form-control form-control-sm text-center card-text" id="fecha_emision" name="fecha_emision" value="{{ $encabezado->fecha_emision }}" tabindex="1" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-group mb-1 input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Serie</label>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm text-center card-text" id="serie" name="serie" value="{{ $encabezado->serie }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="input-group mb-1 input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Correlativo</label>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm text-center" id="correlativo" name="correlativo" value="{{ $encabezado->correlativo }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div id="documentos_afecto">
                                        <div class="card border-dark shadow mb-3">
                                            <div class="card-header bg-light">Documento Afecto</div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group form-control-sm clearfix">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" id="factura" name="tipodocumento" value="1" checked disabled tabindex="3">
                                                                <label for="factura">Factura</label>
                                                            </div>
                                                            &nbsp;&nbsp;&nbsp;
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" id="Debito" name="tipodocumento" value="5" tabindex="4" disabled>
                                                                <label for="Debito">Nota de Debito</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-5 mb-1">
                                                        <div class="input-group input-group-sm mb-1">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Serie</label>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm text-center" id="serie_afecta" name="serie_afecta" style="text-transform: uppercase;" readonly tabindex="5" value="{{ $encabezado->serie_afecta }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-6 mb-1">
                                                        <div class="input-group input-group-sm mb-1">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Correlativo</label>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm text-center" id="documento_afecto" name="documento_afecto" tabindex="6" value="{{ $encabezado->correlativo_afecto }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-1 mb-1">
                                                        <a href="#" class="btn btn-xs btn-default rounded-circle elevation-4" title="Buscar" tabindex="6"><i class="fas fa-search"></i></a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 mb-1">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">N.I.T.</label>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm text-center" id="nit" name="nit" value="{{ $encabezado->nit }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-7 mb-1">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Fecha</label>
                                                            </div>
                                                            <input type="date" class="form-control text-center" id="fecha" name="fecha" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 mb-1">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Nombre</label>
                                                            </div>
                                                            <input type="text" class="form-control text-center" id="nombre" name="nombre" value="{{ $encabezado->nombre }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 mb-1">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Dirección</label>
                                                            </div>
                                                            <input type="text" class="form-control text-center" id="direccion" name="direccion" value="{{ $encabezado->direccion }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 mb-1">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text">Correo Electrónico</label>
                                                            </div>
                                                            <input type="text" class="form-control text-center" id="email" name="email" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header text-center" style="background-color: #E1E8ED;">
                                                    <div class="row">
                                                        <div class="col-md-10 offset-md-1">
                                                            <h5>Detalle</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table id="tblDetalle" class="table table-sm table-striped text-center" style="font-size: 12px;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 150px;">Producto</th>
                                                                            <th style="width: 250px;">Descripción</th>
                                                                            <th>Medída</th>
                                                                            <th>Cantidad</th>
                                                                            <th>Precio Unitario</th>
                                                                            <th>Precio total</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody></tbody>
                                                                    <tfoot></tfoot>
                                                                </table>
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
    </form>
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
                  });
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
                  });
              }, 1000);
          </script>
      @endif
  @endif
  <script type="text/javascript">
      var nlinea = 0;
      var nLinea = 0;
      var nlineaPago = 0;
      var total_detalle = 0;
      var linea = {};
      var statSend = false;
      var condicion = 0;
      var forma_pago = 'E';

      $(function(){
          $('.select2').select2();
          $('.select2bs4').select2({
            theme: 'bootstrap4'
          });
      });


      //====================================================================================
      // Agregar formato de moneda a campos en tabla
      //====================================================================================
      const formatter = new Intl.NumberFormat('es-GT', {
        style: 'currency',
        currency: 'GTQ',
        minimumFractionDigits: 2
      });


      //========================================================================
      // inicializar librerias
      //========================================================================
      $(function () {
          $('.select2').select2()
          $('.select2bs4').select2({
            theme: 'bootstrap4'
          })
      });

      document.addEventListener('DOMContentLoaded', function () {
          $("#btnGuardar").prop("disabled", true);
          var caja_editar_documento      = document.getElementById('caja_editar_documento').value;
          $("#fecha_emision").prop('readonly', true);
          if (caja_editar_documento == '0') {
              $("#serie").prop('readonly', true);
              $("#correlativo").prop('readonly', true);
              document.getElementById('nit').focus();
          }else {
              $("#serie").prop('readonly', false);
              $("#correlativo").prop('readonly', false);
              document.getElementById('serie').focus();
          }

          const detalle = @json($detalle);
          detalle.forEach(function(valor, indice) {
              agregar_cargo();
              $('#cargos\\['+indice+'\\]\\[producto_id\\]').val(valor.producto_id).trigger('change');
              $('#cargos\\['+indice+'\\]\\[descripcion\\]').val(valor.producto_descripcion);
              $('#cargos\\['+indice+'\\]\\[medida_id\\]').val(valor.unidad_medida_id).trigger('change');
              $('#cargos\\['+indice+'\\]\\[cantidad\\]').val(valor.cantidad);
              $('#cargos\\['+indice+'\\]\\[precio_unitario\\]').val(valor.precio_unitario);
              $('#cargos\\['+indice+'\\]\\[precio_total\\]').val(valor.precio_neto);

              $('#cargos\\['+indice+'\\]\\[producto_id\\]').prop("disabled", true);
              $('#cargos\\['+indice+'\\]\\[descripcion\\]').prop("readonly", true);
              $('#cargos\\['+indice+'\\]\\[medida_id\\]').prop("disabled", true);
              $('#cargos\\['+indice+'\\]\\[cantidad\\]').prop("readonly", true);
              $('#cargos\\['+indice+'\\]\\[precio_unitario\\]').prop("readonly", true);
              $('#cargos\\['+indice+'\\]\\[precio_total\\]').prop("readonly", true);
          });
      });

      /*=========================================================================================
        Agregar linea de cargos
        =========================================================================================*/
        function agregar_cargo(){
            var html = '';
            html += '<tr>';
            html += '<td width="150px">';
            html += '<select id="cargos['+nLinea+'][producto_id]" name="cargos['+nLinea+'][producto_id]" class="form-control classproducto" data-required="true" onchange="actualizarMedida('+nLinea+')">';
            html += '<option value="">Seleccionar...</option>';
            @foreach($productos as $producto)
            html += '<option value="{{$producto->id}}">{{$producto->descripcion}}</option>';
            @endforeach
            html += '</select>';
            html += '</td>';
            html += '<td>';
            html += '<input type="text" class="form-control classdescripcion" id="cargos['+nLinea+'][descripcion]" name="cargos['+nLinea+'][descripcion]" />';
            html += '</td>';
            html += '<td width="125px">';
            html += '<select id="cargos['+nLinea+'][medida_id]" name="cargos['+nLinea+'][medida_id]" class="form-control classmedida" data-required="true">';
            html += '<option value="">Seleccionar...</option>';
            html += '</select>';
            html += '</td>';
            html += '<td>';
            html += '<input type="number" step="1" class="form-control classnumero numero" id="cargos['+nLinea+'][cantidad]" name="cargos['+nLinea+'][cantidad]" />';
            html += '</td>';
            html += '<td>';
            html += '<input type="number" step="any" min="0.01" class="form-control classnumero numero" id="cargos['+nLinea+'][precio_unitario]" name="cargos['+nLinea+'][precio_unitario]" />';
            html += '</td>';
            html += '<td>';
            html += '<input type="number" step="any" min="0.01" class="form-control classnumero numero" id="cargos['+nLinea+'][precio_total]" name="cargos['+nLinea+'][precio_total]" />';
            html += '</td>';
            html += '</tr>';
            $('#tblDetalle tbody').append(html);
            // $('.eliminar').on('click',eliminar);
            nLinea += 1;
        }

        /*=========================================================================================
        Actualizar unidad de medida
        =========================================================================================*/
        function actualizarMedida(linea){
            var x = document.getElementById("cargos["+linea+"][producto_id]").selectedIndex;
            var y = document.getElementById("cargos["+linea+"][producto_id]").options;
            var producto_id = y[x].value;              

            $.ajax({
                url: "{{ route('trae_medidas_x_producto') }}",
                type: "POST",
                async: true,
                data: {"_token": "{{ csrf_token() }}", producto_id: producto_id},
                success: function(response){
                    if (response.length == 0) {
                        let dropdown = document.getElementById("cargos["+linea+"][medida_id]");
                        dropdown.length = 0;
                        let option;
                        option = document.createElement('option');
                        option.text = 'Unidad';
                        option.value = 1;
                        dropdown.add(option);
                    }else{
                        let dropdown = document.getElementById("cargos["+linea+"][medida_id]");
                        dropdown.length = 0;
                        let option;
                        option = document.createElement('option');
                        option.text = 'Seleccionar ....';
                        option.value = '';
                        for (let i = 0; i < response.length; i++) {
                            option = document.createElement('option');
                            option.text = response[i].unidad_medida_descripcion;
                            option.value = response[i].unidad_medida_id;
                            dropdown.add(option);
                        }
                    }
                },
                error: function(error){
                    console.log(error);
                } 
            });
        }

      //=======================================================================
      // Confirmar Salida de pantalla
      //=======================================================================
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
                      window.location.href = "{{ route('nc_listado') }}";
                  } 
              }
          );
      }
  </script>
@endsection