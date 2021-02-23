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
                            <li class="active"><a href="/{{$lang}}/profile-pay-information"
                                   title="{{__('default.pages.profile.payment_information')}}">{{__('default.pages.profile.payment_information')}}</a>
                            </li>
                            <li><a href="/{{$lang}}/edit-profile"
                                   title="{{__('default.pages.profile.registration_data')}}">{{__('default.pages.profile.registration_data')}}</a>
                            </li>
                            <li><a href="/{{$lang}}/change-password"
                                   title="{{__('default.pages.profile.password_title')}}">{{__('default.pages.profile.password_title')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <form class="author-personal" action="/{{$lang}}/profile_pay_information" method="POST" >
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
                    @if ($pay_information->user->email_verified_at == null)
                        <div class="alert alert-danger">
                            {{__('default.pages.profile.email_confirm_error')}}
                        </div>
                    @endif
                    <div class="alert alert-warning">
                        {!! __('default.pages.profile.pay_info_help_title') !!}
                    </div>
                    <div @if($pay_information->user->email_verified_at == null)class="row row--multiline disabled"@else class="row row--multiline"@endif>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.profile.merchant_login')}} *</label>
                                <input type="text" name="merchant_login" placeholder="" class="input-regular" value="{{$pay_information->merchant_login}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.profile.merchant_password')}}  *</label>
                                <input type="password" name="merchant_password" placeholder="" class="input-regular" value="{{$pay_information->merchant_password}}" autocomplete="off">
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

