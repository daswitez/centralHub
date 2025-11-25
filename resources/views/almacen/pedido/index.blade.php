@extends('layouts.app')

@section('template_title')
    Almacen.pedidos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Almacen.pedidos') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('almacen.pedidos.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Pedido Almacen Id</th>
									<th >Codigo Pedido</th>
									<th >Almacen Id</th>
									<th >Fecha Pedido</th>
									<th >Estado</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pedidos as $pedido)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $pedido->pedido_almacen_id }}</td>
										<td >{{ $pedido->codigo_pedido }}</td>
										<td >{{ $pedido->almacen_id }}</td>
										<td >{{ $pedido->fecha_pedido }}</td>
										<td >{{ $pedido->estado }}</td>

                                            <td>
                                                <form action="{{ route('almacen.pedidos.destroy', $pedido->getKey()) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('almacen.pedidos.show', $pedido->getKey()) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('almacen.pedidos.edit', $pedido->getKey()) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $pedidos->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
