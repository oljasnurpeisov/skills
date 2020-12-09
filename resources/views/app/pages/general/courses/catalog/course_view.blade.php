@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="plain">
            <div class="container">
                <ul class="breadcrumbs">
                    <li><a href="/{{$lang}}/course-catalog"
                           title="{{__('default.pages.courses.course_catalog')}}">{{__('default.pages.courses.course_catalog')}}</a>
                    </li>
                    <li><span>{{$item->name}}</span></li>
                </ul>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

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
                                            <span>{{$item->rate->pluck('rate')->avg() ?? 0}}</span>
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
                                    <div class="course">
                                        @foreach($item->themes->sortBy('index_number') as $theme)
                                            <div class="topic spoiler">
                                                <div class="topic__header">
                                                    <div class="title">{{$theme->name}}</div>
                                                    <div class="duration">{{\App\Extensions\FormatDate::convertMunitesToTime($item->lessons->where('theme_id', '=', $theme->id)->sum('duration'))}}</div>
                                                </div>
                                                <div class="topic__body">
                                                    @foreach($theme->lessons->sortBy('index_number') as $lesson)
                                                        @if($student_course)
                                                            <div class="lesson {{ (!empty($lesson->lesson_student->is_finished) == true ? 'finished' : '') }}">
                                                                <div class="title"><a
                                                                            href="/{{$lang}}/course-catalog/course/{{$item->id}}/lesson-{{$lesson->id}}"
                                                                            title="{{$lesson->name}}">{{$lesson->name}}</a>
                                                                </div>
                                                                <div class="duration">{{\App\Extensions\FormatDate::convertMunitesToTime($lesson->duration)}}</div>
                                                            </div>
                                                        @else
                                                            <div class="lesson">
                                                                <div class="title">{{$lesson->name}}
                                                                </div>
                                                                <div class="duration">{{\App\Extensions\FormatDate::convertMunitesToTime($lesson->duration)}}</div>
                                                            </div>
                                                        @endif


                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if($item->courseWork() !== null)
                                        <div class="topic">
                                            <form action="/{{$lang}}/course-{{$item->id}}/lesson-{{$item->courseWork()->id}}/delete-lesson-form"
                                                  method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="topic__header">
                                                    @if($student_course)
                                                        <div class="title"><a
                                                                    href="/{{$lang}}/course-catalog/course/{{$item->id}}/lesson-{{$item->courseWork()->id}}">{{__('default.pages.lessons.coursework_title')}}</a>
                                                        </div>
                                                    @else
                                                        <div class="title">{{__('default.pages.lessons.coursework_title')}}
                                                        </div>
                                                    @endif
                                                    <div class="duration"></div>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @if($item->finalTest() !== null)
                                        <div class="topic">
                                            <form action="/{{$lang}}/course-{{$item->id}}/lesson-{{$item->finalTest()->id}}/delete-lesson-form"
                                                  method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="topic__header">
                                                    @if($student_course)
                                                        <div class="title"><a
                                                                    href="/{{$lang}}/course-catalog/course/{{$item->id}}/lesson-{{$item->finalTest()->id}}">{{__('default.pages.courses.final_test_title')}}</a>
                                                        </div>
                                                    @else
                                                        <div class="title">{{__('default.pages.courses.final_test_title')}}
                                                        </div>
                                                    @endif
                                                    <div class="duration"></div>
                                                </div>
                                            </form>
                                        </div>
                                    @endif

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
                                        @if(!empty($student_course) and ($student_course->paid_status != 0))
                                            <div>
                                                <a href="/{{$lang}}/dialog/opponent-{{$item->user->id}}"
                                                   title="{{__('default.pages.courses.write_to_author')}}"
                                                   class="btn small">{{__('default.pages.courses.write_to_author')}}
                                                </a>
                                            </div>
                                        @endif
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
                                <h2 class="title-secondary">Отзывы</h2>
                                <div>
                                    <div class="review">
                                        <div class="review__header">
                                            <div class="review__name">Алиса Сергеевна</div>
                                            <div class="rating">
                                                <div class="rating__stars">
                                                    <i class="icon-star-full"> </i>
                                                    <i class="icon-star-full"> </i>
                                                    <i class="icon-star-full"> </i>
                                                    <i class="icon-star-full"> </i>
                                                    <i class="icon-star-half"> </i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="review__text">
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                            tempor incididunt ut labore et dolore magna aliqua.
                                        </div>
                                    </div>
                                    <div class="review">
                                        <div class="review__header">
                                            <div class="review__name">Кларинов Сергей</div>
                                            <div class="rating">
                                                <div class="rating__stars">
                                                    <i class="icon-star-full"> </i>
                                                    <i class="icon-star-full"> </i>
                                                    <i class="icon-star-full"> </i>
                                                    <i class="icon-star-full"> </i>
                                                    <i class="icon-star-half"> </i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="review__text">
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                            consequat. Duis aute irure dolor in reprehenderit.
                                        </div>
                                    </div>
                                </div>
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
                                                <li>{{ __('default.pages.courses.lessons_title').': '.$item->lessons->whereIn('type', [1,2])->count() }} </li>
                                                <li>{{ __('default.pages.courses.videos_count').': '.$videos_count }}</li>
                                                <li>{{ __('default.pages.courses.audios_count').': '.$audios_count }}</li>
                                                <li>{{ __('default.pages.courses.attachments_count').': '.$attachments_count }}</li>
                                                {{--                                                <li>70,5 часов видео</li>--}}
                                                <li>{{ __('default.pages.courses.tests_count_title').': '.$item->lessons->whereIn('type', [2])->where('end_lesson_type', '=', 0)->count() }}</li>
                                                <li>{{ __('default.pages.courses.homeworks_count').': '.$item->lessons->where('end_lesson_type', '=', 1)->where('type', '=', 2)->count() }}</li>
                                                @if(!empty($item->courseWork()))
                                                    <li>{{ __('default.pages.courses.coursework_title') }}</li>
                                                @endif
                                                @if(!empty($item->finalTest()))
                                                    <li>{{ __('default.pages.courses.final_test_title') }}</li>
                                                @endif
                                                {{--                                                <li>8 интерактивных задач</li>--}}
                                                {{--                                                <li>1 статья</li>--}}
                                                <li>{{ __('default.pages.courses.mobile_access_title') }}</li>
                                                {{--                                                <li>10 файлов</li>--}}
                                                <li>{{ __('default.pages.courses.certificate_access_title') }}</li>
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
                                        <div class="tags">
                                            <ul>
                                                {{--                                                {{$item->skills[0]->professions->first()}}--}}
                                                @foreach($item->skills as $skill)
                                                    @foreach($skill->professions as $profession)
                                                        <li>
                                                            <a href="/{{$lang}}/course-catalog?specialities[]={{$profession->id}}"
                                                               title="{{$profession->getAttribute('name_'.$lang ?? 'name_ru')}}">{{$profession->getAttribute('name_'.$lang ?? 'name_ru')}}</a>
                                                        </li>
                                                    @endforeach
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="sidebar-item">
                                    <div class="sidebar-item__title">{{__('default.pages.courses.skills_title_1')}}:
                                    </div>
                                    <div class="sidebar-item__body">
                                        <div class="tags">
                                            <ul>
                                                @foreach($item->skills as $skill)
                                                    <li><a href="/{{$lang}}/course-catalog?skills[]={{$skill->id}}"
                                                           title="{{$skill->getAttribute('name_'.$lang ?? 'name_ru')}}">{{$skill->getAttribute('name_'.$lang ?? 'name_ru')}}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @if(empty($student_course) or ($student_course->paid_status == 0))
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title"></div>
                                        <div class="sidebar-item__body">
                                            <div class="price">
                                                @if($item->is_paid == 1)
                                                    <div class="price__value">{{number_format($item->cost, 0, ',', ' ')}}
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
                        </form>
                    </div>
                </div>
            </div>
        </section>

        @auth
            @if(empty($student_rate) and !empty($student_course->is_finished) == true)
                <div id="rate" style="display:none;">
                    <h4 class="title-primary text-center">{{__('default.pages.courses.course_rate_title')}}</h4>
                    <form action="/{{$lang}}/course-{{$student_course->course_id}}/saveCourseRate" method="POST">
                        @csrf
                        <div class="rating-fieldset">
                            <input type="radio" name="rating" value="5" id="star5" required>
                            <label for="star5"><i class="icon-star-empty"></i></label>
                            <input type="radio" name="rating" value="4" id="star4">
                            <label for="star4"><i class="icon-star-empty"></i></label>
                            <input type="radio" name="rating" value="3" id="star3">
                            <label for="star3"><i class="icon-star-empty"></i></label>
                            <input type="radio" name="rating" value="2" id="star2">
                            <label for="star2"><i class="icon-star-empty"></i></label>
                            <input type="radio" name="rating" value="1" id="star1">
                            <label for="star1"><i class="icon-star-empty"></i></label>
                        </div>
                        <div class="form-group">
                    <textarea name="review" placeholder="{{__('default.pages.courses.course_rate_description')}}"
                              class="input-regular" required></textarea>
                            <div class="hint text-center gray">* {{__('default.pages.courses.course_rate_ps')}}</div>
                        </div>
                        <div class="text-center">
                            <button type="submit"
                                    class="btn">{{__('default.pages.courses.send_rate_button_title')}}</button>
                        </div>
                    </form>
                </div>
            @endif
        @endauth


        <div id="buyConfirm" style="display:none;">
            <form action="/createPaymentOrder/{{$item->id}}" method="POST">
                @csrf
                <h4 class="title-primary text-center">{{__('default.pages.courses.confirm_modal_title')}}</h4>
                <div class="plain-text gray">{{__('default.pages.courses.confirm_course_buy')}}</div>
                <div class="row row--multiline justify-center">
                    <div class="col-auto">
                        <button type="submit" title="{{__('default.yes_title')}}"
                                class="btn">{{__('default.yes_title')}}</button>
                    </div>
                    <div class="col-auto">
                        <a href="#" title="{{__('default.no_title')}}" class="ghost-btn"
                           data-fancybox-close>{{__('default.no_title')}}</a>
                    </div>
                </div>
            </form>
        </div>

        @auth
            @if(Auth::user()->hasRole('student'))
                <div id="quotaConfirm" style="display:none;">
                    <form action="/createPaymentOrder/{{$item->id}}" method="POST">
                        @csrf
                        <h4 class="title-primary text-center">{{__('default.pages.courses.confirm_modal_title')}}</h4>
                        <div class="plain-text gray text-center">{{__('default.pages.courses.confirm_course_by_quota')}}</div>
                        <div class="plain-text gray text-center">{{__('default.pages.courses.quota_have')}}
                            : {{Auth::user()->student_info->quota_count}}</div>
                        <div class="row row--multiline justify-center">
                            <div class="col-auto">
                                <button type="submit" name="action" value="by_qouta" title="{{__('default.yes_title')}}"
                                        class="btn">{{__('default.yes_title')}}</button>
                            </div>
                            <div class="col-auto">
                                <a href="#" title="{{__('default.no_title')}}" class="ghost-btn"
                                   data-fancybox-close>{{__('default.no_title')}}</a>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        @endauth


    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    @if(empty($student_rate) and !empty($student_course->is_finished) == true)
        <script>
            $.fancybox.open({
                src: '#rate',
                touch: false,
                smallBtn: false,
                buttons: [],
                clickSlide: false,
                clickOutside: false
            })
        </script>
    @endif
    <!---->
@endsection

