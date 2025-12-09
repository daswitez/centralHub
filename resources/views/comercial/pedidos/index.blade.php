@extends('layouts.app')

@section('page_title', 'Pedidos')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Pedidos</h3>
                <div class="card-tools">
                    <a href="{{ route('comercial.pedidos.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Pedido
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Items</th>
                                <th>Monto Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pedidos as $pedido)
                                <tr>
                                    <td>
                                        <a href="{{ route('comercial.pedidos.show', $pedido->pedido_id) }}" class="text-primary font-weight-bold">
                                            {{ $pedido->codigo_pedido }}
                                        </a>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $pedido->codigo_cliente }}</small><br>
                                        {{ $pedido->cliente_nombre }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') }}</td>
                                    <td>{{ $pedido->total_items }}</td>
                                    <td>${{ number_format($pedido->monto_total ?? 0, 2) }}</td>
                                    <td>
                                        @php
                                            $estadoBadge = match($pedido->estado) {
                                                'PENDIENTE', 'ABIERTO' => 'warning',
                                                'PREPARANDO' => 'info',
                                                'ENVIADO' => 'primary',
                                                'ENTREGADO' => 'success',
                                                'CANCELADO' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $estadoBadge }}">{{ $pedido->estado }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('comercial.pedidos.show', $pedido->pedido_id) }}" class="btn btn-sm btn-info" title="Ver Detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        No hay pedidos registrados
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
