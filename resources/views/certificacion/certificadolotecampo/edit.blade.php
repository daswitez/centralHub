@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Certificacion.certificadolotecampo
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Certificacion.certificadolotecampo</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('certificacion.certificadolotecampos.update', $certificadolotecampo->getKey()) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('certificacion.certificadolotecampo.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
