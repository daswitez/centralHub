@extends('layouts.app')

@section('template_title')
    {{ $pedido->name ?? __('Show') . " " . __('Comercial.pedido') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Comercial.pedido</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('comercial.pedidos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Pedido Id:</strong>
                                    {{ $pedido->pedido_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Codigo Pedido:</strong>
                                    {{ $pedido->codigo_pedido }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cliente Id:</strong>
                                    {{ $pedido->cliente_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Pedido:</strong>
                                    {{ $pedido->fecha_pedido }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado:</strong>
                                    {{ $pedido->estado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Almacen Id:</strong>
                                    {{ $pedido->almacen_id }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
