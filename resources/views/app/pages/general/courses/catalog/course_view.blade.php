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
                                <div class="plain-text" style="word-wrap: break-word;">{!! $item->teaser !!}</div>
                                <div class="text-right">
                                    <div class="attributes">
                                        <div class="attributes-item">
                                            <i class="icon-user"> </i>
                                            <span>{{count($item->course_members->whereIn('paid_status', [1,2,3]))}}</span>
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
                                <div class="plain-text" style="word-wrap: break-word;">
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
                                        @foreach($course_data_items as $course_item)
                                            @if($course_item->item_type == 'theme')
                                                <div class="topic spoiler">
                                                    <div class="topic__header">
                                                        <div class="title">{{$course_item->name}}</div>
                                                        <div
                                                            class="duration">{{\App\Extensions\FormatDate::convertMunitesToTime($item->lessons->where('theme_id', '=', $course_item->id)->sum('duration'))}}</div>
                                                    </div>
                                                    <div class="topic__body">
                                                        @foreach($course_item->lessons->sortBy('index_number') as $lesson)
                                                            @if($student_course)
                                                                <div
                                                                    class="lesson {{ $lesson->finishedByCurrentUser() ? 'finished' : '' }}">
                                                                    @if($lesson->type != 1)
                                                                        <div class="title">
                                                                            <a href="/{{$lang}}/course-catalog/course/{{$item->id}}/lesson-{{$lesson->id}}"
                                                                               title="{{$lesson->name}}">
                                                                                {{$lesson->name}}
                                                                                <div class="type">
                                                                                    {{ $lesson->lesson_type->name.' ('.($lesson->end_lesson_type == 0 ? __('default.pages.lessons.test_title') : __('default.pages.lessons.homework_title')).')' }}
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                    @else
                                                                        <div class="title">
                                                                            <a href="/{{$lang}}/course-catalog/course/{{$item->id}}/lesson-{{$lesson->id}}"
                                                                               title="{{$lesson->name}}">
                                                                                {{$lesson->name}}
                                                                                <div class="type">
                                                                                    {{ $lesson->lesson_type->name }}
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                    <div
                                                                        class="duration">{{\App\Extensions\FormatDate::convertMunitesToTime($lesson->duration)}}</div>
                                                                </div>
                                                            @else
                                                                <div class="lesson">
                                                                    @if($lesson->type != 1)
                                                                        <div class="title">{{$lesson->name}}
                                                                            <div class="type">
                                                                                {{ $lesson->lesson_type->name.' ('.($lesson->end_lesson_type == 0 ? __('default.pages.lessons.test_title') : __('default.pages.lessons.homework_title')).')' }}
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div class="title">
                                                                            {{$lesson->name}}
                                                                            <div class="type">
                                                                                {{ $lesson->lesson_type->name }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    <div
                                                                        class="duration">{{\App\Extensions\FormatDate::convertMunitesToTime($lesson->duration)}}</div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <div class="topic__body">
                                                    @if($student_course)
                                                        <div
                                                            class="lesson {{ $course_item->finishedByCurrentUser() ? 'finished' : '' }}">
                                                            @if($course_item->type != 1)
                                                                <div class="title">
                                                                    <a href="/{{$lang}}/course-catalog/course/{{$item->id}}/lesson-{{$course_item->id}}"
                                                                       title="{{$course_item->name}}">
                                                                        {{$course_item->name}}
                                                                        <div class="type">
                                                                            {{ $course_item->lesson_type->name.' ('.($course_item->end_lesson_type == 0 ? __('default.pages.lessons.test_title') : __('default.pages.lessons.homework_title')).')' }}
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <div class="title">
                                                                    <a href="/{{$lang}}/course-catalog/course/{{$item->id}}/lesson-{{$course_item->id}}"
                                                                       title="{{$course_item->name}}">
                                                                        {{$course_item->name}}
                                                                        <div class="type">
                                                                            {{ $course_item->lesson_type->name }}
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            @endif
                                                            <div
                                                                class="duration">{{\App\Extensions\FormatDate::convertMunitesToTime($course_item->duration)}}</div>
                                                        </div>
                                                    @else
                                                        <div class="lesson">
                                                            @if($course_item->type != 1)
                                                                <div class="title">{{$course_item->name}}
                                                                    <div class="type">
                                                                        {{ $course_item->lesson_type->name.' ('.($course_item->end_lesson_type == 0 ? __('default.pages.lessons.test_title') : __('default.pages.lessons.homework_title')).')' }}
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="title">
                                                                    {{$course_item->name}}
                                                                    <div class="type">
                                                                        {{ $course_item->lesson_type->name }}
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div
                                                                class="duration">{{\App\Extensions\FormatDate::convertMunitesToTime($course_item->duration)}}</div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    @if($item->courseWork() !== null)
                                        <div class="topic">
                                            <div class="topic__header">
                                                @if($student_course)
                                                    <div class="title"><a
                                                            href="/{{$lang}}/course-catalog/course/{{$item->id}}/lesson-{{$item->courseWork()->id}}">{{__('default.pages.lessons.coursework_title')}}</a>
                                                    </div>
                                                @else
                                                    <div
                                                        class="title">{{__('default.pages.lessons.coursework_title')}}
                                                    </div>
                                                @endif
                                                <div class="duration"></div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($item->finalTest() !== null)
                                        <div class="topic">
                                            <div class="topic__header">
                                                @if($student_course)
                                                    <div class="title"><a
                                                            href="/{{$lang}}/course-catalog/course/{{$item->id}}/lesson-{{$item->finalTest()->id}}">{{__('default.pages.courses.final_test_title')}}</a>
                                                    </div>
                                                @else
                                                    <div
                                                        class="title">{{__('default.pages.courses.final_test_title')}}
                                                    </div>
                                                @endif
                                                <div class="duration"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="article-section">
                                <h2 class="title-secondary">{{__('default.pages.courses.course_description')}}</h2>
                                <div class="plain-text" style="word-wrap: break-word;">{!! $item->description !!}</div>
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
                                        <div
                                            class="personal-card__name">{{ $item->user->author_info->name . ' ' . $item->user->author_info->surname  }}</div>
                                        <div class="personal-card__gray-text"><strong>{{ __('default.pages.oked_industries') }}:</strong></div>
                                        <div class="personal-card__gray-text">{{ implode(', ', json_decode($item->user->oked_industries->pluck('oked_industry.name_ru')) ?? []) }}</div>
                                        <div class="personal-card__gray-text"><strong>{{ __('default.pages.oked_activities') }}:</strong></div>
                                        <div class="personal-card__gray-text">{{ implode(', ', json_decode($item->user->oked_activities->pluck('oked_activity.name_ru')) ?? []) }}</div>
                                        <div class="personal-card__gray-text"><strong>{{ __('default.pages.description') }}:</strong></div>
                                        <div class="plain-text">
                                            {!! $item->user->author_info->about !!}
                                        </div>
                                        <div class="personal-card__characteristics">
                                            <div>
                                                <span
                                                    class="blue">{{count($rates)}}</span> {{__('default.pages.profile.rates_count_title')}}
                                            </div>
                                            <div>
                                                <span
                                                    class="blue">{{count($author_students)}}</span> {{__('default.pages.profile.course_members_count')}}
                                            </div>
                                            <div>
                                                <span
                                                    class="blue">{{count($courses->where('status', '=', 3))}}</span> {{__('default.pages.profile.course_count')}}
                                            </div>
                                            <div>
                                                <span
                                                    class="blue">{{count($author_students_finished)}}</span> {{__('default.pages.profile.issued_certificates')}}
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
                            @if($course_rates->count() > 0)
                                <div class="article-section">
                                    <h2 class="title-secondary">{{__('default.pages.courses.feedback_title')}}</h2>
                                    <div>
                                        @foreach($course_rates as $rate)
                                            <div class="review">
                                                <div class="review__header">
                                                    <div
                                                        class="review__name">{{$rate->student->student_info->name ?? __('default.pages.profile.student_title')}}</div>
                                                    <div class="rating">
                                                        <div class="rating__stars">
                                                            <?php
                                                            for ($x = 1; $x <= $rate->rate; $x++) {
                                                                echo '<i class="icon-star-full"> </i>';
                                                            }
                                                            if (strpos($rate->rate, '.')) {
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
                                                <div class="review__text">
                                                    {!! $rate->description !!}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="text-center">
                                        {{ $course_rates->links('vendor.pagination.default') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <form class="sidebar">
                            @include('app.pages.general.courses.catalog.components.media_attachments',['item' => $item])
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
                        <div
                            class="plain-text gray text-center">{{__('default.pages.courses.confirm_course_by_quota')}}</div>
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
    <script src="/assets/js/visually-impaired-tools.js"></script>
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

