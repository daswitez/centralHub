@extends('layouts.app')

@section('template_title')
    Cat.planta
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Cat.planta') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('cat.planta.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Planta Id</th>
									<th >Codigo Planta</th>
									<th >Nombre</th>
									<th >Municipio Id</th>
									<th >Direccion</th>
									<th >Lat</th>
									<th >Lon</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cat.planta as $cat.plantum)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $cat.plantum->planta_id }}</td>
										<td >{{ $cat.plantum->codigo_planta }}</td>
										<td >{{ $cat.plantum->nombre }}</td>
										<td >{{ $cat.plantum->municipio_id }}</td>
										<td >{{ $cat.plantum->direccion }}</td>
										<td >{{ $cat.plantum->lat }}</td>
										<td >{{ $cat.plantum->lon }}</td>

                                            <td>
                                                <form action="{{ route('cat.planta.destroy', $cat.plantum->getKey()) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('cat.planta.show', $cat.plantum->getKey()) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('cat.planta.edit', $cat.plantum->getKey()) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $cat.planta->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
