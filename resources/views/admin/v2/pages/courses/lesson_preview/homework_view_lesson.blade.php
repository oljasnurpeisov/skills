@extends('admin.v2.layout.course.template')

@section('content')
    <div class="container">
        <div><a href="javascript:history.back();" title="{{__('admin.pages.courses.back_title')}}" class="link">{{__('admin.pages.courses.back_title')}}</a></div>
        <br/>
        <div class="row row--multiline">
            <div class="col-md-8">
                <div class="article">
                    @if($lesson->type == 3)
                        <h1 class="page-title">{{__('default.pages.lessons.coursework_title')}}</h1>
                    @else
                        <h1 class="page-title">{{__('default.pages.lessons.homework_title')}}</h1>
                    @endif
                    <div class="plain-text">
                        {!! $lesson->practice !!}
                    </div>
                    <hr>
                    <h2 class="title-secondary">{{__('default.pages.lessons.answer_title')}}</h2>
                    <form action="/{{$lang}}/admin/course-{{$item->id}}/lesson-{{$lesson->id}}/admin-homework-submit"
                          method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-group__label">{{__('default.pages.lessons.answer_text_title')}}
                                *</label>
                            <textarea name="answer" class="input-regular tinymce-here" required></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-group__label">{{__('default.pages.courses.video_local')}}</label>
                            <div data-url="/ajax_upload_lesson_videos?_token={{ csrf_token() }}"
                                 data-maxfiles="5"
                                 data-maxsize="50" data-acceptedfiles=".mp4" id="video"
                                 class="dropzone-default dropzone-multiple">
                                <input type="hidden" name="videos" value="">
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
                            <label class="form-group__label">{{__('default.pages.lessons.lesson_audio')}}</label>
                            <div data-url="/ajax_upload_lesson_audios?_token={{ csrf_token() }}"
                                 data-maxfiles="5"
                                 data-maxsize="10" data-acceptedfiles=".mp3" id="audio"
                                 class="dropzone-default dropzone-multiple">
                                <input type="hidden" name="audios" value="">
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
                            <label class="form-group__label">{{__('default.pages.lessons.another_lesson_attachments')}}</label>
                            <div data-url="/ajax_upload_lesson_another_files?_token={{ csrf_token() }}"
                                 data-maxfiles="20"
                                 data-maxsize="20"
                                 data-acceptedfiles=".pdf, .doc, .xls, .ppt, .docx, .xlsx, .pptx, .png, .jpg, .rar, .zip, .7z, .mp3, .mp4, .avi, .mov"
                                 id="documents-dropzone"
                                 class="dropzone-default dropzone-multiple">
                                <input type="hidden" name="another_files" value="">
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
                        <div class="buttons">
                            <button type="submit"
                                    class="btn">{{__('default.pages.lessons.send_answer_title')}}</button>
                            <a href="{{ url()->previous() }}"
                               title="{{__('default.pages.courses.cancel_title')}}"
                               class="ghost-btn">{{__('default.pages.courses.cancel_title')}}</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                @if($item->is_poor_vision == true)
                    <div class="sidebar">
                        <div class="sidebar__inner">
                            <div class="poor-vision">
                                <div><label class="checkbox"><input type="checkbox"
                                                                    data-toggle="poorVision,regularMaterials,poorVisionMaterials"
                                                                    name="poorVision"><span><span
                                                    class="special">{{__('default.pages.lessons.poor_vision_title')}}</span></span></label>
                                </div>
                                <div id="poorVision" style="display:none;">
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title">{{__('default.pages.lessons.font_size_title')}}
                                            :
                                        </div>
                                        <div class="sidebar-item__body">
                                            <div class="range-slider-wrapper">
                                                <input type="range" class="range-slider single-range-slider"
                                                       name="fontSize" min="12"
                                                       data-decimals="0" step="1" max="32" value="24">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title">{{__('default.pages.lessons.line_spacing')}}
                                            :
                                        </div>
                                        <div class="sidebar-item__body">
                                            <div class="range-slider-wrapper">
                                                <input type="range" class="range-slider single-range-slider"
                                                       name="lineHeight" min="1"
                                                       data-decimals="1" step="0.5" max="3" value="2">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title">{{__('default.pages.lessons.font')}}:
                                        </div>
                                        <div class="sidebar-item__body">
                                            <select name="fontFamily" class="selectize-regular custom"
                                                    placeholder="Выберите шрифт">
                                                <option value="">{{__('default.pages.lessons.default_title')}}</option>
                                                <option value="Arial, sans-serif">Arial</option>
                                                <option value="Times New Roman, sans-serif">Times New Roman
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title">{{__('default.pages.lessons.color_palette')}}
                                            :
                                        </div>
                                        <div class="sidebar-item__body">
                                            <select name="colorScheme" class="selectize-regular custom color"
                                                    placeholder="{{__('default.pages.lessons.choose_palette')}}">
                                                <option value="black-white">{{__('default.pages.lessons.default_title')}}</option>
                                                <option value="white-black">{{__('default.pages.lessons.white_on_black_palette')}}</option>
                                                <option value="yellow-blue">{{__('default.pages.lessons.yellow_on_blue_palette')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="sidebar">
                    <div class="sidebar__inner">
                        <div id="regularMaterials">
                            <div class="sidebar-item">
                                <div class="sidebar-item__title">{{__('default.pages.courses.media_attachments')}}
                                    :
                                </div>
                                <div class="sidebar-item__body">
                                    @if(!empty($lesson->lesson_attachment->videos_link))
                                        @foreach(json_decode($lesson->lesson_attachment->videos_link) as $video_link)
                                            @php
                                                $video_id = explode("?v=", $video_link);
                                                $video_id = $video_id[1];
                                            @endphp
                                            <div class="video-wrapper">
                                                <iframe width="560" height="315"
                                                        src="https://www.youtube.com/embed/{{$video_id}}"
                                                        frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                        allowfullscreen></iframe>
                                            </div>
                                        @endforeach
                                    @endif
                                    @if(!empty($lesson->lesson_attachment->videos))
                                        @foreach(json_decode($lesson->lesson_attachment->videos) as $video)
                                            <div class="video-wrapper">
                                                <video controls
                                                       src="{{$video}}"></video>
                                            </div>
                                        @endforeach
                                    @endif
                                    @if(!empty($lesson->lesson_attachment->audios))
                                        @foreach(json_decode($lesson->lesson_attachment->audios) as $audio)
                                            <audio controls
                                                   src="{{$audio}}"></audio>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="sidebar-item">
                                <div class="sidebar-item__title">{{__('default.pages.lessons.lesson_files')}}:
                                </div>
                                <div class="sidebar-item__body">
                                    <div class="plain-text">
                                        <ul>
                                            @if(!empty($lesson->lesson_attachment->another_files))
                                                @foreach(json_decode($lesson->lesson_attachment->another_files) as $file)
                                                    <li><a href="{{env('APP_URL').$file}}"
                                                           title="{{basename($file)}}"
                                                           target="_blank">{{basename($file)}}&nbsp;</a>
                                                        ({{ round(File::size(public_path($file))/1000000, 1) }}
                                                        MB)
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="poorVisionMaterials" style="display:none;">
                            <div class="sidebar-item">
                                <div class="sidebar-item__title">{{__('default.pages.lessons.media_attachments_poor_vision')}}
                                    :
                                </div>
                                <div class="sidebar-item__body">
                                    @if(!empty($lesson->lesson_attachment->videos_poor_vision_link))
                                        @foreach(json_decode($lesson->lesson_attachment->videos_poor_vision_link) as $video_link)
                                            @if($video_link !== null)
                                                @php
                                                    $video_id = explode("?v=", $video_link);
                                                    $video_id = $video_id[1] ?? null;
                                                @endphp
                                                <div class="video-wrapper">
                                                    <iframe width="560" height="315"
                                                            src="https://www.youtube.com/embed/{{$video_id}}"
                                                            frameborder="0"
                                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                            allowfullscreen></iframe>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    @if(!empty($lesson->lesson_attachment->videos_poor_vision))
                                        @foreach(json_decode($lesson->lesson_attachment->videos_poor_vision) as $video)
                                            <div class="video-wrapper">
                                                <video controls
                                                       src="{{$video}}"></video>
                                            </div>
                                        @endforeach
                                    @endif
                                    @if(!empty($lesson->lesson_attachment->audios_poor_vision))
                                        @foreach(json_decode($lesson->lesson_attachment->audios_poor_vision) as $audio)
                                            <audio controls
                                                   src="{{$audio}}"></audio>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="sidebar-item">
                                <div class="sidebar-item__title">{{__('default.pages.lessons.lesson_files_poor_vision')}}
                                    :
                                </div>
                                <div class="sidebar-item__body">
                                    <div class="plain-text">
                                        <ul>
                                            @if(!empty($lesson->lesson_attachment->another_files_poor_vision))
                                                @foreach(json_decode($lesson->lesson_attachment->another_files_poor_vision) as $file)
                                                    <li><a href="{{env('APP_URL').$file}}"
                                                           title="{{basename($file)}}"
                                                           target="_blank">{{basename($file)}}
                                                            &nbsp;</a>({{ round(File::size(public_path($file))/1000000, 1) }}
                                                        MB)
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script src="/assets/js/visually-impaired-tools.js"></script>
    <!---->
@endsection

