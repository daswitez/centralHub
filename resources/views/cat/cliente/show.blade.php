@extends('layouts.app')

@section('template_title')
    {{ $cliente->name ?? __('Show') . " " . __('Cat.cliente') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Cat.cliente</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('cat.clientes.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Cliente Id:</strong>
                                    {{ $cliente->cliente_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Codigo Cliente:</strong>
                                    {{ $cliente->codigo_cliente }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $cliente->nombre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo:</strong>
                                    {{ $cliente->tipo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Municipio Id:</strong>
                                    {{ $cliente->municipio_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Direccion:</strong>
                                    {{ $cliente->direccion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lat:</strong>
                                    {{ $cliente->lat }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lon:</strong>
                                    {{ $cliente->lon }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
