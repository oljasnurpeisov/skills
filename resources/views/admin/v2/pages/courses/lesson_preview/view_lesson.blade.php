@extends('admin.v2.layout.course.template')

@section('content')
    <div class="container">
        <div><a href="javascript:history.back();" title="{{__('admin.pages.courses.back_title')}}"
                class="link">{{__('admin.pages.courses.back_title')}}</a></div>
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
                <div class="course" id="courseDataContainer">
                    @foreach($course_data_items as $course_item)
                        @if($course_item->item_type == 'theme')
                            <div class="topic spoiler">
                                <div class="topic__header">
                                    <div class="title">{{$course_item->name}}</div>
                                    <div class="duration">{{\App\Extensions\FormatDate::convertMunitesToTime($item->lessons->where('theme_id', '=', $course_item->id)->sum('duration'))}}</div>
                                </div>
                                <div class="topic__body">
                                    @foreach($course_item->lessons->sortBy('index_number') as $lesson)
                                        <div class="lesson">
                                            @if($lesson->type != 1)
                                                <div class="title"><a
                                                        href="/{{$lang}}/admin/moderator-course-iframe-{{$item->id}}/lesson-{{$lesson->id}}"
                                                        title="{{$lesson->name}}">{{$lesson->name}}
                                                        <div class="type">{{$lesson->lesson_type->getAttribute('name_'.$lang) ?? $lesson->lesson_type->getAttribute('name_ru')}}
                                                            {{$lesson->end_lesson_type == 0 ? ' ('.__('default.pages.lessons.test_title').')' : ' ('.__('default.pages.lessons.homework_title').')'}}</div>
                                                    </a></div>
                                                <div class="duration">{{\App\Extensions\FormatDate::convertMunitesToTime($lesson->duration)}}</div>
                                            @else
                                                <div class="title"><a
                                                        href="/{{$lang}}/admin/moderator-course-iframe-{{$item->id}}/lesson-{{$lesson->id}}"
                                                        title="{{$lesson->name}}">{{$lesson->name}}
                                                        <div class="type">{{$lesson->lesson_type->getAttribute('name_'.$lang) ?? $lesson->lesson_type->getAttribute('name_ru')}}</div>
                                                    </a></div>
                                                <div class="duration">{{\App\Extensions\FormatDate::convertMunitesToTime($lesson->duration)}}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="topic__body">
                                <div class="lesson">
                                    @if($course_item->type != 1)
                                        {{ dd($course_item) }}
                                        <div class="title"><a
                                                href="/{{$lang}}/admin/moderator-course-iframe-{{$item->id}}/lesson-{{$course_item->id}}"
                                                title="{{$course_item->name}}">{{$course_item->name}}

                                            </a>
                                        </div>
                                    @else
                                        <div class="title"><a
                                                href="/{{$lang}}/admin/moderator-course-iframe-{{$item->id}}/lesson-{{$course_item->id}}"
                                                title="{{$course_item->name}}">{{$course_item->name}}
                                                <div class="type">{{$course_item->lesson_type->getAttribute('name_'.$lang) ?? $course_item->lesson_type->getAttribute('name_ru')}}</div>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                @if($item->finalTest() !== null)
                    <div class="topic">
                        <div class="topic__header">
                            <div class="title"><a
                                    href="/{{$lang}}/admin/moderator-course-iframe-{{$item->id}}/lesson-{{$item->finalTest()->id}}">{{__('default.pages.courses.final_test_title')}}</a>
                            </div>
                            <div class="duration"></div>

                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script src="/assets/js/visually-impaired-tools.js"></script>
    <!---->
@endsection

