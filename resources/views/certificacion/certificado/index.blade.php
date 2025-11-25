@extends('layouts.app')

@section('template_title')
    Certificacion.certificados
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Certificacion.certificados') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('certificacion.certificados.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Certificado Id</th>
									<th >Codigo Certificado</th>
									<th >Ambito</th>
									<th >Area</th>
									<th >Vigente Desde</th>
									<th >Vigente Hasta</th>
									<th >Emisor</th>
									<th >Url Archivo</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($certificados as $certificado)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $certificado->certificado_id }}</td>
										<td >{{ $certificado->codigo_certificado }}</td>
										<td >{{ $certificado->ambito }}</td>
										<td >{{ $certificado->area }}</td>
										<td >{{ $certificado->vigente_desde }}</td>
										<td >{{ $certificado->vigente_hasta }}</td>
										<td >{{ $certificado->emisor }}</td>
										<td >{{ $certificado->url_archivo }}</td>

                                            <td>
                                                <form action="{{ route('certificacion.certificados.destroy', $certificado->getKey()) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('certificacion.certificados.show', $certificado->getKey()) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('certificacion.certificados.edit', $certificado->getKey()) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $certificados->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
