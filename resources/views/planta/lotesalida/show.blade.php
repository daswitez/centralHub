@extends('layouts.app')

@section('template_title')
    {{ $lotesalida->name ?? __('Show') . " " . __('Planta.lotesalida') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Planta.lotesalida</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('planta.lotesalidas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Lote Salida Id:</strong>
                                    {{ $lotesalida->lote_salida_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Codigo Lote Salida:</strong>
                                    {{ $lotesalida->codigo_lote_salida }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lote Planta Id:</strong>
                                    {{ $lotesalida->lote_planta_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Sku:</strong>
                                    {{ $lotesalida->sku }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Peso T:</strong>
                                    {{ $lotesalida->peso_t }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Empaque:</strong>
                                    {{ $lotesalida->fecha_empaque }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
