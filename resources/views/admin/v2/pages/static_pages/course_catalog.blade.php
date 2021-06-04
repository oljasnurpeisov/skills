@extends('admin.v2.layout.default.template')

@section('title',__('admin.pages.static_pages.edit_pages').' | '.__('default.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li>{{ __('admin.pages.static_pages.title') }}
            </li>
            <li class="active">{{ __('admin.pages.static_pages.for_authors') }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @include('admin.v2.partials.components.errors')
        <form id="mainEdit" action="/{{$lang}}/admin/static-pages/course-catalog-update" method="post"
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
                                <div id="main_banner_{{$lang_key}}">
                                    <div class="input-group">
                                        <label
                                            class="input-group__title">{{__('admin.pages.static_pages.image_link')}} @if($lang_key == 'ru')
                                                *@endif</label>
                                        <input type="text" name="image_link_{{$lang_key}}"
                                               value="{{json_decode($item->getAttribute('data_'.$lang_key))->course_catalog->link}}"
                                               placeholder="{{__('admin.pages.static_pages.image_link_placeholder')}}"
                                               class="input-regular {{$lang_key == 'ru' ? 'required' : ''}}">
                                    </div>
                                    <div class="input-group {{ $errors->has('avatar') ? ' has-error' : '' }}">
                                        <label class="input-group__title">
                                            {{ __('admin.pages.static_pages.main_banner_image') }}*
                                        </label>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <img
                                                    src="{{json_decode($item->getAttribute('data_'.$lang_key))->course_catalog->image}}"
                                                    id="avatar_{{ $lang_key }}_image" class="file-upload-image"
                                                    style="height: 150px">
                                            </div>
                                            <div class="col-md-10">
                                                <input type="hidden" name="image_{{ $lang_key }}"
                                                       value="{{json_decode($item->getAttribute('data_'.$lang_key))->course_catalog->image}}">
                                                <div id="avatar_{{ $lang_key }}" class="file-upload">
                                                    <div id="avatar_{{ $lang_key }}_uploader" class="file">
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
                                                    <span
                                                        class="help-block"><strong>{{ $errors->first('avatar') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
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
                    max_file_size: '25mb',
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
@endsection
