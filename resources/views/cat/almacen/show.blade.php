@extends('layouts.app')

@section('template_title')
    {{ $almacen->name ?? __('Show') . " " . __('Cat.almacen') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Cat.almacen</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('cat.almacens.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Almacen Id:</strong>
                                    {{ $almacen->almacen_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Codigo Almacen:</strong>
                                    {{ $almacen->codigo_almacen }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $almacen->nombre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Municipio Id:</strong>
                                    {{ $almacen->municipio_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Direccion:</strong>
                                    {{ $almacen->direccion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lat:</strong>
                                    {{ $almacen->lat }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lon:</strong>
                                    {{ $almacen->lon }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
