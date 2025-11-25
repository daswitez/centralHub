@extends('layouts.app')

@section('template_title')
    {{ $envio->name ?? __('Show') . " " . __('Logistica.envio') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Logistica.envio</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('logistica.envios.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Envio Id:</strong>
                                    {{ $envio->envio_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Codigo Envio:</strong>
                                    {{ $envio->codigo_envio }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ruta Id:</strong>
                                    {{ $envio->ruta_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Transportista Id:</strong>
                                    {{ $envio->transportista_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Salida:</strong>
                                    {{ $envio->fecha_salida }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Llegada:</strong>
                                    {{ $envio->fecha_llegada }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Temp Min C:</strong>
                                    {{ $envio->temp_min_c }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Temp Max C:</strong>
                                    {{ $envio->temp_max_c }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado:</strong>
                                    {{ $envio->estado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Almacen Origen Id:</strong>
                                    {{ $envio->almacen_origen_id }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
