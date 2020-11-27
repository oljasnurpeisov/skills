@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="plain">
            <div class="container">
                <ul class="breadcrumbs">
                    <li><a href="/{{$lang}}/my-courses/" title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a></li>
                    <li><a href="/{{$lang}}/my-courses/course/{{$item->id}}" title="{{$item->name}}">{{$item->name}}</a></li>
                    <li><span>{{__('default.pages.courses.edit_course')}}</span></li>
                </ul>
                <h1 class="title-primary">{{__('default.pages.courses.edit_course')}}</h1>

                <div class="row row--multiline">
                    <div class="col-md-8">
                        <form action="">
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_name')}}</label>
                                <input type="text" name="courseName" placeholder="" value="{{$item->name}}" class="input-regular" required>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">Навыки (мин. 1 навык)</label>
                                <div class="input-addon">
                                    <select name="skills[]" placeholder="Выберите навык"
                                            data-method="getSkillsByData" id="skillsInputTpl" required>
                                        <option value="9311" selected="selected">{{$item->skills[0]}}</option>
                                    </select>
                                    <div class="addon">
                                        <span class="required">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="removable-items">
                                <div class="form-group">
                                    <div class="input-addon">
                                        <select name="skills[]" placeholder="Выберите навык"
                                                data-method="getSkillsByData">
                                            <option value="8497" selected="selected">Автоматизация  составления расклада лекал </option>
                                        </select>
                                        <div class="addon"><div class="btn-icon small icon-close"></div></div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right pull-up">
                                <a href="#" title="Добавить" class="add-btn" data-duplicate="skillsInputTpl"><span
                                            class="add-btn__title">Добавить</span><span class="btn-icon small icon-plus"> </span></a>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">Язык курса</label>
                                <select name="lang" placeholder="Выберите язык" class="selectize-regular" required>
                                    <option value="Русский">Русский</option>
                                    <option value="Казахский">Казахский</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="checkbox"><input type="checkbox" name="paid"
                                                               value="true" id="paidCheckbox" checked><span>Платный</span></label>
                                <label class="checkbox"><input type="checkbox" name="allAvailable"
                                                               value="true"><span>Все уроки доступны сразу</span></label>
                                <label class="checkbox"><input type="checkbox" name="poorVision"
                                                               value="true" data-toggle="poorVision" checked><span>Версия для слабовидящих</span></label>
                            </div>
                            <div class="form-group" id="paidFormgroup" style="display:block;">
                                <label class="form-group__label">Стоимость, тг *</label>
                                <input type="text" name="price" placeholder="" value="3000" class="input-regular" required>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">Чему научит курс (макс. 2000 символов) *</label>
                                <textarea name="aim" class="input-regular tinymce-text-here" required>
                            <p>Тестовый текст</p>
                        </textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">Краткое описание (макс. 200 символов) *</label>
                                <textarea name="annotation" class="input-regular tinymce-text-here" required>
                            <p>Тестовый текст</p>
                        </textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">Описание (макс. 2000 символов) *</label>
                                <textarea name="description" class="input-regular tinymce-text-here" required>
                            <p>Тестовый текст</p>
                        </textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">Картинка курса</label>
                                <div class="avatar course-image dropzone-avatar" id="courseCover"
                                     data-url="https://dev3.panama.kz/ajaxUploadImageTest" data-maxsize="1"
                                     data-acceptedfiles="image/*">
                                    <img src="/assets/img/courses/1.png" data-defaultsrc="/assets/img/course-thumbnail.jpg" class="course-image__preview avatar-preview" alt="">
                                    <div class="course-image__desc dropzone-default">
                                        <input type="text" name="avatarPath" class="avatar-path" value="/assets/img/courses/1.png" required="">
                                        <div class="previews-container">
                                            <div class="dz-preview dz-image-preview">
                                                <div class="dz-details">
                                                    <div class="dz-filename"><span data-dz-name="">1.png</span></div>
                                                    <div class="dz-size" data-dz-size=""><strong>24</strong> KB</div>
                                                </div>
                                                <a href="javascript:undefined;" title="Удалить" class="author-picture__link red" data-dz-remove="">Удалить</a>
                                            </div>
                                        </div>
                                        <div class="dropzone-default__info">PNG, JPG • макс. 1MB</div>
                                        <div class="course-image__link avatar-pick dropzone-default__link dz-clickable">Выбрать фото</div>
                                    </div>
                                    <div class="avatar-preview-template" style="display:none;">
                                        <div class="dz-preview dz-file-preview">
                                            <div class="dz-details">
                                                <div class="dz-filename"><span data-dz-name></span></div>
                                                <div class="dz-size" data-dz-size></div>
                                                <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                                            </div>
                                            <div class="alert alert-danger"><span data-dz-errormessage> </span></div>
                                            <a href="javascript:undefined;" title="Удалить" class="author-picture__link red"
                                               data-dz-remove>Удалить</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">Ссылка на видео курса</label>
                                <input type="url" name="video[]" placeholder="" class="input-regular" value="https://www.youtube.com/?gl=RU" id="courseVideo">
                            </div>
                            <div class="removable-items">
                                <div class="form-group">
                                    <div class="input-addon">
                                        <input type="url" name="video[]" placeholder="" class="input-regular" value="https://www.youtube.com/?gl=RU">
                                        <div class="addon"><div class="btn-icon small icon-close"></div></div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right pull-up">
                                <a href="#" title="Добавить" class="add-btn" data-duplicate="courseVideo" data-maxcount="4"><span
                                            class="add-btn__title">Добавить</span><span class="btn-icon small icon-plus"> </span></a>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">Видео файл с устройства</label>
                                <div data-url="https://dev3.panama.kz/ajaxUploadFilesTest" data-maxfiles="5"
                                     data-maxsize="50" data-acceptedfiles=".mp4" id="video"
                                     class="dropzone-default dropzone-multiple">
                                    <input type="text" name="localVideo" value="">
                                    <div class="dropzone-default__info">MP4 • макс. 50MB</div>
                                    <div class="previews-container">
                                        <div class="dz-preview dz-image-preview dz-stored">
                                            <div class="dz-details">
                                                <input type="text" name="localVideoStored[]" value="/location/example1.mp4" placeholder="">
                                                <div class="dz-filename"><span data-dz-name="">example1.mp4</span></div>
                                                <div class="dz-size" data-dz-size=""><strong>57.2</strong> KB</div>
                                            </div>
                                            <a href="javascript:undefined;" title="Удалить" class="link red">Удалить</a>
                                            <a href="javascript:undefined;" title="Восстановить" class="link green" style="display:none;">Восстановить</a>
                                        </div>
                                        <div class="dz-preview dz-image-preview dz-stored">
                                            <div class="dz-details">
                                                <input type="text" name="localVideoStored[]" value="/location/example2.mp4" placeholder="">
                                                <div class="dz-filename"><span data-dz-name="">example2.mp4</span></div>
                                                <div class="dz-size" data-dz-size=""><strong>44.6</strong> KB</div>
                                            </div>
                                            <a href="javascript:undefined;" title="Удалить" class="link red">Удалить</a>
                                            <a href="javascript:undefined;" title="Восстановить" class="link green" style="display:none;">Восстановить</a>
                                        </div>
                                    </div>
                                    <a href="javascript:;" title="Загрузить файлы" class="dropzone-default__link">Добавить файл</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">Аудио курса</label>
                                <div data-url="https://dev3.panama.kz/ajax_upload_course_audios" data-maxfiles="5"
                                     data-maxsize="10" data-acceptedfiles=".mp3" id="audio"
                                     class="dropzone-default dropzone-multiple">
                                    <input type="text" name="audio" value="">
                                    <div class="dropzone-default__info">MP3 • макс. 10MB</div>
                                    <div class="previews-container">
                                        <div class="dz-preview dz-image-preview dz-stored">
                                            <div class="dz-details">
                                                <input type="text" name="audioStored[]" value="/location/example1.mp3" placeholder="">
                                                <div class="dz-filename"><span data-dz-name="">example1.mp3</span></div>
                                                <div class="dz-size" data-dz-size=""><strong>57.2</strong> KB</div>
                                            </div>
                                            <a href="javascript:undefined;" title="Удалить" class="link red">Удалить</a>
                                            <a href="javascript:undefined;" title="Восстановить" class="link green" style="display:none;">Восстановить</a>
                                        </div>
                                        <div class="dz-preview dz-image-preview dz-stored">
                                            <div class="dz-details">
                                                <input type="text" name="audioStored[]" value="/location/example2.mp3" placeholder="">
                                                <div class="dz-filename"><span data-dz-name="">example2.mp3</span></div>
                                                <div class="dz-size" data-dz-size=""><strong>44.6</strong> KB</div>
                                            </div>
                                            <a href="javascript:undefined;" title="Удалить" class="link red">Удалить</a>
                                            <a href="javascript:undefined;" title="Восстановить" class="link green" style="display:none;">Восстановить</a>
                                        </div>
                                        <div class="dz-preview dz-image-preview dz-stored">
                                            <div class="dz-details">
                                                <input type="text" name="audioStored[]" value="/location/example3.mp3" placeholder="">
                                                <div class="dz-filename"><span data-dz-name="">example3.mp3</span></div>
                                                <div class="dz-size" data-dz-size=""><strong>78.6</strong> KB</div>
                                            </div>
                                            <a href="javascript:undefined;" title="Удалить" class="link red">Удалить</a>
                                            <a href="javascript:undefined;" title="Восстановить" class="link green" style="display:none;">Восстановить</a>
                                        </div>
                                    </div>
                                    <a href="javascript:;" title="Загрузить файлы" class="dropzone-default__link">Добавить файл</a>
                                </div>
                            </div>
                            <div id="poorVision" style="display: block">
                                <div class="form-group">
                                    <label class="form-group__label">Ссылка на видео курса (для слабовидящих)</label>
                                    <input type="url" name="video1[]" placeholder="" class="input-regular" value="https://www.youtube.com/?gl=RU" id="courseVideo1">
                                </div>
                                <div class="removable-items">
                                    <div class="form-group">
                                        <div class="input-addon">
                                            <input type="url" name="video1[]" placeholder="" class="input-regular" value="https://www.youtube.com/?gl=RU">
                                            <div class="addon"><div class="btn-icon small icon-close"></div></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-addon">
                                            <input type="url" name="video1[]" placeholder="" class="input-regular" value="https://www.youtube.com/?gl=RU">
                                            <div class="addon"><div class="btn-icon small icon-close"></div></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right pull-up">
                                    <a href="#" title="Добавить" class="add-btn" data-duplicate="courseVideo1" data-maxcount="4"><span
                                                class="add-btn__title">Добавить</span><span class="btn-icon small icon-plus"> </span></a>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">Видео файл с устройства (для слабовидящих)</label>
                                    <div data-url="https://dev3.panama.kz/ajaxUploadFilesTest" data-maxfiles="5"
                                         data-maxsize="50" data-acceptedfiles=".mp4" id="video2"
                                         class="dropzone-default dropzone-multiple">
                                        <input type="hidden" name="localVideo1" value="">
                                        <div class="dropzone-default__info">MP4 • макс. 50MB</div>
                                        <div class="previews-container">
                                            <div class="dz-preview dz-image-preview dz-stored">
                                                <div class="dz-details">
                                                    <input type="text" name="localVideo1Stored[]" value="/location/example1.mp4" placeholder="">
                                                    <div class="dz-filename"><span data-dz-name="">example1.mp4</span></div>
                                                    <div class="dz-size" data-dz-size=""><strong>57.2</strong> KB</div>
                                                </div>
                                                <a href="javascript:undefined;" title="Удалить" class="link red">Удалить</a>
                                                <a href="javascript:undefined;" title="Восстановить" class="link green" style="display:none;">Восстановить</a>
                                            </div>
                                        </div>
                                        <a href="javascript:;" title="Загрузить файлы" class="dropzone-default__link">Добавить файл</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">Аудио курса (для слабовидящих)</label>
                                    <div data-url="https://dev3.panama.kz/ajax_upload_course_audios" data-maxfiles="5"
                                         data-maxsize="10" data-acceptedfiles=".mp3" id="audio2"
                                         class="dropzone-default dropzone-multiple">
                                        <input type="hidden" name="audio1" value="">
                                        <div class="dropzone-default__info">MP3 • макс. 10MB</div>
                                        <div class="previews-container">
                                            <div class="dz-preview dz-image-preview dz-stored">
                                                <div class="dz-details">
                                                    <input type="text" name="audio1Stored[]" value="/location/example1.mp3" placeholder="">
                                                    <div class="dz-filename"><span data-dz-name="">example1.mp3</span></div>
                                                    <div class="dz-size" data-dz-size=""><strong>57.2</strong> KB</div>
                                                </div>
                                                <a href="javascript:undefined;" title="Удалить" class="link red">Удалить</a>
                                                <a href="javascript:undefined;" title="Восстановить" class="link green" style="display:none;">Восстановить</a>
                                            </div>
                                            <div class="dz-preview dz-image-preview dz-stored">
                                                <div class="dz-details">
                                                    <input type="text" name="audio1Stored[]" value="/location/example2.mp3" placeholder="">
                                                    <div class="dz-filename"><span data-dz-name="">example2.mp3</span></div>
                                                    <div class="dz-size" data-dz-size=""><strong>44.6</strong> KB</div>
                                                </div>
                                                <a href="javascript:undefined;" title="Удалить" class="link red">Удалить</a>
                                                <a href="javascript:undefined;" title="Восстановить" class="link green" style="display:none;">Восстановить</a>
                                            </div>
                                        </div>
                                        <a href="javascript:;" title="Загрузить файлы" class="dropzone-default__link">Добавить файл</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">Выберите сертификат окончания</label>
                                <div class="row row--multiline">
                                    <div class="col-auto">
                                        <div class="image-choice">
                                            <img src="/assets/img/certificates/1-thumbnail.jpg" class="image-choice__thumbnail"
                                                 alt="">
                                            <label class="image-choice__overflow" title="Выбрать">
                                                <input type="radio" value="1" name="certificate" checked required>
                                                <i class="icon-checkmark"> </i>
                                                <a href="/assets/img/certificates/1.jpg" data-fancybox title="Увеличить"
                                                   class="icon-zoom-in"> </a>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="image-choice">
                                            <img src="/assets/img/certificates/2-thumbnail.jpg" class="image-choice__thumbnail"
                                                 alt="">
                                            <label class="image-choice__overflow" title="Выбрать">
                                                <input type="radio" value="2" name="certificate">
                                                <i class="icon-checkmark"> </i>
                                                <a href="/assets/img/certificates/2.jpg" data-fancybox title="Увеличить"
                                                   class="icon-zoom-in"> </a>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="image-choice">
                                            <img src="/assets/img/certificates/3-thumbnail.jpg" class="image-choice__thumbnail"
                                                 alt="">
                                            <label class="image-choice__overflow" title="Выбрать">
                                                <input type="radio" value="3" name="certificate">
                                                <i class="icon-checkmark"> </i>
                                                <a href="/assets/img/certificates/3.jpg" data-fancybox title="Увеличить"
                                                   class="icon-zoom-in"> </a>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons">
                                <button type="submit" class="btn">Создать</button>
                                <a href="#" title="Отмена" class="ghost-btn">Отмена</a>
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
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            (function($) {
                const skillsEl = $('[name="skills[]"]');
                let paidCheckbox = document.querySelector('#paidCheckbox'),
                    paidFormgroup = document.querySelector('#paidFormgroup');

                skillsEl.each(function () {
                    let skillsSelect = new ajaxSelect($(this));
                });

                paidCheckbox.addEventListener('change', function (e) {
                    if (e.target.checked) {
                        showEl(paidFormgroup);
                        paidFormgroup.querySelector('input').setAttribute('required', 'required');
                    } else {
                        hideEl(paidFormgroup);
                        paidFormgroup.querySelector('input').removeAttribute('required');
                    }
                })
            })(jQuery);
        });
    </script>
    <!---->
@endsection

