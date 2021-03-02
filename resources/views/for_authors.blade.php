@extends('app.layout.default.template')

@section('content')
    <main class="main">


        <section class="opening opening--author"
                 style="background: url({{json_decode($content->getAttribute('data_'.$lang))->for_authors_banner->image}})">
            <div class="container">
                <div class="text-center">
                    <h1>{{json_decode($content->getAttribute('data_'.$lang))->for_authors_banner->title}}</h1>
                    <h2>{{json_decode($content->getAttribute('data_'.$lang))->for_authors_banner->teaser}}</h2>

                    @auth
                        @if(Auth::user()->hasRole('author'))
                            <a href="/{{$lang}}/profile-author-information"
                               title="{{__('default.pages.footer.to_be_author')}}"
                               class="btn">{{__('default.pages.footer.to_be_author')}}</a>
                        @else
                            <a href="#authorRegistration1" data-fancybox=""
                               title="{{__('default.pages.footer.to_be_author')}}"
                               class="btn">{{__('default.pages.footer.to_be_author')}}</a>
                        @endif
                    @endauth
                    @guest
                        <a href="#authorRegistration" data-fancybox=""
                           title="{{__('default.pages.footer.to_be_author')}}"
                           class="btn">{{__('default.pages.footer.to_be_author')}}</a>
                    @endguest

                </div>
            </div>
        </section>

        <section class="plain big-padding">
            <div class="container">
                <h2 class="title-primary decorated"><span>{!! __('default.pages.index.our_statistic') !!}</h2>
                <div class="row row--multiline">
                    <div class="col-sm-4">
                        <div class="stat">
                            <img src="/assets/img/students.svg" alt="" class="stat__image">
                            <div>
                                <div class="stat__number">{{$students_count}}</div>
                                <div class="stat__label">{{ __('default.pages.index.students_count') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="stat">
                            <img src="/assets/img/authors.svg" alt="" class="stat__image">
                            <div>
                                <div class="stat__number">{{$authors_count}}</div>
                                <div class="stat__label">{{ __('default.pages.index.authors_count') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="stat">
                            <img src="/assets/img/courses.svg" alt="" class="stat__image">
                            <div>
                                <div class="stat__number">{{$courses_count}}</div>
                                <div class="stat__label">{{ __('default.pages.index.courses_count') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="gray">
            <div class="container">
                <h3 class="title-primary decorated"><span>{!! __('default.pages.index.advantages') !!}</h3>
                <div class="row row--multiline">
                    @foreach(json_decode($content->getAttribute('data_'.$lang))->advantages as $key => $advantages)
                        <div class="col-sm-6 col-md-{{12/count(json_decode($content->getAttribute('data_'.$lang))->advantages)}}">
                            <div class="privilege">
                                <div class="privilege__img">
                                    <img src="{{json_decode($content->getAttribute('data_ru'))->advantages[$key]->icon}}" alt="">
                                </div>
                                <h4 class="privilege__title">{{$advantages->name}}</h4>
                                <div class="privilege__text">
                                    {!! $advantages->description !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <br>
                <div class="text-center">
                    @auth
                        @if(Auth::user()->hasRole('author'))
                            <a href="/{{$lang}}/profile-author-information"
                               title="{{__('default.pages.footer.to_be_author')}}"
                               class="btn">Стать
                                автором</a>
                        @else
                            <a href="#authorRegistration1" data-fancybox=""
                               title="{{__('default.pages.footer.to_be_author')}}"
                               class="btn">{{__('default.pages.footer.to_be_author')}}</a>
                        @endif
                    @endauth
                    @guest
                        <a href="#authorRegistration" data-fancybox=""
                           title="{{__('default.pages.footer.to_be_author')}}"
                           class="btn">{{__('default.pages.footer.to_be_author')}}</a>
                    @endguest
                </div>
            </div>
        </section>

        <section class="plain big-padding">
            <div class="container">
                <h2 class="title-primary decorated">{!! __('default.pages.index.step_by_step') !!}</h2>
                <div class="row row--multiline">
                    @foreach(json_decode($content->getAttribute('data_'.$lang))->step_by_step as $key => $step)
                        <div class="col-md-{{12/count(json_decode($content->getAttribute('data_'.$lang))->step_by_step)}} col-sm-6">
                            <div class="step">
                                <div class="step__number">{{$key+1}}</div>
                                <div>
                                    <h4 class="step__title">{{ $step->name }}</h4>
                                    <div class="step__text">{!! $step->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="plain big-padding">
            <div class="container">
                <h2 class="title-primary decorated">{!! __('default.pages.calculator.title') !!}</h2>
                <div class="plain-text">
                    @php($calculator_teaser = json_decode($calculator->getAttribute('data_'.$lang))->calculator->teaser ?? json_decode($calculator->getAttribute('data_ru'))->calculator->teaser)
                    {!!nl2br(e($calculator_teaser)) !!}
                </div>
                <div class="calculator" id="calculator">
                    <div class="calculator__section white">
                        <div class="form-group">
                            <label class="form-group__label big">{{__('default.pages.calculator.duration')}}</label>
                            <input type="number" name="duration" class="input-big">
                        </div>
                        <div class="form-group">
                            <label class="form-group__label big">{{__('default.pages.calculator.format')}}</label>
                            <div class="radio-group">
                                <div>
                                    <label class="radio radio--bordered">
                                        <input type="checkbox" name="format" value="60"
                                               data-kk="10"><span>{{__('default.pages.calculator.format_section_1')}}</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="radio radio--bordered">
                                        <input type="checkbox" name="format" value="30"
                                               data-kk="5"><span>{{__('default.pages.calculator.format_section_2')}}</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="radio radio--bordered">
                                        <input type="checkbox" name="format" value="0"
                                               data-kk="0"><span>{{__('default.pages.calculator.format_section_3')}}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="hint gray">
                                {{__('default.pages.calculator.format_description')}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-group__label big">{{__('default.pages.calculator.general_tests')}}</label>
                            <div class="radio-group">
                                <div>
                                    <label class="radio radio--bordered">
                                        <input type="checkbox" name="tests"
                                               value="4"><span>{{__('default.pages.calculator.general_tests_section_1')}}</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="radio radio--bordered">
                                        <input type="checkbox" name="tests"
                                               value="2"><span>{{__('default.pages.calculator.general_tests_section_2')}}</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="radio radio--bordered">
                                        <input type="checkbox" name="tests"
                                               value="0"><span>{{__('default.pages.calculator.general_tests_section_3')}}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-group__label big">{{__('default.pages.calculator.final_test')}}</label>
                            <div class="radio-group">
                                <div>
                                    <label class="radio radio--bordered">
                                        <input type="checkbox" name="finalTest"
                                               value="6"><span>{{__('default.pages.calculator.final_test_section_1')}}</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="radio radio--bordered">
                                        <input type="checkbox" name="finalTest"
                                               value="3"><span>{{__('default.pages.calculator.final_test_section_2')}}</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="radio radio--bordered">
                                        <input type="checkbox" name="finalTest"
                                               value="0"><span>{{__('default.pages.calculator.final_test_section_3')}}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-group__label big">{{__('default.pages.calculator.course_rate')}}</label>
                            <div class="radio-group">
                                <div>
                                    <label class="radio radio--bordered">
                                        <input type="checkbox" name="rating"
                                               value="0"><span>{{__('default.pages.calculator.course_rate_section_1')}}</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="radio radio--bordered">
                                        <input type="checkbox" name="rating"
                                               value="5"><span>{{__('default.pages.calculator.course_rate_section_2')}}</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="radio radio--bordered">
                                        <input type="checkbox" name="rating"
                                               value="10"><span>{{__('default.pages.calculator.course_rate_section_3')}}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-group__label big">{{__('default.pages.calculator.language')}}</label>
                            <div class="radio-group">
                                <div>
                                    <label class="radio radio--bordered">
                                        <input type="checkbox" name="language" value=""
                                               class="calculator-kk-radio"><span>{{__('default.pages.calculator.language_section_1')}}</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="radio radio--bordered">
                                        <input type="checkbox" name="language"
                                               value="0"><span>{{__('default.pages.calculator.language_section_2')}}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-group__label big">{{__('default.pages.calculator.poor_vision')}}</label>
                            <div class="radio-group">
                                <div>
                                    <label class="radio radio--bordered">
                                        <input type="checkbox" name="vi-version"
                                               value="5"><span>{{__('default.pages.calculator.poor_vision_section_1')}}</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="radio radio--bordered">
                                        <input type="checkbox" name="vi-version"
                                               value="0"><span>{{__('default.pages.calculator.poor_vision_section_2')}}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="calculator__section gray">
                        <div class="form-group">
                            <label class="form-group__label big">{{__('default.pages.calculator.pay_for_student')}}</label>
                            <input type="text" class="input-big" name="costPerPerson" required value="0" disabled>
                        </div>
                    </div>
                    <div class="separator">
                        X
                    </div>
                    <div class="calculator__section white">
                        <div class="form-group">
                            <label class="form-group__label big">{{__('default.pages.calculator.students_count')}}</label>
                            <input type="number" name="quantity" class="input-big">
                        </div>
                    </div>
                    <div class="separator">
                        II
                    </div>
                    <div class="calculator__section blue">
                        <div class="form-group">
                            <label class="form-group__label big">{{__('default.pages.calculator.total_cost')}}</label>
                            <div class="calculator__result"><span>0</span> {{__('default.tenge_title')}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="blue">
            <div class="container">
                <div class="row row--multiline align-items-center">
                    <div class="col-sm-6">
                        <h2 class="title-primary decorated">{!! __('default.pages.index.to_be_a_teacher') !!}</h2>
                        <div class="plain-text">
                            {!! json_decode($content->getAttribute('data_'.$lang))->for_authors->description !!}
                        </div>
                        @auth
                            @if(Auth::user()->hasRole('author'))
                                <a href="/{{$lang}}/profile-author-information"
                                   title="{{json_decode($content->getAttribute('data_'.$lang))->for_authors->btn_title}}"
                                   class="ghost-btn ghost-btn--white">{{json_decode($content->getAttribute('data_'.$lang))->for_authors->btn_title}}</a>
                            @else
                                <a href="#authorRegistration1" data-fancybox=""
                                   title="{{json_decode($content->getAttribute('data_'.$lang))->for_authors->btn_title}}"
                                   class="ghost-btn ghost-btn--white">{{json_decode($content->getAttribute('data_'.$lang))->for_authors->btn_title}}</a>
                            @endif
                        @endauth
                        @guest
                            <a href="#authorRegistration" data-fancybox=""
                               title="{{json_decode($content->getAttribute('data_'.$lang))->for_authors->btn_title}}"
                               class="ghost-btn ghost-btn--white">{{json_decode($content->getAttribute('data_'.$lang))->for_authors->btn_title}}</a>
                        @endguest
                    </div>
                    <div class="col-sm-6">
                        <img src="/assets/img/authors-banner.svg" alt="">
                    </div>
                </div>
            </div>
        </section>

    </main>

    @auth
        <div id="authorRegistration1" style="display:none;" class="modal-form">
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
                    <input type="number" name="iin" placeholder="" class="input-regular" value="{{ old('iin') }}"
                           required>

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
                               class="author-picture__link red"
                               data-dz-remove>{{__('default.pages.courses.delete')}}</a>
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
    @endauth
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script src="/assets/js/calculator.js"></script>
    <script>
        let calculator = new Calculator({
            calculatorId: '#calculator',
            basePrice: 2001.05
        });
        calculator.init();
    </script>
    <!---->
@endsection

