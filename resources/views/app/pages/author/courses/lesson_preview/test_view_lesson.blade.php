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
                    @if($lesson->type == 4)
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
                            @if($lesson->type == 4)
                                <h1 class="page-title">{{$lesson->lesson_type->getAttribute('name_'.$lang) ?? $lesson->lesson_type->getAttribute('name_ru')}}</h1>
                            @else
                                <h1 class="page-title">{{$lesson->name}}</h1>
                            @endif
                            <form action="/{{$lang}}/course-{{$item->id}}/lesson-{{$lesson->id}}/author-test-submit"
                                  method="POST">
                                @csrf
                                <div class="test">
                                    @foreach(json_decode($lesson->practice)->questions as $key => $question)
                                        @if($question->is_pictures == false)
                                            <div class="item">
                                                <div class="question">{!! $question->name !!}
                                                </div>
                                                <div class="form-group">
                                                    @if(json_decode($lesson->practice)->mixAnswers == true)
                                                        <?php
                                                        shuffle($question->answers)
                                                        ?>
                                                    @endif
                                                    @foreach($question->answers as $k => $answer)
                                                        <label class="radio"><input type="radio"
                                                                                    name="answers[{{$key}}]"
                                                                                    value="{{$answer}}"
                                                                                    required><span>{{$answer}}</span></label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="item">
                                                <div class="question">{!! $question->name !!}
                                                </div>
                                                <div class="form-group image-answers">
                                                    @if(json_decode($lesson->practice)->mixAnswers == true)
                                                        <?php
                                                        shuffle($question->answers)
                                                        ?>
                                                    @endif
                                                    @foreach($question->answers as $k => $answer)
                                                        <div>
                                                            <label class="radio"><input type="radio"
                                                                                        name="answers[{{$key}}]"
                                                                                        value="{{$answer}}"
                                                                                        required><span>{{__('default.pages.lessons.option_title')}} {{$k+1}}</span></label>
                                                            <a href="{{ $answer }}"
                                                               data-fancybox="question2"
                                                               title="{{__('default.pages.courses.zoom_certificate')}}"><img
                                                                        src="{{ $answer }}" alt=""></a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach

                                </div>
                                <div class="buttons">
                                    <button class="btn" disabled>{{__('default.pages.lessons.done_test_btn')}}</button>
                                    <a href="/{{$lang}}/my-courses/course/{{$item->id}}/view-lesson-{{$lesson->id}}"
                                       title="{{__('default.pages.courses.cancel_title')}}"
                                       class="ghost-btn">{{__('default.pages.courses.cancel_title')}}</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-4">
                        @include('app.pages.author.courses.components.lesson.media_attachments',['item' => $item])
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

