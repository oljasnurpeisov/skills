@extends('app.layout.default.template')

@section('content')
    <main class="main">
        <section class="plain">
            <div class="container container--fluid">
                <h1 class="title-primary">{{ __('default.pages.courses.my_avr_title') }}</h1>
            </div>
            <div class="scroll-x">
                <div class="scroll-x__inner">
                    <table class="report">
                        <thead>
                        <tr>
                            <th style="min-width: 100px;">Номер договора</th>
                            <th style="min-width: 100px;">Номер АВР</th>
                            <th style="min-width: 150px;">Наименование курса</th>
                            <th style="min-width: 230px;">Период в АВР</th>
                            <th style="min-width: 110px;">Сумма в АВР</th>
                            <th style="min-width: 130px;">Статус подписания</th>
                            <th style="min-width: 296px;">АВР</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <form action="">
                                <td>
                                    <input type="text" name="contract_number" placeholder="Номер договора" class="input-regular" value="{{ $request['contract_number'] ?? '' }}">
                                </td>
                                <td>
                                    <input type="text" name="avr_number" placeholder="Номер АВР" class="input-regular" value="{{ $request['avr_number'] ?? '' }}">
                                </td>
                                <td>
                                    <input type="text" name="course_name" placeholder="Наименование курса" class="input-regular" value="{{ $request['course_name'] ?? '' }}">
                                </td>
                                <td>
                                    <input type="text" data-date-format="dd.mm.yyyy" id="avr_period" name="avr_period" value="{{ $request['avr_period'] ?? '' }}" placeholder="Период" class="input-regular" autocomplete="off" data-range="true">
                                </td>
                                <td>
                                    <input type="text" name="sum" placeholder="Сумма" class="input-regular" value="{{ $request['sum'] ?? '' }}">
                                </td>
                                <td>
                                    <select name="avr_status" id="avr_status" type="text" class="selectize-regular no-search">
                                        <option value="">Выберите статус</option>
                                        <option value="1">На подписании</option>
                                        <option value="2">Подписан</option>
                                    </select>
                                    <script>document.getElementById('avr_status').value = {{ $request['avr_status'] ?? '""' }};</script>
                                </td>
                                <td>
                                    <a class="btn" style="background: #e2e2e2; color: #333" href="{{ route(Route::currentRouteName(), ['lang' => $lang]) }}">Сбросить</a>
                                    <button class="btn">Поиск</button>
                                </td>
                            </form>
                        </tr>
                        @foreach ($avrs as $avr)
                            <tr>
                                <td>{{ $avr->contract->number ?? '-' }}</td>
                                <td>{{ $avr->number }}</td>
                                <td>{{ $avr->course->name }}</td>
                                <td>{{ $avr->start_at->format('d.m.Y') }} – {{ $avr->end_at->format('d.m.Y') }}</td>
                                <td>{{ number_format($avr->sum, 2, '.', ' ') }} ₸</td>
                                <td>{{ $avr->getStatusName() }}</td>
                                <td>
                                    @if (!empty($avr->link) && pathinfo($avr->link)['extension'] === 'pdf')
                                        <a href="{{ route('author.avr.download', ['lang' => $lang, 'avr_id' => $avr->id]) }}">Скачать</a>
                                    @else
                                        <a href="{{ route('author.avr.view', ['lang' => $lang, 'avr_id' => $avr->id]) }}">Просмотр</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="container container--fluid">

                <div class="text-center">
                    {{ $avrs->links('vendor.pagination.default') }}
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
    <script>
        $('#avr_period').datepicker();
    </script>
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

