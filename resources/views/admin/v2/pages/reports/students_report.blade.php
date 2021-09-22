@extends('admin.v2.layout.default.template')

@section('title',__('admin.pages.reports.title').' | '.__('admin.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li class="active">{{ __('admin.pages.reports.students_report') }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')

        @include('admin.v2.partials.components.errors')

        <form class="block">
            <div class="input-group">
                <label class="input-group__title">Поиск по ФИО</label>
                <input type="text" name="student_name" placeholder="" class="input-regular" value="{{$request->student_name}}">
            </div>
            <div class="collapse-block collapsed" style="display: none;" id="collapse1">
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <label class="input-group__title">Количество оставшихся квот: от</label>
                            <label class="">
                                <input type="number" name="quota_count_from" placeholder=""
                                       class="input-regular" value="{{$request->quota_count_from}}">
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label class="input-group__title">до</label>
                            <label class="">
                                <input type="number" name="quota_count_to" placeholder=""
                                       class="input-regular" value="{{$request->quota_count_to}}">
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <label class="input-group__title">&nbsp;Статус обучающегося</label>
                            <div class="input-group">
                                <label class="checkbox">
                                    <input type="checkbox" name="unemployed_status[]"
                                           value="0" {{in_array(0, $unemployed_status) ? 'checked' : ''}}>
                                    <span>Безработный</span>
                                </label>
                            </div>
                            <div class="input-group">
                                <label class="checkbox">
                                    <input type="checkbox" name="unemployed_status[]"
                                           value="1" {{in_array(1, $unemployed_status) ? 'checked' : ''}}>
                                    <span>Работающий</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group__title">Количество курсов: от</label>
                                    <label class="">
                                        <input type="number" name="courses_count_from" placeholder=""
                                               class="input-regular" value="{{$request->courses_count_from}}">
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group__title">до</label>
                                    <label class="">
                                        <input type="number" name="courses_count_to" placeholder=""
                                               class="input-regular" value="{{$request->courses_count_to}}">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group__title">Количество сертфикатов: от</label>
                                    <label class="">
                                        <input type="number" name="certificates_count_from" placeholder=""
                                               class="input-regular" value="{{$request->certificates_count_from}}">
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group__title">до</label>
                                    <label class="">
                                        <input type="number" name="certificates_count_to" placeholder=""
                                               class="input-regular" value="{{$request->certificates_count_to}}">
                                    </label>
                                </div>
                            </div>
                        </div>
{{--                        <div class="row">--}}
{{--                            <div class="col-sm-6">--}}
{{--                                <div class="input-group">--}}
{{--                                    <label class="input-group__title">Количество квалификаций: от</label>--}}
{{--                                    <label class="">--}}
{{--                                        <input type="number" name="qualifications_count_from" placeholder=""--}}
{{--                                               class="input-regular" value="{{$request->qualifications_count_from}}">--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-sm-6">--}}
{{--                                <div class="input-group">--}}
{{--                                    <label class="input-group__title">до</label>--}}
{{--                                    <label class="">--}}
{{--                                        <input type="number" name="qualifications_count_to" placeholder=""--}}
{{--                                               class="input-regular" value="{{$request->qualifications_count_to}}">--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
            <div class="input-group">
                <a href="javascript:;" title="Расширенный фильтр" class="grey-link small collapse-btn"
                   data-target="collapse1">Расширенный фильтр</a></div>
            <div class="buttons">
                <div>
                    <button type="submit" class="btn btn--green">Искать</button>
                </div>
                <div>
                    <a href="/{{$lang}}/admin/reports/students" class="btn btn--yellow">Сбросить</a>
                </div>
                <div>
                    <a href="/{{$lang}}/admin/export-students-report" class="btn btn--blue">Экспорт</a>
                </div>
            </div>
        </form>


        <div class="block">
            <h2 class="title-secondary">{{__('admin.pages.reports.students_report')}}</h2>
            <table class="table records">
                <colgroup>
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 20%;">
                    <col span="1" style="width: 15%;">
                    <col span="1" style="width: 10%;">
                    <col span="1" style="width: 15%;">
                </colgroup>
                <thead>
                <tr>
                    <th><a href="?sortByName={{$request->sortByName == 'asc' ? 'desc' : 'asc'}}">{{__('admin.pages.reports.name_student')}}</a></th>
                    <th>{{__('admin.pages.reports.unemployed')}}</th>
                    <th><a href="?sortByQuota={{$request->sortByQuota == 'asc' ? 'desc' : 'asc'}}">{{__('admin.pages.reports.quotas_count')}}</a></th>
                    <th>{{__('admin.pages.reports.student_courses_count')}}</th>
                    <th>{{__('admin.pages.reports.student_certificates_count')}}</th>
{{--                    <th>{{__('admin.pages.reports.student_qualifications_count')}}</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{$item->student_info->name}}</td>
                        <td>{{ $item->student_info->unemployed_status == 0 ? __('default.yes_title') : __('default.no_title') }}</td>
                        <td>{{$item->student_info->quota_count}}</td>
                        <td>{{$item->student_course->whereIn('paid_status', [1,2,3])->count()}}</td>
                        <td>{{$item->student_course->where('is_finished', '=', true)->count()}}</td>
{{--                        <td>{{$item->qualifications_count}}</td>--}}
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-right">
                {{ $items->appends([])->links('vendor.pagination.bootstrap') }}
            </div>
        </div>
    </div>

@endsection

@section('scripts')

@endsection
