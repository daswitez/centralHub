@extends('layouts.app')

@section('template_title')
    {{ $sensorlectura->name ?? __('Show') . " " . __('Campo.sensorlectura') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Campo.sensorlectura</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('campo.sensorlecturas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Lectura Id:</strong>
                                    {{ $sensorlectura->lectura_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lote Campo Id:</strong>
                                    {{ $sensorlectura->lote_campo_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Hora:</strong>
                                    {{ $sensorlectura->fecha_hora }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo:</strong>
                                    {{ $sensorlectura->tipo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Valor Num:</strong>
                                    {{ $sensorlectura->valor_num }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Valor Texto:</strong>
                                    {{ $sensorlectura->valor_texto }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
