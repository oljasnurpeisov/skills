@extends('admin.v2.layout.default.template')

@section('title',$item->name.' | '.__('admin.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li><a href="/{{$lang}}/admin/role/index">{{ __('admin.pages.roles.title') }}</a>
            </li>
            <li class="active">{{ $item->name }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @include('admin.v2.partials.components.errors')
        <form action="/{{$lang}}/admin/role/{{ $item->id }}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="block">
                <h2 class="title-secondary">{{ __('admin.pages.role.title') }}</h2>
                <div class="input-group {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('admin.pages.role.name') }} *</label>
                    <input type="text" name="name" value="{{ $item->name }}"
                           placeholder="{{ __('admin.labels.fill_field',['field' => __('admin.pages.role.name')]) }}"
                           class="input-regular" required>
                    @if ($errors->has('name'))
                        <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                    @endif
                </div>
                @if (!in_array($item->slug, $main_roles))
                    <div class="input-group {{ $errors->has('slug') ? ' has-error' : '' }}">
                        <label class="input-group__title">{{ __('admin.pages.role.slug') }} *</label>
                        <input type="text" name="slug" value="{{ $item->slug }}"
                               placeholder="{{ __('admin.labels.fill_field',['field' => __('admin.pages.role.slug')]) }}"
                               class="input-regular" required>
                        @if ($errors->has('slug'))
                            <span class="help-block"><strong>{{ $errors->first('slug') }}</strong></span>
                        @endif
                    </div>
                @endif
                <div class="input-group {{ $errors->has('description') ? 'has-error' : '' }}">
                    <label for="description"
                           class="input-group__title">{{ __('admin.pages.role.description') }}</label>
                    <textarea name="description"
                              placeholder="{{ __('admin.labels.fill_field',['field' => __('admin.pages.role.description')]) }}"
                              class="input-regular">{{ $item->description }}</textarea>
                    @if ($errors->has('description'))
                        <span class="help-block"><strong>{{ $errors->first('description') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="block">
                <h2 class="title-secondary">{{ __('admin.pages.role.permissions') }}</h2>
                @foreach($permissions as $permission)
                    <div class="input-group">
                        <label class="checkbox">
                            <input name="permissions[]" value="{{ $permission->id }}" type="checkbox"
                                   @if($item->permissions->contains($permission->id)) checked="checked" @endif>
                            <span>{{ $permission->name }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="block">
                <div class="buttons">
                    <div>
                        <button type="submit" class="btn btn--green">{{ __('admin.labels.save') }}</button>
                    </div>
                    <div>
                        @if(!in_array($item->slug, $main_roles))
                            <a href="/{{$lang}}/admin/role/{{ $item->id }}" class="btn btn--red btn--delete">
                                {{ __('admin.pages.deleting.submit') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
    @include('admin.v2.partials.modals.form_delete')
@endsection

@section('scripts')

@endsection
