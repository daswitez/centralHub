@extends('layouts.app')

@section('template_title')
    {{ $pedidodetalle->name ?? __('Show') . " " . __('Almacen.pedidodetalle') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Almacen.pedidodetalle</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('almacen.pedidodetalles.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Pedido Detalle Id:</strong>
                                    {{ $pedidodetalle->pedido_detalle_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Pedido Almacen Id:</strong>
                                    {{ $pedidodetalle->pedido_almacen_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Sku:</strong>
                                    {{ $pedidodetalle->sku }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cantidad T:</strong>
                                    {{ $pedidodetalle->cantidad_t }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lote Salida Id:</strong>
                                    {{ $pedidodetalle->lote_salida_id }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
