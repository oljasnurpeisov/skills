@extends('admin.v2.layout.default.template')

@section('title',__('default.pages.dish.title').' | '.__('default.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li><a href="/admin/dish/index">{{ __('default.pages.dishes.title') }}</a>
            </li>
            <li class="active">{{ __('default.pages.dish.title') }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @include('admin.v2.partials.components.errors')
        <form action="/admin/dish/create" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="block">
                <h2 class="title-secondary">{{ __('default.pages.dish.title') }}</h2>
                <div class="input-group {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('default.pages.dish.name') }} *</label>
                    <input type="text" name="name" value="{{ $item->name }}"
                           placeholder="{{ __('default.labels.fill_field',['field' => __('default.pages.dish.name')]) }}"
                           class="input-regular" required>
                    @if ($errors->has('name'))
                        <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                    @endif
                </div>
                <div class="input-group {{ $errors->has('producer_id') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('default.pages.dish_producer.title') }} *</label>
                    <select name="producer_id" id="producer_id" class="input-regular chosen" data-placeholder=" "
                            required>
                        <option value="" disabled selected>-</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('producer_id'))
                        <span class="help-block"><strong>{{ $errors->first('producer_id') }}</strong></span>
                    @endif
                </div>
                <div class="input-group {{ $errors->has('description') ? 'has-error' : '' }}">
                    <label for="description"
                           class="input-group__title">{{ __('default.pages.dish.description') }}</label>
                    <textarea name="description"
                              placeholder="{{ __('default.labels.fill_field',['field' => __('default.pages.dish.description')]) }}"
                              class="input-regular">{{ $item->description }}</textarea>
                    @if ($errors->has('description'))
                        <span class="help-block"><strong>{{ $errors->first('description') }}</strong></span>
                    @endif
                </div>
                <div class="input-group {{ $errors->has('cost') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('default.pages.dish.cost') }} *</label>
                    <input type="number" name="cost" value="{{ $item->cost }}"
                           placeholder="{{ __('default.labels.fill_field',['field' => __('default.pages.dish.cost')]) }}"
                           class="input-regular" required>
                    @if ($errors->has('cost'))
                        <span class="help-block"><strong>{{ $errors->first('cost') }}</strong></span>
                    @endif
                </div>
                <div class="input-group {{ $errors->has('avatar') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('default.pages.dish.avatar') }}</label>
                    <div class="row">
                        <div class="col-md-2">
                            <img src="{{ $item->getAvatar() }}" id="avatar_image" class="file-upload-image">
                        </div>
                        <div class="col-md-10">
                            <input type="hidden" name="avatar" value="{{ $item->avatar }}">
                            <div id="avatar" class="file-upload">
                                <div id="avatar_uploader" class="file">
                                    <div class="progress">
                                        <div class="progress-bar"></div>
                                    </div>
                                    <span class="file__name">
                                    .png, .jpg • 25 MB<br/>
                                    <strong>{{ __('default.labels.upload_image') }}</strong>
                                </span>
                                </div>
                            </div>
                            @if ($errors->has('avatar'))
                                <span class="help-block"><strong>{{ $errors->first('avatar') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="block">
                <div class="buttons">
                    <div>
                        <button type="submit" class="btn btn--green">{{ __('default.labels.save') }}</button>
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
                    url: "/ajaxUploadImage?_token={{ csrf_token() }}",
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
