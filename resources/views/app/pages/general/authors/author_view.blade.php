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
				<li><span>Автор</span></li>
			</ul>
			<div class="title-with-border-block">
				<h1 class="page-title">Автор</h1>
			</div>
			<div class="row mx-0 author-page">
				<div class="article-section col-md-8">
					<div class="personal-card">
						<div class="personal-card__left">
							<div class="personal-card__image">
								<img src="/images/company/1627020736.jpg" alt="">
							</div>
							<ul class="socials">
								<li><a href="#" title=""
								class="icon-language"> </a></li>
								<li><a href="#" title=""
								class="icon-vk"> </a></li>
								<li><a href="#" title=""
								class="icon-facebook"> </a></li>
								<li><a href="#" title=""
								class="icon-instagram"> </a></li>
							</ul>
						</div>
						<div class="personal-card__right">
							<div
							class="personal-card__name">Гурьев Евгений</div>
							<div class="personal-card__gray-text"><strong>{{ __('default.pages.oked_industries') }}:</strong></div>
							<div class="personal-card__gray-text">Образование</div>
							<div class="personal-card__gray-text"><strong>{{ __('default.pages.oked_activities') }}:</strong></div>
							<div class="personal-card__gray-text">Техническое и профессиональное среднее образование</div>
							<div class="personal-card__gray-text"><strong>{{ __('default.pages.description') }}:</strong></div>
							<div class="plain-text">
								Модернизация технического и профессионального образования
							</div>
							<div class="personal-card__characteristics">
								<div>
									<span
									class="blue">29</span> {{__('default.pages.profile.rates_count_title')}}
								</div>
								<div>
									<span
									class="blue">129</span> {{__('default.pages.profile.course_members_count')}}
								</div>
								<div>
									<span
									class="blue">12</span> {{__('default.pages.profile.course_count')}}
								</div>
								<div>
									<span
									class="blue">32</span> {{__('default.pages.profile.issued_certificates')}}
								</div>
							</div>
							<div class="rating">
								<div class="rating__number">5</div>
								<div class="rating__stars">
									<i class="icon-star-full"> </i>
									<i class="icon-star-full"> </i>
									<i class="icon-star-full"> </i>
									<i class="icon-star-full"> </i>
									<i class="icon-star-full"> </i>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="article-section col-md-8">
					<h2 class="title-secondary">{{__('default.pages.courses.feedback_title')}}</h2>
					<div>
						<div class="review">
							<div class="review__header">
								<div
								class="review__name">Буранбаев Айдарбек Рустемович</div>
								<div class="rating">
									<div class="rating__stars">
										<i class="icon-star-full"> </i>
										<i class="icon-star-full"> </i>
										<i class="icon-star-full"> </i>
										<i class="icon-star-full"> </i>
										<i class="icon-star-full"> </i>
									</div>
								</div>
							</div>
							<div class="review__text">
								Хороший курс
							</div>
						</div>
					</div>
					<div class="text-center">
						<!-- Пагинация -->
					</div>
				</div>
				<div class="article-section col-md-8">
					<h2 class="title-secondary">{{__('default.pages.courses.author_certificates')}}</h2>
					<div class="row row--multiline">
						<div class="col-md-3 col-sm-4 col-xs-6">
							<a href="#"
							data-fancybox="author- certificates"
							title="{{__('default.pages.courses.zoom_certificate')}}"
							class="certificate">
								<img src="/users/user_1308/profile/files/60ffdc989ba69_21011759_kz.jpg" alt="">
							</a>
						</div>
						<div class="col-md-3 col-sm-4 col-xs-6">
							<a href="#"
							data-fancybox="author- certificates"
							title="{{__('default.pages.courses.zoom_certificate')}}"
							class="certificate">
								<img src="/users/user_1308/profile/files/60ffdc989ba69_21011759_kz.jpg" alt="">
							</a>
						</div>
						<div class="col-md-3 col-sm-4 col-xs-6">
							<a href="#"
							data-fancybox="author- certificates"
							title="{{__('default.pages.courses.zoom_certificate')}}"
							class="certificate">
								<img src="/users/user_1308/profile/files/60ffdc989ba69_21011759_kz.jpg" alt="">
							</a>
						</div>
						<div class="col-md-3 col-sm-4 col-xs-6">
							<a href="#"
							data-fancybox="author- certificates"
							title="{{__('default.pages.courses.zoom_certificate')}}"
							class="certificate">
								<img src="/users/user_1308/profile/files/60ffdc989ba69_21011759_kz.jpg" alt="">
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-12 author-page_courses">
					<h2 class="title-secondary">Курсы автора</h2> 
					<div class="row row--multiline column-reverse-sm">
                        <div class="col-md-8">
                            <div class="row row--multiline">
								<div class="col-sm-6 col-md-4">
									<a href="#" title="" class="card">
										<div class="card__image">
											<img src="/users/user_2376/courses/images/1629894742.jpg" alt="">
										</div>
										<div class="card__desc">
											<div class="card__top">
												<div class="card__price mark mark--green">{{__('default.pages.courses.free_title')}}</div>
												<h3 class="card__title">Монтаж электросетевого оборудования для начинающих</h3>
												<div class="card__author">Сертификационный центр “Труд”</div>
											</div>
											<div class="card__bottom">
												<div class="card__attribute">
													<i class="icon-user"> </i><span>1500</span>
												</div>
												<div class="card__attribute">
													<i class="icon-star-full"> </i><span>4,5</span>
												</div>
											</div>
										</div>
									</a>
								</div>
								<div class="col-sm-6 col-md-4">
									<a href="#" title="" class="card">
										<div class="card__image">
											<img src="/users/user_2376/courses/images/1629894742.jpg" alt="">
										</div>
										<div class="card__desc">
											<div class="card__top">
												<div class="card__price mark mark--green">{{__('default.pages.courses.free_title')}}</div>
												<h3 class="card__title">Монтаж электросетевого оборудования для начинающих</h3>
												<div class="card__author">Сертификационный центр “Труд”</div>
											</div>
											<div class="card__bottom">
												<div class="card__attribute">
													<i class="icon-user"> </i><span>1500</span>
												</div>
												<div class="card__attribute">
													<i class="icon-star-full"> </i><span>4,5</span>
												</div>
											</div>
										</div>
									</a>
								</div>
								<div class="col-sm-6 col-md-4">
									<a href="#" title="" class="card">
										<div class="card__image">
											<img src="/users/user_2376/courses/images/1629894742.jpg" alt="">
										</div>
										<div class="card__desc">
											<div class="card__top">
												<div class="card__price mark mark--green">{{__('default.pages.courses.free_title')}}</div>
												<h3 class="card__title">Монтаж электросетевого оборудования для начинающих</h3>
												<div class="card__author">Сертификационный центр “Труд”</div>
											</div>
											<div class="card__bottom">
												<div class="card__attribute">
													<i class="icon-user"> </i><span>1500</span>
												</div>
												<div class="card__attribute">
													<i class="icon-star-full"> </i><span>4,5</span>
												</div>
											</div>
										</div>
									</a>
								</div>
								<div class="col-sm-6 col-md-4">
									<a href="#" title="" class="card">
										<div class="card__image">
											<img src="/users/user_2376/courses/images/1629894742.jpg" alt="">
										</div>
										<div class="card__desc">
											<div class="card__top">
												<div class="card__price mark mark--green">{{__('default.pages.courses.free_title')}}</div>
												<h3 class="card__title">Монтаж электросетевого оборудования для начинающих</h3>
												<div class="card__author">Сертификационный центр “Труд”</div>
											</div>
											<div class="card__bottom">
												<div class="card__attribute">
													<i class="icon-user"> </i><span>1500</span>
												</div>
												<div class="card__attribute">
													<i class="icon-star-full"> </i><span>4,5</span>
												</div>
											</div>
										</div>
									</a>
								</div>
								<div class="col-sm-6 col-md-4">
									<a href="#" title="" class="card">
										<div class="card__image">
											<img src="/users/user_2376/courses/images/1629894742.jpg" alt="">
										</div>
										<div class="card__desc">
											<div class="card__top">
												<div class="card__price mark mark--green">{{__('default.pages.courses.free_title')}}</div>
												<h3 class="card__title">Монтаж электросетевого оборудования для начинающих</h3>
												<div class="card__author">Сертификационный центр “Труд”</div>
											</div>
											<div class="card__bottom">
												<div class="card__attribute">
													<i class="icon-user"> </i><span>1500</span>
												</div>
												<div class="card__attribute">
													<i class="icon-star-full"> </i><span>4,5</span>
												</div>
											</div>
										</div>
									</a>
								</div>
								<div class="col-sm-6 col-md-4">
									<a href="#" title="" class="card">
										<div class="card__image">
											<img src="/users/user_2376/courses/images/1629894742.jpg" alt="">
										</div>
										<div class="card__desc">
											<div class="card__top">
												<div class="card__price mark mark--green">{{__('default.pages.courses.free_title')}}</div>
												<h3 class="card__title">Монтаж электросетевого оборудования для начинающих</h3>
												<div class="card__author">Сертификационный центр “Труд”</div>
											</div>
											<div class="card__bottom">
												<div class="card__attribute">
													<i class="icon-user"> </i><span>1500</span>
												</div>
												<div class="card__attribute">
													<i class="icon-star-full"> </i><span>4,5</span>
												</div>
											</div>
										</div>
									</a>
								</div>
							</div>
                            <div class="text-center">
                               <!-- Пагинация -->
							</div>
						</div>
                        <div class="col-md-4">
                            <div class="sidebar" data-toggle-title="{{__('default.pages.courses.show_filter_title')}}">
                                <div class="sidebar__inner">
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title">{{__('default.pages.courses.professional_area_title')}}:</div>
                                        <div class="sidebar-item__body">
                                            <select name="professional_areas[]"
											placeholder="{{__('default.pages.courses.choose_professional_area_title')}}"
											data-method=""
											class="custom" multiple>
												<option value=""
												selected>IT и телекоммуникации</option>
											</select>
										</div>
									</div>
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title">{{__('default.pages.courses.profession')}}:</div>
                                        <div class="sidebar-item__body">
                                            <select name="specialities[]"
											placeholder="{{__('default.pages.courses.choose_profession')}}"
											data-method=""
											class="custom" multiple>
												<option value=""
												selected>IT-дизайнер</option>
											</select>
										</div>
									</div>
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title">{{__('default.pages.courses.skill')}}:</div>
                                        <div class="sidebar-item__body">
                                            <select name="skills[]"
											placeholder="{{__('default.pages.courses.choose_skill')}}"
											data-method=""
											class="custom" multiple>
												<option value=""
												selected>Аварийная остановка, управление работой котельного агрегата</option>
											</select>
										</div>
									</div>
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title">{{__('default.pages.courses.language_education')}}
                                            :
										</div>
                                        <div class="sidebar-item__body">
                                            <label class="checkbox"><input type="checkbox" name="lang_kk"
											value="1"><span>{{__('default.pages.courses.language_education_kk')}}</span></label>
                                            <label class="checkbox"><input type="checkbox" name="lang_ru"
											value="1"><span>{{__('default.pages.courses.language_education_ru')}}</span></label>
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
												value="0">
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
												value="0">
											</div>
										</div>
									</div>
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title">{{__('default.pages.courses.course_type')}}:
										</div>
                                        <div class="sidebar-item__body">
                                            <select name="course_type" class="selectize-regular custom"
											placeholder="{{__('default.pages.courses.choose_course_type')}}">
                                                <option value="">{{__('default.pages.courses.sort_by_default')}}</option>
                                                <option value="1">{{__('default.pages.courses.paid_type')}}</option>
                                                <option value="2">{{__('default.pages.courses.free_type')}}</option>
                                                <option value="3">{{__('default.pages.courses.quota_type')}}</option>
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
                                                <option value="sort_by_rate_high">{{__('default.pages.courses.sort_by_rate_high')}}</option>
                                                <option value="sort_by_rate_low">{{__('default.pages.courses.sort_by_rate_low')}}</option>
                                                <option value="sort_by_cost_high">{{__('default.pages.courses.sort_by_cost_high')}}</option>
                                                <option value="sort_by_cost_low">{{__('default.pages.courses.sort_by_cost_low')}}</option>
											</select>
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
            professionalAreaEl = $('[name="professional_areas[]"]'),
            skillsEl = $('[name="skills[]"]');

        let professionalAreaSelect = new ajaxSelect(professionalAreaEl);
        let specialitySelect = new ajaxSelect(specialityEl);
        let skillsSelect = new ajaxSelect(skillsEl, specialityEl);
    </script>
    <!---->
@endsection
