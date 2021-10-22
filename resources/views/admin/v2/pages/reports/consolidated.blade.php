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
                <div>
                    <a href="/{{$lang}}/admin/export-consolidated-report" class="btn btn--blue">Экспорт</a>
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
                        <th>{{ $data['all']['num'] }}</th>
                        <th>{{ $data['all']['title'] }}</th>
                        <th>{{ $data['all']['count'] }}</th>
                    </tr>
                    <tr>
                        <td>{{ $data['firstLesson']['num'] }}</td>
                        <td>{{ $data['firstLesson']['title'] }}</td>
                        <td>{{ $data['firstLesson']['count'] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $data['didNotPass']['num'] }}</td>
                        <td>{{ $data['didNotPass']['title'] }}</td>
                        <td>{{ $data['didNotPass']['count'] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $data['allWithCert']['num'] }}</td>
                        <td>{{ $data['allWithCert']['title'] }}</td>
                        <td>{{ $data['allWithCert']['count'] }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <th>{{ $data['unemployed']['num'] }}</th>
                        <th>{{ $data['unemployed']['title'] }}</th>
                        <th>{{ $data['unemployed']['count'] }}</th>
                    </tr>
                    <tr>
                        <td>{{ $data['unemployedFirstLesson']['num'] }}</td>
                        <td>{{ $data['unemployedFirstLesson']['title'] }}</td>
                        <td>{{ $data['unemployedFirstLesson']['count'] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $data['unemployedDidNotPass']['num'] }}</td>
                        <td>{{ $data['unemployedDidNotPass']['title'] }}</td>
                        <td>{{ $data['unemployedDidNotPass']['count'] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $data['unemployedWithCert']['num'] }}</td>
                        <td>{{ $data['unemployedWithCert']['title'] }}</td>
                        <td>{{ $data['unemployedWithCert']['count'] }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <th>{{ $data['employed']['num'] }}</th>
                        <th>{{ $data['employed']['title'] }}</th>
                        <th>{{ $data['employed']['count'] }}</th>
                    </tr>
                    <tr>
                        <td>{{ $data['employedFirstLesson']['num'] }}</td>
                        <td>{{ $data['employedFirstLesson']['title'] }}</td>
                        <td>{{ $data['employedFirstLesson']['count'] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $data['employedDidNotPass']['num'] }}</td>
                        <td>{{ $data['employedDidNotPass']['title'] }}</td>
                        <td>{{ $data['employedDidNotPass']['count'] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $data['employedWithCert']['num'] }}</td>
                        <td>{{ $data['employedWithCert']['title'] }}</td>
                        <td>{{ $data['employedWithCert']['count'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
