<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    .dropdown_notification {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        padding: 12px 16px;
        z-index: 1;
        right: 0;
    }

    .dropdown_notification:hover .dropdown-content {
        display: block;
    }
</style>

<header class="header">
    <div class="container container-fluid">
        <a href="javascript:;" title="Свернуть/развернуть навигацию" class="menu-btn icon-menu"></a>
        <a href="/admin" title="Главная" class="logo hidden-md hidden-lg">
            {{--<img src="/assets/admin/img/logo-blue.svg" alt="">--}}

        </a>
        <div class="language hidden-sm hidden-xs">
            {{--<div class="language hidden-sm hidden-xs">--}}
            {{--<a href="#" title="РУС" class="active">РУС</a>--}}
            {{--<a href="#" title="ENG">ENG</a>--}}
            {{--<a href="#" title="QAZ">QAZ</a>--}}
            {{--</div>--}}
        </div>

        <div class="header-dropdown account-nav">
            <div class="header-dropdown__title">
                @php($user = \Illuminate\Support\Facades\Auth::user())
                <span>{{ __('admin.labels.welcome') }}, {{ $user !== null ? $user->name : '' }}!</span>
                <img src="/assets/admin/img/user.svg" alt=""> <i class="icon-chevron-down"></i>
            </div>
            <div class="header-dropdown__desc">
                <ul>
                    <li><a href="/{{$lang}}/admin/profile/">Профиль</a></li>
                    <li>
                        <a href="/admin/logout"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                    class="fa fa-sign-out pull-right"></i>{{ __('admin.labels.logout') }}</a>
                        <form id="logout-form" action="{{ url('/admin/logout') }}" method="POST"
                              style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </div>
        </div>
{{--        <div class="dropdown_notification">--}}
{{--            @php($notifications = Auth::user()->notifications()->get())--}}
{{--            <div class="hidden-sm hidden-xs" style="margin-top: 20px; margin-left: 25px;">--}}
{{--                <a> <i class="fa fa-bell" aria-hidden="true"></i>{{count($notifications)}}</a>--}}
{{--            </div>--}}
{{--            <div class="dropdown-content" style="margin: 15px">--}}
{{--                @foreach($notifications as $notification)--}}
{{--                        <p>{{trans($notification->name, ['course_name' => '"'.$notification->course->name.'"'])}}</p>--}}
{{--                        <hr>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        </div>--}}

    </div>
</header>
