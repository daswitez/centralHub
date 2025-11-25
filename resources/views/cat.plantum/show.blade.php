@extends('layouts.app')

@section('template_title')
    {{ $cat.plantum->name ?? __('Show') . " " . __('Cat.plantum') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Cat.plantum</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('cat.planta.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Planta Id:</strong>
                                    {{ $cat.plantum->planta_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Codigo Planta:</strong>
                                    {{ $cat.plantum->codigo_planta }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $cat.plantum->nombre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Municipio Id:</strong>
                                    {{ $cat.plantum->municipio_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Direccion:</strong>
                                    {{ $cat.plantum->direccion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lat:</strong>
                                    {{ $cat.plantum->lat }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lon:</strong>
                                    {{ $cat.plantum->lon }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
