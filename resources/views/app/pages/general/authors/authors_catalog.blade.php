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
                        @foreach ($items as $item)
                            <div class="col-md-3 col-sm-6">
                                <a href="/{{ $lang }}/authors/{{ $item->id }}" class="card authors-item">
                                    <div class="card__image card__author-image">
                                        <img src="{{ $item->company_logo }}" alt="">
                                    </div>
                                    <div class="card__desc">
                                        <div class="card__top">
                                            <h3 class="card__title">{{ $item->company_name }}</h3>
                                            <div class="card__stats">
                                                <span>{{ count($item->rates) }}</span> {{__('default.pages.profile.rates_count_title')}}
                                                <br/>
                                                <span>{{count($item->author_students)}}</span> {{__('default.pages.profile.course_members_count')}}
                                                <br/>
                                                <span>{{count($item->courses->where('status', '=', 3))}}</span> {{__('default.pages.profile.course_count')}}
                                            </div>
                                        </div>
                                        <div class="card__bottom">
                                            <div class="rating">
                                                <div class="rating__number">{{round($item->average_rates, 1)}}</div>
                                                <div class="rating__stars">
                                                    <?php
                                                    for ($x = 1; $x <= $item->average_rates; $x++) {
                                                        echo '<i class="icon-star-full"> </i>';
                                                    }
                                                    if (strpos($item->average_rates, '.')) {
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
                            </div>
                        @endforeach
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
