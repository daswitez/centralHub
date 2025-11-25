@extends('layouts.app')

@section('template_title')
    Logistica.enviodetallealmacens
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Logistica.enviodetallealmacens') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('logistica.enviodetallealmacens.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Envio Detalle Alm Id</th>
									<th >Envio Id</th>
									<th >Lote Salida Id</th>
									<th >Almacen Id</th>
									<th >Cantidad T</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($enviodetallealmacens as $enviodetallealmacen)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $enviodetallealmacen->envio_detalle_alm_id }}</td>
										<td >{{ $enviodetallealmacen->envio_id }}</td>
										<td >{{ $enviodetallealmacen->lote_salida_id }}</td>
										<td >{{ $enviodetallealmacen->almacen_id }}</td>
										<td >{{ $enviodetallealmacen->cantidad_t }}</td>

                                            <td>
                                                <form action="{{ route('logistica.enviodetallealmacens.destroy', $enviodetallealmacen->getKey()) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('logistica.enviodetallealmacens.show', $enviodetallealmacen->getKey()) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('logistica.enviodetallealmacens.edit', $enviodetallealmacen->getKey()) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $enviodetallealmacens->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
