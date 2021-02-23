@extends('app.layout.default.template')

@section('content')
    <main class="main">
        <section class="plain">
            <div class="container">
                <ul class="breadcrumbs">
                    <li><a href="/{{$lang}}/my-courses/"
                           title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
                    </li>
                    <li><a href="/{{$lang}}/my-courses/drafts"
                           title="{{__('default.pages.courses.drafts')}}">{{__('default.pages.courses.drafts')}}</a>
                    </li>
                    <li><span>{{__('default.pages.courses.creation_course')}}</span></li>
                </ul>
                <h1 class="title-primary">{{__('default.pages.courses.creation_course')}}</h1>
                @if (session('failed'))
                    <div class="alert alert-danger">
                        {!! session('failed') !!}
                    </div>
                @endif
                <div class="row row--multiline">
                    <div class="col-md-8">
                        <form action="/{{$lang}}/create-course" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="form-group__label">
                                    <a href="/files/instructions.pdf" target="_blank" style="color: #2ab5f6;">
                                        {{ __('default.pages.courses.instruction') }}
                                    </a>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_name')}} *</label>
                                <input type="text" name="name" placeholder="" class="input-regular"
                                       value="{{ old('name') }}" required>
                            </div>
                            <div class="professions-container" id="professionsContainer">
                                <div class="professions-group">
                                    <div class="form-group">
                                        <label class="form-group__label"
                                               id="skillsLabel">{{__('default.pages.courses.skill_title')}}</label>
                                        <div class="input-addon">
                                            <select name="skills[0]" id="skillsSelect"
                                                    placeholder="{{__('default.pages.courses.choose_skill_title')}}"
                                                    data-method="getSkills" class="skills-select" required>
                                            </select>
                                            <div class="addon">
                                                <span class="required">*</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="display: none">
                                        <label class="form-group__label"
                                               id="professionsLabel">{{__('default.pages.courses.professions_title')}}</label>
                                        <select name="professions[0][]" id="professionsSelect"
                                                placeholder="{{__('default.pages.courses.choose_professions_title')}}"
                                                data-method="getProfessionsBySkills" data-maxitems="7"
                                                class="professions-select" multiple required>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right pull-up">
                                <a href="#" title="{{__('default.pages.courses.add_skill')}}" id="addProfessionGroup"
                                   data-maxitems="7"
                                   class="add-btn"
                                   style="margin-top: 5px"><span
                                            class="add-btn__title">{{__('default.pages.courses.add_skill')}}</span><span
                                            class="btn-icon small icon-plus"> </span></a>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_lang')}}</label>
                                <select name="lang" placeholder="{{__('default.pages.courses.choose_lang')}}"
                                        class="selectize-regular">
                                    <option value="1">Русский</option>
                                    <option value="0">Қазақша</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="checkbox"><input type="checkbox" name="is_paid"
                                                               value="true" id="paidCheckbox"><span>{{__('default.pages.courses.is_paid')}} ({{__('default.pages.courses.default_free')}})</span></label>
                                <label class="checkbox"><input type="checkbox" name="is_access_all"
                                                               value="true"><span>{{__('default.pages.courses.is_access_all')}}</span></label>
                                <label class="checkbox"><input type="checkbox" name="is_poor_vision"
                                                               value="true"
                                                               data-toggle="poorVision"><span>{{__('default.pages.courses.is_vision_version')}}</span></label>
                            </div>
                            <div class="form-group" id="paidFormgroup" style="display:none;">
                                <label class="form-group__label">{{__('default.pages.courses.course_cost')}} *</label>
                                <input type="number" name="cost" placeholder="" class="input-regular"
                                       value="{{ old('cost') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_profit')}} *</label>
                                <textarea name="profit_desc" class="input-regular tinymce-text-here"
                                          placeholder="{{__('default.pages.courses.course_profit_placeholder')}}"
                                          required>{{ old('profit_desc') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_teaser')}} *</label>
                                <textarea name="teaser" class="input-regular tinymce-text-here"
                                          required>{{ old('teaser') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_desc')}} *</label>
                                <textarea name="description" class="input-regular tinymce-text-here"
                                          placeholder="{{__('default.pages.courses.course_description_placeholder')}}"
                                          required>{{ old('description') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_image')}}</label>
                                <div class="avatar course-image dropzone-avatar" id="courseCover"
                                     data-url="/ajax_upload_course_image?_token={{ csrf_token() }}" data-maxsize="1"
                                     data-acceptedfiles="image/*">
                                    <input type="text" name="image" class="avatar-path" hidden>
                                    <img src="/assets/img/course-thumbnail.jpg"
                                         class="course-image__preview avatar-preview" alt="">
                                    <div class="course-image__desc dropzone-default">
                                        <div class="previews-container"></div>
                                        <div class="dropzone-default__info">PNG, JPG
                                            • {{__('default.pages.courses.max_file_title')}} 1MB
                                        </div>
                                        <div class="course-image__link avatar-pick dropzone-default__link">{{__('default.pages.courses.choose_photo')}}</div>
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
                                            <a href="javascript:undefined;"
                                               title="{{__('default.pages.courses.delete')}}"
                                               class="author-picture__link red"
                                               data-dz-remove>{{__('default.pages.courses.delete')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.video_link')}}</label>
                                <input type="url" name="videos_link[]" placeholder="" class="input-regular"
                                       id="courseVideo">
                            </div>
                            <div class="text-right pull-up">
                                <a href="#" title="{{__('default.pages.profile.add_btn_title')}}" class="add-btn"
                                   data-duplicate="courseVideo" data-maxcount="4"><span
                                            class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span
                                            class="btn-icon small icon-plus"> </span></a>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.video_local')}}</label>
                                <div data-url="/ajax_upload_course_videos?_token={{ csrf_token() }}" data-maxfiles="5"
                                     data-maxsize="500" data-acceptedfiles=".mp4" id="video"
                                     class="dropzone-default dropzone-multiple">
                                    <input type="hidden" name="videos" value="">
                                    <div class="dropzone-default__info">MP4
                                        • {{__('default.pages.courses.max_file_title')}} 500MB
                                    </div>
                                    <a href="javascript:;" title="{{__('default.pages.courses.add_file_btn_title')}}"
                                       class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                    <div class="previews-container"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_audio')}}</label>
                                <div data-url="/ajax_upload_course_audios?_token={{ csrf_token() }}" data-maxfiles="5"
                                     data-maxsize="10" data-acceptedfiles=".mp3" id="audio"
                                     class="dropzone-default dropzone-multiple">
                                    <input type="hidden" name="audios" value="">
                                    <div class="dropzone-default__info">MP3
                                        • {{__('default.pages.courses.max_file_title')}} 10MB
                                    </div>
                                    <a href="javascript:;" title="{{__('default.pages.courses.add_file_btn_title')}}"
                                       class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                    <div class="previews-container"></div>
                                </div>
                            </div>
                            <div id="poorVision" style="display: none">
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.courses.video_link_1')}}</label>
                                    <input type="url" name="videos_poor_vision_link[]" placeholder=""
                                           class="input-regular" id="courseVideo1">
                                </div>
                                <div class="text-right pull-up">
                                    <a href="#" title="{{__('default.pages.courses.add_btn_title')}}" class="add-btn"
                                       data-duplicate="courseVideo1" data-maxcount="4"><span
                                                class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span
                                                class="btn-icon small icon-plus"> </span></a>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.courses.video_local_1')}}</label>
                                    <div data-url="/ajax_upload_course_videos?_token={{ csrf_token() }}"
                                         data-maxfiles="5"
                                         data-maxsize="500" data-acceptedfiles=".mp4" id="video2"
                                         class="dropzone-default dropzone-multiple">
                                        <input type="hidden" name="videos_poor_vision" value="">
                                        <div class="dropzone-default__info">MP4
                                            • {{__('default.pages.courses.max_file_title')}} 500MB
                                        </div>
                                        <a href="javascript:;"
                                           title="{{__('default.pages.courses.add_file_btn_title')}}"
                                           class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                        <div class="previews-container"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.courses.course_audio_1')}}</label>
                                    <div data-url="/ajax_upload_course_audios?_token={{ csrf_token() }}"
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
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.choose_certificate')}}
                                    *</label>
                                <div class="row row--multiline">
                                    <div class="col-auto">
                                        <div class="image-choice">
                                            <img src="/assets/img/certificates/1-thumbnail.jpg"
                                                 class="image-choice__thumbnail"
                                                 alt="">
                                            <label class="image-choice__overflow"
                                                   title="{{__('default.pages.courses.choose')}}">
                                                <input type="radio" value="1" name="certificate_id" required>
                                                <i class="icon-checkmark"> </i>
                                                <a href="/assets/img/certificates/1.png" data-fancybox
                                                   title="{{__('default.pages.courses.zoom_certificate')}}"
                                                   class="icon-zoom-in"> </a>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="image-choice">
                                            <img src="/assets/img/certificates/2-thumbnail.jpg"
                                                 class="image-choice__thumbnail"
                                                 alt="">
                                            <label class="image-choice__overflow"
                                                   title="{{__('default.pages.courses.choose')}}">
                                                <input type="radio" value="2" name="certificate_id">
                                                <i class="icon-checkmark"> </i>
                                                <a href="/assets/img/certificates/2.png" data-fancybox
                                                   title="{{__('default.pages.courses.zoom_certificate')}}"
                                                   class="icon-zoom-in"> </a>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="image-choice">
                                            <img src="/assets/img/certificates/3-thumbnail.jpg"
                                                 class="image-choice__thumbnail"
                                                 alt="">
                                            <label class="image-choice__overflow"
                                                   title="{{__('default.pages.courses.choose')}}">
                                                <input type="radio" value="3" name="certificate_id">
                                                <i class="icon-checkmark"> </i>
                                                <a href="/assets/img/certificates/3.png" data-fancybox
                                                   title="{{__('default.pages.courses.zoom_certificate')}}"
                                                   class="icon-zoom-in"> </a>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons">
                                <button type="submit" class="btn">{{__('default.pages.courses.create')}}</button>
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
    <script src="/assets/js/professions-group.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            let paidCheckbox = document.querySelector('#paidCheckbox'),
                paidFormgroup = document.querySelector('#paidFormgroup');

            paidCheckbox.addEventListener('change', function (e) {
                if (e.target.checked) {
                    showEl(paidFormgroup);
                    paidFormgroup.querySelector('input').setAttribute('required', 'required');
                } else {
                    hideEl(paidFormgroup);
                    paidFormgroup.querySelector('input').removeAttribute('required');
                }
            });
        });
    </script>

    <!---->
@endsection

