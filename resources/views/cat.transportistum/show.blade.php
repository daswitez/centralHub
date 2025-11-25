@extends('layouts.app')

@section('template_title')
    {{ $cat.transportistum->name ?? __('Show') . " " . __('Cat.transportistum') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Cat.transportistum</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('cat.transportista.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Transportista Id:</strong>
                                    {{ $cat.transportistum->transportista_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Codigo Transp:</strong>
                                    {{ $cat.transportistum->codigo_transp }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $cat.transportistum->nombre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nro Licencia:</strong>
                                    {{ $cat.transportistum->nro_licencia }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
