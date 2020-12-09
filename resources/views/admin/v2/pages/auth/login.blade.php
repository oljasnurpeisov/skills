@extends('admin.v2.layout.auth.template')

@section('title',__('admin.pages.login.title').' | '.__('admin.site_name'))

@section('head')

@endsection

@section('content')
    <img src="/assets/img/logo.svg" alt="" class="logo">
    <form action="/admin/login" method="POST">
        {{ csrf_field() }}
        <div class="input-group">
            <input type="email" name="email" placeholder="{{ __('admin.labels.email') }}" class="input-regular"
                   required value="{{ old('email') }}">
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="{{ __('admin.labels.password') }}"
                   class="input-regular" required value="{{ old('password') }}">
            <div class="text-right"><a href="/{{$lang}}/admin/passwordReset"
                                       title="{!! __('admin.pages.password_reset.title') !!}"
                                       class="grey-link small">{!! __('admin.pages.password_reset.title') !!}</a>
            </div>
        </div>
        <div class="input-group">
            <button class="btn" type="submit" style="width: 100%;">{{ __('admin.pages.login.submit') }}</button>
        </div>
    </form>
    @include('admin.v2.partials.components.errors')
    @include('admin.v2.partials.components.success',['message' => session('password_reset')])
@endsection
