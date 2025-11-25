@extends('layouts.app')

@section('template_title')
    {{ $variedadpapa->name ?? __('Show') . " " . __('Cat.variedadpapa') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Cat.variedadpapa</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('cat.variedadpapas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Variedad Id:</strong>
                                    {{ $variedadpapa->variedad_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Codigo Variedad:</strong>
                                    {{ $variedadpapa->codigo_variedad }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre Comercial:</strong>
                                    {{ $variedadpapa->nombre_comercial }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Aptitud:</strong>
                                    {{ $variedadpapa->aptitud }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ciclo Dias Min:</strong>
                                    {{ $variedadpapa->ciclo_dias_min }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ciclo Dias Max:</strong>
                                    {{ $variedadpapa->ciclo_dias_max }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
