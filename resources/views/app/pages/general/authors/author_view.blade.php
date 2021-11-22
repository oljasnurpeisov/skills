@extends('app.layout.default.template')

@section('css')
.graph-container {
width: 100%;
height: 30vh;
}
@stop
@section('content')
<main class="main">

	<section class="plain author-page">
		<div class="container">
			<ul class="breadcrumbs">
				<li><a href="/{{$lang}}"
					title="{{__('default.main_title')}}">{{__('default.main_title')}}</a>
				</li>
				<li><a href="/{{$lang}}/authors">{{__('default.pages.authors.catalog_title')}}</a></li>
				<li><span>{{__('default.pages.courses.author_title')}}</span></li>
			</ul>
			<div class="title-with-border-block">
				<h1 class="page-title">{{__('default.pages.courses.author_title')}}</h1>
			</div>
			<div class="row mx-0 author-page">
				<div class="article-section col-md-8">
					<div class="personal-card">
						<div class="personal-card__left">
							<div class="personal-card__image">
								<img src="{{ $user->author_info->getAvatar() }}" alt="">
							</div>
							<ul class="socials">
								@if(!empty($user->author_info->site_url))
                                    <li><a href="{{$user->author_info->site_url}}" title=""
                                           class="icon-language"> </a></li>
                                @endif
                                @if(!empty($user->author_info->vk_link))
                                    <li><a href="{{$user->author_info->vk_link}}" title=""
                                           class="icon-vk"> </a></li>
                                @endif
                                @if(!empty($user->author_info->fb_link))
                                    <li><a href="{{$user->author_info->fb_link}}" title=""
                                           class="icon-facebook"> </a></li>
                                @endif
                                @if(!empty($user->author_info->instagram_link))
                                    <li><a href="{{$user->author_info->instagram_link}}" title=""
                                           class="icon-instagram"> </a></li>
                                @endif
							</ul>
						</div>
						<div class="personal-card__right">
							<div
							class="personal-card__name">{{ $user->author_info->name . ' ' . $user->author_info->surname  }}</div>
							<div class="personal-card__gray-text"><strong>{{ __('default.pages.oked_industries') }}:</strong></div>
							<div class="personal-card__gray-text">{{ implode(', ', json_decode($user->oked_industries->pluck('oked_industry.name_ru')) ?? []) }}</div>
							<div class="personal-card__gray-text"><strong>{{ __('default.pages.oked_activities') }}:</strong></div>
							<div class="personal-card__gray-text">{{ implode(', ', json_decode($user->oked_activities->pluck('oked_activity.name_ru')) ?? []) }}</div>
							<div class="personal-card__gray-text"><strong>{{ __('default.pages.description') }}:</strong></div>
                            <div class="plain-text">
                                {!! $user->author_info->about !!}
                            </div>
                            <div class="personal-card__characteristics">
                                <div>
                                    <span class="blue">{{count($rates)}}</span> {{__('default.pages.profile.rates_count_title')}}
                                </div>
                                <div>
                                    <span class="blue">{{count($author_students)}}</span> {{__('default.pages.profile.course_members_count')}}
                                </div>
                                <div>
                                    <span class="blue">{{count($courses->where('status', '=', 3))}}</span> {{__('default.pages.profile.course_count')}}
                                </div>
                                <div>
                                    <span class="blue">{{count($author_students_finished)}}</span> {{__('default.pages.profile.issued_certificates')}}
                                </div>
                            </div>
                            <div class="rating">
                                <div class="rating__number">{{round($average_rates, 1)}}</div>
                                <div class="rating__stars">
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
                                </div>
                            </div>
						</div>
					</div>
				</div>
                @if(!empty($user->author_info->certificates))
                    <div class="article-section col-md-8">
                        <h2 class="title-secondary">{{__('default.pages.courses.author_certificates')}}</h2>
                        <div class="row row--multiline">
                            @foreach(json_decode($user->author_info->certificates) as $certificate)
                                <div class="col-md-3 col-sm-4 col-xs-6">
                                    <a href="{{$certificate}}"
                                       data-fancybox="author- certificates"
                                       title="{{__('default.pages.courses.zoom_certificate')}}"
                                       class="certificate">
                                        <img src="{{$certificate}}" alt="">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
{{--                @if($courseItems->isNotEmpty())--}}
                    <div class="col-md-12 author-page_courses">
                        <h2 class="title-secondary">{{__('default.pages.authors.author_courses')}}</h2>
                        <div class="row row--multiline column-reverse-sm">
                            <div class="col-md-8">
                                <div class="row row--multiline">
                                    @foreach($courseItems as $item)
                                        <div class="col-sm-6 col-md-4">
                                            <a href="/{{$lang}}/course-catalog/course/{{$item->id}}" title="" class="card">
                                                @if($item->quota_status == 2)
                                                    <div class="card__quota mark mark--yellow">{{__('default.pages.courses.access_by_quota')}}</div>
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
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-center">
                                    {{ $courseItems->appends(request()->input())->links('vendor.pagination.default') }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="sidebar" data-toggle-title="{{__('default.pages.courses.show_filter_title')}}">
                                    <form action="">
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
                                                <div class="sidebar-item__title">{{__('default.pages.courses.rating_from')}}:
                                                </div>
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
                                                <div class="sidebar-item__title">{{__('default.pages.courses.course_type')}}:
                                                </div>
                                                <div class="sidebar-item__body">
                                                    <select name="course_type" class="selectize-regular custom" placeholder="{{__('default.pages.courses.choose_course_type')}}">
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
                    </div>
{{--                @endif--}}
			</div>
		</div>

	</section>
</main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script>
        const specialityEl = $('[name="specialities[]"]'),
            professionalAreaEl = $('[name="professional_areas[]"]'),
            skillsEl = $('[name="skills[]"]');

        let professionalAreaSelect = new ajaxSelect(professionalAreaEl);
        let specialitySelect = new ajaxSelect(specialityEl);
        let skillsSelect = new ajaxSelect(skillsEl, specialityEl);

        specialityEl.change(function () {
            skillsSelect.update($(this).val() ? {"professions": toArray($(this).val())} : null);
        })

        professionalAreaEl.change(function () {
            specialitySelect.update($(this).val() ? {"professional_areas": toArray($(this).val())} : null);
        })
    </script>
    <!---->
@endsection
