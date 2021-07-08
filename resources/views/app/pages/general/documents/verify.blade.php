@extends('app.layout.default.template')

@section('content')
    <main class="main">
        <section class="plain">
            <div class="container">
                <div class="row row--multiline">
                    <div class="col-md-12">
                        <div class="article">
                            <div class="article-section">
                                <h1 class="page-title">Сведения об электронном документе</h1>
                            </div>
                            <div class="article-section">
                                <div class="hidden-xs">
                                    <table class="table table-bordered hidden-xs" style="width: 100%">
                                        <tr>
                                            <th class="col-md-3">
                                                Тип документа
                                            </th>
                                            <td class="col-md-9">
                                                {{ $document->type->name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-3">
                                                Номер документа
                                            </th>
                                            <td class="col-md-9">
                                                {{ $model->number }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-3">
                                                Уникальный номер
                                            </th>
                                            <td class="col-md-9">
                                                {{ $document->number }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="col-md-3">
                                                Электронные цифровые подписи
                                            </th>
                                            <td class="col-md-9">
                                                @foreach($document->signatures as $signature)
                                                    <p>
                                                        {{ $signature->getCertificate()->legalName ? $signature->getCertificate()->legalName : $signature->getCertificate()->personName }}
                                                    </p>
                                                    <p>Подписано: {{ $signature->user->role ? $signature->user->role->role->name : '' }} {{ $signature->getCertificate()->personName }}</p>
                                                    <p>Дата подписания: {{ $signature->created_at }}</p>
                                                    <hr />
                                                @endforeach
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="hidden-lg hidden-md hidden-sm visible-xs">
                                    <table class="table table-bordered hidden-lg hidden-md hidden-sm visible-xs" style="width: 100%">
                                        <tr>
                                            <td>
                                                <strong>Тип документа</strong>
                                                <br />
                                                {{ $document->type->name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Номер документа</strong>
                                                <br />
                                                {{ $model->number }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Уникальный номер</strong>
                                                <br />
                                                {{ $document->number }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Электронные цифровые подписи</strong>
                                                <br />
                                                <br />
                                                <br />
                                                @foreach($document->signatures as $signature)
                                                    <p>
                                                        {{ $signature->getCertificate()->legalName ? $signature->getCertificate()->legalName : $signature->getCertificate()->personName }}
                                                    </p>
                                                    <p>Подписано: {{ $signature->getCertificate()->personName }} / {{ $signature->user->position_ru ?: ($signature->user->role ? $signature->user->role->role->name : '') }} /</p>
                                                    <p>Дата подписания: {{ $signature->created_at }}</p>
                                                    <hr />
                                                @endforeach
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
