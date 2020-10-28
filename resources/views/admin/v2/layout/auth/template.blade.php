<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    @include('admin.v2.layout.default.components.head')
    @yield('head')
</head>
<body>
<input id="lang" type="hidden" value="ru">
<div class="authorization-wrapper">
    <div class="authorization-inner">
        @yield('content')
    </div>
    <div class="copyright">Canvas Technologies, 2011-{{ date('Y') }}</div>
</div>
@include('admin.v2.layout.default.components.scripts')
@yield('scripts')
</body>
</html>
