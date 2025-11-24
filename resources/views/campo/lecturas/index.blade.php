@extends('layouts.app')

@section('page_title', 'Lecturas de sensores (Campo)')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0 text-uppercase">Lecturas de sensores</h1>
            <p class="text-secondary mb-0 small">Filtra por lote, tipo y rango de fechas</p>
        </div>
        <ol class="breadcrumb float-sm-right bg-transparent mb-0 p-0">
            <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Lecturas</li>
        </ol>
    </div>
@endsection

@section('content')
    @include('components.alerts')

    <div class="card card-outline card-dark">
        <div class="card-header d-flex justify-content-between align-items-center">
            <form class="form-inline" method="get" action="{{ route('campo.lecturas.index') }}">
                <div class="form-row align-items-center">
                    <div class="col-auto mr-2">
                        <select name="lote_campo_id" class="form-control">
                            <option value="0">Todos los lotes</option>
                            @foreach($lotes as $l)
                                <option value="{{ $l->lote_campo_id }}" {{ $loteId==$l->lote_campo_id?'selected':'' }}>
                                    #{{ $l->lote_campo_id }} — {{ $l->codigo_lote_campo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto mr-2">
                        <input type="text" name="tipo" value="{{ $tipo }}" class="form-control" placeholder="Tipo (ej. humedad)">
                    </div>
                    <div class="col-auto mr-2">
                        <input type="datetime-local" name="desde" value="{{ $desde }}" class="form-control">
                    </div>
                    <div class="col-auto mr-2">
                        <input type="datetime-local" name="hasta" value="{{ $hasta }}" class="form-control">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-dark" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
            <a href="{{ route('campo.lecturas.create') }}" class="btn btn-dark">
                <i class="fas fa-plus mr-1"></i> Nueva lectura
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-dark">
                    <tr>
                        <th style="width:9rem;">Fecha/Hora</th>
                        <th>Lote</th>
                        <th>Tipo</th>
                        <th>Valor num</th>
                        <th>Valor texto</th>
                        <th style="width: 9rem;">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($lecturas as $r)
                        <tr>
                            <td>{{ \Illuminate\Support\Str::of($r->fecha_hora) }}</td>
                            <td>#{{ $r->lote_campo_id }}</td>
                            <td>{{ $r->tipo }}</td>
                            <td>{{ $r->valor_num }}</td>
                            <td class="text-truncate" title="{{ $r->valor_texto }}">{{ $r->valor_texto }}</td>
                            <td>
                                <a href="{{ route('campo.lecturas.edit', $r->lectura_id) }}" class="btn btn-sm btn-outline-dark">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form class="d-inline" method="post" action="{{ route('campo.lecturas.destroy', $r->lectura_id) }}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar lectura?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Sin datos</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $lecturas->links() }}
        </div>
    </div>
@endsection


