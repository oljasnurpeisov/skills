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
                                <h1 class="page-title">{{__('default.pages.lessons.coursework_title')}}</h1>
                            @else
                                <h1 class="page-title">{{__('default.pages.lessons.homework_title')}}</h1>
                            @endif
                            <div class="plain-text">
                                {!! $lesson->practice !!}
                            </div>
                            <hr>
                            <h2 class="title-secondary">{{__('default.pages.lessons.answer_title')}}</h2>
                            <form action="/{{$lang}}/course-{{$item->id}}/lesson-{{$lesson->id}}/author-homework-submit"
                                  method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.lessons.answer_text_title')}}
                                        *</label>
                                    <textarea name="answer" class="input-regular" required disabled></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.lessons.video_files')}}</label>
                                    <div data-url="/"
                                         data-maxfiles="5"
                                         data-maxsize="500" data-acceptedfiles=".mp4" id="video"
                                         class="dropzone-default dropzone-multiple">
                                        <input type="hidden" name="videos" value="">
                                        <div class="dropzone-default__info">MP4
                                            • {{__('default.pages.courses.max_file_title')}} 500MB
                                        </div>
                                        <div class="previews-container"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.lessons.audio_files')}}</label>
                                    <div data-url="/"
                                         data-maxfiles="5"
                                         data-maxsize="10" data-acceptedfiles=".mp3" id="audio"
                                         class="dropzone-default dropzone-multiple">
                                        <input type="hidden" name="audios" value="">
                                        <div class="dropzone-default__info">MP3
                                            • {{__('default.pages.courses.max_file_title')}} 10MB
                                        </div>
                                        <div class="previews-container"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.lessons.another_attachments')}}</label>
                                    <div data-url="/"
                                         data-maxfiles="20"
                                         data-maxsize="20"
                                         data-acceptedfiles=".pdf, .doc, .xls, .ppt, .docx, .xlsx, .pptx, .png, .jpg"
                                         id="documents-dropzone"
                                         class="dropzone-default dropzone-multiple">
                                        <input type="hidden" name="another_files" value="">
                                        <div class="dropzone-default__info">PDF, DOC, XLS, PPT, DOCX, XLSX, PPTX, PNG,
                                            JPG • {{__('default.pages.courses.max_file_title')}}
                                            20 MB
                                        </div>
                                        <div class="previews-container"></div>
                                    </div>
                                </div>
                                <div class="buttons">
                                    <button class="btn" disabled>{{__('default.pages.lessons.send_answer_title')}}</button>
                                    <a href="{{ url()->previous() }}"
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

