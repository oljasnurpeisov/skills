<footer class="footer">
    <div class="container">
        <div class="row row--multiline">
            <div class="col-md-5">
                <h4 class="footer__title">{{__('default.pages.footer.main_links')}}</h4>
                <ul class="site-map">
                    <li><a href="/{{$lang}}" title="{{__('default.main_title')}}">{{__('default.main_title')}}</a></li>
                    <li><a href="https://www.enbek.kz/" title="{{__('default.pages.footer.enbek_kz')}}" target="_blank">{{__('default.pages.footer.enbek_kz')}}</a></li>
                    <li><a href="#" title="{{__('default.pages.footer.teacher_title')}}">{{__('default.pages.footer.teacher_title')}}</a></li>
                    <li><a href="#" title="{{__('default.pages.footer.about_us')}}">{{__('default.pages.footer.about_us')}}</a></li>
                    <li><a href="#" title="{{__('default.pages.footer.faq')}}">{{__('default.pages.footer.faq')}}</a></li>
                    <li><a href="#" title="{{__('default.pages.footer.feedback')}}">{{__('default.pages.footer.feedback')}}</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h4 class="footer__title">{{__('default.pages.footer.contacts')}}</h4>
                <div class="footer__contacts">
                    <p><span>{{__('default.pages.footer.address_title')}}:</span> {{__('default.pages.footer.address')}}</p>
                    <p><span>{{__('default.pages.footer.email_title')}}:</span> <a href="mailto:{{__('default.pages.footer.email')}}" title="{{__('default.pages.footer.email')}}">{{__('default.pages.footer.email')}}</a></p>
                    <p><span>{{__('default.pages.footer.phone_title')}}:</span> <a href="tel:{{__('default.pages.footer.phone')}}" title="{{__('default.pages.footer.phone')}}">{{__('default.pages.footer.phone')}}</a></p>
                </div>
            </div>
            <div class="col-md-3">
                <h4 class="footer__title">{{__('default.pages.footer.social_networks')}}</h4>
                <ul class="socials">
                    <li><a href="#" title="" class="icon-instagram"> </a></li>
                    <li><a href="#" title="" class="icon-twitter"> </a></li>
                    <li><a href="#" title="" class="icon-facebook"> </a></li>
                    <li><a href="#" title="" class="icon-vk"> </a></li>
                </ul>
                <div class="apps-links">
                    <a href="#" target="_blank" title="Перейти"><img src="/assets/img/appstore.png" alt=""></a>
                    <a href="#" target="_blank" title="Перейти"><img src="/assets/img/playmarket.png" alt=""></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<div id="authorization" style="display:none;">
    <h4 class="title-primary text-center">Войти</h4>
    <div class="row row--multiline">
        <div class="col-sm-6">
            <a href="#authorAuth" data-fancybox title="Как автор" class="authorization-option">
                <div class="authorization-option__image">
                    <img src="/assets/img/author.svg" alt="">
                </div>
                <div class="authorization-option__title">Как автор</div>
            </a>
        </div>
        <div class="col-sm-6">
            <a href="#studentAuth" data-fancybox title="Как автор" class="authorization-option">
                <span class="authorization-option__image">
                    <img src="/assets/img/student.svg" alt="">
                </span>
                <div class="authorization-option__title">Как обучающийся</div>
            </a>
        </div>
    </div>
</div>

<div id="authorAuth" style="display:none;" class="modal-form">
    <h4 class="title-primary text-center">Войти как автор</h4>
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
            <div class="text-right">
                <a href="#passwordRecovery" data-fancybox title="Забыли пароль?" class="link hint">Забыли пароль?</a>
            </div>
        </div>
        <div class="alert alert-danger">Введен неверный пароль</div>
        <div class="text-center">
            <div class="form-group">
                <button type="submit" class="btn">Войти</button>
            </div>
            <div class="hint">
                Не зарегистрированы?<br/>
                <a href="#authorRegistration" data-fancybox title="Регистрация" class="link">Регистрация</a>
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