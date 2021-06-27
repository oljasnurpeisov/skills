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
                            <li @if(Route::currentRouteName() === 'author.courses.signing' || Route::currentRouteName() === 'author.courses.signing.contract') class="active" @endif>
                                <a href="{{ route('author.courses.signing', ['lang' => $lang]) }}" title="">На подписании</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row row--multiline column-reverse-sm">
                    <div class="col-md-12">
{{--                        <div id="contract" style="max-width: 100%">--}}
{{--                            {!! $contract !!}--}}
{{--                        </div>--}}
                        <iframe src="{{ route('author.courses.signing.contractDoc', ['lang' => 'ru', 'contract_id' => $contract->id]) }}" frameborder="0" width="100%" height="600"></iframe>

                        <a href="{{ route('author.courses.signing.contract.reject', ['lang' => $lang, 'contract_id' => $contract->id]) }}" class="btn" style="margin-right: 15px;">Отклонить</a>
                        <a href="{{ route('author.courses.signing.contract.next', ['lang' => $lang, 'contract_id' => $contract->id]) }}" class="btn">Подписать</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection


<style>
    #contract * {word-break: break-word}
    #contract table {max-width: 100% !important;}
    #contract tr {max-width: 50% !important;}
</style>
@section('scripts')
    <!--Only this page's scripts-->
@endsection

