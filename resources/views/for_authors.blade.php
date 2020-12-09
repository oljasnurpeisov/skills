@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="opening opening--author">
            <div class="container">
                <div class="text-center">
                    <h1>Меняйте мир к лучшему</h1>
                    <h2>Публикуйте курсы онлайн и зарабатывайте деньги, обучая людей по всему миру</h2>

                    @auth
                        @if(Auth::user()->hasRole('author'))
                            <a href="/{{$lang}}/profile-author-information" title="Стать автором" class="btn">Стать
                                автором</a>
                        @else
                            <a href="#authorRegistration" data-fancybox="" title="Стать автором" class="btn">Стать
                                автором</a>
                        @endif
                    @endauth
                    @guest
                        <a href="#authorRegistration" data-fancybox="" title="Стать автором" class="btn">Стать
                            автором</a>
                    @endguest

                </div>
            </div>
        </section>

        <section class="plain big-padding">
            <div class="container">
                <h2 class="title-primary decorated"><span>Наши</span><br/> показатели</h2>
                <div class="row row--multiline">
                    <div class="col-sm-4">
                        <div class="stat">
                            <img src="/assets/img/students.svg" alt="" class="stat__image">
                            <div>
                                <div class="stat__number">3000</div>
                                <div class="stat__label">обучающихся</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="stat">
                            <img src="/assets/img/authors.svg" alt="" class="stat__image">
                            <div>
                                <div class="stat__number">300</div>
                                <div class="stat__label">авторов</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="stat">
                            <img src="/assets/img/courses.svg" alt="" class="stat__image">
                            <div>
                                <div class="stat__number">600</div>
                                <div class="stat__label">курсов</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="gray">
            <div class="container">
                <h3 class="title-primary decorated"><span>Преимущества</span><br/> Раскройте свой потенциал</h3>
                <div class="row row--multiline">
                    <div class="col-sm-6 col-md-4">
                        <div class="privilege">
                            <div class="privilege__img">
                                <img src="/assets/img/earnings.svg" alt="">
                            </div>
                            <h4 class="privilege__title">Зарабатывайте</h4>
                            <div class="privilege__text">
                                Получайте деньги сразу при покупке Платного курса обучающимся и получайте оплату раз в
                                месяц за доступные Курсы по Квоте
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="privilege">
                            <div class="privilege__img">
                                <img src="/assets/img/reading-book.svg" alt="">
                            </div>
                            <h4 class="privilege__title">Вдохновляйте студентов</h4>
                            <div class="privilege__text">
                                Помогайте людям овладеть новыми навыками и продвинуться в карьере
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="privilege">
                            <div class="privilege__img">
                                <img src="/assets/img/books.svg" alt="">
                            </div>
                            <h4 class="privilege__title">Делитесь знаниями</h4>
                            <div class="privilege__text">
                                Создавайте и публикуйте свои курсы, поделитесь своими знаниями со всеми
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="text-center">
                    @auth
                        @if(Auth::user()->hasRole('author'))
                            <a href="/{{$lang}}/profile-author-information" title="Стать автором"
                               class="btn">Стать
                                автором</a>
                        @else
                            <a href="#authorRegistration" data-fancybox="" title="Стать автором" class="btn">Стать
                                автором</a>
                        @endif
                    @endauth
                    @guest
                        <a href="#authorRegistration" data-fancybox="" title="Стать автором" class="btn">Стать
                            автором</a>
                    @endguest
                </div>
            </div>
        </section>

        <section class="plain big-padding">
            <div class="container">
                <h2 class="title-primary decorated"><span>Шаг за</span><br/> шагом</h2>
                <div class="row row--multiline">
                    <div class="col-md-3 col-sm-6">
                        <div class="step">
                            <div class="step__number">1</div>
                            <div>
                                <h4 class="step__title">Зарегистрируйтесь</h4>
                                <div class="step__text">Quis convallis laoreet porta eget mauris non lorem etiam integer
                                    justo phasellus senectus pellentesque
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="step">
                            <div class="step__number">2</div>
                            <div>
                                <h4 class="step__title">Создайте курс</h4>
                                <div class="step__text">Mi urna pellentesque commodo nisl quis consequat, volutpat nulla
                                    tristique fames lectus nunc nam
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="step">
                            <div class="step__number">3</div>
                            <div>
                                <h4 class="step__title">Пройдите модерацию</h4>
                                <div class="step__text">Tellus aliquam, velit orci, sit aliquam tellus sed enim sit at
                                    mauris eu, nam
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="step">
                            <div class="step__number">4</div>
                            <div>
                                <h4 class="step__title">Начните зарабатывать</h4>
                                <div class="step__text">Quis convallis laoreet porta eget mauris non lorem etiam integer
                                    justo phasellus senectus pellentesque
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="blue">
            <div class="container">
                <div class="row row--multiline align-items-center">
                    <div class="col-sm-6">
                        <h2 class="title-primary decorated"><span>Станьте</span><br/> автором курсов</h2>
                        <div class="plain-text">
                            {{__('default.pages.index.to_be_author_description')}}
                        </div>
                        @auth
                            @if(Auth::user()->hasRole('author'))
                                <a href="/{{$lang}}/profile-author-information" title="Стать автором"
                                   class="ghost-btn ghost-btn--white">Стать автором</a>
                            @else
                                <a href="#authorRegistration" data-fancybox="" title="Стать автором"
                                   class="ghost-btn ghost-btn--white">Стать автором</a>
                            @endif
                        @endauth
                        @guest
                            <a href="#authorRegistration" data-fancybox="" title="Стать автором"
                               class="ghost-btn ghost-btn--white">Стать автором</a>
                        @endguest
                    </div>
                    <div class="col-sm-6">
                        <img src="/assets/img/authors-banner.svg" alt="">
                    </div>
                </div>
            </div>
        </section>

    </main>

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
                        <option value="{{ $type->id }}">{{ $type->getAttribute('name_'.$lang) ??  $type->getAttribute('name_ru')}}</option>
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
                    <div class="logo-picture__link avatar-pick dropzone-default__link">{{__('default.pages.courses.choose_photo')}}</div>
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
@endsection

@section('scripts')

@endsection

