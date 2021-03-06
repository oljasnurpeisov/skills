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
                    <li><a href="/{{$lang}}/my-courses/course/{{$item->id}}" title="{{$item->name}}">{{$item->name}}</a>
                    </li>
                    <li><span>{{__('default.pages.lessons.lesson_create')}}</span></li>
                </ul>
                <h1 class="title-primary">{{__('default.pages.lessons.lesson_create')}}</h1>

                <div class="row row--multiline">
                    <div class="col-md-8">
                        @if($theme)
                            <form action="/{{$lang}}/create-lesson/{{$item->id}}/{{$theme->id}}" method="POST"
                                  enctype="multipart/form-data">
                                @else
                                    <form action="/{{$lang}}/create-lesson/{{$item->id}}" method="POST"
                                          enctype="multipart/form-data">
                                        @endif

                                        @csrf
                                        <div class="row row--multiline">
                                            <div class="col-md-12">
                                                <div class="form-group" id="nameField">
                                                    <label class="form-group__label">{{__('default.pages.lessons.lesson_name')}}
                                                        *</label>
                                                    <input type="text" name="name" placeholder="" class="input-regular"
                                                           value="{{ old('name') }}" required>
                                                </div>
                                                {!! $errors->first('name', '<div class="alert alert-danger">
                                :message
                            </div>') !!}
                                                <div class="form-group">
                                                    <label class="form-group__label">{{__('default.pages.lessons.lesson_type')}}
                                                        *</label>
                                                    <select name="type" class="selectize-regular" id="lessonSelect">
                                                        <option value="theory">{{__('default.pages.lessons.theory_title')}}</option>
                                                        <option value="practice">{{__('default.pages.lessons.theory_with_practic_title')}}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group" id="practiceTypes" style="display: none;">
                                                    <label class="form-group__label">{{__('default.pages.lessons.headline')}}</label>
                                                    <label class="radio"><input type="radio" name="practiceType"
                                                                                value="test"
                                                                                checked><span>{{__('default.pages.lessons.test_title')}}</span></label>
                                                    <label class="radio"><input type="radio" name="practiceType"
                                                                                value="homework"><span>{{__('default.pages.lessons.homework')}}</span></label>
                                                </div>
                                                <div class="form-group" id="durationField">
                                                    <label class="form-group__label">{{__('default.pages.lessons.duration_title')}}
                                                        *</label>
                                                    <input type="number" name="duration" placeholder=""
                                                           class="input-regular"
                                                           value="{{ old('duration') }}" required>
                                                    <label class="form-group__label"
                                                           style="color: #828282;margin-top: 5px">{{__('default.pages.lessons.duration_teaser')}}
                                                    </label>
                                                </div>
                                                {!! $errors->first('duration', '<div class="alert alert-danger">
                                :message
                            </div>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-group__label">{{__('default.pages.lessons.theory_title')}}
                                                *</label>
                                            <textarea name="theory" class="input-regular tinymce-here"
                                                      required>{{ old('theory') }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-group__label">{{__('default.pages.lessons.lesson_video_link')}}</label>
                                            <input type="url" name="videos_link[]" placeholder="" class="input-regular"
                                                   id="courseVideo">
                                        </div>
                                        <div class="removable-items"></div>
                                        <div class="text-right pull-up">
                                            <a href="#" title="{{__('default.pages.profile.add_btn_title')}}"
                                               class="add-btn"
                                               data-duplicate="courseVideo" data-maxcount="4"><span
                                                        class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span
                                                        class="btn-icon small icon-plus"> </span></a>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-group__label">{{__('default.pages.lessons.lesson_audio')}}</label>
                                            <div data-url="/ajax_upload_lesson_audios?_token={{ csrf_token() }}"
                                                 data-maxfiles="5"
                                                 data-maxsize="10" data-acceptedfiles=".mp3" id="audio"
                                                 class="dropzone-default dropzone-multiple">
                                                <input type="hidden" name="audios" value="">
                                                <div class="dropzone-default__info">MP3
                                                    ??? {{__('default.pages.courses.max_file_title')}} 10MB
                                                </div>
                                                <a href="javascript:;"
                                                   title="{{__('default.pages.courses.add_file_btn_title')}}"
                                                   class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}
                                                    ??</a>
                                                <div class="previews-container"></div>
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
                                                <input type="hidden" name="another_files" value="">
                                                <div class="dropzone-default__info">PDF, DOC, XLS, PPT, DOCX, XLSX,
                                                    PPTX, PNG, JPG
                                                    ??? {{__('default.pages.courses.max_file_title')}} 20
                                                    MB
                                                </div>
                                                <a href="javascript:;"
                                                   title="{{__('default.pages.courses.add_file_btn_title')}}"
                                                   class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                                <div class="previews-container"></div>
                                            </div>
                                        </div>
                                        @if($item->is_poor_vision == true)
                                            <h3 class="title-tertiary">{{__('default.pages.courses.is_vision_version')}}</h3>
                                            <div class="form-group">
                                                <label class="form-group__label">{{__('default.pages.lessons.lesson_audio_1')}}*</label>
                                                <div data-url="/ajax_upload_lesson_audios?_token={{ csrf_token() }}"
                                                     data-maxfiles="5"
                                                     data-required="true"
                                                     data-maxsize="10" data-acceptedfiles=".mp3" id="audio1"
                                                     class="dropzone-default dropzone-multiple">
                                                    <input type="text" name="audios_poor_vision" value="">
                                                    <input name="req" type="text" class="req" required>
                                                    <div class="dropzone-default__info">MP3
                                                        ??? {{__('default.pages.courses.max_file_title')}} 10MB
                                                    </div>
                                                    <a href="javascript:;"
                                                       title="{{__('default.pages.courses.add_file_btn_title')}}"
                                                       class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                                    <div class="previews-container"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-group__label">{{__('default.pages.lessons.another_lesson_attachments_1')}}*</label>
                                                <div data-url="/ajax_upload_lesson_another_files?_token={{ csrf_token() }}"
                                                     data-maxfiles="20"
                                                     data-maxsize="20"
                                                     data-required="true"
                                                     data-acceptedfiles=".pdf, .doc, .xls, .ppt, .docx, .xlsx, .pptx, .png, .jpg"
                                                     id="documents-dropzone2"
                                                     class="dropzone-default dropzone-multiple">
                                                    <input type="text" name="another_files_poor_vision" value="" required>
                                                    <input name="req" type="text" class="req" required>
                                                    <div class="dropzone-default__info">PDF, DOC, XLS, PPT, DOCX, XLSX,
                                                        PPTX, PNG,
                                                        JPG
                                                        ??? {{__('default.pages.courses.max_file_title')}}
                                                        20 MB
                                                    </div>
                                                    <a href="javascript:;"
                                                       title="{{__('default.pages.courses.add_file_btn_title')}}"
                                                       class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                                    <div class="previews-container"></div>
                                                </div>
                                            </div>
                                        @endif
                                        @if($item->is_poor_hearing == true)
                                            <h3 class="title-tertiary">{{__('default.pages.courses.is_poor_hearing')}}</h3>
                                            <div class="form-group">
                                                <label class="form-group__label">{{__('default.pages.lessons.lesson_video_link_2')}}*</label>
                                                <input type="url" name="videos_poor_hearing_link[]" placeholder=""
                                                       class="input-regular"
                                                       id="courseVideo2" required>
                                            </div>
                                            <div class="removable-items"></div>
                                            <div class="text-right pull-up">
                                                <a href="#" title="{{__('default.pages.profile.add_btn_title')}}"
                                                   class="add-btn"
                                                   data-duplicate="courseVideo2" data-maxcount="4"><span
                                                            class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span
                                                            class="btn-icon small icon-plus"> </span></a>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-group__label">{{__('default.pages.lessons.another_lesson_attachments_2')}}</label>
                                                <div data-url="/ajax_upload_lesson_another_files?_token={{ csrf_token() }}"
                                                     data-maxfiles="20"
                                                     data-maxsize="20"
                                                     data-acceptedfiles=".pdf, .doc, .xls, .ppt, .docx, .xlsx, .pptx, .png, .jpg"
                                                     id="documents-dropzone2"
                                                     class="dropzone-default dropzone-multiple">
                                                    <input type="hidden" name="another_files_poor_hearing" value="">
                                                    <div class="dropzone-default__info">PDF, DOC, XLS, PPT, DOCX, XLSX,
                                                        PPTX, PNG,
                                                        JPG
                                                        ??? {{__('default.pages.courses.max_file_title')}}
                                                        20 MB
                                                    </div>
                                                    <a href="javascript:;"
                                                       title="{{__('default.pages.courses.add_file_btn_title')}}"
                                                       class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                                    <div class="previews-container"></div>
                                                </div>
                                            </div>
                                        @endif
                                        <div id="optionalFields">
                                            <div id="homework" style="display: none;">
                                                <div class="form-group">
                                                    <label class="form-group__label">{{__('default.pages.lessons.homework')}}
                                                        *</label>
                                                    <textarea name="homework" class="input-regular tinymce-here"
                                                              required></textarea>
                                                </div>
                                            </div>
                                            <div id="test" style="display: none;">
                                                <div class="test-constructor">
                                                    <div class="title-secondary">{{__('default.pages.lessons.test_title')}}</div>
                                                    <div class="questions" id="questions">
                                                        <div class="question form-group">
                                                            <label class="form-group__label">{{__('default.pages.lessons.question_title')}}</label>
                                                            <div class="input-addon">
                                                                <div>
                                                                    <div class="form-group">
                                            <textarea name="questions[1]"
                                                      class="input-regular tinymce-here question-text"
                                                      placeholder="{{__('default.pages.lessons.question_text')}}"
                                                      required></textarea>
                                                                    </div>
                                                                    <div class="answers-bar">
                                                                        <span>{{__('default.pages.lessons.answers_title')}}</span>
                                                                        <label class="checkbox"><input type="checkbox"
                                                                                                       name="isPictures[1]"
                                                                                                       value="true"><span>{{__('default.pages.lessons.pictures_type_title')}}</span></label>
                                                                    </div>
                                                                    <div class="answers-wrapper">
                                                                        <div class="answers">
                                                                            <div class="form-group green">
                                                                                <div class="input-addon">
                                                                                    <input type="text"
                                                                                           name="answers[1][]"
                                                                                           placeholder="{{__('default.pages.lessons.right_answer_title')}}"
                                                                                           class="input-regular small"
                                                                                           required>
                                                                                    <div class="addon small">
                                                                                        <span class="required">*</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <div class="input-addon">
                                                                                    <input type="text"
                                                                                           name="answers[1][]"
                                                                                           placeholder="{{__('default.pages.lessons.input_answer_title')}}"
                                                                                           class="input-regular small"
                                                                                           required>
                                                                                    <div class="addon small">
                                                                                        <span class="required">*</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="text-right">
                                                                            <div title="{{__('default.pages.profile.add_btn_title')}}"
                                                                                 class="add-btn"><span
                                                                                        class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span
                                                                                        class="btn-icon extra-small icon-plus"> </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="addon addon-btn">
                                                                    <span class="required">*</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <a href="#" title="{{__('default.pages.lessons.add_question')}}"
                                                       class="btn small"
                                                       id="addQuestion">{{__('default.pages.lessons.add_question')}}</a>
                                                    <div class="test-constructor__info">
                                                        <div class="row row--multiline">
                                                            <div class="col-auto">
                                                                <div class="text">{{__('default.pages.lessons.questions_count')}}
                                                                    :
                                                                    <span id="questionsCount">1</span></div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="passing-score">
                                                                    <span class="text">{{__('default.pages.lessons.passing_score')}}</span>
                                                                    <input id="passingScore" type="text"
                                                                           name="passingScore"
                                                                           class="input-regular small" placeholder="">
                                                                </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <label class="checkbox small"><input type="checkbox"
                                                                                                     name="mixAnswers"
                                                                                                     value="true"
                                                                                                     checked><span>{{__('default.pages.lessons.mix_answers')}}</span></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="buttons">
                                            <button type="submit"
                                                    class="btn">{{__('default.pages.lessons.create')}}</button>
                                            <a href="/{{$lang}}/my-courses/course/{{$item->id}}"
                                               title="{{__('default.pages.lessons.cancel')}}"
                                               class="ghost-btn">{{__('default.pages.lessons.cancel')}}</a>
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
                                <div class="dropzone-default__info">JPG, PNG ??? {{__('default.pages.courses.max_file_title')}} 1MB</div>
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

