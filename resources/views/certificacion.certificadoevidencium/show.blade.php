@extends('layouts.app')

@section('template_title')
    {{ $certificacion.certificadoevidencium->name ?? __('Show') . " " . __('Certificacion.certificadoevidencium') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Certificacion.certificadoevidencium</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('certificacion.certificadoevidencia.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Evidencia Id:</strong>
                                    {{ $certificacion.certificadoevidencium->evidencia_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Certificado Id:</strong>
                                    {{ $certificacion.certificadoevidencium->certificado_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo:</strong>
                                    {{ $certificacion.certificadoevidencium->tipo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Descripcion:</strong>
                                    {{ $certificacion.certificadoevidencium->descripcion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Url Archivo:</strong>
                                    {{ $certificacion.certificadoevidencium->url_archivo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Registro:</strong>
                                    {{ $certificacion.certificadoevidencium->fecha_registro }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
