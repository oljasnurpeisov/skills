@extends('app.layout.default.template')

@section('content')
    <main class="main">
        <section class="plain">
            <div class="container">
                <div class="title-block">
                    <div class="row row--multiline align-items-center">
                        <div class="col-auto"><h1
                                class="title-primary">{{__('default.pages.courses.my_courses_title')}}</h1></div>
                        <div class="col-auto">
                            <a href="/{{$lang}}/create-course">
                                <div class="ghost-btn ghost-btn--blue">{{__('default.pages.courses.create_course')}}
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mobile-dropdown">
                    <div class="mobile-dropdown__title dynamic">{{__('default.pages.courses.my_courses')}}</div>
                    <div class="mobile-dropdown__desc">
                        <ul class="tabs-links">
                            <li @if(Route::currentRouteName() === 'author.courses.my_courses') class="active" @endif>
                                <a href="{{ route('author.courses.my_courses', ['lang' => $lang]) }}" title="{{__('default.pages.courses.my_courses')}}">{{__('default.pages.courses.my_courses')}}</a>
                            </li>
                            <li @if(Route::currentRouteName() === 'author.courses.unpublished') class="active" @endif>
                                <a href="{{ route('author.courses.unpublished', ['lang' => $lang]) }}" title="{{__('default.pages.courses.my_courses_unpublished')}}">{{__('default.pages.courses.my_courses_unpublished')}}</a>
                            </li>
                            <li @if(Route::currentRouteName() === 'author.courses.on_check') class="active" @endif>
                                <a href="{{ route('author.courses.on_check', ['lang' => $lang]) }}" title="{{__('default.pages.courses.my_courses_onCheck')}}">{{__('default.pages.courses.my_courses_onCheck')}}</a>
                            </li>
                            <li @if(Route::currentRouteName() === 'author.courses.drafts') class="active" @endif>
                                <a href="{{ route('author.courses.drafts', ['lang' => $lang]) }}" title="{{__('default.pages.courses.drafts')}}">{{__('default.pages.courses.drafts')}}</a>
                            </li>
                            <li @if(Route::currentRouteName() === 'author.courses.deleted') class="active" @endif>
                                <a href="{{ route('author.courses.deleted', ['lang' => $lang]) }}" title="{{__('default.pages.courses.my_courses_deleted')}}">{{__('default.pages.courses.my_courses_deleted')}}</a>
                            </li>
                            <li @if(Route::currentRouteName() === 'author.courses.signing') class="active" @endif>
                                <a href="{{ route('author.courses.signing', ['lang' => $lang]) }}" title="">На подписании</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row row--multiline column-reverse-sm">
                    <div class="col-md-12">
                        <a href="{{ route('author.courses.signing.contract.reject', ['lang' => $lang, 'id' => $id]) }}" class="btn">Отклонить</a>
                        <a href="" class="btn">Подписать</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
@endsection

