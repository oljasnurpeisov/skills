@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="opening">
            <div class="container">
                <h1>{!! __('default.pages.index.index_title') !!}</h1>
                <form class="opening-form" action="/{{$lang}}/course-catalog">
                    <div class="row row--multiline">
                        <div class="col-md-2">
                            <div class="opening-form__title">
                                {!! __('default.pages.index.find_your_course') !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-group__label">{{__('default.pages.courses.choose_profession')}}</label>
                            <select name="specialities[]"
                                    placeholder="{{__('default.pages.courses.choose_profession')}}"
                                    data-method="getProfessionsByName"
                                    data-default="{{__('default.pages.courses.sort_by_default')}}"
                                    class="white"> </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-group__label">{{__('default.pages.courses.choose_skill')}}</label>
                            <select name="skills[]" placeholder="{{__('default.pages.courses.choose_skill')}}"
                                    data-method="getSkillsByData"
                                    data-default="{{__('default.pages.courses.sort_by_default')}}"
                                    class="white"> </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn">{{__('default.pages.courses.apply_title')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        @auth
            @if(Auth::user()->roles()->first()->slug == 'student')
                @if(count($courses)>0)
                    <section class="plain">
                        <div class="container">
                            <h2 class="title-primary decorated">
                                <span>{!! __('default.pages.courses.recommended_courses') !!}</h2>
                            <div class="regular-carousel courses-carousel">
                                @foreach($courses as $course)
                                    <a href="#" title="" class="card">
                                        @if($item->quota_status == 2)
                                            <div class="card__quota mark mark--yellow">{{__('default.pages.courses.access_by_quota')}}</div>
                                        @endif
                                        <div class="card__image">
                                            <img src="{{$course->image}}" alt="">
                                        </div>
                                        <div class="card__desc">
                                            <div class="card__top">
                                                <div class="card__price mark mark--blue">{{number_format($course->cost, 0, ',', ' ')}} {{__('default.tenge_title')}}</div>
                                                <h3 class="card__title">{{$course->name}}</h3>
                                                <div class="card__author">{{$course->user->company_name}}</div>
                                            </div>
                                            <div class="card__bottom">
                                                <div class="card__attribute">
                                                    <i class="icon-user"> </i><span>{{count($course->course_members->whereIn('paid_status', [1,2]))}}</span>
                                                </div>
                                                <div class="card__attribute">
                                                    <i class="icon-star-full"> </i><span>{{$course->rate->pluck('rate')->avg() ?? 0}}</span>
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
                <h2 class="title-primary decorated"><span>Наши</span><br/> показатели</h2>
                <div class="row row--multiline">
                    <div class="col-sm-4">
                        <div class="stat">
                            <img src="/assets/img/students.svg" alt="" class="stat__image">
                            <div>
                                <div class="stat__number">3000</div>
                                <div class="stat__label">обучающихся</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="stat">
                            <img src="/assets/img/authors.svg" alt="" class="stat__image">
                            <div>
                                <div class="stat__number">300</div>
                                <div class="stat__label">авторов</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="stat">
                            <img src="/assets/img/courses.svg" alt="" class="stat__image">
                            <div>
                                <div class="stat__number">600</div>
                                <div class="stat__label">курсов</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="gray">
            <div class="container">
                <h3 class="title-primary decorated"><span>Можно применить</span><br/> популярные курсы</h3>
                <ul class="home-arrow-links">
                    <li><a href="#" class="arrow-link" title="Курс визуального дизайна и интерьера">Курс визуального
                            дизайна и интерьера</a></li>
                    <li><a href="#" class="arrow-link" title="Основы программирования на Phyton">Основы программирования
                            на Phyton</a></li>
                    <li><a href="#" class="arrow-link" title="3D дизайн или как начать создавать с нуля">3D дизайн или
                            как начать создавать с нуля</a></li>
                    <li><a href="#" class="arrow-link" title="Основы модульного построения зданий">Основы модульного
                            построения зданий</a></li>
                    <li><a href="#" class="arrow-link" title="Веб-дизайн или с чего начать новичку">Веб-дизайн или с
                            чего начать новичку</a></li>
                    <li><a href="#" class="arrow-link" title="Детальный разбор C++ от профессионала">Детальный разбор
                            C++ от профессионала</a></li>
                    <li><a href="/{{$lang}}/course-catalog" title="Посмотреть весь каталог" class="btn">Посмотреть весь
                            каталог</a></li>
                </ul>
            </div>
        </section>

        <section class="plain">
            <div class="container">
                <h2 class="title-primary decorated"><span>Щаг за</span><br/> шагом</h2>
                <div class="row row--multiline">
                    <div class="col-md-4 col-sm-6">
                        <div class="step">
                            <div class="step__number">1</div>
                            <div>
                                <h4 class="step__title">Подбор навыкА</h4>
                                <div class="step__text">Quis convallis laoreet porta eget mauris non lorem etiam integer
                                    justo phasellus senectus pellentesque
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="step">
                            <div class="step__number">2</div>
                            <div>
                                <h4 class="step__title">Выбор курса</h4>
                                <div class="step__text">Mi urna pellentesque commodo nisl quis consequat, volutpat nulla
                                    tristique fames lectus nunc nam
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="step">
                            <div class="step__number">3</div>
                            <div>
                                <h4 class="step__title">Прохождение обучения</h4>
                                <div class="step__text">Tellus aliquam, velit orci, sit aliquam tellus sed enim sit at
                                    mauris eu, nam
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="step">
                            <div class="step__number">4</div>
                            <div>
                                <h4 class="step__title">Получение сертификата</h4>
                                <div class="step__text">Quis convallis laoreet porta eget mauris non lorem etiam integer
                                    justo phasellus senectus pellentesque
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="step">
                            <div class="step__number">5</div>
                            <div>
                                <h4 class="step__title">Теперь вы специалист!</h4>
                                <div class="step__text">Mi urna pellentesque commodo nisl quis consequat, volutpat nulla
                                    tristique fames lectus nunc nam
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @auth
            @if(Auth::user()->roles()->first()->slug == 'student')
                <section class="plain">
                    <div class="container">
                        <h2 class="title-primary decorated"><span>Популярные</span><br/> курсы</h2>
                        <div class="regular-carousel courses-carousel">
                            @foreach($popular_courses as $course)
                                <a href="#" title="" class="card">
                                    <div class="card__quota mark mark--yellow">Доступен по квоте</div>
                                    <div class="card__image">
                                        <img src="/assets/img/courses/1.png" alt="">
                                    </div>
                                    <div class="card__desc">
                                        <div class="card__top">
                                            <div class="card__price mark mark--blue">10 000 тг</div>
                                            <h3 class="card__title">Монтаж электросетевого оборудования для
                                                начинающих</h3>
                                            <div class="card__author">Арман Досмугамбетов</div>
                                        </div>
                                        <div class="card__bottom">
                                            <div class="card__attribute">
                                                <i class="icon-user"> </i><span>1500</span>
                                            </div>
                                            <div class="card__attribute">
                                                <i class="icon-star-full"> </i><span>4.5</span>
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
                        <h2 class="title-primary decorated"><span>Популярные</span><br/> авторы</h2>
                        <div class="regular-carousel courses-carousel">
                            <a href="#" title="" class="card">
                                <div class="card__image card__author-image">
                                    <img src="/assets/img/authors/1.png" alt="">
                                </div>
                                <div class="card__desc">
                                    <div class="card__top">
                                        <h3 class="card__title">Гурьев Евгений</h3>
                                        <div class="card__stats">
                                            <span>29</span> отзывов<br/>
                                            <span>129</span> обучающихся<br/>
                                            <span>12</span> курсов
                                        </div>
                                    </div>
                                    <div class="card__bottom">
                                        <div class="rating">
                                            <div class="rating__number">4.5</div>
                                            <div class="rating__stars">
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-half"> </i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a href="#" title="" class="card">
                                <div class="card__image card__author-image">
                                    <img src="/assets/img/authors/2.png" alt="">
                                </div>
                                <div class="card__desc">
                                    <div class="card__top">
                                        <h3 class="card__title">Насыров Арман </h3>
                                        <div class="card__stats">
                                            <span>29</span> отзывов<br/>
                                            <span>129</span> обучающихся<br/>
                                            <span>12</span> курсов
                                        </div>
                                    </div>
                                    <div class="card__bottom">
                                        <div class="rating">
                                            <div class="rating__number">4.5</div>
                                            <div class="rating__stars">
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-half"> </i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a href="#" title="" class="card">
                                <div class="card__image card__author-image">
                                    <img src="/assets/img/authors/3.png" alt="">
                                </div>
                                <div class="card__desc">
                                    <div class="card__top">
                                        <h3 class="card__title">Алексеева Кристина</h3>
                                        <div class="card__stats">
                                            <span>29</span> отзывов<br/>
                                            <span>129</span> обучающихся<br/>
                                            <span>12</span> курсов
                                        </div>
                                    </div>
                                    <div class="card__bottom">
                                        <div class="rating">
                                            <div class="rating__number">4.5</div>
                                            <div class="rating__stars">
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-half"> </i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a href="#" title="" class="card">
                                <div class="card__image card__author-image">
                                    <img src="/assets/img/authors/4.png" alt="">
                                </div>
                                <div class="card__desc">
                                    <div class="card__top">
                                        <h3 class="card__title">Аширбеков Даулет</h3>
                                        <div class="card__stats">
                                            <span>29</span> отзывов<br/>
                                            <span>129</span> обучающихся<br/>
                                            <span>12</span> курсов
                                        </div>
                                    </div>
                                    <div class="card__bottom">
                                        <div class="rating">
                                            <div class="rating__number">4.5</div>
                                            <div class="rating__stars">
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-half"> </i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a href="#" title="" class="card">
                                <div class="card__image card__author-image">
                                    <img src="/assets/img/authors/1.png" alt="">
                                </div>
                                <div class="card__desc">
                                    <div class="card__top">
                                        <h3 class="card__title">Гурьев Евгений</h3>
                                        <div class="card__stats">
                                            <span>29</span> отзывов<br/>
                                            <span>129</span> обучающихся<br/>
                                            <span>12</span> курсов
                                        </div>
                                    </div>
                                    <div class="card__bottom">
                                        <div class="rating">
                                            <div class="rating__number">4.5</div>
                                            <div class="rating__stars">
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-half"> </i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a href="#" title="" class="card">
                                <div class="card__image card__author-image">
                                    <img src="/assets/img/authors/2.png" alt="">
                                </div>
                                <div class="card__desc">
                                    <div class="card__top">
                                        <h3 class="card__title">Насыров Арман </h3>
                                        <div class="card__stats">
                                            <span>29</span> отзывов<br/>
                                            <span>129</span> обучающихся<br/>
                                            <span>12</span> курсов
                                        </div>
                                    </div>
                                    <div class="card__bottom">
                                        <div class="rating">
                                            <div class="rating__number">4.5</div>
                                            <div class="rating__stars">
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-half"> </i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a href="#" title="" class="card">
                                <div class="card__image card__author-image">
                                    <img src="/assets/img/authors/3.png" alt="">
                                </div>
                                <div class="card__desc">
                                    <div class="card__top">
                                        <h3 class="card__title">Алексеева Кристина</h3>
                                        <div class="card__stats">
                                            <span>29</span> отзывов<br/>
                                            <span>129</span> обучающихся<br/>
                                            <span>12</span> курсов
                                        </div>
                                    </div>
                                    <div class="card__bottom">
                                        <div class="rating">
                                            <div class="rating__number">4.5</div>
                                            <div class="rating__stars">
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-half"> </i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a href="#" title="" class="card">
                                <div class="card__image card__author-image">
                                    <img src="/assets/img/authors/4.png" alt="">
                                </div>
                                <div class="card__desc">
                                    <div class="card__top">
                                        <h3 class="card__title">Аширбеков Даулет</h3>
                                        <div class="card__stats">
                                            <span>29</span> отзывов<br/>
                                            <span>129</span> обучающихся<br/>
                                            <span>12</span> курсов
                                        </div>
                                    </div>
                                    <div class="card__bottom">
                                        <div class="rating">
                                            <div class="rating__number">4.5</div>
                                            <div class="rating__stars">
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-full"> </i>
                                                <i class="icon-star-half"> </i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </section>
            @endif
        @endauth
        <section class="plain">
            <div class="container">
                <h2 class="title-primary decorated">{!! __('default.pages.index.popular_courses') !!}</h2>
                <div class="regular-carousel courses-carousel">
                    @foreach($popular_courses as $item)
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
                    @endforeach
                </div>
            </div>
        </section>

        <section class="blue">
            <div class="container">
                <div class="row row--multiline align-items-center">
                    <div class="col-sm-6">
                        <h2 class="title-primary decorated"><span>Станьте</span><br/> автором курсов</h2>
                        <div class="plain-text">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                            laboris nisi ut aliquip ex ea commodo consequat.
                        </div>
                        <a href="#" title="ХОЧУ СТАТЬ АВТОРОМ КУРСОВ!" class="ghost-btn ghost-btn--white">ХОЧУ СТАТЬ
                            АВТОРОМ КУРСОВ!</a>
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

        //Modal message inline call example
        /*$(document).ready(function () {
          $.fancybox.open(`
            <div class="text-center">
                <h4 class="title-primary text-center">Восстановление пароля</h4>
                <div class="plain-text gray">Пароль успешно отправлен на данный E-mail</div>
                <button data-fancybox-close class="btn">Закрыть</button>
            </div>
          `, {
            touch: false
          });
        });*/

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

