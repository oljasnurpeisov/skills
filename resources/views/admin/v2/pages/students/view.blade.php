@extends('admin.v2.layout.default.template')

@section('title',$item->email.' | '.__('admin.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li><a href="/{{$lang}}/admin/student/index">{{ __('admin.pages.students.title') }}</a></li>
            <li class="active">{{ $item->email }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @if(session('newPassword'))
            <div class="alert alert-warning" role="alert">
                <strong>{{ __('admin.notifications.new_password',['password' => session('newPassword')]) }}</strong>
            </div>
        @endif
        @include('admin.v2.partials.components.errors')
        <form id="author_form" action="/{{$lang}}/admin/author/{{ $item->id }}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="block">
                <div class="tabs">
                    <div class="mobile-dropdown">
                        <div class="mobile-dropdown__desc">
                            <ul class="tabs-titles">
                                <li class="active"><a href="javascript:;" title="Данные об обучающемся">Данные об обучающемся</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="tabs-contents">
                        <div class="active">
                            <div class="input-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="input-group__title">ФИО</label>
                                <input type="text" name="name" value="{{ $user_information->name ?? '' }}"
                                       placeholder=""
                                       class="input-regular" required disabled>
                                @if ($errors->has('name'))
                                    <span class="help-block"><strong>{{ $errors->first('surname') }}</strong></span>
                                @endif
                            </div>
                            <div class="input-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="input-group__title">E-mail</label>
                                <input type="email" name="email" value="{{ $item->email ?? '' }}"
                                       placeholder=""
                                       class="input-regular" required disabled>
                                @if ($errors->has('email'))
                                    <span class="help-block"><strong>{{ $errors->first('surname') }}</strong></span>
                                @endif
                            </div>
                            <div class="input-group">
                                <label class="input-group__title">ИИН</label>
                                <input type="email" name="email" value="{{ $user_information->iin ?? '' }}"
                                       placeholder=""
                                       class="input-regular" required disabled>
                            </div>
                            <div class="input-group">
                                <label class="input-group__title">{{__('default.pages.auth.area_title')}}</label>
                                <input type="email" name="email" value="{{ $regionCaption ?? '' }}"
                                       placeholder=""
                                       class="input-regular" required disabled>
                            </div>
                            <div class="input-group">
                                <label class="input-group__title">{{__('default.pages.auth.locality_title')}}</label>
                                <input type="email" name="email" value="{{ $localityCaption ?? '' }}"
                                       placeholder=""
                                       class="input-regular" required disabled>
                            </div>
                        </div>
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

        $(document).ready(function () {
            $('#is_activate').on('change', function () {
                if (this.value == '2') {
                    $("#rejectMessageBlock").show();
                    $("#rejectMessage").attr('required', '');
                }
                else {
                    $("#rejectMessageBlock").hide();
                    $("#rejectMessage").removeAttr('required');
                }
            });
        });

        $("#rejectBtn").click(function (e) {
            e.preventDefault();
            var link = $(this).attr("href");
            if (link !== undefined) {
                $("#author_form").attr("action", link);

                $.fancybox.open({
                    src: "#dialog-confirm-reject",
                    touch: false
                });
            }
        });

        function form_submit() {
            document.getElementById("author_form").submit();
        }

        // $('#send_reject').click(function(){
        //     $.fancybox.close();
        // });
    </script>
@endsection
