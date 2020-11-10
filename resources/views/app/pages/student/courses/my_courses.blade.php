<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */
        html {
            line-height: 1.15;
            -webkit-text-size-adjust: 100%
        }

        body {
            margin: 0
        }

        a {
            background-color: transparent
        }

        [hidden] {
            display: none
        }

        html {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
            line-height: 1.5
        }

        *, :after, :before {
            box-sizing: border-box;
            border: 0 solid #e2e8f0
        }

        a {
            color: inherit;
            text-decoration: inherit
        }

        svg, video {
            display: block;
            vertical-align: middle
        }

        video {
            max-width: 100%;
            height: auto
        }

        .bg-white {
            --bg-opacity: 1;
            background-color: #fff;
            background-color: rgba(255, 255, 255, var(--bg-opacity))
        }

        .bg-gray-100 {
            --bg-opacity: 1;
            background-color: #f7fafc;
            background-color: rgba(247, 250, 252, var(--bg-opacity))
        }

        .border-gray-200 {
            --border-opacity: 1;
            border-color: #edf2f7;
            border-color: rgba(237, 242, 247, var(--border-opacity))
        }

        .border-t {
            border-top-width: 1px
        }

        .flex {
            display: flex
        }

        .grid {
            display: grid
        }

        .hidden {
            display: none
        }

        .items-center {
            align-items: center
        }

        .justify-center {
            justify-content: center
        }

        .font-semibold {
            font-weight: 600
        }

        .h-5 {
            height: 1.25rem
        }

        .h-8 {
            height: 2rem
        }

        .h-16 {
            height: 4rem
        }

        .text-sm {
            font-size: .875rem
        }

        .text-lg {
            font-size: 1.125rem
        }

        .leading-7 {
            line-height: 1.75rem
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto
        }

        .ml-1 {
            margin-left: .25rem
        }

        .mt-2 {
            margin-top: .5rem
        }

        .mr-2 {
            margin-right: .5rem
        }

        .ml-2 {
            margin-left: .5rem
        }

        .mt-4 {
            margin-top: 1rem
        }

        .ml-4 {
            margin-left: 1rem
        }

        .mt-8 {
            margin-top: 2rem
        }

        .ml-12 {
            margin-left: 3rem
        }

        .-mt-px {
            margin-top: -1px
        }

        .max-w-6xl {
            max-width: 72rem
        }

        .min-h-screen {
            min-height: 100vh
        }

        .overflow-hidden {
            overflow: hidden
        }

        .p-6 {
            padding: 1.5rem
        }

        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem
        }

        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem
        }

        .pt-8 {
            padding-top: 2rem
        }

        .fixed {
            position: fixed
        }

        .relative {
            position: relative
        }

        .top-0 {
            top: 0
        }

        .right-0 {
            right: 0
        }

        .shadow {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06)
        }

        .text-center {
            text-align: center
        }

        .text-gray-200 {
            --text-opacity: 1;
            color: #edf2f7;
            color: rgba(237, 242, 247, var(--text-opacity))
        }

        .text-gray-300 {
            --text-opacity: 1;
            color: #e2e8f0;
            color: rgba(226, 232, 240, var(--text-opacity))
        }

        .text-gray-400 {
            --text-opacity: 1;
            color: #cbd5e0;
            color: rgba(203, 213, 224, var(--text-opacity))
        }

        .text-gray-500 {
            --text-opacity: 1;
            color: #a0aec0;
            color: rgba(160, 174, 192, var(--text-opacity))
        }

        .text-gray-600 {
            --text-opacity: 1;
            color: #718096;
            color: rgba(113, 128, 150, var(--text-opacity))
        }

        .text-gray-700 {
            --text-opacity: 1;
            color: #4a5568;
            color: rgba(74, 85, 104, var(--text-opacity))
        }

        .text-gray-900 {
            --text-opacity: 1;
            color: #1a202c;
            color: rgba(26, 32, 44, var(--text-opacity))
        }

        .underline {
            text-decoration: underline
        }

        .antialiased {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale
        }

        .w-5 {
            width: 1.25rem
        }

        .w-8 {
            width: 2rem
        }

        .w-auto {
            width: auto
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr))
        }

        @media (min-width: 640px) {
            .sm\:rounded-lg {
                border-radius: .5rem
            }

            .sm\:block {
                display: block
            }

            .sm\:items-center {
                align-items: center
            }

            .sm\:justify-start {
                justify-content: flex-start
            }

            .sm\:justify-between {
                justify-content: space-between
            }

            .sm\:h-20 {
                height: 5rem
            }

            .sm\:ml-0 {
                margin-left: 0
            }

            .sm\:px-6 {
                padding-left: 1.5rem;
                padding-right: 1.5rem
            }

            .sm\:pt-0 {
                padding-top: 0
            }

            .sm\:text-left {
                text-align: left
            }

            .sm\:text-right {
                text-align: right
            }
        }

        @media (min-width: 768px) {
            .md\:border-t-0 {
                border-top-width: 0
            }

            .md\:border-l {
                border-left-width: 1px
            }

            .md\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media (min-width: 1024px) {
            .lg\:px-8 {
                padding-left: 2rem;
                padding-right: 2rem
            }
        }

        @media (prefers-color-scheme: dark) {
            .dark\:bg-gray-800 {
                --bg-opacity: 1;
                background-color: #2d3748;
                background-color: rgba(45, 55, 72, var(--bg-opacity))
            }

            .dark\:bg-gray-900 {
                --bg-opacity: 1;
                background-color: #1a202c;
                background-color: rgba(26, 32, 44, var(--bg-opacity))
            }

            .dark\:border-gray-700 {
                --border-opacity: 1;
                border-color: #4a5568;
                border-color: rgba(74, 85, 104, var(--border-opacity))
            }

            .dark\:text-white {
                --text-opacity: 1;
                color: #fff;
                color: rgba(255, 255, 255, var(--text-opacity))
            }

            .dark\:text-gray-400 {
                --text-opacity: 1;
                color: #cbd5e0;
                color: rgba(203, 213, 224, var(--text-opacity))
            }
        }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Nunito';
        }
    </style>
