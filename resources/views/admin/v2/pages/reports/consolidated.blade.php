@extends('admin.v2.layout.default.template')

@section('title', $title .' | '.__('admin.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li class="active">{{ $title }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')

        @include('admin.v2.partials.components.errors')
        <form class="block">
            <div class="row">
                <div class="col-sm-3">
                    <div class="input-group">
                        <label class="input-group__title">Дата записи на курс: от</label>
                        <label class="date">
                            <input
                                type="text"
                                data-date-format="dd.mm.yyyy"
                                name="date_course_from"
                                value="{{$request->date_course_from ?? ''}}"
                                placeholder=""
                                class="input-regular custom-datepicker"
                                autocomplete="off">
                        </label>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="input-group">
                        <label class="input-group__title">до</label>
                        <label class="date">
                            <input
                                type="text"
                                data-date-format="dd.mm.yyyy"
                                name="date_course_to"
                                value="{{$request->date_course_to ?? ''}}"
                                placeholder=""
                                class="input-regular custom-datepicker"
                                autocomplete="off">
                        </label>
                    </div>
                </div>
            </div>
            <div class="buttons">
                <div>
                    <button type="submit" class="btn btn--green">Искать</button>
                </div>
                <div>
                    <a href="/{{$lang}}/admin/reports/consolidated" class="btn btn--yellow">Сбросить</a>
                </div>
            </div>
        </form>

        <div class="block">
            <h2 class="title-secondary" style="float: left">{{ $title }}</h2>

            <table class="table records">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Категория</th>
                    <th>Количество</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>1</th>
                        <th>Всего записано на курс</th>
                        <th>{{ $all }}</th>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Количество лиц, начавших обучение</td>
                        <td>{{ $firstLesson }}</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Количесство лиц, прослушавших курс и не прошедших пороговый уровень итогового тестирования</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Количество лиц, получивших сертификат</td>
                        <td>{{ $allWithCert }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <th>5</th>
                        <th>Всего, имеющих статус безработного</th>
                        <th>{{ $unemployed }}</th>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Количество лиц, начавших обучение</td>
                        <td>{{ $unemployedFirstLesson }}</td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>Количество лиц, прослушавших курс и не прошедших пороговый уровень итогового тестирования</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>Количество лиц, получивших сертификаты</td>
                        <td>{{ $unemployedWithCert }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <th>9</th>
                        <th>Всего, не имеющих статус безработного</th>
                        <th>{{ $employed }}</th>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>Количество лиц, начавших обучение</td>
                        <td>{{ $employedFirstLesson }}</td>
                    </tr>
                    <tr>
                        <td>11</td>
                        <td>Количество лиц, прослушавших курс и не прошедших пороговый уровень итогового тестирования
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>12</td>
                        <td>Количество лиц, получивших сертификаты</td>
                        <td>{{ $employedWithCert }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
