@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="plain">
            <div class="container">
                <ul class="breadcrumbs">
                    <li><a href="/{{$lang}}/my-courses"
                           title="{{__('default.pages.courses.my_courses_title')}}">{{__('default.pages.courses.my_courses_title')}}</a>
                    </li>
                    @include('app.pages.author.courses.components.breadcrumb_course_type',['item' => $item])
                    <li><a href="/{{$lang}}/my-courses/course/{{$item->id}}"
                           title="{{$item->name}}">{{$item->name}}</a>
                    </li>
                    @if($lesson->type == 4)
                        <li>
                            <span>{{$lesson->lesson_type->getAttribute('name_'.$lang) ?? $lesson->lesson_type->getAttribute('name_ru')}}</span>
                        </li>
                    @else
                        <li><span>{{$lesson->name}}</span></li>
                    @endif
                </ul>

                <div class="row row--multiline">
                    <div class="col-md-8">
                        <div class="article">
                            @if($lesson->type == 4)
                                <h1 class="page-title">{{$lesson->lesson_type->getAttribute('name_'.$lang) ?? $lesson->lesson_type->getAttribute('name_ru')}}</h1>
                            @else
                                <h1 class="page-title">{{$lesson->name}}</h1>
                            @endif
                            <form action="/{{$lang}}/course-{{$item->id}}/lesson-{{$lesson->id}}/author-test-submit"
                                  method="POST">
                                @csrf
                                <div class="test">
                                    @foreach(json_decode($lesson->practice)->questions as $key => $question)
                                        @if($question->is_pictures == false)
                                            <div class="item">
                                                <div class="question">{!! $question->name !!}
                                                </div>
                                                <div class="form-group">
                                                    @if(json_decode($lesson->practice)->mixAnswers == true)
                                                        <?php
                                                        shuffle($question->answers)
                                                        ?>
                                                    @endif
                                                    @foreach($question->answers as $k => $answer)
                                                        <label class="radio"><input type="radio"
                                                                                    name="answers[{{$key}}]"
                                                                                    value="{{$answer}}"
                                                                                    required><span>{{$answer}}</span></label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="item">
                                                <div class="question">{!! $question->name !!}
                                                </div>
                                                <div class="form-group image-answers">
                                                    @if(json_decode($lesson->practice)->mixAnswers == true)
                                                        <?php
                                                        shuffle($question->answers)
                                                        ?>
                                                    @endif
                                                    @foreach($question->answers as $k => $answer)
                                                        <div>
                                                            <label class="radio"><input type="radio"
                                                                                        name="answers[{{$key}}]"
                                                                                        value="{{$answer}}"
                                                                                        required><span>{{__('default.pages.lessons.option_title')}} {{$k+1}}</span></label>
                                                            <a href="{{ $answer }}"
                                                               data-fancybox="question2"
                                                               title="{{__('default.pages.courses.zoom_certificate')}}"><img
                                                                        src="{{ $answer }}" alt=""></a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach

                                </div>
                                <div class="buttons">
                                    <button type="submit"
                                            class="btn">{{__('default.pages.lessons.done_test_btn')}}</button>
                                    <a href="{{ url()->previous() }}"
                                       title="{{__('default.pages.courses.cancel_title')}}"
                                       class="ghost-btn">{{__('default.pages.courses.cancel_title')}}</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-4">
                        @if($item->is_poor_vision == true)
                            <div class="sidebar">
                                <div class="sidebar__inner">
                                    <div class="poor-vision">
                                        <div><label class="checkbox"><input type="checkbox"
                                                                            data-toggle="poorVision,regularMaterials,poorVisionMaterials"
                                                                            name="poorVision"><span><span
                                                            class="special">{{__('default.pages.lessons.poor_vision_title')}}</span></span></label>
                                        </div>
                                        <div id="poorVision" style="display:none;">
                                            <div class="sidebar-item">
                                                <div class="sidebar-item__title">{{__('default.pages.lessons.font_size_title')}}
                                                    :
                                                </div>
                                                <div class="sidebar-item__body">
                                                    <div class="range-slider-wrapper">
                                                        <input type="range" class="range-slider single-range-slider"
                                                               name="fontSize" min="12"
                                                               data-decimals="0" step="1" max="32" value="24">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="sidebar-item">
                                                <div class="sidebar-item__title">{{__('default.pages.lessons.line_spacing')}}
                                                    :
                                                </div>
                                                <div class="sidebar-item__body">
                                                    <div class="range-slider-wrapper">
                                                        <input type="range" class="range-slider single-range-slider"
                                                               name="lineHeight" min="1"
                                                               data-decimals="1" step="0.5" max="3" value="2">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="sidebar-item">
                                                <div class="sidebar-item__title">{{__('default.pages.lessons.font')}}:
                                                </div>
                                                <div class="sidebar-item__body">
                                                    <select name="fontFamily" class="selectize-regular custom"
                                                            placeholder="Выберите шрифт">
                                                        <option value="">{{__('default.pages.lessons.default_title')}}</option>
                                                        <option value="Arial, sans-serif">Arial</option>
                                                        <option value="Times New Roman, sans-serif">Times New Roman
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="sidebar-item">
                                                <div class="sidebar-item__title">{{__('default.pages.lessons.color_palette')}}
                                                    :
                                                </div>
                                                <div class="sidebar-item__body">
                                                    <select name="colorScheme" class="selectize-regular custom color"
                                                            placeholder="{{__('default.pages.lessons.choose_palette')}}">
                                                        <option value="black-white">{{__('default.pages.lessons.default_title')}}</option>
                                                        <option value="white-black">{{__('default.pages.lessons.white_on_black_palette')}}</option>
                                                        <option value="yellow-blue">{{__('default.pages.lessons.yellow_on_blue_palette')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="sidebar">
                            <div class="sidebar__inner">
                                <div id="regularMaterials">
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title">{{__('default.pages.courses.media_attachments')}}
                                            :
                                        </div>
                                        <div class="sidebar-item__body">
                                            @if(!empty($lesson->lesson_attachment->videos_link))
                                                @foreach(json_decode($lesson->lesson_attachment->videos_link) as $video_link)
                                                    @if(!empty($video_link))
                                                        @php
                                                            $video_id = explode("?v=", $video_link);
                                                            $video_id = $video_id[1];
                                                        @endphp
                                                        <div class="video-wrapper">
                                                            <iframe width="560" height="315"
                                                                    src="https://www.youtube.com/embed/{{$video_id}}"
                                                                    frameborder="0"
                                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                                    allowfullscreen></iframe>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if(!empty($lesson->lesson_attachment->videos))
                                                @foreach(json_decode($lesson->lesson_attachment->videos) as $video)
                                                    <div class="video-wrapper">
                                                        <video controls
                                                               src="{{$video}}"></video>
                                                    </div>
                                                @endforeach
                                            @endif
                                            @if(!empty($lesson->lesson_attachment->audios))
                                                @foreach(json_decode($lesson->lesson_attachment->audios) as $audio)
                                                    <audio controls
                                                           src="{{$audio}}"></audio>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title">{{__('default.pages.lessons.lesson_files')}}:
                                        </div>
                                        <div class="sidebar-item__body">
                                            <div class="plain-text">
                                                <ul>
                                                    @if(!empty($lesson->lesson_attachment->another_files))
                                                        @foreach(json_decode($lesson->lesson_attachment->another_files) as $file)
                                                            <li><a href="{{env('APP_URL').$file}}"
                                                                   title="{{substr(basename($file), 14)}}"
                                                                   target="_blank">{{substr(basename($file), 14)}}
                                                                    &nbsp;</a>
                                                                ({{ round(File::size(public_path($file))/1000000, 1) }}
                                                                MB)
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="poorVisionMaterials" style="display:none;">
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title">{{__('default.pages.lessons.media_attachments_poor_vision')}}
                                            :
                                        </div>
                                        <div class="sidebar-item__body">
                                            @if(!empty($lesson->lesson_attachment->videos_poor_vision_link))
                                                @foreach(json_decode($lesson->lesson_attachment->videos_poor_vision_link) as $video_link)
                                                    @if($video_link !== null)
                                                        @php
                                                            $video_id = explode("?v=", $video_link);
                                                            $video_id = $video_id[1] ?? null;
                                                        @endphp
                                                        <div class="video-wrapper">
                                                            <iframe width="560" height="315"
                                                                    src="https://www.youtube.com/embed/{{$video_id}}"
                                                                    frameborder="0"
                                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                                    allowfullscreen></iframe>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if(!empty($lesson->lesson_attachment->videos_poor_vision))
                                                @foreach(json_decode($lesson->lesson_attachment->videos_poor_vision) as $video)
                                                    <div class="video-wrapper">
                                                        <video controls
                                                               src="{{$video}}"></video>
                                                    </div>
                                                @endforeach
                                            @endif
                                            @if(!empty($lesson->lesson_attachment->audios_poor_vision))
                                                @foreach(json_decode($lesson->lesson_attachment->audios_poor_vision) as $audio)
                                                    <audio controls
                                                           src="{{$audio}}"></audio>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div class="sidebar-item">
                                        <div class="sidebar-item__title">{{__('default.pages.lessons.lesson_files_poor_vision')}}
                                            :
                                        </div>
                                        <div class="sidebar-item__body">
                                            <div class="plain-text">
                                                <ul>
                                                    @if(!empty($lesson->lesson_attachment->another_files_poor_vision))
                                                        @foreach(json_decode($lesson->lesson_attachment->another_files_poor_vision) as $file)
                                                            <li><a href="{{env('APP_URL').$file}}"
                                                                   title="{{substr(basename($file), 14)}}"
                                                                   target="_blank">{{substr(basename($file), 14)}}
                                                                    &nbsp;</a>
                                                                ({{ round(File::size(public_path($file))/1000000, 1) }}
                                                                MB)
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script src="/assets/js/visually-impaired-tools.js"></script>
    <!---->
@endsection

