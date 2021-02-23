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
                            <li><a href="/{{$lang}}/profile-author-information"
                                                  title="{{__('default.pages.profile.organization_data')}}">{{__('default.pages.profile.organization_data')}}</a>
                            </li>
                            <li><a href="/{{$lang}}/profile-pay-information"
                                   title="{{__('default.pages.profile.payment_information')}}">{{__('default.pages.profile.payment_information')}}</a>
                            </li>
                            <li class="active"><a href="/{{$lang}}/edit-profile"
                                   title="{{__('default.pages.profile.registration_data')}}">{{__('default.pages.profile.registration_data')}}</a>
                            </li>
                            <li><a href="/{{$lang}}/change-password"
                                   title="{{__('default.pages.profile.password_title')}}">{{__('default.pages.profile.password_title')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <form action="/{{$lang}}/update_profile" method="POST"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
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

                    <div class="row row--multiline">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.profile.email_title')}} *</label>
                                <input type="email" name="email" placeholder="" class="input-regular" value="{{$user->email}}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.profile.iin_title')}} *</label>
                                <input type="number" name="iin" placeholder="" class="input-regular" value="{{$user->iin}}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.profile.type_of_ownership')}} *</label>
                                <select name="type_of_ownership" class="selectize-regular no-search">
                                    @foreach($types_of_ownership as $type)
                                    <option value="{{ $type->id }}" @if($type->id==$user->type_ownership->id) selected='selected' @endif>
                                        {{ $type->getAttribute('name_'.$lang) ??  $type->getAttribute('name_ru') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.profile.company_name')}} *</label>
                                <input type="text" name="company_name" placeholder="" class="input-regular" value="{{$user->company_name}}" required>
                            </div>

                            <div class="avatar logo-picture dropzone-avatar" id="companyLogo" data-url="{{env('APP_URL')}}/ajax_upload_image?_token={{ csrf_token() }}" data-maxsize="1" data-acceptedfiles="image/*">
                                <input type="hidden" name="company_logo" class="avatar-path" value="{{$user->company_logo}}">
                                <img src="{{$user->company_logo ?? '/assets/img/logo-thumbnail.png'}}" class="logo-picture__preview avatar-preview" alt="">
                                <div class="logo-picture__desc dropzone-default">
                                    <div class="dropzone-default__info">PNG, JPG • {{__('default.pages.profile.max_file_title')}} 1MB</div>
                                    <div class="previews-container"></div>
                                    <div class="logo-picture__link avatar-pick dropzone-default__link">{{__('default.pages.profile.choose_photo')}}</div>
                                </div>
                                <div class="avatar-preview-template" style="display:none;">
                                    <div class="dz-preview dz-file-preview">
                                        <div class="dz-details">
                                            <div class="dz-filename"><span data-dz-name></span></div>
                                            <div class="dz-size" data-dz-size></div>
                                        </div>
                                        <div class="alert alert-danger"><span data-dz-errormessage> </span></div>
                                        <a href="javascript:undefined;" title="{{__('default.pages.profile.delete')}}" class="author-picture__link red" data-dz-remove>{{__('default.pages.profile.delete')}}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons">
                                <button type="submit" class="btn">{{__('default.pages.profile.save_btn_title')}}</button>
{{--                                <a href="#" title="Отмена" class="ghost-btn">Отмена</a>--}}
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

    <!---->
@endsection

