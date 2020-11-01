<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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

            tr.collapse.in {
                display: table-row;
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
<div class="container">
    <br>
    <nav class="nav nav-pills nav-justified">
        <a class="nav-link" href="/{{$lang}}/my-courses">{{__('default.pages.courses.my_courses')}}</a>
        <a class="nav-link"
           href="/{{$lang}}/my-courses/unpublished">{{__('default.pages.courses.my_courses_unpublished')}}</a>
        <a class="nav-link" href="/{{$lang}}/my-courses/on-check">{{__('default.pages.courses.my_courses_onCheck')}}</a>
        <a class="nav-link" href="/{{$lang}}/my-courses/drafts">{{__('default.pages.courses.drafts')}}</a>
        <a class="nav-link" href="/{{$lang}}/my-courses/deleted">{{__('default.pages.courses.my_courses_deleted')}}</a>
    </nav>
    @if (Route::has('login'))
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            @auth
                <a href="/{{$lang}}/edit_profile" class="text-sm text-gray-700 underline">Профиль</a>|
                <a href="/{{$lang}}/logout" class="text-sm text-gray-700 underline">Logout</a>
            @else
                <a href="/{{$lang}}/login" class="text-sm text-gray-700 underline">Login</a>

                @if (Route::has('register'))
                    <a href="/{{$lang}}/login" class="ml-4 text-sm text-gray-700 underline">Register</a>
                @endif
            @endif
        </div>
    @endif
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="row my-2">
        <div class="col-lg-8 order-lg-2">
            <div class="tab-content py-4">
                <div class="tab-pane active" id="profile">
                    <form id="themes_form" action="/{{$lang}}/publish-course/{{$item->id}}" method="POST"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <h3><b>{{$item->name}}</b></h3>
                        <p>{!! $item->teaser !!}</p>
                        <br>
                        <h4><b>{{__('default.pages.courses.profit_title')}}</b></h4>
                        <p>{!! $item->profit_desc !!}</p>
                        <br>
                        <h4><b>{{__('default.pages.courses.course_materials')}}</b></h4>
                        <p>Уроков {{$lessons_count}}</p>
                        <table class="table table-striped" id="themes_table">
                            <tbody>
                            @foreach($themes as $key => $theme)

                                <tr>
                                    <td>{{$theme->name}}</td>
                                    @switch(!($item->status))
                                        @case(1)
                                        @case(3)
                                        <td>
                                            <a href="/{{$lang}}/my-courses/course/{{$item->id}}/theme-{{$theme->id}}/create-lesson"
                                               class="btn btn-primary">+</a>
                                            <button type="button" theme-id="{{$theme->id}}"
                                                    theme-name="{{$theme->name}}"
                                                    class="btn btn-warning" data-toggle="modal"
                                                    data-target=".editThemeModal"><i class="fa fa-pencil"></i></button>
                                            <button type="button" class="btn btn-danger deleteThemeBtn"><i
                                                        class="fa fa-trash"></i></button>
                                            <button type="button" class="btn btn-info moveUpThemeBtn"><i
                                                        class="fa fa-arrow-up"></i></button>
                                            <button type="button" class="btn btn-info moveDownThemeBtn"><i
                                                        class="fa fa-arrow-down"></i></button>
                                        </td>
                                        @break
                                    @endswitch
                                    <td hidden>{{$theme->id}}</td>
                                    <td hidden>{{$theme->index_number}}</td>
                                </tr>
                                @foreach($theme->lessons()->orderBy('index_number', 'asc')->get() as $key => $lesson)
                                    <tr>
                                        <td></td>
                                        <td>{{$lesson->name}}&nbsp;&nbsp;
                                            @switch(!($item->status))
                                                @case(1)
                                                @case(3)
                                                <a href="/{{$lang}}/my-courses/theme-{{$theme->id}}/edit-lesson-{{$lesson->id}}"
                                                   class="btn btn-warning"><i class="fa fa-pencil"></i></a>
                                                <button type="button" class="btn btn-danger deleteLessonBtn"><i
                                                            class="fa fa-trash"></i></button>
                                                <button type="button" class="btn btn-info moveUpLessonBtn"><i
                                                            class="fa fa-arrow-up"></i></button>
                                                <button type="button" class="btn btn-info moveDownLessonBtn"><i
                                                            class="fa fa-arrow-down"></i></button>
                                                @break
                                            @endswitch
                                        </td>
                                        <td hidden>{{$lesson->id}}</td>
                                        <td hidden>{{$lesson->index_number}}</td>
                                        <td hidden>{{$theme->id}}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                        @switch(!($item->status))
                            @case(1)
                            @case(3)
                            <br>
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#createThemeModal">
                                Создать тему
                            </button>
                            @break
                        @endswitch
                        <br><br>
                        <h4><b>{{__('default.pages.courses.course_description')}}</b></h4>
                        <p>{!! $item->description !!}</p>
                        <div class="modal-footer">
                        </div>
                        {{--@if($item->status != 3 or $item->status != 1)--}}
                        @switch(!($item->status))
                            @case(1)
                            @case(3)
                            <a style="color: white" class="btn btn-primary" data-toggle="modal"
                               data-target="#publishModal">Опубликовать</a>
                            <a style="color: white" class="btn btn-warning"
                               href="/{{$lang}}/my-courses/edit-course/{{$item->id}}">Редактировать</a>
                            @break
                        @endswitch

                        @if($item->status != 4)
                            <a style="color: white" class="btn btn-danger"
                               href="/{{$lang}}/my-courses/delete-course/{{$item->id}}" data-toggle="modal"
                               data-target="#rejectModal">Удалить курс</a>
                        @endif

                        <div class="modal fade" id="publishModal" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Курс создан</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Вы уверены, что хотите опубликовать курс?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Да</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                    <form action="/{{$lang}}/my-courses/delete-course/{{$item->id}}" id="delete_form" method="POST">
                        {{ csrf_field() }}

                        <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Удаление курса</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Вы уверены, что хотите удалить курс?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Да</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="course_form">
                        {{ csrf_field() }}
                        <input name="course_id" value="{{$item->id}}" hidden>
                        <div class="modal fade" id="createThemeModal" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Название темы</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="theme_name">Название темы</label>
                                            <input type="text" class="form-control" id="theme_name" name="theme_name"
                                                   aria-describedby="emailHelp" placeholder="Введите название темы">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" id="createThemeBtn" class="btn btn-primary">Создать
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <form id="edit_course_form">
                        {{ csrf_field() }}
                        <input name="course_id" value="{{$item->id}}" hidden>
                        <div class="modal fade editThemeModal" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Название темы</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="theme_name">Название темы</label>
                                            <input type="text" class="form-control" id="edit_theme_name"
                                                   name="edit_theme_name"
                                                   aria-describedby="emailHelp" placeholder="Введите название темы"
                                                   value="">
                                            <input name="theme_id" value="" hidden>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" id="editThemeBtn" class="btn btn-primary">Сохранить
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
</body>
<script>
    {{--console.log({{json_encode(url('/create-theme'))}});--}}
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#createThemeBtn").click(function (e) {
        e.preventDefault();
        var request = $('#course_form').serializeArray();
        $.ajax({
            type: "POST",
            url: {!! json_encode(url('/ru/create-theme')) !!},
            data: {
                course_id: request[1]['value'],
                theme_name: request[2]['value']
            },
            success: function (data) {
                // var newRowContent = "<tr><td>" + request[2]['value'] + "</td></tr>";
                // $(newRowContent).appendTo($("#themes_table"));
                $('#themes_table').html(data);
                $("#theme_name").val('');
                $("#createThemeModal").modal('hide');
            }
        });

    });

    $('.editThemeModal').on('show.bs.modal', function (e) {
        // get information to update quickly to modal view as loading begins
        var opener = e.relatedTarget;//this holds the element who called the modal

        //we get details from attributes
        var theme_name = $(opener).attr('theme-name');
        var theme_id = $(opener).attr('theme-id');

//set what we got to our form
        $('#edit_course_form').find('[name="edit_theme_name"]').val(theme_name);
        $('#edit_course_form').find('[name="theme_id"]').val(theme_id);

    });

    $("#editThemeBtn").click(function (e) {
        e.preventDefault();
        var request = $('#edit_course_form').serializeArray();
        var theme_id = $("#edit_course_form :input[name='theme_id']").val();
        $.ajax({
            type: "POST",
            url: {!! json_encode(url('/ru/edit-theme/')) !!} +'/' + theme_id,
            data: {
                course_id: request[1]['value'],
                theme_name: request[2]['value']
            },
            success: function (data) {
                $('#themes_table').html(data);
                $(".editThemeModal").modal('hide');
            }
        });

    });

    $(document).delegate(".deleteThemeBtn", "click", function (e) {
        e.preventDefault();
        var request = $(this).closest("tr").find("td:nth-child(3)").text();
        $.ajax({
            type: "DELETE",
            url: {!! json_encode(url('ru/delete-theme/')) !!}+'/' + request,
            data: {
                theme_id: request,
                course_id: {{$item->id}},
            },
            success: function (data) {
                $('#themes_table').html(data);
            }
        });
    });

    $(document).delegate(".deleteLessonBtn", "click", function (e) {
        e.preventDefault();
        var request = $(this).closest("tr").find("td:nth-child(3)").text();
        $.ajax({
            type: "DELETE",
            url: {!! json_encode(url('ru/delete-lesson/')) !!}+'/' + request,
            data: {
                lesson_id: request,
                course_id: {{$item->id}},
            },
            success: function (data) {
                $('#themes_table').html(data);
            }
        });
    });

    $(document).delegate(".moveUpLessonBtn", "click", function (e) {
        e.preventDefault();
        var request = $(this).closest("tr").find("td:nth-child(3)").text();
        var lesson_index = $(this).closest("tr").find("td:nth-child(4)").text();
        var theme_id = $(this).closest("tr").find("td:nth-child(5)").text();
        $.ajax({
            type: "POST",
            url: {!! json_encode(url('ru/moveup-lesson/')) !!}+'/' + request,
            data: {
                lesson_id: request,
                theme_id: theme_id,
                lesson_index: lesson_index,
                course_id: {{$item->id}},
            },
            success: function (data) {
                $('#themes_table').html(data);
            }
        });
    });

    $(document).delegate(".moveDownLessonBtn", "click", function (e) {
        e.preventDefault();
        var request = $(this).closest("tr").find("td:nth-child(3)").text();
        var lesson_index = $(this).closest("tr").find("td:nth-child(4)").text();
        var theme_id = $(this).closest("tr").find("td:nth-child(5)").text();
        $.ajax({
            type: "POST",
            url: {!! json_encode(url('ru/movedown-lesson/')) !!}+'/' + request,
            data: {
                lesson_id: request,
                theme_id: theme_id,
                lesson_index: lesson_index,
                course_id: {{$item->id}},
            },
            success: function (data) {
                $('#themes_table').html(data);
                console.log(data)
            }
        });
    });

    $(document).delegate(".moveUpThemeBtn", "click", function (e) {
        e.preventDefault();
        var request = $(this).closest("tr").find("td:nth-child(3)").text();
        var theme_index = $(this).closest("tr").find("td:nth-child(4)").text();
        $.ajax({
            type: "POST",
            url: {!! json_encode(url('ru/moveup-theme/')) !!}+'/' + request,
            data: {
                theme_id: request,
                theme_index: theme_index,
                course_id: {{$item->id}},
            },
            success: function (data) {
                $('#themes_table').html(data);
                // console.log(data);
            }
        });
    });
    $(document).delegate(".moveDownThemeBtn", "click", function (e) {
        e.preventDefault();
        var request = $(this).closest("tr").find("td:nth-child(3)").text();
        var theme_index = $(this).closest("tr").find("td:nth-child(4)").text();
        $.ajax({
            type: "POST",
            url: {!! json_encode(url('ru/movedown-theme/')) !!}+'/' + request,
            data: {
                theme_id: request,
                theme_index: theme_index,
                course_id: {{$item->id}},
            },
            success: function (data) {
                $('#themes_table').html(data);
                // console.log(data);
            }
        });
    });
</script>
</html>
