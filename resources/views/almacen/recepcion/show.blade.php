@extends('layouts.app')

@section('template_title')
    {{ $recepcion->name ?? __('Show') . " " . __('Almacen.recepcion') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Almacen.recepcion</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('almacen.recepcions.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Recepcion Id:</strong>
                                    {{ $recepcion->recepcion_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Envio Id:</strong>
                                    {{ $recepcion->envio_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Almacen Id:</strong>
                                    {{ $recepcion->almacen_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Recepcion:</strong>
                                    {{ $recepcion->fecha_recepcion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Observacion:</strong>
                                    {{ $recepcion->observacion }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
