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
                    <col span="1" style="width: 5%;">
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 7%;">
                    <col span="1" style="width: 8%;">
                    <col span="1" style="width: 7%;">
                    <col span="1" style="width: 15%;">
                    <col span="1" style="width: 10%;">
                    <col span="1" style="width: 10%;">
                    <col span="1" style="width: 12%;">
                </colgroup>
                <thead>
                <tr>
                    <th>Номер договора</th>
                    <th>Номер АВР</th>
                    <th>Название курса</th>
                    <th>Название организации</th>
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
                            <input name="sum" type="text" class="input-regular" value="{{ $request['sum'] ?? '' }}">
                        </th>
                        <th>
                            <input type="text" data-date-format="dd.mm.yyyy" id="avr_period" name="avr_period" value="{{ $request['avr_period'] ?? '' }}" placeholder="" class="input-regular custom-datepicker" autocomplete="off" data-value="{{ $request['avr_period'] ?? '' }}" data-range="true">
                        </th>
                        <th>
                            <input type="text" data-date-format="dd.mm.yyyy" id="author_signed_at" name="author_signed_at" value="{{ $request['author_signed_at'] ?? '' }}" placeholder="" class="input-regular custom-datepicker" autocomplete="off" data-value="" data-range="true">
                        </th>
                        <th>
                            <input type="text" data-date-format="dd.mm.yyyy" id="signed_at" name="signed_at" value="{{ $request['signed_at'] ?? '' }}" placeholder="" class="input-regular custom-datepicker" autocomplete="off" data-value="" data-range="true">
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
                        <td>{{ $item->sum }} ₸</td>
                        <td>{{ $item->start_at->format('d.m.Y') }}-{{ $item->end_at->format('d.m.Y') }}</td>
                        <td>{{ !empty($item->author_signed_at) ? $item->author_signed_at->format('d.m.Y H:i:s') : '-' }}</td>
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

@endsection

@section('scripts')
    <?php
        if (!empty($request['avr_period'])) {
            $date = explode(',', $request['avr_period']);
            $start_a = \Carbon\Carbon::parse($date[0])->format('m/d/Y');
            $end_a = \Carbon\Carbon::parse($date[1])->format('m/d/Y');
        }
        if (!empty($request['signed_at'])) {
            $date = explode(',', $request['signed_at']);
            $start_s = \Carbon\Carbon::parse($date[0])->format('m/d/Y');
            $end_s = \Carbon\Carbon::parse($date[1])->format('m/d/Y');
        }
        if (!empty($request['author_signed_at'])) {
            $date = explode(',', $request['author_signed_at']);
            $start_as = \Carbon\Carbon::parse($date[0])->format('m/d/Y');
            $end_as = \Carbon\Carbon::parse($date[1])->format('m/d/Y');
        }
    ?>
    @if (!empty($request['avr_period']))
        <script>
            var datepicker = $('#avr_period').datepicker().data('datepicker');
            datepicker.selectDate(new Date('{{ $start_a }}'));
            datepicker.selectDate(new Date('{{ $end_a }}'));
        </script>
    @endif
    @if (!empty($request['signed_at']))
        <script>
            var datepicker = $('#signed_at').datepicker().data('datepicker');
            datepicker.selectDate(new Date('{{ $start_s }}'));
            datepicker.selectDate(new Date('{{ $end_s }}'));
        </script>
    @endif
    @if (!empty($request['author_signed_at']))
        <script>
            var datepicker = $('#author_signed_at').datepicker().data('datepicker');
            datepicker.selectDate(new Date('{{ $start_as }}'));
            datepicker.selectDate(new Date('{{ $end_as }}'));
        </script>
    @endif
@endsection
