@extends('layouts.app')

@section('template_title')
    {{ $certificadoenvio->name ?? __('Show') . " " . __('Certificacion.certificadoenvio') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Certificacion.certificadoenvio</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('certificacion.certificadoenvios.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Certificado Id:</strong>
                                    {{ $certificadoenvio->certificado_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Envio Id:</strong>
                                    {{ $certificadoenvio->envio_id }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
