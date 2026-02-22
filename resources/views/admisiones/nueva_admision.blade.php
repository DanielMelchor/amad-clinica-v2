@extends('adminlte::page')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style type="text/css">
        .patient-banner {
          display: flex;
          justify-content: space-between;
          align-items: center;
          background: #ffffff;
          padding: 15px 25px;
          border-bottom: 2px solid #e2e8f0;
          margin-bottom: 20px;
        }

        .patient-identity { display: flex; align-items: center; }

        .avatar {
          width: 50px; height: 50px; background: #3182ce; color: white;
          border-radius: 50%; display: flex; align-items: center; justify-content: center;
          font-weight: bold; margin-right: 15px;
        }

        .info h2 { margin: 0; font-size: 1.2rem; color: #2d3748; }
        .age { font-size: 0.9rem; color: #718096; font-weight: normal; }
        .genero { font-size: 0.9rem; color: #718096; font-weight: normal; }

        /* Estilo de Alertas Médicas */
        .alert-box {
          padding: 5px 12px; border-radius: 4px; font-size: 0.85rem; font-weight: bold; margin-bottom: 4px;
        }
        .allergy { background: #fff5f5; color: #c53030; border: 1px solid #feb2b2; }
        .risk { background: #fffaf0; color: #9c4221; border: 1px solid #fbd38d; }

        /* Botones de Acción */
        .btn-action {
          padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600; margin-left: 10px;
        }
        .order { background: #3182ce; color: white; }
        .note { background: #edf2f7; color: #2d3748; }

        .control-sidebar {
            width: 500px; /* Ajusta el tamaño de la barra */
        }

        .control-sidebar-open {
            right: 0 !important;
        }

        /* Ajuste inicial */
        .content-wrapper {
            transition: margin-right 0.3s ease-in-out;
            margin-right: 0;
        }

        /* Cuando la barra derecha está abierta */
        .content-wrapper.push-content {
            margin-right: 300px; /* Mueve el contenido a la izquierda */
        }

        /* Ajuste de la barra derecha */
        .control-sidebar {
            width: 300px; /* Tamaño de la barra lateral derecha */
            transition: right 0.3s ease-in-out;
            right: -300px; /* Oculta la barra lateral */
            position: fixed;
            top: 0;
            height: 100vh;
        }

        /* Cuando la barra derecha está visible */
        .control-sidebar.control-sidebar-open {
            right: 0;
        }

        .tree, .tree ul {
          list-style: none;
          margin: 0;
          padding: 0;
        }

        .tree li {
          margin: 0;
          padding: 5px 10px;
          line-height: 20px;
          color: #369;
          font-weight: bold;
          border-left: 1px solid #ccc; /* Línea guía vertical */
        }

        /* Ocultar subniveles por defecto */
        .tree ul {
          display: none;
        }

        .admission-node {
          cursor: pointer;
          display: block;
          background: #f0f4f8;
          padding: 8px;
          border-radius: 4px;
          border: 1px solid #d1d9e1;
        }

        .admission-node:hover {
          background: #e1e9f0;
        }

        /* Indicador de expansión */
        /*.admission-node::before {
          content: '➕ ';
          font-size: 10px;
        }*/

        /*.open > .admission-node::before {
          content: '➖ ';
        }*/

        .leaf {
          font-weight: normal;
          color: #555;
          padding-left: 15px;
          cursor: default;
        }

        .link-deshabilitado {
            /* Evita que el clic funcione */
            pointer-events: none;
            
            /* Cambia el aspecto visual (gris y transparente) */
            color: #6c757d !important;
            opacity: 0.5;
            
            /* Quita el subrayado si lo tiene */
            text-decoration: none;
            
            /* Cambia el cursor para indicar que no se puede tocar */
            cursor: not-allowed;
        }

        .btn-tree-compact {
            padding-top: 2px !important;
            padding-bottom: 2px !important;
            line-height: 0.2; /* Reduce el espacio entre el texto y los bordes */
            font-size: 0.9rem; /* Opcional: reduce un poco la letra si es necesario */
            text-decoration: none !important; /* Quita el subrayado del link para que se vea más como app médica */
        }

        .nav-pills .nav-link.active,
        .show>.nav-pills .nav-link{
            background: #7FB3D5 !important;
            color: #000000 !important;
        }
        .flex-column .nav-link.active,
        .show>.nav-pills .nav-link{
            background: #b9aca2 !important;
            color: #000000 !important;
        }

        .note-editable {
            font-size: 12pt !important;
        }

        .deshabilitar_registro {
            cursor: not-allowed !important;
            opacity: 0.6;
            /* Evita que los eventos de clic de JavaScript se disparen */
            user-select: none;
        }

        /* Para bloquear el clic de JS si usas jQuery o similar */
        .deshabilitar_registro:active {
            pointer-events: none;
        }

        /* Clase para forzar que el select parezca bloqueado pero siga activo */
        .select-readonly {
            pointer-events: none;
            background-color: #e9ecef !important;
            touch-action: none;
        }

        /* Aplica el estilo de "bloqueado" a textareas e inputs readonly */
        textarea[readonly], 
        input[readonly] {
            background-color: #e9ecef !important; /* Color gris de AdminLTE */
            cursor: not-allowed;
            border-color: #ced4da;
        }

        /* Estilo para Summernote deshabilitado */
        .note-editor.note-frame.disabled {
            background-color: #e9ecef !important;
        }

        .note-editor.note-frame.disabled .note-editable {
            background-color: #e9ecef !important;
            cursor: not-allowed;
        }

        #timer {
            transition: color 0.3s ease;
        }
    </style>
@endsection
@section('title', 'Seguimiento Medico a Admisiones')
@section('content_header')
    
@endsection
@section('content')
    <div class="container-fluid">
        <input type="hidden" id="admision_id" name="admision_id">
        <input type="hidden" id="admision_atencion_id" name="admision_atencion_id">

        <header class="patient-banner">
            <div class="patient-identity">
                <div class="avatar">Sr.</div>
                <div class="info">
                    <h2>
                        {{ $pPaciente->nombre_completo }}
                        <span class="age" id="EdadHtml">45 años</span>
                        <span class="genero">| {{ $pPaciente->genero }}</span>
                    </h2>
                    <span class="age" id="AdmisionNo"><strong>Admisión:</strong></span>
                </div>
            </div>
            <div class="patient-alerts">
                <!-- <div class="alert-box allergy">⚠️ Alergia: Penicilina</div>
                <div class="alert-box risk">⚠️ Riesgo: Caída Alta</div> -->
                <div class="row">
                    <button id="toggleRightSidebar" class="btn btn-xs rounded-circle elevation-4" data-widget="control-sidebar" data-slide="true">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
            <!-- <div class="patient-actions">
                <button class="btn-action order">📝 Nueva Orden</button>
                <button class="btn-action note">✍️ Evolución</button>
            </div> -->
        </header>
        <div id="Antecedentes">
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Antecedentes
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-2 col-sm-12">
                                    <ul class="nav flex-column" style="font-size: 12px; !important">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#AntecedentesMedico" data-toggle="tab">Medico</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#AntecedentesQuuirugico" data-toggle="tab">Quirurgico</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#AntecedentesAlergias" data-toggle="tab">Alergias</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#AntecedentesGinecologico" data-toggle="tab">Ginecologico</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#AntecedentesFamiliares" data-toggle="tab">Familiares</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#AntecedentesMedicamentos" data-toggle="tab">Medicamentos</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#AntecedentesHabitos" data-toggle="tab">Habitos</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-lg-10 col-sm-12">
                                    <div class="tab-content">
                                        <!-- Antecedentes Medicos -->
                                        <div class="active tab-pane" id="AntecedentesMedico">
                                            {{ $pPaciente->antmedico_descripcion }}
                                        </div>
                                        <!-- Antecedentes Quirurgico -->
                                        <div class="tab-pane" id="AntecedentesQuuirugico">
                                            {{ $pPaciente->antquirurgico_descripcion }}
                                        </div>
                                        <!-- Antecedentes Alergias -->
                                        <div class="tab-pane" id="AntecedentesAlergias">
                                            {{ $pPaciente->antalergia_descripcion }}
                                        </div>
                                        <!-- Antecedentes Ginecologico -->
                                        <div class="tab-pane" id="AntecedentesGinecologico">
                                            {{ $pPaciente->antgineco_descripcion }}
                                        </div>
                                        <!-- Antecedentes Familiares -->
                                        <div class="tab-pane" id="AntecedentesFamiliares">
                                            {{ $pPaciente->antfamiliar_descripcion }}
                                        </div>
                                        <!-- Antecedentes Medicamentos -->
                                        <div class="tab-pane" id="AntecedentesMedicamentos">
                                            {{ $pPaciente->antmedicamento_descripcion }}
                                        </div>
                                        <!-- Antecedentes Habitos -->
                                        <div class="tab-pane" id="AntecedentesHabitos">
                                            <p>Cigarro {{ $pPaciente->tabaco_cnt }} - {{ $pPaciente->tabaco_tiempo }}</p>
                                            <p>Bebida {{ $pPaciente->alchohol_cnt }} - {{ $pPaciente->alchohol_tiempo }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Signos Vitales -->
        <div id="signosVitalesDiv" hidden>
            <div class="card">
                <div class="card-header" style="background-color: #E1E8ED;">
                    <div class="row">
                        <div class="col-9">
                            <p>Signos Vitales</p>
                        </div>
                        <div class="col-3" style="text-align: right;">
                            <a href="#" id="btnAgregarVitales" onclick="openModal('modalVitales'); return false;" class="btn btn-xs btn-outline-primary rounded-circle elevation-4 agregar_registro" title="Agregar Signos Vitales"><i class="fas fa-plus"></i></a>
                            <a href="#" onclick="closeDiv('signosVitalesDiv');" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Cerrar Ventana"><i class="fas fa-times"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- <div class="row">
                        <div class="col-1 offset-11" style="text-align: right;">
                            <a href="#" onclick="openModal('modalVitales'); return false;" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" title="Agregar Signos Vitales"><i class="fas fa-plus"></i></a>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-10 offset-1">
                            <table class="table table-sm table-striped table-hover" style="font-size: 12px;" id="tblVitales">
                                <thead>
                                    <tr style="text-align: center;">
                                        <th>Fecha</th>
                                        <th>Usuario</th>
                                        <th>Peso</th>
                                        <th>Talla</th>
                                        <th>IMC</th>
                                        <th>Pulso</th>
                                        <th>Temperatura</th>
                                        <th>Respiraciones</th>
                                        <th>Presión</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Signos Vitales -->
        <!-- Consultas Medicas -->
        <div id="consultasDiv" hidden>
            <div class="card">
                <div class="card-header" style="background-color: #E1E8ED;">
                    <div class="row">
                        <div class="col-9">
                            <p>Consulta Médica</p>
                        </div>
                        <div class="col-3" style="text-align: right;">
                            <a href="#" id="btnAgregarConsultas" onclick="openModal('modalConsultas'); return false;" class="btn btn-xs btn-outline-primary rounded-circle elevation-4 agregar_registro" title="Agregar Registro de Consulta"><i class="fas fa-plus"></i></a>
                            <a href="#" onclick="closeDiv('consultasDiv');" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Cerrar Ventana"><i class="fas fa-times"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-10 offset-1">
                            <table class="table table-sm table-striped table-hover" style="font-size: 12px;" id="tblConsultas">
                                <colgroup>
                                    <col style="width: 15%;"> <col style="width: 15%;"> <col style="width: 65%;"> <col style="width: 5%;"> 
                                </colgroup>
                                <thead>
                                    <tr style="text-align: center;">
                                        <th>Fecha</th>
                                        <th>usuario</th>
                                        <th>Impresión Clinica</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Consultas Medicas -->
        <!-- Procedimientos -->
        <div id="procedimientosDiv" hidden>
            <div class="card">
                <div class="card-header" style="background-color: #E1E8ED;">
                    <div class="row">
                        <div class="col-9">
                            <p>Procedimientos</p>
                        </div>
                        <div class="col-3" style="text-align: right;">
                            <a href="#" id="btnAgregarProcedimientos" onclick="openModal('modalProcedimientos'); return false;" class="btn btn-xs btn-outline-primary rounded-circle elevation-4 agregar_registro" title="Agregar Registro de Consulta"><i class="fas fa-plus"></i></a>
                            <a href="#" onclick="closeDiv('procedimientosDiv');" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Cerrar Ventana"><i class="fas fa-times"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-10 offset-1">
                            <table class="table table-sm table-striped table-hover" style="font-size: 12px;" id="tblProcedimientos">
                                <colgroup>
                                    <col style="width: 15%;"> <col style="width: 15%;"> <col style="width: 65%;"> <col style="width: 5%;"> 
                                </colgroup>
                                <thead>
                                    <tr style="text-align: center;">
                                        <th>Fecha</th>
                                        <th>usuario</th>
                                        <th>Impresión Clinica</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Procedimientos -->
        <!-- hospitalizacion -->
        <div id="hospitalizacionDiv" hidden>
            <div class="card">
                <div class="card-header" style="background-color: #E1E8ED;">
                    <div class="row">
                        <div class="col-9">
                            <p>Hospitalización</p>
                        </div>
                        <div class="col-3" style="text-align: right;">
                            <a href="#" id="btnAgregarHospitalizaciones" onclick="openModal('modalHospitalizacion'); return false;" class="btn btn-xs btn-outline-primary rounded-circle elevation-4 agregar_registro" title="Agregar Registro"><i class="fas fa-plus"></i></a>
                            <a href="#" onclick="closeDiv('hospitalizacionDiv');" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" title="Cerrar Ventana"><i class="fas fa-times"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-10 offset-1">
                            <table class="table table-sm table-striped table-hover" style="font-size: 12px;" id="tblHospitalizaciones">
                                <colgroup>
                                    <col style="width: 15%;"> <col style="width: 15%;"> <col style="width: 65%;"> <col style="width: 5%;"> 
                                </colgroup>
                                <thead>
                                    <tr style="text-align: center;">
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Final</th>
                                        <th>Comentario</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /hospitalizacion -->
        <!-- barra lateral derecha -->
        <aside class="control-sidebar">
            <ul id="tree-medico" class="tree" style="font-size: 12px;">
                @foreach($listado as $admision)
                    <li>
                        <span class="admission-node" id="{{ $admision->id }}" value="{{ $admision->admision_no }}" data-revisado="{{ $admision->encabezado_revisado }}" data-estado="{{ $admision->estado }}" data-atencion="{{ $admision->inicio_atencion_medica }}">
                            📋 Admisión: #{{ $admision->admision_no }} --- {{ $admision->fecha }}
                        </span>
                        <ul>
                            <br>
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1" style="display: flex; align-items: center; justify-content: space-between;">
        
                                    <div id="cronometro-container-{{ $admision->id }}" class="cronometro-instancia" style="display: none;"> 
                                        <span style="font-size: 1.2rem; font-family: 'monospace'; font-weight: bold;">
                                            <i class="fas fa-stopwatch text-muted mr-2"></i> 
                                            <span id="timer-{{ $admision->id }}" class="timer-text">00:00:00</span>
                                        </span>
                                    </div>

                                    <div style="display: flex; gap: 8px;">
                                        <a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4 inicio-atencion" title="Iniciar Atención" onclick="fnInicioAtencion();">
                                            <i class="fas fa-lock-open"></i>
                                        </a>
                                        <a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4 final-atencion" title="Finalizar Atención" onclick="fnFinalAtencion();">
                                            <i class="fas fa-lock"></i>
                                        </a>
                                    </div>

                                </div>
                            </div>
                            <li><span class="leaf leaf-link">🔬 Laboratorios</span></li>
                            <li>
                                <span class="leaf leaf-link">
                                    <a href="#" onclick="openDiv(this, 'signosVitalesDiv'); return false;">❤️ Signos Vitales</a>
                                </span>
                            </li>
                            <li>
                                <span class="leaf leaf-link">
                                    <a href="#" onclick="openDiv(this, 'consultasDiv'); return false;">👨‍⚕️ Consultas</a>
                                </span>
                            </li>
                            <li>
                                <span class="leaf leaf-link">
                                    <a href="#" onclick="openDiv(this, 'procedimientosDiv'); return false;">💉 Procedimientos</a>
                                </span>
                            </li>
                            <li>
                                <span class="leaf leaf-link">
                                    <a href="#" onclick="openDiv(this, 'hospitalizacionDiv'); return false;">🏥 Hospitalizaciones</a>
                                </span>
                            </li>
                        </ul>
                    </li>
                @endforeach
            </ul>
        </aside>
        <!-- /barra lateral derecha -->
        <!-- Vitales -->
        <div class="modal fade" id="modalVitales" role="dialog" aria-labelledby="modalVitalesModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <form id="formVitales" class="form" method="POST" action="#">
                        @csrf
                        <div class="card">
                            <div class="card-header" style="background-color: #E1E8ED;">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h5>Signos Vitales</h5>
                                    </div>
                                    <div class="col-md-3" style="text-align: right;">
                                        <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar cambios"><i class="fas fa-save"></i></button>
                                        <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-sign-out-alt"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <input type="hidden" id="vitales_admision_id" name="vitales_admision_id">
                                <input type="hidden" id="vitales_atencion_id" name="vitales_atencion_id" value="0">
                                <div class="row">
                                    <div class="col-md-5 offset-md-1 input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Peso</label>
                                        </div>
                                        <input type="number" min="0" step="any" class="form-control" id="peso" name="peso" placeholder="Kgs." style="text-align: right;" required>
                                    </div>
                                    <div class="col-md-5 input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Talla</label>
                                        </div>
                                        <input type="number" min="0" step="any" class="form-control" id="talla" name="talla" placeholder="mts." style="text-align: right;" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5 offset-md-1 input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">IMC</label>
                                        </div>
                                        <input type="number" step="any" class="form-control" id="imc" name="imc" placeholder="" style="text-align: right;" readonly>
                                    </div>
                                    <div class="col-md-5 input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Pulso</label>
                                        </div>
                                        <input type="number" min="0" step="1" class="form-control" id="pulso" name="pulso" placeholder="ppm" style="text-align: right;" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5 offset-md-1 input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Temperatura</label>
                                        </div>
                                        <input type="number" min="30" step="any" class="form-control" id="temperatura" name="temperatura" placeholder="°C" style="text-align: right;" required>
                                    </div>
                                    <div class="col-md-5 input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Respiraciones</label>
                                        </div>
                                        <input type="number" min="0" step="1" class="form-control" id="respiraciones" name="respiraciones" placeholder="" style="text-align: right;" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5 offset-md-1 input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Presión Sistolica</label>
                                        </div>
                                        <input type="number" class="form-control" id="presion_sistolica" name="presion_sistolica" placeholder="000" style="text-align: right;" required>
                                    </div>
                                    <div class="col-md-5 input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Presión Diastolica</label>
                                        </div>
                                        <input type="number" class="form-control" id="presion_diastolica" name="presion_diastolica" placeholder="000" style="text-align: right;" required>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-10 offset-1">
                                        <figure class="highcharts-figure">
                                            <div id="container"></div>
                                            <p class="highcharts-description">
                                            </p>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Vitales-->

        <!-- modal Consultas -->
        <div class="modal fade" id="modalConsultas" role="dialog" aria-labelledby="modalConsultasModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <form id="formConsultas" class="form" method="POST" action="#">
                        @csrf
                        <!-- Detalle de consulta -->
                        <div class="card">
                            <div class="card-header" style="background-color: #E1E8ED;">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h5>Consulta Médica</h5>
                                    </div>
                                    <div class="col-md-3" style="text-align: right;">
                                        <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar cambios"><i class="fas fa-save"></i></button>
                                        <a href="#" id="impresion_receta" class="btn btn-xs btn-danger rounded-circle elevation-4" target="_blank" title="Impresión de Receta" onclick="generar_receta(); return false;" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                        <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <input type="hidden" id="consulta_admision_id" name="consulta_admision_id">
                                <input type="hidden" id="consulta_atencion_id" name="consulta_atencion_id" value="0">
                                <div class="row">
                                    <div class="col-lg-2 col-sm-12">
                                        <ul class="nav flex-column" style="font-size: 12px; !important">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#subjetivo" data-toggle="tab">Subjetivos</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#objetivo" data-toggle="tab">Objetivos</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#impresion_clinica" data-toggle="tab">Impresión Clinica</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#plan" data-toggle="tab">Plan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#tratamiento" data-toggle="tab">Tratamiento</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-10 col-sm-12">
                                        <div class="tab-content">
                                            <!-- Datos Subjetivos -->
                                            <div class="active tab-pane" id="subjetivo">
                                                <div class="row">
                                                    <div class="form-group col-md-10 offset-md-1">
                                                        <label for="consulta_subjetivo">Descripción</label>
                                                        <textarea class="form-control form-control-sm summernote" id="consulta_subjetivo" name="consulta_subjetivo" rows="8" maxlength="975" style="text-align: justify;" placeholder="Se obtienen a través de la comunicación directa con el paciente"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /Datos Subjetivos -->
                                            <!-- Datos Objetivos -->
                                            <div class="tab-pane" id="objetivo">
                                                <div class="row">
                                                    <div class="form-group col-md-10 offset-md-1">
                                                        <label for="consulta_objetivo">Descripción</label>
                                                        <textarea class="form-control form-control-sm summernote" id="consulta_objetivo" name="consulta_objetivo" rows="8" placeholder="Se basan en pruebas y exámenes médicos cuantificables, como análisis de sangre, radiografías o mediciones de presión arterial"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /Datos Objetivos -->
                                            <!-- Impresion Clinica -->
                                            <div class="tab-pane" id="impresion_clinica">
                                                <div class="row">
                                                    <div class="form-group col-md-10 offset-md-1">
                                                        <label for="consulta_impresion_clinica">Descripción</label>
                                                        <textarea class="form-control form-control-sm summernote" id="consulta_impresion_clinica" name="consulta_impresion_clinica" rows="8" placeholder="La impresión clínica global es una medida subjetiva de la gravedad de los síntomas y de la eficacia del tratamiento realizada por su médico a partir de su experiencia."></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /Impresion Clinica -->
                                            <!-- Plan -->
                                            <div class="tab-pane" id="plan">
                                                <div class="row">
                                                    <div class="form-group col-md-10 offset-md-1">
                                                        <label for="consulta_plan">Descripción</label>
                                                        <textarea class="form-control form-control-sm summernote" id="consulta_plan" name="consulta_plan" rows="8" placeholder="Plan detallado que se entrega a un paciente después de terminar el tratamiento; contiene un resumen del tratamiento y recomendaciones para el seguimiento de la atención."></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /Plan -->
                                            <!-- Tratamiento -->
                                            <div class="tab-pane" id="tratamiento">
                                                <!-- <div class="row">
                                                    <div class="mb-1 col-lg-5 col-sm-5 offset-lg-1 offset-sm-1">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" for="tratamiento_medicamento_id">Medicamento</span>
                                                            </div>
                                                            <select class="custom-select  custom-select-sm select2 select2bs4" id="tratamiento_medicamento_id" name="tratamiento_medicamento_id" onchange="fn_actualizar_dosis(); return false;">
                                                                <option selected>Seleccionar...</option>
                                                                @foreach($pMedicamentos as $pM)
                                                                <option value="{{ $pM->id }}">{{ $pM->descripcion }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="mb-1 col-lg-4 col-sm-4">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" for="tratamiento_dosis_id">Dosis</span>
                                                            </div>
                                                            <select class="custom-select  custom-select-sm select2 select2bs4" id="tratamiento_dosis_id" name="tratamiento_dosis_id">
                                                                <option selected>Seleccionar...</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="mb-1 col-lg-2 col-sm-2" style="text-align: right;">
                                                        <a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="copiarDosis(); return false;"><i class="fas fa-plus"></i></a>
                                                    </div>
                                                </div> -->
                                                <div class="row">
                                                    <div class="col-5 offset-1 mb-1 input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" for="tratamiento_medicamento_id">Medicamento</span>
                                                        </div>
                                                        <select id="tratamiento_medicamento_id" name="tratamiento_medicamento_id" class="form-control" data-required="true" onchange="actualizarDosis(this)">
                                                            <option value="">Seleccionar...</option>
                                                            @foreach($pMedicamentos as $pM)
                                                                <option value="{{ $pM->id }}">{{ $pM->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-5 mb-1 input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" for="tratamiento_dosis_id">Dosis</span>
                                                        </div>
                                                        <select id="tratamiento_dosis_id" name="tratamiento_dosis_id" class="form-control" data-required="true" onchange="copiarDosis()">
                                                            <option value="">Seleccionar...</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-1 col-1" style="text-align: right;">
                                                        <a href="#" class="btn btn-xs btn-outline-primary rounded-circle elevation-4" onclick="agregarRegistroMedicamento(); return false;" title="Agregsar medicamento"><i class="fas fa-hand-pointer"></i></a>
                                                    </div>
                                                </div>
                                                <input type="hidden" class="form-control" id="tratamiento_descripcion" name="tratamiento_descripcion">
                                                <div class="row">
                                                    <div class="form-group col-md-10 offset-md-1">
                                                        <label for="consulta_tratamiento"></label>
                                                        <textarea class="form-control form-control-sm summernote" id="consulta_tratamiento" name="consulta_tratamiento" rows="4"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /Tratamiento -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Detalle de consulta -->
                    </form>
                </div>
            </div>
        </div>
        <!-- /modal Consultas -->
        <!-- modal Procedimientos -->
        <div class="modal fade" id="modalProcedimientos" role="dialog" aria-labelledby="modalProcedimientosModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <form id="formProcedimientos" class="form" method="POST" action="#" enctype="multipart/form-data">
                        @csrf
                        <!-- Detalle de consulta -->
                        <div class="card">
                            <div class="card-header" style="background-color: #E1E8ED;">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h5>Procedimiento</h5>
                                    </div>
                                    <div class="col-md-3" style="text-align: right;">
                                        <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar cambios"><i class="fas fa-save"></i></button>
                                        <a href="#" id="impresion_receta" class="btn btn-xs btn-danger rounded-circle elevation-4" target="_blank" title="Informe Medico" onclick="generar_informe(); return false;"><i class="fas fa-file-pdf"></i></a>
                                        <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <input type="hidden" id="procedimiento_admision_id" name="procedimiento_admision_id">
                                <input type="text" id="procedimiento_atencion_id" name="procedimiento_atencion_id">
                                <div class="row">
                                    <div class="col-lg-2 col-sm-12">
                                        <ul class="nav flex-column" style="font-size: 12px; !important">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#procedimiento" data-toggle="tab">Procedimiento</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#indicacion" data-toggle="tab">Indicación</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#hallazgos" data-toggle="tab">Hallazgos</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#diagnostico" data-toggle="tab">Diagnostico</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#recomendacion" data-toggle="tab">Recomendación</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#imagenes" data-toggle="tab">Imagenes</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-10 col-sm-12">
                                        <div class="tab-content">
                                            <!-- Procedimiento -->
                                            <div class="active tab-pane" id="procedimiento">
                                                <div class="row">
                                                    <div class="mb-1 col-7 offset-1">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text" for="p_procedimiento_id">Procedimiento</label>
                                                            </div>
                                                            <select class="custom-select custom-select-sm select2 select2bs4" id="p_procedimiento_id" name="p_procedimiento_id">
                                                                <option selected>Seleccionar...</option>
                                                                @foreach($pProcedimientos as $pProcedimiento)
                                                                    <option value="{{ $pProcedimiento->id }}">{{ $pProcedimiento->descripcion }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="mb-1 col-7 offset-1">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text" for="p_premedicacion_id">Premedicación</label>
                                                            </div>
                                                            <select class="custom-select custom-select-sm select2 select2bs4" id="p_premedicacion_id" name="p_premedicacion_id">
                                                                <option selected>Seleccionar...</option>
                                                                @foreach($premedicacion as $p)
                                                                    <option value="{{ $p->id }}">{{ $p->descripcion }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="mb-1 col-7 offset-1">
                                                        <div class="form-group form-control-sm clearfix">
                                                            <label>Tolencia</label>
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" id="bueno" name="tolerncia" value="B" checked>
                                                                <label for="bueno">Buena</label>
                                                            </div>
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" id="regular" name="tolerncia" value="R">
                                                                <label for="regular">Regular</label>
                                                            </div>
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" id="mala" name="tolerncia" value="M">
                                                                <label for="mala">Mala</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-group input-group-sm col-7 offset-1 mb-1 ">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Anestesiólogo</label>
                                                        </div>
                                                        <input type="text" class="form-control" id="panestesiologo" name="panestesiologo" value="{{ old('panestesiologo') }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-group input-group-sm col-7 offset-1 mb-1 ">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text">Patólogo</label>
                                                        </div>
                                                        <input type="text" class="form-control" id="ppatologo" name="ppatologo" value="{{ old('ppatologo') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /Procedimiento -->
                                            <!-- indicacion -->
                                            <div class="tab-pane" id="indicacion">
                                                <div class="row">
                                                    <div class="form-group col-md-10 offset-md-1">
                                                        <textarea class="form-control form-control-sm summernote" id="pindicacion" name="pindicacion" maxlength="975" style="text-align: justify;" placeholder="Sintomatología principal, tiempo de evolución, signos de alarma y objetivo del estudio (ej. tamizaje, diagnóstico o seguimiento)."></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /indicacion -->
                                            <!-- hallazgos -->
                                            <div class="tab-pane" id="hallazgos">
                                                <div class="row">
                                                    <div class="form-group col-md-10 offset-md-1">
                                                        <textarea class="form-control form-control-sm summernote" id="phallazgos" name="phallazgos" maxlength="975" style="text-align: justify;" placeholder="Descripción técnica "></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /hallazgos -->
                                            <!-- diagnostico -->
                                            <div class="tab-pane" id="diagnostico">
                                                <div class="row">
                                                    <div class="form-group col-md-10 offset-md-1">
                                                        <textarea class="form-control form-control-sm summernote" id="pdiagnostico" name="pdiagnostico" maxlength="975" style="text-align: justify;" placeholder="La conclusión sindromática"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /diagnostico -->
                                            <!-- recomendacion -->
                                            <div class="tab-pane" id="recomendacion">
                                                <div class="row">
                                                    <div class="form-group col-md-10 offset-md-1">
                                                        <textarea class="form-control form-control-sm summernote" id="precomendacion" name="precomendacion" maxlength="975" style="text-align: justify;" placeholder="Plan a seguir"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /recomendacion -->
                                            <!-- imagenes -->
                                            <div class="tab-pane" id="imagenes">
                                                <div class="card card-outline card-success">
                                                    <div class="card-header">
                                                        <h3 class="card-title">Cargar Imágenes</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="imagenes">Seleccione una o varias imágenes</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input type="file" name="imagenes[]" class="custom-file-input" id="imagenes" multiple accept="image/*">
                                                                    <label class="custom-file-label" for="imagenes">Elegir archivos...</label>
                                                                </div>
                                                            </div>
                                                            <small class="text-muted">Formatos permitidos: JPG, PNG, WEBP. Máximo 2MB por foto.</small>
                                                        </div>
                                                        <div id="preview-container" class="row mt-3" style="padding: 10px;"></div>                              
                                                    </div>
                                                    <div class="card-footer">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /imagenes -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /modal Procedimientos -->
        <!-- modal Hospitalizaciones -->
        <div class="modal fade" id="modalHospitalizacion" role="dialog" aria-labelledby="hospitalizacionesModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <form id="formHospitalizaciones" class="form" method="POST" action="#">
                        @csrf
                        <!-- Detalle de consulta -->
                        <div class="card">
                            <div class="card-header" style="background-color: #E1E8ED;">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h5>Hospitalización</h5>
                                    </div>
                                    <div class="col-md-3" style="text-align: right;">
                                        <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-4" title="Guardar cambios"><i class="fas fa-save"></i></button>
                                        <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-4" data-dismiss="modal" title="Cerrar Ventana"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <input type="hidden" id="hospitalizacion_admision_id" name="hospitalizacion_admision_id">
                                <input type="hidden" id="hospitalizacion_atencion_id" name="hospitalizacion_atencion_id" value="0">
                                <div class="row">
                                    <div class="col-md-5 offset-md-1 input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Del</label>
                                        </div>
                                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ $hoy }}" autofocus required>
                                    </div>
                                    <div class="col-md-5 input-group input-group-sm mb-1">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text">Al</label>
                                        </div>
                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="{{ $hoy }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-10 offset-md-1">
                                        <label for="resumen_egreso">Comentario</label>
                                        <textarea class="form-control form-control-sm summernote" id="resumen_egreso" name="resumen_egreso" maxlength="975" style="text-align: justify;" placeholder=""></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /modal Hospitalizaciones -->
        <!-- modal Visualizacion de imagen -->
        <div class="modal fade" id="modalVisorImagen" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content" style="background-color: rgba(0,0,0,.9); border: none;">
                    <div class="modal-header" style="border: none;">
                        <h5 class="modal-title text-white" id="nombreImagenModal">Vista Previa</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 2rem;">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center p-0">
                        <img src="" id="imgVisorFull" class="img-fluid rounded shadow-lg" style="max-height: 85vh;">
                    </div>
                </div>
            </div>
        </div>
        <!-- /modal Visualizacion de imagen -->
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/Highcharts-11.1.0/js/highcharts.js') }}"></script>
    <script src="{{ asset('assets/Highcharts-11.1.0/modules/accessibility.js') }}"></script>
    <script src="{{ asset('assets/Highcharts-11.1.0/js/highcharts-more.js') }}"></script>
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
        var nLinea =  0;
        let archivosMaster = [];

        var admision_id = null;
        var admision_no = null;
        var admision_revisado = null;
        var admision_estado   = null;
        var admision_atencion_medica = null;

        let timerInterval;
        let startTime;
        const paciente = @json($pPaciente);


        // Usamos esta forma para que '$' siempre funcione dentro
        (function($) {
            "use strict";

            // Definimos la función y la asignamos a 'window' para que sea accesible desde el HTML
            window.abrirModal = function(modalId) {
                var $modal = $("#" + modalId);
                
                if ($modal.length > 0) {
                    $modal.modal('show');
                } else {
                    console.error("No se encontró el modal con ID: " + modalId);
                }
            };

            $(document).ready(function() {
                // Aquí puedes poner otros inicializadores como Select2 o Datatables
                console.log("jQuery cargado y listo");
            });

        })(jQuery);

        //=======================================================================
        // Inicializa libreria Summernote
        //=======================================================================
        $(document).ready(function() {
            // if (typeof $.fn.summernote !== 'undefined') {
            //     // 2. Inicialización corregida para evitar el error de Tooltip
            //     var placeholderPersonalizado = $(this).attr('placeholder');
            //     $('.summernote').summernote({
            //         placeholder: placeholderPersonalizado,
            //         tabsize: 2,
            //         height: 200,
            //         lang: 'es-ES',
            //         // ESTA LÍNEA ES VITAL: Desactiva los tooltips nativos de Summernote 
            //         // que chocan con Bootstrap/jQuery UI
            //         buttons: {
            //             tooltip: false 
            //         },
            //         tooltip: false, 
            //         toolbar: [
            //             // ['style', ['style']],
            //             ['font', ['bold', 'underline', 'clear', 'fontsize', 'fontname']],
            //             ['color', ['color']],
            //             ['para', ['ul', 'ol', 'paragraph']],
            //             // ['table', ['table']],
            //             // ['insert', ['link', 'picture', 'video']],
            //             // ['view', ['fullscreen', 'codeview', 'help']]
            //         ],
            //         fontSizes: ['8', '9', '10', '11', '12', '14', '18', '24', '36'], // Lista de opciones
        
            //         // --- ESTO ESTABLECE EL TAMAÑO POR DEFECTO ---
            //         callbacks: {
            //             onInit: function() {
            //                 // Selecciona el tamaño 12 en el dropdown y aplica el estilo al editor
            //                 $(this).summernote('fontSize', '12');
            //             }
            //         }
            //     });
            // }
            $('.summernote').each(function() {
                // Lee el placeholder que pusiste en el HTML de este textarea específico
                var placeholderIndividual = $(this).attr('placeholder');
                
                $(this).summernote({
                    placeholder: placeholderIndividual, // Asigna el suyo propio
                    tabsize: 2,
                    height: 200,
                    lang: 'es-ES',
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ]
                });
            });
        });

        //=======================================================================
        // Confirmar Salida de pantalla
        //=======================================================================
        function confirma_salida(){
            var origen   = document.getElementById('origen').value;
            var paciente = document.getElementById('paciente_id').value;
            Swal.fire({
                title: 'Confirmación',
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
        }

        document.getElementById('toggleRightSidebar').addEventListener('click', function () {
            let sidebar = document.querySelector('.control-sidebar');
            let content = document.querySelector('.content-wrapper');

            // Alternar la clase para abrir/cerrar la barra lateral derecha
            sidebar.classList.toggle('control-sidebar-open');
            content.classList.toggle('push-content');
        });

        
        $(document).ready(function() {
            $('.admission-node').click(function(e) {
                var elemento = $(this);
                // 1. CERRAR TODOS LOS DIVS DE CONTENIDO ABIERTOS
                // Buscamos todos los divs que terminan en 'Div' y los ocultamos
                $('#signosVitalesDiv, #consultasDiv, #procedimientosDiv, #hospitalizacionDiv').hide().attr('hidden', true);

                // 2. Lógica original del árbol (abrir/cerrar submenú)
                var $submenu = $(this).next('ul');
                $submenu.slideToggle(200);
                $(this).parent().toggleClass('open');

                // 3. Resetear el estilo visual de selección
                $('.admission-node').css('background-color', 'transparent');
                $(this).css('background-color', '#e3f2fd');
                
                // Actualizar el número de admisión en el banner superior si tiene valor
                var noAdmision = $(this).attr('value');
                if(noAdmision) {
                    document.getElementById('AdmisionNo').innerHTML = "<strong>Admisión: </strong>" + noAdmision;
                    document.getElementById('admision_id').value = $(this).attr('id');
                    admision_id = document.getElementById('admision_id').value;
                    generalesAdmision();
                }
            });

            $("#EdadHtml").text(paciente.edad+' años'); // Tu lógica existente

            // $('.admission-node').on('click', function() {
            //     // 1. Identificamos el <li> padre de este nodo
            //     var $actualLi = $(this).closest('li');
                
            //     // 2. Cerramos todos los demás <li> que estén al mismo nivel
            //     $actualLi.siblings().each(function() {
            //         $(this).find('ul').slideUp(); // O .hide()
            //         $(this).removeClass('open');  // Por si usas clases de CSS para los iconos (+/-)
            //     });

            //     // 3. Abrimos el actual (toggle)
            //     $(this).next('ul').slideToggle();

            //     // 4. Tu lógica de los botones (usando clases en lugar de IDs)
            //     var admision_atencion_id = parseInt($(this).data('atencion'), 10);
            //     actualizarBotones($(this), admision_atencion_id);
            // });
            $('.admission-node').on('click', function(e) {
                // 1. Identificamos el <li> que contiene el nodo clickeado
                var $liPadre = $(this).closest('li');
                
                // 2. Buscamos el <ul> interno de este nodo (el que queremos expandir)
                var $miLista = $(this).siblings('ul'); 

                // 3. COLAPSAR LOS DEMÁS: 
                // Buscamos todos los <li> hermanos, entramos en sus <ul> y los cerramos
                $liPadre.siblings().find('ul').slideUp();
                
                // Opcional: remover clases de estilo de los hermanos para que visualmente se vean cerrados
                $liPadre.siblings().removeClass('active');

                // 4. ACCIÓN SOBRE EL ACTUAL:
                // Hacemos que el <ul> de este nodo se despliegue (slideDown) 
                // o alterne (slideToggle) si quieres que se cierre al clickear de nuevo
                $miLista.slideDown(); 
                $liPadre.addClass('active');

                // Aquí iría tu lógica de actualización de botones que vimos antes...
                var estadoAtencion = parseInt($(this).data('atencion'), 10);
                actualizarBotones($(this), estadoAtencion);
            });
        });

        function actualizarBotones($nodo, estado) {
            // Buscamos los botones SOLO dentro del <li> donde se hizo clic
            var $contenedor = $nodo.closest('li');
            var $btnInicio = $contenedor.find('.inicio-atencion');
            var $btnFinal = $contenedor.find('.final-atencion');

            switch (estado) {
                case 0:
                    $btnInicio.removeClass('link-deshabilitado').css('pointer-events', 'auto');
                    $btnFinal.addClass('link-deshabilitado').css('pointer-events', 'none');
                    break;
                case 1:
                    $btnInicio.addClass('link-deshabilitado').css('pointer-events', 'none');
                    $btnFinal.removeClass('link-deshabilitado').css('pointer-events', 'auto');
                    break;
                case 2:
                    $btnInicio.addClass('link-deshabilitado').css('pointer-events', 'none');
                    $btnFinal.addClass('link-deshabilitado').css('pointer-events', 'none');
                    break;
            }
        }

        function generalesAdmision(){
            $.ajax({
                url: "{{ route('trae_generales_admision') }}", // La ruta de Laravel
                method: "POST",
                data: {"_token": "{{ csrf_token() }}",
                       admision_id: admision_id},
                success: function(response) {
                    admision_id = response.id;
                    admision_no = response.admision_no;
                    admision_estado = response.estado;
                    admision_atencion_medica = Number(response.atencion_medica);
                    let segundos_iniciales = Number(response.segundos_atencion) || 0;

                    // Reiniciamos estilos por defecto
                    clearInterval(timerInterval);
                    $('#timer').css('color', '#2d3748'); // Color normal

                    if (admision_atencion_medica > 0) {
                        $('#cronometro-container').css('visibility', 'visible').show();
                    }

                    // switch (admision_atencion_medica) {
                    //     case 0:
                    //         console.log(segundos_iniciales);
                    //         clearInterval(timerInterval);
                    //         actualizarTimerManual(0);
                    //         $('.inicio-atencion')
                    //             .removeClass('link-deshabilitado')
                    //             .prop('disabled', false) // Por si es un <button>
                    //         .css({
                    //             'pointer-events': 'auto',
                    //             'opacity': '1'
                    //         });
                    //         $('.final-atencion').addClass('link-deshabilitado');
                    //         $('.agregar_registro').addClass('link-deshabilitado');
                    //         break;
                    //     case 1:
                    //         startTime = Date.now() - (segundos_iniciales * 1000);
                    //         timerInterval = setInterval(actualizarTimer, 1000);
                    //         $('.inicio-atencion').addClass('link-deshabilitado');
                    //         $('.final-atencion').removeClass('link-deshabilitado');
                    //         $('.agregar_registro').removeClass('link-deshabilitado');
                    //         break;
                    //     case 2:
                    //         console.log(segundos_iniciales);
                    //         clearInterval(timerInterval);
                    //         actualizarTimerManual(segundos_iniciales);
                    //         $('#timer').css('color', '#dc3545'); // Rojo
                    //         $('.inicio-atencion')
                    //             .addClass('link-deshabilitado')
                    //             .prop('disabled', true) // Por si es un <button>
                    //         .css({
                    //             'pointer-events': 'none',
                    //             'opacity': '0.5'
                    //         });
                    //         $('.final-atencion').addClass('link-deshabilitado');
                    //         $('.agregar_registro').addClass('link-deshabilitado');
                    //         break;
                    // }
                    switch (admision_atencion_medica) {
                        case 0:
                            $('.cronometro-instancia').hide();
                            $('#cronometro-container-' + admision_id).show().css('visibility', 'visible');
                            actualizarTimerManual(0);
                            $('#timer-' + admision_id).css('color', '#gray');
                            
                            $('.inicio-atencion')
                                .removeClass('link-deshabilitado')
                                .prop('disabled', false)
                                .css({ 'pointer-events': 'auto', 'opacity': '1' });
                            $('.final-atencion').addClass('link-deshabilitado');
                            $('.agregar_registro').addClass('link-deshabilitado');
                            break;

                        case 1:
                            $('.cronometro-instancia').hide(); 
                            $('#cronometro-container-' + admision_id).show().css('visibility', 'visible');
                            
                            startTime = Date.now() - (segundos_iniciales * 1000);
                            timerInterval = setInterval(actualizarTimer, 1000);
                            
                            $('#timer').css('color', '#A6CAE2'); // Color normal (azul/negro)
                            $('.inicio-atencion').addClass('link-deshabilitado');
                            $('.final-atencion').removeClass('link-deshabilitado');
                            $('.agregar_registro').removeClass('link-deshabilitado');
                            break;

                        case 2:
                            $('.cronometro-instancia').hide();
                            $('#cronometro-container-' + admision_id).show().css('visibility', 'visible');
                            actualizarTimerManual(segundos_iniciales);
                            $('#timer-' + admision_id).css('color', '#609E33');
                            
                            $('.inicio-atencion')
                                .addClass('link-deshabilitado')
                                .prop('disabled', true)
                                .css({ 'pointer-events': 'none', 'opacity': '0.5' });
                            $('.final-atencion').addClass('link-deshabilitado');
                            $('.agregar_registro').addClass('link-deshabilitado');
                            break;
                    }
                },
                error: function(xhr) {
                    // Aquí manejas errores (validaciones de Laravel, etc.)
                    let errores = xhr.responseJSON.errors;
                    // console.error("Error al guardar:", errores);
                    // alert("Hubo un error al procesar la solicitud.");
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
                }
            });
        }

        // $(document).on('click', '.admission-node', function(e) {
        //     generalesAdmision();
        // });

        $(document).on('hidden.bs.modal', '.modal', function () {
            if (window.ID_ADMISION_ACTIVA) {
                $('#admision_id').val(window.ID_ADMISION_ACTIVA);
            }
        });

        function openDiv(elemento, div) {
            if ($('#' + div).is(':hidden')) {
                switch (div) {
                    case 'signosVitalesDiv':
                        actualizarTablaVitales(admision_id);
                        break;
                    case 'consultasDiv':
                        actualizarTablaConsulta(admision_id);
                        break;
                    case 'procedimientosDiv':
                        actualizarTablaProcedimiento(admision_id);
                        break;
                    case 'hospitalizacionDiv':
                        actualizarTablaHospializacion(admision_id);
                        break;
                    // Agrega aquí los demás casos (consultas, procedimientos) cuando implementes sus funciones
                }
                
                // Mostrar el div solicitado sin cerrar los otros de la misma admisión
                $('#' + div).fadeIn().removeAttr('hidden');
            }
        }

        function openConsulta(elemento){

        }

        function actualizarTablaVitales(admision_id){
            $.ajax({
                url: "{{ route('trae_lista_vitales') }}", // La ruta de Laravel
                method: "POST",
                data: {"_token": "{{ csrf_token() }}",
                       admision_id: admision_id},
                success: function(response) {
                    var html = '';
                    for (var i = 0; i < response.length; i++) {
                        html += '<tr style="text-align: center;">'
                        html += '<td>'
                        html += response[i]['created_at']
                        html += '</td>'
                        html += '<td>'
                        html += response[i]['username']
                        html += '</td>'
                        html += '<td>'
                        html += response[i]['peso']
                        html += '</td>'
                        html += '<td>'
                        html += response[i]['talla']
                        html += '</td>'
                        html += '<td>'
                        html += response[i]['bmi']
                        html += '</td>'
                        html += '<td>'
                        html += response[i]['pulso']
                        html += '</td>'
                        html += '<td>'
                        html += response[i]['temperatura']
                        html += '</td>'
                        html += '<td>'
                        html += response[i]['respiracion']
                        html += '</td>'
                        html += '<td>'
                        html += response[i]['presion']
                        html += '</td>'
                        html += '<td>'
                        html += '<a href="#" class="btn btn-xs btn-warning rounded-circle elevation-4" title="Editar Registro" '
                        html += 'onclick="fn_edicion_vitales('+response[i]['id']+')"><i class="fas fa-edit"></i></a>'
                        html += '</td>'
                        html += '</tr>'
                    }
                    $("#tblVitales tbody tr").remove();
                    $('#tblVitales tbody').append(html);
                },
                error: function(xhr) {
                    // Aquí manejas errores (validaciones de Laravel, etc.)
                    let errores = xhr.responseJSON.errors;
                    // console.error("Error al guardar:", errores);
                    // alert("Hubo un error al procesar la solicitud.");
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
                }
            });
        }

        function actualizarTablaHospializacion(admision_id){
            $.ajax({
                url: "{{ route('trae_lista_hospitalizaciones') }}", // La ruta de Laravel
                method: "POST",
                data: {"_token": "{{ csrf_token() }}",
                       admision_id: admision_id},
                success: function(response) {
                    var html = '';
                    for (var i = 0; i < response.length; i++) {
                        html += '<tr style="text-align: center;">'
                        html += '<td>'
                        html += response[i]['hfecha_inicio']
                        html += '</td>'
                        html += '<td>'
                        html += response[i]['hfecha_fin']
                        html += '</td>'
                        html += '<td>'
                        html += response[i]['hresumen']
                        html += '</td>'
                        html += '<td>'
                        html += '<a href="#" class="btn btn-xs btn-warning rounded-circle elevation-4" title="Editar Registro" '
                        html += 'onclick="fn_edicion('+response[i]['id']+')"><i class="fas fa-edit"></i></a>'
                        html += '</td>'
                        html += '</tr>'
                    }
                    $("#tblHospitalizaciones tbody tr").remove();
                    $('#tblHospitalizaciones tbody').append(html);
                },
                error: function(xhr) {
                    // Aquí manejas errores (validaciones de Laravel, etc.)
                    let errores = xhr.responseJSON.errors;
                    // console.error("Error al guardar:", errores);
                    // alert("Hubo un error al procesar la solicitud.");
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
                }
            });
        }

        function actualizarTablaConsulta(admision_id){
            $.ajax({
                url: "{{ route('trae_lista_consultas') }}", // La ruta de Laravel
                method: "POST",
                data: {"_token": "{{ csrf_token() }}",
                       admision_id: admision_id},
                success: function(response) {
                    var html = '';
                    for (var i = 0; i < response.length; i++) {
                        html += '<tr style="text-align: center;">'
                        html += '<td>'
                        html += response[i]['created_at']
                        html += '</td>'
                        html += '<td>'
                        html += response[i]['username']
                        html += '</td>'
                        html += '<td>'
                        html += response[i]['cimpresion_clinica']
                        html += '</td>'
                        html += '<td>'
                        html += '<a href="#" class="btn btn-xs btn-warning rounded-circle elevation-4" title="Editar Registro" '
                        html += 'onclick="fn_edicion('+response[i]['id']+')"><i class="fas fa-edit"></i></a>'
                        html += '</td>'
                        html += '</tr>'
                    }
                    $("#tblConsultas tbody tr").remove();
                    $('#tblConsultas tbody').append(html);
                },
                error: function(xhr) {
                    // Aquí manejas errores (validaciones de Laravel, etc.)
                    let errores = xhr.responseJSON.errors;
                    // console.error("Error al guardar:", errores);
                    // alert("Hubo un error al procesar la solicitud.");
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
                }
            });
        }

        function actualizarTablaProcedimiento(admision_id){
            $.ajax({
                url: "{{ route('trae_lista_procedimientos') }}", // La ruta de Laravel
                method: "POST",
                data: {"_token": "{{ csrf_token() }}",
                       admision_id: admision_id},
                success: function(response) {
                    var html = '';
                    for (var i = 0; i < response.length; i++) {
                        html += '<tr style="text-align: center;">'
                        html += '<td>'
                        html += response[i]['created_at']
                        html += '</td>'
                        html += '<td>'
                        html += response[i]['username']
                        html += '</td>'
                        html += '<td>'
                        html += response[i]['descripcion']
                        html += '</td>'
                        html += '<td>'
                        html += '<a href="#" class="btn btn-xs btn-warning rounded-circle elevation-4" title="Editar Registro" '
                        html += 'onclick="fn_edicion('+response[i]['id']+')"><i class="fas fa-edit"></i></a>'
                        html += '</td>'
                        html += '</tr>'
                    }
                    $("#tblProcedimientos tbody tr").remove();
                    $('#tblProcedimientos tbody').append(html);
                },
                error: function(xhr) {
                    // Aquí manejas errores (validaciones de Laravel, etc.)
                    let errores = xhr.responseJSON.errors;
                    // console.error("Error al guardar:", errores);
                    // alert("Hubo un error al procesar la solicitud.");
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
                }
            });
        }

        function closeDiv(div){
            $('#'+div).hide();
            $('#'+div).attr('hidden');
        }

        function fn_calcula_bmi(){
            // 1. Obtener los elementos del DOM
            const inputPeso  = document.getElementById('peso');
            const inputTalla = document.getElementById('talla');
            const inputIMC   = document.getElementById('imc');

            // 2. Convertir valores a números flotantes
            const peso  = parseFloat(inputPeso.value);
            const talla = parseFloat(inputTalla.value);

            // 3. Validar que los valores sean números válidos y mayores a cero
            if (peso > 0 && talla > 0) {
                // Fórmula: peso / (talla * talla)
                const imc = peso / (talla * talla);

                // 4. Mostrar el resultado en el input IMC (con 2 decimales)
                inputIMC.value = imc.toFixed(2);
            } else {
                // Limpiar el campo si los datos no son válidos
                inputIMC.value = "";
            }

            graficoIMC(parseFloat(inputIMC.value));
        }

        // 5. Opcional: Escuchar cambios en tiempo real
        document.getElementById('peso').addEventListener('input', fn_calcula_bmi);
        document.getElementById('talla').addEventListener('input', fn_calcula_bmi);

        function graficoIMC(bmi){
            Highcharts.chart('container', {
                chart: {
                    type: 'gauge',
                    plotBackgroundColor: null,
                    plotBackgroundImage: null,
                    plotBorderWidth: 0,
                    plotShadow: false,
                    height: '45%',
                    marginTop: 0,      // Elimina el margen superior del área de trazado
                    spacingTop: 0,     // Elimina el espacio entre el borde del SVG y el contenido
                },

                title: {
                    text: 'Indice Masa Corporal'
                },

                pane: {
                    startAngle: -90,
                    endAngle: 89.9,
                    background: null,
                    center: ['50%', '75%'],
                    size: '110%'
                },

                // the value axis
                yAxis: {
                    min: 0,
                    max: 40,
                    tickPixelInterval: 30,
                    plotBands: [{
                        from: 0,
                        to: 18.5,
                        color: '#DF5353', // Rojo (Bajo peso)
                        thickness: 20
                    }, {
                        from: 18.5,
                        to: 25,
                        color: '#55BF3B', // Verde (Normal)
                        thickness: 20
                    }, {
                        from: 25,
                        to: 30,
                        color: '#DDDF0D', // Amarillo (Sobrepeso)
                        thickness: 20
                    }, {
                        from: 30,
                        to: 40,
                        color: '#F59827', // Rojo (Obesidad)
                        thickness: 20
                    }]
                },

                series: [{
                    name: 'IMC',
                    data: [bmi],
                    // tooltip: {
                    //     valueSuffix: ' IMC'
                    // },
                    dataLabels: {
                        format: '{y} IMC',
                        borderWidth: 0,
                        color: (
                            Highcharts.defaultOptions.title &&
                            Highcharts.defaultOptions.title.style &&
                            Highcharts.defaultOptions.title.style.color
                        ) || '#333333',
                        style: {
                            fontSize: '24px'
                        }
                    },
                    dial: {
                        radius: '75%',
                        backgroundColor: 'gray',
                        baseWidth: 12,
                        baseLength: '0%',
                        rearLength: '0%'
                    },
                    pivot: {
                        backgroundColor: 'gray',
                        radius: 6
                    }

                }]

            });
        }

        $('#formVitales').on('submit', function(e) {
            e.preventDefault();
            // 1. Obtener el valor del input externo (ejemplo: un ID de paciente o admisión)
            var valorExterno = $('#admision_id').val();
            
            // 2. Validar que el valor exista (opcional pero recomendado)
            if (!valorExterno) {
                console.warn("El valor externo está vacío");
            }

            // 3. Asignarlo al input oculto
            $('#vitales_admision_id').val(valorExterno);
            
            // El formulario se envía automáticamente después de esta función
            let formData = $(this).serialize();
            $.ajax({
                url: "{{ route('vitalesAdmision') }}", // La ruta de Laravel
                method: "POST",
                data: formData,
                success: function(response) {
                    actualizarTablaVitales(valorExterno);
                    $('#formVitales')[0].reset();
                    $("#modalVitales").modal('hide');

                    swal.fire({
                        title: 'Exito !!!',
                        text: 'Signos Vitales Actualizados con Exito !!!!',
                        type: 'success',
                    });
                },
                error: function(xhr) {
                    // Aquí manejas errores (validaciones de Laravel, etc.)
                    let errores = xhr.responseJSON.errors;
                    // console.error("Error al guardar:", errores);
                    // alert("Hubo un error al procesar la solicitud.");
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
                }
            });
        });

        $('#formConsultas').on('submit', function(e) {
            e.preventDefault();
            // 1. Obtener el valor del input externo (ejemplo: un ID de paciente o admisión)
            var valorExterno = $('#admision_id').val();

            // 2. Validar que el valor exista (opcional pero recomendado)
            if (!valorExterno) {
                console.warn("El valor externo está vacío");
            }

            // 3. Asignarlo al input oculto
            $('#consulta_admision_id').val(valorExterno);

            // El formulario se envía automáticamente después de esta función
            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route('actconsulta_ajax') }}", // La ruta de Laravel
                method: "POST",
                data: formData,
                success: function(response) {
                    actualizarTablaConsulta(valorExterno);
                    $('#formConsultas')[0].reset();
                    $("#modalConsultas").modal('hide');

                    Swal.fire({
                        title: response.type === 'success' ? "¡Trabajo Finalizado!" : "Aviso",
                        text: response.message,
                        icon: response.type, // 'success'
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#A5C890",
                        customClass: {
                            confirmButton: 'btn btn-success'
                        },
                        buttonsStyling: false
                    });
                },
                error: function(xhr) {
                    // Aquí manejas errores (validaciones de Laravel, etc.)
                    let errores = xhr.responseJSON.errors;
                    // console.error("Error al guardar:", errores);
                    // alert("Hubo un error al procesar la solicitud.");
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
                }
            });
        });

        $('#formHospitalizaciones').on('submit', function(e) {
            e.preventDefault();
            // 1. Obtener el valor del input externo (ejemplo: un ID de paciente o admisión)
            var valorExterno = $('#admision_id').val();
            
            // 2. Validar que el valor exista (opcional pero recomendado)
            if (!valorExterno) {
                console.warn("El valor externo está vacío");
            }

            // 3. Asignarlo al input oculto
            $('#hospitalizacion_admision_id').val(valorExterno);
            
            // El formulario se envía automáticamente después de esta función
            let formData = $(this).serialize();
            $.ajax({
                url: "{{ route('acthospitalizacion_ajax') }}", // La ruta de Laravel
                method: "POST",
                data: formData,
                success: function(response) {
                    actualizarTablaHospializacion(valorExterno);
                    $('#formHospitalizaciones')[0].reset();
                    $("#modalHospitalizacion").modal('hide');

                    swal.fire({
                        title: 'Exito !!!',
                        text: 'Registro Actualizado con Exito !!!',
                        type: 'success',
                    });
                },
                error: function(xhr) {
                    // Aquí manejas errores (validaciones de Laravel, etc.)
                    let errores = xhr.responseJSON.errors;
                    // console.error("Error al guardar:", errores);
                    // alert("Hubo un error al procesar la solicitud.");
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
                }
            });
        });

        $('#formProcedimientos').on('submit', function(e){
            e.preventDefault();

            // 1. Validaciones básicas antes de enviar
            if ($('#p_procedimiento_id').val() == "") {
                Swal.fire('Atención', 'Debe seleccionar un procedimiento', 'warning');
                return;
            }
            
            // 1. Usar FormData en lugar de serialize
            // Esto captura TODO: textos y archivos
            let formData = new FormData(this);

            // DEBUG: Veamos qué hay en archivosMaster antes de enviarlo
            console.log("Archivos a enviar:", archivosMaster);

            // 2. Limpiar las imágenes que el navegador cree que tiene y poner las de archivosMaster
            formData.delete('imagenes[]'); 

            if (typeof archivosMaster !== 'undefined' && archivosMaster.length > 0) {
                archivosMaster.forEach(function(archivo) {
                    formData.append('imagenes[]', archivo);
                });
            }
            console.log("Cantidad de archivos en el envío:", formData.getAll('imagenes[]').length);
            // archivosMaster.forEach(file => {
            //     formData.append('imagenes[]', file);
            // });

            // 2. Si necesitas agregar manualmente valores externos
            var valorExterno = $('#admision_id').val();
            // formData.append('procedimiento_admision_id', valorExterno);

            $.ajax({
                url: "{{ route('actprocedimiento_ajax') }}",
                method: "POST",
                data: formData,
                contentType: false, // OBLIGATORIO para enviar archivos
                processData: false, // OBLIGATORIO para enviar archivos
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    // Esta línea es VITAL para que Laravel no redireccione y devuelva JSON
                    'Accept': 'application/json' 
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Guardando...',
                        text: 'Procesando información e imágenes',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                },
                success: function(response) {
                    actualizarTablaProcedimiento(valorExterno);
                    $('#formProcedimientos')[0].reset();
                    $("#modalProcedimientos").modal('hide');
                    $('#preview-container').empty();
                    archivosMaster = [];
                    // ... tu código anterior (Swal, reset, etc)
                    $('#preview-container').empty(); // Limpia las miniaturas
                    $('.custom-file-label').html('Elegir archivos...'); // Resetea el texto del input

                    swal.fire({
                        title: 'Exito !!!',
                        text: 'Registro Actualizado con Exito !!!',
                        type: 'success',
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Aquí es donde Laravel nos dice qué validación falló
                        let errores = xhr.responseJSON.errors;
                        let mensajeError = "";
                        
                        // Recorremos los errores para mostrarlos
                        Object.keys(errores).forEach(key => {
                            mensajeError += errores[key][0] + "<br>";
                        });

                        Swal.fire({
                            icon: 'error',
                            title: 'Error de Validación',
                            html: mensajeError
                        });
                    } else {
                        Swal.fire('Error', 'Error crítico en el servidor', 'error');
                        console.error(xhr.responseText);
                    }
                }
            });
        });

        function openModal(modal){
            var admision_id  = document.getElementById("admision_id").value;
            // jQuery.noConflict();
            if (admision_id == undefined || admision_id == null) {
                Swal.fire({
                    title: "¡Error!",
                    text: "Debe Seleccionar una Admisión para continuar",
                    icon: "error", // Cambiado de 'type' a 'icon'
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });
            }else{
                if (modal === 'modalVitales') {
                    $('#formVitales')[0].reset();
                    document.getElementById('vitales_admision_id').value = admision_id;
                }
                if (modal === 'modalConsultas') {
                    $('#formConsultas')[0].reset();
                    document.getElementById('consulta_admision_id').value = admision_id;
                    $('.summernote').summernote('focus');
                    if ($.isFunction($.fn.summernote)) {
                        // $('#consulta_subjetivo').summernote('code', '');
                        $('#consulta_subjetivo').summernote('reset');
                        $('#consulta_objetivo').summernote('code', '');
                        $('#consulta_impresion_clinica').summernote('code', '');
                        $('#consulta_plan').summernote('code', '');
                        $('#consulta_tratamiento').summernote('code', '');
                    }
                    $('#formConsultas').find('input, textarea').prop('readonly', false);
                    $('#formConsultas select').prop('disabled', false);
                    $('#formConsultas button[type="submit"]').removeClass('deshabilitar_registro');
                    $('#formConsultas .summernote').summernote('enable');
                    
                }
                if (modal === 'modalProcedimientos') {
                    $('#formProcedimientos')[0].reset();
                    document.getElementById('procedimiento_admision_id').value = admision_id;
                    if ($.isFunction($.fn.summernote)) {
                        $('#pindicacion').summernote('code', '');
                        $('#phallazgos').summernote('code', '');
                        $('#pdiagnostico').summernote('code', '');
                        $('#precomendacion').summernote('code', '');
                    }
                    
                }
                if (modal === 'modalHospitalizacion') {
                    $('#formProcedimientos')[0].reset();
                    document.getElementById('hospitalizacion_admision_id').value = admision_id;
                }
                // ... resto de tus resets ...
                // $("#"+modal).modal('show');
                // $('#formVitales')[0].reset();
                // graficoIMC(parseFloat(0));
                // $('#formHospitalizaciones')[0].reset();
                $("#"+modal).modal('show');
            }
        }

        function agregarRegistroMedicamento(){
            var medicamento = $('#tratamiento_medicamento_id option:selected').text();
            var descripcion = $('#tratamiento_descripcion').val();

            // Validar que se haya seleccionado algo (opcional)
            if (medicamento === "" || descripcion === "") {
                alert("Por favor seleccione un medicamento y una dosis.");
                return;
            }

            // 2. Formatear la cadena
            var textoAInsertar = medicamento + " - " + descripcion;

            // 3. Insertar en Summernote en la posición del cursor
            // El método 'insertText' respeta la posición actual del foco
            $('#consulta_tratamiento').summernote('insertText', textoAInsertar + '\n');

            // OPCIONAL: Devolver el foco al editor para que el usuario siga escribiendo
            $('#consulta_tratamiento').summernote('focus');
            // let html = '';
            // html += '<tr>'
            // html += '<td>'
            // html += '<div class="input-group input-group-sm">'
            // html += '<select id="receta['+nLinea+'][tratamiento_medicamento_id]" name="receta['+nLinea+'][tratamiento_medicamento_id]" class="form-control" data-required="true" onchange="actualizarDosis('+nLinea+', this)">';
            // html += '<option value="">Seleccionar...</option>';
            // @foreach($pMedicamentos as $pM)
            //     html += '<option value="{{ $pM->id }}">{{ $pM->descripcion }}</option>'
            // @endforeach
            // html += '</select>';
            // html += '</div>'
            // html += '</td>'
            // html += '<td>'
            // html += '<div class="input-group input-group-sm">'
            // html += '<select id="receta['+nLinea+'][tratamiento_dosis_id]" name="receta['+nLinea+'][tratamiento_dosis_id]" class="form-control" onchange="copiarDosis('+nLinea+'); return false;" data-required="true">';
            // html += '<option value="">Seleccionar...</option>';
            // html += '</select>';
            // html += '</div>'
            // html += '</td>'
            // html += '<td>'
            // html += '<div class="input-group input-group-sm mb-1">'
            // html += '<input type="text" class="form-control" id="receta['+nLinea+'][tratamiento_descripcion]" name="receta['+nLinea+'][tratamiento_descripcion]" required>'
            // html += '</div>'
            // html += '</td>'
            // html += '<td><a href="#" class="btn btn-xs btn-outline-danger rounded-circle elevation-4 eliminar" id="btn_eliminar_registro"><i class="fas fa-trash-alt"></i></a></td>';
            // html += '</tr>'
            // nLinea++;

            // $('#tblMedicamentos tbody').append(html);
            // $('.eliminar').on('click',eliminar);
        }


        function eliminar(){
            var whichtr = $(this).closest("tr");
            whichtr.remove(); 
            return false;
        }

        function actualizarDosis(elemento){
            let idMedicamento = $(elemento).val();
            let idDestino = "tratamiento_dosis_id";
            let $selectDosis = $(document.getElementById(idDestino));
            
            if(idMedicamento) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: "{{ route('trae_dosis') }}",
                    data: {medicamento_id : idMedicamento },
                    success: function(response) {           
                        // 1. Limpiamos el select y dejamos la opción por defecto
                        $selectDosis.empty().append('<option value="">Seleccionar...</option>');

                        // 2. Construimos todas las opciones en una sola cadena (más eficiente)
                        let options = '';
                        for (var i = 0; i < response.length; i++) {
                            options += '<option value="' + response[i].unidad_medida_id + '">' + response[i].descripcion + '</option>';
                        }
                        
                        // 3. Insertamos las opciones
                        $selectDosis.append(options);

                    },
                    error: function(error){
                        console.log(error);
                    }
                }); 
            }
            
        }

        function copiarDosis(linea){
        //     // 1. Construimos los IDs exactos de los elementos
            let idMedicamento = "tratamiento_medicamento_id";
            let idDosis = "tratamiento_dosis_id";
            let idDescripcion = "tratamiento_descripcion";
            let campoTexto = document.getElementById(idDescripcion);

        //     // 2. Obtenemos los valores usando getElementById (para evitar problemas con los corchetes)
            let valorMedicamento = document.getElementById(idMedicamento).value;
            let valorDosis = document.getElementById(idDosis).value;

            $.ajax({
                url: "{{ route('receta_descripcion') }}",
                type: "POST",
                async: true,
                data: {"_token" : "{{ csrf_token() }}", 
                       medicamento_id : valorMedicamento,
                       dosis_id : valorDosis},
                success: function(response){
                    campoTexto.value = response.descripcion;
                    $(campoTexto).fadeOut(100).fadeIn(100);
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function fn_edicion(atencion_id){
            $.ajax({
                url: "{{ route('trae_registro') }}", // La ruta de Laravel
                method: "POST",
                data: {"_token": "{{ csrf_token() }}",
                       atencion_id: atencion_id},
                success: function(response) {
                    console.log(response);
                    switch (response.tipo_atencion_id) {
                        case 1:
                            openModal('modalConsultas');
                            
                            document.getElementById('consulta_admision_id').value = response.admision_id;
                            document.getElementById('consulta_atencion_id').value = response.id;
                            
                            setTimeout(function() {
                                if ($.isFunction($.fn.summernote)) {
                                    $('#consulta_tratamiento').summernote('code', response.ctratamiento || '');
                                } else {
                                    // Si por alguna razón AdminLTE no lo cargó, lo inicializamos nosotros
                                    $('#consulta_tratamiento').summernote({
                                        height: 300,
                                        lang: 'es-ES'
                                    }).summernote('code', response.ctratamiento || '');
                                }
                            }, 400); // Un pequeño retraso para esperar al modal
                            $('#consulta_subjetivo').summernote('code', response.csubjetivo);
                            $('#consulta_objetivo').summernote('code', response.cobjetivo);
                            $('#consulta_impresion_clinica').summernote('code', response.cimpresion_clinica);
                            $('#consulta_plan').summernote('code', response.cplan);

                            if (admision_atencion_medica != 1) {
                                $('#formConsultas').find('input, textarea').prop('readonly', true);
                                $('#formConsultas select').prop('disabled', true);
                                $('#formConsultas button[type="submit"]').addClass('deshabilitar_registro');
                                $('#formConsultas .summernote').each(function() {
                                    if ($(this).data('summernote')) { // Verifica si está inicializado
                                        $(this).summernote('disable');
                                    }
                                });
                            }else{
                                $('#formConsultas').find('input, textarea').prop('readonly', false);
                                $('#formConsultas select').prop('disabled', false);
                                $('#formConsultas button[type="submit"]').removeClass('deshabilitar_registro');
                                $('#formConsultas .summernote').summernote('enable');
                            }
                            break;
                        case 2:
                            document.getElementById('hospitalizacion_admision_id').value = response.admision_id;
                            document.getElementById('hospitalizacion_atencion_id').value = response.id;
                            document.getElementById('fecha_inicio').value = response.hfecha_inicio;
                            document.getElementById('fecha_inicio').value = response.hfecha_inicio;
                            document.getElementById('fecha_fin').value = response.hfecha_fin;
                            $('#resumen_egreso').summernote('code', response.hresumen);
                            if (admision_atencion_medica != 1) {
                                $('#formHospitalizaciones').find('input, textarea').prop('disabled', true);
                                $('#formHospitalizaciones select').prop('disabled', true);
                                $('#formHospitalizaciones button[type="submit"]').addClass('deshabilitar_registro');
                                $('#formHospitalizaciones .summernote').each(function() {
                                    if ($(this).data('summernote')) { // Verifica si está inicializado
                                        $(this).summernote('disable');
                                    }
                                });
                            }else{
                                $('#formHospitalizaciones').find('input, textarea').prop('readonly', false);
                                $('#formHospitalizaciones select').prop('disabled', false);
                                $('#formHospitalizaciones button[type="submit"]').removeClass('deshabilitar_registro');
                                $('#formHospitalizaciones .summernote').summernote('enable');
                            }
                            openModal('modalHospitalizacion');
                            
                            break;
                        case 3:
                            openModal('modalProcedimientos');
                            // console.log('entre a procedimiento y el response devuelve '+response)
                            document.getElementById('procedimiento_admision_id').value = response.admision_id;
                            document.getElementById('procedimiento_atencion_id').value = response.id;
                            console.log(response.pprocedimiento_id);
                            $('#p_procedimiento_id').val(response.pprocedimiento_id).trigger('change');
                            document.getElementById('p_premedicacion_id').value = response.ppremedicacion;
                            document.getElementById('panestesiologo').value = response.panestesiologo;
                            document.getElementById('ppatologo').value = response.ppatologo;
                            // 2. Desmarcar todos primero (opcional pero recomendado)
                            document.querySelectorAll('input[name="tolerncia"]').forEach(el => el.checked = false);
                            // 3. Marcar el que viene del servidor
                            const target = document.querySelector(`input[name="tolerncia"][value="${response.ptolerancia}"]`);
                            if(target) target.checked = true;

                            if (response.indicacion) {
                                $('#pindicacion').summernote('code', response.indicacion);
                            } else {
                                $('#pindicacion').summernote('code', 'nulo'); // Limpia si viene vacío
                            }
                            $('#consulta_subjetivo').summernote('code', response.csubjetivo);
                            $('#phallazgos').summernote('code', response.hallazgos);
                            $('#pdiagnostico').summernote('code', response.diagnostico);
                            $('#precomendacion').summernote('code', response.recomendaciones);
                            cargarImagenesGuardadas(response.id);

                            if (admision_atencion_medica != 1) {
                                $('input[name="imagenes_viejas_visibles[]"]').prop('disabled', true);
                                $('label.custom-control-label').css('cursor', 'not-allowed');

                                $('#formProcedimientos').find('input[type="checkbox"]').prop('disabled', true);
                                $('#formProcedimientos').find('input, textarea').prop('disabled', true);
                                $('#formProcedimientos select').prop('disabled', true);
                                $('#formProcedimientos button[type="submit"]').addClass('deshabilitar_registro');
                                $('#formProcedimientos .summernote').each(function() {
                                    if ($(this).data('summernote')) { // Verifica si está inicializado
                                        $(this).summernote('disable');
                                    }
                                });
                            }else{
                                $('input[name="imagenes_viejas_visibles[]"]').prop('disabled', false);
                                $('label.custom-control-label').css('cursor', 'pointer');
                                $('#formProcedimientos').find('input[type="checkbox"]').prop('disabled', false);
                                $('#formProcedimientos').find('input, textarea').prop('disabled', false);
                                $('#formProcedimientos select').prop('disabled', false);
                                $('#formProcedimientos button[type="submit"]').removeClass('deshabilitar_registro');
                                $('#formProcedimientos .summernote').summernote('enable');
                            }
                            break;
                    }
                },
                error: function(xhr) {
                    // Aquí manejas errores (validaciones de Laravel, etc.)
                    let errores = xhr.responseJSON.errors;
                    // console.error("Error al guardar:", errores);
                    // alert("Hubo un error al procesar la solicitud.");
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
                }
            });
        }

        function cargarImagenesGuardadas(atencionId) {
            
            const previewContainer = $('#preview-container'); // Usamos jQuery por consistencia
            
            // 1. Limpiar y mostrar spinner
            previewContainer.html(`
                <div class="col-12 text-center p-3">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p>Cargando imágenes guardadas...</p>
                </div>
            `);

            // 2. Llamada AJAX
            // 1. Generamos la URL usando Blade con un texto comodín (ID_TEMPORAL)
            let urlConComodin = "{{ route('trae_proc_imagenes', ['atencion_id' => 'ID_TEMPORAL']) }}";
            
            // 2. Reemplazamos con JavaScript el comodín por el ID real
            let urlFinal = urlConComodin.replace('ID_TEMPORAL', atencionId);
            
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "GET",
                url: urlFinal,
                dataType: 'json',
                success: function(data) {
                    previewContainer.empty(); // Limpiar el spinner

                    if (data.length === 0) {
                        // Opcional: No poner nada o un mensaje suave
                        return;
                    }

                    // 3. Iterar los resultados
                    data.forEach(function(img) {
                        // El checkbox para imágenes que YA ESTÁN en la base de datos
                        // Usamos un nombre diferente para procesarlos distinto en el backend
                        const checked = img.visible == 1 ? 'checked' : '';
                        const uniqueId = `old_img_${img.id}`;
                        const urlCompleta = `/storage/procedimientos/${img.ruta}`;
                        const urlImagen = img.url.replace(/\\\//g, "/");

                        const html = `
                            <div class="col-md-3 mb-3">
                                <div class="card shadow-sm h-100 border border-info">
                                    <div style="cursor: zoom-in; overflow: hidden;">
                                        <img src="${urlImagen}" 
                                             class="card-img-top img-thumbnail" 
                                             style="height: 120px; object-fit: cover;" 
                                             onclick="verImagenEnModal('${urlImagen}', '${img.nombre}')"
                                             onerror="this.src='https://placehold.co/300x200?text=Error+Imagen'"
                                             title="Clic para ampliar">
                                    </div>
                                    <div class="card-body p-2 text-center bg-light">
                                        <div class="custom-control custom-checkbox text-left d-inline-block">
                                            <input type="checkbox" 
                                                   class="custom-control-input" 
                                                   id="${uniqueId}" 
                                                   name="imagenes_viejas_visibles[]" 
                                                   value="${img.id}" 
                                                   ${checked}>
                                            <label class="custom-control-label" for="${uniqueId}" style="font-size: 0.8rem; cursor:pointer;">
                                                Mostrar en Informe
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        previewContainer.append(html);
                    });
                },
                error: function(xhr) {
                    console.error("Error al cargar imágenes:", xhr.statusText);
                    previewContainer.html('<div class="col-12 text-danger text-center">No se pudieron cargar las imágenes previas.</div>');
                }
            });
        }

        function fn_edicion_vitales(atencion_id){
            openModal('modalVitales');
            $.ajax({
                url: "{{ route('trae_registro_vitales') }}", // La ruta de Laravel
                method: "POST",
                data: {"_token": "{{ csrf_token() }}",
                       atencion_id: atencion_id},
                success: function(response) {
                    $('#formVitales')[0].reset();
                    graficoIMC(parseFloat(0));
                    // $("#modalHospitalizacion").modal('hide');
                    document.getElementById('vitales_admision_id').value = response.admision_id;
                    document.getElementById('vitales_atencion_id').value = response.id;
                    document.getElementById('peso').value = response.peso;
                    document.getElementById('talla').value = response.talla;
                    document.getElementById('pulso').value = response.pulso;
                    document.getElementById('temperatura').value = response.temperatura;
                    document.getElementById('respiraciones').value = response.respiracion;
                    document.getElementById('presion_sistolica').value = response.presion_sistolica;
                    document.getElementById('presion_diastolica').value = response.presion_diastolica;
                    document.getElementById('imc').value = response.bmi;
                    // $('#fecha_inicio').val = response.hfecha_inicio;
                    graficoIMC(parseFloat(response.bmi));
                    if (admision_atencion_medica == 1) {
                        $('#formVitales').find('input, textarea').prop('readonly', false);
                        $('#formVitales button[type="submit"]').removeClass('deshabilitar_registro');
                    }else{
                        $('#formVitales').find('input, textarea').prop('readonly', true);
                        $('#formVitales button[type="submit"]').addClass('deshabilitar_registro');
                    }
                },
                error: function(xhr) {
                    // Aquí manejas errores (validaciones de Laravel, etc.)
                    let errores = xhr.responseJSON.errors;
                    // console.error("Error al guardar:", errores);
                    // alert("Hubo un error al procesar la solicitud.");
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
                }
            });
        }

        function generar_receta(){
            var atencion_id = document.getElementById('consulta_atencion_id').value;

            var urlBase = "{{ route('generar_receta', [':id']) }}";
            var urlFinal = urlBase.replace(':id', atencion_id);

            var win = window.open(urlFinal, '_blank');

            // Si 'win' es null, el navegador bloqueó la ventana
            if (win) {
                win.focus();
            } else {
                Swal.fire({
                    title: "¡Trabajo Finalizado!",
                    text: "{!! Session::get('message') !!}",
                    icon: "warning", // Cambiado de 'type' a 'icon'
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                    customClass: {
                        confirmButton: 'btn btn-warning'
                    },
                    buttonsStyling: false
                });
                Swal.fire({
                    title: 'Ventana bloqueada',
                    text: 'Por favor, permite las ventanas emergentes para este sitio',
                    icon: 'warning'
                });
            }
        }

        function generar_informe(){
            var atencion_id = document.getElementById('procedimiento_atencion_id').value;

            // Generamos la URL base con Blade y reemplazamos un "comodín" por el ID de JS
            var urlBase = "{{ route('generar_informe', [':id']) }}";
            var urlFinal = urlBase.replace(':id', atencion_id);

            var win = window.open(urlFinal, '_blank');

            // Si 'win' es null, el navegador bloqueó la ventana
            if (win) {
                win.focus();
            } else {
                Swal.fire({
                    title: "¡Trabajo Finalizado!",
                    text: "{!! Session::get('message') !!}",
                    icon: "warning", // Cambiado de 'type' a 'icon'
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                    customClass: {
                        confirmButton: 'btn btn-warning'
                    },
                    buttonsStyling: false
                });
                Swal.fire({
                    title: 'Ventana bloqueada',
                    text: 'Por favor, permite las ventanas emergentes para este sitio',
                    icon: 'warning'
                });
            }
        }

        //=======================================================================
        // Preview de imagenes
        //=======================================================================
        document.addEventListener('DOMContentLoaded', function() {
            const inputImagenes = document.getElementById('imagenes');
            const previewContainer = document.getElementById('preview-container');
            // let archivosMaster = [];

            if (inputImagenes) {
                inputImagenes.addEventListener('change', function(e) {
                    const nuevosArchivos = Array.from(e.target.files);
                    
                    nuevosArchivos.forEach(file => {
                        if (!archivosMaster.some(a => a.name === file.name && a.size === file.size)) {
                            archivosMaster.push(file);
                        }
                    });

                    renderizarTodo();
                    actualizarInputReal();
                });
            }

            function renderizarTodo() {
                previewContainer.innerHTML = ''; // Limpiamos todo el contenedor

                archivosMaster.forEach((file, index) => {
                    const divCol = document.createElement('div');
                    divCol.className = 'col-md-3 mb-3 position-relative';
                    
                    // Generamos un ID único garantizado usando el timestamp y el índice
                    const uniqueId = `chk_${Date.now()}_${index}`;
                    const urlImagen = URL.createObjectURL(file); // Más rápido que FileReader

                    divCol.innerHTML = `
                        <div class="card shadow-sm h-100 border">
                            <button type="button" class="btn btn-danger btn-sm position-absolute" 
                                    style="top: -8px; right: -8px; border-radius: 50%; z-index: 100; width: 24px; height: 24px; padding: 0;"
                                    onclick="eliminarFotoDirecto(${index})" title="Eliminar">
                                <i class="fas fa-times" style="font-size: 10px;"></i>
                            </button>

                            <a href="${urlImagen}" target="_blank">
                                <img src="${urlImagen}" class="card-img-top img-thumbnail" style="height: 120px; object-fit: cover;">
                            </a>

                            <div class="card-body p-2 text-center bg-light">
                                <div class="custom-control custom-checkbox text-left d-inline-block">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="${uniqueId}" 
                                           name="procesar_imagen[]" 
                                           value="${file.name}" 
                                           checked>
                                    <label class="custom-control-label" for="${uniqueId}" style="font-size: 0.8rem; cursor:pointer;">
                                        Seleccionar
                                    </label>
                                </div>
                            </div>
                        </div>
                    `;
                    previewContainer.appendChild(divCol);
                });
            }

            window.eliminarFotoDirecto = function(index) {
                archivosMaster.splice(index, 1);
                renderizarTodo();
                actualizarInputReal();
            };

            function actualizarInputReal() {
                const dt = new DataTransfer();
                archivosMaster.forEach(file => dt.items.add(file));
                inputImagenes.files = dt.files;

                const label = inputImagenes.nextElementSibling;
                if (label && label.classList.contains('custom-file-label')) {
                    label.innerText = archivosMaster.length > 0 
                        ? `${archivosMaster.length} archivo(s) seleccionado(s)` 
                        : 'Elegir imágenes...';
                }
            }
        });

        window.verImagenEnModal = function(url, nombre) {
            // Evita que el evento se propague si hubiera otros elementos
            if (event) event.preventDefault();

            // 1. Limpiar la URL por si trae caracteres de escape
            const urlLimpia = url.replace(/\\\//g, "/");
            
            // 2. Asignar los datos al modal
            document.getElementById('imgVisorFull').src = urlLimpia;
            document.getElementById('nombreImagenModal').innerText = nombre;
            
            // 3. Mostrar el modal usando jQuery (estándar en AdminLTE)
            $('#modalVisorImagen').modal('show');
        }

        function fnInicioAtencion(){
           let admision_id = $('#admision_id').val();
           $.ajax({
                url: "{{ route('inicioAtencion') }}", // La ruta de Laravel
                method: "POST",
                data: {"_token": "{{ csrf_token() }}",
                       admision_id: admision_id},
                success: function(response) {
                    if (response.type == 'success') {
                        Swal.fire({
                            title: '¡ Trabajo Finalizado !',
                            text: response.message,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745', // Color success de AdminLTE
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) { 
                                generalesAdmision();
                                // Hacer visible el cronómetro a la izquierda
                                document.getElementById('cronometro-container').style.visibility = 'visible';
                                
                                clearInterval(timerInterval);
                                startTime = Date.now();
                                timerInterval = setInterval(actualizarTimer, 1000);
                            } 
                        });
                    }else{
                        Swal.fire({
                            title: "¡ Ooops !",
                            text: response.message,
                            icon: "error", // Cambiado de 'type' a 'icon'
                            confirmButtonText: "Aceptar",
                            confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        });
                    }
                }, 
                error: function(xhr) {
                    // Aquí manejas errores (validaciones de Laravel, etc.)
                    let errores = xhr.responseJSON.errors;
                    // console.error("Error al guardar:", errores);
                    // alert("Hubo un error al procesar la solicitud.");
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
                }
            });
        }

        function fnFinalAtencion(){
           let admision_id = $('#admision_id').val();
           $.ajax({
                url: "{{ route('finalAtencion') }}", // La ruta de Laravel
                method: "POST",
                data: {"_token": "{{ csrf_token() }}",
                       admision_id: admision_id},
                success: function(response) {
                    if (response.type == 'success') {
                        Swal.fire({
                            title: '¡ Trabajo Finalizado !',
                            text: response.message,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745', // Color success de AdminLTE
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) { 
                                // const btn = document.getElementById("btnFinalAtencion");
                                // btn.style.display = "none";
                                generalesAdmision();
                                // Detener el tiempo
                                clearInterval(timerInterval);
                                
                                // Opcional: poner el texto en rojo para indicar que se detuvo
                                document.getElementById('timer').style.color = '#dc3545';
                            } 
                        });
                    }else{
                        Swal.fire({
                            title: "¡ Ooops !",
                            text: response.message,
                            icon: "error", // Cambiado de 'type' a 'icon'
                            confirmButtonText: "Aceptar",
                            confirmButtonColor: "#A5C890", // Combinando con tu estilo de botones
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        });
                    }
                }, 
                error: function(xhr) {
                    // Aquí manejas errores (validaciones de Laravel, etc.)
                    let errores = xhr.responseJSON.errors;
                    // console.error("Error al guardar:", errores);
                    // alert("Hubo un error al procesar la solicitud.");
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
                }
            });
        }

        function actualizarTimer() {
            let now = Date.now();
            let diff = now - startTime;

            let horas = Math.floor(diff / 3600000);
            let minutos = Math.floor((diff % 3600000) / 60000);
            let segundos = Math.floor((diff % 60000) / 1000);

            // Formatear con ceros a la izquierda (00:00:00)
            let format = 
                (horas < 10 ? "0" + horas : horas) + ":" + 
                (minutos < 10 ? "0" + minutos : minutos) + ":" + 
                (segundos < 10 ? "0" + segundos : segundos);

            // document.getElementById('timer').innerText = format;
            let el = document.getElementById('timer-' + admision_id);
            if(el) el.innerText = format;
        }

        function actualizarTimerManual(totalSegundos) {
            let total = parseInt(totalSegundos) || 0;
            let horas = Math.floor(total / 3600);
            let minutos = Math.floor((total % 3600) / 60);
            let segundos = total % 60;

            let format = 
                (horas < 10 ? "0" + horas : horas) + ":" + 
                (minutos < 10 ? "0" + minutos : minutos) + ":" + 
                (segundos < 10 ? "0" + segundos : segundos);

            $('#timer-' + admision_id).text(format);
            
            $('.cronometro-instancia').hide(); // Ocultamos todos los demás primero
            $('#cronometro-container-' + admision_id).attr('style', 'display: inline-block !important; visibility: visible !important;');
        }
    </script>
@endsection