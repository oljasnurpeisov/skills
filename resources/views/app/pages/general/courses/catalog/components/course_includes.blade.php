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