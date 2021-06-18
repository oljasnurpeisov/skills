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
                                    <a href="/files/author_instructions_{{$lang}}.pdf" target="_blank" style="color: #2ab5f6;">
                                        {{ __('default.pages.courses.instruction') }}
                                    </a>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_name')}}</label>
                                <div class="input-addon">
                                    <input type="text" name="name" placeholder="" value="{{ old('name') }}"
                                           class="input-regular" required>
                                    <div class="addon">
                                        <span class="required">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label
                                    class="form-group__label">{{__('default.pages.courses.choose_professional_area_title')}}</label>
                                <div class="input-addon">
                                    <select name="professional_areas[]"
                                            placeholder="{{__('default.pages.courses.choose_professional_area_title')}}"
                                            data-method="getProfessionalAreaByName"
                                            class="professional-areas-select"
{{--                                            data-noresults="{{__('default.pages.index.nothing_to_show')}}" required>--}}
                                            data-noresults="{{__('default.pages.index.nothing_to_show')}}" multiple required>
                                    </select>
                                    <div class="addon">
                                        <span class="required">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label
                                    class="form-group__label">{{__('default.pages.courses.choose_profession_title')}}</label>
                                <div class="input-addon">
                                    <select name="professions[]"
                                            placeholder="{{__('default.pages.courses.choose_profession_title')}}"
                                            data-method="getProfessionsByData"
                                            class="professions-select"
{{--                                            data-noresults="{{__('default.pages.index.nothing_to_show')}}" required>--}}
                                            data-noresults="{{__('default.pages.index.nothing_to_show')}}" multiple required>
                                    </select>
                                    <div class="addon">
                                        <span class="required">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label
                                    class="form-group__label">{{__('default.pages.courses.choose_skills_title')}}</label>
                                <div class="input-addon">
                                    <select name="skills[]" id="skillsSelect"
                                            placeholder="{{__('default.pages.courses.choose_skills_title')}}"
                                            data-method="getSkillsByData" data-maxitems="7"
                                            class="skills-select"
                                            data-noresults="{{__('default.pages.index.nothing_to_show')}}" multiple
                                            required>
                                    </select>
                                    <div class="addon">
                                        <span class="required">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_lang')}}</label>
                                <div class="input-addon">
                                    <select name="lang" placeholder="{{__('default.pages.courses.choose_lang')}}"
                                            class="selectize-regular" required>
                                        <option value="1">Русский</option>
                                        <option value="0">Қазақша</option>
                                    </select>
                                    <div class="addon">
                                        <span class="required">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.type_title') }}</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="col-sm-12">
                                            <label class="radio">
                                                <input type="radio" name="is_paid" value="false" data-toggle="paidFormgroup,quota_status" checked><span>{{__('default.pages.courses.free_title') }}</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="radio">
                                                <input type="radio" name="is_paid" value="true" data-toggle="paidFormgroup,quota_status"><span>{{__('default.pages.courses.paid_title') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6" id="quota_status" style="display:none;">
                                        <label class="checkbox">
                                            <input type="checkbox" name="quota_status" value="true"><span>{{ __('default.pages.courses.quota_title') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="paidFormgroup" style="display:none;">
                                <label class="form-group__label">{{__('default.pages.courses.course_cost') }}</label>
                                <div class="input-addon">
                                    <input type="text" name="cost" placeholder="" class="input-regular"
                                           value="{{ old('cost') ?? 0 }}" required disabled onfocus="$(this).inputmask('currency', {prefix: '',groupSeparator: ' ',rightAlign: false, digits: 0})">
                                    <div class="addon">
                                        <span class="required">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.availability_title') }}</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="checkbox">
                                            <input type="checkbox" name="is_access_all" value="true"><span>{{__('default.pages.courses.is_access_all')}}</span>
                                        </label>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="checkbox">
                                            <input type="checkbox" name="is_poor_vision" value="true" data-toggle="poorVision">
                                            <span>{{__('default.pages.courses.is_vision_version')}}</span>
                                        </label>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="checkbox">
                                            <input type="checkbox" name="is_poor_hearing" value="true" data-toggle="poorHearing">
                                            <span>{{__('default.pages.courses.is_poor_hearing')}}</span>
                                        </label>
                                    </div>
                                </div>
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
                                <label class="form-group__label">{{__('default.pages.courses.course_profit')}} *</label>
                                <textarea name="profit_desc" class="input-regular tinymce-text-here"
                                          placeholder="{{__('default.pages.courses.course_profit_placeholder')}}"
                                          required>{{ old('profit_desc') }}</textarea>
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
                                        <div
                                            class="course-image__link avatar-pick dropzone-default__link">{{__('default.pages.courses.choose_photo')}}</div>
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
                            <div class="removable-items"></div>
                            <div class="text-right pull-up">
                                <a href="#" title="{{__('default.pages.profile.add_btn_title')}}" class="add-btn"
                                   data-duplicate="courseVideo" data-maxcount="4"><span
                                        class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span
                                        class="btn-icon small icon-plus"> </span></a>
                            </div>
                            {{--                            <div class="form-group">--}}
                            {{--                                <label class="form-group__label">{{__('default.pages.courses.video_local')}}</label>--}}
                            {{--                                <div data-url="/ajax_upload_course_videos?_token={{ csrf_token() }}" data-maxfiles="5"--}}
                            {{--                                     data-maxsize="500" data-acceptedfiles=".mp4" id="video"--}}
                            {{--                                     class="dropzone-default dropzone-multiple">--}}
                            {{--                                    <input type="hidden" name="videos" value="">--}}
                            {{--                                    <div class="dropzone-default__info">MP4--}}
                            {{--                                        • {{__('default.pages.courses.max_file_title')}} 500MB--}}
                            {{--                                    </div>--}}
                            {{--                                    <a href="javascript:;" title="{{__('default.pages.courses.add_file_btn_title')}}"--}}
                            {{--                                       class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>--}}
                            {{--                                    <div class="previews-container"></div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
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
                                <h3 class="title-tertiary">{{__('default.pages.courses.is_vision_version')}}</h3>
{{--                                <div class="form-group">--}}
{{--                                    <label--}}
{{--                                        class="form-group__label">{{__('default.pages.courses.video_link_1')}}</label>--}}
{{--                                    <input type="url" name="videos_poor_vision_link[]" placeholder=""--}}
{{--                                           class="input-regular" id="courseVideo1" required disabled>--}}
{{--                                </div>--}}
{{--                                <div class="removable-items"></div>--}}
{{--                                <div class="text-right pull-up">--}}
{{--                                    <a href="#" title="{{__('default.pages.courses.add_btn_title')}}" class="add-btn"--}}
{{--                                       data-duplicate="courseVideo1" data-maxcount="4"><span--}}
{{--                                            class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span--}}
{{--                                            class="btn-icon small icon-plus"> </span></a>--}}
{{--                                </div>--}}
                                {{--                                <div class="form-group">--}}
                                {{--                                    <label--}}
                                {{--                                        class="form-group__label">{{__('default.pages.courses.video_local_1')}}</label>--}}
                                {{--                                    <div data-url="/ajax_upload_course_videos?_token={{ csrf_token() }}"--}}
                                {{--                                         data-maxfiles="5"--}}
                                {{--                                         data-maxsize="500" data-acceptedfiles=".mp4" id="video1"--}}
                                {{--                                         class="dropzone-default dropzone-multiple">--}}
                                {{--                                        <input type="hidden" name="videos_poor_vision" value="">--}}
                                {{--                                        <div class="dropzone-default__info">MP4--}}
                                {{--                                            • {{__('default.pages.courses.max_file_title')}} 500MB--}}
                                {{--                                        </div>--}}
                                {{--                                        <a href="javascript:;"--}}
                                {{--                                           title="{{__('default.pages.courses.add_file_btn_title')}}"--}}
                                {{--                                           class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>--}}
                                {{--                                        <div class="previews-container"></div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                <div class="form-group">
                                    <label
                                        class="form-group__label">{{__('default.pages.courses.course_audio_1')}}*</label>
                                    <div class="input-addon">
                                        <div data-url="/ajax_upload_course_audios?_token={{ csrf_token() }}"
                                             data-maxfiles="5"
                                             data-required="true"
                                             data-maxsize="10" data-acceptedfiles=".mp3" id="audio1"
                                             class="dropzone-default dropzone-multiple">
                                            <input type="hidden" name="audios_poor_vision" value="">
                                            <input name="req" type="text" class="req" required disabled>
                                            <div class="dropzone-default__info">MP3
                                                • {{__('default.pages.courses.max_file_title')}} 10MB
                                            </div>
                                            <a href="javascript:;"
                                               title="{{__('default.pages.courses.add_file_btn_title')}}"
                                               class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                            <div class="previews-container"></div>
                                        </div>
                                        <div class="addon">
                                            <span class="required">*</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="poorHearing" style="display: none">
                                <h3 class="title-tertiary">{{__('default.pages.courses.is_poor_hearing')}}</h3>
                                <div class="form-group">
                                    <label
                                        class="form-group__label">{{__('default.pages.courses.video_link_2')}}*</label>
                                    <div class="input-addon">
                                        <input type="url" name="videos_poor_hearing_link[]" placeholder=""
                                               class="input-regular"
                                               id="courseVideo2" required disabled>
                                        <div class="addon">
                                            <span class="required">*</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="removable-items"></div>
                                <div class="text-right pull-up">
                                    <a href="#" title="{{__('default.pages.courses.add_btn_title')}}" class="add-btn"
                                       data-duplicate="courseVideo2"
                                       data-maxcount="4"><span
                                            class="add-btn__title">{{__('default.pages.courses.add_btn_title')}}</span><span
                                            class="btn-icon small icon-plus"> </span></a>
                                </div>
                                {{--                                <div class="form-group">--}}
                                {{--                                    <label--}}
                                {{--                                        class="form-group__label">{{__('default.pages.courses.video_local_2')}}</label>--}}
                                {{--                                    <div data-url="/ajax_upload_course_videos?_token={{ csrf_token() }}"--}}
                                {{--                                         data-maxfiles="5"--}}
                                {{--                                         data-maxsize="500" data-acceptedfiles=".mp4" id="video2"--}}
                                {{--                                         class="dropzone-default dropzone-multiple">--}}
                                {{--                                        <input type="hidden" name="videos_poor_hearing" value="">--}}
                                {{--                                        <div class="dropzone-default__info">MP4--}}
                                {{--                                            • {{__('default.pages.courses.max_file_title')}} 50MB--}}
                                {{--                                        </div>--}}
                                {{--                                        <a href="javascript:;"--}}
                                {{--                                           title="{{__('default.pages.courses.add_file_btn_title')}}"--}}
                                {{--                                           class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}--}}
                                {{--                                        </a>--}}
                                {{--                                        <div class="previews-container"></div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                {{--                                <div class="form-group">--}}
                                {{--                                    <label--}}
                                {{--                                        class="form-group__label">{{__('default.pages.courses.course_audio_2')}}</label>--}}
                                {{--                                    <div data-url="/ajax_upload_course_audios?_token={{ csrf_token() }}"--}}
                                {{--                                         data-maxfiles="5"--}}
                                {{--                                         data-maxsize="10" data-acceptedfiles=".mp3" id="audio2"--}}
                                {{--                                         class="dropzone-default dropzone-multiple">--}}
                                {{--                                        <input type="hidden" name="audios_poor_hearing" value="">--}}
                                {{--                                        <div class="dropzone-default__info">MP3--}}
                                {{--                                            • {{__('default.pages.courses.max_file_title')}} 10MB--}}
                                {{--                                        </div>--}}
                                {{--                                        <a href="javascript:;"--}}
                                {{--                                           title="{{__('default.pages.courses.add_file_btn_title')}}"--}}
                                {{--                                           class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}--}}
                                {{--                                        </a>--}}
                                {{--                                        <div class="previews-container"></div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.preview_certificate_title')}}</label>
                                <input name="certificate_id" type="text" value="{{ 10 ?? old('certificate_id') }}">

                                <div class="row row--multiline">
                                    <div class="col-auto">
                                        <div class="image-choice checked" data-id="10">
                                            <img src="/assets/img/certificates/cert_new_10.jpg"
                                                 class="image-choice__thumbnail"
                                                 alt="">
                                            <label class="image-choice__overflow">
                                                <i> </i>
                                                <a class="check" style="margin-top: -22px;">✓</a>
                                                <a href="/assets/img/certificates/cert_new_10.jpg" data-fancybox title="{{__('default.pages.courses.zoom_certificate')}}"
                                                   class="icon-zoom-in" style="margin-top: 23px"> </a>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <div class="image-choice " data-id="11">
                                            <img src="/assets/img/certificates/cert_new_11.jpg"
                                                 class="image-choice__thumbnail"
                                                 alt="">
                                            <label class="image-choice__overflow">
                                                <i> </i>
                                                <a class="check" style="margin-top: -22px;">✓</a>
                                                <a href="/assets/img/certificates/cert_new_11.jpg" data-fancybox title="{{__('default.pages.courses.zoom_certificate')}}"
                                                   class="icon-zoom-in" style="margin-top: 23px"> </a>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <div class="image-choice " data-id="12">
                                            <img src="/assets/img/certificates/cert_new_12.jpg"
                                                 class="image-choice__thumbnail"
                                                 alt="">
                                            <label class="image-choice__overflow">
                                                <i> </i>
                                                <a class="check" style="margin-top: -22px;">✓</a>
                                                <a href="/assets/img/certificates/cert_new_12.jpg" data-fancybox title="{{__('default.pages.courses.zoom_certificate')}}"
                                                   class="icon-zoom-in" style="margin-top: 23px"> </a>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <div class="image-choice " data-id="13">
                                            <img src="/assets/img/certificates/cert_new_13.jpg"
                                                 class="image-choice__thumbnail"
                                                 alt="">
                                            <label class="image-choice__overflow">
                                                <i> </i>
                                                <a class="check" style="margin-top: -22px;">✓</a>
                                                <a href="/assets/img/certificates/cert_new_13.jpg" data-fancybox title="{{__('default.pages.courses.zoom_certificate')}}"
                                                   class="icon-zoom-in" style="margin-top: 23px"> </a>
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

    <style>
        .image-choice.checked {
            border: 3px solid #2ab5f6;
        }
    </style>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script>
        $('.image-choice .check').on('click', function () {
            $('.image-choice').removeClass('checked');
            let block = $(this).parent().parent();
            block.addClass('checked');
            $('input[name="certificate_id"]').val(block.attr('data-id'));
        });
    </script>
    <script>
        const professionalAreaEl = $('[name="professional_areas[]"]'),
            specialityEl = $('[name="professions[]"]'),
            skillsEl = $('[name="skills[]"]');

        let professionAreaSelect = new ajaxSelect(professionalAreaEl, null, true, 2);
        let specialitySelect = new ajaxSelect(specialityEl, professionalAreaEl, true, 3);
        let skillsSelect = new ajaxSelect(skillsEl, specialityEl, true, 7);

        var type = null;

        function clearAllFields() {
            professionAreaSelect.clear();
            specialitySelect.clear();
            skillsSelect.clear();
        }

        professionalAreaEl.change(function () {
            if (type === null) {
                type = 1;
            }

            if (type === 1) {
                specialitySelect.update($(this).val() ? {"professional_areas": toArray($(this).val())} : null);
                setTimeout(function () {
                    specialitySelect.removeMessage();
                }, 3000);
            }

            if (professionalAreaEl.val() === null && specialityEl.val() === null && skillsEl.val() === null) {
                type = null;
                clearAllFields();
            }
        });

        specialityEl.change(function () {
            if (type === null) {
                type = 1;
            }

            if (type === 2) {
                professionAreaSelect.update($(this).val() ? {"professions": toArray($(this).val())} : null);

                setTimeout(function () {
                    professionAreaSelect.removeMessage();
                }, 3000);
            } else if (type === 1) {
                professionAreaSelect.update($(this).val() ? {"professions": toArray($(this).val())} : null);
                skillsSelect.update($(this).val() ? {"professions": toArray($(this).val())} : null);
                setTimeout(function () {
                    skillsSelect.removeMessage();
                }, 3000);
            }

            if (professionalAreaEl.val() === null && specialityEl.val() === null && skillsEl.val() === null) {
                type = null;
                clearAllFields();
            }
        });

        skillsEl.change(function () {
            if (type === null) {
                type = 2;
            }

            if (type === 2) {
                specialitySelect.update($(this).val() ? {"skills": toArray($(this).val())} : null);
                setTimeout(function () {
                    specialitySelect.removeMessage();
                }, 3000);
            }

            if (professionalAreaEl.val() === null && specialityEl.val() === null && skillsEl.val() === null) {
                type = null;
                clearAllFields();
            }
        })
    </script>

    <script>
        let quotaEl = $('[name="quota_status"]'),
            paidEl = $('[name="is_paid"]');

        paidEl.change(function () {
            quotaEl.prop('checked', false);
        });
    </script>
@endsection

