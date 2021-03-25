@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="plain">
            <div class="container">
                <ul class="breadcrumbs">
                    <li><a href="/{{$lang}}/student/my-courses"
                           title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
                    </li>
                    <li><a href="/{{$lang}}/course-catalog/course/{{$item->id}}"
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
                    </div>
                    <div class="col-md-4">
                        @include('app.pages.student.lesson.components.media_attachments',['item' => $item, 'sidebar_btn' => true])
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script src="/assets/js/visually-impaired-tools.js"></script>
    <!---->
@endsection

