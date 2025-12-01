@extends('layouts.app')

@section('page_title', 'Registrar Pedido')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Nuevo Pedido</h3>
            </div>
            <form action="{{ route('comercial.pedidos.store') }}" method="POST" id="formPedido">
                @csrf
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cliente_id">Cliente *</label>
                                <select name="cliente_id" id="cliente_id" class="form-control" required>
                                    <option value="">Seleccione cliente...</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->cliente_id }}">
                                            {{ $cliente->codigo_cliente }} - {{ $cliente->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_pedido">Fecha Pedido *</label>
                                <input type="date" name="fecha_pedido" id="fecha_pedido" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5>Productos</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tablaDetalles">
                            <thead class="bg-light">
                                <tr>
                                    <th width="35%">SKU / Producto</th>
                                    <th width="20%">Cantidad (t)</th>
                                    <th width="20%">Precio Unit. (USD)</th>
                                    <th width="20%">Subtotal (USD)</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="detalle-row">
                                    <td>
                                        <input type="text" name="detalles[0][sku]" class="form-control" placeholder="ej: PAPA-CORTE-5KG" required>
                                    </td>
                                    <td>
                                        <input type="number" name="detalles[0][cantidad_t]" class="form-control cantidad" step="0.01" min="0.01" required>
                                    </td>
                                    <td>
                                        <input type="number" name="detalles[0][precio_unit_usd]" class="form-control precio" step="0.01" min="0.01" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control subtotal" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger btn-remove" disabled>
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                                    <td><input type="text" id="totalGeneral" class="form-control font-weight-bold" readonly></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <button type="button" class="btn btn-secondary" id="btnAgregarDetalle">
                        <i class="fas fa-plus"></i> Agregar Producto
                    </button>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Registrar Pedido
                    </button>
                    <a href="{{ route('comercial.pedidos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let detalleIndex = 1;

// Agregar nueva fila
document.getElementById('btnAgregarDetalle').addEventListener('click', function() {
    const tbody = document.querySelector('#tablaDetalles tbody');
    const newRow = document.querySelector('.detalle-row').cloneNode(true);
    
    // Actualizar índices
    newRow.querySelectorAll('input, select').forEach(input => {
        if (input.name) {
            input.name = input.name.replace(/\[\d+\]/, `[${detalleIndex}]`);
            if (!input.classList.contains('subtotal')) {
                input.value = '';
            }
        }
    });
    
    // Habilitar botón de eliminar
    newRow.querySelector('.btn-remove').disabled = false;
    
    tbody.appendChild(newRow);
    detalleIndex++;
    
    actualizarEventos();
});

// Eliminar fila
function actualizarEventos() {
    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.onclick = function() {
            if (document.querySelectorAll('.detalle-row').length > 1) {
                this.closest('.detalle-row').remove();
                calcularTotales();
            }
        };
    });
    
    document.querySelectorAll('.cantidad, .precio').forEach(input => {
        input.oninput = calcularTotales;
    });
}

// Calcular totales
function calcularTotales() {
    let total = 0;
    
    document.querySelectorAll('.detalle-row').forEach(row => {
        const cantidad = parseFloat(row.querySelector('.cantidad').value) || 0;
        const precio = parseFloat(row.querySelector('.precio').value) || 0;
        const subtotal = cantidad * precio;
        
        row.querySelector('.subtotal').value = subtotal.toFixed(2);
        total += subtotal;
    });
    
    document.getElementById('totalGeneral').value = total.toFixed(2);
}

// Inicializar
actualizarEventos();
</script>
@endsection
