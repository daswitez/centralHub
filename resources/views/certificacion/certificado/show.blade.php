@extends('layouts.app')

@section('template_title')
    {{ $certificado->name ?? __('Show') . " " . __('Certificacion.certificado') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Certificacion.certificado</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('certificacion.certificados.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Certificado Id:</strong>
                                    {{ $certificado->certificado_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Codigo Certificado:</strong>
                                    {{ $certificado->codigo_certificado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ambito:</strong>
                                    {{ $certificado->ambito }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Area:</strong>
                                    {{ $certificado->area }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Vigente Desde:</strong>
                                    {{ $certificado->vigente_desde }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Vigente Hasta:</strong>
                                    {{ $certificado->vigente_hasta }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Emisor:</strong>
                                    {{ $certificado->emisor }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Url Archivo:</strong>
                                    {{ $certificado->url_archivo }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
