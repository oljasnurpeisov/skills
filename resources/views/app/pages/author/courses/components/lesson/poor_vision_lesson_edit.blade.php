@if($course->is_poor_vision == true)
    <div id="poorVision" @if($course->is_poor_vision == true) style="display: block" @else style="display: none" @endif>
        <h3 class="title-tertiary">{{__('default.pages.courses.is_vision_version')}}</h3>
    {{--    <div class="form-group">--}}
    {{--        <label class="form-group__label">{{__('default.pages.lessons.lesson_video_link_1')}}</label>--}}
    {{--        @if($item->lesson_attachment->videos_poor_vision_link != null)--}}
    {{--            <input type="url" name="videos_poor_vision_link[]" placeholder=""--}}
    {{--                   class="input-regular"--}}
    {{--                   value="{{json_decode($item->lesson_attachment->videos_poor_vision_link)[0]}}"--}}
    {{--                   id="courseVideo1">--}}
    {{--        @else--}}
    {{--            <input type="url" name="videos_poor_vision_link[]" placeholder=""--}}
    {{--                   class="input-regular"--}}
    {{--                   value="" id="courseVideo1">--}}
    {{--        @endif--}}
    {{--    </div>--}}
    {{--    <div class="removable-items">--}}
    {{--        @if($item->lesson_attachment->videos_poor_vision_link != null)--}}
    {{--            @foreach(array_slice(json_decode($item->lesson_attachment->videos_poor_vision_link),1) as $video_poor_vision_link)--}}
    {{--                <div class="form-group">--}}
    {{--                    <div class="input-addon">--}}
    {{--                        <input type="url" name="videos_poor_vision_link[]" placeholder=""--}}
    {{--                               class="input-regular"--}}
    {{--                               value="{{$video_poor_vision_link}}">--}}
    {{--                        <div class="addon">--}}
    {{--                            <div class="btn-icon small icon-close"></div>--}}
    {{--                        </div>--}}

    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            @endforeach--}}
    {{--        @endif--}}
    {{--    </div>--}}
    {{--    <div class="text-right pull-up">--}}
    {{--        <a href="#" title="{{__('default.pages.courses.add_btn_title')}}" class="add-btn"--}}
    {{--           data-duplicate="courseVideo1"--}}
    {{--           data-maxcount="4"><span--}}
    {{--                    class="add-btn__title">{{__('default.pages.courses.add_btn_title')}}</span><span--}}
    {{--                    class="btn-icon small icon-plus"> </span></a>--}}
    {{--    </div>--}}
    {{--    <div class="form-group">--}}
    {{--        <label class="form-group__label">{{__('default.pages.courses.video_local_1')}}</label>--}}
    {{--        <div data-url="/ajax_upload_course_videos?_token={{ csrf_token() }}"--}}
    {{--             data-maxfiles="5"--}}
    {{--             data-maxsize="500" data-acceptedfiles=".mp4" id="video2"--}}
    {{--             class="dropzone-default dropzone-multiple">--}}
    {{--            <input type="hidden" name="localVideo1" value="">--}}
    {{--            <div class="dropzone-default__info">MP4--}}
    {{--                • {{__('default.pages.courses.max_file_title')}} 500MB--}}
    {{--            </div>--}}
    {{--            <div class="previews-container">--}}
    {{--                @if($item->lesson_attachment->videos_poor_vision != null)--}}
    {{--                    @foreach(json_decode($item->lesson_attachment->videos_poor_vision) as $video_poor_vision)--}}
    {{--                        <div class="dz-preview dz-image-preview dz-stored">--}}
    {{--                            <div class="dz-details">--}}
    {{--                                <input type="text" name="localVideoStored1[]"--}}
    {{--                                       value="{{{$video_poor_vision}}}" placeholder="">--}}
    {{--                                <div class="dz-filename"><span--}}
    {{--                                            data-dz-name="">{{substr(basename($video_poor_vision), 14)}}</span>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <a href="javascript:undefined;"--}}
    {{--                               title="{{__('default.pages.courses.delete')}}"--}}
    {{--                               class="link red">{{__('default.pages.courses.delete')}}</a>--}}
    {{--                            <a href="javascript:undefined;"--}}
    {{--                               title="{{__('default.pages.courses.reestablish')}}"--}}
    {{--                               class="link green"--}}
    {{--                               style="display:none;">{{__('default.pages.courses.reestablish')}}</a>--}}
    {{--                        </div>--}}
    {{--                    @endforeach--}}
    {{--                @endif--}}
    {{--            </div>--}}
    {{--            <a href="javascript:;"--}}
    {{--               title="{{__('default.pages.courses.add_file_btn_title')}}"--}}
    {{--               class="dropzone-default__link">{{__('default.pages.courses.add_file_btn_title')}}</a>--}}
    {{--        </div>--}}
    {{--    </div>--}}
        <div class="form-group">
            <label class="form-group__label">{{__('default.pages.courses.course_audio_1')}}*</label>
            <div data-url="/ajax_upload_course_audios?_token={{ csrf_token() }}"
                 data-maxfiles="5"
                 data-required="true"
                 data-maxsize="10" data-acceptedfiles=".mp3" id="audio2"
                 class="dropzone-default dropzone-multiple">
                <input type="text" name="localAudio1">
                <input name="localAudio1-req" type="text" class="req" required>
                <div class="dropzone-default__info">MP3
                    • {{__('default.pages.courses.max_file_title')}} 10MB
                </div>
                <div class="previews-container">
                    @if($item->lesson_attachment->audios_poor_vision != null)
                        @foreach(json_decode($item->lesson_attachment->audios_poor_vision) as $audio_poor_vision)
                            <div class="dz-preview dz-image-preview dz-stored">
                                <div class="dz-details">
                                    <input type="text" name="localAudioStored1[]" value="{{$audio_poor_vision}}" placeholder="">
                                    <div class="dz-filename">
                                        <span data-dz-name="">{{substr(basename($audio_poor_vision), 14)}}</span>
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
            <label class="form-group__label">{{__('default.pages.lessons.another_lesson_attachments_1')}}*</label>
            <div data-url="/ajax_upload_lesson_another_files?_token={{ csrf_token() }}" data-maxfiles="20"
                 data-maxsize="20"
                 data-required="true"
                 data-acceptedfiles=".pdf, .doc, .xls, .ppt, .docx, .xlsx, .pptx, .png, .jpg, .rar"
                 id="documents-dropzone"
                 class="dropzone-default dropzone-multiple">
                <input type="text" name="localDocuments1">
                <input name="localDocuments1-req" type="text" class="req" required>
                <div class="dropzone-default__info">PDF, DOC, XLS, PPT, DOCX, XLSX, PPTX, PNG, JPG • {{__('default.pages.courses.max_file_title')}} 20
                    MB
                </div>
                <div class="previews-container">
                    @if($item->lesson_attachment->another_files_poor_vision != null)
                        @foreach(json_decode($item->lesson_attachment->another_files_poor_vision) as $file)
                            <div class="dz-preview dz-image-preview dz-stored">
                                <div class="dz-details">
                                    <input type="text" name="localDocumentsStored1[]"
                                           value="{{$file}}"
                                           placeholder="">
                                    <div class="dz-filename"><span
                                                data-dz-name="">{{substr(basename($file), 14)}}</span>
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
    </div>
@endif
