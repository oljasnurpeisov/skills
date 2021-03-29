@extends('admin.v2.layout.default.template')

@section('title',__('default.pages.reviews.title').': '.$item->full_name.' | '.__('default.site_name'))

@section('head')

@endsection

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li><a href="/admin/review/index">{{ __('default.pages.reviews.title') }}</a></li>
            <li><a href="/admin/user/{{ $item->id }}">{{ $item->full_name }}</a></li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @include('admin.v2.partials.components.errors')
        <div class="row">
            <div class="col-md-8">
                <div class="block">
                    <h2 class="title-secondary">{{ __('default.pages.reviews.title') }}</h2>
                    <table class="table records">
                        <colgroup>
                            <col span="1" style="width: 80%;">
                            <col span="1" style="width: 20%;">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>{{ __('default.pages.review.title') }}</th>
                            <th>{{ __('default.labels.created_at') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($item->reviews as $review)
                            <tr class="alert @if($review->sender_id === $item->id) alert-warning @else alert-success @endif"
                                style="font-size: 1em;">
                                <td>
                                    {!! '<b>'.__('default.pages.review.from').': '.$review->sender->full_name.'</b><br>' !!}
                                    {!! $review->preview() !!}
                                </td>
                                <td>{!! $item->created_at !!}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <form action="/admin/review/{{ $item->id }}" method="post" enctype="multipart/form-data" class="block">
                    {{ csrf_field() }}
                    <h2 class="title-secondary">{{ __('default.pages.review.title') }}</h2>
                    <div class="input-group {{ $errors->has('description') ? 'has-error' : '' }}">
                        <label for="description"
                               class="input-group__title">{{ __('default.pages.review.description') }}</label>
                        <textarea name="description"
                                  placeholder="{{ __('default.labels.fill_field',['field' => __('default.pages.review.description')]) }}"
                                  class="input-regular">{{ $item->description }}</textarea>
                        @if ($errors->has('description'))
                            <span class="help-block"><strong>{{ $errors->first('description') }}</strong></span>
                        @endif
                    </div>
                    <div class="buttons">
                        <div>
                            <button type="submit" class="btn btn--green">{{ __('default.pages.review.add_answer') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
