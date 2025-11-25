@extends('layouts.app')

@section('template_title')
    {{ $enviodetallealmacen->name ?? __('Show') . " " . __('Logistica.enviodetallealmacen') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Logistica.enviodetallealmacen</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('logistica.enviodetallealmacens.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Envio Detalle Alm Id:</strong>
                                    {{ $enviodetallealmacen->envio_detalle_alm_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Envio Id:</strong>
                                    {{ $enviodetallealmacen->envio_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lote Salida Id:</strong>
                                    {{ $enviodetallealmacen->lote_salida_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Almacen Id:</strong>
                                    {{ $enviodetallealmacen->almacen_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cantidad T:</strong>
                                    {{ $enviodetallealmacen->cantidad_t }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
