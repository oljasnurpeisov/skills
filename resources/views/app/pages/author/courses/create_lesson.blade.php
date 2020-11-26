@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="plain">
            <div class="container">
                <ul class="breadcrumbs">
                    <li><a href="/{{$lang}}/my-courses"
                           title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
                    </li>
                    <li><a href="/{{$lang}}/my-courses/course/{{$item->id}}" title="{{$item->name}}">{{$item->name}}</a></li>
                    <li><span>{{__('default.pages.lessons.lesson_create')}}</span></li>
                </ul>
                <h1 class="title-primary">{{__('default.pages.lessons.lesson_create')}}</h1>

                <div class="row row--multiline">
                    <div class="col-md-8">
                        <form action="/{{$lang}}/create-lesson/{{$item->id}}/{{$theme->id}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row row--multiline">
                                <div class="col-md-8">
                                    <div class="form-group" id="nameField">
                                        <label class="form-group__label">{{__('default.pages.lessons.lesson_name')}} *</label>
                                        <input type="text" name="name" placeholder="" class="input-regular" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-group__label">{{__('default.pages.lessons.lesson_type')}} *</label>
                                        <select name="type" class="selectize-regular" id="lessonSelect">
                                            <option value="theory">{{__('default.pages.lessons.theory_title')}}</option>
                                            <option value="practice">{{__('default.pages.lessons.theory_with_practic_title')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="practiceTypes" style="display: none;">
                                        <label class="form-group__label">{{__('default.pages.lessons.headline')}}</label>
                                        <label class="radio"><input type="radio" name="end_lesson_type"
                                                                    value="test" checked><span>{{__('default.pages.lessons.test_title')}}</span></label>
                                        <label class="radio"><input type="radio" name="end_lesson_type"
                                                                    value="homework"><span>{{__('default.pages.lessons.homework')}}</span></label>
                                    </div>
                                    <div class="form-group" id="durationField">
                                        <label class="form-group__label">{{__('default.pages.lessons.duration_title')}} *</label>
                                        <input type="number" name="duration" placeholder="" class="input-regular" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-group__label">{{__('default.pages.lessons.lesson_image')}}</label>
                                        <div class="avatar lesson-image dropzone-avatar" id="lessonCover"
                                             data-url="/ajax_upload_lesson_image?_token={{ csrf_token() }}" data-maxsize="1"
                                             data-acceptedfiles="image/*">
                                            <input type="hidden" name="image" class="avatar-path">
                                            <div class="lesson-image__preview">
                                                <img src="/assets/img/lesson-thumbnail.jpg"
                                                     class="avatar-preview" alt="">
                                            </div>
                                            <div class="lesson-image__desc dropzone-default">
                                                <div class="previews-container"></div>
                                                <div class="dropzone-default__info">PNG, JPG • {{__('default.pages.courses.max_file_title')}} 1MB</div>
                                                <div class="lesson-image__link avatar-pick dropzone-default__link">Выбрать
                                                    фото
                                                </div>
                                            </div>
                                            <div class="avatar-preview-template" style="display:none;">
                                                <div class="dz-preview dz-file-preview">
                                                    <div class="dz-details">
                                                        <div class="dz-filename"><span data-dz-name></span></div>
                                                        <div class="dz-size" data-dz-size></div>
                                                        <div class="dz-progress"><span class="dz-upload"
                                                                                       data-dz-uploadprogress></span></div>
                                                    </div>
                                                    <div class="alert alert-danger"><span data-dz-errormessage> </span></div>
                                                    <a href="javascript:undefined;" title="{{__('default.pages.courses.delete')}}"
                                                       class="author-picture__link red"
                                                       data-dz-remove>{{__('default.pages.courses.delete')}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.lessons.theory_title')}} *</label>
                                <textarea name="theory" class="input-regular tinymce-here" required></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.lessons.lesson_video_link')}}</label>
                                <input type="url" name="videos_link[]" placeholder="" class="input-regular" id="courseVideo">
                            </div>
                            <div class="text-right pull-up">
                                <a href="#" title="{{__('default.pages.profile.add_btn_title')}}" class="add-btn" data-duplicate="courseVideo" data-maxcount="4"><span
                                            class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span class="btn-icon small icon-plus"> </span></a>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.video_local')}}</label>
                                <div data-url="/ajax_upload_lesson_videos?_token={{ csrf_token() }}" data-maxfiles="5"
                                     data-maxsize="50" data-acceptedfiles=".mp4" id="video"
                                     class="dropzone-default dropzone-multiple">
                                    <input type="hidden" name="videos" value="">
                                    <div class="dropzone-default__info">MP4 • {{__('default.pages.courses.max_file_title')}} 50MB</div>
                                    <a href="javascript:;" title="{{__('default.pages.courses.add_file_btn_title')}}" class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                    <div class="previews-container"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.lessons.lesson_audio')}}</label>
                                <div data-url="/ajax_upload_lesson_audios?_token={{ csrf_token() }}" data-maxfiles="5"
                                     data-maxsize="10" data-acceptedfiles=".mp3" id="audio"
                                     class="dropzone-default dropzone-multiple">
                                    <input type="hidden" name="audios" value="">
                                    <div class="dropzone-default__info">MP3 • {{__('default.pages.courses.max_file_title')}} 10MB</div>
                                    <a href="javascript:;" title="{{__('default.pages.courses.add_file_btn_title')}}" class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}л</a>
                                    <div class="previews-container"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.lessons.another_lesson_attachments')}}</label>
                                <div data-url="/ajax_upload_lesson_another_files?_token={{ csrf_token() }}" data-maxfiles="20"
                                     data-maxsize="20"
                                     data-acceptedfiles=".pdf, .doc, .xls, .ppt, .docx, .xlsx, .pptx, .png, .jpg, .rar, .zip, .7z, .mp3, .mp4, .avi, .mov"
                                     id="documents-dropzone"
                                     class="dropzone-default dropzone-multiple">
                                    <input type="hidden" name="another_files" value="">
                                    <div class="dropzone-default__info">PDF, DOC, XLS, PPT, DOCX, XLSX, PPTX, PNG, JPG, RAR,
                                        ZIP, 7z, MP3, MP4, AVI, MOV • макс. 20 MB
                                    </div>
                                    <a href="javascript:;" title="{{__('default.pages.courses.max_file_title')}}" class="dropzone-default__link">{{__('default.pages.courses.max_file_title')}}</a>
                                    <div class="previews-container"></div>
                                </div>
                            </div>
                            @if($item->is_poor_vision == true)
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.lessons.lesson_video_link_1')}}</label>
                                    <input type="url" name="videos_poor_vision_link[]" placeholder=""
                                           class="input-regular"
                                           id="courseVideo1">
                                </div>
                                <div class="text-right pull-up">
                                    <a href="#" title="{{__('default.pages.profile.add_btn_title')}}" class="add-btn"
                                       data-duplicate="courseVideo1" data-maxcount="4"><span
                                                class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span
                                                class="btn-icon small icon-plus"> </span></a>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.lessons.video_local_1')}}</label>
                                    <div data-url="/ajax_upload_lesson_videos?_token={{ csrf_token() }}"
                                         data-maxfiles="5"
                                         data-maxsize="50" data-acceptedfiles=".mp4" id="video2"
                                         class="dropzone-default dropzone-multiple">
                                        <input type="hidden" name="videos_poor_vision" value="">
                                        <div class="dropzone-default__info">MP4
                                            • {{__('default.pages.courses.max_file_title')}} 50MB
                                        </div>
                                        <a href="javascript:;"
                                           title="{{__('default.pages.courses.add_file_btn_title')}}"
                                           class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                        <div class="previews-container"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.lessons.lesson_audio_1')}}</label>
                                    <div data-url="/ajax_upload_lesson_audios?_token={{ csrf_token() }}"
                                         data-maxfiles="5"
                                         data-maxsize="10" data-acceptedfiles=".mp3" id="audio2"
                                         class="dropzone-default dropzone-multiple">
                                        <input type="hidden" name="audios_poor_vision" value="">
                                        <div class="dropzone-default__info">MP3
                                            • {{__('default.pages.courses.max_file_title')}} 10MB
                                        </div>
                                        <a href="javascript:;"
                                           title="{{__('default.pages.courses.add_file_btn_title')}}"
                                           class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                        <div class="previews-container"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.lessons.another_lesson_attachments_1')}}</label>
                                    <div data-url="/ajax_upload_lesson_another_files?_token={{ csrf_token() }}"
                                         data-maxfiles="20"
                                         data-maxsize="20"
                                         data-acceptedfiles=".pdf, .doc, .xls, .ppt, .docx, .xlsx, .pptx, .png, .jpg, .rar, .zip, .7z, .mp3, .mp4, .avi, .mov"
                                         id="documents-dropzone2"
                                         class="dropzone-default dropzone-multiple">
                                        <input type="hidden" name="another_files_poor_vision" value="">
                                        <div class="dropzone-default__info">PDF, DOC, XLS, PPT, DOCX, XLSX, PPTX, PNG,
                                            JPG, RAR,
                                            ZIP, 7z, MP3, MP4, AVI, MOV • {{__('default.pages.courses.max_file_title')}}
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
                                        <label class="form-group__label">Домашнее задание *</label>
                                        <textarea name="homework" class="input-regular tinymce-here" required></textarea>
                                    </div>
                                </div>
                                <div id="test" style="display: none;">
                                    <div class="test-constructor">
                                        <div class="title-secondary">Тест</div>
                                        <div class="questions" id="questions">
                                            <div class="question form-group">
                                                <label class="form-group__label">Вопрос</label>
                                                <div class="input-addon">
                                                    <div>
                                                        <div class="form-group">
                                            <textarea name="questions[1]" class="input-regular tinymce-here question-text"
                                                      placeholder="Текст вопроса" required></textarea>
                                                        </div>
                                                        <div class="answers-bar">
                                                            <span>Ответы</span>
                                                            <label class="checkbox"><input type="checkbox" name="isPictures"
                                                                                           value="true"><span>В виде картинок</span></label>
                                                        </div>
                                                        <div class="answers-wrapper">
                                                            <div class="answers">
                                                                <div class="form-group green">
                                                                    <div class="input-addon">
                                                                        <input type="text" name="answers[1][]"
                                                                               placeholder="Введите правильный вариант ответа"
                                                                               class="input-regular small" required>
                                                                        <div class="addon small">
                                                                            <span class="required">*</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="input-addon">
                                                                        <input type="text" name="answers[1][]"
                                                                               placeholder="Введите вариант ответа"
                                                                               class="input-regular small" required>
                                                                        <div class="addon small">
                                                                            <span class="required">*</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="text-right">
                                                                <div title="Добавить" class="add-btn"><span
                                                                            class="add-btn__title">Добавить</span><span
                                                                            class="btn-icon extra-small icon-plus"> </span></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="addon addon-btn">
                                                        <span class="required">*</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="#" title="Добавить вопрос" class="btn small" id="addQuestion">Добавить вопрос</a>
                                        <div class="test-constructor__info">
                                            <div class="row row--multiline">
                                                <div class="col-auto">
                                                    <div class="text">Всего вопросов: <span id="questionsCount">1</span></div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="passing-score">
                                                        <span class="text">Проходной балл</span>
                                                        <input id="passingScore" type="text" name="passingScore" class="input-regular small" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <label class="checkbox small"><input type="checkbox" name="mixAnswers"
                                                                                         value="true" checked><span>Перемешать ответы</span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons">
                                <button type="submit" class="btn">{{__('default.pages.lessons.create')}}</button>
                                <a href="/{{$lang}}/my-courses/course/{{$item->id}}" title="{{__('default.pages.lessons.cancel')}}"
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
                                       placeholder="Введите вариант ответа"
                                       class="input-regular small" required>
                                <div class="addon small">
                                    <span class="required">*</span>
                                </div>
                            </div>
                        </div>`;
        let picAnswerTpl = `<div class="form-group">
                        <div class="input-addon">
                            <div data-url="https://dev3.panama.kz/ajaxUploadFilesTest" data-maxfiles="1"
                                 data-maxsize="1" data-acceptedfiles="image/*"
                                 class="dropzone-default dropzone-multiple">
                                <input type="text" name="$answersName" value="" required>
                                <div class="dropzone-default__info">JPG, PNG • макс. 1MB</div>
                                <a href="javascript:;" title="Загрузить файлы" class="dropzone-default__link">Добавить
                                    файлы</a>
                                <div class="previews-container"></div>
                            </div>
                            <div class="addon small">
                                <span class="required">*</span>
                            </div>
                        </div>
                    </div>`;
        let questionTpl = `<div class="question form-group">
                            <label class="form-group__label">Вопрос</label>
                            <div class="input-addon">
                                <div>
                                    <div class="form-group">
                                        <textarea name="$questionName" class="input-regular tinymce-here question-text"
                                                  placeholder="Текст вопроса" required></textarea>
                                    </div>
                                    <div class="answers-bar">
                                        <span>Ответы</span>
                                        <label class="checkbox"><input type="checkbox" name="isPictures"
                                                                       value="true"><span>В виде картинок</span></label>
                                    </div>
                                    <div class="answers">

                                    </div>
                                    <div class="text-right">
                                        <div title="Добавить" class="add-btn"><span
                                                class="add-btn__title">Добавить</span><span
                                                class="btn-icon extra-small icon-plus"> </span></div>
                                    </div>
                                </div>
                                <div class="addon addon-btn">

                                </div>
                            </div>
                        </div>`;

        let newTextConstructor = new TestConstructor({textAnswerTpl, picAnswerTpl, questionTpl});
    </script>
    <!---->
@endsection

