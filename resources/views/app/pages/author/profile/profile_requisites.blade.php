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
                            <li><a href="/{{$lang}}/edit-profile"
                                   title="{{__('default.pages.profile.registration_data')}}">{{__('default.pages.profile.registration_data')}}</a>
                            </li>
                            <li><a href="/{{$lang}}/change-password"
                                                  title="{{__('default.pages.profile.password_title')}}">{{__('default.pages.profile.password_title')}}</a>
                            </li>
                            <li class="active">
                                <a href="/{{$lang}}/profile-requisites"
                                   title="{{__('default.pages.profile.requisites_data')}}">{{__('default.pages.profile.requisites_data')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <form action="{{ route('update_profile_requisites', ['lang' => $lang]) }}" method="POST">
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

                    <div class="row row--multiline">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.auth.type_of_ownership')}} *</label>
                                <select name="type_of_ownership" class="selectize-regular no-search">
                                    @foreach($types_of_ownership as $type)
                                        <option value="{{ $type->id }}" @if(isset($user->type_ownership->id) and $type->id==Auth::user()->type_ownership) selected='selected' @endif>
                                            {{ $type->getAttribute('name_'.$lang) ??  $type->getAttribute('name_ru') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.auth.company_name')}} *</label>
                                <input type="text" name="company_name" placeholder="" class="input-regular" required value="{{ old('company_name') ?? Auth::user()->company_name }}">
                            </div>

                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.profile.position')}} *</label>
                                <input type="text" name="position" placeholder="" class="input-regular" required value="{{ old('position') ?? Auth::user()->position }}">
                            </div>

                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.profile.fio_director')}} *</label>
                                <input type="text" name="fio_director" placeholder="" class="input-regular" required value="{{ old('fio_director') ?? Auth::user()->fio_director }}">
                            </div>

                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.profile.base')}} *</label>
                                <select type="text" name="base" required class="selectize-regular no-search">
                                    <option @if(Auth::user()->base === 1) selected='selected' @endif value="1">{{__('default.pages.profile.base_select.tired')}}</option>
                                    <option @if(Auth::user()->base === 2) selected='selected' @endif value="2">{{__('default.pages.profile.base_select.position')}}</option>
                                    <option @if(Auth::user()->base === 3) selected='selected' @endif value="3">{{__('default.pages.profile.base_select.order')}}</option>
                                    <option @if(Auth::user()->base === 4) selected='selected' @endif value="4">{{__('default.pages.profile.base_select.decision')}}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div data-url="{{ route('ajaxUploadFile') }}" data-maxfiles="1"
                                     data-maxsize="1" data-acceptedfiles="image/*, application/pdf"
                                     class="dropzone-default dropzone-multiple @if (!empty(Auth::user()->base_file)) dz-max-files-reached @endif">
                                    <input type="text" name="base_file" value="{{ Auth::user()->base_file }}" required>
                                    <div class="dropzone-default__info">JPG, PNG, PDF • {{__('default.pages.profile.max_file_title') }}. 1MB</div>
                                    <a href="javascript:;" title="{{__('default.pages.profile.choose_file')}}" class="dropzone-default__link">{{__('default.pages.profile.choose_file')}}</a>
                                    @if (!empty(Auth::user()->base_file))
                                        <div class="previews-container">
                                            <div class="dz-preview dz-processing dz-image-preview dz-success dz-complete">
                                                <div class="dz-details">
                                                    <div class="dz-filename"><span data-dz-name="">{{ Auth::user()->base_file }}</span></div>
                                                    <div class="dz-size" data-dz-size="">
                                                        @if(file_exists(public_path(Auth::user()->base_file)))
                                                            <strong>{{ Auth::user()->base_file ? round(filesize(public_path(Auth::user()->base_file)) / 1024) : 0 }}</strong>
                                                            KB
                                                        @else
                                                            <strong>0</strong> KB
                                                        @endif
                                                    </div>
                                                    <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress="" style="width: 100%;"></span></div>
                                                </div>
                                                <div class="alert alert-danger"><span data-dz-errormessage=""> </span></div>
                                                <a href="javascript:undefined;" title="Удалить" class="link red" data-dz-remove="">{{__('default.pages.profile.delete') }}</a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="previews-container"></div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.auth.iin')}} *</label>
                                <input type="text" name="iin" placeholder="" class="input-regular" required value="{{ old('iin') ?? Auth::user()->iin }}">
                            </div>

                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.profile.iik_kz')}} *</label>
                                <input type="text" name="iik_kz" placeholder="" class="input-regular" required value="{{ old('iik_kz') ?? Auth::user()->iik_kz }}">
                            </div>

                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.profile.kbe')}} *</label>
                                <input type="text" name="kbe" placeholder="" class="input-regular" required value="{{ old('kbe') ?? Auth::user()->kbe }}">
                            </div>

                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.profile.bik')}} *</label>
                                <input type="text" name="bik" placeholder="" class="input-regular" required value="{{ old('bik') ?? Auth::user()->bik }}">
                            </div>

                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.profile.bank_name')}} *</label>
                                <select type="text" name="bank_id" required class="selectize-regular">
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}" @if($bank->id==Auth::user()->bank_id) selected='selected' @endif>
                                            {{ $bank->getAttribute('name_'.$lang) ??  $type->getAttribute('name_ru') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="buttons">
                                <button type="submit" class="btn">{{__('default.pages.profile.save_btn_title')}}</button>
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

