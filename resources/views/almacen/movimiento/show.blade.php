@extends('layouts.app')

@section('template_title')
    {{ $movimiento->name ?? __('Show') . " " . __('Almacen.movimiento') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Almacen.movimiento</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('almacen.movimientos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Mov Id:</strong>
                                    {{ $movimiento->mov_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Almacen Id:</strong>
                                    {{ $movimiento->almacen_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Lote Salida Id:</strong>
                                    {{ $movimiento->lote_salida_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo:</strong>
                                    {{ $movimiento->tipo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cantidad T:</strong>
                                    {{ $movimiento->cantidad_t }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Mov:</strong>
                                    {{ $movimiento->fecha_mov }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Referencia:</strong>
                                    {{ $movimiento->referencia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Detalle:</strong>
                                    {{ $movimiento->detalle }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
