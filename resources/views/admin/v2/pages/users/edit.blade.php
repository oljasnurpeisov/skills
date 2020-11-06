@extends('admin.v2.layout.default.template')

@section('title',$item->surname.' '.$item->name.' '.$item->middle_name.' | '.__('admin.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li><a href="/{{$lang}}/admin/user/index">{{ __('admin.pages.users.title') }}</a></li>
            <li class="active">{{ $item->surname.' '.$item->name.' '.$item->middle_name }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @if(session('newPassword'))
            <div class="alert alert-warning" role="alert">
                <strong>{{ __('admin.notifications.new_password',['password' => session('newPassword')]) }}</strong>
            </div>
        @endif
        @include('admin.v2.partials.components.errors')
        <form action="/{{$lang}}/admin/user/{{ $item->id }}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="block">
                <h2 class="title-secondary">{{ __('admin.pages.user.title') }}</h2>
                <div class="input-group {{ $errors->has('full_name') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('admin.pages.user.full_name') }} *</label>
                    <input type="text" name="name" value="{{ $item->name }}"
                           placeholder="{{ __('admin.labels.fill_field',['field' => __('admin.pages.user.full_name')]) }}"
                           class="input-regular" required>
                    @if ($errors->has('full_name'))
                        <span class="help-block"><strong>{{ $errors->first('full_name') }}</strong></span>
                    @endif
                </div>
                <div class="input-group {{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('admin.pages.user.email') }} *</label>
                    <input type="email" name="email" value="{{ $item->email }}"
                           placeholder="{{ __('admin.labels.fill_field',['field' => __('admin.pages.user.email')]) }}"
                           class="input-regular" required>
                    @if ($errors->has('email'))
                        <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                    @endif
                </div>
                <div class="input-group {{ $errors->has('iin') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('admin.pages.user.iin_bin') }} *</label>
                    <input type="text" name="iin" value="{{ $item->iin }}"
                           placeholder="{{ __('admin.labels.fill_field',['field' => __('admin.pages.user.iin_bin')]) }}"
                           class="input-regular" required>
                    @if ($errors->has('iin'))
                        <span class="help-block"><strong>{{ $errors->first('iin') }}</strong></span>
                    @endif
                </div>
                <div class="input-group {{ $errors->has('type_of_ownership') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('admin.pages.user.type_of_ownership') }} *</label>
                    <select name="type_of_ownership" id="type_of_ownership" class="input-regular chosen"
                            data-placeholder=" " required>
                        @foreach($types_of_ownership as $type)
                            <option value="{{ $type->id }}"
                                    @if($type->id==$item->type_ownership->id) selected='selected' @endif >{{ $type->getAttribute('name_'.$lang) ??  $type->getAttribute('name_ru') }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('type_of_ownership'))
                        <span class="help-block"><strong>{{ $errors->first('type_of_ownership') }}</strong></span>
                    @endif
                </div>
                <div class="input-group {{ $errors->has('iin') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('admin.pages.user.company_name') }} *</label>
                    <input type="text" name="company_name" value="{{ $item->company_name }}"
                           placeholder="{{ __('admin.labels.fill_field',['field' => __('admin.pages.user.company_name')]) }}"
                           class="input-regular" required>
                    @if ($errors->has('iin'))
                        <span class="help-block"><strong>{{ $errors->first('company_name') }}</strong></span>
                    @endif
                </div>
                <div class="input-group {{ $errors->has('company_logo') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('admin.pages.user.company_logo') }}</label>
                    <div class="row">
                        <div class="col-md-2">
                            <img src="{{$item->getAvatar()}}" id="avatar_image" class="file-upload-image"
                                 style="height: 150px">
                        </div>
                        <div class="col-md-10">
                            <input type="hidden" name="company_logo" value="{{$item->getAvatar()}}">
                            <div id="avatar" class="file-upload">
                                <div id="avatar_uploader" class="file">
                                    <div class="progress">
                                        <div class="progress-bar"></div>
                                    </div>
                                    <span class="file__name">
                                    .png, .jpg • 1 MB<br/>
                                    <strong>{{ __('admin.labels.upload_image') }}</strong>
                                </span>
                                </div>
                            </div>
                            @if ($errors->has('company_logo'))
                                <span class="help-block"><strong>{{ $errors->first('company_logo') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="block">
                <h2 class="title-secondary" style="margin-bottom: .3125em;">{{ __('admin.pages.user.role') }} *</h2>
                <div class="input-group {{ $errors->has('role_id') ? ' has-error' : '' }}">
                    <select name="role_id" id="role_id" class="input-regular chosen" data-placeholder=" " required>
                        {{--<option value="" data-permissions="" disabled>-</option>--}}
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}"
                                    @if($item->hasRole($role->slug)) selected @endif>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('role_id'))
                        <span class="help-block"><strong>{{ $errors->first('role_id') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="block">
                <h2 class="title-secondary" style="margin-bottom: .3125em;">{{ __('admin.pages.user.status') }} *</h2>
                <div class="input-group {{ $errors->has('role_id') ? ' has-error' : '' }}">
                    <select name="is_activate" id="is_activate" class="input-regular chosen" data-placeholder="" required>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}"
                                    @if($item->is_activate == $status) selected @endif>{{ __("admin.pages.user.statuses.$status") }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('is_activate'))
                        <span class="help-block"><strong>{{ $errors->first('is_activate') }}</strong></span>
                    @endif
                </div>
                <div class="input-group" id="rejectMessageBlock" style='display:none;'>
                    <label class="input-group__title">Причина отказа*</label>
                    <textarea name="rejectMessage" id="rejectMessage" placeholder="Заполните поле: Причина отказа"
                              class="input-regular"></textarea>
                </div>
            </div>
            <div class="block">
                <div class="buttons">
                    <div>
                        <button type="submit" class="btn btn--green">{{ __('admin.labels.save') }}</button>
                    </div>
                    <div>
                        <a href="/{{$lang}}/admin/user/{{ $item->id }}/passwordUpdate" class="btn btn--blue">
                            {{ __('admin.labels.update_password') }}
                        </a>
                    </div>
                    <div>
                        <a href="/{{$lang}}/admin/user/{{ $item->id }}" class="btn btn--red btn--delete">
                            {{ __('admin.pages.deleting.submit') }}
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @include('admin.v2.partials.modals.form_delete')
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
                    url: "/ajaxUploadImageTest?_token={{ csrf_token() }}",
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

        $(document).ready(function(){
            $('#is_activate').on('change', function() {
                if ( this.value == '2')
                {
                    $("#rejectMessageBlock").show();
                    $("#rejectMessage").attr('required', '');
                }
                else
                {
                    $("#rejectMessageBlock").hide();
                    $("#rejectMessage").removeAttr('required');
                }
            });
        });
    </script>
@endsection
