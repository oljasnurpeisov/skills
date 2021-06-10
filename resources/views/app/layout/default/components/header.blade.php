@auth
    <header class="header">
        <div class="container">
            <a href="/{{$lang}}" title="{{__('default.main_title')}}" class="logo">
                <picture>
                    <source srcset="{{ asset('/assets/img/logo_new.webp') }}" type="image/webp">
                    <img src="{{ asset('/assets/img/logo_new.png') }}" alt="" />
                </picture>
            </a>
            <ul class="menu">
                @if(Auth::user()->hasRole('author'))
                    <li><a href="/{{$lang}}/profile-author-information"
                           title="{{__('default.pages.profile.title')}}">{{__('default.pages.profile.title')}}</a></li>
                @endif
                @if(Auth::user()->hasRole('author'))
                    <li @if(basename(request()->path()) == 'my-courses')class="active"@endif><a
                                href="/{{$lang}}/my-courses"
                                title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>

                    </li>
                @endif
                @if(Auth::user()->hasRole('student'))
                    <li @if(basename(request()->path()) == 'course-catalog')class="active"@endif><a
                                href="/{{$lang}}/course-catalog"
                                title="{{__('default.pages.courses.course_catalog')}}">{{__('default.pages.courses.course_catalog')}}</a>

                    </li>
                @endif
            </ul>
            <div class="buttons-group">
                <form class="input-search" action="/{{$lang}}/course-catalog?search=">
                    <i class="icon-search"> </i>
                    <input type="text" name="search" placeholder="{{__('default.pages.index.search_placeholder')}}">
                    <button type="submit" class="btn-icon small icon-chevron-right"></button>
                </form>

                @php($notifications = Auth::user()->notifications()->where('is_read', '=', false)->orderBy('created_at', 'asc')->limit(3)->get())
                @php($notifications_count = Auth::user()->notifications()->where('is_read', '=', false)->count())
                <div class="header-dropdown notifications">
                    <div class="header-dropdown__title">
                        <a href="#" title="{{__('notifications.title')}}"
                           class="btn-icon small btn-icon--transparent icon-notification"
                           data-unread="{{$notifications_count}}"> </a>
                    </div>
                    <div class="header-dropdown__desc">
                        <ul>
                            @foreach($notifications as $notification)
                                @if($notification->type == 1)
                                    <li data-id="{{$notification->id}}">
                                        <form method="POST"
                                              action="/{{$lang}}/my-courses/quota-confirm-course/{{$notification->course->id}}"
                                              id="quota_confirm_form">
                                            {{ csrf_field() }}
                                            <span>{!!trans($notification->name, ['course_name' => '"'. optional($notification->course)->name .'"', 'lang' => $lang, 'course_id' => optional($notification->course)->id, 'opponent_id' => json_decode($notification->data)[0]->dialog_opponent_id ?? 0, 'reject_message' => json_decode($notification->data)[0]->course_reject_message ?? '', 'course_quota_cost' => json_decode($notification->data)[0]->course_quota_cost ?? 0, 'notification_id' => $notification->id])!!}</span>
                                            @if($notification->course->quota_status == 1)
                                                <div class="buttons">
                                                    <button name="action" value="confirm"
                                                            title="{{__('notifications.confirm_btn_title')}}"
                                                            class="btn small">{{__('notifications.confirm_btn_title')}}</button>
                                                    <button name="action" value="reject" style="background-color: white"
                                                            title="{{__('notifications.reject_btn_title')}}"
                                                            class="ghost-btn small">{{__('notifications.reject_btn_title')}}</button>
                                                </div>
                                            @endif
                                        </form>
                                    </li>
                                    <li class="break">
                                        <hr>
                                    </li>
                                @else

                                    <li data-id="{{$notification->id}}">
                                        @php($opponent = \App\Models\User::whereId(json_decode($notification->data)[0]->dialog_opponent_id ?? 0)->first())
                                        <span>{!!trans($notification->name, ['course_name' => '"'. optional($notification->course)->name .'"', 'lang' => $lang, 'course_id' => optional($notification->course)->id, 'opponent_id' => json_decode($notification->data)[0]->dialog_opponent_id ?? 0, 'reject_message' => json_decode($notification->data)[0]->course_reject_message ?? '','user_name' => $opponent ? ($opponent->hasRole('author') ? $opponent->author_info->name . ' ' . $opponent->author_info->surname : $opponent->student_info->name ??  $opponent->name) : '', 'course_quota_cost' => json_decode($notification->data)[0]->course_quota_cost ?? 0])!!}</span>
                                    </li>
                                    <li class="break">
                                        <hr>
                                    </li>
                                @endif
                                @if (Auth::user()->hasRole('author'))
                                    <div id="rulesQuotaModal{{optional($notification->course)->id.'-'.$notification->id}}"
                                         style="display:none; width: 500px" class="modal-form">
                                        <h4 class="title-primary text-center">{{__('notifications.quota_rules_title')}}</h4>
                                        <div class="plain-text" style="font-size: 1em">
                                            {!! trans(__('notifications.quota_rules_description'), ['course_id' => optional($notification->course)->id,'course_name' => optional($notification->course)->name, 'author_name' => Auth::user()->author_info->name . ' ' . Auth::user()->author_info->surname, 'course_quota_cost' => json_decode($notification->data)[0]->course_quota_cost ?? 0, 'lang' => $lang])!!}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            <li><a href="/{{$lang}}/notifications"
                                   title="{{__('notifications.all_notifications')}}"
                                   class="blue">{{__('notifications.all_notifications')}} <i
                                            class="icon-chevron-right"> </i></a></li>

                        </ul>
                    </div>
                </div>
                <div class="header-dropdown profile">
                    <div class="header-dropdown__title">
                        @if(Auth::user()->hasRole('author'))
                            <img src="{{Auth::user()->author_info->getAvatar()}}"
                                 alt=""><i
                                    class="icon-chevron-down"> </i>
                        @elseif(Auth::user()->hasRole('student'))
                            @if (!empty(Auth::user()->student_info->avatar))
                                <img src="{{ Auth::user()->student_info->getAvatar() }}" alt="">
                            @else
                                <img src="/assets/img/author-thumbnail.png" alt="">
                            @endif

                            <i class="icon-chevron-down"> </i>
                        @endif
                    </div>
                    <div class="header-dropdown__desc">
                        @if(Auth::user()->hasRole('author'))
                            <div class="name">{{Auth::user()->author_info->name . ' ' . Auth::user()->author_info->surname}}</div>
                        @elseif(Auth::user()->hasRole('student'))
                            <div class="name">
                                @if (!empty(Auth::user()->student_info->name))
                                    {{Auth::user()->student_info->name}}
                                @else
                                    {{ Auth::user()->email }}
                                @endif

                            </div>
                            <div class="quotas">{{__('default.pages.profile.have_quota')}}
                                : {{Auth::user()->student_info->quota_count}}</div>
                        @endif
                        <hr>
                        <ul>
                            @if(Auth::user()->hasRole('author'))
                                <li><a href="/{{$lang}}/my-courses/statistics"
                                       title="{{__('default.pages.statistics.title')}}">{{__('default.pages.statistics.title')}}</a>
                                </li>
                            @endif
                            @if(Auth::user()->hasRole('author'))
                                <li><a href="/{{$lang}}/profile-author-information"
                                       title="{{__('default.pages.profile.title')}}">{{__('default.pages.profile.title')}}</a>
                                </li>
                            @elseif(Auth::user()->hasRole('student'))
                                <li><a href="/{{$lang}}/student-profile"
                                       title="{{__('default.pages.profile.title')}}">{{__('default.pages.profile.title')}}</a>
                                </li>
                            @endif
                            @if(Auth::user()->hasRole('author'))
                                <li><a href="/{{$lang}}/my-courses"
                                       title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
                                </li>
                            @elseif(Auth::user()->hasRole('student'))
                                <li><a href="/{{$lang}}/student/my-courses"
                                       title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
                                </li>
                            @endif
                            @if(Auth::user()->hasRole('student'))
                                <li><a href="/{{$lang}}/student/my-certificates"
                                       title="{{__('default.pages.profile.my_certificates')}}">{{__('default.pages.profile.my_certificates')}}</a>
                                </li>
                            @endif
                            <li><a href="/{{$lang}}/dialogs"
                                   title="{{__('default.pages.dialogs.title')}}">{{__('default.pages.dialogs.title')}}</a>
                            </li>
                            @if(Auth::user()->hasRole('author'))
                                <li><a href="/{{$lang}}/my-courses/reporting"
                                       title="{{__('default.pages.reporting.title')}}">{{__('default.pages.reporting.title')}}</a>
                                </li>
                            @endif
                            <li><a href="/{{$lang}}/notifications"
                                   title="{{__('notifications.title')}}">{{__('notifications.title')}}</a></li>
                                <li><a href="/{{$lang}}/help"
                                       title="{{__('default.pages.footer.help')}}">{{__('default.pages.footer.help')}}</a>
                                </li>
                            <li class="break">
                                <hr>
                            </li>
                            <li><a href="/{{$lang}}/logout"
                                   title="{{__('default.pages.profile.logout_title')}}">{{__('default.pages.profile.logout_title')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="header-dropdown language">
                    <div class="header-dropdown__title">
                        @if($lang == 'en')
                            <span>EN</span><i class="icon-chevron-down"> </i>
                        @elseif($lang == 'kk')
                            <span>KZ</span><i class="icon-chevron-down"> </i>
                        @else
                            <span>RU</span><i class="icon-chevron-down"> </i>
                        @endif
                    </div>
                    <div class="header-dropdown__desc">
                        <ul>
                            @if($lang == 'en')
                                <li><a href="/kk{{ $uri }}" title="KZ">KZ</a></li>
                                <li class="break">
                                    <hr>
                                </li>
                                <li><a href="/ru{{ $uri }}" title="RU">RU</a></li>
                            @elseif($lang == 'kk')
                                <li><a href="/ru{{ $uri }}" title="RU">RU</a></li>
{{--                                <li class="break">--}}
{{--                                    <hr>--}}
{{--                                </li>--}}
{{--                                <li><a href="/en{{ $uri }}" title="EN">EN</a></li>--}}
                            @else
                                <li><a href="/kk{{ $uri }}" title="KZ">KZ</a></li>
{{--                                <li class="break">--}}
{{--                                    <hr>--}}
{{--                                </li>--}}
{{--                                <li><a href="/en{{ $uri }}" title="EN">EN</a></li>--}}
                            @endif
                        </ul>
                    </div>
                </div>
                <a href="#" class="bvi-open bvi-btn" title="{{__('default.pages.index.poor_vision_version')}}"><img
                            src="/assets/img/eye.svg" alt=""></a>
            </div>
            <div class="mobile-buttons">
                <div class="notifications-btn mob-overlay-btn" data-target="notifications-mobile">
                    <a href="/{{$lang}}/notifications"><i class="icon-notification"> </i></a>
                </div>
                <div class="menu-btn mob-overlay-btn" data-target="header-mobile">
                    <i class="icon-menu"> </i>
                </div>
            </div>
        </div>
    </header>

    <div class="mob-overlay header-mobile" id="header-mobile">
        <div class="mob-overlay__top">
            <a href="#" title="{{__('default.pages.courses.close_title')}}" class="mob-overlay__close icon-close"> </a>
        </div>
        <div class="mob-overlay__body">
            <div class="mob-overlay__inner">
                <div class="header-mobile__profile">
                    @if(Auth::user()->hasRole('author'))
                        <img src="{{Auth::user()->author_info->getAvatar()}}" alt="">
                        <div class="name">{{Auth::user()->author_info->name . ' ' . Auth::user()->author_info->surname}}</div>
                    @elseif(Auth::user()->hasRole('student'))
                        <img src="{{Auth::user()->student_info->getAvatar()}}" alt="">
                        <div class="name"></div>
                    @endif
                </div>
                <div class="mob-overlay__group">
                    <form class="input-search" action="/{{$lang}}/course-catalog?search=">
                        <i class="icon-search"> </i>
                        <input type="text" name="search" placeholder="{{__('default.pages.index.search_placeholder')}}">
                        <button type="submit" class="btn-icon small icon-chevron-right"></button>
                    </form>
                </div>
                <hr>
                <ul class="header-mobile__menu">
                    @if(Auth::user()->hasRole('author'))
                        <li><a href="/{{$lang}}/my-courses/statistics"
                               title="{{__('default.pages.statistics.title')}}">{{__('default.pages.statistics.title')}}</a>
                        </li>
                    @endif
                    @if(Auth::user()->hasRole('author'))
                        <li><a href="/{{$lang}}/profile-author-information"
                               title="{{__('default.pages.profile.title')}}">{{__('default.pages.profile.title')}}</a>
                        </li>
                    @elseif(Auth::user()->hasRole('student'))
                        <li><a href="/{{$lang}}/student-profile"
                               title="{{__('default.pages.profile.title')}}">{{__('default.pages.profile.title')}}</a>
                        </li>
                    @endif
                    @if(Auth::user()->hasRole('author'))
                        <li><a href="/{{$lang}}/my-courses"
                               title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
                        </li>
                    @elseif(Auth::user()->hasRole('student'))
                        <li><a href="/{{$lang}}/student/my-courses"
                               title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
                        </li>
                    @endif
                    <li><a href="/{{$lang}}/dialogs"
                           title="{{__('default.pages.dialogs.title')}}">{{__('default.pages.dialogs.title')}}</a>
                    </li>
                    @if(Auth::user()->hasRole('author'))
                        <li><a href="/{{$lang}}/my-courses/reporting"
                               title="{{__('default.pages.reporting.title')}}">{{__('default.pages.reporting.title')}}</a>
                        </li>
                    @endif
                    <li><a href="/{{$lang}}/notifications"
                           title="{{__('notifications.title')}}">{{__('notifications.title')}}</a></li>
                        <li><a href="/{{$lang}}/help"
                               title="{{__('default.pages.footer.help')}}">{{__('default.pages.footer.help')}}</a>
                        </li>
                    <li><a href="/{{$lang}}/logout"
                           title="{{__('default.pages.profile.logout_title')}}">{{__('default.pages.profile.logout_title')}}</a>
                    </li>
                </ul>
                <hr>
                <ul class="mob-language">
                    <li><a href="/kk{{ $uri }}" title="KZ">KZ</a></li>
                    <li><a href="/ru{{ $uri }}" title="RU">RU</a></li>
{{--                    <li><a href="/en{{ $uri }}" title="EN">EN</a></li>--}}
                </ul>
            </div>
        </div>
    </div>
@endauth

@guest
    <header class="header compensate-for-scrollbar">
        <div class="container">
            <a href="/{{$lang}}" title="{{__('default.main_title')}}" class="logo">
                <picture>
                    <source srcset="{{ asset('/assets/img/logo_new.webp') }}" type="image/webp">
                    <img src="{{ asset('/assets/img/logo_new.png') }}" alt="" />
                </picture>
            </a>
            <div class="buttons-group">
                <form class="input-search" action="/{{$lang}}/course-catalog?search=">
                    <i class="icon-search"> </i>
                    <input type="text" name="search" placeholder="{{__('default.pages.index.search_placeholder')}}">
                    <button type="submit" class="btn-icon small icon-chevron-right"></button>
                </form>

                <a href="#authorization" data-fancybox title="{{__('default.pages.auth.auth_title')}}"
                   class="btn small">{{__('default.pages.auth.auth_title')}}</a>
                <div class="header-dropdown language">
                    <div class="header-dropdown__title">
                        @if($lang == 'en')
                            <span>EN</span><i class="icon-chevron-down"> </i>
                        @elseif($lang == 'kk')
                            <span>KZ</span><i class="icon-chevron-down"> </i>
                        @else
                            <span>RU</span><i class="icon-chevron-down"> </i>
                        @endif
                    </div>
                    <div class="header-dropdown__desc">
                        <ul>
                            @if($lang == 'en')
                                <li><a href="/kk{{ $uri }}" title="KZ">KZ</a></li>
                                <li class="break">
                                    <hr>
                                </li>
                                <li><a href="/ru{{ $uri }}" title="RU">RU</a></li>
                            @elseif($lang == 'kk')
                                <li><a href="/ru{{ $uri }}" title="RU">RU</a></li>
{{--                                <li class="break">--}}
{{--                                    <hr>--}}
{{--                                </li>--}}
{{--                                <li><a href="/en{{ $uri }}" title="EN">EN</a></li>--}}
                            @else
                                <li><a href="/kk{{ $uri }}" title="KZ">KZ</a></li>
{{--                                <li class="break">--}}
{{--                                    <hr>--}}
{{--                                </li>--}}
{{--                                <li><a href="/en{{ $uri }}" title="EN">EN</a></li>--}}
                            @endif
                        </ul>
                    </div>
                </div>
                <a href="#" class="bvi-open bvi-btn" title="{{__('default.pages.index.poor_vision_version')}}"><img
                            src="/assets/img/eye.svg" alt=""></a>
            </div>
            <div class="mobile-buttons">
                <div class="menu-btn mob-overlay-btn" data-target="header-mobile">
                    <i class="icon-menu"> </i>
                </div>
            </div>
        </div>
    </header>

    <div class="mob-overlay" id="header-mobile">
        <div class="mob-overlay__top">
            <a href="#" title="{{__('default.pages.courses.close_title')}}" class="mob-overlay__close icon-close"> </a>
        </div>
        <div class="mob-overlay__body">
            <div class="mob-overlay__inner">
                <div class="mob-overlay__group">
                    <a href="#authorization" data-fancybox title="{{__('default.pages.auth.auth_title')}}"
                       class="btn">{{__('default.pages.auth.auth_title')}}</a>
                    <form class="input-search" action="/{{$lang}}/course-catalog?search=">
                        <i class="icon-search"> </i>
                        <input type="text" name="search" placeholder="{{__('default.pages.index.search_placeholder')}}">
                        <button type="submit" class="btn-icon small icon-chevron-right"></button>
                    </form>
                </div>
                <hr>
                <ul class="mob-language">
                    <li><a href="/kk{{ $uri }}" title="KZ">KZ</a></li>
                    <li><a href="/ru{{ $uri }}" title="RU">RU</a></li>
{{--                    <li><a href="/en{{ $uri }}" title="EN">EN</a></li>--}}
                </ul>
            </div>
        </div>
    </div>
@endguest
