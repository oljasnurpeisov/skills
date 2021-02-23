@extends('admin.v2.layout.default.template')

@section('title','Профиль | '.__('default.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li class="active">Профиль</li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @include('admin.v2.partials.components.errors')
        <form action="/{{$lang}}/admin/profile" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="block">
                <h2 class="title-secondary">Сменить контактные данные</h2>
                {{--<div class="input-group {{ $errors->has('surname') ? ' has-error' : '' }}">--}}
                    {{--<label class="input-group__title">{{ __('admin.pages.user.surname') }} <span--}}
                                {{--class="required">*</span></label>--}}
                    {{--<input type="text" name="surname" value="{{ $item->surname }}"--}}
                           {{--placeholder="{{ __('admin.labels.fill_field',['field' => __('admin.pages.user.surname')]) }}"--}}
                           {{--class="input-regular" required>--}}
                    {{--@if ($errors->has('surname'))--}}
                        {{--<span class="help-block"><strong>{{ $errors->first('surname') }}</strong></span>--}}
                    {{--@endif--}}
                {{--</div>--}}
                <div class="input-group {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('admin.pages.user.name') }} <span
                                class="required">*</span></label>
                    <input type="text" name="name" value="{{ $item->name }}"
                           placeholder="{{ __('admin.labels.fill_field',['field' => __('admin.pages.user.name')]) }}"
                           class="input-regular" required>
                    @if ($errors->has('name'))
                        <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                    @endif
                </div>
                {{--<div class="input-group {{ $errors->has('middle_name') ? ' has-error' : '' }}">--}}
                    {{--<label class="input-group__title">{{ __('admin.pages.user.middle_name') }}</label>--}}
                    {{--<input type="text" name="middle_name" value="{{ $item->middle_name }}"--}}
                           {{--placeholder="{{ __('admin.labels.fill_field',['field' => __('admin.pages.user.middle_name')]) }}"--}}
                           {{--class="input-regular">--}}
                    {{--@if ($errors->has('middle_name'))--}}
                        {{--<span class="help-block"><strong>{{ $errors->first('middle_name') }}</strong></span>--}}
                    {{--@endif--}}
                {{--</div>--}}
                <div class="input-group {{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('admin.pages.user.email') }}</label>
                    <input type="email" name="email" value="{{ $item->email }}"
                           placeholder="{{ __('admin.labels.fill_field',['field' => __('admin.pages.user.email')]) }}"
                           class="input-regular" required>
                    @if ($errors->has('email'))
                        <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                    @endif
                </div>
                <div class="buttons">
                    <div>
                        <button type="submit" class="btn btn--green">{{ __('admin.labels.save') }}</button>
                    </div>
                </div>
            </div>
        </form>
        <form action="/{{$lang}}/admin/profile" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="block">
                <h2 class="title-secondary">Сменить пароль</h2>
                <div class="input-group {{ $errors->has('old_password') ? ' has-error' : '' }}">
                    <label class="input-group__title">Старый пароль *</label>
                    <input type="password" name="old_password"
                           placeholder="{{ __('admin.labels.fill_field',['field' => 'Старый пароль']) }}"
                           class="input-regular" required>
                    @if ($errors->has('old_password'))
                        <span class="help-block"><strong>{!! $errors->first('old_password') !!}</strong></span>
                    @endif
                </div>
                <div class="input-group {{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="input-group__title">Новый пароль *</label>
                    <input type="password" name="password"
                           placeholder="{{ __('admin.labels.fill_field',['field' => 'Новый пароль']) }}"
                           class="input-regular" required>
                    @if ($errors->has('password'))
                        <span class="help-block"><strong>{!! $errors->first('password') !!}</strong></span>
                    @endif
                </div>
                <div class="input-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <label class="input-group__title">Подтвердите пароль *</label>
                    <input type="password" name="password_confirmation"
                           placeholder="{{ __('admin.labels.fill_field',['field' => 'Подтвердите пароль']) }}"
                           class="input-regular" required>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block"><strong>{!! $errors->first('password_confirmation') !!}</strong></span>
                    @endif
                </div>
                <div class="buttons">
                    <div>
                        <button type="submit" class="btn btn--green">{{ __('admin.labels.save') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
