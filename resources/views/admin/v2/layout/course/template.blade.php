<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    @include('app.layout.default.components.head')
    @yield('head')
</head>
<body>

<div class="main-wrapper">
    @yield('content')

    @include('app.layout.default.components.scripts')
    @yield('scripts')
</div>
</body>
</html>
