@extends('app.layout.default.template')

@section('content')
    <main class="main">
        <section class="plain">
            <div class="container container--fluid">
                <h1 class="title-primary">{{ __('default.pages.courses.my_contracts_title') }}</h1>
            </div>
            <div class="scroll-x">
                <div class="scroll-x__inner">
                    <table class="report">
                        <thead>
                        <tr>
                            <th style="min-width: 200px;">Номер договора</th>
                            <th style="min-width: 230px;">Наименование курса</th>
                            <th style="min-width: 230px;">Статус договора</th>
                            <th style="min-width: 230px;">Тип договора</th>
                            <th style="min-width: 33px;">Доступен по квоте</th>
                            <th style="min-width: 96px;">Договор</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($contracts as $contract)
                            <tr>
                                <td>{{ $contract->number }}</td>
                                <td>{{ $contract->course->name }}</td>
                                <td>{{ $contract->getStatusName() }}</td>
                                <td>{{ $contract->getTypeName() }}</td>
                                <td>{{ $contract->isQuota() ? 'Да' : 'Нет' }}</td>
                                <td>
                                    <a href="{{ route('author.contracts.download', ['lang' => $lang, 'contract_id' => $contract->id]) }}">Скачать</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="container container--fluid">

                <div class="text-center">
                    {{ $contracts->links('vendor.pagination.default') }}
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')

@endsection

