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
            </div>
        </div>

        @include('admin.v2.partials.components.warning')

        <div class="block">
            <h2 class="title-secondary">{{ !empty($request) ? __('admin.labels.search_result') : __('admin.labels.record_list') }}</h2>

            <table class="table records">
                <colgroup>
                    <col span="1" style="width: 5%;">
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 7%;">
                    <col span="1" style="width: 17%;">
                    <col span="1" style="width: 7%;">
                    <col span="1" style="width: 15%;">
                    <col span="1" style="width: 15%;">
                    @if (Route::currentRouteName() === 'admin.contracts.distributed' || Route::currentRouteName() === 'admin.contracts.rejected_by_admin')
                        <col span="1" style="width: 15%;">
                    @endif
                    <col span="1" style="width: 15%;">
                </colgroup>
                <thead>
                <tr>
                    <th>Номер договора</th>
                    <th>Название курса</th>
                    <th>Название организации</th>
                    <th>Статус договора</th>
                    <th>Тип курса</th>
                    <th>Дата подписания Автором</th>
                    <th>Дата публикации</th>
                    @if (Route::currentRouteName() === 'admin.contracts.distributed' || Route::currentRouteName() === 'admin.contracts.rejected_by_admin')
                        <th>Причина отклонения</th>
                    @endif
                    <th>Договор</th>
                </tr>
                </thead>
                <tbody>
                    <form name="search">
                        <tr>
                            <th><input name="contract_number" type="text" class="input-regular" value="{{ $request['contract_number'] ?? '' }}"></th>
                            <th><input name="course_name" type="text" class="input-regular" value="{{ $request['course_name'] ?? '' }}"></th>
                            <th><input name="company_name" type="text" class="input-regular" value="{{ $request['company_name'] ?? '' }}"></th>
                            <th>
                                @if(Route::currentRouteName() === 'admin.contracts.all')
                                    <select id="contract_status" name="contract_status" class="input-regular chosen">
                                        <option value="">Все</option>
                                        <option value="1">Ожидающие подписания</option>
                                        <option value="2">Подписаны</option>
                                        <option value="3">Расторгнуты</option>
                                        <option value="4">Отклонены автором</option>
                                    </select>
                                    <script>document.getElementById('contract_status').value = {{ $request['contract_status'] ?? '' }};</script>
                                @endif
                            </th>
                            <th>
                                <select id="course_type" name="course_type" class="input-regular chosen">
                                    <option value="">Все</option>
                                    <option value="3">Доступен по квоте</option>
                                    <option value="2">Платный</option>
                                    <option value="1">Бесплатный</option>
                                </select>
                                <script>document.getElementById('course_type').value = {{ $request['course_type'] ?? '' }};</script>
                            </th>
                            <th></th>
                            <th></th>
                            @if (Route::currentRouteName() === 'admin.contracts.distributed' || Route::currentRouteName() === 'admin.contracts.rejected_by_admin')
                                <th></th>
                            @endif
                            <th>
                                <div class="buttons btn-group-sm">
                                    <a href="{{ route(Route::currentRouteName(), ['lang' => $lang]) }}" class="btn" style="color: #fff; background: #e2e2e2; text-decoration:none; height: 30px; margin-top: 2px; margin-right: 5px">Сбросить</a>
                                    <button class="btn">Поиск</button>
                                </div>

                            </th>
                        </tr>
                    </form>

                    @foreach($contracts as $contract)
                        <tr>
                            <td>{{ $contract->number }}</td>
                            <td>{{ $contract->course->name }}</td>
                            <td>{{ $contract->course->user->company_name }}</td>
                            <td>{{ $contract->getStatusName() }}</td>
                            <td>{{ $contract->course->getTypeName() }}</td>
                            <td>-</td>
                            <td>{{ $contract->course->publish_at ?? '-' }}</td>
                            @if (Route::currentRouteName() === 'admin.contracts.distributed' || Route::currentRouteName() === 'admin.contracts.rejected_by_admin')
                                <td>{{ $contract->reject_comment }}</td>
                            @endif
                            <td>
                                <div class="action-buttons">
                                    @if (!empty($contract->current_route))
                                        <a target="_blank" href="{{ route('admin.contracts.view', ['lang' => $lang, 'contract_id' => $contract->id]) }}" title="{{ __('admin.labels.view') }}" class="icon-btn icon-btn--yellow icon-eye"></a>
                                    @endif
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
