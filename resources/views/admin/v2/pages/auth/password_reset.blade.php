@extends('admin.v2.layout.auth.template')

@section('title',__('admin.pages.password_reset.title').' | '.__('admin.site_name'))

@section('head')

@endsection

@section('content')
    {{--<img src="/assets/admin/img/logo.svg" alt="FlowDoc" class="logo">--}}
    <form action="/{{$lang}}/admin/passwordReset" method="POST">
        {{ csrf_field() }}
        <div class="input-group">
            <input type="email" name="email" placeholder="{{ __('admin.labels.email') }}" class="input-regular"
                   required value="{{ old('email') }}">
            <div class="text-right">
                <a href="/{{$lang}}/admin/login" title="{!! __('admin.pages.login.title') !!}"
                   class="grey-link small">{!! __('admin.pages.login.title') !!}</a>
            </div>
        </div>
        <div class="input-group">
            <button class="btn" type="submit" style="width: 100%;">{{ __('admin.pages.password_reset.submit') }}</button>
        </div>
    </form>
    @include('admin.v2.partials.components.errors')
@endsection
