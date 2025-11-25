@extends('layouts.app')

@section('template_title')
    Almacen.movimientos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Almacen.movimientos') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('almacen.movimientos.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Mov Id</th>
									<th >Almacen Id</th>
									<th >Lote Salida Id</th>
									<th >Tipo</th>
									<th >Cantidad T</th>
									<th >Fecha Mov</th>
									<th >Referencia</th>
									<th >Detalle</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movimientos as $movimiento)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $movimiento->mov_id }}</td>
										<td >{{ $movimiento->almacen_id }}</td>
										<td >{{ $movimiento->lote_salida_id }}</td>
										<td >{{ $movimiento->tipo }}</td>
										<td >{{ $movimiento->cantidad_t }}</td>
										<td >{{ $movimiento->fecha_mov }}</td>
										<td >{{ $movimiento->referencia }}</td>
										<td >{{ $movimiento->detalle }}</td>

                                            <td>
                                                <form action="{{ route('almacen.movimientos.destroy', $movimiento->getKey()) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('almacen.movimientos.show', $movimiento->getKey()) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('almacen.movimientos.edit', $movimiento->getKey()) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $movimientos->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
