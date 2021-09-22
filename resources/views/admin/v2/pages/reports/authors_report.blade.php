@extends('admin.v2.layout.default.template')

@section('title',__('admin.pages.reports.title').' | '.__('admin.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li class="active">{{ __('admin.pages.reports.authors_report') }}</li>
        </ul>

        <form class="block">
            <div class="input-group">
                <label class="input-group__title">Поисковая по ФИО</label>
                <input type="text" name="author_name" value="{{$request->author_name}}" placeholder="" class="input-regular">
            </div>
            <div class="collapse-block collapsed" style="display: none;" id="collapse1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="input-group__title">Поиск по специализации</label>
                            <input type="text" name="specialization" value="{{$request->specialization}}" placeholder="" class="input-regular">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <label class="input-group__title">Рейтинг: от</label>
                            <label class="">
                                <input type="number" name="rate_from" placeholder=""
                                       class="input-regular" value="{{$request->rate_from}}">
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <label class="input-group__title">до</label>
                            <label class="">
                                <input type="number" name="rate_to" placeholder=""
                                       class="input-regular" value="{{$request->rate_to}}">
                            </label>
                        </div>
                    </div>
                </div>
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
                    <div class="col-sm-6">
                        <div class="input-group">
                            <label class="input-group__title">Количество платных курсов: от</label>
                            <label class="">
                                <input type="number" name="paid_courses_count_from" placeholder=""
                                       class="input-regular" value="{{$request->paid_courses_count_from}}">
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <label class="input-group__title">до</label>
                            <label class="">
                                <input type="number" name="paid_courses_count_to" placeholder=""
                                       class="input-regular" value="{{$request->paid_courses_count_to}}">
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <label class="input-group__title">Количество бесплатных курсов: от</label>
                            <label class="">
                                <input type="number" name="free_courses_count_from" placeholder=""
                                       class="input-regular" value="{{$request->free_courses_count_from}}">
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <label class="input-group__title">до</label>
                            <label class="">
                                <input type="number" name="free_courses_count_to" placeholder=""
                                       class="input-regular" value="{{$request->free_courses_count_to}}">
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <label class="input-group__title">Количество доступных по квоте: от</label>
                            <label class="">
                                <input type="number" name="quota_courses_count_from" placeholder=""
                                       class="input-regular" value="{{$request->quota_courses_count_from}}">
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <label class="input-group__title">до</label>
                            <label class="">
                                <input type="number" name="quota_courses_count_to" placeholder=""
                                       class="input-regular" value="{{$request->quota_courses_count_to}}">
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <label class="input-group__title">Количество обучающихся: от</label>
                            <label class="">
                                <input type="number" name="course_members_count_from" placeholder=""
                                       class="input-regular" value="{{$request->course_members_count_from}}">
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <label class="input-group__title">до</label>
                            <label class="">
                                <input type="number" name="course_members_count_to" placeholder=""
                                       class="input-regular" value="{{$request->course_members_count_to}}">
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
            </div>
            <div class="input-group">
                <a href="javascript:;" title="Расширенный фильтр" class="grey-link small collapse-btn"
                   data-target="collapse1">Расширенный фильтр</a></div>
            <div class="buttons">
                <div>
                    <button class="btn btn--green">Искать</button>
                </div>
                <div>
                    <a href="/{{$lang}}/admin/reports/authors" class="btn btn--yellow">Сбросить</a>
                </div>
                <div>
                    <a href="/{{$lang}}/admin/export-authors-report" class="btn btn--blue">Экспорт</a>
                </div>
            </div>
        </form>

        <div class="block">
            <h2 class="title-secondary">{{__('admin.pages.reports.authors_report')}}</h2>
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
                    <th><a href="{{request()->fullUrlWithQuery(["sortByName"=>$request->sortByName == 'asc' ? 'desc' : 'asc'])}}">{{__('admin.pages.reports.name_title')}}</a></th>
                    <th>{{__('admin.pages.reports.specialization')}}</th>
                    <th><a href="?sortByRateAuthor={{$request->sortByRateAuthor == 'asc' ? 'desc' : 'asc'}}">{{__('admin.pages.reports.rating')}}</a></th>
                    <th><a href="?sortByCoursesCount={{$request->sortByCoursesCount == 'asc' ? 'desc' : 'asc'}}">{{__('admin.pages.reports.courses_count')}}</a></th>
                    <th><a href="?sortByPaidCoursesCount={{$request->sortByPaidCoursesCount == 'asc' ? 'desc' : 'asc'}}">{{__('admin.pages.reports.courses_paid_count')}}</a></th>
                    <th><a href="?sortByFreeCoursesCount={{$request->sortByFreeCoursesCount == 'asc' ? 'desc' : 'asc'}}">{{__('admin.pages.reports.courses_free_count')}}</a></th>
                    <th><a href="?sortByQuotaCoursesCount={{$request->sortByQuotaCoursesCount == 'asc' ? 'desc' : 'asc'}}">{{__('admin.pages.reports.courses_by_quota_count')}}</a></th>
                    <th><a href="?sortByStudentsCount={{$request->sortByStudentsCount == 'asc' ? 'desc' : 'asc'}}">{{__('admin.pages.reports.courses_students_count')}}</a></th>
                    <th><a href="?sortByCertificateStudentsCount={{$request->sortByCertificateStudentsCount == 'asc' ? 'desc' : 'asc'}}">{{__('admin.pages.reports.courses_certificates_students_count')}}</th>
{{--                    <th>{{__('admin.pages.reports.courses_students_confirm_qualification_count')}}</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{$item->author_info->name . ' ' . $item->author_info->surname ?? ''}}</td>
                        <td>{{ implode(', ', json_decode($item->author_info->specialization) ?? []) }}</td>
                        <td>{{round($item->average_rates, 1)}}</td>
                        <td>{{$item->courses->count()}}</td>
                        <td>{{$item->courses->where('is_paid', '=', true)->count()}}</td>
                        <td>{{$item->courses->where('is_paid', '=', false)->count()}}</td>
                        <td>{{$item->courses->where('quota_status', '=', 2)->count()}}</td>
                        <td>{{count($item->members) ?? 0}}</td>
                        <td>{{count($item->certificate_members) ?? 0}}</td>
{{--                        <td>{{$item->qualification_students ?? 0}}</td>--}}
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-right">
                {{ $items->appends($inputs)->links('vendor.pagination.bootstrap') }}
            </div>
        </div>
    </div>

@endsection

@section('scripts')

@endsection
