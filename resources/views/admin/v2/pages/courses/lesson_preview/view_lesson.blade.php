@extends('admin.v2.layout.course.template')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-6"><a href="javascript:history.back();" title="{{__('admin.pages.courses.back_title')}}"
                    class="link">{{__('admin.pages.courses.back_title')}}</a></div>
            @if($nextLesson)
            <div class="text-right col-sm-6"><a href="/{{$lang}}/admin/moderator-course-iframe-{{$lesson->course_id}}/lesson-{{$nextLesson->id}}" title="{{__('admin.pages.courses.next_lesson_title')}}"
                    class="link">{{__('admin.pages.courses.next_lesson_title')}}</a></div>
            @endif
        </div>
        <br/>
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
                    @if($lesson->image !== null)
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
                @include('admin.v2.pages.courses.lesson_preview.components.media_attachments',['item' => $item, 'sidebar_btn' => true])
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script src="/assets/js/visually-impaired-tools.js"></script>
    <!---->
@endsection

