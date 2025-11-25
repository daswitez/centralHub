@extends('layouts.app')

@section('template_title')
    Certificacion.certificadoevidencia
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Certificacion.certificadoevidencia') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('certificacion.certificadoevidencia.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Evidencia Id</th>
									<th >Certificado Id</th>
									<th >Tipo</th>
									<th >Descripcion</th>
									<th >Url Archivo</th>
									<th >Fecha Registro</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($certificacion.certificadoevidencia as $certificacion.certificadoevidencium)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $certificacion.certificadoevidencium->evidencia_id }}</td>
										<td >{{ $certificacion.certificadoevidencium->certificado_id }}</td>
										<td >{{ $certificacion.certificadoevidencium->tipo }}</td>
										<td >{{ $certificacion.certificadoevidencium->descripcion }}</td>
										<td >{{ $certificacion.certificadoevidencium->url_archivo }}</td>
										<td >{{ $certificacion.certificadoevidencium->fecha_registro }}</td>

                                            <td>
                                                <form action="{{ route('certificacion.certificadoevidencia.destroy', $certificacion.certificadoevidencium->getKey()) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('certificacion.certificadoevidencia.show', $certificacion.certificadoevidencium->getKey()) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('certificacion.certificadoevidencia.edit', $certificacion.certificadoevidencium->getKey()) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $certificacion.certificadoevidencia->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
