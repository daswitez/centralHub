@extends('layouts.app')

@section('template_title')
    {{ $certificadolotecampo->name ?? __('Show') . " " . __('Certificacion.certificadolotecampo') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Certificacion.certificadolotecampo</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('certificacion.certificadolotecampos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Certificado Id:</strong>
                                    {{ $certificadolotecampo->certificado_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lote Campo Id:</strong>
                                    {{ $certificadolotecampo->lote_campo_id }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
