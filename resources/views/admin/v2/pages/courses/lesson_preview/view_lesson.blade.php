@extends('admin.v2.layout.course.template')

@section('content')
    <div class="container">
        <div><a href="javascript:history.back();" title="Назад" class="link">Назад</a></div>
        <br/>
        <div class="row row--multiline">
            <div class="col-md-8">
                <div class="article">
                    @if($lesson->type == 3)
                        <h1 class="page-title">{{$lesson->lesson_type->getAttribute('name_'.$lang) ?? $lesson->lesson_type->getAttribute('name_ru')}}</h1>
                    @elseif($lesson->type == 4)
                        <h1 class="page-title">{{$lesson->lesson_type->getAttribute('name_'.$lang) ?? $lesson->lesson_type->getAttribute('name_ru')}}</h1>
                    @else
                        <h1 class="page-title">{{$lesson->name}}</h1>
                    @endif
                    <div class="article__info">
                        <span><i class="icon-lesson"></i> {{$lesson->lesson_type->getAttribute('name_'.$lang) ?? $lesson->lesson_type->getAttribute('name_ru')}}</span>
                        <span><i class="icon-clock"></i> {{$time .' '. __('default.pages.lessons.hour_short_title')}} </span>
                    </div>
                    @if($lesson->image !== null)
                        <div class="article__image">
                            <img src="{{ $lesson->image }}" alt="">
                        </div>
                    @endif
                    <div class="plain-text">
                        {!! $lesson->theory !!}
                    </div>
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
                                                    placeholder="Выберите палитру">
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
                                                    <li>
                                                        <a href="{{env('APP_URL').$file}}" title="{{basename($file)}}"
                                                           target="_blank">{{basename($file)}}&nbsp;</a>
                                                        ({{ round(File::size(public_path($file))/1000000, 1) }} MB)
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
                                                    <li>
                                                        <a href="{{env('APP_URL').$file}}" title="{{basename($file)}}"
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
                    <form action="/{{$lang}}/course_{{$item->id}}/author_lesson_finished_{{$lesson->id}}"
                          method="POST">
                        @csrf
                        <div class="sidebar__buttons">
                            @if($lesson->type == 1)
                                <button type="submit" class="sidebar-btn disabled" name="action"
                                        value="next_lesson">{{__('default.pages.lessons.next_lesson')}}</button>
                            @elseif($lesson->type == 2 and $lesson->end_lesson_type == 1)
                                <button type="submit" class="sidebar-btn" name="action"
                                        value="homework">{{__('default.pages.lessons.get_task_btn')}}</button>
                            @elseif($lesson->type == 2 and $lesson->end_lesson_type == 0)
                                <button type="submit" class="sidebar-btn" name="action"
                                        value="test">{{__('default.pages.lessons.get_test_btn')}}</button>
                            @elseif($lesson->type == 3)
                                <button type="submit" class="sidebar-btn" name="action"
                                        value="coursework">{{__('default.pages.lessons.get_task_btn')}}</button>
                            @elseif($lesson->type == 4)
                                <button type="submit" class="sidebar-btn" name="action"
                                        value="final-test">{{__('default.pages.lessons.get_test_btn')}}</button>
                            @endif
                        </div>
                    </form>
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

