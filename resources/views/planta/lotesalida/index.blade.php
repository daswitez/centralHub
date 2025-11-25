@extends('layouts.app')

@section('template_title')
    Planta.lotesalidas
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Planta.lotesalidas') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('planta.lotesalidas.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Lote Salida Id</th>
									<th >Codigo Lote Salida</th>
									<th >Lote Planta Id</th>
									<th >Sku</th>
									<th >Peso T</th>
									<th >Fecha Empaque</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lotesalidas as $lotesalida)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $lotesalida->lote_salida_id }}</td>
										<td >{{ $lotesalida->codigo_lote_salida }}</td>
										<td >{{ $lotesalida->lote_planta_id }}</td>
										<td >{{ $lotesalida->sku }}</td>
										<td >{{ $lotesalida->peso_t }}</td>
										<td >{{ $lotesalida->fecha_empaque }}</td>

                                            <td>
                                                <form action="{{ route('planta.lotesalidas.destroy', $lotesalida->getKey()) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('planta.lotesalidas.show', $lotesalida->getKey()) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('planta.lotesalidas.edit', $lotesalida->getKey()) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $lotesalidas->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
