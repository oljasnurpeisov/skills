<aside class="sidebar">
    <div class="sidebar__top hidden-sm hidden-xs">
        {{--<a href="/admin" title="Главная" class="logo"><img src="/assets/admin/img/logo.svg" alt=""></a>--}}
        <a href="/{{$lang}}/admin" title="Главная" class="logo" style="color:white">
            <picture>
                <source srcset="{{ asset('/assets/img/logo_new.webp') }}" type="image/webp">
                <img src="{{ asset('/assets/img/logo_new.png') }}" alt="" />
            </picture>
        </a>
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
                    <li><a href="/{{$lang}}/admin/role/create" class="add">+{{ __('admin.pages.roles.create') }}</a>
                    </li>
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
                    <li><a href="/{{$lang}}/admin/user/create" class="add">+{{ __('admin.pages.users.create') }}</a>
                    </li>
                </ul>
            </li>
            @endhasPermission
            @hasPermission('admin.courses')
            <li class="dropdown">
                <a href="javascript:;" title="{{ __('admin.pages.courses.title') }}">
                    <i class="icon-reports"></i> {{ __('admin.pages.courses.title') }}
                </a>
                <ul>
                    <li><a href="/{{$lang}}/admin/courses/wait_verification">{{ __('admin.pages.courses.wait_publish_list') }}</a></li>
                    <li><a href="{{ route('admin.courses.wait_check_contracts', ['lang' => $lang]) }}">Ожидающие проверки договора</a></li>
                    <li><a href="{{ route('admin.courses.wait_signing_author', ['lang' => $lang]) }}">Ожидающие подписания договора со стороны Автора</a></li>
                    <li><a href="{{ route('admin.courses.wait_signing_admin', ['lang' => $lang]) }}">Ожидающие подписания договора со стороны Администрации</a></li>
                    <li><a href="/{{$lang}}/admin/courses/published">{{ __('admin.pages.courses.publish_list') }}</a></li>
                    <li><a href="/{{$lang}}/admin/courses/unpublished">{{ __('admin.pages.courses.unpublish_list') }}</a></li>
                    <li><a href="/{{$lang}}/admin/courses/deleted">{{ __('admin.pages.courses.deleted_list') }}</a></li>
                    <li><a href="/{{$lang}}/admin/courses/drafts">{{ __('admin.pages.courses.drafts_list') }}</a></li>
                    <li><a href="/{{$lang}}/admin/courses/index">{{ __('admin.pages.courses.list') }}</a></li>
                </ul>
            </li>
            @endhasPermission
            @hasPermission('admin.contracts')
            <li class="dropdown">
                <a href="javascript:;" title="Договоры">
                    <i class="icon-reports"></i> Договоры
                </a>
                <ul>
                    <li><a href="{{ route('admin.contracts.all', ['lang' => $lang]) }}">Все договоры</a></li>
                    <li><a href="{{ route('admin.contracts.pending', ['lang' => $lang]) }}">Ожидающие подписания</a></li>
                    <li><a href="{{ route('admin.contracts.signed', ['lang' => $lang]) }}">Подписаны</a></li>
                    <li><a href="{{ route('admin.contracts.distributed', ['lang' => $lang]) }}">Расторгнуты</a></li>
                    <li><a href="{{ route('admin.contracts.rejected_by_author', ['lang' => $lang]) }}">Отклонены автором</a></li>
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
                    <li>
                        <a href="/{{$lang}}/admin/static-pages/for-authors">{{ __('admin.pages.static_pages.for_authors') }}</a>
                    </li>
                    <li>
                        <a href="/{{$lang}}/admin/static-pages/faq-index">{{ __('admin.pages.static_pages.faq') }}</a>
                    </li>
                    <li>
                        <a href="/{{$lang}}/admin/static-pages/course-catalog">{{ __('admin.pages.static_pages.course_catalog') }}</a>
                    </li>
                    <li>
                        <a href="/{{$lang}}/admin/static-pages/help-index">{{ __('admin.pages.static_pages.help') }}</a>
                    </li>
                    <li>
                        <a href="/{{$lang}}/admin/static-pages/calculator">{{ __('admin.pages.static_pages.calculator') }}</a>
                    </li>
                </ul>
            </li>
            @endhasPermission
            @hasPermission('admin.reports')
            <li class="dropdown">
                <a href="javascript:;" title="{{ __('admin.pages.reports.title') }}">
                    <i class="icon-reports"></i> {{ __('admin.pages.reports.title') }}
                </a>
                <ul>
                    <li><a href="/{{$lang}}/admin/reports/authors">{{ __('admin.pages.reports.authors_report') }}</a>
                    </li>
                    <li><a href="/{{$lang}}/admin/reports/courses">{{ __('admin.pages.reports.courses_report') }}</a>
                    </li>
                    <li><a href="/{{$lang}}/admin/reports/students">{{ __('admin.pages.reports.students_report') }}</a>
                    </li>
                    <li><a href="/{{$lang}}/admin/reports/certificates">{{ __('admin.pages.reports.certificates_report') }}</a>
                    </li>
                </ul>
            </li>
            @endhasPermission
            @hasPermission('admin.tech_support')
                <li class="dropdown">
                    <a href="javascript:;" title="{{ __('admin.pages.dialogs.title') }}">
                        <i class="fa fa-comments"></i> {{ __('admin.pages.dialogs.title') }}
                    </a>
                    <ul>
                        <li>
                            <a href="/{{$lang}}/admin/dialogs">{{ __('admin.pages.dialogs.list') }}</a>
                        </li>
                    </ul>
                </li>
            @endhasPermission
            <li class="dropdown">
                <a href="javascript:;" title="{{ __('admin.pages.help.title') }}">
                    <i class="fa fa-question"></i> {{ __('admin.pages.help.title') }}
                </a>
                <ul>
                    <li>
                        <a href="/assets/admin/video/instruction.mp4"
                           target="_blank">{{ __('admin.pages.help.video') }}</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</aside>
