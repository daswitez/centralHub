@extends('layouts.app')

@section('template_title')
    Cat.clientes
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Cat.clientes') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('cat.clientes.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Cliente Id</th>
									<th >Codigo Cliente</th>
									<th >Nombre</th>
									<th >Tipo</th>
									<th >Municipio Id</th>
									<th >Direccion</th>
									<th >Lat</th>
									<th >Lon</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clientes as $cliente)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $cliente->cliente_id }}</td>
										<td >{{ $cliente->codigo_cliente }}</td>
										<td >{{ $cliente->nombre }}</td>
										<td >{{ $cliente->tipo }}</td>
										<td >{{ $cliente->municipio_id }}</td>
										<td >{{ $cliente->direccion }}</td>
										<td >{{ $cliente->lat }}</td>
										<td >{{ $cliente->lon }}</td>

                                            <td>
                                                <form action="{{ route('cat.clientes.destroy', $cliente->getKey()) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('cat.clientes.show', $cliente->getKey()) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('cat.clientes.edit', $cliente->getKey()) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $clientes->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
