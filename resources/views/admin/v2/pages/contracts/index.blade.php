@extends('admin.v2.layout.default.template')

@section('title', $title .' | '.__('admin.site_name'))

@section('head')

@endsection

@section('content')
    <div class="container container-fluid">
        <div class="title-block">
            <div class="row row--multiline align-items-center">
                <div class="col-md-4">
                    <h1 class="title-primary" style="margin-bottom: 0">{{ $title }}</h1>
                </div>
                <div class="col-md-8 text-right-md text-right-lg">
                    <div class="flex-form">
                        <div>
                            <form action="{{ route('admin.contracts.all', ['lang' => $lang]) }}" method="get" class="input-button">
                                <input type="text" name="keywords"
                                       placeholder="{{ __('admin.pages.courses.course_name') }}"
                                       class="input-regular input-regular--solid" style="width: 282px;"
                                       value="{{ $keywords }}">
                                <button class="btn btn--green">{{ __('admin.labels.search') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.v2.partials.components.warning')
        <div class="block">
            <h2 class="title-secondary">{{ $keywords ? __('admin.labels.search_result') : __('admin.labels.record_list') }}</h2>

            <table class="table records">
                <colgroup>
                    <col span="1" style="width: 5%;">
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 7%;">
                    <col span="1" style="width: 7%;">
                    <col span="1" style="width: 7%;">
                    <col span="1" style="width: 15%;">
                    <col span="1" style="width: 15%;">
                    <col span="1" style="width: 8%;">
                </colgroup>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Название курса</th>
                    <th>Название организации автора</th>
                    <th>Статус договора</th>
                    <th>Тип курса</th>
                    <th>Дата подписания Автором</th>
                    <th>Дата публикации</th>
                    <th>{{ __('admin.labels.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($contracts as $contract)
                    <tr>
                        <td>{{ $contract->id }}</td>
                        <td>{{ $contract->course->name }}</td>
                        <td>{{ $contract->course->user->company_name }}</td>
                        <td>-</td>
                        <td>{{ $contract->course->getTypeName() }}</td>
                        <td>-</td>
                        <td>{{ $contract->created_at }}</td>
                        <td>
                            <div class="action-buttons">
                                <a target="_blank" href="{{ asset($contract->link) }}" title="{{ __('admin.labels.view') }}"
                                   class="icon-btn icon-btn--yellow icon-eye"></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-right">
                {{ $contracts->links('vendor.pagination.bootstrap') }}
            </div>
        </div>
    </div>

    @include('admin.v2.partials.modals.form_delete')
@endsection

@section('scripts')

@endsection
