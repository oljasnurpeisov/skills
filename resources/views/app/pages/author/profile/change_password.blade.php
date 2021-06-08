@extends('app.layout.default.template')

@section('content')
    <main class="main">
        <section class="plain">
            <div class="container">
                <h1 class="title-primary">{{__('default.pages.profile.title')}}</h1>
                <div class="mobile-dropdown">
                    <div class="mobile-dropdown__title dynamic">{{__('default.pages.profile.organization_data')}}</div>
                    <div class="mobile-dropdown__desc">
                        <ul class="tabs-links">
                            <li><a href="/{{$lang}}/profile-author-information"
                                                  title="{{__('default.pages.profile.organization_data')}}">{{__('default.pages.profile.organization_data')}}</a>
                            </li>
                            <li><a href="/{{$lang}}/profile-pay-information"
                                   title="{{__('default.pages.profile.payment_information')}}">{{__('default.pages.profile.payment_information')}}</a>
                            </li>
                            <li><a href="/{{$lang}}/edit-profile"
                                   title="{{__('default.pages.profile.registration_data')}}">{{__('default.pages.profile.registration_data')}}</a>
                            </li>
                            <li class="active"><a href="/{{$lang}}/change-password"
                                   title="{{__('default.pages.profile.password_title')}}">{{__('default.pages.profile.password_title')}}</a>
                            </li>
                            <li>
                                <a href="/{{$lang}}/profile-requisites"
                                   title="{{__('default.pages.profile.requisites_data')}}">{{__('default.pages.profile.requisites_data')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <form action="/{{$lang}}/update_password" method="POST">
                    {{ csrf_field() }}

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('failed'))
                        <div class="alert alert-danger">
                            {{ session('failed') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{!! $error !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row row--multiline">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.profile.old_password')}} *</label>
                                <input type="password" name="old_password" placeholder="" class="input-regular" required>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.profile.new_password')}} *</label>
                                <input type="password" name="password" placeholder="" class="input-regular" required>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.profile.confirm_new_password')}} *</label>
                                <input type="password" name="password_confirmation" placeholder="" class="input-regular" required>
                            </div>
                            <div class="buttons">
                                <button type="submit" class="btn">{{__('default.pages.profile.save_btn_title')}}</button>
{{--                                <a href="#" title="Отмена" class="ghost-btn">Отмена</a>--}}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>

    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->

    <!---->
@endsection

