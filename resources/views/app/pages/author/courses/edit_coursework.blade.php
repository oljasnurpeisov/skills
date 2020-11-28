@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="plain">
            <div class="container">
                <ul class="breadcrumbs">
                    <li><a href="/{{$lang}}/my-courses/"
                           title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
                    </li>
                    <li><a href="/{{$lang}}/my-courses/course/{{$course->id}}"
                           title="{{$course->name}}">{{$course->name}}</a>
                    <li><span>{{__('default.pages.courses.edit_coursework_title')}}</span></li>
                </ul>
                <h1 class="title-primary">{{__('default.pages.courses.edit_coursework_title')}}</h1>

                <div class="row row--multiline">
                    <div class="col-md-8">
                        <form action="/{{$lang}}/my-courses/course/{{$course->id}}/edit-coursework" method="POST" enctype="multipart/form-data">
                           @csrf
                            <div class="row row--multiline">
                                <div class="col-md-8">
                                    <div class="form-group" id="durationField">
                                        <label class="form-group__label">{{__('default.pages.lessons.duration_title')}} *</label>
                                        <input type="number" name="duration" placeholder="" class="input-regular" value="{{$item->duration}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-group__label">{{__('default.pages.lessons.lesson_image')}}</label>
                                        <div class="avatar lesson-image dropzone-avatar" id="lessonCover"
                                             data-url="/ajax_upload_lesson_image?_token={{ csrf_token() }}" data-maxsize="1"
                                             data-acceptedfiles="image/*">
                                            <input type="hidden" name="image" class="avatar-path" value="{{$item->image}}">
                                            <div class="lesson-image__preview">
                                                <img src="{{$item->getAvatar()}}" data-defaultsrc="/assets/img/lesson-thumbnail.jpg"
                                                     class="avatar-preview" alt="">
                                            </div>
                                            <div class="lesson-image__desc dropzone-default">
                                                <div class="previews-container">
                                                    <div class="dz-preview dz-image-preview">
                                                        <div class="dz-details">
                                                            <div class="dz-filename"><span data-dz-name="">{{basename($item->image)}}</span></div>
                                                            <div class="dz-size" data-dz-size=""><strong>0.1</strong> MB</div>
                                                        </div>
                                                        <a href="javascript:undefined;" title="{{__('default.pages.courses.delete')}}" class="author-picture__link red" data-dz-remove="">{{__('default.pages.courses.delete')}}</a>
                                                    </div>
                                                </div>
                                                <div class="dropzone-default__info">PNG, JPG • {{__('default.pages.courses.max_file_title')}} 1MB</div>
                                                <div class="lesson-image__link avatar-pick dropzone-default__link">{{__('default.pages.courses.choose_photo')}}
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
                                <textarea name="theory" class="input-regular tinymce-text-here" required>
                            {{$item->theory}}
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
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.courses.video_local')}}</label>
                                <div data-url="/ajax_upload_lesson_videos?_token={{ csrf_token() }}"
                                     data-maxfiles="5"
                                     data-maxsize="50" data-acceptedfiles=".mp4" id="video2"
                                     class="dropzone-default dropzone-multiple">
                                    <input type="hidden" name="localVideo" value="">
                                    <div class="dropzone-default__info">MP4
                                        • {{__('default.pages.courses.max_file_title')}} 50MB
                                    </div>
                                    <div class="previews-container">
                                        @if($item->lesson_attachment->videos != null)
                                            @foreach(json_decode($item->lesson_attachment->videos) as $video)
                                                <div class="dz-preview dz-image-preview dz-stored">
                                                    <div class="dz-details">
                                                        <input type="text" name="localVideoStored[]"
                                                               value="{{$video}}" placeholder="">
                                                        <div class="dz-filename"><span
                                                                    data-dz-name="">{{basename($video)}}</span>
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
                                       title="{{__('default.pages.courses.add_file_btn_title')}}{{__('default.pages.courses.add_file_btn_title')}}"
                                       class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                </div>
                            </div>
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
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.lessons.another_lesson_attachments')}}</label>
                                <div data-url="/ajax_upload_lesson_another_files?_token={{ csrf_token() }}" data-maxfiles="20"
                                     data-maxsize="20"
                                     data-acceptedfiles=".pdf, .doc, .xls, .ppt, .docx, .xlsx, .pptx, .png, .jpg, .rar, .zip, .7z, .mp3, .mp4, .avi, .mov"
                                     id="documents-dropzone"
                                     class="dropzone-default dropzone-multiple">
                                    <input type="text" name="localDocuments" value="">
                                    <div class="dropzone-default__info">PDF, DOC, XLS, PPT, DOCX, XLSX, PPTX, PNG, JPG,
                                        RAR,
                                        ZIP, 7z, MP3, MP4, AVI, MOV • {{__('default.pages.courses.max_file_title')}} 20
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
                                                                    data-dz-name="">{{basename($file)}}</span>
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
                            @if($course->is_poor_vision == true)
                                @include('app.pages.author.courses.components.lesson.poor_vision_lesson_edit',['item' => $item])
                            @endif
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.lessons.coursework_task')}} *</label>
                                <textarea name="coursework_task" class="input-regular tinymce-here" required>
                            {{$item->coursework_task}}
                        </textarea>
                            </div>
                            <div class="buttons">
                                <button type="submit" class="btn">{{__('default.pages.courses.save_title')}}</button>
                                <a href="/{{$lang}}/my-courses/course/{{$course->id}}" title="{{__('default.pages.courses.cancel_title')}}" class="ghost-btn">{{__('default.pages.courses.cancel_title')}}</a>
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

    <!---->
@endsection

