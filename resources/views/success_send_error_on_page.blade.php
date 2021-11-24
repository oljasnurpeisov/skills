@extends('app.layout.default.template')

@section('content')
    <main class="main">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center" style="padding-bottom: 90px">
                    <h1>{{ __('default.thanks_for_error') }}</h1>
                    <a class="btn" href="{{ url()->previous() }}">{{ __('default.back') }}</a>
                </div>
            </div>

        </div>
    </main>
@endsection
