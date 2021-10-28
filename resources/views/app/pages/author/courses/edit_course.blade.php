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
                    <li><a href="/{{$lang}}/my-courses/course/{{$item->id}}" title="{{$item->name}}">{{$item->name}}</a>
                    </li>
                    <li><span>{{__('default.pages.courses.edit_course')}}</span></li>
                </ul>
                <h1 class="title-primary">{{__('default.pages.courses.edit_course')}}</h1>
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('failed'))
                    <div class="alert alert-danger">
                        {!! session('failed') !!}
                    </div>
                @endif
                <div class="row row--multiline">
                    <div class="col-md-8">
                        <form id="form" action="/{{$lang}}/my-courses/edit-course/{{$item->id}}" method="POST"
                              enctype="multipart/form-data">
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
                                    <input type="text" name="name" placeholder="" value="{{$item->name}}"
                                           class="input-regular" required>
                                    <div class="addon">
                                        <span class="required">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="professions-container" id="professionsContainer">
                                <div class="form-group">
                                    <label class="form-group__label"
                                           id="skillsLabel">{{__('default.pages.courses.professional_area_title')}}</label>
                                    <div class="input-addon">
                                        <select name="professional_areas[]" id="professionalAreasSelect"
                                                placeholder="{{__('default.pages.courses.choose_professional_area_title')}}"
                                                data-method="getProfessionalAreaByName" data-maxitems="2"
                                                class="professional-areas-select" multiple required>
                                            @foreach($item->professional_areas->unique('id') as $area)
                                                <option value="{{ $area->id }}" selected="selected">{{ $area->getAttribute('name_'.$lang) ?? $area->getAttribute('name_ru') }}</option>
                                            @endforeach
{{--                                            <option value="{{$item->professional_areas[0]->id}}"--}}
{{--                                                    selected="selected">{{$item->professional_areas[0]->getAttribute('name_'.$lang) ?? $item->professions[0]->group_professions[0]->getAttribute('name_ru')}}</option>--}}
                                        </select>
                                        <div class="addon">
                                            <span class="required">*</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label"
                                           id="professionsLabel">{{__('default.pages.courses.profession_title')}}</label>
                                    <div class="input-addon">
                                        <select name="professions[]" id="professionsSelect"
                                                placeholder="{{__('default.pages.courses.choose_profession_title')}}"
                                                data-method="getProfessionsByData" data-maxitems=3"
                                                class="professions-select" multiple required>
                                            @foreach($item->professions->unique('id') as $profession)
                                                <option value="{{ $profession->id }}" selected="selected">{{ $profession->getAttribute('name_'.$lang) ?? $profession->getAttribute('name_ru') }}</option>
                                            @endforeach
{{--                                            <option value="{{$item->professions[0]->id}}"--}}
{{--                                                    selected="selected">{{$item->professions[0]->getAttribute('name_'.$lang) ?? $item->professions[0]->group_professions[0]->getAttribute('name_ru')}}</option>--}}
                                        </select>
                                        <div class="addon">
                                            <span class="required">*</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label"
                                           id="skillsLabel">{{__('default.pages.courses.skills_title')}}</label>
                                    <select name="skills[]" id="skillsSelect"
                                            placeholder="{{__('default.pages.courses.choose_skills_title')}}"
                                            data-method="getSkillsByData" data-maxitems="7"
                                            class="skills-select" multiple required>
                                        @foreach($current_skills as $skill)
                                            <option value="{{$skill->id}}" selected="selected">{{$skill->getAttribute('name_'.$lang) ?? $skill->getAttribute('name_ru')}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_lang')}}</label>
                                <div class="input-addon">
                                    <select name="lang" placeholder="{{__('default.pages.courses.choose_lang')}}"
                                            class="selectize-regular" required>
                                        @php($languages = [["Қазақша","0"], ["Русский","1"]])
                                        @foreach($languages as $key => $language)
                                            <option value="{{ $language[1] }}"
                                                    @if($language[1]==$item->lang) selected='selected' @endif >{{ $language[0] }}</option>
                                        @endforeach
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
                                                <input type="radio" name="is_paid" value="false" data-toggle="paidFormgroup,quota_status" {{ ($item->is_paid === 0 ? ' checked' : '') }}><span>{{__('default.pages.courses.free_title') }}</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="radio">
                                                <input type="radio" name="is_paid" value="true" data-toggle="paidFormgroup,quota_status" {{ ($item->is_paid === 1 ? ' checked' : '') }}><span>{{__('default.pages.courses.paid_title') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6" id="quota_status" @if ($item->is_paid === 0) style="display:none;" @endif>
                                        <label class="checkbox">
                                            <input type="checkbox" name="quota_status" data-toggle="top_profession" value="true" {{ ($item->quota_status === 2 ? ' checked' : '') }}><span>{{ __('default.pages.courses.quota_title') }}</span>
                                        </label>
{{--                                        <div class="text-right" id="top_profession" style="display:none;">--}}
{{--                                            <a href="/assets/data/top100.pdf" target="_blank" title="{{__('default.top_100_title')}}" class="add-btn">--}}
{{--                                                <span class="add-btn__title">{{__('default.top_100_title')}}</span>--}}
{{--                                            </a>--}}
{{--                                        </div>--}}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="paidFormgroup" @if ($item->is_paid === 0) style="display:none;" @endif>
                                <label class="form-group__label">{{__('default.pages.courses.course_cost')}}</label>
                                <div class="input-addon">
                                    <input type="text" name="cost" placeholder="" value="{{ $item->cost }}"
                                           class="input-regular" required @if ($item->is_paid === 0) disabled @endif onfocus="$(this).inputmask('currency', {prefix: '',groupSeparator: ' ',rightAlign: false, digits: 0})">
                                    <div class="addon">
                                        <span class="required">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.availability_title') }}</label>
                                <div class="row">
{{--                                    <div class="col-sm-6">--}}
{{--                                        <label class="checkbox">--}}
{{--                                            <input type="checkbox" name="is_paid" value="true" data-toggle="paidFormgroup" {{ ($item->is_paid == true ? ' checked' : '') }}>--}}
{{--                                            <span>{{__('default.pages.courses.is_paid')}}  ({{__('default.pages.courses.default_free')}})</span>--}}
{{--                                        </label>--}}
{{--                                    </div>--}}
                                    <div class="col-sm-6">
                                        <label class="checkbox"><input type="checkbox" name="is_access_all" value="true" {{ ($item->is_access_all == true ? ' checked' : '') }}>
                                            <span>{{__('default.pages.courses.is_access_all')}}</span>
                                        </label>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="checkbox"><input type="checkbox" name="is_poor_vision" value="true" data-toggle="poorVision" {{ ($item->is_poor_vision == true ? ' checked' : '') }}>
                                            <span>{{__('default.pages.courses.is_vision_version')}}</span>
                                        </label>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="checkbox"><input type="checkbox" name="is_poor_hearing" value="true" data-toggle="poorHearing" {{ ($item->is_poor_hearing == true ? ' checked' : '') }}>
                                            <span>{{__('default.pages.courses.is_poor_hearing')}}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_profit')}} *</label>
                                <textarea name="profit_desc" class="input-regular tinymce-text-here"
                                          placeholder="{{__('default.pages.courses.course_profit_placeholder')}}"
                                          required>
                            {{$item->profit_desc}}
                        </textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_teaser')}} *</label>
                                <textarea name="teaser" class="input-regular tinymce-text-here" required>
                            {{$item->teaser}}
                        </textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_desc')}} *</label>
                                <textarea name="description" class="input-regular tinymce-text-here"
                                          placeholder="{{__('default.pages.courses.course_description_placeholder')}}"
                                          required>
                            {{$item->description}}
                        </textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_image')}}</label>
                                <div class="avatar course-image dropzone-avatar" id="courseCover"
                                     data-url="/ajax_upload_course_image?_token={{ csrf_token() }}" data-maxsize="1"
                                     data-acceptedfiles="image/*">
                                    <img src="{{ $item->image ?? "/assets/img/course-thumbnail.jpg" }}"
                                         data-defaultsrc="/assets/img/course-thumbnail.jpg"
                                         class="course-image__preview avatar-preview" alt="">
                                    <div class="course-image__desc dropzone-default">
                                        <input type="text" name="image" class="avatar-path" value="{{ $item->image }}">
                                        @if($item->image)
                                            <div class="previews-container">
                                                <div class="dz-preview dz-image-preview">
                                                    <div class="dz-details">
                                                        <div class="dz-filename">
                                                            <span data-dz-name="">{{ basename($item->image) }}</span>
                                                        </div>
                                                        <div class="dz-size" data-dz-size="">
                                                            @if(file_exists(public_path($item->image)))
                                                                <strong>{{ $item->image ? round(filesize(public_path($item->image)) / 1024) : 0 }}</strong>
                                                                KB
                                                            @else
                                                                <strong>0</strong> KB
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <a href="javascript:undefined;"
                                                       title="{{__('default.pages.courses.delete')}}"
                                                       class="author-picture__link red"
                                                       data-dz-remove="">{{__('default.pages.courses.delete')}}</a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="previews-container"></div>
                                        @endif
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
                                            <div class="alert alert-danger"><span data-dz-errormessage> </span>
                                            </div>
                                            <a href="javascript:undefined;"
                                               title="{{__('default.pages.courses.delete')}}"
                                               class="author-picture__link red"
                                               data-dz-remove>{{__('default.pages.courses.delete')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.video_link')}}</label>
                                @if($item->attachments->videos_link != null)
                                    <input type="url" name="videos_link[]" placeholder="" class="input-regular"
                                           value="{{json_decode($item->attachments->videos_link)[0]}}" id="courseVideo">
                                @else
                                    <input type="url" name="videos_link[]" placeholder="" class="input-regular"
                                           value="" id="courseVideo">
                                @endif

                            </div>-->
                            <!--<div class="removable-items">
                                @if($item->attachments->videos_link != null)
                                    @foreach(array_slice(json_decode($item->attachments->videos_link),1) as $video_link)
                                        <div class="form-group">
                                            <div class="input-addon">
                                                <input type="url" name="videos_link[]" placeholder=""
                                                       class="input-regular" value="{{$video_link}}">
                                                <div class="addon">
                                                    <div class="btn-icon small icon-close"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>-->
                            <!--<div class="text-right pull-up">
                                <a href="#" title="{{__('default.pages.courses.add_btn_title')}}" class="add-btn"
                                   data-duplicate="courseVideo"
                                   data-maxcount="4"><span
                                        class="add-btn__title">{{__('default.pages.courses.add_btn_title')}}</span><span
                                        class="btn-icon small icon-plus"> </span></a>
                            </div>-->
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
                            {{--                                    @if($item->attachments->videos != null)--}}
                            {{--                                        <div class="previews-container">--}}
                            {{--                                            @foreach(json_decode($item->attachments->videos) as $video)--}}
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
                            {{--                                        </div>--}}
                            {{--                                    @endif--}}
                            {{--                                    <a href="javascript:;"--}}
                            {{--                                       title="{{__('default.pages.courses.add_file_btn_title')}}"--}}
                            {{--                                       class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>--}}
                            {{--                                    @if($item->attachments->videos == null)--}}
                            {{--                                        <div class="previews-container"></div>--}}
                            {{--                                    @endif--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            <!--<div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.course_audio')}}</label>
                                <div data-url="/ajax_upload_course_audios?_token={{ csrf_token() }}" data-maxfiles="5"
                                     data-maxsize="10" data-acceptedfiles=".mp3" id="audio"
                                     class="dropzone-default dropzone-multiple">
                                    <input type="text" name="localAudio" value="">
                                    <div class="dropzone-default__info">MP3
                                        • {{__('default.pages.courses.max_file_title')}} 10MB
                                    </div>
                                    @if($item->attachments->audios != null)
                                        <div class="previews-container">
                                            @foreach(json_decode($item->attachments->audios) as $audio)
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
                                        </div>
                                    @endif
                                    <a href="javascript:;" title="{{__('default.pages.courses.add_file_btn_title')}}"
                                       class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                    @if($item->attachments->audios == null)
                                        <div class="previews-container"></div>
                                    @endif
                                </div>
                            </div>-->
                            <div id="poorVision" @if($item->is_poor_vision == true) style="display: block"
                                 @else style="display: none" @endif>
                                <h3 class="title-tertiary">{{__('default.pages.courses.is_vision_version')}}</h3>
{{--                                <div class="form-group">--}}
{{--                                    <label class="form-group__label">{{__('default.pages.courses.video_link_1')}}</label>--}}
{{--                                    @if($item->attachments->videos_poor_vision_link != null)--}}
{{--                                        <input type="url" name="videos_poor_vision_link[]" placeholder=""--}}
{{--                                               class="input-regular"--}}
{{--                                               value="{{json_decode($item->attachments->videos_poor_vision_link)[0]}}"--}}
{{--                                               id="courseVideo1">--}}
{{--                                    @else--}}
{{--                                        <input type="url" name="videos_poor_vision_link[]" placeholder=""--}}
{{--                                               class="input-regular"--}}
{{--                                               value="" id="courseVideo1">--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                                <div class="removable-items">--}}
{{--                                    @if($item->attachments->videos_poor_vision_link != null)--}}
{{--                                        @foreach(array_slice(json_decode($item->attachments->videos_poor_vision_link),1) as $video_poor_vision_link)--}}
{{--                                            <div class="form-group">--}}
{{--                                                <div class="input-addon">--}}
{{--                                                    <input type="url" name="videos_poor_vision_link[]" placeholder=""--}}
{{--                                                           class="input-regular" value="{{$video_poor_vision_link}}">--}}
{{--                                                    <div class="addon">--}}
{{--                                                        <div class="btn-icon small icon-close"></div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        @endforeach--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                                <div class="text-right pull-up">--}}
{{--                                    <a href="#" title="{{__('default.pages.courses.add_btn_title')}}" class="add-btn"--}}
{{--                                       data-duplicate="courseVideo1" data-maxcount="4"><span--}}
{{--                                            class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span--}}
{{--                                            class="btn-icon small icon-plus"> </span></a>--}}
{{--                                </div>--}}
                                {{--                                <div class="form-group">--}}
                                {{--                                    <label class="form-group__label">{{__('default.pages.courses.video_local_1')}}</label>--}}
                                {{--                                    <div data-url="/ajax_upload_course_videos?_token={{ csrf_token() }}"--}}
                                {{--                                         data-maxfiles="5"--}}
                                {{--                                         data-maxsize="500" data-acceptedfiles=".mp4" id="video2"--}}
                                {{--                                         class="dropzone-default dropzone-multiple">--}}
                                {{--                                        <input type="hidden" name="localVideo1" value="">--}}
                                {{--                                        <div class="dropzone-default__info">MP4--}}
                                {{--                                            • {{__('default.pages.courses.max_file_title')}} 500MB--}}
                                {{--                                        </div>--}}
                                {{--                                        @if($item->attachments->videos_poor_vision != null)--}}
                                {{--                                            <div class="previews-container">--}}
                                {{--                                                @foreach(json_decode($item->attachments->videos_poor_vision) as $video_poor_vision)--}}
                                {{--                                                    <div class="dz-preview dz-image-preview dz-stored">--}}
                                {{--                                                        <div class="dz-details">--}}
                                {{--                                                            <input type="text" name="localVideoStored1[]"--}}
                                {{--                                                                   value="{{{$video_poor_vision}}}" placeholder="">--}}
                                {{--                                                            <div class="dz-filename"><span--}}
                                {{--                                                                        data-dz-name="">{{substr(basename($video_poor_vision), 14)}}</span>--}}
                                {{--                                                            </div>--}}
                                {{--                                                        </div>--}}
                                {{--                                                        <a href="javascript:undefined;"--}}
                                {{--                                                           title="{{__('default.pages.courses.delete')}}"--}}
                                {{--                                                           class="link red">{{__('default.pages.courses.delete')}}</a>--}}
                                {{--                                                        <a href="javascript:undefined;"--}}
                                {{--                                                           title="{{__('default.pages.courses.reestablish')}}"--}}
                                {{--                                                           class="link green"--}}
                                {{--                                                           style="display:none;">{{__('default.pages.courses.reestablish')}}</a>--}}
                                {{--                                                    </div>--}}
                                {{--                                                @endforeach--}}
                                {{--                                            </div>--}}
                                {{--                                        @endif--}}
                                {{--                                        <a href="javascript:;"--}}
                                {{--                                           title="{{__('default.pages.courses.add_file_btn_title')}}"--}}
                                {{--                                           class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>--}}
                                {{--                                        @if($item->attachments->videos_poor_vision == null)--}}
                                {{--                                            <div class="previews-container"></div>--}}
                                {{--                                        @endif--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.courses.course_audio_1')}}*</label>
                                    <div class="input-addon">
                                        <div data-url="/ajax_upload_course_audios?_token={{ csrf_token() }}"
                                             data-maxfiles="5"
                                             data-required="true"
                                             data-maxsize="10" data-acceptedfiles=".mp3" id="audio2"
                                             class="dropzone-default dropzone-multiple">
                                            <input type="hidden" name="localAudio1" value="">
                                            <input name="req" type="text" class="req" required @if($item->is_poor_vision != true) disabled @endif>
                                            <div class="dropzone-default__info">MP3
                                                • {{__('default.pages.courses.max_file_title')}} 10MB
                                            </div>
                                            @if($item->attachments->audios_poor_vision != null)
                                                <div class="previews-container">
                                                    @foreach(json_decode($item->attachments->audios_poor_vision) as $audio_poor_vision)
                                                        <div class="dz-preview dz-image-preview dz-stored">
                                                            <div class="dz-details">
                                                                <input type="text" name="localAudioStored1[]"
                                                                       value="{{$audio_poor_vision}}" placeholder="">
                                                                <div class="dz-filename"><span
                                                                        data-dz-name="">{{substr(basename($audio_poor_vision), 14)}}</span>
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
                                                </div>
                                            @endif
                                            <a href="javascript:;"
                                               title="{{__('default.pages.courses.add_file_btn_title')}}"
                                               class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                            @if($item->attachments->audios_poor_vision == null)
                                                <div class="previews-container"></div>
                                            @endif
                                        </div>
                                        <div class="addon">
                                            <span class="required">*</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div id="poorHearing" @if($item->is_poor_hearing == true) style="display: block"
                                 @else style="display: none" @endif>
                                <h3 class="title-tertiary">{{__('default.pages.courses.is_poor_hearing')}}</h3>
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.courses.video_link_2')}}*</label>
                                    <div class="input-addon">
                                        @if($item->attachments->videos_poor_hearing_link != null)
                                            <input type="url" name="videos_poor_hearing_link[]" placeholder=""
                                                   class="input-regular"
                                                   value="{{json_decode($item->attachments->videos_poor_hearing_link)[0]}}"
                                                   id="courseVideo2" required disabled>
                                        @else
                                            <input type="url" name="videos_poor_hearing_link[]" placeholder=""
                                                   class="input-regular"
                                                   value="" id="courseVideo2" required disabled>
                                        @endif
                                        <div class="addon">
                                            <span class="required">*</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="removable-items">
                                    @if($item->attachments->videos_poor_hearing_link != null)
                                        @foreach(array_slice(json_decode($item->attachments->videos_poor_hearing_link),1) as $video_poor_hearing_link)
                                            <div class="form-group">
                                                <div class="input-addon">
                                                    <input type="url" name="videos_poor_hearing_link[]" placeholder=""
                                                           class="input-regular" value="{{$video_poor_hearing_link}}">
                                                    <div class="addon">
                                                        <div class="btn-icon small icon-close"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="text-right pull-up">
                                    <a href="#" title="{{__('default.pages.courses.add_btn_title')}}" class="add-btn"
                                       data-duplicate="courseVideo2"
                                       data-maxcount="4"><span
                                            class="add-btn__title">{{__('default.pages.courses.add_btn_title')}}</span><span
                                            class="btn-icon small icon-plus"> </span></a>
                                </div>
                                {{--                                <div class="form-group">--}}
                                {{--                                    <label class="form-group__label">{{__('default.pages.courses.video_local_2')}}</label>--}}
                                {{--                                    <div data-url="/ajax_upload_course_videos?_token={{ csrf_token() }}"--}}
                                {{--                                         data-maxfiles="5"--}}
                                {{--                                         data-maxsize="500" data-acceptedfiles=".mp4" id="video2"--}}
                                {{--                                         class="dropzone-default dropzone-multiple">--}}
                                {{--                                        <input type="hidden" name="localVideo2" value="">--}}
                                {{--                                        <div class="dropzone-default__info">MP4--}}
                                {{--                                            • {{__('default.pages.courses.max_file_title')}} 500MB--}}
                                {{--                                        </div>--}}
                                {{--                                        @if($item->attachments->videos_poor_hearing != null)--}}
                                {{--                                            <div class="previews-container">--}}
                                {{--                                                @foreach(json_decode($item->attachments->videos_poor_hearing) as $video_poor_hearing)--}}
                                {{--                                                    <div class="dz-preview dz-image-preview dz-stored">--}}
                                {{--                                                        <div class="dz-details">--}}
                                {{--                                                            <input type="text" name="localVideoStored2[]"--}}
                                {{--                                                                   value="{{{$video_poor_hearing}}}" placeholder="">--}}
                                {{--                                                            <div class="dz-filename"><span--}}
                                {{--                                                                        data-dz-name="">{{substr(basename($video_poor_hearing), 14)}}</span>--}}
                                {{--                                                            </div>--}}
                                {{--                                                        </div>--}}
                                {{--                                                        <a href="javascript:undefined;"--}}
                                {{--                                                           title="{{__('default.pages.courses.delete')}}"--}}
                                {{--                                                           class="link red">{{__('default.pages.courses.delete')}}</a>--}}
                                {{--                                                        <a href="javascript:undefined;"--}}
                                {{--                                                           title="{{__('default.pages.courses.reestablish')}}"--}}
                                {{--                                                           class="link green"--}}
                                {{--                                                           style="display:none;">{{__('default.pages.courses.reestablish')}}</a>--}}
                                {{--                                                    </div>--}}
                                {{--                                                @endforeach--}}
                                {{--                                            </div>--}}
                                {{--                                        @endif--}}
                                {{--                                        <a href="javascript:;"--}}
                                {{--                                           title="{{__('default.pages.courses.add_file_btn_title')}}"--}}
                                {{--                                           class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}--}}
                                {{--                                        </a>--}}
                                {{--                                        @if($item->attachments->videos_poor_hearing == null)--}}
                                {{--                                            <div class="previews-container"></div>--}}
                                {{--                                        @endif--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                {{--                                <div class="form-group">--}}
                                {{--                                    <label class="form-group__label">{{__('default.pages.courses.course_audio_2')}}</label>--}}
                                {{--                                    <div data-url="/ajax_upload_course_audios?_token={{ csrf_token() }}"--}}
                                {{--                                         data-maxfiles="5"--}}
                                {{--                                         data-maxsize="10" data-acceptedfiles=".mp3" id="audio2"--}}
                                {{--                                         class="dropzone-default dropzone-multiple">--}}
                                {{--                                        <input type="hidden" name="localAudio2" value="">--}}
                                {{--                                        <div class="dropzone-default__info">MP3--}}
                                {{--                                            • {{__('default.pages.courses.max_file_title')}} 10MB--}}
                                {{--                                        </div>--}}
                                {{--                                        @if($item->attachments->audios_poor_hearing != null)--}}
                                {{--                                            <div class="previews-container">--}}
                                {{--                                                @foreach(json_decode($item->attachments->audios_poor_hearing) as $audio_poor_hearing)--}}
                                {{--                                                    <div class="dz-preview dz-image-preview dz-stored">--}}
                                {{--                                                        <div class="dz-details">--}}
                                {{--                                                            <input type="text" name="localAudioStored2[]"--}}
                                {{--                                                                   value="{{$audio_poor_hearing}}" placeholder="">--}}
                                {{--                                                            <div class="dz-filename"><span--}}
                                {{--                                                                        data-dz-name="">{{substr(basename($audio_poor_hearing), 14)}}</span>--}}
                                {{--                                                            </div>--}}
                                {{--                                                        </div>--}}
                                {{--                                                        <a href="javascript:undefined;"--}}
                                {{--                                                           title="{{__('default.pages.courses.delete')}}"--}}
                                {{--                                                           class="link red">{{__('default.pages.courses.delete')}}</a>--}}
                                {{--                                                        <a href="javascript:undefined;"--}}
                                {{--                                                           title="{{__('default.pages.courses.reestablish')}}"--}}
                                {{--                                                           class="link green"--}}
                                {{--                                                           style="display:none;">{{__('default.pages.courses.reestablish')}}</a>--}}
                                {{--                                                    </div>--}}
                                {{--                                                @endforeach--}}
                                {{--                                            </div>--}}
                                {{--                                        @endif--}}
                                {{--                                        <a href="javascript:;"--}}
                                {{--                                           title="{{__('default.pages.courses.add_file_btn_title')}}"--}}
                                {{--                                           class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}--}}
                                {{--                                        </a>--}}
                                {{--                                        @if($item->attachments->audios_poor_hearing == null)--}}
                                {{--                                            <div class="previews-container"></div>--}}
                                {{--                                        @endif--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.preview_certificate_title')}}</label>
                                <input name="certificate_id" type="hidden" value="{{$item->certificate_id}}">
                                <div class="row row--multiline">
{{--                                    <div class="col-auto">--}}
{{--                                        <div class="image-choice">--}}
{{--                                            <img src="/assets/img/certificates/4.png"--}}
{{--                                                 class="image-choice__thumbnail"--}}
{{--                                                 alt="">--}}
{{--                                            <label class="image-choice__overflow">--}}
{{--                                                <i> </i>--}}
{{--                                                <a href="/assets/img/certificates/4.png" data-fancybox title="{{__('default.pages.courses.zoom_certificate')}}"--}}
{{--                                                   class="icon-zoom-in" style="margin-top: 20px"> </a>--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <div class="col-auto">
                                        <div class="image-choice {{ $item->certificate_id ==  '11' ? 'checked' : ''  }} " data-id="11">
                                            <img src="/assets/img/certificates/cert_new_011.jpg"
                                                 class="image-choice__thumbnail"
                                                 alt="">
                                            <label class="image-choice__overflow">
                                                <i> </i>
                                                {{--                                                <a class="check" style="margin-top: -22px;">✓</a>--}}
                                                <a href="/assets/img/certificates/cert_new_011.jpg" data-fancybox title="{{__('default.pages.courses.zoom_certificate')}}"
                                                   class="icon-zoom-in" style="margin-top: 23px"> </a>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <div class="image-choice {{ $item->certificate_id ==  '12' ? 'checked' : ''  }} " data-id="12">
                                            <img src="/assets/img/certificates/cert_new_012.jpg"
                                                 class="image-choice__thumbnail"
                                                 alt="">
                                            <label class="image-choice__overflow">
                                                <i> </i>
                                                {{--                                                <a class="check" style="margin-top: -22px;">✓</a>--}}
                                                <a href="/assets/img/certificates/cert_new_012.jpg" data-fancybox title="{{__('default.pages.courses.zoom_certificate')}}"
                                                   class="icon-zoom-in" style="margin-top: 23px"> </a>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <div class="image-choice {{ $item->certificate_id ==  '13' ? 'checked' : ''  }} " data-id="13">
                                            <img src="/assets/img/certificates/cert_new_013.jpg"
                                                 class="image-choice__thumbnail"
                                                 alt="">
                                            <label class="image-choice__overflow">
                                                <i> </i>
                                                {{--                                                <a class="check" style="margin-top: -22px;">✓</a>--}}
                                                <a href="/assets/img/certificates/cert_new_013.jpg" data-fancybox title="{{__('default.pages.courses.zoom_certificate')}}"
                                                   class="icon-zoom-in" style="margin-top: 23px"> </a>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <div class="image-choice {{ $item->certificate_id ==  '10' ? 'checked' : ''  }} " data-id="10">
                                            <img src="/assets/img/certificates/cert_new_010.jpg"
                                                 class="image-choice__thumbnail"
                                                 alt="">
                                            <label class="image-choice__overflow">
                                                <i> </i>
                                                {{--                                                <a class="check" style="margin-top: -22px;">✓</a>--}}
                                                <a href="/assets/img/certificates/cert_new_010.jpg" data-fancybox title="{{__('default.pages.courses.zoom_certificate')}}"
                                                   class="icon-zoom-in" style="margin-top: 23px"> </a>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons">
                                <button type="submit"
                                        class="btn">{{__('default.pages.profile.save_btn_title')}}</button>
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

    <style>
        .image-choice.checked::after {
            content: "";
            position: absolute;
            top: -10px;
            right: -11px;
            height: 25px;
            width: 25px;
            background: url(/assets/img/checked.png);
            background-repeat: no-repeat;
            background-image: cover;
        }
        .image-choice {
            width: 9rem;
        }
    </style>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script>
        $('.image-choice').on('click', function () {
            $('.image-choice').removeClass('checked');
            let block = $(this);
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
                // specialitySelect.clear();
                // skillsSelect.clear();
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
                // professionAreaSelect.clear();
                setTimeout(function () {
                    professionAreaSelect.removeMessage();
                }, 3000);
            } else if (type === 1) {
                professionAreaSelect.update($(this).val() ? {"professions": toArray($(this).val())} : null);
                skillsSelect.update($(this).val() ? {"professions": toArray($(this).val())} : null);
                // skillsSelect.clear();
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
                // professionAreaSelect.clear();
                // specialitySelect.clear();
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
        $('#form').on('submit', function () {
            if ($('input[name="is_paid"]:checked').val() === "true") {
                if (parseInt($('input[name="cost"]').val()) === 0) {
                    let html = '<div class="alert alert-danger cost-danger"><ul><li>{{ __('validation.course_cost') }}</li></ul></div>';

                    $('h1.title-primary').after(html);
                    $('html, body').animate({scrollTop: '0px'}, 300);

                    return false

                } else {
                    $('.cost-danger').remove();
                }
            }
        })
    </script>
    <!---->
@endsection

