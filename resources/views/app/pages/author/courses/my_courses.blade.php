@extends('app.layout.default.template')

@section('content')
    <main class="main">
        <section class="plain">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
            <div class="container">
                <div class="title-block">
                    <div class="row row--multiline align-items-center">
                        <div class="col-auto"><h1
                                    class="title-primary">{{__('default.pages.courses.my_courses_title')}}</h1></div>
                        <div class="col-auto">
                            <div class="ghost-btn ghost-btn--blue"><a href="/{{$lang}}/create-course">{{__('default.pages.courses.create_course')}}</a></div>
                        </div>
                    </div>
                </div>

                <div class="mobile-dropdown">
                    <div class="mobile-dropdown__title dynamic">{{__('default.pages.courses.my_courses')}}</div>
                    <div class="mobile-dropdown__desc">
                        <ul class="tabs-links">
                            <li @if($page_name == 'default.pages.courses.my_courses')class="active"@endif><a href="/{{$lang}}/my-courses"
                                                  title="{{__('default.pages.courses.my_courses')}}">{{__('default.pages.courses.my_courses')}}</a>
                            </li>
                            <li @if($page_name == 'default.pages.courses.my_courses_unpublished')class="active"@endif><a href="/{{$lang}}/my-courses/unpublished"
                                   title="{{__('default.pages.courses.my_courses_unpublished')}}">{{__('default.pages.courses.my_courses_unpublished')}}</a>
                            </li>
                            <li @if($page_name == 'default.pages.courses.my_courses_onCheck')class="active"@endif><a href="/{{$lang}}/my-courses/on-check"
                                   title="{{__('default.pages.courses.my_courses_onCheck')}}">{{__('default.pages.courses.my_courses_onCheck')}}</a>
                            </li>
                            <li @if($page_name == 'default.pages.courses.drafts')class="active"@endif><a href="/{{$lang}}/my-courses/drafts"
                                   title="{{__('default.pages.courses.drafts')}}">{{__('default.pages.courses.drafts')}}</a>
                            </li>
                            <li @if($page_name == 'default.pages.courses.my_courses_deleted')class="active"@endif><a href="/{{$lang}}/my-courses/deleted"
                                   title="{{__('default.pages.courses.my_courses_deleted')}}">{{__('default.pages.courses.my_courses_deleted')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row row--multiline">
                    <div class="col-md-8">
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
                                                    <i class="icon-user"> </i><span>{{count($item->course_members)}}</span>
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
                            {{ $items->links('vendor.pagination.default') }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <form class="sidebar">
                            <div class="sidebar__inner">
                                <div class="sidebar-item">
                                    <div class="sidebar-item__title">Профессия:</div>
                                    <div class="sidebar-item__body">
                                        <select name="speciality" placeholder="Выберите профессию"
                                                data-method="getProfessionsByName"
                                                class="custom" multiple> </select>
                                    </div>
                                </div>
                                <div class="sidebar-item">
                                    <div class="sidebar-item__title">Навык:</div>
                                    <div class="sidebar-item__body">
                                        <select name="skills" placeholder="Выберите навык" data-method="getSkillsByData"
                                                class="custom" multiple> </select>
                                    </div>
                                </div>
                                <div class="sidebar-item">
                                    <div class="sidebar-item__title">Язык обучения:</div>
                                    <div class="sidebar-item__body">
                                        <label class="checkbox"><input type="checkbox" name="lang"
                                                                       value="kk"><span>Казахский</span></label>
                                        <label class="checkbox"><input type="checkbox" name="lang"
                                                                       value="ru"><span>Русский</span></label>
                                    </div>
                                </div>
                                <div class="sidebar-item">
                                    <div class="sidebar-item__title">Рейтинг от:</div>
                                    <div class="sidebar-item__body">
                                        <div class="range-slider-wrapper">
                                            <input type="range" class="range-slider single-range-slider" name="rating" min="1"
                                                   data-decimals="1" step="0.5" max="5" value="3">
                                        </div>
                                    </div>
                                </div>
                                <div class="sidebar-item">
                                    <div class="sidebar-item__title">Учеников, окончивших курс (мин):</div>
                                    <div class="sidebar-item__body">
                                        <div class="range-slider-wrapper">
                                            <input type="range" class="range-slider single-range-slider" name="studentsCount" min="1"
                                                   data-decimals="0" step="1" max="30" value="10">
                                        </div>
                                    </div>
                                </div>
                                <div class="sidebar-item">
                                    <div class="sidebar-item__title">Вид курса:</div>
                                    <div class="sidebar-item__body">
                                        <select name="courseType" class="selectize-regular custom" placeholder="Выберите вид курса" >
                                            <option value="">По умолчанию</option>
                                            <option value="Платные">Платные</option>
                                            <option value="Бесплатные">Бесплатные</option>
                                            <option value="По квоте">По квоте</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="sidebar-item">
                                    <div class="sidebar-item__title">Сортировка:</div>
                                    <div class="sidebar-item__body">
                                        <select name="sort" placeholder="Выберите сортировку" class="selectize-regular custom">
                                            <option value="">По умолчанию</option>
                                            <option value="Рейтинг - по убыванию">Рейтинг - по убыванию</option>
                                            <option value="Рейтинг - по возрастанию">Рейтинг - по возрастанию</option>
                                            <option value="Стоимость - по убыванию">Стоимость - по убыванию</option>
                                            <option value="Стоимость - по возрастанию">Стоимость - по возрастанию</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="sidebar__buttons">
                                <button type="submit" class="sidebar-btn">Применить</button>
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
        const specialityEl = $('[name="speciality"]'),
            skillsEl = $('[name="skills"]');

        let specialitySelect = new ajaxSelect(specialityEl);
        let skillsSelect = new ajaxSelect(skillsEl, specialityEl);

        specialityEl.change(function () {
            skillsSelect.update($(this).val() ? {"professions": toArray($(this).val())} : null);
        })
    </script>
    <!---->
@endsection

