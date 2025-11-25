@extends('layouts.app')

@section('template_title')
    {{ $lotecampo->name ?? __('Show') . " " . __('Campo.lotecampo') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Campo.lotecampo</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('campo.lotecampos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Lote Campo Id:</strong>
                                    {{ $lotecampo->lote_campo_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Codigo Lote Campo:</strong>
                                    {{ $lotecampo->codigo_lote_campo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Productor Id:</strong>
                                    {{ $lotecampo->productor_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Variedad Id:</strong>
                                    {{ $lotecampo->variedad_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Superficie Ha:</strong>
                                    {{ $lotecampo->superficie_ha }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Siembra:</strong>
                                    {{ $lotecampo->fecha_siembra }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Cosecha:</strong>
                                    {{ $lotecampo->fecha_cosecha }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Humedad Suelo Pct:</strong>
                                    {{ $lotecampo->humedad_suelo_pct }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
