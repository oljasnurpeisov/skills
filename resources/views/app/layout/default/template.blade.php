<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    @include('app.layout.default.components.head')
    @yield('head')
</head>
<body>
{{--<input id="lang" type="hidden" value="ru">--}}
<div class="main-wrapper">
    @include('app.layout.default.components.header')
    @yield('content')

    @include('app.layout.default.components.footer')
    @include('app.layout.default.components.scripts')
    @include('app.layout.default.components.modals')
    @yield('scripts')
</div>
</body>
</html>
