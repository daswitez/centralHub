@extends('layouts.app')

@section('template_title')
    Planta.loteplanta
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Planta.loteplanta') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('planta.loteplanta.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
									<th >Lote Planta Id</th>
									<th >Codigo Lote Planta</th>
									<th >Planta Id</th>
									<th >Fecha Inicio</th>
									<th >Fecha Fin</th>
									<th >Rendimiento Pct</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($planta.loteplanta as $planta.loteplantum)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $planta.loteplantum->lote_planta_id }}</td>
										<td >{{ $planta.loteplantum->codigo_lote_planta }}</td>
										<td >{{ $planta.loteplantum->planta_id }}</td>
										<td >{{ $planta.loteplantum->fecha_inicio }}</td>
										<td >{{ $planta.loteplantum->fecha_fin }}</td>
										<td >{{ $planta.loteplantum->rendimiento_pct }}</td>

                                            <td>
                                                <form action="{{ route('planta.loteplanta.destroy', $planta.loteplantum->getKey()) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('planta.loteplanta.show', $planta.loteplantum->getKey()) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('planta.loteplanta.edit', $planta.loteplantum->getKey()) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $planta.loteplanta->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
