@extends('app.layout.default.template')

@section('content')
    <main class="main">
        <section class="plain">
            <div class="container">
                <h1 class="title-primary">{{__('default.pages.statistics.title')}}</h1>
                <div class="row row--multiline align-items-center">
                    <div class="col-auto">
                        <div class="author-stat">
                            <span class="author-stat__label">{{__('default.pages.statistics.your_rating')}}</span>
                            <span class="rating">
                    <span class="rating__number">{{round($average_rates, 1)}}</span>
                                <span class="rating__stars">
                                        <?php
                                    for ($x = 1; $x <= $average_rates; $x++) {
                                        echo '<i class="icon-star-full"> </i>';
                                    }
                                    if (strpos($average_rates, '.')) {
                                        echo '<i class="icon-star-half"> </i>';
                                        $x++;
                                    }
                                    while ($x <= 5) {
                                        echo '<i class="icon-star-empty"> </i>';
                                        $x++;
                                    }
                                    ?>
                                    </span>
                </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="author-stat">
                            <span class="author-stat__label">{{__('default.pages.statistics.course_members_count')}}:</span>
                            <span class="author-stat__value">{{count($author_students)}}</span>
                        </div>
                    </div>
                </div>
                <row class="row row--multiline">
                    <div class="col-sm-6">
                        <div class="income">
                            <div class="income__label">{{__('default.pages.statistics.total_earn')}}</div>
                            <div class="income__value green">{{number_format(array_sum($all_cost_courses), 0, ',', ' ')}} {{__('default.tenge_title')}}</div>
                            <img src="/assets/img/budget.svg" alt="" class="income__icon">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="income">
                            <div class="income__label">{{__('default.pages.statistics.earn_quota')}}</div>
                            <div class="income__value yellow">{{number_format(array_sum($quota_cost_courses), 0, ',', ' ')}} {{__('default.tenge_title')}}</div>
                            <img src="/assets/img/gift.svg" alt="" class="income__icon">
                        </div>
                    </div>
                </row>
            </div>
        </section>

        <section class="plain">
            <div class="container">
                <div class="row row--multiline">
                    <div class="col-md-3 col-sm-4">
                        <div class="form-group">
                            <label class="form-group__label">{{__('default.pages.statistics.date_from')}}:</label>
                            <div class="input-group">
                                <input type="text" name="dateFrom"
                                       placeholder=""
                                       class="input-regular">
                                <i class="icon-calendar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4">
                        <div class="form-group">
                            <label class="form-group__label">{{__('default.pages.statistics.date_to')}}:</label>
                            <div class="input-group">
                                <input type="text" name="dateTo" placeholder=""
                                       class="input-regular">
                                <i class="icon-calendar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4">
                        <div class="form-group">
                            <label class="form-group__label hidden-xs">&nbsp;</label>
                            <div class="input-group">
                                <a href="#" title="" id="clear" class="ghost-btn">Сбросить</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="income-diagram">
                    @if(Auth::user()->id == 46)
                        <div id="chartdiv" data-url="/{{$lang}}/statisticForChartDemo"></div>
                    @else
                        <div id="chartdiv" data-url="/{{$lang}}/my-courses/statistics/statisticForChart"></div>
                    @endif
                </div>
            </div>
        </section>

        <section class="plain">
            <div class="container">
                <div class="title-block">
                    <div class="row row--multiline align-items-center justify-space-between">
                        <div class="col-md-8 col-sm-7">
                            <h2 class="title-primary">{{__('default.pages.courses.my_courses_title')}}</h2>
                        </div>
                        <div class="col-md-4 col-sm-5">
                            <form action="">
                                @php($filters = array('sort_by_default','sort_by_rate_low',
                                    'sort_by_rate_high', 'sort_by_members_count_low',
                                    'sort_by_members_count_high'))
                                <select name="sort_by" class="selectize-regular no-search"
                                        placeholder="{{__('default.pages.statistics.sort_by_title')}}"
                                        onchange="$(this).closest('form').submit()">
                                    @foreach($filters as $filter)
                                        <option value="{{ $filter }}"
                                                @if($request->sort_by == $filter) selected @endif >
                                            {{ __('default.pages.statistics.'.$filter) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row row--multiline">
                    @foreach($courses as $course)
                        <div class="col-sm-6 col-md-3">
                            <a href="/{{$lang}}/my-courses/course/{{$course->id}}" title="" class="card">
                                @if($course->quota_status == 2)
                                    <div class="card__quota mark mark--yellow">{{__('default.pages.courses.access_by_quota')}}</div>
                                @endif
                                <div class="card__image">
                                    <img src="{{$course->image}}" alt="">
                                </div>
                                <div class="card__desc">
                                    <div class="card__top">
                                        @if($course->is_paid == true)
                                            <div class="card__price mark mark--blue">{{number_format($course->cost, 0, ',', ' ')}} {{__('default.tenge_title')}}</div>
                                        @else
                                            <div class="card__price mark mark--green">{{__('default.pages.courses.free_title')}}</div>
                                        @endif
                                        <h3 class="card__title">{{$course->name}}</h3>
                                        <div class="card__author">{{$course->user->company_name}}</div>
                                    </div>
                                    <div class="card__bottom">
                                        <div class="card__attribute">
                                            <i class="icon-user"> </i><span>{{count($course->course_members->whereIn('paid_status', [1,2]))}}</span>
                                        </div>
                                        <div class="card__attribute">
                                            <i class="icon-star-full"> </i><span>{{round($course->rate->pluck('rate')->avg() ?? 0, 1)}}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="text-center">
                    {{ $courses->links('vendor.pagination.default') }}
                </div>
            </div>
        </section>

    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script src="/assets/libs/amcharts4/core.js"></script>
    <script src="/assets/libs/amcharts4/charts.js"></script>
    <script src="/assets/libs/amcharts4/themes/animated.js"></script>
    <script src="/assets/libs/amcharts4/lang/ru_RU.js"></script>
    <script>
        renderAuthorStats(document.querySelector('#chartdiv'));
    </script>
    <!---->
@endsection

