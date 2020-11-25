<div id="authorization" style="display:none;">
    <h4 class="title-primary text-center">{{__('default.pages.auth.auth_title')}}</h4>
    <div class="row row--multiline">
        <div class="col-sm-6">
            <a href="#authorAuth" data-fancybox title="{{__('default.pages.auth.as_author')}}" class="authorization-option">
                <div class="authorization-option__image">
                    <img src="/assets/img/author.svg" alt="">
                </div>
                <div class="authorization-option__title">{{__('default.pages.auth.as_author')}}</div>
            </a>
        </div>
        <div class="col-sm-6">
            <a href="#studentAuth" data-fancybox title="{{__('default.pages.auth.as_student')}}" class="authorization-option">
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
                <input type="email" name="email" placeholder="" class="input-regular" required>
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
                <a href="#passwordRecovery" data-fancybox title="Забыли пароль?" class="link hint">Забыли пароль?</a>
            </div>
        </div>
{{--        <div class="alert alert-danger">Введен неверный пароль</div>--}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="text-center">
            <div class="form-group">
                <button type="submit" class="btn">{{__('default.pages.auth.auth_title')}}</button>
            </div>
            <div class="hint">
                {{__('default.pages.auth.not_registered_title')}}<br/>
                <a href="#authorRegistration" data-fancybox title="{{__('default.pages.auth.registration_title')}}" class="link">{{__('default.pages.auth.registration_title')}}</a>
            </div>
        </div>
    </form>
</div>

<div id="studentAuth" style="display:none;" class="modal-form">
    <h4 class="title-primary text-center">Войти как обучающийся</h4>
    <form action="">
        <div class="form-group">
            <label class="form-group__label">E-mail</label>
            <div class="input-group">
                <input type="email" name="email" placeholder="" class="input-regular" required>
                <i class="icon-user"> </i>
            </div>
        </div>
        <div class="form-group">
            <label class="form-group__label">Пароль</label>
            <div class="input-group">
                <input type="password" name="password" placeholder="" class="input-regular" required>
                <i class="icon-password"> </i>
            </div>
        </div>
        <div class="alert alert-danger">Введен неверный пароль</div>
        <div class="text-center">
            <div class="form-group">
                <button type="submit" class="btn">Войти</button>
            </div>
            <div class="hint">
                Не зарегистрированы?<br/>
                <a href="#" title="Регистрация" class="link">Регистрация</a>
            </div>
        </div>
    </form>
</div>

<div id="authorRegistration" style="display:none;" class="modal-form">
    <h4 class="title-primary text-center">Регистрация автора</h4>
    <form action="">
        <div class="form-group">
            <label class="form-group__label">Электронная почта *</label>
            <input type="email" name="email" placeholder="" class="input-regular" required>
        </div>
        <div class="form-group">
            <label class="form-group__label">Пароль *</label>
            <input type="password" name="password" placeholder="" class="input-regular" required>
        </div>
        <div class="form-group">
            <label class="form-group__label">Повторите пароль *</label>
            <input type="password" name="passwordConfirm" placeholder="" class="input-regular" required>
        </div>
        <div class="form-group">
            <label class="form-group__label">ИИН/БИН *</label>
            <input type="number" name="iin" placeholder="" class="input-regular" required>
        </div>
        <div class="form-group">
            <label class="form-group__label">Форма собственности *</label>
            <select name="propertyForm" class="selectize-regular no-search" data-placeholder=" ">
                <option value="Форма собственности 1">Форма собственности 1</option>
                <option value="Форма собственности 2">Форма собственности 2</option>
                <option value="Форма собственности 3">Форма собственности 3</option>
                <option value="Форма собственности 4">Форма собственности 4</option>
                <option value="Форма собственности 5">Форма собственности 5</option>
                <option value="Форма собственности 6">Форма собственности 6</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-group__label">Наименование организации *</label>
            <input type="text" name="companyName" placeholder="" class="input-regular" required>
        </div>
        <div class="avatar logo-picture dropzone-avatar" id="companyLogoModal" data-url="https://dev3.panama.kz/ajaxUploadImageTest" data-maxsize="1" data-acceptedfiles="image/*">
            <img src="/assets/img/logo-thumbnail.png" class="logo-picture__preview avatar-preview" alt="">
            <div class="logo-picture__desc dropzone-default">
                <div class="dropzone-default__info">PNG, JPG • макс. 1MB</div>
                <div class="previews-container"></div>
                <div class="logo-picture__link avatar-pick dropzone-default__link">Выбрать фото</div>
            </div>
            <div class="avatar-preview-template" style="display:none;">
                <div class="dz-preview dz-file-preview">
                    <div class="dz-details">
                        <div class="dz-filename"><span data-dz-name></span></div>
                        <div class="dz-size" data-dz-size></div>
                    </div>
                    <div class="alert alert-danger"><span data-dz-errormessage> </span></div>
                    <a href="javascript:undefined;" title="Удалить" class="author-picture__link red" data-dz-remove>Удалить</a>
                </div>
            </div>
            <input type="hidden" name="avatarPath" class="avatar-path">
        </div>
        <div class="alert alert-danger">Введен неверный пароль</div>
        <div class="text-center">
            <div class="form-group">
                <button type="submit" class="btn">Зарегистрироваться</button>
            </div>
            <div class="hint">
                Уже зарегистрированы?<br/>
                <a href="#authorization" data-fancybox title="Авторизация" class="link">Авторизация</a>
            </div>
        </div>
    </form>
</div>

<div id="passwordRecovery" style="display:none;" class="modal-form">
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