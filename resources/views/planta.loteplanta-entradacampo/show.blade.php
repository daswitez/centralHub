@extends('layouts.app')

@section('template_title')
    {{ $planta.loteplantaEntradacampo->name ?? __('Show') . " " . __('Planta.loteplanta Entradacampo') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Planta.loteplanta Entradacampo</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('planta.loteplanta-entradacampos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Lote Planta Id:</strong>
                                    {{ $planta.loteplantaEntradacampo->lote_planta_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lote Campo Id:</strong>
                                    {{ $planta.loteplantaEntradacampo->lote_campo_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Peso Entrada T:</strong>
                                    {{ $planta.loteplantaEntradacampo->peso_entrada_t }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
