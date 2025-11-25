@extends('layouts.app')

@section('template_title')
    {{ $municipio->name ?? __('Show') . " " . __('Cat.municipio') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Cat.municipio</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('cat.municipios.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Municipio Id:</strong>
                                    {{ $municipio->municipio_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Departamento Id:</strong>
                                    {{ $municipio->departamento_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $municipio->nombre }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
