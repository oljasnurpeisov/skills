@extends('app.layout.default.template')

@section('content')

    <main class="main">
        <section class="plain">
            <div class="container">
                <ul class="breadcrumbs">
                    <li><a href="/{{$lang}}/my-courses/"
                           title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
                    </li>
                    <li><a href="/{{$lang}}/my-courses/course/{{$item->id}}" title="{{$item->name}}">{{$item->name}}</a>
                    </li>
                    <li><span>{{__('default.pages.courses.edit_course')}}</span></li>
                </ul>
                <h1 class="title-primary">{{__('default.pages.courses.edit_course')}}</h1>

                <div class="row row--multiline">
                    <div class="col-md-8">
                        <form action="/{{$lang}}/my-courses/edit-course/{{$item->id}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_name')}}</label>
                                <input type="text" name="name" placeholder="" value="{{$item->name}}"
                                       class="input-regular" required>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.skills_title')}}</label>
                                <div class="input-addon">
                                    <select name="skills[]" placeholder="{{__('default.pages.courses.choose_skill')}}"
                                            data-method="getSkillsByData" id="skillsInputTpl" required>
                                        <option value="{{$item->skills[0]->id}}"
                                                selected="selected">{{$item->skills[0]->getAttribute('name_'.$lang) ?? $item->skills[0]->getAttribute('name_ru')}}</option>
                                    </select>
                                    <div class="addon">
                                        <span class="required">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="removable-items">
                                <div class="form-group">
                                    @if(!empty($item->skills[1]))
                                        <div class="input-addon">
                                            <select name="skills[]"
                                                    placeholder="{{__('default.pages.courses.choose_skill')}}"
                                                    data-method="getSkillsByData">
                                                @php($s = $item->skills->toArray())
                                                @foreach(array_slice($s,1) as $skill)
                                                    <option value="{{$skill["id"]}}"
                                                            selected="selected">{{$skill['name_'.$lang] ?? $skill['name_ru']}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="addon">
                                                <div class="btn-icon small icon-close"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right pull-up">
                                <a href="#" title="{{__('default.pages.profile.add_btn_title')}}" class="add-btn"
                                   data-duplicate="skillsInputTpl"><span
                                            class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span
                                            class="btn-icon small icon-plus"> </span></a>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_lang')}}</label>
                                <select name="lang" placeholder="{{__('default.pages.courses.choose_lang')}}"
                                        class="selectize-regular" required>
                                    @php($languages = [["Қазақша","0"], ["Русский","1"]])
                                    @foreach($languages as $key => $language)
                                        <option value="{{ $language[1] }}"
                                                @if($language[1]==$item->lang) selected='selected' @endif >{{ $language[0] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="checkbox"><input type="checkbox" name="is_paid"
                                                               value="true" id="paidCheckbox"
                                            {{ ($item->is_paid == true ? ' checked' : '') }}><span>{{__('default.pages.courses.is_paid')}}</span></label>
                                <label class="checkbox"><input type="checkbox" name="is_access_all"
                                                               value="true" {{ ($item->is_access_all == true ? ' checked' : '') }}><span>{{__('default.pages.courses.is_access_all')}}</span></label>
                                <label class="checkbox"><input type="checkbox" name="is_poor_vision"
                                                               value="true"
                                                               data-toggle="poorVision" {{ ($item->is_poor_vision == true ? ' checked' : '') }}><span>{{__('default.pages.courses.is_vision_version')}}</span></label>
                            </div>
                            <div class="form-group" id="paidFormgroup" style="display:block;">
                                <label class="form-group__label">{{__('default.pages.courses.course_cost')}} *</label>
                                <input type="text" name="cost" placeholder="" value="{{$item->cost}}"
                                       class="input-regular"
                                       required>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_profit')}} *</label>
                                <textarea name="profit_desc" class="input-regular tinymce-text-here" required>
                            <p>{{$item->profit_desc}}</p>
                        </textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_teaser')}} *</label>
                                <textarea name="teaser" class="input-regular tinymce-text-here" required>
                            <p>{{$item->teaser}}</p>
                        </textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_desc')}} *</label>
                                <textarea name="description" class="input-regular tinymce-text-here" required>
                            <p>{{$item->description}}</p>
                        </textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_image')}}</label>
                                <div class="avatar course-image dropzone-avatar" id="courseCover"
                                     data-url="/ajax_upload_course_image?_token={{ csrf_token() }}" data-maxsize="1"
                                     data-acceptedfiles="image/*">
                                    <img src="{{$item->image}}"
                                         data-defaultsrc="/assets/img/course-thumbnail.jpg"
                                         class="course-image__preview avatar-preview" alt="">
                                    <div class="course-image__desc dropzone-default">
                                        <input type="text" name="image" class="avatar-path"
                                               value="{{$item->image}}" required="">
                                        <div class="previews-container">
                                            <div class="dz-preview dz-image-preview">
                                                <div class="dz-details">
                                                    <div class="dz-filename"><span
                                                                data-dz-name="">{{basename($item->image)}}</span></div>
                                                    <div class="dz-size" data-dz-size=""><strong> 8</strong> KB</div>
                                                </div>
                                                <a href="javascript:undefined;"
                                                   title="{{__('default.pages.courses.delete')}}"
                                                   class="author-picture__link red"
                                                   data-dz-remove="">{{__('default.pages.courses.delete')}}</a>
                                            </div>
                                        </div>
                                        <div class="dropzone-default__info">PNG, JPG
                                            • {{__('default.pages.courses.max_file_title')}} 1MB
                                        </div>
                                        <div class="course-image__link avatar-pick dropzone-default__link dz-clickable">
                                            {{__('default.pages.courses.choose_photo')}}
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
                                @if($item->attachments->videos_link != null)
                                    <input type="url" name="videos_link[]" placeholder="" class="input-regular"
                                           value="{{json_decode($item->attachments->videos_link)[0]}}" id="courseVideo">
                                @else
                                    <input type="url" name="videos_link[]" placeholder="" class="input-regular"
                                           value="" id="courseVideo">
                                @endif

                            </div>
                            <div class="removable-items">
                                <div class="form-group">
                                    @if($item->attachments->videos_link != null)
                                        <div class="input-addon">
                                            @foreach(array_slice(json_decode($item->attachments->videos_link),1) as $video_link)
                                                <input type="url" name="videos_link[]" placeholder=""
                                                       class="input-regular"
                                                       value="{{$video_link}}">

                                                <div class="addon">
                                                    <div class="btn-icon small icon-close"></div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right pull-up">
                                <a href="#" title="{{__('default.pages.courses.add_btn_title')}}" class="add-btn"
                                   data-duplicate="courseVideo"
                                   data-maxcount="4"><span
                                            class="add-btn__title">{{__('default.pages.courses.add_btn_title')}}</span><span
                                            class="btn-icon small icon-plus"> </span></a>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.video_local')}}</label>
                                <div data-url="/ajax_upload_course_videos?_token={{ csrf_token() }}" data-maxfiles="5"
                                     data-maxsize="50" data-acceptedfiles=".mp4" id="video"
                                     class="dropzone-default dropzone-multiple">
                                    <input type="text" name="localVideo" value="">
                                    <div class="dropzone-default__info">MP4
                                        • {{__('default.pages.courses.max_file_title')}} 50MB
                                    </div>
                                    <div class="previews-container">
                                        @if($item->attachments->videos != null)
                                            @foreach(json_decode($item->attachments->videos) as $video)
                                                <div class="dz-preview dz-image-preview dz-stored">
                                                    <div class="dz-details">
                                                        <input type="text" name="localVideoStored[]"
                                                               value="{{$video}}" placeholder="">
                                                        <div class="dz-filename"><span
                                                                    data-dz-name="">{{basename($video)}}</span>
                                                        </div>
                                                        <div class="dz-size" data-dz-size=""><strong>57.2</strong> KB
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
                                <label class="form-group__label">{{__('default.pages.courses.course_audio')}}</label>
                                <div data-url="/ajax_upload_course_audios?_token={{ csrf_token() }}" data-maxfiles="5"
                                     data-maxsize="10" data-acceptedfiles=".mp3" id="audio"
                                     class="dropzone-default dropzone-multiple">
                                    <input type="text" name="audio" value="">
                                    <div class="dropzone-default__info">MP3
                                        • {{__('default.pages.courses.max_file_title')}} 10MB
                                    </div>
                                    <div class="previews-container">
                                        @if($item->attachments->audios != null)
                                            @foreach(json_decode($item->attachments->audios) as $audio)
                                                <div class="dz-preview dz-image-preview dz-stored">
                                                    <div class="dz-details">
                                                        <input type="text" name="audios[]"
                                                               value="{{$audio}}"
                                                               placeholder="">
                                                        <div class="dz-filename"><span
                                                                    data-dz-name="">{{basename($audio)}}</span>
                                                        </div>
                                                        <div class="dz-size" data-dz-size=""><strong>57.2</strong> KB
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
                            <div id="poorVision" @if($item->is_poor_vision == true) style="display: block"@else style="display: none" @endif>
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.courses.video_link_1')}}</label>
                                    @if($item->attachments->videos_poor_vision_link != null)
                                        <input type="url" name="videos_poor_vision_link[]" placeholder=""
                                               class="input-regular"
                                               value="{{json_decode($item->attachments->videos_poor_vision_link)[0]}}"
                                               id="courseVideo1">
                                    @else
                                        <input type="url" name="videos_poor_vision_link[]" placeholder=""
                                               class="input-regular"
                                               value="" id="courseVideo1">
                                    @endif
                                </div>
                                <div class="removable-items">
                                    <div class="form-group">
                                        @if($item->attachments->videos_poor_vision_link != null)
                                            <div class="input-addon">
                                                @foreach(array_slice(json_decode($item->attachments->videos_poor_vision_link),1) as $video_poor_vision_link)
                                                    <input type="url" name="videos_poor_vision_link[]" placeholder=""
                                                           class="input-regular"
                                                           value="{{$video_poor_vision_link}}">
                                                    <div class="addon">
                                                        <div class="btn-icon small icon-close"></div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right pull-up">
                                    <a href="#" title="{{__('default.pages.courses.add_btn_title')}}" class="add-btn"
                                       data-duplicate="courseVideo1"
                                       data-maxcount="4"><span
                                                class="add-btn__title">{{__('default.pages.courses.add_btn_title')}}</span><span
                                                class="btn-icon small icon-plus"> </span></a>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.courses.video_local_1')}}</label>
                                    <div data-url="/ajax_upload_course_videos?_token={{ csrf_token() }}"
                                         data-maxfiles="5"
                                         data-maxsize="50" data-acceptedfiles=".mp4" id="video2"
                                         class="dropzone-default dropzone-multiple">
                                        <input type="hidden" name="localVideo1" value="">
                                        <div class="dropzone-default__info">MP4
                                            • {{__('default.pages.courses.max_file_title')}} 50MB
                                        </div>
                                        <div class="previews-container">
                                            @if($item->attachments->videos_poor_vision != null)
                                                @foreach(json_decode($item->attachments->videos_poor_vision) as $video_poor_vision)
                                                    <div class="dz-preview dz-image-preview dz-stored">
                                                        <div class="dz-details">
                                                            <input type="text" name="videos_poor_vision[]"
                                                                   value="{{{$video_poor_vision}}}" placeholder="">
                                                            <div class="dz-filename"><span
                                                                        data-dz-name="">{{basename($video_poor_vision)}}</span>
                                                            </div>
                                                            <div class="dz-size" data-dz-size=""><strong>57.2</strong>
                                                                KB
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
                                        <a href="javascript:;"
                                           title="{{__('default.pages.courses.add_file_btn_title')}}"
                                           class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.courses.course_audio_1')}}</label>
                                    <div data-url="/ajax_upload_course_audios?_token={{ csrf_token() }}"
                                         data-maxfiles="5"
                                         data-maxsize="10" data-acceptedfiles=".mp3" id="audio2"
                                         class="dropzone-default dropzone-multiple">
                                        <input type="hidden" name="audio1" value="">
                                        <div class="dropzone-default__info">MP3
                                            • {{__('default.pages.courses.max_file_title')}} 10MB
                                        </div>
                                        <div class="previews-container">
                                            @if($item->attachments->audios_poor_vision != null)
                                                @foreach(json_decode($item->attachments->audios_poor_vision) as $audio_poor_vision)
                                                    <div class="dz-preview dz-image-preview dz-stored">
                                                        <div class="dz-details">
                                                            <input type="text" name="audios_poor_vision[]"
                                                                   value="{{$audio_poor_vision}}" placeholder="">
                                                            <div class="dz-filename"><span
                                                                        data-dz-name="">{{basename($audio_poor_vision)}}</span>
                                                            </div>
                                                            <div class="dz-size" data-dz-size=""><strong>57.2</strong>
                                                                KB
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
                                        <a href="javascript:;" title="Загрузить файлы" class="dropzone-default__link">Добавить
                                            файл</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.choose_certificate')}}</label>
                                <div class="row row--multiline">
                                    @php($certificates = [["1","/assets/img/certificates/1-thumbnail.jpg","/assets/img/certificates/1.jpg"], ["2", "/assets/img/certificates/2-thumbnail.jpg", "/assets/img/certificates/2.jpg"], ["3", "/assets/img/certificates/3-thumbnail.jpg", "/assets/img/certificates/3.jpg"]])
                                    @foreach($certificates as $certificate)
                                        <div class="col-auto">
                                            <div class="image-choice">
                                                <img src="{{$certificate[1]}}"
                                                     class="image-choice__thumbnail"
                                                     alt="">
                                                <label class="image-choice__overflow"
                                                       title="{{__('default.pages.courses.choose')}}">
                                                    <input type="radio" value="{{$certificate[0]}}"
                                                           name="certificate_id"
                                                           {{ ($item->certificate_id == $certificate[0] ? ' checked' : '') }}
                                                           required>
                                                    <i class="icon-checkmark"> </i>
                                                    <a href="{{$certificate[2]}}" data-fancybox
                                                       title="{{__('default.pages.courses.zoom_certificate')}}"
                                                       class="icon-zoom-in"> </a>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="buttons">
                                <button type="submit" class="btn">{{__('default.pages.profile.save_btn_title')}}</button>
                                <a href="/{{$lang}}/my-courses/course/{{$item->id}}"
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
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            (function ($) {
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

