@extends('layouts.app')

@section('template_title')
    Planta.controlprocesos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Planta.controlprocesos') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('planta.controlprocesos.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Control Id</th>
									<th >Lote Planta Id</th>
									<th >Etapa</th>
									<th >Fecha Hora</th>
									<th >Parametro</th>
									<th >Valor Num</th>
									<th >Valor Texto</th>
									<th >Estado</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($controlprocesos as $controlproceso)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $controlproceso->control_id }}</td>
										<td >{{ $controlproceso->lote_planta_id }}</td>
										<td >{{ $controlproceso->etapa }}</td>
										<td >{{ $controlproceso->fecha_hora }}</td>
										<td >{{ $controlproceso->parametro }}</td>
										<td >{{ $controlproceso->valor_num }}</td>
										<td >{{ $controlproceso->valor_texto }}</td>
										<td >{{ $controlproceso->estado }}</td>

                                            <td>
                                                <form action="{{ route('planta.controlprocesos.destroy', $controlproceso->getKey()) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('planta.controlprocesos.show', $controlproceso->getKey()) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('planta.controlprocesos.edit', $controlproceso->getKey()) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $controlprocesos->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
