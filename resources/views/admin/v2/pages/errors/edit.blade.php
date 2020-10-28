@extends('admin.v2.layout.default.template')

@section('title',__('default.pages.error.title').' '.$item->id.' | '.__('default.site_name'))

@section('head')

@endsection

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li><a href="/admin/error/index">{{ __('default.pages.errors.title') }}</a></li>
            <li><a href="/admin/{{ $item->type }}/{{$item->target_id}}">{{ $item->types()[$item->type] }}
                    ID:{{ $item->target_id }}</a></li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @include('admin.v2.partials.components.errors')
        <form action="/admin/error/{{ $item->id }}" method="post" enctype="multipart/form-data" class="block">
            {{ csrf_field() }}
            <div class="input-group">
                <label class="input-group__title">{{ __('default.pages.error.type') }}</label>
                <input type="text" value="{{ $item->types()[$item->type] }}" class="input-regular" disabled>
            </div>
            <div class="input-group">
                <label class="input-group__title">{{ __('default.pages.error.target_id') }}</label>
                <input type="text" value="{{ $item->target_id }}" class="input-regular" disabled>
            </div>
            <div class="input-group">
                <label class="input-group__title">{{ __('default.pages.error.description') }}</label>
                <input type="text" value="{{ $item->description }}" class="input-regular" disabled>
            </div>
            <div class="input-group {{ $errors->has('processed') ? ' has-error' : '' }}">
                <label class="checkbox">
                    <input type="hidden" name="processed" value="0">
                    <input name="processed" value="1" type="checkbox"
                           @if($item->processed) checked="checked" @endif>
                    <span>{{ __('default.pages.error.statuses.1') }}</span>
                </label>
                @if ($errors->has('processed'))
                    <span class="help-block"><strong>{{ $errors->first('processed') }}</strong></span>
                @endif
            </div>
            <div class="buttons">
                <div>
                    <button type="submit" class="btn btn--green">{{ __('default.labels.save') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')

@endsection
