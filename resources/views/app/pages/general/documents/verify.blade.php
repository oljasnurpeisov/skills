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
                                <table class="table table-bordered" style="width: 100%">
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
                                                <p>Подписано: {{ $signature->getCertificate()->personName }}</p>
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
        </section>
    </main>
@endsection
