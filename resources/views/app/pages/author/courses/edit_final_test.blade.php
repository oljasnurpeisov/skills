@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="plain">
            <div class="container">
                <ul class="breadcrumbs">
                    <li><a href="/{{$lang}}/my-courses/"
                           title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
                    </li>
                    @include('app.pages.author.courses.components.breadcrumb_course_type',['item' => $item])
                    <li><a href="/{{$lang}}/my-courses/course/{{$course->id}}"
                           title="{{$course->name}}">{{$course->name}}</a>
                    <li><span>{{__('default.pages.courses.edit_final_test_title')}}</span></li>
                </ul>
                <h1 class="title-primary">{{__('default.pages.courses.edit_final_test_title')}}</h1>

                <div class="row row--multiline">
                    <div class="col-md-8">
                        <form action="/{{$lang}}/my-courses/course/{{$course->id}}/edit-final-test" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row row--multiline">
                                <div class="col-md-12">
                                    <div class="form-group" id="durationField">
                                        <label class="form-group__label">{{__('default.pages.lessons.duration_title')}}
                                            *</label>
                                        <input type="number" name="duration" placeholder="" class="input-regular"
                                               value="{{ old('duration') ?? $item->duration}}"
                                               required>
                                    </div>
                                    {!! $errors->first('duration', '<div class="alert alert-danger">
                    :message
                </div>') !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.lessons.theory_title')}}</label>
                                <textarea name="theory" class="input-regular tinymce-text-here">
                            {{ old('theory') ?? $item->theory}}
                        </textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.video_link')}}</label>
                                @if($item->lesson_attachment->videos_link != null)
                                    <input type="url" name="videos_link[]" placeholder=""
                                           class="input-regular"
                                           value="{{json_decode($item->lesson_attachment->videos_link)[0]}}"
                                           id="courseVideo">
                                @else
                                    <input type="url" name="videos_link[]" placeholder=""
                                           class="input-regular"
                                           value="" id="courseVideo">
                                @endif
                            </div>
                            <div class="removable-items">
                                @if($item->lesson_attachment->videos_link != null)
                                    @foreach(array_slice(json_decode($item->lesson_attachment->videos_link),1) as $video_link)
                                        <div class="form-group">
                                            <div class="input-addon">
                                                <input type="url" name="videos_link[]" placeholder=""
                                                       class="input-regular"
                                                       value="{{$video_link}}">
                                                <div class="addon">
                                                    <div class="btn-icon small icon-close"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="text-right pull-up">
                                <a href="#" title="{{__('default.pages.profile.add_btn_title')}}" class="add-btn"
                                   data-duplicate="courseVideo" data-maxcount="4"><span
                                            class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span
                                            class="btn-icon small icon-plus"> </span></a>
                            </div>
{{--                            <div class="form-group">--}}
{{--                                <label class="form-group__label">{{__('default.pages.courses.video_local')}}</label>--}}
{{--                                <div data-url="/ajax_upload_lesson_videos?_token={{ csrf_token() }}"--}}
{{--                                     data-maxfiles="5"--}}
{{--                                     data-maxsize="500" data-acceptedfiles=".mp4" id="video2"--}}
{{--                                     class="dropzone-default dropzone-multiple">--}}
{{--                                    <input type="hidden" name="localVideo" value="">--}}
{{--                                    <div class="dropzone-default__info">MP4--}}
{{--                                        • {{__('default.pages.courses.max_file_title')}} 500MB--}}
{{--                                    </div>--}}
{{--                                    <div class="previews-container">--}}
{{--                                        @if($item->lesson_attachment->videos != null)--}}
{{--                                            @foreach(json_decode($item->lesson_attachment->videos) as $video)--}}
{{--                                                <div class="dz-preview dz-image-preview dz-stored">--}}
{{--                                                    <div class="dz-details">--}}
{{--                                                        <input type="text" name="localVideoStored[]"--}}
{{--                                                               value="{{$video}}" placeholder="">--}}
{{--                                                        <div class="dz-filename"><span--}}
{{--                                                                    data-dz-name="">{{substr(basename($video), 14)}}</span>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <a href="javascript:undefined;"--}}
{{--                                                       title="{{__('default.pages.courses.delete')}}"--}}
{{--                                                       class="link red">{{__('default.pages.courses.delete')}}</a>--}}
{{--                                                    <a href="javascript:undefined;"--}}
{{--                                                       title="{{__('default.pages.courses.reestablish')}}"--}}
{{--                                                       class="link green"--}}
{{--                                                       style="display:none;">{{__('default.pages.courses.reestablish')}}</a>--}}
{{--                                                </div>--}}
{{--                                            @endforeach--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <a href="javascript:;"--}}
{{--                                       title="{{__('default.pages.courses.add_file_btn_title')}}"--}}
{{--                                       class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_audio')}}</label>
                                <div data-url="/ajax_upload_course_audios?_token={{ csrf_token() }}" data-maxfiles="5"
                                     data-maxsize="10" data-acceptedfiles=".mp3" id="audio"
                                     class="dropzone-default dropzone-multiple">
                                    <input type="text" name="localAudio" value="">
                                    <div class="dropzone-default__info">MP3
                                        • {{__('default.pages.courses.max_file_title')}} 10MB
                                    </div>
                                    <div class="previews-container">
                                        @if($item->lesson_attachment->audios != null)
                                            @foreach(json_decode($item->lesson_attachment->audios) as $audio)
                                                <div class="dz-preview dz-image-preview dz-stored">
                                                    <div class="dz-details">
                                                        <input type="text" name="localAudioStored[]"
                                                               value="{{$audio}}"
                                                               placeholder="">
                                                        <div class="dz-filename"><span
                                                                    data-dz-name="">{{substr(basename($audio), 14)}}</span>
                                                        </div>
                                                    </div>
                                                    <a href="javascript:undefined;"
                                                       title="{{__('default.pages.courses.delete')}}"
                                                       class="link red">{{__('default.pages.courses.delete')}}</a>
                                                    <a href="javascript:undefined;"
                                                       title="{{__('default.pages.courses.reestablish')}}"
                                                       class="link green"
                                                       style="display:none;">{{__('default.pages.courses.reestablish')}}</a>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <a href="javascript:;" title="{{__('default.pages.courses.add_file_btn_title')}}"
                                       class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.lessons.another_lesson_attachments')}}</label>
                                <div data-url="/ajax_upload_lesson_another_files?_token={{ csrf_token() }}"
                                     data-maxfiles="20"
                                     data-maxsize="20"
                                     data-acceptedfiles=".pdf, .doc, .xls, .ppt, .docx, .xlsx, .pptx, .png, .jpg"
                                     id="documents-dropzone"
                                     class="dropzone-default dropzone-multiple">
                                    <input type="text" name="localDocuments" value="">
                                    <div class="dropzone-default__info">PDF, DOC, XLS, PPT, DOCX, XLSX, PPTX, PNG, JPG • {{__('default.pages.courses.max_file_title')}} 20
                                        MB
                                    </div>
                                    <div class="previews-container">
                                        @if($item->lesson_attachment->another_files != null)
                                            @foreach(json_decode($item->lesson_attachment->another_files) as $file)
                                                <div class="dz-preview dz-image-preview dz-stored">
                                                    <div class="dz-details">
                                                        <input type="text" name="localDocumentsStored[]"
                                                               value="{{$file}}"
                                                               placeholder="">
                                                        <div class="dz-filename"><span
                                                                    data-dz-name="">{{substr(basename($file), 14)}}</span>
                                                        </div>
                                                    </div>
                                                    <a href="javascript:undefined;"
                                                       title="{{__('default.pages.courses.delete')}}"
                                                       class="link red">{{__('default.pages.courses.delete')}}</a>
                                                    <a href="javascript:undefined;"
                                                       title="{{__('default.pages.courses.reestablish')}}"
                                                       class="link green"
                                                       style="display:none;">{{__('default.pages.courses.reestablish')}}</a>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <a href="javascript:;" title="{{__('default.pages.courses.add_file_btn_title')}}"
                                       class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                </div>
                            </div>
                            @include('app.pages.author.courses.components.lesson.poor_vision_lesson_edit',['item' => $item])
                            @include('app.pages.author.courses.components.lesson.poor_hearing_lesson_edit',['item' => $item])
                            @include('app.pages.author.courses.components.lesson.test_lesson_edit',['item' => $item])
                            <div class="buttons">
                                <button type="submit" class="btn">{{__('default.pages.courses.save_title')}}</button>
                                <a href="/{{$lang}}/my-courses/course/{{$course->id}}"
                                   title="{{__('default.pages.courses.cancel_title')}}"
                                   class="ghost-btn">{{__('default.pages.courses.cancel_title')}}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script src="/assets/js/lesson-create.js"></script>
    <script src="/assets/js/test-constructor.js"></script>
    <script>
        let textAnswerTpl = `<div class="form-group">
                            <div class="input-addon">
                                <input type="text" name="$answersName"
                                       placeholder="{{__('default.pages.lessons.input_answer_title')}}"
                                       class="input-regular small" required>
                                <div class="addon small">
                                    <span class="required">*</span>
                                </div>
                            </div>
                        </div>`;
        let picAnswerTpl = `<div class="form-group">
                        <div class="input-addon">
                            <div data-url="/ajax_upload_test_images?_token={{ csrf_token() }}" data-maxfiles="1"
                                 data-maxsize="1" data-acceptedfiles="image/*"
                                 class="dropzone-default dropzone-multiple">
                                <input type="text" name="$answersName" value="" required>
                                <div class="dropzone-default__info">JPG, PNG • {{__('default.pages.courses.max_file_title')}} 1MB</div>
                                <a href="javascript:;" title="{{__('default.pages.courses.add_file_btn_title')}}" class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                <div class="previews-container"></div>
                            </div>
                            <div class="addon small">
                                <span class="required">*</span>
                            </div>
                        </div>
                    </div>`;
        let questionTpl = `<label class="form-group__label">{{__('default.pages.lessons.question_title')}}</label>
                            <div class="input-addon">
                                <div>
                                    <div class="form-group">
                                        <textarea name="$questionName" class="input-regular tinymce-here question-text"
                                                  placeholder="{{__('default.pages.lessons.question_text')}}" required></textarea>
                                    </div>
                                    <div class="answers-bar">
                                        <span>{{__('default.pages.lessons.answers_title')}}</span>
                                        <label class="checkbox"><input type="checkbox" name="isPictures[$isPicturesIndex]"
                                                                       value="true"><span>{{__('default.pages.lessons.pictures_type_title')}}</span></label>
                                    </div>
                                    <div class="answers">

                                    </div>
                                    <div class="text-right">
                                        <div title="{{__('default.pages.profile.add_btn_title')}}" class="add-btn"><span
                                                class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span
                                                class="btn-icon extra-small icon-plus"> </span></div>
                                    </div>
                                </div>
                                <div class="addon addon-btn">

                                </div>
                            </div>`;

        let newTextConstructor = new TestConstructor({textAnswerTpl, picAnswerTpl, questionTpl});
    </script>
    <!---->
@endsection

