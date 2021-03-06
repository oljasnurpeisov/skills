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
                                       placeholder="{{__('default.pages.courses.search_placeholder')}}"
                                       value="{{$request->search}}">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn">{{__('default.pages.courses.search_button')}}</button>
                            </div>
                        </div>
                    </div>
                    {{--                </form>--}}

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

                    <div class="row row--multiline column-reverse-sm">
                        <div class="col-md-8">
                            <div class="row row--multiline">
                                @foreach($items as $item)
                                    <div class="col-sm-6 col-md-4">
                                        <div class="card">
                                            <a href="/{{$lang}}/course-catalog/course/{{$item->course->id}}" title=""
                                               class="card__image">
                                                <img src="{{$item->course->getAvatar()}}" alt="">
                                                <div class="card__progress mark mark--{{ $item->progress < 100 ? 'yellow' : 'green' }}">{{ $item->progress }}
                                                    %
                                                </div>
                                            </a>
                                            <div class="card__desc">
                                                <div class="card__top">
                                                    <h3 class="card__title"><a
                                                                href="/{{$lang}}/course-catalog/course/{{$item->course->id}}"
                                                                title="{{$item->course->name}}">{{$item->course->name}}</a>
                                                    </h3>

                                                    <?php $tos = 'name_short_'. $lang; ?>
                                                    <div class="card__author">{{ $item->course->user->type_ownership->$tos }} "{{$item->course->user->company_name}}"</div>
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
                                                    @if($item->course->studentCertificate())
                                                        <a href="{{$item->course->studentCertificate()->getAttribute('pdf_'.$lang ) ?? $item->course->studentCertificate()->getAttribute('pdf_ru')}}"
                                                           download
                                                           title="{{__('default.pages.courses.get_certificate_active')}}"
                                                           class="card__link {{$item->course->studentCertificate() ? '' : 'disabled'}}">{{__('default.pages.courses.get_certificate_active')}}</a>
                                                        <a href="#rate" data-fancybox
                                                           data-options='{"smallBtn": false, "buttons": [],"clickSlide": false, "clickOutside": false}'
                                                           class="card__btn rateClick {{  ($student_rate->where('course_id', '=', $item->course_id)->count() != 0 ? ' disabled' : '') }}"
                                                           title="{{__('default.pages.courses.write_feedback')}}"
                                                           course_id="{{$item->course_id}}">{{__('default.pages.courses.write_feedback')}}</a>
                                                    @else
                                                        <a href="" title="{{__('default.pages.courses.get_certificate_active')}}"
                                                           class="card__link disabled">{{__('default.pages.courses.get_certificate_active')}}</a>
                                                        <a href="#rate" data-fancybox
                                                           data-options='{"smallBtn": false, "buttons": [],"clickSlide": false, "clickOutside": false}'
                                                           class="card__btn rateClick {{  ($student_rate->where('course_id', '=', $item->course_id)->count() != 0 ? ' disabled' : '') }}"
                                                           title="{{__('default.pages.courses.write_feedback')}}"
                                                           course_id="{{$item->course_id}}">{{__('default.pages.courses.write_feedback')}}</a>
                                                    @endif
                                                @else
                                                    <a href="#"
                                                       title="{{__('default.pages.courses.get_certificate_inactive')}}"
                                                       class="card__link disabled">{{__('default.pages.courses.get_certificate_inactive')}}</a>
                                                    <a href="#rate" data-fancybox class="card__btn disabled rateClick"
                                                       title="{{__('default.pages.courses.write_feedback')}}"
                                                       course_id="{{$item->course_id}}">{{__('default.pages.courses.write_feedback')}}</a>
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
                            {{--                        <form class="sidebar">--}}
                            <div class="sidebar" data-toggle-title="{{__('default.pages.courses.show_filter_title')}}">
                                <div class="sidebar__inner">
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title">{{__('default.pages.courses.professional_area_title')}}:</div>
                                        <div class="sidebar-item__body">
                                            <select name="professional_areas[]"
                                                    placeholder="{{__('default.pages.courses.choose_professional_area_title')}}"
                                                    data-method="getProfessionalAreaByName"
                                                    class="custom" multiple>
                                                @if(!empty($request->professional_areas))
                                                    @foreach($professional_areas as $professional_area)
                                                        <option value="{{$professional_area->id}}"
                                                                selected>{{$professional_area->getAttribute('name_'.$lang ?? 'name_ru')}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title">{{__('default.pages.courses.profession')}}:</div>
                                        <div class="sidebar-item__body">
                                            <select name="specialities[]"
                                                    placeholder="{{__('default.pages.courses.choose_profession')}}"
                                                    data-method="getProfessionsByData"
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
                                        <div class="sidebar-item__title">{{__('default.pages.courses.course_author')}}:
                                        </div>
                                        <div class="sidebar-item__body">
                                            <select name="authors[]"
                                                    placeholder="{{__('default.pages.courses.choose_author')}}"
                                                    data-method="getAuthorsByName"
                                                    data-default="" class="custom" multiple>
                                                @if(!empty($request->authors))
                                                    @foreach($authors as $author)
                                                        <option value="{{$author->id}}"
                                                                selected>{{$author->author_info->name . ' ' . $author->author_info->surname}}</option>
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
                                        <div class="sidebar-item__title">{{__('default.pages.courses.course_status')}}:
                                        </div>
                                        <div class="sidebar-item__body">
                                            <label class="checkbox"><input type="checkbox" name="status[]"
                                                                           value="0" {{(in_array('0', $status) ? ' checked' : '')}}><span>{{__('default.pages.courses.course_in_process')}}</span></label>
                                            <label class="checkbox"><input type="checkbox" name="status[]"
                                                                           value="1" {{(in_array('1', $status)  ? ' checked' : '')}}><span>{{__('default.pages.courses.course_finished')}}</span></label>
                                            <label class="checkbox"><input type="checkbox" name="certificate"
                                                                           value="1" {{($request->certificate == 1  ? ' checked' : '')}}><span>{{__('default.pages.courses.course_got_certificate')}}</span></label>
                                        </div>
                                    </div>
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title">{{__('default.pages.courses.course_date_begin')}}
                                            :
                                        </div>
                                        <div class="sidebar-item__body" style="padding-right: .5em;padding-left: .5em;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" name="start_date_from"
                                                                   placeholder="{{__('default.from')}}"
                                                                   class="input-regular custom-datepicker"
                                                                   value="{{$start_date_from}}" autocomplete="off">
                                                            <i class="icon-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" name="start_date_to"
                                                                   placeholder="{{__('default.to')}}"
                                                                   class="input-regular custom-datepicker"
                                                                   value="{{$start_date_to}}" autocomplete="off">
                                                            <i class="icon-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title">{{__('default.pages.courses.course_end_begin')}}
                                            :
                                        </div>
                                        <div class="sidebar-item__body" style="padding-right: .5em;padding-left: .5em;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" name="finish_date_from"
                                                                   placeholder="{{__('default.from')}}"
                                                                   class="input-regular custom-datepicker"
                                                                   value="{{$finish_date_from}}" autocomplete="off">
                                                            <i class="icon-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" name="finish_date_to"
                                                                   placeholder="{{__('default.to')}}"
                                                                   class="input-regular custom-datepicker"
                                                                   value="{{$finish_date_to}}" autocomplete="off">
                                                            <i class="icon-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sidebar__buttons">
                                    <button type="submit"
                                            class="sidebar-btn">{{__('default.pages.courses.apply_title')}}</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
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
        const specialityEl = $('[name="specialities[]"]'),
            professionalAreaEl = $('[name="professional_areas[]"]'),
            skillsEl = $('[name="skills[]"]'),
            authorEl = $('[name="authors[]"]');

        let professionalAreaSelect = new ajaxSelect(professionalAreaEl);
        let specialitySelect = new ajaxSelect(specialityEl);
        let skillsSelect = new ajaxSelect(skillsEl, specialityEl);
        let authorSelect = new ajaxSelect(authorEl, null, false);

        specialityEl.change(function () {
            skillsSelect.update($(this).val() ? {"professions": toArray($(this).val())} : null);
        })

        professionalAreaEl.change(function () {
            specialitySelect.update($(this).val() ? {"professional_areas": toArray($(this).val())} : null);
        })
    </script>

    <script>
        var course_id = 0;
        $('.rateClick').click(function () {
            $('form').attr('action', '/{{$lang}}/course-' + $(this).attr('course_id') + '/saveCourseRate')
        });

        function submitForm() {
            $('form').submit();
        }
    </script>
    <!---->
@endsection

