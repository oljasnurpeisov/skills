@extends('admin.v2.layout.course.template')

@section('content')
    <div class="container">
        <div><a href="javascript:history.back();" title="{{__('admin.pages.courses.back_title')}}"
                class="link">{{__('admin.pages.courses.back_title')}}</a></div>
        <br/>
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
                        <a href="/{{$lang}}/admin/moderator-course-iframe-{{ $item->id }}"
                           title="{{__('default.pages.lessons.to_lessons_list')}}"
                           class="btn">{{__('default.pages.lessons.to_lessons_list')}}</a>
                        <a href="{{ url()->previous() }}" title="{{__('default.pages.lessons.test_try_again')}}"
                           class="ghost-btn">{{__('default.pages.lessons.test_try_again')}}</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                @include('admin.v2.pages.courses.lesson_preview.components.media_attachments',['item' => $item])
            </div>
        </div>
    </div>

    <div id="result" style="display:none;">
        <h4 class="title-primary text-center">{{__('default.pages.lessons.result_title')}}</h4>
        @if($right_answers >= json_decode($lesson->practice)->passingScore)
            <div class="plain-text gray text-center green">{{$right_answers}}
                /{{json_decode($lesson->practice)->passingScore}}. {{__('default.pages.lessons.test_success_passed')}}
            </div>
        @else
            <div class="plain-text gray text-center red">{{$right_answers}}
                /{{json_decode($lesson->practice)->passingScore}}. {{__('default.pages.lessons.test_failed_passed')}}
            </div>

        @endif
        <div class="text-center">
            <a href="#" title="Ок" class="btn" data-fancybox-close>Ок</a>
        </div>
    </div>
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

