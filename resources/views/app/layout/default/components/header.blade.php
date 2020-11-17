<header class="header">
    <div class="container">
        <a href="/{{$lang}}" title="Главная" class="logo"><img src="/assets/img/logo.svg" alt=""></a>
        <ul class="menu">
            <li><a href="#" title="{{__('default.pages.profile.title')}}">{{__('default.pages.profile.title')}}</a></li>
            <li @if(basename(request()->path()) == 'my-courses')class="active"@endif><a href="/{{$lang}}/my-courses"
                                                                                        title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
            </li>
        </ul>
        <div class="buttons-group">
            <!--<a href="#" title="Помощь" class="ghost-btn ghost-btn&#45;&#45;blue small">Помощь</a>-->
            <div class="header-dropdown notifications">
                <div class="header-dropdown__title">
                    <a href="#" title="Уведомления" class="btn-icon small btn-icon--transparent icon-notification"> </a>
                </div>
                <div class="header-dropdown__desc">
                    <ul>
                        <li><span>Чтобы стать автором, подтвердите свой E-mail</span></li>
                        <li class="break">
                            <hr>
                        </li>
                        <li><span>Текст уведомления <a href="#" title="Ссылка в уведомлении">Ссылка в уведомлении</a> продолжение текста</span>
                        </li>
                        <li class="break">
                            <hr>
                        </li>
                        <li><span>Добро пожаловать на сервис Enbek.kz</span></li>
                        <li class="break">
                            <hr>
                        </li>
                        <li><a href="/notifications.html" title="Все уведомления" class="blue">Все уведомления <i
                                        class="icon-chevron-right"> </i></a></li>
                    </ul>
                </div>
            </div>
            <div class="header-dropdown profile">
                <div class="header-dropdown__title">
                    <img src="{{Auth::user()->author_info->avatar}}" alt=""><i class="icon-chevron-down"> </i>
                </div>
                <div class="header-dropdown__desc">
                    <div class="name">{{Auth::user()->author_info->name . ' ' . Auth::user()->author_info->surname}}</div>
                    <hr>
                    <ul>
                        <li><a href="/{{$lang}}/my-courses/statistics"
                               title="{{__('default.pages.statistics.title')}}">{{__('default.pages.statistics.title')}}</a>
                        </li>
                        <li><a href="#"
                               title="{{__('default.pages.profile.title')}}">{{__('default.pages.profile.title')}}</a>
                        </li>
                        <li><a href="/{{$lang}}/my-courses"
                               title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
                        </li>
                        <li><a href="#"
                               title="{{__('default.pages.dialogs.title')}}">{{__('default.pages.dialogs.title')}}</a>
                        </li>
                        <li><a href="/{{$lang}}/my-courses/reporting"
                               title="{{__('default.pages.reporting.title')}}">{{__('default.pages.reporting.title')}}</a>
                        </li>
                        <li><a href="#" title="{{__('notifications.title')}}">{{__('notifications.title')}}</a></li>
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
                <i class="icon-notification"> </i>
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
                <img src="/assets/img/avatar.png" alt="">
                <div class="name">Евгений Гурьев</div>
            </div>
            <hr>
            <ul class="header-mobile__menu">
                <li><a href="/{{$lang}}/my-courses/statistics"
                       title="{{__('default.pages.statistics.title')}}">{{__('default.pages.statistics.title')}}</a>
                </li>
                <li class="active"><a href="#" title="Профиль">Профиль</a></li>
                <li><a href="#" title="Мои курсы">Мои курсы</a></li>
                <li><a href="#" title="Мои диалоги">Мои диалоги</a></li>
                <li><a href="/{{$lang}}/my-courses/reporting"
                       title="{{__('default.pages.reporting.title')}}">{{__('default.pages.reporting.title')}}</a></li>
                <li><a href="#" title="Уведомления">Уведомления</a></li>
                <li><a href="#" title="Выйти">Выйти</a></li>
            </ul>
            <hr>
            <a href="#" title="Помощь" class="ghost-btn ghost-btn--blue">Помощь</a>
            <ul class="mob-language">
                <li><a href="#" title="KZ">KZ</a></li>
                <li><a href="#" title="RU">RU</a></li>
                <li><a href="#" title="EN">EN</a></li>
            </ul>
        </div>
    </div>
</div>