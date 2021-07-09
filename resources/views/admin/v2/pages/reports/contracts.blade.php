@extends('admin.v2.layout.default.template')

@section('title', $title .' | '.__('admin.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li class="active">{{ $title }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')

        @include('admin.v2.partials.components.errors')

        <div class="block">
            <h2 class="title-secondary" style="float: left">{{ $title }}</h2>

            <table class="table records">
                <colgroup>
                    <col span="1" style="width: 5%;">
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 7%;">
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
                    <th>Тип договора</th>
                    <th>Дата подписания Автором</th>
                    <th>Дата публикации</th>
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
                            <select id="course_type" name="course_type" class="input-regular chosen">
                                <option value="">Все</option>
                                <option value="3">Доступен по квоте</option>
                                <option value="2">Платный</option>
                                <option value="1">Бесплатный</option>
                            </select>
                            <script>document.getElementById('course_type').value = {{ $request['course_type'] ?? '' }};</script>
                        </th>
                        <th>
                            <input type="text" data-date-format="dd.mm.yyyy" id="author_signed_at" name="author_signed_at" value="{{ $request['author_signed_at'] ?? '' }}" placeholder="" class="input-regular custom-datepicker" autocomplete="off" data-value="{{ $request['author_signed_at'] ?? '' }}" data-range="true">
                        </th>
                        <th>
                            <input type="text" data-date-format="dd.mm.yyyy" id="course_publish_at" name="course_publish_at" value="{{ $request['course_publish_at'] ?? '' }}" placeholder="" class="input-regular custom-datepicker" autocomplete="off" data-value="{{ $request['course_publish_at'] ?? '' }}" data-range="true">
                        </th>
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
                        <td>{{ $contract->course->getTypeName() }}</td>
                        <td>{{ $contract->author_signed_at ?? '-' }}</td>
                        <td>{{ $contract->course->publish_at ?? '-' }}</td>
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

@endsection

@section('scripts')
    <?php
        if (!empty($request['author_signed_at'])) {
            $date = explode(',', $request['author_signed_at']);
            $start_s = \Carbon\Carbon::parse($date[0])->format('m/d/Y');
            $end_s = \Carbon\Carbon::parse($date[1])->format('m/d/Y');
        }
        if (!empty($request['course_publish_at'])) {
            $date = explode(',', $request['course_publish_at']);
            $start_c = \Carbon\Carbon::parse($date[0])->format('m/d/Y');
            $end_c = \Carbon\Carbon::parse($date[1])->format('m/d/Y');
        }
    ?>
    @if (!empty($request['author_signed_at']))
        <script>
            var datepicker = $('#author_signed_at').datepicker().data('datepicker');
            datepicker.selectDate(new Date('{{ $start_s }}'));
            datepicker.selectDate(new Date('{{ $end_s }}'));
        </script>
    @endif
    @if (!empty($request['course_publish_at']))
        <script>
            var datepicker = $('#course_publish_at').datepicker().data('datepicker');
            datepicker.selectDate(new Date('{{ $start_c }}'));
            datepicker.selectDate(new Date('{{ $end_c }}'));
        </script>
    @endif
@endsection

