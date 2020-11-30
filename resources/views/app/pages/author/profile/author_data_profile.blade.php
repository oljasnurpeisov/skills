@extends('app.layout.default.template')

@section('content')
    <main class="main">
        <section class="plain">
            <div class="container">
                <h1 class="title-primary">{{__('default.pages.profile.title')}}</h1>
                <div class="mobile-dropdown">
                    <div class="mobile-dropdown__title dynamic">{{__('default.pages.profile.organization_data')}}</div>
                    <div class="mobile-dropdown__desc">
                        <ul class="tabs-links">
                            <li class="active"><a href="/{{$lang}}/profile-author-information"
                                                  title="{{__('default.pages.profile.organization_data')}}">{{__('default.pages.profile.organization_data')}}</a>
                            </li>
                            <li><a href="/{{$lang}}/profile-pay-information"
                                   title="{{__('default.pages.profile.payment_information')}}">{{__('default.pages.profile.payment_information')}}</a>
                            </li>
                            <li><a href="/{{$lang}}/edit-profile"
                                   title="{{__('default.pages.profile.registration_data')}}">{{__('default.pages.profile.registration_data')}}</a>
                            </li>
                            <li><a href="/{{$lang}}/change-password"
                                   title="{{__('default.pages.profile.password_title')}}">{{__('default.pages.profile.password_title')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <form class="author-personal" action="/{{$lang}}/update_author_data_profile" method="POST"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('failed'))
                        <div class="alert alert-danger">
                            {{ session('failed') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{!! $error !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if ($item->user->email_verified_at == null)
                        <div class="alert alert-danger">
                            {{__('default.pages.profile.email_confirm_error')}}
                        </div>
                    @endif
                    <div @if($item->user->email_verified_at == null)class="row row--multiline disabled"@else class="row row--multiline"@endif>
                        <div class="col-sm-4">
                            <div class="author-personal__left">
                                <div class="avatar author-picture dropzone-avatar" id="avatar"
                                     data-url="{{env('APP_URL')}}/ajax_upload_image?_token={{ csrf_token() }}"
                                     data-maxsize="1"
                                     data-acceptedfiles="image/*">
                                    <img src="{{$item->getAvatar()}}"
                                         class="author-picture__preview avatar-preview" alt="">
                                    <div class="author-picture__link avatar-pick">{{__('default.pages.profile.choose_photo')}}</div>
                                    <div class="previews-container"></div>
                                    <div class="avatar-preview-template" style="display:none;">
                                        <div class="previews-container"></div>
                                        <div class="dz-preview dz-file-preview">
                                            <div class="alert alert-danger"><span data-dz-errormessage> </span></div>
                                            <a href="javascript:undefined;"
                                               title="{{__('default.pages.profile.delete')}}"
                                               class="author-picture__link red"
                                               data-dz-remove>{{__('default.pages.profile.delete')}}</a>
                                        </div>
                                    </div>
                                    <input type="hidden" name="avatar" class="avatar-path" value="{{$item->getAvatar()}}">
                                </div>
                                <div class="rating">
                                    <div class="rating__number">{{round($average_rates, 1)}}</div>
                                    <div class="rating__stars">
                                        <?php
                                        for ($x = 1; $x <= $average_rates; $x++) {
                                            echo '<i class="icon-star-full"> </i>';
                                        }
                                        if (strpos($average_rates, '.')) {
                                            echo '<i class="icon-star-half"> </i>';
                                            $x++;
                                        }
                                        while ($x <= 5) {
                                            echo '<i class="icon-star-empty"> </i>';
                                            $x++;
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="author-stats">
                                    <div>
                                        <span class="author-stats__number">{{count($rates)}}</span>
                                        <span class="author-stats__label">{{__('default.pages.profile.rates_count_title')}}</span>
                                    </div>
                                    <div>
                                        <span class="author-stats__number">{{count($author_students)}}</span>
                                        <span class="author-stats__label">{{__('default.pages.profile.course_members_count')}}</span>
                                    </div>
                                    <div>
                                        <span class="author-stats__number">{{count($courses->where('status', '=', 3))}}</span>
                                        <span class="author-stats__label">{{__('default.pages.profile.course_count')}}</span>
                                    </div>
                                    <div>
                                        <span class="author-stats__number">{{count($author_students_finished)}}</span>
                                        <span class="author-stats__label">{{__('default.pages.profile.issued_certificates')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="author-personal__right">
                                <h2 class="title-secondary">{{__('default.pages.profile.responsible_person')}}</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-group__label">{{__('default.pages.profile.name')}}
                                                *</label>
                                            <input type="text" name="name" placeholder="" class="input-regular"
                                                   value="{{$item->name}}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-group__label">{{__('default.pages.profile.surname')}}
                                                *</label>
                                            <input type="text" name="surname" placeholder="" class="input-regular"
                                                   value="{{$item->surname}}"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.profile.specialization')}}</label>
                                    <div class="input-addon">
                                        <div id="specialitiesInputTpl">
                                            <input type="text" name="specialization[]" placeholder=""
                                                   class="input-regular"
                                                   value="{{json_decode($item->specialization)[0] ?? ''}}">
                                        </div>

                                        <div class="addon">
                                            <span class="required">*</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="specialitiesContainer">
                                    @if(!empty(json_decode($item->specialization)[1]))
                                        @foreach(array_slice(json_decode($item->specialization),1) as $spec)
                                            <div class="form-group">
                                                <div class="input-addon">

                                                    <input type="text" name="specialization[]" placeholder=""
                                                           class="input-regular" value="{{$spec}}">

                                                    <div class="addon">
                                                        <div class="btn-icon small icon-close"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="text-right pull-up">
                                    <a href="#" title="{{__('default.pages.profile.add_btn_title')}}" class="add-btn"
                                       id="specialitiesAddBtn"><span
                                                class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span
                                                class="btn-icon small icon-plus"> </span></a>
                                </div>
{{--                                <div class="text-right pull-up">--}}
{{--                                    <a href="#" title="{{__('default.pages.profile.add_btn_title')}}" class="add-btn"--}}
{{--                                       data-duplicate="specialitiesInputTpl"><span--}}
{{--                                                class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span--}}
{{--                                                class="btn-icon small icon-plus"> </span></a>--}}
{{--                                </div>--}}
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.profile.about_company')}}
                                        *</label>
                                    <textarea name="about" class="input-regular tinymce-text-here" required>{{$item->about}}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-group__label">{{__('default.pages.profile.phone_1')}}</label>
                                            <input type="tel" name="phone_1"
                                                   onfocus="$(this).inputmask('+7 (999) 999 99 99')" placeholder=""
                                                   class="input-regular" value="{{$item->phone_1}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-group__label">{{__('default.pages.profile.phone_2')}}</label>
                                            <input type="tel" name="phone_2"
                                                   onfocus="$(this).inputmask('+7 (9999) 99 99 99')"
                                                   placeholder="" class="input-regular" value="{{$item->phone_2}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.profile.site_url')}}</label>
                                    <input type="url" name="site_url" placeholder="" class="input-regular"
                                           value="{{$item->site_url}}">
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-group__label">{{__('default.pages.profile.vk_link')}}</label>
                                            <input type="url" name="vk_link" placeholder="" class="input-regular"
                                                   value="{{$item->vk_link}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-group__label">{{__('default.pages.profile.fb_link')}}</label>
                                            <input type="url" name="fb_link" placeholder="" class="input-regular"
                                                   value="{{$item->fb_link}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-group__label">{{__('default.pages.profile.instagram_link')}}</label>
                                            <input type="url" name="instagram_link" placeholder="" class="input-regular"
                                                   value="{{$item->instagram_link}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">{{__('default.pages.profile.certificates')}}</label>
                                    <div data-url="/ajax_upload_certificates?_token={{ csrf_token() }}" data-maxfiles="15"
                                         data-maxsize="20" data-acceptedfiles=".pdf, .png, .jpg"
                                         id="documents-dropzone2"
                                         class="dropzone-default dropzone-multiple">
                                        <input type="hidden" name="certificates" value="{{$item->certificates}}">
                                        <div class="dropzone-default__info">PDF, PNG, JPG
                                            • {{__('default.pages.profile.max_file_title')}} 20MB
                                        </div>
                                        <div class="previews-container"></div>
                                        <a href="javascript:;"
                                           title="{{__('default.pages.profile.add_files_btn_title')}}"
                                           class="dropzone-default__link">{{__('default.pages.profile.add_files_btn_title')}}</a>
                                    </div>

                                </div>
                                <div class="buttons">
                                    <button type="submit"
                                            class="btn">{{__('default.pages.profile.save_btn_title')}}</button>
                                    {{--                                    <a href="#" title="Отмена" class="ghost-btn">Отмена</a>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>

    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <script>
        let addBtn = document.querySelector('#specialitiesAddBtn'),
            addContainer = document.querySelector('#specialitiesContainer'),
            // inputTpl = document.querySelector('#specialitiesInputTpl').innerHTML;
            inputTpl = '<div id="specialitiesInputTpl">\n' +
                '                                            <input type="text" name="specialization[]" placeholder="" class="input-regular">\n' +
                '                                        </div>'

        addBtn.addEventListener('click', function (e) {
            e.preventDefault();

            let removeBtn = document.createElement('div'),
                removeBtnClassList = ['btn-icon', 'small', 'icon-close'];
            removeBtnClassList.forEach(function (className) {
                removeBtn.classList.add(className);
            });

            let newItem = document.createElement('div');
            newItem.className = 'form-group';
            newItem.innerHTML = `<div class="input-addon">
                                ${inputTpl}
                                <div class="addon"></div>
                            </div>
      `;

            removeBtn.addEventListener('click', function () {
                newItem.remove();
            });

            newItem.querySelector('.addon').append(removeBtn);

            addContainer.append(newItem);
        });

    </script>
    <!---->
@endsection

