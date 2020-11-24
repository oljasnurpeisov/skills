@extends('app.layout.default.template')

@section('content')
    <main class="main">
        <section class="plain">
            <div class="container">
                <div class="title-block">
                    <div class="row row--multiline align-items-center">
                        <div class="col-auto"><h1
                                    class="title-primary">{{__('default.pages.courses.my_courses_title')}}</h1></div>
                        <div class="col-auto">
                            <a href="/{{$lang}}/create-course">
                                <div class="ghost-btn ghost-btn--blue">{{__('default.pages.courses.create_course')}}
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mobile-dropdown">
                    <div class="mobile-dropdown__title dynamic">{{__('default.pages.courses.my_courses')}}</div>
                    <div class="mobile-dropdown__desc">
                        <ul class="tabs-links">
                            <li @if($page_name == 'default.pages.courses.my_courses')class="active"@endif><a
                                        href="/{{$lang}}/my-courses"
                                        title="{{__('default.pages.courses.my_courses')}}">{{__('default.pages.courses.my_courses')}}</a>
                            </li>
                            <li @if($page_name == 'default.pages.courses.my_courses_unpublished')class="active"@endif><a
                                        href="/{{$lang}}/my-courses/unpublished"
                                        title="{{__('default.pages.courses.my_courses_unpublished')}}">{{__('default.pages.courses.my_courses_unpublished')}}</a>
                            </li>
                            <li @if($page_name == 'default.pages.courses.my_courses_onCheck')class="active"@endif><a
                                        href="/{{$lang}}/my-courses/on-check"
                                        title="{{__('default.pages.courses.my_courses_onCheck')}}">{{__('default.pages.courses.my_courses_onCheck')}}</a>
                            </li>
                            <li @if($page_name == 'default.pages.courses.drafts')class="active"@endif><a
                                        href="/{{$lang}}/my-courses/drafts"
                                        title="{{__('default.pages.courses.drafts')}}">{{__('default.pages.courses.drafts')}}</a>
                            </li>
                            <li @if($page_name == 'default.pages.courses.my_courses_deleted')class="active"@endif><a
                                        href="/{{$lang}}/my-courses/deleted"
                                        title="{{__('default.pages.courses.my_courses_deleted')}}">{{__('default.pages.courses.my_courses_deleted')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row row--multiline">
                    <div class="col-md-8">
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
                            @foreach($items as $item)
                                <div class="col-sm-6 col-md-4">
                                    <a href="/{{$lang}}/my-courses/course/{{$item->id}}" title="" class="card">
                                        @if($item->quota_status == 2)
                                            <div class="card__quota mark mark--yellow">{{__('default.pages.courses.access_by_quota')}}</div>
                                        @endif
                                        <div class="card__image">
                                            <img src="{{$item->image}}" alt="">
                                        </div>
                                        <div class="card__desc">
                                            <div class="card__top">
                                                @if($item->is_paid == true)
                                                    <div class="card__price mark mark--blue">{{number_format($item->cost, 0, ',', ' ')}} {{__('default.tenge_title')}}</div>
                                                @else
                                                    <div class="card__price mark mark--green">{{__('default.pages.courses.free_title')}}</div>
                                                @endif
                                                <h3 class="card__title">{{$item->name}}</h3>
                                                <div class="card__author">{{$item->user->company_name}}</div>
                                            </div>
                                            <div class="card__bottom">
                                                <div class="card__attribute">
                                                    <i class="icon-user"> </i><span>{{count($item->course_members->whereIn('paid_status', [1,2]))}}</span>
                                                </div>
                                                <div class="card__attribute">
                                                    <i class="icon-star-full"> </i><span>{{$item->rate->pluck('rate')->avg() ?? 0}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach

                        </div>
                        <div class="text-center">
                            {{ $items->appends(request()->input())->links('vendor.pagination.default') }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <form class="sidebar">
                            <div class="sidebar__inner">
                                <div class="sidebar-item">
                                    <div class="sidebar-item__title">{{__('default.pages.courses.profession')}}:</div>
                                    <div class="sidebar-item__body">
                                        <select name="specialities[]"
                                                placeholder="{{__('default.pages.courses.choose_profession')}}"
                                                data-method="getProfessionsByName"
                                                class="custom" multiple>
                                            @if(!empty($request->specialities))
                                                @foreach($professions as $profession)
                                                    <option value="{{$profession->id}}"
                                                            selected>{{$profession->getAttribute('name_'.$lang ?? 'name_ru')}}</option>
                                                @endforeach
                                            @endif
                                        </select>


                                    </div>
                                </div>
                                <div class="sidebar-item">
                                    <div class="sidebar-item__title">{{__('default.pages.courses.skill')}}:</div>
                                    <div class="sidebar-item__body">
                                        <select name="skills[]"
                                                placeholder="{{__('default.pages.courses.choose_skill')}}"
                                                data-method="getSkillsByData"
                                                class="custom" multiple>
                                            @if(!empty($request->skills))
                                                @foreach($skills as $skill)
                                                    <option value="{{$skill->id}}"
                                                            selected>{{$skill->getAttribute('name_'.$lang ?? 'name_ru')}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="sidebar-item">
                                    <div class="sidebar-item__title">{{__('default.pages.courses.language_education')}}
                                        :
                                    </div>
                                    <div class="sidebar-item__body">
                                        <label class="checkbox"><input type="checkbox" name="lang_kk"
                                                                       value="1" {{($request->lang_kk == 1 ? ' checked' : '')}}><span>{{__('default.pages.courses.language_education_kk')}}</span></label>
                                        <label class="checkbox"><input type="checkbox" name="lang_ru"
                                                                       value="1" {{($request->lang_ru == 1 ? ' checked' : '')}}><span>{{__('default.pages.courses.language_education_ru')}}</span></label>
                                    </div>
                                </div>
                                <div class="sidebar-item">
                                    <div class="sidebar-item__title">{{__('default.pages.courses.rating_from')}}:</div>
                                    <div class="sidebar-item__body">
                                        <div class="range-slider-wrapper">
                                            <input type="range" class="range-slider single-range-slider"
                                                   name="min_rating" min="0"
                                                   data-decimals="1" step="0.5" max="5"
                                                   value="{{$request->min_rating ?? 0}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="sidebar-item">
                                    <div class="sidebar-item__title">{{__('default.pages.courses.students_complete_course')}}
                                        :
                                    </div>
                                    <div class="sidebar-item__body">
                                        <div class="range-slider-wrapper">
                                            <input type="range" class="range-slider single-range-slider"
                                                   name="members_count" min="0"
                                                   data-decimals="0" step="1" max="30"
                                                   value="{{$request->members_count ?? 0}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="sidebar-item">
                                    <div class="sidebar-item__title">{{__('default.pages.courses.course_type')}}:</div>
                                    <div class="sidebar-item__body">
                                        <select name="course_type" class="selectize-regular custom"
                                                placeholder="{{__('default.pages.courses.choose_course_type')}}">
                                            <option value="">{{__('default.pages.courses.sort_by_default')}}</option>
                                            <option value="1" {{($request->course_type == 1 ? 'selected' : '')}}>{{__('default.pages.courses.paid_type')}}</option>
                                            <option value="2" {{($request->course_type == 2 ? 'selected' : '')}}>{{__('default.pages.courses.free_type')}}</option>
                                            <option value="3" {{($request->course_type == 3 ? 'selected' : '')}}>{{__('default.pages.courses.quota_type')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="sidebar-item">
                                    <div class="sidebar-item__title">{{__('default.pages.courses.sorting')}}:</div>
                                    <div class="sidebar-item__body">
                                        <select name="course_sort"
                                                placeholder="{{__('default.pages.courses.choose_sort_type')}}"
                                                class="selectize-regular custom">
                                            <option value="">{{__('default.pages.courses.sort_by_default')}}</option>
                                            <option value="sort_by_rate_high" {{($request->course_sort == 'sort_by_rate_high' ? 'selected' : '')}}>{{__('default.pages.courses.sort_by_rate_high')}}</option>
                                            <option value="sort_by_rate_low" {{($request->course_sort == 'sort_by_rate_low' ? 'selected' : '')}}>{{__('default.pages.courses.sort_by_rate_low')}}</option>
                                            <option value="sort_by_cost_high" {{($request->course_sort == 'sort_by_cost_high' ? 'selected' : '')}}>{{__('default.pages.courses.sort_by_cost_high')}}</option>
                                            <option value="sort_by_cost_low" {{($request->course_sort == 'sort_by_cost_low' ? 'selected' : '')}}>{{__('default.pages.courses.sort_by_cost_low')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="sidebar__buttons">
                                <button type="submit"
                                        class="sidebar-btn">{{__('default.pages.courses.apply_title')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script>
        const specialityEl = $('[name="specialities[]"]'),
            skillsEl = $('[name="skills[]"]');

        let specialitySelect = new ajaxSelect(specialityEl);
        let skillsSelect = new ajaxSelect(skillsEl, specialityEl);

        specialityEl.change(function () {
            skillsSelect.update($(this).val() ? {"professions": toArray($(this).val())} : null);
        })
    </script>
    <!---->
@endsection

