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
    @include('app.layout.default.components.modals')
    @include('app.layout.default.components.scripts')
    @yield('scripts')
</div>
<script>
    $('body').on('keydown', function (e) {
        if (e.ctrlKey && e.keyCode === 13) {
            document.getElementById('openErrorOnPage').click()
            document.getElementById('errorOnPageText').value = document.getSelection().toString()
        }
    });
</script>
</body>
</html>
