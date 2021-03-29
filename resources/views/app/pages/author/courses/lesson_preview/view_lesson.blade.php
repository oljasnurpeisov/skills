@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="plain">
            <div class="container">
                <ul class="breadcrumbs">
                    <li><a href="/{{$lang}}/my-courses"
                           title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
                    </li>
                    @include('app.pages.author.courses.components.breadcrumb_course_type',['item' => $item])
                    <li><a href="/{{$lang}}/my-courses/course/{{$item->id}}"
                           title="{{$item->name}}">{{$item->name}}</a>
                    </li>
                    @if($lesson->type == 3)
                        <li>
                            <span>{{$lesson->lesson_type->getAttribute('name_'.$lang) ?? $lesson->lesson_type->getAttribute('name_ru')}}</span>
                        </li>
                    @elseif($lesson->type == 4)
                        <li>
                            <span>{{$lesson->lesson_type->getAttribute('name_'.$lang) ?? $lesson->lesson_type->getAttribute('name_ru')}}</span>
                        </li>
                    @else
                        <li><span>{{$lesson->name}}</span></li>
                    @endif
                </ul>

                @include('.app.pages.author.courses.components.preview_alert_info')

                <div class="row row--multiline">
                    <div class="col-md-8">
                        <div class="article">
                            @if($lesson->type == 3)
                                <h1 class="page-title">{{$lesson->lesson_type->getAttribute('name_'.$lang) ?? $lesson->lesson_type->getAttribute('name_ru')}}</h1>
                            @elseif($lesson->type == 4)
                                <h1 class="page-title">{{$lesson->lesson_type->getAttribute('name_'.$lang) ?? $lesson->lesson_type->getAttribute('name_ru')}}</h1>
                            @else
                                <h1 class="page-title">{{$lesson->name}}</h1>
                            @endif
                            <div class="article__info">
                                <span><i class="icon-lesson"></i> {{$lesson->lesson_type->getAttribute('name_'.$lang) ?? $lesson->lesson_type->getAttribute('name_ru')}}</span>
                                <span><i class="icon-clock"></i> {{$time .' '. __('default.pages.lessons.hour_short_title')}} </span>
                            </div>
                            @if($lesson->image)
                                <div class="article__image">
                                    <img src="{{ $lesson->image }}" alt="">
                                </div>
                            @endif
                            <div class="plain-text">
                                {!! $lesson->theory !!}
                            </div>
                        </div>
                        @if($item->status == 0 or $item->status == 2)
                            <form action="/{{$lang}}/course-{{$item->id}}/lesson-{{$lesson->id}}/delete-lesson-form"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="row row--multiline hidden-sm hidden-xs">
                                    <div class="col-auto">
                                        @if($lesson->type == 3)
                                            <a href="/{{$lang}}/my-courses/course/{{$item->id}}/edit-coursework"
                                               title="{{__('default.pages.courses.edit_title')}}"
                                               class="ghost-btn">{{__('default.pages.courses.edit_title')}}</a>
                                        @elseif($lesson->type == 4)
                                            <a href="/{{$lang}}/my-courses/course/{{$item->id}}/edit-final-test"
                                               title="{{__('default.pages.courses.edit_title')}}"
                                               class="ghost-btn">{{__('default.pages.courses.edit_title')}}</a>
                                        @else
                                            <a href="/{{$lang}}/my-courses/course/{{$item->id}}/edit-lesson-{{$lesson->id}}"
                                               title="{{__('default.pages.courses.edit_title')}}"
                                               class="ghost-btn">{{__('default.pages.courses.edit_title')}}</a>
                                        @endif
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit"
                                                title="{{__('default.pages.courses.delete_title')}}"
                                                class="ghost-btn"
                                                style="background-color: white">{{__('default.pages.courses.delete_title')}}</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>

                    <div class="col-md-4">
                        @include('app.pages.author.courses.components.lesson.media_attachments',['item' => $item, 'sidebar_btn' => true])
                    </div>
                </div>

                @if($item->status == 0 or $item->status == 2)
                    <form action="/{{$lang}}/course-{{$item->id}}/lesson-{{$lesson->id}}/delete-lesson-form"
                          method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="row row--multiline hidden-md hidden-lg" style="margin-top: 10px">
                            <div class="col-auto">
                                @if($lesson->type == 3)
                                    <a href="/{{$lang}}/my-courses/course/{{$item->id}}/edit-coursework"
                                       title="{{__('default.pages.courses.edit_title')}}"
                                       class="ghost-btn">{{__('default.pages.courses.edit_title')}}</a>
                                @elseif($lesson->type == 4)
                                    <a href="/{{$lang}}/my-courses/course/{{$item->id}}/edit-final-test"
                                       title="{{__('default.pages.courses.edit_title')}}"
                                       class="ghost-btn">{{__('default.pages.courses.edit_title')}}</a>
                                @else
                                    <a href="/{{$lang}}/my-courses/course/{{$item->id}}/edit-lesson-{{$lesson->id}}"
                                       title="{{__('default.pages.courses.edit_title')}}"
                                       class="ghost-btn">{{__('default.pages.courses.edit_title')}}</a>
                                @endif
                            </div>
                            <div class="col-auto">
                                <button type="submit"
                                        title="{{__('default.pages.courses.delete_title')}}"
                                        class="ghost-btn"
                                        style="background-color: white">{{__('default.pages.courses.delete_title')}}</button>
                            </div>
                        </div>
                    </form>
                @endif

            </div>
        </section>

    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script src="/assets/js/visually-impaired-tools.js"></script>
    <!---->
@endsection

