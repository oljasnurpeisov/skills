@extends('app.layout.default.template')

@section('css')
.graph-container {
width: 100%;
height: 30vh;
}
@stop
@section('content')
<main class="main">
	
	<section class="plain">
		<div class="container">
			<ul class="breadcrumbs">
				<li><a href="/{{$lang}}"
					title="{{__('default.main_title')}}">{{__('default.main_title')}}</a>
				</li>
				<li><span>{{__('default.pages.authors.catalog_title')}}</span></li>
			</ul>
			<h1 class="page-title">{{__('default.pages.authors.catalog_title')}}</h1>
			<form action="">
				<div class="form-group">
					<div class="row row--multiline">
						<div class="col-auto col-grow-1">
							<input type="text" name="search" class="input-regular"
							placeholder="{{__('default.pages.authors.search_placeholder')}}"
							value="{{$request->search}}">
						</div>
						<div class="col-auto">
							<button type="submit" class="btn">{{__('default.pages.courses.search_button')}}</button>
						</div>
					</div>
				</div>
			</form>
			
			<div class="row row--multiline column-reverse-sm">
				<div class="col-md-12 px-0">
					<div class="row row--multiline authors">
						<div class="col-md-3 col-sm-6">
							<a href="#" class="card authors-item">
								<div class="card__image card__author-image">
									<img src="/users/user_2376/profile/images/1629282340.jpg" alt="">
								</div>
								<div class="card__desc">
									<div class="card__top">
										<h3 class="card__title">ГУ "Министерство труда и социальной защиты населения Республики Казахстан"</h3>
										<div class="card__stats">
											<span>351</span> {{__('default.pages.profile.rates_count_title')}}
											<br/>
											<span>2950</span> {{__('default.pages.profile.course_members_count')}}
											<br/>
											<span>26</span> {{__('default.pages.profile.course_count')}}
										</div>
									</div>
									<div class="card__bottom">
										<div class="rating">
											<div class="rating__number">4.8</div>
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
							</a>
						</div>
						<div class="col-md-3 col-sm-6">
							<a href="#" class="card authors-item">
								<div class="card__image card__author-image">
									<img src="/users/user_2376/profile/images/1629282340.jpg" alt="">
								</div>
								<div class="card__desc">
									<div class="card__top">
										<h3 class="card__title">ГУ "Министерство труда и социальной защиты населения Республики Казахстан"</h3>
										<div class="card__stats">
											<span>351</span> {{__('default.pages.profile.rates_count_title')}}
											<br/>
											<span>2950</span> {{__('default.pages.profile.course_members_count')}}
											<br/>
											<span>26</span> {{__('default.pages.profile.course_count')}}
										</div>
									</div>
									<div class="card__bottom">
										<div class="rating">
											<div class="rating__number">4.8</div>
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
							</a>
						</div>
						<div class="col-md-3 col-sm-6">
							<a href="#" class="card authors-item">
								<div class="card__image card__author-image">
									<img src="/users/user_2376/profile/images/1629282340.jpg" alt="">
								</div>
								<div class="card__desc">
									<div class="card__top">
										<h3 class="card__title">ГУ "Министерство труда и социальной защиты населения Республики Казахстан"</h3>
										<div class="card__stats">
											<span>351</span> {{__('default.pages.profile.rates_count_title')}}
											<br/>
											<span>2950</span> {{__('default.pages.profile.course_members_count')}}
											<br/>
											<span>26</span> {{__('default.pages.profile.course_count')}}
										</div>
									</div>
									<div class="card__bottom">
										<div class="rating">
											<div class="rating__number">4.8</div>
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
							</a>
						</div>
						<div class="col-md-3 col-sm-6">
							<a href="#" class="card authors-item">
								<div class="card__image card__author-image">
									<img src="/users/user_2376/profile/images/1629282340.jpg" alt="">
								</div>
								<div class="card__desc">
									<div class="card__top">
										<h3 class="card__title">ГУ "Министерство труда и социальной защиты населения Республики Казахстан"</h3>
										<div class="card__stats">
											<span>351</span> {{__('default.pages.profile.rates_count_title')}}
											<br/>
											<span>2950</span> {{__('default.pages.profile.course_members_count')}}
											<br/>
											<span>26</span> {{__('default.pages.profile.course_count')}}
										</div>
									</div>
									<div class="card__bottom">
										<div class="rating">
											<div class="rating__number">4.8</div>
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
							</a>
						</div>
						<div class="col-md-3 col-sm-6">
							<a href="#" class="card authors-item">
								<div class="card__image card__author-image">
									<img src="/users/user_2376/profile/images/1629282340.jpg" alt="">
								</div>
								<div class="card__desc">
									<div class="card__top">
										<h3 class="card__title">ГУ "Министерство труда и социальной защиты населения Республики Казахстан"</h3>
										<div class="card__stats">
											<span>351</span> {{__('default.pages.profile.rates_count_title')}}
											<br/>
											<span>2950</span> {{__('default.pages.profile.course_members_count')}}
											<br/>
											<span>26</span> {{__('default.pages.profile.course_count')}}
										</div>
									</div>
									<div class="card__bottom">
										<div class="rating">
											<div class="rating__number">4.8</div>
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
							</a>
						</div>
						<div class="col-md-3 col-sm-6">
							<a href="#" class="card authors-item">
								<div class="card__image card__author-image">
									<img src="/users/user_2376/profile/images/1629282340.jpg" alt="">
								</div>
								<div class="card__desc">
									<div class="card__top">
										<h3 class="card__title">ГУ "Министерство труда и социальной защиты населения Республики Казахстан"</h3>
										<div class="card__stats">
											<span>351</span> {{__('default.pages.profile.rates_count_title')}}
											<br/>
											<span>2950</span> {{__('default.pages.profile.course_members_count')}}
											<br/>
											<span>26</span> {{__('default.pages.profile.course_count')}}
										</div>
									</div>
									<div class="card__bottom">
										<div class="rating">
											<div class="rating__number">4.8</div>
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
							</a>
						</div>
						<div class="col-md-3 col-sm-6">
							<a href="#" class="card authors-item">
								<div class="card__image card__author-image">
									<img src="/users/user_2376/profile/images/1629282340.jpg" alt="">
								</div>
								<div class="card__desc">
									<div class="card__top">
										<h3 class="card__title">ГУ "Министерство труда и социальной защиты населения Республики Казахстан"</h3>
										<div class="card__stats">
											<span>351</span> {{__('default.pages.profile.rates_count_title')}}
											<br/>
											<span>2950</span> {{__('default.pages.profile.course_members_count')}}
											<br/>
											<span>26</span> {{__('default.pages.profile.course_count')}}
										</div>
									</div>
									<div class="card__bottom">
										<div class="rating">
											<div class="rating__number">4.8</div>
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
							</a>
						</div>
						<div class="col-md-3 col-sm-6">
							<a href="#" class="card authors-item">
								<div class="card__image card__author-image">
									<img src="/users/user_2376/profile/images/1629282340.jpg" alt="">
								</div>
								<div class="card__desc">
									<div class="card__top">
										<h3 class="card__title">ГУ "Министерство труда и социальной защиты населения Республики Казахстан"</h3>
										<div class="card__stats">
											<span>351</span> {{__('default.pages.profile.rates_count_title')}}
											<br/>
											<span>2950</span> {{__('default.pages.profile.course_members_count')}}
											<br/>
											<span>26</span> {{__('default.pages.profile.course_count')}}
										</div>
									</div>
									<div class="card__bottom">
										<div class="rating">
											<div class="rating__number">4.8</div>
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
							</a>
						</div>
					</div>
					<div class="text-center">
						{{ $items->appends(request()->input())->links('vendor.pagination.default') }}
					</div>
				</div>
			</div>
		</div>
		
	</section>
</main>
@endsection
