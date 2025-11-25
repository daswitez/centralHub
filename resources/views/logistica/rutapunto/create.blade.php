@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Logistica.rutapunto
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Logistica.rutapunto</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('logistica.rutapuntos.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('logistica.rutapunto.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
