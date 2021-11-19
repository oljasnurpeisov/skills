@extends('app.layout.default.template')

@section('css')
    .graph-container {
    width: 100%;
    height: 30vh;
    }
@stop
@section('content')
    <main class="main">

        <section class="plain">
            <div class="container">
                <ul class="breadcrumbs">
                    <li><a href="/{{$lang}}"
                           title="{{__('default.main_title')}}">{{__('default.main_title')}}</a>
                    </li>
                    <li><a href="/{{$lang}}/authors">{{__('default.pages.authors.catalog_title')}}</a></li>
                    <li><span>Автор</span></li>
                </ul>
                <h1 class="title-primary">Автор</h1>
            </div>

        </section>
    </main>
@endsection
