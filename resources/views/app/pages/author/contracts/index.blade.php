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
                                    <input type="text" name="contract_number" placeholder="Номер договора" class="input-regular" value="{{ $request['contract_number'] ?? '' }}">
                                </td>
                                <td>
                                    <input type="text" name="course_name" placeholder="Наименование курса" class="input-regular" value="{{ $request['course_name'] ?? '' }}">
                                </td>
                                <td></td>
                                <td>
                                    <select name="contract_type" id="contract_type" type="text" class="selectize-regular no-search">
                                        <option value="">Выберите тип</option>
                                        <option value="1">Бесплатный</option>
                                        <option value="2">Платный</option>
                                        <option value="3">При гос. поддержке</option>
                                    </select>
                                    <script>document.getElementById('contract_type').value = {{ $request['contract_type'] ?? '' }};</script>
                                </td>
                                <td>
                                    <select name="contract_quota" id="contract_quota" type="text" class="selectize-regular no-search">
                                        <option value="">Выберите доступность</option>
                                        <option value="1">Да</option>
                                        <option value="2">Нет</option>
                                    </select>
                                    <script>document.getElementById('contract_quota').value = {{ $request['contract_quota'] ?? '' }};</script>
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

