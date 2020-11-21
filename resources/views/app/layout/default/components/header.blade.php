<header class="header">
    <div class="container">
        <a href="/{{$lang}}" title="{{__('default.main_title')}}" class="logo"><img src="/assets/img/logo.svg"
                                                                                    alt=""></a>
        <ul class="menu">
            @if(Auth::user()->roles()->first()->slug == 'author')
                <li><a href="/{{$lang}}/profile-author-information"
                       title="{{__('default.pages.profile.title')}}">{{__('default.pages.profile.title')}}</a></li>
            @elseif(Auth::user()->roles()->first()->slug == 'student')
                <li><a href="/{{$lang}}/student-profile"
                       title="{{__('default.pages.profile.title')}}">{{__('default.pages.profile.title')}}</a></li>
            @endif
            @if(Auth::user()->roles()->first()->slug == 'author')
                <li @if(basename(request()->path()) == 'my-courses')class="active"@endif><a href="/{{$lang}}/my-courses"
                                                                                            title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>

                </li>
            @elseif(Auth::user()->roles()->first()->slug == 'student')
                <li><a href="/{{$lang}}/student/my-courses"
                       title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>

                </li>
            @endif
        </ul>
        <div class="buttons-group">
            <!--<a href="#" title="Помощь" class="ghost-btn ghost-btn&#45;&#45;blue small">Помощь</a>-->
            <div class="header-dropdown notifications">
                <div class="header-dropdown__title">
                    <a href="#" title="{{__('notifications.title')}}"
                       class="btn-icon small btn-icon--transparent icon-notification"> </a>
                </div>
                <div class="header-dropdown__desc">
                    @php($notifications = Auth::user()->notifications()->limit(3)->get())
                    <ul>
                        @foreach($notifications as $notification)
                            <li><span>{!!trans($notification->name, ['course_name' => '"'.$notification->course->name.'"'])!!}</span></li>
                            <li class="break">
                                <hr>
                            </li>
                        @endforeach
                        <li><a href="/{{$lang}}/notifications" title="{{__('notifications.all_notifications')}}"
                               class="blue">{{__('notifications.all_notifications')}} <i
                                        class="icon-chevron-right"> </i></a></li>
                    </ul>
                </div>
            </div>
            <div class="header-dropdown profile">
                <div class="header-dropdown__title">
                    @if(Auth::user()->roles()->first()->slug == 'author')
                        <img src="{{Auth::user()->author_info->avatar ?? '/assets/img/author-thumbnail.png'}}" alt=""><i
                                class="icon-chevron-down"> </i>
                    @elseif(Auth::user()->roles()->first()->slug == 'student')
                        <img src="{{Auth::user()->student_info->avatar ?? '/assets/img/author-thumbnail.png'}}" alt="">
                        <i class="icon-chevron-down"> </i>
                    @endif
                </div>
                <div class="header-dropdown__desc">
                    @if(Auth::user()->roles()->first()->slug == 'author')
                        <div class="name">{{Auth::user()->author_info->name . ' ' . Auth::user()->author_info->surname}}</div>
                    @elseif(Auth::user()->roles()->first()->slug == 'student')
                        {{--                        <div class="name">{{Auth::user()->author_info->name . ' ' . Auth::user()->author_info->surname}}</div>--}}
                    @endif
                    <hr>
                    <ul>
                        @if(Auth::user()->roles()->first()->slug == 'author')
                            <li><a href="/{{$lang}}/my-courses/statistics"
                                   title="{{__('default.pages.statistics.title')}}">{{__('default.pages.statistics.title')}}</a>
                            </li>
                        @endif
                        @if(Auth::user()->roles()->first()->slug == 'author')
                            <li><a href="/{{$lang}}/profile-author-information"
                                   title="{{__('default.pages.profile.title')}}">{{__('default.pages.profile.title')}}</a>
                            </li>
                        @elseif(Auth::user()->roles()->first()->slug == 'student')
                            <li><a href="/{{$lang}}/student-profile"
                                   title="{{__('default.pages.profile.title')}}">{{__('default.pages.profile.title')}}</a>
                            </li>
                        @endif
                        @if(Auth::user()->roles()->first()->slug == 'author')
                            <li><a href="/{{$lang}}/my-courses"
                                   title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
                            </li>
                        @elseif(Auth::user()->roles()->first()->slug == 'student')
                            <li><a href="/{{$lang}}/student/my-courses"
                                   title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
                            </li>
                        @endif
                        <li><a href="#"
                               title="{{__('default.pages.dialogs.title')}}">{{__('default.pages.dialogs.title')}}</a>
                        </li>
                        @if(Auth::user()->roles()->first()->slug == 'author')
                            <li><a href="/{{$lang}}/my-courses/reporting"
                                   title="{{__('default.pages.reporting.title')}}">{{__('default.pages.reporting.title')}}</a>
                            </li>
                        @endif
                        <li><a href="/{{$lang}}/notifications"
                               title="{{__('notifications.title')}}">{{__('notifications.title')}}</a></li>
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
                            <li class="break">
                                <hr>
                            </li>
                            <li><a href="/en{{ $uri }}" title="EN">EN</a></li>
                        @else
                            <li><a href="/kk{{ $uri }}" title="KZ">KZ</a></li>
                            <li class="break">
                                <hr>
                            </li>
                            <li><a href="/en{{ $uri }}" title="EN">EN</a></li>
                        @endif
                    </ul>
                </div>
            </div>
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
        <a href="#" title="Закрыть" class="mob-overlay__close icon-close"> </a>
    </div>
    <div class="mob-overlay__body">
        <div class="mob-overlay__inner">
            <div class="header-mobile__profile">
                @if(Auth::user()->roles()->first()->slug == 'author')
                    <img src="{{Auth::user()->author_info->avatar ?? '/assets/img/author-thumbnail.png'}}" alt="">
                    <div class="name">{{Auth::user()->author_info->name . ' ' . Auth::user()->author_info->surname}}</div>
                @elseif(Auth::user()->roles()->first()->slug == 'student')
                    <img src="{{Auth::user()->student_info->avatar ?? '/assets/img/author-thumbnail.png'}}" alt="">
                    <div class="name"></div>
                @endif
            </div>
            <hr>
            <ul class="header-mobile__menu">
                @if(Auth::user()->roles()->first()->slug == 'author')
                    <li><a href="/{{$lang}}/my-courses/statistics"
                           title="{{__('default.pages.statistics.title')}}">{{__('default.pages.statistics.title')}}</a>
                    </li>
                @endif
                @if(Auth::user()->roles()->first()->slug == 'author')
                    <li><a href="/{{$lang}}/profile-author-information"
                           title="{{__('default.pages.profile.title')}}">{{__('default.pages.profile.title')}}</a></li>
                @elseif(Auth::user()->roles()->first()->slug == 'student')
                    <li><a href="/{{$lang}}/student-profile"
                           title="{{__('default.pages.profile.title')}}">{{__('default.pages.profile.title')}}</a></li>
                @endif
                @if(Auth::user()->roles()->first()->slug == 'author')
                    <li><a href="/{{$lang}}/my-courses"
                           title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
                    </li>
                @elseif(Auth::user()->roles()->first()->slug == 'student')
                    <li><a href="/{{$lang}}/student/my-courses"
                           title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
                    </li>
                @endif
                <li><a href="#" title="{{__('default.pages.dialogs.title')}}">{{__('default.pages.dialogs.title')}}</a>
                </li>
                @if(Auth::user()->roles()->first()->slug == 'author')
                    <li><a href="/{{$lang}}/my-courses/reporting"
                           title="{{__('default.pages.reporting.title')}}">{{__('default.pages.reporting.title')}}</a>
                    </li>
                @endif
                <li><a href="/{{$lang}}/notifications"
                       title="{{__('notifications.title')}}">{{__('notifications.title')}}</a></li>
                <li><a href="/{{$lang}}/logout"
                       title="{{__('default.pages.profile.logout_title')}}">{{__('default.pages.profile.logout_title')}}</a>
                </li>
            </ul>
            <hr>
            <a href="#" title="Помощь" class="ghost-btn ghost-btn--blue">Помощь</a>
            <ul class="mob-language">
                <li><a href="/kk{{ $uri }}" title="KZ">KZ</a></li>
                <li><a href="/ru{{ $uri }}" title="RU">RU</a></li>
                <li><a href="/en{{ $uri }}" title="EN">EN</a></li>
            </ul>
        </div>
    </div>
</div>