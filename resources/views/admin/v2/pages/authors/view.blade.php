@extends('admin.v2.layout.default.template')

@section('title',$item->email.' | '.__('admin.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li><a href="/{{$lang}}/admin/author/index">{{ __('admin.pages.authors.title') }}</a></li>
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
                                <li class="active"><a href="javascript:;" title="Данные об авторе">Данные об авторе</a></li>
                                <li><a href="javascript:;" title="Платежная информация">Платежная информация</a></li>
                                <li><a href="javascript:;" title="Регистрационные данные">Регистрационные данные</a></li>
                                <li><a href="javascript:;" title="Реквизиты">Реквизиты</a></li>

                            </ul>
                        </div>
                    </div>
                    <div class="tabs-contents">
                        <div class="active">
                            <div class="input-group {{ $errors->has('surname') ? ' has-error' : '' }}">
                                <label class="input-group__title">Фамилия</label>
                                <input type="text" name="surname" value="{{ $user_information->surname ?? '' }}"
                                       placeholder=""
                                       class="input-regular" required disabled>
                                @if ($errors->has('surname'))
                                    <span class="help-block"><strong>{{ $errors->first('surname') }}</strong></span>
                                @endif
                            </div>
                            <div class="input-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="input-group__title">Имя</label>
                                <input type="text" name="name" value="{{ $user_information->name ?? '' }}"
                                       placeholder=""
                                       class="input-regular" required disabled>
                                @if ($errors->has('name'))
                                    <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                                @endif
                            </div>
                            <div class="input-group {{ $errors->has('specialization') ? ' has-error' : '' }}">
                                <label class="input-group__title">Специализация</label>
                                <input type="text" name="specialization" value="{{ implode(', ', json_decode($user_information->specialization) ?? [])}}"
                                       placeholder=""
                                       class="input-regular" required disabled>
                                @if ($errors->has('specialization'))
                                    <span class="help-block"><strong>{{ $errors->first('specialization') }}</strong></span>
                                @endif
                            </div>
                            <div class="input-group {{ $errors->has('phone_1') ? ' has-error' : '' }}">
                                <label class="input-group__title">Контактный номер (мобильный)</label>
                                <input type="text" name="phone_1" value="{{ $user_information->phone_1 ?? '' }}"
                                       placeholder=""
                                       class="input-regular" required disabled>
                                @if ($errors->has('phone_1'))
                                    <span class="help-block"><strong>{{ $errors->first('phone_1') }}</strong></span>
                                @endif
                            </div>
                            <div class="input-group {{ $errors->has('phone_2') ? ' has-error' : '' }}">
                                <label class="input-group__title">Контактный номер (городской)</label>
                                <input type="text" name="phone_2" value="{{ $user_information->phone_2 ?? '' }}"
                                       placeholder=""
                                       class="input-regular" required disabled>
                                @if ($errors->has('phone_2'))
                                    <span class="help-block"><strong>{{ $errors->first('phone_2') }}</strong></span>
                                @endif
                            </div>
                            <div class="input-group {{ $errors->has('site_url') ? ' has-error' : '' }}">
                                <label class="input-group__title">Адрес сайта</label>
                                <input type="text" name="site_url" value="{{ $user_information->site_url ?? '' }}"
                                       placeholder=""
                                       class="input-regular" required disabled>
                                @if ($errors->has('site_url'))
                                    <span class="help-block"><strong>{{ $errors->first('site_url') }}</strong></span>
                                @endif
                            </div>
                            <div class="input-group {{ $errors->has('vk_link') ? ' has-error' : '' }}">
                                <label class="input-group__title">VK</label>
                                <input type="text" name="vk_link" value="{{ $user_information->vk_link ?? '' }}"
                                       placeholder=""
                                       class="input-regular" required disabled>
                                @if ($errors->has('vk_link'))
                                    <span class="help-block"><strong>{{ $errors->first('vk_link') }}</strong></span>
                                @endif
                            </div>
                            <div class="input-group {{ $errors->has('fb_link') ? ' has-error' : '' }}">
                                <label class="input-group__title">Facebook</label>
                                <input type="text" name="fb_link" value="{{ $user_information->fb_link ?? '' }}"
                                       placeholder=""
                                       class="input-regular" required disabled>
                                @if ($errors->has('fb_link'))
                                    <span class="help-block"><strong>{{ $errors->first('fb_link') }}</strong></span>
                                @endif
                            </div>
                            <div class="input-group {{ $errors->has('instagram_link') ? ' has-error' : '' }}">
                                <label class="input-group__title">Instagram</label>
                                <input type="text" name="instagram_link" value="{{ $user_information->instagram_link ?? '' }}"
                                       placeholder=""
                                       class="input-regular" required disabled>
                                @if ($errors->has('instagram_link'))
                                    <span class="help-block"><strong>{{ $errors->first('instagram_link') }}</strong></span>
                                @endif
                            </div>
                            <div class="input-group {{ $errors->has('avatar') ? ' has-error' : '' }}">
                                <label class="input-group__title">Фото автора</label>
                                <div class="row">
                                    <div class="col-md-2">
                                        <img src="{{$user_information->avatar ?? ''}}" id="avatar" class="file-upload-image"
                                             style="height: 150px">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="input-group {{ $errors->has('merchant_certificate_id') ? ' has-error' : '' }}">
                                <label class="input-group__title">ID сертификата продавца</label>
                                <input type="text" name="merchant_certificate_id" value="{{ $pay_information->merchant_login ?? '' }}"
                                       placeholder=""
                                       class="input-regular" required disabled>
                                @if ($errors->has('merchant_certificate_id'))
                                    <span class="help-block"><strong>{{ $errors->first('merchant_certificate_id') }}</strong></span>
                                @endif
                            </div>
                        </div>
                        <div>
                            {{--<h2 class="title-secondary">{{ __('admin.pages.user.title') }}</h2>--}}
                            {{--<div class="input-group {{ $errors->has('full_name') ? ' has-error' : '' }}">--}}
                                {{--<label class="input-group__title">{{ __('admin.pages.user.full_name') }} *</label>--}}
                                {{--<input type="text" name="name" value="{{ $item->name }}"--}}
                                       {{--placeholder="{{ __('admin.labels.fill_field',['field' => __('admin.pages.user.full_name')]) }}"--}}
                                       {{--class="input-regular" required disabled>--}}
                                {{--@if ($errors->has('full_name'))--}}
                                    {{--<span class="help-block"><strong>{{ $errors->first('full_name') }}</strong></span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                            <div class="input-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="input-group__title">{{ __('admin.pages.user.email') }} *</label>
                                <input type="email" name="email" value="{{ $item->email }}"
                                       placeholder="{{ __('admin.labels.fill_field',['field' => __('admin.pages.user.email')]) }}"
                                       class="input-regular" required disabled>
                                @if ($errors->has('email'))
                                    <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                @endif
                            </div>
                            <div class="input-group {{ $errors->has('iin') ? ' has-error' : '' }}">
                                <label class="input-group__title">{{ __('admin.pages.user.iin_bin') }} *</label>
                                <input type="text" name="iin" value="{{ $item->iin }}"
                                       placeholder="{{ __('admin.labels.fill_field',['field' => __('admin.pages.user.iin_bin')]) }}"
                                       class="input-regular" required disabled>
                                @if ($errors->has('iin'))
                                    <span class="help-block"><strong>{{ $errors->first('iin') }}</strong></span>
                                @endif
                            </div>
                            <div class="input-group {{ $errors->has('type_of_ownership') ? ' has-error' : '' }}">
                                <label class="input-group__title">{{ __('admin.pages.user.type_of_ownership') }}
                                    *</label>
                                <select name="type_of_ownership" id="type_of_ownership" class="input-regular chosen"
                                        data-placeholder=" " required disabled>
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
                                       class="input-regular" required disabled>
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
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="input-group">
                                <label class="input-group__title">{{__('default.pages.auth.type_of_ownership')}} *</label>
                                <input disabled type="text" class="input-regular" value="{{ Auth::user()->type_ownership->name_ru ?? '' }}">
                            </div>

                            <div class="input-group">
                                <label class="input-group__title">{{__('default.pages.auth.company_name')}} *</label>
                                <input disabled type="text" name="company_name" placeholder="" class="input-regular" required value="{{ old('company_name') ?? Auth::user()->company_name }}">
                            </div>

                            <div class="form-group">
                                <label class="input-group__title">{{ __('default.pages.profile.address') }} (ru) *</label>
                                <input disabled type="text" name="legal_address_ru" placeholder="" class="input-regular" required value="{{ old('legal_address_ru') ?? Auth::user()->legal_address_ru }}">
                            </div>

                            <div class="form-group">
                                <label class="input-group__title">{{ __('default.pages.profile.address') }} (kz) *</label>
                                <input disabled type="text" name="legal_address_kk" placeholder="" class="input-regular" required value="{{ old('legal_address_kk') ?? Auth::user()->legal_address_kk }}">
                            </div>

                            <div class="form-group">
                                <label class="input-group__title">{{__('default.pages.profile.position')}} (ru) *</label>
                                <input disabled type="text" name="position_ru" placeholder="" class="input-regular" required value="{{ old('position_ru') ?? Auth::user()->position_ru }}">
                            </div>

                            <div class="form-group">
                                <label class="input-group__title">{{__('default.pages.profile.position')}} (kz) *</label>
                                <input disabled type="text" name="position_kk" placeholder="" class="input-regular" required value="{{ old('position_kk') ?? Auth::user()->position_kk }}">
                            </div>

                            <div class="form-group">
                                <label class="input-group__title">{{__('default.pages.profile.fio_director')}} *</label>
                                <input disabled type="text" name="fio_director" placeholder="" class="input-regular" required value="{{ old('fio_director') ?? Auth::user()->fio_director }}">
                            </div>

                            <div class="form-group">
                                <label class="input-group__title">{{__('default.pages.profile.base')}} *</label>
                                <input disabled type="text" class="input-regular" value="{{ Auth::user()->base->name_ru ?? '' }}">
                            </div>

                            <div class="form-group">
                                <a href="{{ asset(Auth::user()->base_file) }}" target="_blank" class="btn">{{__('default.download_file') }}</a>
                            </div>

                            <div class="form-group">
                                <label class="input-group__title">{{__('default.pages.auth.iin')}} *</label>
                                <input disabled type="text" name="iin" placeholder="" onfocus="$(this).inputmask('999999999999')" class="input-regular" required value="{{ old('iin') ?? Auth::user()->iin }}">
                            </div>

                            <div class="form-group">
                                <label class="input-group__title">{{__('default.pages.profile.iik_kz')}} *</label>
                                <input disabled type="text" name="iik_kz" placeholder="" onfocus="$(this).inputmask('KZ 999999999999999999')" class="input-regular" required value="{{ old('iik_kz') ?? Auth::user()->iik_kz }}">
                            </div>

                            <div class="form-group">
                                <label class="input-group__title">{{__('default.pages.profile.kbe')}} *</label>
                                <input disabled type="text" name="kbe" placeholder="" onfocus="$(this).inputmask('99')" class="input-regular" required value="{{ old('kbe') ?? Auth::user()->kbe }}">
                            </div>

                            <div class="form-group">
                                <label class="input-group__title">{{__('default.pages.profile.bik')}} *</label>
                                <input disabled type="text" name="bik" placeholder="" onfocus="$(this).inputmask('9|A{1,100}')" class="input-regular" required value="{{ old('bik') ?? Auth::user()->bik }}">
                            </div>

                            <div class="form-group">
                                <label class="input-group__title">{{__('default.pages.profile.bank_name')}} *</label>
                                <input disabled type="text" class="input-regular" value="{{ Auth::user()->bank->name_ru ?? '' }}">
                            </div>
                        </div>
                    </div>
{{--                    @if($item->is_activate == 0)--}}
{{--                        <div class="block">--}}
{{--                            <div class="buttons">--}}
{{--                                <div>--}}
{{--                                    <button type="submit" name="action" value="activate"--}}
{{--                                            class="btn btn--green">{{ __('admin.pages.authors.activate_button') }}</button>--}}
{{--                                </div>--}}
{{--                                <div>--}}
{{--                                    --}}{{--<button type="submit" name="action" value="reject" class="btn btn--red btn--delete">{{ __('admin.pages.authors.reject_button') }}</button>--}}
{{--                                    <a href="/{{$lang}}/admin/author/{{ $item->id }}"--}}
{{--                                       title="{{ __('admin.pages.authors.reject_button') }}"--}}
{{--                                       class="btn btn--red btn--delete"--}}
{{--                                       id="rejectBtn">{{ __('admin.pages.authors.reject_button') }}</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    @endif--}}
                    <div id="dialog-confirm-reject" class="modal" style="display: none;">
                        <h4 class="title-secondary">{{ __('admin.pages.reject.title') }}</h4>
                        <hr>
                        <div class="input-group" id="rejectMessageBlock">
                            <label class="input-group__title">{{ __('admin.pages.reject.hint') }}</label>
                            <textarea form="author_form" name="rejectMessage" id="rejectMessage" placeholder=""
                                      class="input-regular"></textarea>
                        </div>
                        <div class="buttons justify-end">
                            <div>
                                <button type="submit" form="author_form" id="send_reject" name="action" value="reject"
                                        class="btn btn--red btn--delete">{{ __('admin.pages.authors.reject_button') }}</button>
                                {{--<button type="submit" name="action" value="reject" class="btn btn--red">{{ __('admin.pages.deleting.submit') }}</button>--}}
                            </div>
                            <div>
                                <button class="btn" data-fancybox-close>{{ __('admin.labels.cancel') }}</button>
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
