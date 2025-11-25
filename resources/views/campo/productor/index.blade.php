@extends('layouts.app')

@section('template_title')
    Campo.productors
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Campo.productors') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('campo.productors.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Productor Id</th>
									<th >Codigo Productor</th>
									<th >Nombre</th>
									<th >Municipio Id</th>
									<th >Telefono</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productors as $productor)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $productor->productor_id }}</td>
										<td >{{ $productor->codigo_productor }}</td>
										<td >{{ $productor->nombre }}</td>
										<td >{{ $productor->municipio_id }}</td>
										<td >{{ $productor->telefono }}</td>

                                            <td>
                                                <form action="{{ route('campo.productors.destroy', $productor->getKey()) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('campo.productors.show', $productor->getKey()) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('campo.productors.edit', $productor->getKey()) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $productors->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
