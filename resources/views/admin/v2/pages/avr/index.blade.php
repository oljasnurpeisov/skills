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
                    <col span="1" style="width: 5%;">
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 7%;">
                    <col span="1" style="width: 17%;">
                    <col span="1" style="width: 7%;">
                    <col span="1" style="width: 15%;">
                    <col span="1" style="width: 15%;">
                    <col span="1" style="width: 15%;">
                    <col span="1" style="width: 15%;">
                </colgroup>
                <thead>
                <tr>
                    <th>Номер договора</th>
                    <th>Номер АВР</th>
                    <th>Название курса</th>
                    <th>Название организации</th>
                    <th>Статус АВР</th>
                    <th>Сумма АВР</th>
                    <th>Период</th>
                    <th>Дата подписания Автором</th>
                    <th>Дата принятия работ</th>
                    <th>АВР</th>
                </tr>
                </thead>
                <tbody>
                    <form name="search">
                        <tr>
                            <th><input name="contract_number" type="text" class="input-regular" value="{{ $request['contract_number'] ?? '' }}"></th>
                            <th><input name="avr_number" type="text" class="input-regular" value="{{ $request['avr_number'] ?? '' }}"></th>
                            <th><input name="course_name" type="text" class="input-regular" value="{{ $request['course_name'] ?? '' }}"></th>
                            <th><input name="company_name" type="text" class="input-regular" value="{{ $request['company_name'] ?? '' }}"></th>
                            <th>
                                <select id="avr_status" name="avr_status" class="input-regular chosen">
                                    <option value="">Все</option>
                                    <option value="1">Ожидающие подписания</option>
                                    <option value="2">Подписаны</option>
                                </select>
                                @if (!empty($request['avr_status'])) <script>document.getElementById('avr_status').value = {{ $request['avr_status'] ?? '' }};</script> @endif
                            </th>
                            <th>
                                <input name="sum" type="text" class="input-regular" value="{{ $request['sum'] ?? '' }}">
                            </th>
                            <th>
                                <input type="text" data-date-format="dd.mm.yyyy" id="avr_period" name="avr_period" value="{{ $request['avr_period'] ?? '' }}" placeholder="" class="input-regular custom-datepicker" autocomplete="off" data-value="{{ $request['avr_period'] ?? '' }}" data-range="true">

{{--                                @if (!empty($request['avr_period']))--}}
{{--                                            <?php--}}
{{--                                            $date = explode(',', $request['avr_period']);--}}
{{--                                            //--}}
{{--                                            ?>--}}
{{--                                            <script>--}}
{{--                                                var datepicker = $('#avr_period').datepicker().data('datepicker');--}}
{{--                                                datepicker.selectDate(new Date('01/07/2021'));--}}
{{--                                                datepicker.selectDate(new Date('01/08/2021'));--}}

{{--                                                // console.log(new Date('01/07/2021'));--}}
{{--                                            </script>--}}
{{--                                @endif--}}
                            </th>
                            <th>
                                <input type="text" data-date-format="dd.mm.yyyy" name="" disabled value="" placeholder="" class="input-regular custom-datepicker" autocomplete="off">
                            </th>
                            <th>
                                <input type="text" data-date-format="dd.mm.yyyy" name="" disabled value="" placeholder="" class="input-regular custom-datepicker" autocomplete="off">
                            </th>
                            <th>
                                <div class="buttons btn-group-sm">
                                    <a href="{{ route(Route::currentRouteName(), ['lang' => $lang]) }}" class="btn" style="color: #fff; background: #e2e2e2; text-decoration:none; height: 30px; margin-top: 2px; margin-right: 5px">Сбросить</a>
                                    <button class="btn">Поиск</button>
                                </div>
                            </th>
                        </tr>
                    </form>

                    @foreach($avr as $item)
                        <tr>
                            <td>{{ $item->contract->number ?? '-' }}</td>
                            <td>{{ $item->number }}</td>
                            <td>{{ $item->course->name }}</td>
                            <td>{{ $item->course->user->company_name }}</td>
                            <td>{{ $item->getStatusName() }}</td>
                            <td>{{ $item->sum }} ₸</td>
                            <td>{{ $item->start_at->format('d.m.Y') }}-{{ $item->end_at->format('d.m.Y') }}</td>
                            <td>{{ $item->getAuthorSignedAt() }}</td>
                            <td>{{ $item->getSignedAt() }}</td>
                            <td>
                                <div class="action-buttons">
{{--                                    @if (!empty($item->current_route))--}}
                                        <a target="_blank" href="{{ route('admin.avr.view', ['lang' => $lang, 'avr_id' => $item->id]) }}" title="{{ __('admin.labels.view') }}" class="icon-btn icon-btn--yellow icon-eye"></a>
{{--                                    @endif--}}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-right">
                {{ $avr->links('vendor.pagination.bootstrap') }}
            </div>
        </div>
    </div>

    @include('admin.v2.partials.modals.form_delete')
@endsection

@section('scripts')
    <?php
        if (!empty($request['avr_period'])) {
            $date = explode(',', $request['avr_period']);
            $start = \Carbon\Carbon::parse($date[0])->format('m/d/Y');
            $end = \Carbon\Carbon::parse($date[1])->format('m/d/Y');
        }
    ?>
    @if (!empty($request['avr_period']))
        <script>
            var datepicker = $('#avr_period').datepicker().data('datepicker');
            datepicker.selectDate(new Date('{{ $start }}'));
            datepicker.selectDate(new Date('{{ $end }}'));
        </script>
    @endif
@endsection
