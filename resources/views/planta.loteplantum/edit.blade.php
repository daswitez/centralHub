@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Planta.loteplantum
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Planta.loteplantum</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('planta.loteplanta.update', $planta.loteplantum->getKey()) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('planta.loteplantum.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
