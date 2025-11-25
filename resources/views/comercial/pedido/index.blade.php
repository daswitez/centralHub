@extends('layouts.app')

@section('template_title')
    Comercial.pedidos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Comercial.pedidos') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('comercial.pedidos.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Pedido Id</th>
									<th >Codigo Pedido</th>
									<th >Cliente Id</th>
									<th >Fecha Pedido</th>
									<th >Estado</th>
									<th >Almacen Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pedidos as $pedido)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $pedido->pedido_id }}</td>
										<td >{{ $pedido->codigo_pedido }}</td>
										<td >{{ $pedido->cliente_id }}</td>
										<td >{{ $pedido->fecha_pedido }}</td>
										<td >{{ $pedido->estado }}</td>
										<td >{{ $pedido->almacen_id }}</td>

                                            <td>
                                                <form action="{{ route('comercial.pedidos.destroy', $pedido->getKey()) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('comercial.pedidos.show', $pedido->getKey()) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('comercial.pedidos.edit', $pedido->getKey()) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
