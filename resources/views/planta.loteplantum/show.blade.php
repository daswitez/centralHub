@extends('layouts.app')

@section('template_title')
    {{ $planta.loteplantum->name ?? __('Show') . " " . __('Planta.loteplantum') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Planta.loteplantum</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('planta.loteplanta.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Lote Planta Id:</strong>
                                    {{ $planta.loteplantum->lote_planta_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Codigo Lote Planta:</strong>
                                    {{ $planta.loteplantum->codigo_lote_planta }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Planta Id:</strong>
                                    {{ $planta.loteplantum->planta_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Inicio:</strong>
                                    {{ $planta.loteplantum->fecha_inicio }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Fin:</strong>
                                    {{ $planta.loteplantum->fecha_fin }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Rendimiento Pct:</strong>
                                    {{ $planta.loteplantum->rendimiento_pct }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
