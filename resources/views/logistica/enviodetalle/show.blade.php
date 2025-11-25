@extends('layouts.app')

@section('template_title')
    {{ $enviodetalle->name ?? __('Show') . " " . __('Logistica.enviodetalle') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Logistica.enviodetalle</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('logistica.enviodetalles.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Envio Detalle Id:</strong>
                                    {{ $enviodetalle->envio_detalle_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Envio Id:</strong>
                                    {{ $enviodetalle->envio_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lote Salida Id:</strong>
                                    {{ $enviodetalle->lote_salida_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cliente Id:</strong>
                                    {{ $enviodetalle->cliente_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cantidad T:</strong>
                                    {{ $enviodetalle->cantidad_t }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
