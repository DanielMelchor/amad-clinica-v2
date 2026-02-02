@extends('adminlte::page')

@section('title', 'Consulta Médica')

@section('content')
    <div class="card">
        <div class="card-body">
            <label>Tratamiento:</label>
            <textarea id="tratamiento" class="summernote"></textarea>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // Ya no necesitas cargar scripts manualmente, 
            // AdminLTE lo hace por ti al detectar la clase .summernote
            $('.summernote').summernote({
                height: 200,
                lang: 'es-ES'
            });
        });
    </script>
@endsection