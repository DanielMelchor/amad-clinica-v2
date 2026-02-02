@section('css')
	
@endsection
<div class="row">
	<div class="col-md-10 offset-md-1">
		<div class="row">
            <div class="input-group input-group-sm col-md-12 mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text" for="empresa_id">Empresa</span>
                </div>
                <select class="custom-select select2" id="empresa_id" name="empresa_id" required>
                    <option value="" selected>Seleccionar...</option>
                    @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id }}">{{ $empresa->nombre_comercial }}</option>
                    @endforeach
                </select>
            </div>
        </div>
	</div>
</div>