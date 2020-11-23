@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="plain">
            <div class="container">
                <ul class="breadcrumbs">
                    <li><a href="/{{$lang}}/my-courses/" title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a></li>
                    <li><span>{{__('default.pages.courses.creation_course')}}</span></li>
                </ul>
                <h1 class="title-primary">{{__('default.pages.courses.creation_course')}}</h1>

                <div class="row row--multiline">
                    <div class="col-md-8">
                        <form action="/{{$lang}}/create-course" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_name')}}</label>
                                <input type="text" name="name" placeholder="" class="input-regular" required>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.skills_title')}}</label>
                                <div class="input-addon">
                                    <select name="skills[]" placeholder="{{__('default.pages.courses.choose_skill')}}"
                                            data-method="getSkillsByData" id="skillsInputTpl"> </select>
                                    <div class="addon">
                                        <span class="required">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right pull-up">
                                <a href="#" title="{{__('default.pages.profile.add_btn_title')}}" class="add-btn" data-duplicate="skillsInputTpl"><span
                                            class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span class="btn-icon small icon-plus"> </span></a>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_lang')}}</label>
                                <select name="lang" placeholder="{{__('default.pages.courses.choose_lang')}}" class="selectize-regular">
                                    <option value="1">Русский</option>
                                    <option value="0">Қазақша</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="checkbox"><input type="checkbox" name="is_paid"
                                                               value="true" id="paidCheckbox"><span>{{__('default.pages.courses.is_paid')}}</span></label>
                                <label class="checkbox"><input type="checkbox" name="is_access_all"
                                                               value="true"><span>{{__('default.pages.courses.is_access_all')}}</span></label>
                                <label class="checkbox"><input type="checkbox" name="is_poor_vision"
                                                               value="true" data-toggle="poorVision"><span>{{__('default.pages.courses.is_vision_version')}}</span></label>
                            </div>
                            <div class="form-group" id="paidFormgroup" style="display:none;">
                                <label class="form-group__label">{{__('default.pages.courses.course_cost')}} *</label>
                                <input type="text" name="cost" placeholder="" class="input-regular">
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_profit')}} *</label>
                                <textarea name="profit_desc" class="input-regular" required> </textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_teaser')}} *</label>
                                <textarea name="teaser" class="input-regular" required> </textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_desc')}} *</label>
                                <textarea name="description" class="input-regular" required> </textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_image')}}</label>
                                <div class="avatar course-image dropzone-avatar" id="courseCover"
                                     data-url="/ajax_upload_course_image?_token={{ csrf_token() }}" data-maxsize="1"
                                     data-acceptedfiles="image/*">
                                    <input type="hidden" name="image" class="avatar-path">
                                    <img src="/assets/img/course-thumbnail.jpg" class="course-image__preview avatar-preview" alt="">
                                    <div class="course-image__desc dropzone-default">
                                        <div class="previews-container"></div>
                                        <div class="dropzone-default__info">PNG, JPG • {{__('default.pages.courses.max_file_title')}} 1MB</div>
                                        <div class="course-image__link avatar-pick dropzone-default__link">{{__('default.pages.courses.choose_photo')}}</div>
                                    </div>
                                    <div class="avatar-preview-template" style="display:none;">
                                        <div class="dz-preview dz-file-preview">
                                            <div class="dz-details">
                                                <div class="dz-filename"><span data-dz-name></span></div>
                                                <div class="dz-size" data-dz-size></div>
                                                <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                                            </div>
                                            <div class="alert alert-danger"><span data-dz-errormessage> </span></div>
                                            <a href="javascript:undefined;" title="{{__('default.pages.courses.delete')}}" class="author-picture__link red"
                                               data-dz-remove>{{__('default.pages.courses.delete')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.video_link')}}</label>
                                <input type="url" name="videos_link[]" placeholder="" class="input-regular" id="courseVideo">
                            </div>
                            <div class="text-right pull-up">
                                <a href="#" title="{{__('default.pages.profile.add_btn_title')}}" class="add-btn" data-duplicate="courseVideo" data-maxcount="4"><span
                                            class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span class="btn-icon small icon-plus"> </span></a>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.video_local')}}</label>
                                <div data-url="/ajax_upload_course_videos?_token={{ csrf_token() }}" data-maxfiles="5"
                                     data-maxsize="50" data-acceptedfiles=".mp4" id="video"
                                     class="dropzone-default dropzone-multiple">
                                    <input type="hidden" name="videos" value="">
                                    <div class="dropzone-default__info">MP4 • {{__('default.pages.courses.max_file_title')}} 50MB</div>
                                    <a href="javascript:;" title="{{__('default.pages.courses.add_file_btn_title')}}" class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                    <div class="previews-container"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_audio')}}</label>
                                <div data-url="/ajax_upload_course_audios?_token={{ csrf_token() }}" data-maxfiles="5"
                                     data-maxsize="10" data-acceptedfiles=".mp3" id="audio"
                                     class="dropzone-default dropzone-multiple">
                                    <input type="hidden" name="audios" value="">
                                    <div class="dropzone-default__info">MP3 • {{__('default.pages.courses.max_file_title')}} 10MB</div>
                                    <a href="javascript:;" title="{{__('default.pages.courses.add_file_btn_title')}}" class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                    <div class="previews-container"></div>
                                </div>
                            </div>
                            <div id="poorVision" style="display: none">
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.courses.video_link_1')}}</label>
                                    <input type="url" name="videos_poor_vision_link[]" placeholder="" class="input-regular" id="courseVideo1">
                                </div>
                                <div class="text-right pull-up">
                                    <a href="#" title="{{__('default.pages.courses.add_btn_title')}}" class="add-btn" data-duplicate="courseVideo1" data-maxcount="4"><span
                                                class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span class="btn-icon small icon-plus"> </span></a>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.courses.video_local_1')}}</label>
                                    <div data-url="/ajax_upload_course_videos?_token={{ csrf_token() }}" data-maxfiles="5"
                                         data-maxsize="50" data-acceptedfiles=".mp4" id="video2"
                                         class="dropzone-default dropzone-multiple">
                                        <input type="hidden" name="videos_poor_vision" value="">
                                        <div class="dropzone-default__info">MP4 • {{__('default.pages.courses.max_file_title')}} 50MB</div>
                                        <a href="javascript:;" title="{{__('default.pages.courses.add_file_btn_title')}}" class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                        <div class="previews-container"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.courses.course_audio_1')}}</label>
                                    <div data-url="/ajax_upload_course_audios?_token={{ csrf_token() }}" data-maxfiles="5"
                                         data-maxsize="10" data-acceptedfiles=".mp3" id="audio2"
                                         class="dropzone-default dropzone-multiple">
                                        <input type="hidden" name="audios_poor_vision" value="">
                                        <div class="dropzone-default__info">MP3 • {{__('default.pages.courses.max_file_title')}} 10MB</div>
                                        <a href="javascript:;" title="{{__('default.pages.courses.add_file_btn_title')}}" class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                        <div class="previews-container"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.choose_certificate')}}</label>
                                <div class="row row--multiline">
                                    <div class="col-auto">
                                        <div class="image-choice">
                                            <img src="/assets/img/certificates/1-thumbnail.jpg" class="image-choice__thumbnail"
                                                 alt="">
                                            <label class="image-choice__overflow" title="{{__('default.pages.courses.choose')}}">
                                                <input type="radio" value="1" name="certificate_id" required>
                                                <i class="icon-checkmark"> </i>
                                                <a href="/assets/img/certificates/1.jpg" data-fancybox title="{{__('default.pages.courses.zoom_certificate')}}"
                                                   class="icon-zoom-in"> </a>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="image-choice">
                                            <img src="/assets/img/certificates/2-thumbnail.jpg" class="image-choice__thumbnail"
                                                 alt="">
                                            <label class="image-choice__overflow" title="{{__('default.pages.courses.choose')}}">
                                                <input type="radio" value="2" name="certificate_id">
                                                <i class="icon-checkmark"> </i>
                                                <a href="/assets/img/certificates/2.jpg" data-fancybox title="{{__('default.pages.courses.zoom_certificate')}}"
                                                   class="icon-zoom-in"> </a>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="image-choice">
                                            <img src="/assets/img/certificates/3-thumbnail.jpg" class="image-choice__thumbnail"
                                                 alt="">
                                            <label class="image-choice__overflow" title="{{__('default.pages.courses.choose')}}">
                                                <input type="radio" value="3" name="certificate_id">
                                                <i class="icon-checkmark"> </i>
                                                <a href="/assets/img/certificates/3.jpg" data-fancybox title="{{__('default.pages.courses.zoom_certificate')}}"
                                                   class="icon-zoom-in"> </a>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons">
                                <button type="submit" class="btn">{{__('default.pages.courses.create')}}</button>
{{--                                <a href="#" title="Отмена" class="ghost-btn">Отмена</a>--}}
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
    <script defer>
        window.addEventListener('DOMContentLoaded', function() {
            (function($) {
                const skillsEl = $('[name="skills[]"]');
                let skillsSelect = new ajaxSelect(skillsEl),
                    paidCheckbox = document.querySelector('#paidCheckbox'),
                    paidFormgroup = document.querySelector('#paidFormgroup');

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

