@switch($item->status)
    @case(\App\Models\Course::published)
    <li><a href="/{{$lang}}/my-courses"
           title="{{__('default.pages.courses.my_courses')}}">{{__('default.pages.courses.my_courses')}}</a>
    </li>
    @break
    @case(\App\Models\Course::unpublished)
    <li><a href="/{{$lang}}/my-courses/unpublished"
           title="{{__('default.pages.courses.my_courses_unpublished')}}">{{__('default.pages.courses.my_courses_unpublished')}}</a>
    </li>
    @break
    @case(\App\Models\Course::onCheck)
    <li><a href="/{{$lang}}/my-courses/on-check"
           title="{{__('default.pages.courses.my_courses_onCheck')}}">{{__('default.pages.courses.my_courses_onCheck')}}</a>
    </li>
    @break
    @case(\App\Models\Course::draft)
    <li><a href="/{{$lang}}/my-courses/drafts"
           title="{{__('default.pages.courses.drafts')}}">{{__('default.pages.courses.drafts')}}</a>
    </li>
    @break
    @case(\App\Models\Course::deleted)
    <li><a href="/{{$lang}}/my-courses/deleted"
           title="{{__('default.pages.courses.my_courses_deleted')}}">{{__('default.pages.courses.my_courses_deleted')}}</a>
    </li>
    @break
    @default
@endswitch
