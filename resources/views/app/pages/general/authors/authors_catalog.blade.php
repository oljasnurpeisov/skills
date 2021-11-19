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
                    <li><span>{{__('default.pages.authors.catalog_title')}}</span></li>
                </ul>
                <h1 class="title-primary">{{__('default.pages.authors.catalog_title')}}</h1>
                <form action="">
                    <div class="form-group">
                        <div class="row row--multiline">
                            <div class="col-auto col-grow-1">
                                <input type="text" name="search" class="input-regular"
                                       placeholder="{{__('default.pages.authors.search_placeholder')}}"
                                       value="{{$request->search}}">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn">{{__('default.pages.courses.search_button')}}</button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row row--multiline column-reverse-sm">
                    <div class="col-md-12">
                        <div class="row row--multiline">

                        </div>
                        <div class="text-center">
                            {{ $items->appends(request()->input())->links('vendor.pagination.default') }}
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </main>
@endsection
