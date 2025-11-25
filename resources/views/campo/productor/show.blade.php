@extends('layouts.app')

@section('template_title')
    {{ $productor->name ?? __('Show') . " " . __('Campo.productor') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Campo.productor</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('campo.productors.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Productor Id:</strong>
                                    {{ $productor->productor_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Codigo Productor:</strong>
                                    {{ $productor->codigo_productor }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $productor->nombre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Municipio Id:</strong>
                                    {{ $productor->municipio_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Telefono:</strong>
                                    {{ $productor->telefono }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
