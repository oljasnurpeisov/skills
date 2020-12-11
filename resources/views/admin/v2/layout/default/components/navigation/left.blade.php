<aside class="sidebar">
    <div class="sidebar__top hidden-sm hidden-xs">
        {{--<a href="/admin" title="Главная" class="logo"><img src="/assets/admin/img/logo.svg" alt=""></a>--}}
        <a href="/{{$lang}}/admin" title="Главная" class="logo" style="color:white"><img src="/assets/img/logo.svg" alt=""></a>
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
                    <li><a href="/{{$lang}}/admin/user/index">{{ __('admin.pages.users.admin_list') }}</a></li>
                    @hasPermission('admin.authors')
                    <li><a href="/{{$lang}}/admin/author/index">{{ __('admin.pages.authors.list') }}</a></li>
                    @endhasPermission
                    <li><a href="/{{$lang}}/admin/student/index">{{ __('admin.pages.students.list') }}</a></li>
                    <li><a href="/{{$lang}}/admin/user/index_all">{{ __('admin.pages.users.list') }}</a></li>
                    <li><a href="/{{$lang}}/admin/user/create" class="add">+{{ __('admin.pages.users.create') }}</a></li>
                </ul>
            </li>
            @endhasPermission
            @hasPermission('admin.courses')
            <li class="dropdown">
                <a href="javascript:;" title="{{ __('admin.pages.courses.title') }}">
                    <i class="icon-reports"></i> {{ __('admin.pages.courses.title') }}
                </a>
                <ul>
                    <li><a href="/{{$lang}}/admin/courses/deleted">{{ __('admin.pages.courses.deleted_list') }}</a></li>
                    <li><a href="/{{$lang}}/admin/courses/drafts">{{ __('admin.pages.courses.drafts_list') }}</a></li>
                    <li><a href="/{{$lang}}/admin/courses/wait_verification">{{ __('admin.pages.courses.wait_publish_list') }}</a></li>
                    <li><a href="/{{$lang}}/admin/courses/unpublished">{{ __('admin.pages.courses.unpublish_list') }}</a></li>
                    <li><a href="/{{$lang}}/admin/courses/published">{{ __('admin.pages.courses.publish_list') }}</a></li>
                    <li><a href="/{{$lang}}/admin/courses/index">{{ __('admin.pages.courses.list') }}</a></li>
                </ul>
            </li>
            @endhasPermission
            @hasPermission('admin.pages')
            <li class="dropdown">
                <a href="javascript:;" title="{{ __('admin.pages.static_pages.title') }}">
                    <i class="icon-reports"></i> {{ __('admin.pages.static_pages.title') }}
                </a>
                <ul>
                    <li><a href="/{{$lang}}/admin/static-pages/main">{{ __('admin.pages.static_pages.main') }}</a></li>
                    <li><a href="/{{$lang}}/admin/static-pages/for-authors">{{ __('admin.pages.static_pages.for_authors') }}</a></li>
                </ul>
            </li>
            @endhasPermission
        </ul>
    </div>
</aside>
