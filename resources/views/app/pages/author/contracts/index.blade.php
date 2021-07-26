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
                            <th style="min-width: 200px;">{{ __('default.pages.contracts.contract_number') }}</th>
                            <th style="min-width: 230px;">{{ __('default.pages.contracts.course_name') }}</th>
                            <th style="min-width: 230px;">{{ __('default.pages.contracts.contract_status') }}</th>
                            <th style="min-width: 230px;">{{ __('default.pages.contracts.contract_type') }}</th>
                            <th style="min-width: 33px;">{{ __('default.pages.courses.quota') }}</th>
                            <th style="min-width: 96px;">{{ __('default.pages.contracts.contract') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <form action="">
                                <td>
                                    <input type="text" name="contract_number" placeholder="{{ __('default.pages.contracts.contract_number') }}" class="input-regular" value="{{ $request['contract_number'] ?? '' }}">
                                </td>
                                <td>
                                    <input type="text" name="course_name" placeholder="{{ __('default.pages.contracts.course_name') }}" class="input-regular" value="{{ $request['course_name'] ?? '' }}">
                                </td>
                                <td></td>
                                <td>
                                    <select name="contract_type" id="contract_type" type="text" class="selectize-regular no-search">
                                        <option value="">{{ __('default.pages.contracts.choose_type') }}</option>
                                        <option value="1">{{ __('default.pages.contracts.free') }}</option>
                                        <option value="2">{{ __('default.pages.contracts.paid') }}</option>
                                        <option value="3">{{ __('default.pages.contracts.quota') }}</option>
                                    </select>
                                    <script>document.getElementById('contract_type').value = {{ $request['contract_type'] ?? '' }};</script>
                                </td>
                                <td>
                                    <select name="contract_quota" id="contract_quota" type="text" class="selectize-regular no-search">
                                        <option value="">{{ __('default.pages.contracts.choose_avail') }}</option>
                                        <option value="1">{{ __('default.pages.contracts.yes') }}</option>
                                        <option value="2">{{ __('default.pages.contracts.no') }}</option>
                                    </select>
                                    <script>document.getElementById('contract_quota').value = {{ $request['contract_quota'] ?? '' }};</script>
                                </td>
                                <td>
                                    <a class="btn" style="background: #e2e2e2; color: #333" href="{{ route(Route::currentRouteName(), ['lang' => $lang]) }}">{{ __('default.pages.contracts.reset') }}</a>
                                    <button class="btn">{{ __('default.pages.contracts.search') }}</button>
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
                                    <a href="{{ route('author.contracts.view', ['lang' => $lang, 'contract_id' => $contract->id]) }}">{{ __('default.pages.contracts.view') }}</a>
                                    <br />
                                    <a href="{{ route('author.contracts.download', ['lang' => $lang, 'contract_id' => $contract->id]) }}">{{ __('default.pages.contracts.download') }}</a>
{{--                                    @if (!empty($contract->link) && pathinfo($contract->link)['extension'] === 'pdf')--}}
{{--                                        <a href="{{ route('author.contracts.download', ['lang' => $lang, 'contract_id' => $contract->id]) }}">Скачать</a>--}}
{{--                                    @endif--}}
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

