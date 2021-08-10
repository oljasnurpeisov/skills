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
                    <li>
                        <span>{{$lesson->lesson_type->getAttribute('name_'.$lang) ?? $lesson->lesson_type->getAttribute('name_ru')}}</span>
                    </li>
                </ul>

                @include('.app.pages.author.courses.components.preview_alert_info')

                <div class="row row--multiline">
                    <div class="col-md-8">
                        <div class="article">
                            <h1 class="page-title">{{__('default.pages.lessons.test_title')}}</h1>
                            <div class="test">
                                @foreach(json_decode($lesson->practice)->questions as $key => $question)
                                    @if(!array_key_exists($key, $results))
                                        <div class="item">
                                            <div class="question green">{!! $question->name !!}
                                            </div>
                                        </div>
                                    @else
                                        <div class="item">
                                            <div class="question red">{!! $question->name !!}
                                            </div>
                                        </div>
                                    @endif

                                @endforeach

                            </div>
                            <div class="buttons">
                                <a href="/{{$lang}}/my-courses/course/{{$item->id}}"
                                   title="{{__('default.pages.lessons.to_lessons_list')}}"
                                   class="btn">{{__('default.pages.lessons.to_lessons_list')}}</a>
                                <a href="{{ url()->previous() }}" title="{{__('default.pages.lessons.test_try_again')}}"
                                   class="ghost-btn">{{__('default.pages.lessons.test_try_again')}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        @include('app.pages.author.courses.components.lesson.media_attachments',['item' => $item])
                    </div>
                </div>
            </div>
        </section>

        <div id="result" style="display:none;">
            <h4 class="title-primary text-center">{{__('default.pages.lessons.result_title')}}</h4>
            @if($right_answers >= json_decode($lesson->practice)->passingScore)
                <div class="plain-text gray text-center green">{{$right_answers}}
                    /{{json_decode($lesson->practice)->passingScore}}
                    . {{__('default.pages.lessons.test_success_passed')}}
                    . {{ __('default.pages.lessons.test_success_congratulation', ['course' => $lesson->course->name]) }}
                </div>
            @else
                <div class="plain-text gray text-center red">{{$right_answers}}
                    /{{json_decode($lesson->practice)->passingScore}}
                    . {{__('default.pages.lessons.test_failed_passed')}}
                </div>

            @endif
            <div class="text-center">
                <a href="#" title="Ок" class="btn" data-fancybox-close>Ок</a>
            </div>
        </div>

    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script src="/assets/js/visually-impaired-tools.js"></script>
    <script>
        $.fancybox.open({
            src: '#result',
            touch: false
        })
    </script>
    <!---->
@endsection

