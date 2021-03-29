@extends('admin.v2.layout.default.template')

@section('title',__('default.pages.reviews.title').' | '.__('default.site_name'))

@section('head')

@endsection

@section('content')
    <div class="container container-fluid">
        <div class="title-block">
            <div class="row row--multiline align-items-center">
                <div class="col-md-4">
                    <h1 class="title-primary" style="margin-bottom: 0">{{ __('default.pages.reviews.title') }}</h1>
                </div>
                <div class="col-md-8 text-right-md text-right-lg">

                </div>
            </div>
        </div>

        @include('admin.v2.partials.components.warning')
        <div class="block">
            <h2 class="title-secondary">{{ __('default.labels.record_list') }}</h2>

            @if(count($items) > 0)
                <table class="table records">
                    <colgroup>
                        <col span="1" style="width: 5%;">
                        <col span="1" style="width: 20%;">
                        <col span="1" style="width: 55%;">
                        <col span="1" style="width: 10%;">
                        <col span="1" style="width: 10%;">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('default.pages.user.title') }}</th>
                        <th>{{ __('default.pages.review.last_message') }}</th>
                        <th>{{ __('default.pages.review.status') }}</th>
                        <th>{{ __('default.labels.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        @php($review = $item->reviews->first())
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td><a href="/admin/user/{{$item->id}}">{{ $item->full_name }}</a></td>
                            <td>
                                {!! '<b>'.__('default.pages.review.from').': '.$review->sender->full_name.'</b><br>' !!}
                                {!! $review->preview() !!}
                            </td>
                            <td>
                                @if($review->sender_id === $item->id)
                                    <div class="alert alert-warning" style="margin: 0;">
                                        {{ __('default.pages.review.statuses.0') }}
                                    </div>
                                @else
                                    <div class="alert alert-success" style="margin: 0;">
                                        {{ __('default.pages.review.statuses.1') }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/admin/review/{{ $item->id }}"
                                       title="{{ __('default.labels.view') }}"
                                       class="icon-btn icon-btn--green icon-eye"></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif

            <div class="text-right">
                {{ $items->appends([])->links('vendor.pagination.bootstrap') }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
