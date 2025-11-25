@extends('layouts.app')

@section('template_title')
    Almacen.recepcions
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Almacen.recepcions') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('almacen.recepcions.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Recepcion Id</th>
									<th >Envio Id</th>
									<th >Almacen Id</th>
									<th >Fecha Recepcion</th>
									<th >Observacion</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recepcions as $recepcion)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $recepcion->recepcion_id }}</td>
										<td >{{ $recepcion->envio_id }}</td>
										<td >{{ $recepcion->almacen_id }}</td>
										<td >{{ $recepcion->fecha_recepcion }}</td>
										<td >{{ $recepcion->observacion }}</td>

                                            <td>
                                                <form action="{{ route('almacen.recepcions.destroy', $recepcion->getKey()) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('almacen.recepcions.show', $recepcion->getKey()) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('almacen.recepcions.edit', $recepcion->getKey()) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $recepcions->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
