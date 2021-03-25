@if($item->is_poor_vision == true)
    <div class="sidebar">
        <div class="sidebar__inner">
            <div class="poor-vision">
                <div><label class="checkbox"><input type="checkbox" data-toggle="poorVision"
                                                    name="poorVision"><span><span
                                    class="special">{{__('default.pages.lessons.poor_vision_title')}}</span></span></label>
                </div>
                <div id="poorVision" style="display:none;">
                    <div class="hidden-xs hidden-sm">
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
                            <div class="sidebar-item__title">{{__('default.pages.lessons.font')}}
                                :
                            </div>
                            <div class="sidebar-item__body">
                                <select name="fontFamily" class="selectize-regular custom"
                                        placeholder="{{__('default.pages.lessons.choose_font')}}">
                                    <option value="">{{__('default.pages.lessons.default_title')}}</option>
                                    <option value="Arial, sans-serif">Arial</option>
                                    <option value="Times New Roman, sans-serif">Times New
                                        Roman
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="sidebar-item">
                            <div class="sidebar-item__title">{{__('default.pages.lessons.color_palette')}}
                                :
                            </div>
                            <div class="sidebar-item__body">
                                <select name="colorScheme"
                                        class="selectize-regular custom color"
                                        placeholder="{{__('default.pages.lessons.choose_palette')}}">
                                    <option value="black-white">{{__('default.pages.lessons.default_title')}}</option>
                                    <option value="white-black">{{__('default.pages.lessons.white_on_black_palette')}}</option>
                                    <option value="yellow-blue">{{__('default.pages.lessons.yellow_on_blue_palette')}}</option>
                                </select>
                            </div>
                        </div>
                        @if(!empty($item->attachments->videos_poor_vision_link))
                            <div class="sidebar-item">
                                <div class="sidebar-item__title">{{__('default.pages.courses.media_attachments')}}
                                    :
                                </div>
                                <div class="sidebar-item__body">
                                    @foreach(json_decode($item->attachments->videos_poor_vision_link) as $video_link)
                                        @if($video_link !== null)
                                            @php
                                                $video_id = \App\Extensions\YoutubeParse::parseYoutube($video_link);
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
                                    @if(!empty($item->attachments->videos_poor_vision))
                                        @foreach(json_decode($item->attachments->videos_poor_vision) as $video)
                                            <div class="video-wrapper">
                                                <video controls
                                                       src="{{$video}}"></video>
                                            </div>
                                        @endforeach
                                    @endif
                                    @if(!empty($item->attachments->audios_poor_vision))
                                        @foreach(json_decode($item->attachments->audios_poor_vision) as $audio)
                                            <audio controls
                                                   src="{{$audio}}"></audio>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if(!empty($item->attachments->another_files_poor_vision))
                            <div class="sidebar-item">
                                <div class="sidebar-item__title">{{__('default.pages.lessons.lesson_files')}}
                                    :
                                </div>
                                <div class="sidebar-item__body">
                                    <div class="plain-text">
                                        <ul>
                                            @if(!empty($item->attachments->another_files_poor_vision))
                                                @foreach(json_decode($item->attachments->another_files_poor_vision) as $file)
                                                    <li>
                                                        <a href="{{env('APP_URL').$file}}"
                                                           title="{{substr(basename($file), 14)}}"
                                                           target="_blank">{{substr(basename($file), 14)}}
                                                            &nbsp;</a>
                                                        ({{ round(File::size(public_path($file))/1000000, 1) }}
                                                        MB)
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if($item->is_poor_hearing == true)
    <div class="sidebar">
        <div class="sidebar__inner">
            <div class="poor-vision">
                <div><label class="checkbox"><input type="checkbox" data-toggle="poorHearing"
                                                    name="poorHearing"><span><span
                                    class="special">{{__('default.pages.lessons.poor_hearing_title')}}</span></span></label>
                </div>
                <div id="poorHearing" style="display:none;">
                    <div class="hidden-xs hidden-sm">
                        @if(!empty($item->attachments->videos_poor_hearing_link))
                            <div class="sidebar-item">
                                <div class="sidebar-item__title">{{__('default.pages.courses.media_attachments')}}
                                    :
                                </div>
                                <div class="sidebar-item__body">
                                    @foreach(json_decode($item->attachments->videos_poor_hearing_link) as $video_link)
                                        @if($video_link !== null)
                                            @php
                                                $video_id = \App\Extensions\YoutubeParse::parseYoutube($video_link);
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
                                    @if(!empty($item->attachments->videos_poor_hearing))
                                        @foreach(json_decode($item->attachments->videos_poor_hearing) as $video)
                                            <div class="video-wrapper">
                                                <video controls
                                                       src="{{$video}}"></video>
                                            </div>
                                        @endforeach
                                    @endif
                                    @if(!empty($item->attachments->audios_poor_hearing))
                                        @foreach(json_decode($item->attachments->audios_poor_hearing) as $audio)
                                            <audio controls
                                                   src="{{$audio}}"></audio>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if(!empty($item->attachments->another_files_poor_hearing))
                            <div class="sidebar-item">
                                <div class="sidebar-item__title">{{__('default.pages.lessons.lesson_files')}}
                                    :
                                </div>
                                <div class="sidebar-item__body">
                                    <div class="plain-text">
                                        <ul>
                                            @if(!empty($item->attachments->another_files_poor_hearing))
                                                @foreach(json_decode($item->attachments->another_files_poor_hearing) as $file)
                                                    <li>
                                                        <a href="{{env('APP_URL').$file}}"
                                                           title="{{substr(basename($file), 14)}}"
                                                           target="_blank">{{substr(basename($file), 14)}}
                                                            &nbsp;</a>
                                                        ({{ round(File::size(public_path($file))/1000000, 1) }}
                                                        MB)
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
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
                    @if(!empty($item->attachments->videos_link))
                        @foreach(json_decode($item->attachments->videos_link) as $video_link)
                            @if(!empty($video_link))
                                @php
                                    $video_id = \App\Extensions\YoutubeParse::parseYoutube($video_link);
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
                    @if(!empty($item->attachments->videos))
                        @foreach(json_decode($item->attachments->videos) as $video)
                            <div class="video-wrapper">
                                <video controls
                                       src="{{$video}}"></video>
                            </div>
                        @endforeach
                    @endif
                    @if(!empty($item->attachments->audios))
                        @foreach(json_decode($item->attachments->audios) as $audio)
                            <audio controls
                                   src="{{$audio}}"></audio>
                        @endforeach
                    @endif
                </div>
            </div>
            @if(!empty($item->attachments->another_files))
                <div class="sidebar-item">
                    <div class="sidebar-item__title">{{__('default.pages.lessons.lesson_files')}}
                        :
                    </div>
                    <div class="sidebar-item__body">
                        <div class="plain-text">
                            <ul>
                                @if(!empty($item->attachments->another_files))
                                    @foreach(json_decode($item->attachments->another_files) as $file)
                                        <li>
                                            <a href="{{env('APP_URL').$file}}"
                                               title="{{substr(basename($file), 14)}}"
                                               target="_blank">{{substr(basename($file), 14)}}
                                                &nbsp;</a>
                                            ({{ round(File::size(public_path($file))/1000000, 1) }}
                                            MB)
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            <div class="sidebar-item">
                <div class="sidebar-item__title">{{__('default.pages.courses.course_lang')}}:
                </div>
                <div class="sidebar-item__body">
                    <div class="plain-text">
                        @if($item->lang == 0)
                            Қазақша
                        @elseif($item->lang == 1)
                            Русский
                        @endif
                    </div>
                </div>
            </div>
            <div class="sidebar-item">
                <div class="sidebar-item__title">{{__('default.pages.courses.course_include')}}:
                </div>
                <div class="sidebar-item__body">
                    <div class="plain-text">
                        <ul>
                            <li>{{__('default.pages.courses.lessons_title')}}
                                : {{$item->lessons->whereIn('type', [1,2])->count()}} </li>
                            <li>{{__('default.pages.courses.videos_count')}}: {{$videos_count}}</li>
                            <li>{{__('default.pages.courses.audios_count')}}
                                : {{$audios_count}}  </li>
                            <li>{{__('default.pages.courses.attachments_count')}}
                                : {{$attachments_count}}</li>
                            {{--                                                <li>70,5 часов видео</li>--}}
                            <li>{{__('default.pages.courses.tests_count_title')}}
                                : {{$item->lessons->whereIn('type', [2])->where('end_lesson_type', '=', 0)->count()}}</li>
                            <li>{{__('default.pages.courses.homeworks_count')}}
                                : {{$item->lessons->where('end_lesson_type', '=', 1)->where('type', '=', 2)->count()}}</li>
                            @if(!empty($item->courseWork()))
                                <li>{{__('default.pages.courses.coursework_title')}}</li>
                            @endif
                            @if(!empty($item->finalTest()))
                                <li>{{__('default.pages.courses.final_test_title')}}</li>
                            @endif
                            {{--                                                <li>8 интерактивных задач</li>--}}
                            {{--                                                <li>1 статья</li>--}}
                            <li>{{__('default.pages.courses.mobile_access_title')}}</li>
                            {{--                                                <li>10 файлов</li>--}}
                            <li>{{__('default.pages.courses.certificate_access_title')}}</li>
                        </ul>
                        <div class="hint gray">{{__('default.pages.courses.last_updates')}}
                            : {{\App\Extensions\FormatDate::formatDate($item->updated_at->format("d.m.Y"))}}</div>
                    </div>
                </div>
            </div>
            <div class="sidebar-item">
                <div class="sidebar-item__title">{{__('default.pages.courses.professional_area_title')}}
                    :
                </div>
                <div class="sidebar-item__body">
                    <div class="extendable">
                        <div class="tags">
                            <ul>
                                @foreach($item->professional_areas->groupBy('id') as $key => $profession_area)
                                    <li><a href=""
                                           title="{{$profession_area[0]->getAttribute('name_'.$lang) ?? $profession_area[0]->getAttribute('name_ru')}}">{{$profession_area[0]->getAttribute('name_'.$lang) ?? $profession_area[0]->getAttribute('name_ru')}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <a href="javascript:;" title="{{__('default.pages.courses.show_all')}}"
                       class="link small text-center" data-maxheight="300"
                       data-alternativetitle="{{__('default.pages.courses.hide')}}"
                       style="display:none;">{{__('default.pages.courses.show_all')}}</a>
                </div>

            </div>
            <div class="sidebar-item">
                <div class="sidebar-item__title">{{__('default.pages.courses.profession_title')}}:
                </div>
                <div class="sidebar-item__body">
                    <div class="extendable">
                        <div class="tags">
                            <ul>
                                @foreach($item->professions->groupBy('id') as $key => $profession)
                                    <li><a href="/{{$lang}}/course-catalog?professions[]={{$key}}"
                                           title="{{$profession[0]->getAttribute('name_'.$lang) ?? $profession[0]->getAttribute('name_ru')}}">{{$profession[0]->getAttribute('name_'.$lang) ?? $profession[0]->getAttribute('name_ru')}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <a href="javascript:;" title="{{__('default.pages.courses.show_all')}}"
                       class="link small text-center" data-maxheight="300"
                       data-alternativetitle="{{__('default.pages.courses.hide')}}"
                       style="display:none;">{{__('default.pages.courses.show_all')}}</a>
                </div>

            </div>
            <div class="sidebar-item">
                <div class="sidebar-item__title">{{__('default.pages.courses.skill_title')}}:
                </div>
                <div class="sidebar-item__body">
                    <div class="extendable">
                        <div class="tags">
                            <ul>
                                @foreach($item->skills->groupBy('id') as $key => $skill)
                                    <li><a href="/{{$lang}}/course-catalog?skills[]={{$key}}"
                                           title="{{$skill[0]->getAttribute('name_'.$lang) ?? $skill[0]->getAttribute('name_ru')}}">{{$skill[0]->getAttribute('name_'.$lang) ?? $skill[0]->getAttribute('name_ru')}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <a href="javascript:;" title="{{__('default.pages.courses.show_all')}}"
                       class="link small text-center" data-maxheight="300"
                       data-alternativetitle="{{__('default.pages.courses.hide')}}"
                       style="display:none;">{{__('default.pages.courses.show_all')}}</a>
                </div>
            </div>
            @if(empty($student_course) or ($student_course->paid_status == 0))
                <div class="sidebar-item">
                    <div class="sidebar-item__title"></div>
                    <div class="sidebar-item__body">
                        <div class="price">
                            @if($item->is_paid == 1)
                                <div
                                        class="price__value">{{number_format($item->cost, 0, ',', ' ')}}
                                    ₸
                                </div>
                            @else
                                <div class="price__value">{{__('default.pages.courses.free_title')}}
                                </div>
                            @endif
                            @if($item->quota_status == 2)
                                <div class="price__quota"><span
                                            class="mark mark--yellow">{{__('default.pages.courses.access_by_quota')}}</span>*
                                </div>
                                <div class="hint gray">
                                    * {{__('default.pages.courses.access_by_quota_desc')}}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @if(empty($student_course) or ($student_course->paid_status == 0))
        <div class="sidebar__buttons">
            @if($item->quota_status == 2)
                @guest
                    <a href="#studentAuth" data-fancybox
                       title="{{__('default.pages.courses.get_by_quota')}}"
                       class="sidebar-btn ghost">{{__('default.pages.courses.get_by_quota')}}</a>
                @endguest
                @auth
                    @if(Auth::user()->hasRole('student'))
                        <a href="#quotaConfirm" data-fancybox
                           title="{{__('default.pages.courses.get_by_quota')}}"
                           class="sidebar-btn ghost">{{__('default.pages.courses.get_by_quota')}}</a>
                    @endif
                @endauth
            @endif
            @if($item->is_paid == 0)
                @guest
                    <a href="#studentAuth" data-fancybox
                       title="{{__('default.pages.courses.get_free')}}"
                       class="sidebar-btn ghost">{{__('default.pages.courses.get_free')}}</a>
                @endguest
                @auth
                    @if(Auth::user()->hasRole('student'))
                        <a href="#buyConfirm" data-fancybox
                           title="{{__('default.pages.courses.get_free')}}"
                           class="sidebar-btn ghost">{{__('default.pages.courses.get_free')}}</a>
                    @endif
                @endauth
            @else
                @guest
                    <a href="#studentAuth" data-fancybox
                       title="{{__('default.pages.courses.buy_course')}}"
                       class="sidebar-btn">{{__('default.pages.courses.buy_course')}}</a>
                @endguest
                @auth
                    @if(Auth::user()->hasRole('student'))
                        <a href="#buyConfirm" data-fancybox
                           title="{{__('default.pages.courses.buy_course')}}"
                           class="sidebar-btn">{{__('default.pages.courses.buy_course')}}</a>
                    @endif
                @endauth
            @endif
        </div>
    @endif
</div>