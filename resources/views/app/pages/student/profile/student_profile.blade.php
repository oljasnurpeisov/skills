@extends('app.layout.default.template')

@section('content')
    <main class="main">

        <section class="plain">
            <div class="container">
                <h1 class="title-primary">Профиль</h1>
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
                <div class="row">
                    <div class="col-md-6 col-sm-8">
                        <form action="/{{$lang}}/update_student_profile" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="form-group__label">{{__('default.pages.profile.avatar')}}</label>
                                <div class="avatar logo-picture dropzone-avatar" id="companyLogoModal"
                                     data-url="/ajax_upload_image?_token={{ csrf_token() }}" data-maxsize="1"
                                     data-acceptedfiles="image/*">
                                    <div class="logo-picture__preview">
                                        <img src="{{$item->getAvatar()}}"
                                             data-defaultsrc="/assets/img/lesson-thumbnail.jpg"
                                             class="avatar-preview" alt="">
                                    </div>
                                    <div class="logo-picture__desc dropzone-default">
                                        <input type="hidden" name="avatar" class="avatar-path" value="{{$item->image}}">
                                        @if($item->avatar != null)
                                            <div class="previews-container">
                                                <div class="dz-preview dz-image-preview">
                                                    <div class="dz-details">
                                                        <div class="dz-filename"><span
                                                                    data-dz-name="">{{basename($item->getAvatar())}}</span>
                                                        </div>
                                                        <div class="dz-size" data-dz-size="">
                                                            <strong>{{ round(filesize(public_path($item->avatar)) / 1024) }}</strong>
                                                            MB
                                                        </div>
                                                    </div>
                                                    <a href="javascript:undefined;"
                                                       title="{{__('default.pages.profile.delete')}}"
                                                       class="author-picture__link red"
                                                       data-dz-remove="">{{__('default.pages.profile.delete')}}</a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="previews-container"></div>
                                        @endif
                                        <div class="dropzone-default__info">PNG, JPG
                                            • {{__('default.pages.profile.max_file_title')}} 1MB
                                        </div>
                                        <div class="logo-picture__link avatar-pick dropzone-default__link">{{__('default.pages.profile.choose_photo')}}
                                        </div>
                                    </div>
                                    <div class="avatar-preview-template" style="display:none;">
                                        <div class="dz-preview dz-file-preview">
                                            <div class="dz-details">
                                                <div class="dz-filename"><span data-dz-name></span></div>
                                                <div class="dz-size" data-dz-size></div>
                                                <div class="dz-progress"><span class="dz-upload"
                                                                               data-dz-uploadprogress></span></div>
                                            </div>
                                            <div class="alert alert-danger"><span data-dz-errormessage> </span></div>
                                            <a href="javascript:undefined;"
                                               title="{{__('default.pages.profile.delete')}}"
                                               class="author-picture__link red"
                                               data-dz-remove>{{__('default.pages.profile.delete')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons">
                                <button type="submit"
                                        class="btn">{{__('default.pages.profile.save_btn_title')}}</button>
                                {{--                                <a href="#" title="Отмена" class="ghost-btn">Отмена</a>--}}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection

@section('scripts')
    <!--Only this page's scripts-->
    <!---->
@endsection

