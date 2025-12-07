@extends('layouts.app')

@section('page_title', 'Nueva Orden de Envío')

@section('page_header')
    <div>
        <h1 class="m-0">Nueva Orden de Envío</h1>
        <p class="text-muted mb-0">Crear orden de envío desde Planta hacia Almacén</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <form action="{{ route('ordenes-envio.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    {{-- Origen --}}
                    <div class="col-md-6">
                        <h5 class="mb-3"><i class="fas fa-industry text-primary"></i> Origen</h5>
                        
                        <div class="form-group">
                            <label for="planta_origen_id">Planta de Origen *</label>
                            <select name="planta_origen_id" id="planta_origen_id" class="form-control @error('planta_origen_id') is-invalid @enderror" required>
                                <option value="">Seleccione planta...</option>
                                @foreach($plantas as $p)
                                    <option value="{{ $p->planta_id }}">{{ $p->codigo_planta }} - {{ $p->nombre }}</option>
                                @endforeach
                            </select>
                            @error('planta_origen_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="lote_salida_id">Lote de Salida *</label>
                            <select name="lote_salida_id" id="lote_salida_id" class="form-control @error('lote_salida_id') is-invalid @enderror" required>
                                <option value="">Seleccione lote...</option>
                                @foreach($lotesSalida as $ls)
                                    <option value="{{ $ls->lote_salida_id }}" data-peso="{{ $ls->peso_t }}">
                                        {{ $ls->codigo_lote_salida }} - {{ $ls->sku }} ({{ number_format($ls->peso_t, 2) }}t)
                                    </option>
                                @endforeach
                            </select>
                            @error('lote_salida_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="cantidad_t">Cantidad a Enviar (t) *</label>
                            <input type="number" step="0.01" name="cantidad_t" id="cantidad_t" 
                                   class="form-control @error('cantidad_t') is-invalid @enderror" 
                                   required min="0.1">
                            @error('cantidad_t')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Destino --}}
                    <div class="col-md-6">
                        <h5 class="mb-3"><i class="fas fa-warehouse text-success"></i> Destino</h5>
                        
                        <div class="form-group">
                            <label for="almacen_destino_id">Almacén de Destino *</label>
                            <select name="almacen_destino_id" id="almacen_destino_id" class="form-control @error('almacen_destino_id') is-invalid @enderror" required>
                                <option value="">Seleccione almacén...</option>
                                @foreach($almacenes as $a)
                                    <option value="{{ $a->almacen_id }}">{{ $a->codigo_almacen }} - {{ $a->nombre }}</option>
                                @endforeach
                            </select>
                            @error('almacen_destino_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="fecha_programada">Fecha Programada *</label>
                            <input type="date" name="fecha_programada" id="fecha_programada" 
                                   class="form-control @error('fecha_programada') is-invalid @enderror" 
                                   required min="{{ date('Y-m-d') }}">
                            @error('fecha_programada')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="prioridad">Prioridad *</label>
                            <select name="prioridad" id="prioridad" class="form-control" required>
                                <option value="NORMAL" selected>Normal</option>
                                <option value="URGENTE">Urgente</option>
                                <option value="BAJA">Baja</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" rows="2" class="form-control" 
                              maxlength="500" placeholder="Notas adicionales para el envío..."></textarea>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Asignación Automática:</strong> Al crear la orden, el sistema buscará automáticamente un conductor y vehículo disponibles.
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Crear Orden de Envío
                </button>
                <a href="{{ route('ordenes-envio.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('lote_salida_id').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    const peso = option.dataset.peso;
    if (peso) {
        document.getElementById('cantidad_t').value = peso;
    }
});
</script>
@endpush
