@extends('layouts.app')

@section('template_title')
    Cat.almacens
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Cat.almacens') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('cat.almacens.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Almacen Id</th>
									<th >Codigo Almacen</th>
									<th >Nombre</th>
									<th >Municipio Id</th>
									<th >Direccion</th>
									<th >Lat</th>
									<th >Lon</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($almacens as $almacen)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $almacen->almacen_id }}</td>
										<td >{{ $almacen->codigo_almacen }}</td>
										<td >{{ $almacen->nombre }}</td>
										<td >{{ $almacen->municipio_id }}</td>
										<td >{{ $almacen->direccion }}</td>
										<td >{{ $almacen->lat }}</td>
										<td >{{ $almacen->lon }}</td>

                                            <td>
                                                <form action="{{ route('cat.almacens.destroy', $almacen->getKey()) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('cat.almacens.show', $almacen->getKey()) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('cat.almacens.edit', $almacen->getKey()) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $almacens->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
