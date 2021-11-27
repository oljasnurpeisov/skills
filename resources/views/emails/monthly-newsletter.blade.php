@php
    /** @var \App\Models\Course $course */
@endphp
    <!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<p>Приветствуем Вас! Загляните на Портал онлайн обучения <a style="text-decoration: none" href="https://skills.enbek.kz">skills.enbek.kz</a>, где
    размещен (ы) {{ $count }} новый (х) курс (ов) на темы:
@foreach($courses as $course)
    <p><a style="text-decoration: none" href="{{ route('courseView', ['lang' => 'ru','item' => $course->id]) }}">{{$course->name}}</a></p>
    @endforeach
    </p>
</body>
</html>
