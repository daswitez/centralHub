@extends('layouts.app')

@section('template_title')
    Logistica.envios
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Logistica.envios') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('logistica.envios.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Envio Id</th>
									<th >Codigo Envio</th>
									<th >Ruta Id</th>
									<th >Transportista Id</th>
									<th >Fecha Salida</th>
									<th >Fecha Llegada</th>
									<th >Temp Min C</th>
									<th >Temp Max C</th>
									<th >Estado</th>
									<th >Almacen Origen Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($envios as $envio)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $envio->envio_id }}</td>
										<td >{{ $envio->codigo_envio }}</td>
										<td >{{ $envio->ruta_id }}</td>
										<td >{{ $envio->transportista_id }}</td>
										<td >{{ $envio->fecha_salida }}</td>
										<td >{{ $envio->fecha_llegada }}</td>
										<td >{{ $envio->temp_min_c }}</td>
										<td >{{ $envio->temp_max_c }}</td>
										<td >{{ $envio->estado }}</td>
										<td >{{ $envio->almacen_origen_id }}</td>

                                            <td>
                                                <form action="{{ route('logistica.envios.destroy', $envio->getKey()) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('logistica.envios.show', $envio->getKey()) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('logistica.envios.edit', $envio->getKey()) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $envios->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
