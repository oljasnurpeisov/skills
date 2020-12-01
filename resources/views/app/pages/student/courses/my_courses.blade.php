@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="plain">
            <div class="container">
                <h1 class="title-primary">{{__('default.pages.courses.my_courses_title')}}</h1>

                <form action="">
                    <div class="form-group">
                        <div class="row row--multiline">
                            <div class="col-auto col-grow-1">
                                <input type="text" name="search" class="input-regular"
                                       placeholder="{{__('default.pages.courses.search_placeholder')}}">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn">{{__('default.pages.courses.search_button')}}</button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row row--multiline">
                    <div class="col-md-8">
                        <div class="row row--multiline">
                            @foreach($items as $item)
                                <div class="col-sm-6 col-md-4">
                                    <div class="card">
                                        <a href="#" title="" class="card__image">
                                            <img src="{{$item->course->getAvatar()}}" alt="">
                                            <div class="card__progress mark mark--green">{{round(($item->finished_lessons_count/$item->lessons_count)*100)}}
                                                %
                                            </div>
                                        </a>
                                        <div class="card__desc">
                                            <div class="card__top">
                                                <h3 class="card__title"><a href="#"
                                                                           title="{{$item->course->name}}">{{$item->course->name}}</a>
                                                </h3>
                                                <div class="card__author">{{$item->course->user->company_name}}</div>
                                            </div>
                                            <div class="card__date">
                                                <div>
                                                    <i class="icon-start"></i>
                                                    <span>{{date('d.m.Y', strtotime($item->created_at))}}</span>
                                                </div>
                                                @if($item->is_finished == true)
                                                    <div>
                                                        <i class="icon-finish"></i>
                                                        <span>{{date('d.m.Y', strtotime($item->updated_at))}}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card__additional">
                                            @if($item->is_finished == true)
                                                <a href="#"
                                                   title="{{__('default.pages.courses.get_certificate_active')}}"
                                                   class="card__link">{{__('default.pages.courses.get_certificate_active')}}</a>
                                                <a href="#rate" data-fancybox
                                                   data-options='{"smallBtn": false, "buttons": [],"clickSlide": false, "clickOutside": false}'
                                                   class="card__btn rateClick {{  ($student_rate->where('course_id', '=', $item->course_id)->count() != 0 ? ' disabled' : '') }}"
                                                   title="{{__('default.pages.courses.write_feedback')}}" course_id="{{$item->course_id}}">{{__('default.pages.courses.write_feedback')}}</a>
                                            @else
                                                <a href="#"
                                                   title="{{__('default.pages.courses.get_certificate_inactive')}}"
                                                   class="card__link disabled">{{__('default.pages.courses.get_certificate_inactive')}}</a>
                                                <a href="#rate" data-fancybox class="card__btn disabled rateClick"
                                                   title="{{__('default.pages.courses.write_feedback')}}"
                                                   course_id="{{$item->course_id}}" >{{__('default.pages.courses.write_feedback')}}</a>
                                            @endif
                                        </div>
                                    </div>
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
                                    <div class="sidebar-item__title">Автор курса:</div>
                                    <div class="sidebar-item__body">
                                        <select name="author" placeholder="Выберите автора"
                                                data-method="getAuthorsByName"
                                                data-default="По умолчанию" class="custom" multiple> </select>
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
                                    <div class="sidebar-item__title">Статус курса:</div>
                                    <div class="sidebar-item__body">
                                        <label class="checkbox"><input type="checkbox" name="status[]"
                                                                       value="active"><span>В процессе изучения</span></label>
                                        <label class="checkbox"><input type="checkbox" name="status[]"
                                                                       value="finished"><span>Изучен</span></label>
                                        <label class="checkbox"><input type="checkbox" name="status[]"
                                                                       value="certified"><span>Получен сертификат</span></label>
                                    </div>
                                </div>
                                <div class="sidebar-item">
                                    <div class="sidebar-item__title">Дата записи на курс:</div>
                                    <div class="sidebar-item__body" style="padding-right: .5em;padding-left: .5em;">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" name="startDateFrom" placeholder="от"
                                                               class="input-regular custom-datepicker">
                                                        <i class="icon-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" name="startDateTo" placeholder="до"
                                                               class="input-regular custom-datepicker">
                                                        <i class="icon-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sidebar-item">
                                    <div class="sidebar-item__title">Дата окончания курса:</div>
                                    <div class="sidebar-item__body" style="padding-right: .5em;padding-left: .5em;">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" name="finishDateFrom" placeholder="от"
                                                               class="input-regular custom-datepicker">
                                                        <i class="icon-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" name="finishDateTo" placeholder="до"
                                                               class="input-regular custom-datepicker">
                                                        <i class="icon-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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

        <div id="rate" style="display:none;">
            <h4 class="title-primary text-center">{{__('default.pages.courses.course_rate_title')}}</h4>
                <form action="/" method="POST">
                    @csrf
                    <div class="rating-fieldset">
                        <input type="radio" name="rating" value="5" id="star5" required>
                        <label for="star5"><i class="icon-star-empty"></i></label>
                        <input type="radio" name="rating" value="4" id="star4">
                        <label for="star4"><i class="icon-star-empty"></i></label>
                        <input type="radio" name="rating" value="3" id="star3">
                        <label for="star3"><i class="icon-star-empty"></i></label>
                        <input type="radio" name="rating" value="2" id="star2">
                        <label for="star2"><i class="icon-star-empty"></i></label>
                        <input type="radio" name="rating" value="1" id="star1">
                        <label for="star1"><i class="icon-star-empty"></i></label>
                    </div>
                    <div class="form-group">
                    <textarea name="review" placeholder="{{__('default.pages.courses.course_rate_description')}}"
                              class="input-regular" required></textarea>
                        <div class="hint text-center gray">* {{__('default.pages.courses.course_rate_ps')}}</div>
                    </div>
                    <div class="text-center">
                        <button onclick="submitForm()"
                                class="btn">{{__('default.pages.courses.send_rate_button_title')}}</button>
                    </div>
                </form>
        </div>

    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script>
        const specialityEl = $('[name="speciality"]'),
            skillsEl = $('[name="skills"]'),
            authorEl = $('[name="author"]');

        let specialitySelect = new ajaxSelect(specialityEl);
        let skillsSelect = new ajaxSelect(skillsEl, specialityEl);
        let authorSelect = new ajaxSelect(authorEl, null, false);

        specialityEl.change(function () {
            skillsSelect.update($(this).val() ? {"professions": toArray($(this).val())} : null);
        })
    </script>

    <script>

        var course_id = 0;
        $('.rateClick').click(function() {
            $('form').attr('action', '/{{$lang}}/course-'+$(this).attr('course_id')+'/saveCourseRate')
        });

        function submitForm(){
            $('form').submit();
        }
    </script>
    <!---->
@endsection

