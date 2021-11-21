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
				<li><span>{{__('default.pages.students.students_title')}}</span></li>
			</ul>
			<div class="title-with-border-block">
				<h1 class="page-title">Обучающиеся <span class="blue-text">9 900</span></h1>
			</div>
			<div class="row row--multiline students_filter align-items-center">
				<div class="col-md-3 col-sm-4">
					<div class="form-group">
						<label class="form-group__label">{{__('default.pages.courses.choose_professional_area_title')}}</label>
						<div class="input-addon">
							<select  name="professional_areas[]"
							placeholder="{{__('default.pages.courses.choose_professional_area_title')}}"
							data-method="getProfessionalAreaByName"
							class="professional-areas-select"
							{{--                                            data-noresults="{{__('default.pages.index.nothing_to_show')}}" required>--}}
								data-noresults="{{__('default.pages.index.nothing_to_show')}}" multiple required>>
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-4">
					<div class="form-group">
						<label class="form-group__label">Выберите месяц с</label>
						<div class="input-group">
							<input type="text" name="dateFrom"
							placeholder=""
							class="input-regular custom-datepicker">
							<i class="icon-calendar"></i>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-4">
					<div class="form-group">
						<label class="form-group__label">Выберите месяц по</label>
						<div class="input-group">
							<input type="text" name="dateTo" placeholder=""
							class="input-regular custom-datepicker">
							<i class="icon-calendar"></i>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<button type="submit" class="btn">{{__('default.pages.courses.apply_title')}}</button>
				</div>
			</div>
			<div class="students_users-count d-flex align-items-center">
			 <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">300</span>
			</div>
			<div class="row students_list">
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			</div>
			<div class="title-with-border-block">
				<h2 class="page-title">Получившие сертификат <span class="blue-text">9 900</span></h2>
			</div>
			<div class="row row--multiline students_filter align-items-center">
				<div class="col-md-3 col-sm-4">
					<div class="form-group">
						<label class="form-group__label">{{__('default.pages.courses.choose_professional_area_title')}}</label>
						<div class="input-addon">
							<select  name="professional_areas[]"
							placeholder="{{__('default.pages.courses.choose_professional_area_title')}}"
							data-method="getProfessionalAreaByName"
							class="professional-areas-select"
							{{--                                            data-noresults="{{__('default.pages.index.nothing_to_show')}}" required>--}}
								data-noresults="{{__('default.pages.index.nothing_to_show')}}" multiple required>>
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-4">
					<div class="form-group">
						<label class="form-group__label">Выберите месяц с</label>
						<div class="input-group">
							<input type="text" name="dateFrom"
							placeholder=""
							class="input-regular custom-datepicker">
							<i class="icon-calendar"></i>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-4">
					<div class="form-group">
						<label class="form-group__label">Выберите месяц по</label>
						<div class="input-group">
							<input type="text" name="dateTo" placeholder=""
							class="input-regular custom-datepicker">
							<i class="icon-calendar"></i>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<button type="submit" class="btn">{{__('default.pages.courses.apply_title')}}</button>
				</div>
			</div>
			<div class="students_users-count d-flex align-items-center">
			 <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">300</span>
			</div>
			<div class="row students_list">
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Web-разработчик</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">30</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный диспетчер</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">591</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			 <div class="col-md-3 col-sm-6 students_list-item">
			  <div>Авиационный техник по приборам - метролог</div>
			  <div class="students_users-count d-flex align-items-center">
			   <img src="{{ asset('/assets/img/user-blue-ico.svg') }}" alt="" /><span class="blue-text">330</span>
			  </div>
			 </div>
			</div>
			<h2 class="page-title">{{__('default.pages.students.top_skills')}}</h2>
			
		</div>
		<div class="chart-container container " style="position: relative; width:100%">
			<div style="border:1px solid #e0e0e0; padding:1rem"><canvas id="myChart"></canvas></div>
		</div>
		
	</section>
</main>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<!--Only this page's scripts-->
<script>
	var top_skills = {!! json_encode($top_skills) !!};
	const ctx = document.getElementById('myChart').getContext('2d');
	const myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: top_skills.map(({ name }) => name),
			datasets: [{
				data: top_skills.map(({ total }) => total),
				backgroundColor: ['rgba(42, 181, 246, 1)'],
				borderColor: ['rgba(42, 181, 246, 1)'],
				borderWidth: 1,
				pointStyle: function(context){
					var img = new Image(20,20);
					img.src = "https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Skull_Icon_%28Noun_Project%29.svg/1200px-Skull_Icon_%28Noun_Project%29.svg.png";
					return img;
				},
			}]
		},
		plugins: [ChartDataLabels],
		options: {
			color: 'white',
			layout: {
				padding: {
					bottom: 15  //set that fits the best
				}
			},
			elements: {
				bar: {
					borderWidth: 0,
				}
			},
			scales: {
				x: {
					ticks: {
						font: {
							size: 14,
						},
						maxRotation: 90,
						minRotation: 90,
						autoSkip: false,
						color: 'rgba(51, 51, 51, 1)',
						callback: function(value) {
							const valueLegend = this.getLabelForValue(value);
							if (valueLegend.length > 25) {
								return valueLegend.substring(0, 25) + '...';
							}
							return valueLegend
						}
					},
					grid: {
						display:false
					}
				},
				y: {
					ticks: {
						display: false
					},
					grid: {
						display:false
					}
				}
			},
			plugins: {
				tooltip: {
					yAlign: 'top',
					backgroundColor: '#FFF',
					titleColor: 'rgba(51, 51, 51, 1)',
					bodyColor: 'rgba(51, 51, 51, 1)',
					xAlign: 'bottom',
					usePointStyle: true,
					padding: 10,
					titleMarginBottom: 10,
					callbacks: {
						labelPointStyle: function(context) {
							return {
								pointStyle: 'triangle',
								rotation: 0
							};
						},
					}
				},
				legend: {
					display: false
				},
				datalabels: {
					color: '#fff',
					rotation: 270,
				}
			}
		}
	});
</script>
<script>
	const professionalAreaEl = $('[name="professional_areas[]"]');
	
	let professionAreaSelect = new ajaxSelect(professionalAreaEl, null, true, 2);
</script>
<!---->
@endsection