</head>
<body class="antialiased">
{{--<div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">--}}
<div id="course_data">
    <div class="container" >
        <br>
        <nav class="nav nav-pills nav-justified">
            <a class="nav-link " href="/{{$lang}}/course-catalog">Каталог курсов</a>
            <a class="nav-link active" href="/{{$lang}}/student/my-courses">Мои курсы</a>
        </nav>
        <br>

        @if (Route::has('login'))
            <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                @auth
                    <a href="/{{$lang}}/" class="text-sm text-gray-700 underline">Главная </a>|
                    @if(Auth::user()->roles()->first()->id == 5)
                        <a href="/{{$lang}}/student-profile" class="text-sm text-gray-700 underline">Профиль</a>
                    @elseif(Auth::user()->roles()->first()->id == 4)
                        <a href="/{{$lang}}/edit_profile" class="text-sm text-gray-700 underline">Профиль</a>
                    @endif
                    <a href="/{{$lang}}/logout" class="text-sm text-gray-700 underline">Logout</a>
                @else
                    <a href="/{{$lang}}/login" class="text-sm text-gray-700 underline">Login</a>

                    @if (Route::has('register'))
                        <a href="/{{$lang}}/login" class="ml-4 text-sm text-gray-700 underline">Register</a>
                    @endif
                @endif
            </div>

        @endif
        @if (\Session::has('status'))
            <div class="alert alert-success">
                <ul>
                    <li>{!! \Session::get('status') !!}</li>
                </ul>
            </div>
        @endif
    </div>
    <div class="container">
        <form class="form-inline my-2 my-lg-0" id="courses_form">
            @csrf
            {{--<input class="form-control mr-sm-2" type="search" placeholder="Поиск" id="term" name="term" aria-label="Поиск"--}}
                   {{--value="{{ $term ?? '' }}">--}}
            {{--<button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Поиск</button>--}}
            {{--<br><br><br>--}}
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        @foreach($items as $item)
                            <div class="col-sm-4" style="margin-right: 55px;margin-bottom: 25px">
                                <div class="card" style="width: 15rem;">
                                    <img class="card-img-top" src="{{$item->course->image}}" alt="image" height="200px">
                                    <div class="card-body">
                                        <h5 class="card-title">{{$item->course->name}}</h5>
                                        <p class="card-text" style="color:#828282;">{{$item->course->teaser}}</p>


                                        <p class="card-text">Прогресс:<span class="badge badge-success">{{round(($item->finished_lessons_count/$item->lessons_count)*100)}}%</span></p>

                                        <a href="/{{$lang}}/course-catalog/course/{{$item->course->id}}" class="btn btn-primary">Перейти</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                {{--<div class="col-sm-2">--}}
                    {{--<div class="col-sm-3">--}}
                    {{--<div class="form-group">--}}
                        {{--<br>--}}
                        {{--<label for="sel1">{{__('default.pages.courses.profession')}}: </label>--}}
                        {{--<select class="form-control" id="professions" style="width:200px;" name="professions">--}}
                            {{--@foreach($professions as $profession)--}}
                                {{--@foreach($profession as $i)--}}
                                {{--<option value="{{$profession->id}}">{{$profession->getAttribute('name_'.$lang) ??  $type->getAttribute('name_ru')}}</option>--}}
                                {{--@endforeach--}}
                            {{--@endforeach--}}
                        {{--</select>--}}
                    {{--</div>--}}
                    {{--<br>--}}
                    {{--<div class="form-group">--}}
                        {{--<label for="sel1">{{__('default.pages.courses.skill')}}: </label>--}}
                        {{--<select class="form-control" id="skills" name="choosed_skills">--}}
                            {{--<option> </option>--}}
                            {{--@foreach($skills as $skill)--}}
                                {{--@foreach($profession as $i)--}}
                                {{--<option value="{{$skill->id}}">{{$skill->getAttribute('name_'.$lang) ??  $skill->getAttribute('name_ru')}}</option>--}}
                                {{--@endforeach--}}
                            {{--@endforeach--}}
                        {{--</select>--}}
                    {{--</div>--}}
                    {{--<br>--}}
                    {{--<p>{{__('default.pages.courses.language_education')}}</p>--}}
                    {{--<div class="form-check form-check-inline">--}}
                        {{--<input class="form-check-input" type="checkbox" id="choosed_lang_kk" name="choosed_lang_kk" value="0">--}}
                        {{--<label class="form-check-label" for="inlineCheckbox1">Казахский</label>--}}
                    {{--</div>--}}
                    {{--<div class="form-check form-check-inline">--}}
                        {{--<input class="form-check-input" type="checkbox" id="choosed_lang_ru" name="choosed_lang_ru" value="1">--}}
                        {{--<label class="form-check-label" for="inlineCheckbox2">Русский</label>--}}
                    {{--</div>--}}
                    {{--<br>--}}
                    {{--<div class="form-group">--}}
                        {{--<label for="sel1">{{__('default.pages.courses.rating_from')}}:</label>--}}
                        {{--<input type="number" class="form-control" placeholder="" name="rating_from">--}}
                    {{--</div>--}}
                    {{--<br>--}}
                    {{--<div class="form-group">--}}
                        {{--<label for="sel1">{{__('default.pages.courses.students_complete_course')}}:</label>--}}
                        {{--<input type="number" class="form-control" placeholder="" name="students_count">--}}
                    {{--</div>--}}
                    {{--<br>--}}
                    {{--<div class="form-group">--}}
                        {{--<label for="course_type_label">{{__('default.pages.courses.course_type')}}: </label>--}}
                        {{--<select class="form-control" id="course_type" name="course_type">--}}
                            {{--<option> </option>--}}
                            {{--<option value="1">{{__('default.pages.courses.paid_type')}}</option>--}}
                            {{--<option value="0">{{__('default.pages.courses.free_type')}}</option>--}}
                        {{--</select>--}}
                    {{--</div>--}}
                    {{--<br>--}}
                    {{--<div class="form-group">--}}
                        {{--<label for="sel1">{{__('default.pages.courses.sorting')}}: </label>--}}
                        {{--<select class="form-control" id="sel1" name="sorting">--}}
                            {{--<option>Не выбран</option>--}}
                        {{--</select>--}}
                    {{--</div>--}}
                    {{--<br><br><br><br>--}}
                    {{--</div>--}}
                {{--</div>--}}

            </div>
        </form>
    </div>
</div>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#professions').on('change', function() {
        var formdata = $('#courses_form').serializeArray();
        $.ajax({
            type: 'POST',
            url: 'https://dev3.panama.kz/ru/course-catalog-filter' ,
            data: formdata,
            success: function (data) {
                console.log(data);
                // $('#course_data').html(data);

            },
            error: function() {
                console.log(data);
            }
        });
    });

</script>
</body>
</html>
