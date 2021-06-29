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
                        <tr>
                            <form action="">
                                <td>
                                    <input type="text" name="contract_number" placeholder="Номер договора" class="input-regular" value="">
                                </td>
                                <td>
                                    <input type="text" name="course_name" placeholder="Наименование курса" class="input-regular" value="">
                                </td>
                                <td></td>
                                <td>
                                    <select name="contract_type" type="text" class="selectize-regular no-search">
                                        <option value="">Бесплатный</option>
                                        <option value="">Платный</option>
                                        <option value="">При гос. поддержке</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="quota" type="text" class="selectize-regular no-search">
                                        <option value="">Да</option>
                                        <option value="">Нет</option>
                                    </select>
                                </td>
                                <td>
                                    <a class="btn" style="background: #e2e2e2; color: #333">Сбросить</a>
                                    <button class="btn">Поиск</button>
                                </td>
                            </form>
                        </tr>
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

