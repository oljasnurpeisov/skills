@extends('admin.v2.layout.default.template')

@section('title',__('admin.pages.static_pages.edit_pages').' | '.__('default.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li>{{ __('admin.pages.static_pages.title') }}
            </li>
            <li class="active">{{ __('admin.pages.static_pages.main') }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @include('admin.v2.partials.components.errors')
        <form id="mainEdit" action="/{{$lang}}/admin/static-pages/main-update" method="post"
              enctype="multipart/form-data">
            @csrf
            @php($languages = ['ru' => 'Русский язык', 'kk' => 'Казахский язык', 'en' => 'Английский язык'])
            <div class="block">
                <div class="tabs">
                    <div class="mobile-dropdown">
                        <div class="mobile-dropdown__desc">
                            <ul class="tabs-titles">
                                @foreach($languages as $key => $language)
                                    <li class="{{$key == 'ru' ? 'active' : ''}}"><a href="javascript:;"
                                                                                    title="{{$language}}">{{$language}}</a>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                    <div class="tabs-contents">
                        @foreach($languages as $lang_key => $language)
                            <div class="{{$lang_key == 'ru' ? 'active' : ''}}">
                                <h2 class="title-secondary">{{__('admin.pages.static_pages.main_banner_title')}}</h2>
                                <div id="main_banner_{{$lang_key}}">
                                    <div class="input-group">
                                        <label class="input-group__title">{{__('admin.pages.static_pages.step_title')}} @if($lang_key == 'ru')
                                                *@endif</label>
                                        <input type="text" name="banner_title_{{$lang_key}}"
                                               value="{{json_decode($item->getAttribute('data_'.$lang_key))->main_banner->title}}"
                                               placeholder="{{__('admin.pages.static_pages.step_title_placeholder')}}"
                                               class="input-regular {{$lang_key == 'ru' ? 'required' : ''}}">
                                    </div>
                                    <div class="input-group">
                                        <label class="input-group__title">{{__('admin.pages.static_pages.teaser_title')}} @if($lang_key == 'ru')
                                                *@endif</label>
                                        <input type="text" name="banner_teaser_{{$lang_key}}"
                                               value="{{json_decode($item->getAttribute('data_'.$lang_key))->main_banner->teaser}}"
                                               placeholder="{{__('admin.pages.static_pages.teaser_placeholder')}}"
                                               class="input-regular {{$lang_key == 'ru' ? 'required' : ''}}">
                                    </div>
                                    @if($lang_key == 'ru')
                                        <div class="input-group {{ $errors->has('avatar') ? ' has-error' : '' }}">
                                            <label class="input-group__title">{{ __('admin.pages.static_pages.main_banner_image') }} *</label>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <img src="{{json_decode($item->getAttribute('data_'.$lang_key))->main_banner->image}}"
                                                         id="avatar_image" class="file-upload-image" style="height: 150px">
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="hidden" name="avatar" value="{{json_decode($item->getAttribute('data_'.$lang_key))->main_banner->image}}">
                                                    <div id="avatar" class="file-upload">
                                                        <div id="avatar_uploader" class="file">
                                                            <div class="progress">
                                                                <div class="progress-bar"></div>
                                                            </div>
                                                            <span class="file__name">
                                    .png, .jpg • 25 MB<br/>
                                    <strong>{{ __('admin.pages.static_pages.upload_image') }}</strong>
                                </span>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('avatar'))
                                                        <span class="help-block"><strong>{{ $errors->first('avatar') }}</strong></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <hr>
                                <h2 class="title-secondary">{{__('admin.pages.static_pages.step_by_step')}}</h2>
                                <div id="steps_{{$lang_key}}">
                                    @foreach(json_decode($item->getAttribute('data_'.$lang_key))->step_by_step as $key => $step)
                                        <div class="input-group">
                                            <label class="input-group__title">{{__('admin.pages.static_pages.step_title')}} @if($key == 0)
                                                    *@endif</label>
                                            <input type="text" name="steps_{{$lang_key}}[]" value="{{ $step->name }}"
                                                   placeholder="{{__('admin.pages.static_pages.step_title_placeholder')}}"
                                                   class="input-regular" {{$key == 0 ? 'required' : ''}}>
                                        </div>
                                        <div class="input-group">
                                            <label for="description"
                                                   class="input-group__title">{{__('admin.pages.static_pages.step_description')}} @if($key == 0)
                                                    *@endif</label>
                                            <textarea name="descriptions_{{$lang_key}}[]"
                                                      placeholder="{{__('admin.pages.static_pages.step_description_placeholder')}}"
                                                      class="input-regular" {{$key == 0 ? 'required' : ''}}>{{ $step->description }}</textarea>
                                        </div>
                                        <hr>
                                    @endforeach
                                </div>
                                <div class="buttons">
                                    <div>
                                        @if(count(json_decode($item->getAttribute('data_'.$lang_key))->step_by_step) < 6)
                                            <button type="button" id="add_step_{{$lang_key}}"
                                                    class="btn btn--blue">{{ __('admin.pages.static_pages.add_btn') }}</button>
                                        @endif
                                    </div>
                                </div>
                                <br>
                                <h2 class="title-secondary">{{__('admin.pages.static_pages.for_authors')}}</h2>
                                <div class="input-group">
                                    <label for="for_authors_description"
                                           class="input-group__title">{{__('admin.pages.static_pages.step_description')}}
                                        *</label>
                                    <textarea name="for_authors_description_{{$lang_key}}"
                                              placeholder="{{__('admin.pages.static_pages.step_description_placeholder')}}"
                                              class="input-regular"
                                              required>{{json_decode($item->getAttribute('data_'.$lang_key))->for_authors->description}}</textarea>
                                </div>
                                <div class="input-group">
                                    <label class="input-group__title">{{__('admin.pages.static_pages.btn_title')}}
                                        *</label>
                                    <input type="text" name="for_authors_btn_title_{{$lang_key}}"
                                           value="{{json_decode($item->getAttribute('data_'.$lang_key))->for_authors->btn_title}}"
                                           placeholder="{{__('admin.pages.static_pages.step_title_placeholder')}}"
                                           class="input-regular" required>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="block">
                <div class="buttons">
                    <div>
                        <button type="submit" class="btn btn--green">{{ __('admin.labels.save') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        var uploaders = new Array();

        initUploaders = function (uploaders) {
            $(".file-upload").each(function () {
                var el = $(this),
                    button = el.attr("id") + "_uploader",
                    progressBar = el.find('.progress-bar'),
                    input = el.siblings('input'),
                    fileUploadPlaceholder = $("#" + el.attr("id") + "_image");

                var uploader = new plupload.Uploader({
                    runtimes: 'gears,html5,flash,silverlight,browserplus',
                    browse_button: button,
                    drop_element: button,
                    max_file_size: '1mb',
                    url: "/ajaxUploadImageContent?_token={{ csrf_token() }}",
                    flash_swf_url: '/assets/admin/libs/plupload/js/Moxie.swf',
                    silverlight_xap_url: '/assets/admin/libs/plupload/js/Moxie.xap',
                    filters: [
                        {title: "Image files", extensions: "png,jpg,jpeg"}
                    ],
                    unique_names: true,
                    multiple_queues: false,
                    multi_selection: false
                });

                uploader.bind('FilesAdded', function (up, files) {
                    progressBar.css({width: 0});
                    el.removeClass('error').removeClass('success').addClass('disabled');
                    uploader.start();
                });

                uploader.bind("UploadProgress", function (up, file) {
                    progressBar.css({width: file.percent + "%"});
                });

                uploader.bind("FileUploaded", function (up, file, response) {
                    var obj = $.parseJSON(response.response.replace(/^.*?({.*}).*?$/gi, "$1"));
                    input.val(obj.location);
                    fileUploadPlaceholder.attr('src', obj.location);
                    el.removeClass('disabled').removeClass('error').addClass('success');
                    up.refresh();
                    console.log(obj.location);
                });

                uploader.bind("Error", function (up, err) {
                    progressBar.css({width: 0});
                    el.removeClass('disabled').removeClass('success').addClass('error');
                    up.refresh();
                });

                uploader.init();

                uploaders.push(uploader);
            });
        };

        initUploaders(uploaders);
    </script>
    <script>
        languages = ['ru', 'kk', 'en']
        const max_steps = 6

        step_names = [];
        step_descriptions = [];
        for (language of languages) {
            step_names[language] = '<div class="input-group">' +
                '                            <label class="input-group__title">{{__('admin.pages.static_pages.step_title')}}</label>' +
                '                            <input type="text" name="steps_' + language + '[]" value=""' +
                '                                   placeholder="{{__('admin.pages.static_pages.step_title_placeholder')}}"' +
                '                                   class="input-regular">' +
                '                        </div>';

            step_descriptions[language] = '<div class="input-group">' +
                '                            <label for="description"' +
                '                                   class="input-group__title">{{__('admin.pages.static_pages.step_description')}}</label>' +
                '                            <textarea name="descriptions_' + language + '[]"' +
                '                                      placeholder="{{__('admin.pages.static_pages.step_description_placeholder')}}"' +
                '                                      class="input-regular"></textarea>' +
                '                        </div>'
        }

        $("#add_step_ru").click(function () {
            if ($("[name='steps_ru[]']").length < max_steps) {
                $("#steps_ru").append(step_names['ru'], step_descriptions['ru']);
            }
            if ($("[name='steps_ru[]']").length === max_steps) {
                $("#add_step_ru").remove();
            }
        });
        $("#add_step_kk").click(function () {
            if ($("[name='steps_kk[]']").length < max_steps) {
                $("#steps_kk").append(step_names['kk'], step_descriptions['kk']);
            }
            if ($("[name='steps_kk[]']").length === max_steps) {
                $("#add_step_kk").remove();
            }
        });
        $("#add_step_en").click(function () {
            if ($("[name='steps_en[]']").length < max_steps) {
                $("#steps_en").append(step_names['en'], step_descriptions['en']);
            }
            if ($("[name='steps_en[]']").length === max_steps) {
                $("#add_step_en").remove();
            }
        });
    </script>
@endsection
