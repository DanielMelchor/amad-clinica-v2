<!-- Busqueda -->
    <div class="modal fade" id="busquedaModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="busquedaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form action="{{ $routeAction ?? '#' }}" method="get">
                    <div class="card bg-light">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-10">
                                    <h6>Críterios de Busqueda</h6>
                                </div>
                                <div class="col-md-2" style="text-align: right;">
                                    <button type="submit" class="btn btn-xs btn-outline-success rounded-circle elevation-1" title="Mostrar coincidencias"><i class="fas fa-bars"></i></button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-circle elevation-1" title="Cerrar" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"><i class="fas fa-sign-out-alt"></i></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12 mb-3 form-group">
                                    <label>Bodega</label>
                                    <select id="bodega_id" name="bodega_id" class="form-control">
                                        <option></option> <option value="0">Todas las bodegas</option>
                                        @foreach($bodegas as $b)
                                            <option value="{{ $b->id_encriptado }}" {{ $bodegaId == $b->id_encriptado ? 'selected' : '' }}>
                                                {{ $b->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12 mb-3 form-group">
                                    <label>Familia</label>
                                    <select id="familia_id" name="familia_id" class="form-control">
                                        <option></option> <option value="0">Todas las familias</option>
                                        @foreach($familias as $f)
                                            <option value="{{ $f->id_encriptado }}" {{ $familiaId == $f->id_encriptado ? 'selected' : '' }}>
                                                {{ $f->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12 mb-3 form-group">
                                    <label>Insumo</label>
                                    <select id="producto_id" name="producto_id" class="form-control">
                                        <option value="">Seleccionar...</option>
                                        <option value="0">Todos los Insumos</option>
                                        @foreach($productos as $p)
                                            <option value="{{ $p->id_encriptado }}" {{ $productoId == $p->id_encriptado ? 'selected' : '' }}>
                                                {{ $p->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if(isset($mostrarFechas) && $mostrarFechas)
                                <div class="row mb-3">
                                    <div class="col-12 form-group">
                                        <label>Fecha Inicial</label>
                                        <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio', date('Y-m-01')) }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 form-group">
                                        <label>Fecha Final</label>
                                        <input type="date" name="fecha_final" class="form-control" value="{{ request('fecha_final', date('Y-m-01')) }}">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Busqueda -->