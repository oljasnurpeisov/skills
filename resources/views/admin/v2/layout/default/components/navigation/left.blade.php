<aside class="sidebar">
    <div class="sidebar__top hidden-sm hidden-xs">
        {{--<a href="/admin" title="Главная" class="logo"><img src="/assets/admin/img/logo.svg" alt=""></a>--}}
        <a href="/admin" title="Главная" class="logo" style="color:white"><img src="" alt="">logo</a>
    </div>
    <div class="menu-wrapper">
        <ul class="menu">
            @hasPermission('admin.roles')
            <li class="dropdown">
                <a href="javascript:;" title="{{ __('admin.pages.roles.title') }}">
                    <i class="icon-users"></i> {{ __('admin.pages.roles.title') }}
                </a>
                <ul>
                    <li><a href="/{{$lang}}/admin/role/index">{{ __('admin.pages.roles.list') }}</a></li>
                    <li><a href="/{{$lang}}/admin/role/create" class="add">+{{ __('admin.pages.roles.create') }}</a></li>
                </ul>
            </li>
            @endhasPermission
            @hasPermission('admin.users')
            <li class="dropdown">
                <a href="javascript:;" title="{{ __('admin.pages.users.title') }}">
                    <i class="icon-users"></i> {{ __('admin.pages.users.title') }}
                </a>
                <ul>
                    <li><a href="/{{$lang}}/admin/user/index">{{ __('admin.pages.users.list') }}</a></li>
                    <li><a href="/{{$lang}}/admin/user/create" class="add">+{{ __('admin.pages.users.create') }}</a></li>
                </ul>
            </li>
            @endhasPermission
            @hasPermission('admin.authors')
            <li class="dropdown">
                <a href="javascript:;" title="{{ __('admin.pages.authors.title') }}">
                    <i class="icon-users"></i> {{ __('admin.pages.authors.title') }}
                </a>
                <ul>
                    <li><a href="/{{$lang}}/admin/author/index">{{ __('admin.pages.authors.list') }}</a></li>
                    {{--<li><a href="/{{$lang}}/admin/author/create" class="add">+{{ __('admin.pages.authors.create') }}</a></li>--}}
                </ul>
            </li>
            @endhasPermission
            @hasPermission('admin.courses')
            <li class="dropdown">
                <a href="javascript:;" title="{{ __('admin.pages.courses.title') }}">
                    <i class="icon-reports"></i> {{ __('admin.pages.courses.title') }}
                </a>
                <ul>
                    <li><a href="/{{$lang}}/admin/courses/index">{{ __('admin.pages.courses.list') }}</a></li>
                </ul>
            </li>
            @endhasPermission

        </ul>
    </div>
</aside>
