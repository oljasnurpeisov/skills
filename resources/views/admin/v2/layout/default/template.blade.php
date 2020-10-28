<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    @include('admin.v2.layout.default.components.head')
    @yield('head')
</head>
<body>
<input id="lang" type="hidden" value="ru">
<div class="main-wrapper">
    @include('admin.v2.layout.default.components.navigation.left')
    <div class="right-wrapper">
        @include('admin.v2.layout.default.components.navigation.top')
        <main class="main">
            @yield('content')
        </main>
    </div>
</div>
@include('admin.v2.layout.default.components.scripts')
@include('admin.v2.layout.default.components.modals')
@yield('scripts')
</body>
</html>
