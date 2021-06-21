@guest
    <div id="authorization" style="display:none;">
        <h4 class="title-primary text-center">{{__('default.pages.auth.auth_title')}}</h4>
        <div class="row row--multiline">
            <div class="col-sm-6">
                <a href="#authorAuth" data-fancybox title="{{__('default.pages.auth.as_author')}}"
                   class="authorization-option">
                    <div class="authorization-option__image">
                        <img src="/assets/img/author.svg" alt="">
                    </div>
                    <div class="authorization-option__title">{{__('default.pages.auth.as_author')}}</div>
                </a>
            </div>
            <div class="col-sm-6">
                <a href="https://passport.enbek.kz/ru/user/login?redirect_uri={{ route('auth_sso') }}&redirect=strict"  title="{{__('default.pages.auth.as_student')}}"
                   class="authorization-option">
                <span class="authorization-option__image">
                    <img src="/assets/img/student.svg" alt="">
                </span>
                    <div class="authorization-option__title">{{__('default.pages.auth.as_student')}}</div>
                </a>
            </div>
        </div>
    </div>

    <div id="authorAuth" style="display:none;" class="modal-form">
        <h4 class="title-primary text-center">{{__('default.pages.auth.auth_as_author')}}</h4>
        <form action="/{{$lang}}/login" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-group__label">E-mail</label>
                <div class="input-group">
                    <input type="email" name="email" placeholder="" class="input-regular" value="{{ old('email') }}"
                           required>
                    <i class="icon-user"> </i>
                </div>

            </div>
            <div class="form-group">
                <label class="form-group__label">{{__('default.pages.auth.password_title')}}</label>
                <div class="input-group">
                    <input type="password" name="password" placeholder="" class="input-regular" required>
                    <i class="icon-password"> </i>
                </div>
                <div class="text-right">
                    <a href="#passwordRecovery" data-fancybox title="{{__('default.pages.auth.forgot_password')}}"
                       class="link hint">{{__('default.pages.auth.forgot_password')}}</a>
                </div>
            </div>
            {!! $errors->first('email', '<div class="alert alert-danger">
                    :message
                </div>') !!}
            @if (session('recovery_pass'))
                <div class="alert alert-success">
                    {{ session('recovery_pass') }}
                </div>
            @endif
            <div class="text-center">
                <div class="form-group">
                    <button type="submit" class="btn">{{__('default.pages.auth.auth_title')}}</button>
                </div>
                <div class="hint">
                    {{__('default.pages.auth.not_registered_title')}}<br/>
                    <a href="#authorRegistration" data-fancybox title="{{__('default.pages.auth.registration_title')}}"
                       class="link">{{__('default.pages.auth.registration_title')}}</a>
                </div>
            </div>
        </form>
    </div>

{{--    <div id="studentAuth" style="display:none;" class="modal-form">--}}
{{--        <h4 class="title-primary text-center">{{__('default.pages.auth.auth_as_student')}}</h4>--}}
{{--        <form action="/{{$lang}}/login_student" method="POST">--}}
{{--            @csrf--}}
{{--            <div class="form-group">--}}
{{--                <label class="form-group__label">E-mail</label>--}}
{{--                <div class="input-group">--}}
{{--                    <input type="email" name="email" placeholder="" class="input-regular" value="{{ old('email') }}"--}}
{{--                           required>--}}
{{--                    <i class="icon-user"> </i>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="form-group">--}}
{{--                <label class="form-group__label">{{__('default.pages.auth.password_title')}}</label>--}}
{{--                <div class="input-group">--}}
{{--                    <input type="password" name="password" placeholder="" class="input-regular" required>--}}
{{--                    <i class="icon-password"> </i>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="form-group">--}}
{{--                <label class="checkbox small"><input type="checkbox" name="agree" id="agreeCheckbox"--}}
{{--                                                     data-enable="#studentLoginBtn" value="on"--}}
{{--                                                     {{old('agree') == 'on' ? 'checked' : ''}} required> <span--}}
{{--                        style="font-family: sans-serif">{!! __('default.pages.auth.private_policy_agree_title') !!}</span></label>--}}
{{--            </div>--}}
{{--            @if(Session::get('failed'))--}}

{{--                <div class="alert alert-danger">--}}
{{--                    {{Session::get('failed')}}--}}
{{--                </div>--}}
{{--            @endif--}}

{{--            <div class="text-center">--}}
{{--                <div class="form-group">--}}
{{--                    <button type="submit" class="btn"--}}
{{--                            id="studentLoginBtn" {{old('agree') != 'on' ? 'disabled' : ''}}>{{__('default.pages.auth.auth_title')}}</button>--}}
{{--                </div>--}}
{{--                <div class="hint">--}}
{{--                    <a href="https://passport.enbek.kz/ru/user/login?redirect_uri={{ route('auth_sso') }}&redirect=strict" title=""--}}
{{--                       class="">passport.enbek.kz</a>--}}
{{--                </div>--}}
{{--                <div class="hint">--}}
{{--                    {{__('default.pages.auth.not_registered_title')}}<br/>--}}
{{--                    <a href="https://www.enbek.kz/ru/register" title="{{__('default.pages.auth.registration_title')}}"--}}
{{--                       class="link" target="_blank">{{__('default.pages.auth.registration_title')}}</a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </form>--}}
{{--    </div>--}}

    <div id="authorRegistration" style="display:none;" class="modal-form">
        <h4 class="title-primary text-center">{{__('default.pages.auth.author_register')}}</h4>
        <form action="/{{$lang}}/register" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-group__label">{{__('default.pages.auth.email_title')}} *</label>
                <input type="email" name="email_register" placeholder="" class="input-regular"
                       value="{{ old('email_register') }}" required>

                {!! $errors->first('email_register', '<div class="alert alert-danger">
                    :message
                </div>') !!}
            </div>

            <div class="form-group">
                <label class="form-group__label">{{__('default.pages.auth.password_title')}} *</label>
                <input type="password" name="password_register" placeholder="" class="input-regular" required>

                {!! $errors->first('password_register', '<div class="alert alert-danger">
                    :message
                </div>') !!}
            </div>
            <div class="form-group">
                <label class="form-group__label">{{__('default.pages.auth.confirm_password_title')}} *</label>
                <input type="password" name="password_register_confirmation" placeholder="" class="input-regular"
                       required>

                {!! $errors->first('password_register_confirmation', '<div class="alert alert-danger">
                    :message
                </div>') !!}
            </div>
            <div class="form-group">
                <label class="form-group__label">{{__('default.pages.auth.iin')}} *</label>
                <input type="number" name="iin" placeholder="" class="input-regular" value="{{ old('iin') }}" required>

                {!! $errors->first('iin', '<div class="alert alert-danger">
                    :message
                </div>') !!}
            </div>
            <div class="form-group">
                <label class="form-group__label">{{__('default.pages.auth.type_of_ownership')}} *</label>
                <select name="type_of_ownership" class="selectize-regular no-search" data-placeholder=" ">
                    @php($types_of_ownership = \App\Models\Type_of_ownership::all())
                    @foreach($types_of_ownership as $type)
                        <option
                            value="{{ $type->id }}">{{ $type->getAttribute('name_'.$lang) ??  $type->getAttribute('name_ru')}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-group__label">{{__('default.pages.auth.company_name')}} *</label>
                <input type="text" name="company_name" placeholder="" class="input-regular"
                       value="{{ old('company_name') }}" required>

                {!! $errors->first('company_name', '<div class="alert alert-danger">
                    :message
                </div>') !!}
            </div>
            <div class="avatar logo-picture dropzone-avatar" id="companyLogoModal"
                 data-url="/ajax_upload_company_image?_token={{ csrf_token() }}" data-maxsize="1"
                 data-acceptedfiles="image/*">
                <img src="/assets/img/logo-thumbnail.png" class="logo-picture__preview avatar-preview" alt="">
                <div class="logo-picture__desc dropzone-default">
                    <div class="dropzone-default__info">PNG, JPG • {{__('default.pages.courses.max_file_title')}}1MB
                    </div>
                    <div class="previews-container"></div>
                    <div
                        class="logo-picture__link avatar-pick dropzone-default__link">{{__('default.pages.courses.choose_photo')}}</div>
                </div>
                <div class="avatar-preview-template" style="display:none;">
                    <div class="dz-preview dz-file-preview">
                        <div class="dz-details">
                            <div class="dz-filename"><span data-dz-name></span></div>
                            <div class="dz-size" data-dz-size></div>
                        </div>
                        <div class="alert alert-danger"><span data-dz-errormessage> </span></div>
                        <a href="javascript:undefined;" title="{{__('default.pages.courses.delete')}}"
                           class="author-picture__link red" data-dz-remove>{{__('default.pages.courses.delete')}}</a>
                    </div>
                </div>
                <input type="hidden" name="company_logo" class="avatar-path">
            </div>
            {!! $errors->first('company_logo', '<div class="alert alert-danger">
                :message
            </div>') !!}
            <br>
            <div class="text-center">
                <div class="form-group">
                    <button type="submit" class="btn">{{__('default.pages.auth.register_submit')}}</button>
                </div>
                <div class="hint">
                    {{__('default.pages.auth.is_registered_title')}}<br/>
                    <a href="#authorization" data-fancybox title="{{__('default.pages.auth.authorization_title')}}"
                       class="link">{{__('default.pages.auth.authorization_title')}}</a>
                </div>
            </div>
        </form>
    </div>

    <div id="passwordRecovery" style="display:none;" class="modal-form">
        <h4 class="title-primary text-center">{{__('default.pages.auth.recovery_password')}}</h4>
        <form action="/{{$lang}}/forgot_password" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-group__label">E-mail</label>
                <input type="email" name="email_forgot_password" placeholder="" class="input-regular"
                       value="{{ old('email_forgot_password') }}" required>
            </div>
            {!! $errors->first('email_forgot_password', '<div class="alert alert-danger">
                    :message
                </div>') !!}
            <div class="text-center">
                <button type="submit" class="btn">{{__('default.pages.auth.send_button_title')}}</button>
            </div>
        </form>
    </div>

    <div id="message" style="display:none;">
        <h4 class="title-primary text-center">Восстановление пароля</h4>
        <form action="">
            <div class="form-group">
                <label class="form-group__label">E-mail</label>
                <input type="email" name="email" placeholder="" class="input-regular" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn">Отправить</button>
            </div>
        </form>
    </div>

    <div id="noCvModal" style="display:none;" class="modal-form">
        <h4 class="title-primary text-center">{{__('default.pages.auth.create_resume_title')}}</h4>
        <div class="plain-text text-center">
            {!! __('default.pages.auth.create_resume_description') !!}

        </div>
        <form action="/{{$lang}}/save-student-data/{{session('resume_data')}}" method="post">
            @csrf
            <div class="form-group">
                <label class="form-group__label">{{__('default.pages.auth.fio_title')}}</label>
                <input type="text" name="resume_name" placeholder="" value="{{ old('resume_name') }}"
                       class="input-regular" required>
                {!! $errors->first('resume_name', '<div class="alert alert-danger">
                    :message
                </div>') !!}
            </div>
            <div class="form-group">
                <label class="form-group__label">{{__('default.pages.auth.iin_resume_title')}}</label>
                <input type="text" name="resume_iin" placeholder="" class="input-regular"
                       onfocus="$(this).inputmask('999999999999')" value="{{ old('resume_iin') }}" required>
                {!! $errors->first('resume_iin', '<div class="alert alert-danger">
                    :message
                </div>') !!}
            </div>
            <div class="form-group">
                <label class="checkbox small">
                    <input type="checkbox" name="agree" id="agreeCheckbox" data-enable="#noCvModal" value="on" {{old('agree') == 'on' ? 'checked' : ''}} required>
                    <span style="font-family: sans-serif">{!! __('default.pages.auth.private_policy_agree_title') !!}</span>
                </label>
            </div>
            <div class="text-center">
                <div class="form-group">
                    <button type="submit" class="btn">{{__('default.pages.auth.send_resume')}}</button>
                </div>
            </div>
        </form>
    </div>
    <div id="privacyPolicy" style="display: none;">
        <h2 class="title-primary">{{__('default.pages.private_policy.private_policy_title')}}</h2>
        <h3 class="plain-text">{{__('default.pages.private_policy.private_policy_teaser')}}</h3>
        <div class="plain-text">
            {!! __('default.pages.private_policy.private_policy_description') !!}
        </div>
        <div class="form-group">
            <a href="#noCvModal" data-fancybox title="{{__('default.pages.private_policy.agree_title')}}" class="btn"
               id="privacyPolicyBtn"
               onclick="document.querySelector('#agreeCheckbox').checked = true;document.querySelector('#agreeCheckbox').dispatchEvent(new Event('change'));">{{__('default.pages.private_policy.agree_title')}}</a>
        </div>
    </div>
@endguest
