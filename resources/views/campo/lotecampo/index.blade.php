@extends('layouts.app')

@section('template_title')
    Campo.lotecampos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Campo.lotecampos') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('campo.lotecampos.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Lote Campo Id</th>
									<th >Codigo Lote Campo</th>
									<th >Productor Id</th>
									<th >Variedad Id</th>
									<th >Superficie Ha</th>
									<th >Fecha Siembra</th>
									<th >Fecha Cosecha</th>
									<th >Humedad Suelo Pct</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lotecampos as $lotecampo)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $lotecampo->lote_campo_id }}</td>
										<td >{{ $lotecampo->codigo_lote_campo }}</td>
										<td >{{ $lotecampo->productor_id }}</td>
										<td >{{ $lotecampo->variedad_id }}</td>
										<td >{{ $lotecampo->superficie_ha }}</td>
										<td >{{ $lotecampo->fecha_siembra }}</td>
										<td >{{ $lotecampo->fecha_cosecha }}</td>
										<td >{{ $lotecampo->humedad_suelo_pct }}</td>

                                            <td>
                                                <form action="{{ route('campo.lotecampos.destroy', $lotecampo->getKey()) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('campo.lotecampos.show', $lotecampo->getKey()) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('campo.lotecampos.edit', $lotecampo->getKey()) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $lotecampos->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
