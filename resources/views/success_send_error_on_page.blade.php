@extends('app.layout.default.template')

@section('content')
    <main class="main">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1>Спасибо, замечание принято в работу</h1>
                    <a class="btn" href="{{ url()->previous() }}">Назад</a>
                </div>
            </div>

        </div>
    </main>
@endsection
