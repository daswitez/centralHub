@extends('layouts.app')

@section('template_title')
    {{ $controlproceso->name ?? __('Show') . " " . __('Planta.controlproceso') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Planta.controlproceso</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('planta.controlprocesos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Control Id:</strong>
                                    {{ $controlproceso->control_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lote Planta Id:</strong>
                                    {{ $controlproceso->lote_planta_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Etapa:</strong>
                                    {{ $controlproceso->etapa }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Hora:</strong>
                                    {{ $controlproceso->fecha_hora }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Parametro:</strong>
                                    {{ $controlproceso->parametro }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Valor Num:</strong>
                                    {{ $controlproceso->valor_num }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Valor Texto:</strong>
                                    {{ $controlproceso->valor_texto }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado:</strong>
                                    {{ $controlproceso->estado }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
