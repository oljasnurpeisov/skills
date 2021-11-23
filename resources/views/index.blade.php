@extends('app.layout.default.template')

@section('content')

    <main class="main">
        <section class="opening"
                 style="background: url({{json_decode($content->getAttribute('data_'.$lang))->main_banner->image}}) center center no-repeat; background-size: cover;">
            <div class="container">
                <h1>{!! json_decode($content->getAttribute('data_'.$lang))->main_banner->title !!}<br/><span>
                        {!! json_decode($content->getAttribute('data_'.$lang))->main_banner->teaser !!}</span></h1>
                <form class="opening-form" action="/{{$lang}}/course-catalog">
                    <div class="row row--multiline">
                        <div class="col-md-2">
                            <div class="opening-form__title">
                                {!! __('default.pages.index.find_your_course') !!}
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row row--multiline">
                                <div class="col-md-4">
                                    <label class="form-group__label">{{__('default.pages.courses.choose_professional_area')}}</label>
                                    <select
                                        name="professional_areas[]"
                                        placeholder="{{__('default.pages.courses.choose_professional_area')}}"
                                        data-method="getProfessionalAreaByName"
                                        data-default="{{__('default.pages.courses.sort_by_default')}}"
                                        class="white"
                                        data-noresults="{{__('default.pages.index.nothing_to_show')}}"> </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-group__label">{{__('default.pages.courses.choose_profession')}}</label>
                                    <select name="specialities[]" placeholder="{{__('default.pages.courses.choose_profession')}}" data-method="getProfessionsByData" data-default="{{__('default.pages.courses.sort_by_default')}}" class="white" data-noresults="{{__('default.pages.index.nothing_to_show')}}"> </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-group__label">{{__('default.pages.courses.choose_skill')}}</label>
                                    <select name="skills[]" placeholder="{{__('default.pages.courses.choose_skill')}}" data-method="getSkillsByData" data-default="{{__('default.pages.courses.sort_by_default')}}" class="white" data-noresults="{{__('default.pages.index.nothing_to_show')}}"> </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn">{{__('default.pages.courses.apply_title')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        @auth
            @if(Auth::user()->hasRole('student'))
                @if(count($courses)>0)
                    <section class="plain">
                        <div class="container">
                            <h2 class="title-primary decorated">
                                <span>{!! __('default.pages.courses.recommended_courses') !!}</h2>
                            <div class="regular-carousel courses-carousel">
                                @foreach($courses as $course)
                                    <a href="/{{$lang}}/course-catalog/course/{{$course->id}}" title="" class="card">
                                        @if($course->quota_status == 2)
                                            <div
                                                    class="card__quota mark mark--yellow">{{__('default.pages.courses.access_by_quota')}}</div>
                                        @endif
                                        <div class="card__image">
                                            <img src="{{$course->getAvatar()}}" alt="">
                                        </div>
                                        <div class="card__desc">
                                            <div class="card__top">
                                                @if($course->is_paid == true)
                                                    <div
                                                            class="card__price mark mark--blue">{{number_format($course->cost, 0, ',', ' ')}} {{__('default.tenge_title')}}</div>
                                                @else
                                                    <div
                                                            class="card__price mark mark--green">{{__('default.pages.courses.free_title')}}</div>
                                                @endif
                                                <h3 class="card__title">{{$course->name}}</h3>
                                                <div class="card__author">{{$course->user->company_name}}</div>
                                            </div>
                                            <div class="card__bottom">
                                                <div class="card__attribute">
                                                    <i class="icon-user"> </i><span>{{count($course->course_members->whereIn('paid_status', [1,2,3]))}}</span>
                                                </div>
                                                <div class="card__attribute">
                                                    <i class="icon-star-full"> </i><span>{{round($course->rate->pluck('rate')->avg() ?? 0, 1)}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif

                @if(count($skills)>0)
                    <section class="plain">
                        <div class="container">
                            <h2 class="title-primary decorated">
                                <span>{!! __('default.pages.courses.recommended_skills') !!}
                            </h2>
                            <div class="regular-carousel skills-carousel">
                                @foreach($skills->chunk(2) as $chunk)
                                    <div class="skills-carousel__col">
                                        @foreach($chunk as $skill)
                                            <a href="/{{$lang}}/course-catalog?skills[]={{$skill->id}}"
                                               title="{{$skill->getAttribute('name_'.$lang) ?? $skill->name_ru}}"
                                               class="skill-link">{{$skill->getAttribute('name_'.$lang) ?? $skill->name_ru}}</a>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif
            @endif
        @endauth

        <section class="plain big-padding">
            <div class="container">
                <h2 class="title-primary decorated"><span>{!! __('default.pages.index.our_statistic') !!}</h2>
                <div class="row row--multiline">
                    <div class="col-sm-4">
                        <a href="/{{$lang}}/students">
                            <div class="stat">
                                <img src="/assets/img/students.svg" alt="" class="stat__image">
                                <div>
                                    <div class="stat__number">{{$students_count}}</div>
                                    <div class="stat__label">{{ __('default.pages.index.users_count') }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a href="/{{$lang}}/authors">
                            <div class="stat">
                                <img src="/assets/img/authors.svg" alt="" class="stat__image">
                                <div>
                                    <div class="stat__number">{{$authors_count}}</div>
                                    <div class="stat__label">{{ __('default.pages.index.authors_count') }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a href="/{{$lang}}/course-catalog">
                            <div class="stat">
                                <img src="/assets/img/courses.svg" alt="" class="stat__image">
                                <div>
                                    <div class="stat__number">{{$courses_count}}</div>
                                    <div class="stat__label">{{ __('default.pages.index.courses_count') }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{--        <section class="gray">--}}
        {{--            <div class="container">--}}
        {{--                <h3 class="title-primary decorated">{!! __('default.pages.index.popular_courses_catalog') !!}</h3>--}}
        {{--                <ul class="home-arrow-links">--}}
        {{--                    @foreach($popular_courses as $item)--}}
        {{--                        <li><a href="/{{$lang}}/course-catalog/course/{{$item->id}}" class="arrow-link"--}}
        {{--                               title="{{$item->name}}">{{$item->name}}</a></li>--}}
        {{--                    @endforeach--}}
        {{--                    <li><a href="/{{$lang}}/course-catalog" title="{!! __('default.pages.index.popular_courses_catalog_btn') !!}" class="btn">{!! __('default.pages.index.popular_courses_catalog_btn') !!}</a></li>--}}
        {{--                </ul>--}}
        {{--            </div>--}}
        {{--        </section>--}}

        <section class="plain">
            <div class="container">
                <h2 class="title-primary decorated">{!! __('default.pages.index.step_by_step') !!}</h2>
                <div class="row row--multiline">
                    @foreach(json_decode($content->getAttribute('data_'.$lang))->step_by_step as $key => $step)
                        <div class="col-md-4 col-sm-6">
                            <div class="step">
                                <div class="step__number">{{$key+1}}</div>
                                <div>
                                    <h4 class="step__title">{{ $step->name }}</h4>
                                    <div class="step__text">{!! $step->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="plain">
            <div class="container">
                <h2 class="title-primary decorated">{!! __('default.pages.index.popular_courses') !!}</h2>
                <div class="regular-carousel courses-carousel">
                    @foreach($popular_courses as $item)
                        <a href="/{{$lang}}/course-catalog/course/{{$item->id}}" title="" class="card">
                            @if($item->quota_status == 2)
                                <div
                                        class="card__quota mark mark--yellow">{{__('default.pages.courses.access_by_quota')}}</div>
                            @endif
                            <div class="card__image">
                                <img src="{{$item->getAvatar()}}" alt="">
                            </div>
                            <div class="card__desc">
                                <div class="card__top">
                                    @if($item->is_paid == true)
                                        <div class="card__price mark mark--blue">{{number_format($item->cost, 0, ',', ' ')}} {{__('default.tenge_title')}}</div>
                                    @else
                                        <div class="card__price mark mark--green">{{__('default.pages.courses.free_title')}}</div>
                                    @endif
                                    <h3 class="card__title">{{$item->name}}</h3>

                                    <?php $tos = 'name_short_'. $lang; ?>
                                    <div class="card__author">{{ $item->user->type_ownership->$tos }} "{{$item->user->company_name}}"</div>
                                </div>
                                <div class="card__bottom">
                                    <div class="card__attribute">
                                        <i class="icon-user"> </i><span>{{count($item->course_members->whereIn('paid_status', [1,2,3]))}}</span>
                                    </div>
                                    <div class="card__attribute">
                                        <i class="icon-star-full"> </i><span>{{round($item->rate->pluck('rate')->avg() ?? 0, 1)}}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="plain big-padding">
            <div class="container">
                <h2 class="title-primary decorated">{!! __('default.pages.index.popular_authors') !!}</h2>
                <div class="regular-carousel courses-carousel">
                    @foreach($popular_authors as $author)
                        <a href="/{{$lang}}/authors/{{$author->id}}" title="" class="card">
                            <div class="card__image card__author-image">
                                <img src="{{$author->author_info->getAvatar()}}" alt="">
                            </div>
                            <div class="card__desc">
                                <div class="card__top">
                                    <?php $tos = 'name_short_'. $lang; ?>
                                    <h3 class="card__title">{{ $author->type_ownership->$tos }} "{{$author->company_name}}"</h3>
                                    <div class="card__stats">
                                        <span>{{$author->rates ?? 0}}</span> {{__('default.pages.profile.rates_count_title')}}
                                        <br/>
                                        <span>{{count($author->members)}}</span> {{__('default.pages.profile.course_members_count')}}
                                        <br/>
                                        <span>{{$author->courses->where('status', '=', 3)->count()}}</span> {{__('default.pages.profile.course_count')}}
                                    </div>
                                </div>
                                <div class="card__bottom">
                                    <div class="rating">
                                        <div class="rating__number">{{round($author->average_rates, 1)}}</div>
                                        <div class="rating__stars">
                                            <?php
                                            for ($x = 1; $x <= $author->average_rates; $x++) {
                                                echo '<i class="icon-star-full"> </i>';
                                            }
                                            if (strpos($author->average_rates, '.')) {
                                                echo '<i class="icon-star-half"> </i>';
                                                $x++;
                                            }
                                            while ($x <= 5) {
                                                echo '<i class="icon-star-empty"> </i>';
                                                $x++;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="blue">
            <div class="container">
                <div class="row row--multiline align-items-center">
                    <div class="col-sm-6">
                        <h2 class="title-primary decorated">{!! __('default.pages.index.to_be_a_teacher') !!}</h2>
                        <div class="plain-text">
                            {!! json_decode($content->getAttribute('data_'.$lang))->for_authors->description !!}
                        </div>
                        <a href="/{{$lang}}/for-authors"
                           title="{{json_decode($content->getAttribute('data_'.$lang))->for_authors->btn_title}}"
                           class="ghost-btn ghost-btn--white">{{json_decode($content->getAttribute('data_'.$lang))->for_authors->btn_title}}</a>
                    </div>
                    <div class="col-sm-6">
                        <img src="/assets/img/authors-banner.svg" alt="">
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script>
        let defaultSlickOptions = {
            prevArrow: '<i class="icon-chevron-thin-left"> </i>',
            nextArrow: '<i class="icon-chevron-thin-right"> </i>',
            swipeToSlide: true
        };

        $('.courses-carousel').slick({
            ...defaultSlickOptions,
            slidesToShow: 4,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });

        $('.skills-carousel').slick({
            ...defaultSlickOptions,
            slidesToShow: 3,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });

        const professionalAreaEl = $('[name="professional_areas[]"]'),
            specialityEl = $('[name="specialities[]"]'),
            skillsEl = $('[name="skills[]"]');

        let professionAreaSelect = new ajaxSelect(professionalAreaEl);
        let specialitySelect = new ajaxSelect(specialityEl, professionalAreaEl);
        let skillsSelect = new ajaxSelect(skillsEl, specialityEl);

        professionalAreaEl.change(function () {
            specialitySelect.update($(this).val() ? {"professional_areas[]": toArray($(this).val())} : null);
            specialitySelect.clear();
            skillsSelect.clear();
            setTimeout(function () {
                specialitySelect.removeMessage();
            }, 3000);
        });

        specialityEl.change(function () {
            skillsSelect.update($(this).val() ? {"professions[]": toArray($(this).val())} : null);
            skillsSelect.clear();
            setTimeout(function () {
                skillsSelect.removeMessage();
            }, 3000);
        })
    </script>
    <!---->
@endsection

