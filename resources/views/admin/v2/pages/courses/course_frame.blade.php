@extends('admin.v2.layout.course.template')

@section('content')
    <div class="container">
        <div class="row row--multiline">
            <div class="col-md-8">
                <div class="article">
                    <div class="article-section">
                        <h1 class="page-title">{{$item->name}}</h1>
                        <div class="plain-text">{!! $item->teaser !!}</div>
                        <div class="text-right">
                            <div class="attributes">
                                <div class="attributes-item">
                                    <i class="icon-user"> </i>
                                    <span>{{count($item->course_members->whereIn('paid_status', [1,2]))}}</span>
                                </div>
                                <div class="attributes-item">
                                    <i class="icon-star-full"> </i>
                                    <span>{{round($item->rate->pluck('rate')->avg() ?? 0, 1)}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="article-section">
                        <h2 class="title-secondary">{{__('default.pages.courses.profit_title')}}</h2>
                        <div class="plain-text">
                            {!! $item->profit_desc !!}
                        </div>
                    </div>
                    <div class="article-section">
                        <h2 class="title-secondary">{{__('default.pages.courses.course_materials')}}</h2>
                        <div class="article__info">
                                    <span>{{__('default.pages.courses.lessons_title_1')}}: <span
                                                id="lessonsCount">{{$item->lessons->whereIn('type', [1,2])->count()}}</span></span>
                            <span>{{__('default.pages.courses.total_time_lessons')}}: <span
                                        id="courseDuration">{{\App\Extensions\FormatDate::convertMunitesToTime($item->lessons->whereIn('type', [1,2])->sum('duration'))}}</span> {{__('default.pages.courses.hours_title')}}</span>
                        </div>

                        <div class="course">
                            <div class="course" id="courseDataContainer">
                                @foreach($item->themes->sortBy('index_number') as $theme)
                                    <div class="topic spoiler">
                                        <div class="topic__header">
                                            <div class="title">{{$theme->name}}</div>
                                            <div class="duration">{{\App\Extensions\FormatDate::convertMunitesToTime($item->lessons->where('theme_id', '=', $theme->id)->sum('duration'))}}</div>
                                        </div>
                                        <div class="topic__body">
                                            @foreach($theme->lessons->sortBy('index_number') as $lesson)
                                                <div class="lesson">
                                                    @if($lesson->type != 1)
                                                        <div class="title"><a
                                                                    href="/{{$lang}}/admin/moderator-course-iframe-{{$item->id}}/lesson-{{$lesson->id}}"
                                                                    title="{{$lesson->name}}">{{$lesson->name}}
                                                                <div class="type">{{$lesson->lesson_type->getAttribute('name_'.$lang) ?? $lesson->lesson_type->getAttribute('name_ru')}}
                                                                    {{$lesson->end_lesson_type == 0 ? ' ('.__('default.pages.lessons.test_title').')' : ' ('.__('default.pages.lessons.homework_title').')'}}</div>
                                                            </a></div>
                                                        <div class="duration">{{\App\Extensions\FormatDate::convertMunitesToTime($lesson->duration)}}</div>
                                                    @else
                                                        <div class="title"><a
                                                                    href="/{{$lang}}/admin/moderator-course-iframe-{{$item->id}}/lesson-{{$lesson->id}}"
                                                                    title="{{$lesson->name}}">{{$lesson->name}}
                                                                <div class="type">{{$lesson->lesson_type->getAttribute('name_'.$lang) ?? $lesson->lesson_type->getAttribute('name_ru')}}</div>
                                                            </a></div>
                                                        <div class="duration">{{\App\Extensions\FormatDate::convertMunitesToTime($lesson->duration)}}</div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($item->courseWork() !== null)
                                <div class="topic">
                                    <div class="topic__header">
                                        <div class="title"><a
                                                    href="/{{$lang}}/admin/moderator-course-iframe-{{$item->id}}/lesson-{{$item->courseWork()->id}}">{{__('default.pages.lessons.coursework_title')}}</a>
                                        </div>
                                        <div class="duration"></div>
                                    </div>
                                </div>
                            @endif
                            @if($item->finalTest() !== null)
                                <div class="topic">
                                    <div class="topic__header">
                                        <div class="title"><a
                                                    href="/{{$lang}}/admin/moderator-course-iframe-{{$item->id}}/lesson-{{$item->finalTest()->id}}">{{__('default.pages.courses.final_test_title')}}</a>
                                        </div>
                                        <div class="duration"></div>

                                    </div>
                                </div>
                            @endif

                        </div>

                        <div id="addTopicModal" style="display:none;">
                            <h4 class="title-primary text-center">{{__('default.pages.courses.theme_name')}}</h4>
                            <div class="form-group">
                                <input type="text" name="topicName" id="newTopicNameInput" placeholder=""
                                       class="input-regular" required>
                            </div>
                            <div class="row row--multiline justify-center">
                                <div class="col-auto">
                                    <a href="#" title="{{__('default.pages.courses.create')}}" class="btn"
                                       id="addTopicBtn">{{__('default.pages.courses.create')}}</a>
                                </div>
                                <div class="col-auto">
                                    <a href="#" title="{{__('default.pages.courses.cancel_title')}}"
                                       class="ghost-btn"
                                       data-fancybox-close>{{__('default.pages.courses.cancel_title')}}</a>
                                </div>
                            </div>
                        </div>

                        <div id="editTopicModal" style="display:none;">
                            <h4 class="title-primary text-center">{{__('default.pages.courses.edit_theme_title')}}</h4>
                            <div class="form-group">
                                <input type="text" name="editTopicName" id="editTopicNameInput" placeholder=""
                                       class="input-regular">
                            </div>
                            <div class="row row--multiline justify-center">
                                <div class="col-auto">
                                    <a href="#" title="{{__('default.pages.courses.save_title')}}" class="btn"
                                       id="editTopicBtn">{{__('default.pages.courses.save_title')}}</a>
                                </div>
                                <div class="col-auto">
                                    <a href="#" title="{{__('default.pages.courses.cancel_title')}}"
                                       class="ghost-btn"
                                       data-fancybox-close>{{__('default.pages.courses.cancel_title')}}</a>
                                </div>
                            </div>
                        </div>

                        <div id="removeTopicModal" style="display:none;">
                            <h4 class="title-primary text-center">{{__('default.pages.courses.confirm_modal_title')}}</h4>
                            <div class="plain-text gray">{{__('default.pages.courses.confirm_theme_modal_desc')}}</div>
                            <div class="row row--multiline justify-center">
                                <div class="col-auto">
                                    <a href="#" title="{{__('default.pages.courses.delete_title')}}" class="btn"
                                       id="removeTopicBtn">{{__('default.pages.courses.delete_title')}}</a>
                                </div>
                                <div class="col-auto">
                                    <a href="#" title="{{__('default.pages.courses.cancel_tile')}}"
                                       class="ghost-btn"
                                       data-fancybox-close>{{__('default.pages.courses.cancel_tile')}}</a>
                                </div>
                            </div>
                        </div>

                        <div id="removeLessonModal" style="display:none;">
                            <h4 class="title-primary text-center">{{__('default.pages.courses.confirm_modal_title')}}</h4>
                            <div class="plain-text gray">{{__('default.pages.courses.confirm_lesson_modal_desc')}}</div>
                            <div class="row row--multiline justify-center">
                                <div class="col-auto">
                                    <a href="#" title="{{__('default.pages.courses.delete_title')}}" class="btn"
                                       id="removeLessonBtn">{{__('default.pages.courses.delete_title')}}</a>
                                </div>
                                <div class="col-auto">
                                    <a href="#" title="{{__('default.pages.courses.cancel_tile')}}"
                                       class="ghost-btn"
                                       data-fancybox-close>{{__('default.pages.courses.cancel_tile')}}</a>
                                </div>
                            </div>
                        </div>

                        <div id="modalMsg" style="display:none;">
                            <div class="text-center">
                                <h4 class="title-primary text-center"></h4>
                                <div class="plain-text gray"></div>
                                <button data-fancybox-close
                                        class="btn">{{__('default.pages.courses.close_title')}}</button>
                            </div>
                        </div>
                    </div>
                    <div class="article-section">
                        <h2 class="title-secondary">{{__('default.pages.courses.course_description')}}</h2>
                        <div class="plain-text">{!! $item->description !!}</div>
                    </div>
                    <div class="article-section">
                        <h2 class="title-secondary">{{__('default.pages.courses.author_title')}}</h2>
                        <div class="personal-card">
                            <div class="personal-card__left">
                                <div class="personal-card__image">
                                    <img src="{{ $item->user->author_info->getAvatar()  }}" alt="">
                                </div>
                                <ul class="socials">
                                    @if(!empty($item->user->author_info->site_url))
                                        <li><a href="{{$item->user->author_info->site_url}}" title=""
                                               class="icon-language"> </a></li>
                                    @endif
                                    @if(!empty($item->user->author_info->vk_link))
                                        <li><a href="{{$item->user->author_info->vk_link}}" title=""
                                               class="icon-vk"> </a></li>
                                    @endif
                                    @if(!empty($item->user->author_info->fb_link))
                                        <li><a href="{{$item->user->author_info->fb_link}}" title=""
                                               class="icon-facebook"> </a></li>
                                    @endif
                                    @if(!empty($item->user->author_info->instagram_link))
                                        <li><a href="{{$item->user->author_info->instagram_link}}" title=""
                                               class="icon-instagram"> </a></li>
                                    @endif
                                </ul>
                            </div>
                            <div class="personal-card__right">
                                <div class="personal-card__name">{{ $item->user->author_info->name . ' ' . $item->user->author_info->surname  }}</div>
                                <div class="personal-card__gray-text">{{ implode(', ', json_decode($item->user->author_info->specialization) ?? []) }}</div>
                                <div class="plain-text">
                                    {!! $item->user->author_info->about !!}
                                </div>
                                <div class="personal-card__characteristics">
                                    <div>
                                        <span class="blue">{{count($rates)}}</span> {{__('default.pages.profile.rates_count_title')}}
                                    </div>
                                    <div>
                                        <span class="blue">{{count($author_students)}}</span> {{__('default.pages.profile.course_members_count')}}
                                    </div>
                                    <div>
                                        <span class="blue">{{count($courses->where('status', '=', 3))}}</span> {{__('default.pages.profile.course_count')}}
                                    </div>
                                    <div>
                                        <span class="blue">{{count($author_students_finished)}}</span> {{__('default.pages.profile.issued_certificates')}}
                                    </div>
                                </div>
                                <div class="rating">
                                    <div class="rating__number">{{round($average_rates, 1)}}</div>
                                    <div class="rating__stars">
                                        <?php
                                        for ($x = 1; $x <= $average_rates; $x++) {
                                            echo '<i class="icon-star-full"> </i>';
                                        }
                                        if (strpos($average_rates, '.')) {
                                            echo '<i class="icon-star-half"> </i>';
                                            $x++;
                                        }
                                        while ($x <= 5) {
                                            echo '<i class="icon-star-empty"> </i>';
                                            $x++;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(!empty($item->user->author_info->certificates))
                        <div class="article-section">
                            <h2 class="title-secondary">{{__('default.pages.courses.author_certificates')}}</h2>
                            <div class="row row--multiline">

                                @foreach(json_decode($item->user->author_info->certificates) as $certificate)
                                    <div class="col-md-3 col-sm-4 col-xs-6">
                                        <a href="{{$certificate}}"
                                           data-fancybox="author- certificates"
                                           title="{{__('default.pages.courses.zoom_certificate')}}"
                                           class="certificate">
                                            <img src="{{$certificate}}" alt="">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="article-section">
                        <h2 class="title-secondary">{{__('default.pages.courses.feedback_title')}}</h2>
                        <div class="plain-text">{{__('default.pages.courses.feedback_placeholder')}}</div>
                    </div>
                </div>

            </div>
            <div class="col-md-4">
                <form class="sidebar">
                    <div class="sidebar__inner">
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
                                @if(!empty($item->attachments->videos_poor_vision_link) and $item->is_poor_vision == true)
                                    @foreach(json_decode($item->attachments->videos_poor_vision_link) as $video_link)
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
                                @if(!empty($item->attachments->videos_poor_vision) and $item->is_poor_vision == true)
                                    @foreach(json_decode($item->attachments->videos_poor_vision) as $video)
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
                                @if(!empty($item->attachments->audios_poor_vision) and $item->is_poor_vision == true)
                                    @foreach(json_decode($item->attachments->audios_poor_vision) as $audio)
                                        <audio controls
                                               src="{{$audio}}"></audio>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="sidebar-item">
                            <div class="sidebar-item__title">{{__('default.pages.courses.course_lang')}}:</div>
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
                                        <li>{{$item->lessons->whereIn('type', [1,2])->count()}} {{__('default.pages.courses.lessons_title')}}</li>
                                        <li>{{$videos_count}} {{__('default.pages.courses.videos_count')}}</li>
                                        <li>{{$audios_count}} {{__('default.pages.courses.audios_count')}} </li>
                                        <li>{{$attachments_count}} {{__('default.pages.courses.attachments_count')}} </li>
                                        {{--                                                <li>70,5 часов видео</li>--}}
                                        <li>{{$item->lessons->whereIn('type', [2])->where('end_lesson_type', '=', 0)->count()}} {{__('default.pages.courses.tests_count_title')}}</li>
                                        <li>{{$item->lessons->where('end_lesson_type', '=', 1)->where('type', '=', 2)->count()}} {{__('default.pages.courses.homeworks_count')}}</li>
                                        @if(!empty($item->courseWork()))
                                            <li>{{__('default.pages.courses.coursework_title')}}</li>
                                        @endif
                                        @if(!empty($item->finalTest()))
                                            <li>{{__('default.pages.courses.final_test_title')}}</li>
                                        @endif
                                        <li>{{__('default.pages.courses.mobile_access_title')}}</li>
                                        <li>{{__('default.pages.courses.certificate_access_title')}}</li>
                                    </ul>
                                    <div class="hint gray">{{__('default.pages.courses.last_updates')}}
                                        : {{\App\Extensions\FormatDate::formatDate($item->updated_at->format("d.m.Y"))}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="sidebar-item">
                            <div class="sidebar-item__title">{{__('default.pages.courses.professions_title_1')}}
                                :
                            </div>
                            <div class="sidebar-item__body">
                                <div class="extendable">
                                    <div class="tags">
                                        <ul>
                                            @foreach($item->skills as $skill)
                                                @foreach($skill->professions as $profession)
                                                    <li>
                                                        <a href="#"
                                                           title="{{$profession->getAttribute('name_'.$lang ?? 'name_ru')}}">{{$profession->getAttribute('name_'.$lang ?? 'name_ru')}}</a>
                                                    </li>
                                                @endforeach
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
                            <div class="sidebar-item__title">{{__('default.pages.courses.skills_title_1')}}:
                            </div>
                            <div class="sidebar-item__body">
                                <div class="extendable">
                                    <div class="tags">
                                        <ul>
                                            @foreach($item->skills as $skill)
                                                <li><a href="#"
                                                       title="{{$skill->getAttribute('name_'.$lang ?? 'name_ru')}}">{{$skill->getAttribute('name_'.$lang ?? 'name_ru')}}</a>
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
                            <div class="sidebar-item__title"></div>
                            <div class="sidebar-item__body">
                                <div class="price">
                                    @if($item->is_paid == 1)
                                        <div class="price__value">{{number_format($item->cost, 0, ',', ' ')}} ₸
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
                    </div>
                    <div class="sidebar__buttons">
                        <a href="#" title="{{__('default.pages.courses.get_by_quota')}}"
                           class="sidebar-btn ghost disabled">{{__('default.pages.courses.get_by_quota')}}</a>
                        <a href="#" title="{{__('default.pages.courses.buy_course')}}"
                           class="sidebar-btn disabled">{{__('default.pages.courses.buy_course')}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!--Only this page's scripts-->

    <!---->
@endsection

