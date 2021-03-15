@extends('admin.v2.layout.default.template')

@section('title',__('admin.pages.reports.title').' | '.__('admin.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li class="active">{{ __('admin.pages.reports.courses_report') }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')

        @include('admin.v2.partials.components.errors')

        <form class="block">
            <div class="input-group">
                <label class="input-group__title">Поиск по наименованию курса</label>
                <input type="text" name="course_name" value="{{$request->course_name}}" placeholder=""
                       class="input-regular">
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="input-group">
                        <label class="input-group__title">Дата начала отчетного периода:</label>
                        <label class="date">
                            <input type="text" data-date-format="dd.mm.yyyy" name="date_from" value="{{$request->date_from}}" placeholder="" class="input-regular custom-datepicker" autocomplete="off">
                        </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <label class="input-group__title">Дата окончания отчетного периода:</label>
                        <label class="date">
                            <input type="text" data-date-format="dd.mm.yyyy" name="date_to" value="{{$request->date_to}}" placeholder="" class="input-regular custom-datepicker" autocomplete="off">
                        </label>
                    </div>
                </div>
            </div>
            <div class="collapse-block collapsed" style="display: none;" id="collapse1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="input-group__title">Поиск по наименованию автора</label>
                            <input type="text" name="author_name" value="{{$request->author_name}}" placeholder=""
                                   class="input-regular">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group__title">Стоимсоть курса: от</label>
                                    <label class="">
                                        <input type="number" name="cost_from" placeholder=""
                                               class="input-regular" value="{{$request->cost_from}}">
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group__title">до</label>
                                    <label class="">
                                        <input type="number" name="cost_to" placeholder=""
                                               class="input-regular" value="{{$request->cost_to}}">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="input-group__title">Статус курса</label>
                            <select name="course_status" class="input-regular chosen" data-placeholder=" ">
                                <option value="" selected hidden></option>
                                <option value="1"{{$request->course_status == 1 ? 'selected' : ''}}>На модерации
                                </option>
                                <option value="3"{{$request->course_status == 3 ? 'selected' : ''}}>Опубликован</option>
                                <option value="0"{{is_null($request->course_status) == 0 ? 'selected' : ''}}>Черновик
                                </option>
                                <option value="4"{{$request->course_status == 4 ? 'selected' : ''}}>Удален</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group">
                            <label class="input-group__title">Поиск по навыкам</label>
                            <input type="text" name="skill" value="{{$request->skill}}" placeholder="" class="input-regular">
                        </div>
                    </div>
{{--                    <div class="col-md-12">--}}
{{--                        <div class="input-group">--}}
{{--                            <label class="input-group__title">Поиск по профессии</label>--}}
{{--                            <select name="course_professions" class="input-regular chosen" data-placeholder="{{__('default.pages.courses.choose_profession')}}">--}}
{{--                                @if(!empty($request->specialities))--}}
{{--                                    @foreach($professions as $profession)--}}
{{--                                        <option value="{{$profession->id}}"--}}
{{--                                                selected>{{$profession->getAttribute('name_'.$lang ?? 'name_ru')}}</option>--}}
{{--                                    @endforeach--}}
{{--                                @endif--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <label class="input-group__title">Рейтинг курса: от</label>
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
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <label class="input-group__title">&nbsp;Доступен по квоте</label>
                            <div class="input-group">
                                <label class="checkbox">
                                    <input type="checkbox" name="quota_status[]"
                                           value="2" {{in_array(2, $quota_status) ? 'checked' : ''}}>
                                    <span>Да</span>
                                </label>
                            </div>
                            <div class="input-group">
                                <label class="checkbox">
                                    <input type="checkbox" name="quota_status[]"
                                           value="0" {{in_array(0, $quota_status) ? 'checked' : ''}}>
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <label class="input-group__title">&nbsp;Платежный статус</label>
                            <div class="input-group">
                                <label class="checkbox">
                                    <input type="checkbox" name="paid_status[]"
                                           value="1" {{in_array(1, $paid_status) ? 'checked' : ''}}>
                                    <span>Платный</span>
                                </label>
                            </div>
                            <div class="input-group">
                                <label class="checkbox">
                                    <input type="checkbox" name="paid_status[]"
                                           value="0" {{in_array(0, $paid_status) ? 'checked' : ''}}>
                                    <span>Бесплатный</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
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
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <label class="input-group__title">Количество квалификаций: от</label>
                            <label class="">
                                <input type="number" name="qualifications_count_from" placeholder=""
                                       class="input-regular" value="{{$request->qualifications_count_from}}">
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <label class="input-group__title">до</label>
                            <label class="">
                                <input type="number" name="qualifications_count_to" placeholder=""
                                       class="input-regular" value="{{$request->qualifications_count_to}}">
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
                    <a href="/{{$lang}}/admin/reports/courses" class="btn btn--yellow">Сбросить</a>
                </div>
                <div>
                    <a href="/{{$lang}}/admin/export-courses-report" class="btn btn--blue">Экспорт</a>
                </div>
            </div>
        </form>

        <div class="block">
            <h2 class="title-secondary">{{__('admin.pages.reports.courses_report')}}</h2>
            <table class="table records " style="display: block;
    overflow-x: auto; max-width: 1550px">
                <colgroup>
                    <col span="1" style="width: 15%;">
                    <col span="1" style="width: 15%;">
                    <col span="1" style="width: 15%;">
                    <col span="1" style="width: 10%;">
                    <col span="1" style="width: 10%;">
                    <col span="1" style="width: 10%;">
                    <col span="1" style="width: 10%;">
                    <col span="1" style="width: 15%;">
                    <col span="1" style="width: 10%;">
                    <col span="1" style="width: 10%;">
                    <col span="1" style="width: 10%;">
                    <col span="1" style="width: 10%;">
                </colgroup>
                <thead>
                <tr>
{{--                    <th><a href="{{request()->fullUrlWithQuery(["sortByName"=>$request->sortByName == 'asc' ? 'desc' : 'asc'])}}">{{__('admin.pages.reports.course_name')}} {{$request->sortByName == 'asc' ? '↑' : ($request->sortByName == 'desc' ? '↓' : '')}}</a></th>--}}
                    <th><a href="{{request()->fullUrlWithQuery(["sortByName"=>$request->sortByName == 'asc' ? 'desc' : 'asc'])}}">{{__('admin.pages.reports.course_name')}}</a></th>
                    <th><a href="{{request()->fullUrlWithQuery(["sortByAuthorName"=>$request->sortByAuthorName == 'asc' ? 'desc' : 'asc'])}}">{{__('admin.pages.reports.author_name')}}</th>
                    <th style="min-width: 230px;">{{__('default.pages.reporting.professional_area')}}</th>
                    <th style="min-width: 230px;">{{__('default.pages.reporting.profession')}}</th>
                    <th style="min-width: 230px;">{{__('default.pages.reporting.skills')}}</th>
                    <th style="min-width: 33px;"><a href="?sortByRateCourse={{$request->sortByRateCourse == 'asc' ? 'desc' : 'asc'}}">{{__('default.pages.reporting.course_rate')}}</a></th>
                    <th style="min-width: 96px;">{{__('default.pages.reporting.course_status')}}</th>
                    <th style="min-width: 96px;">{{__('default.pages.reporting.course_type')}}</th>
                    <th style="min-width: 96px;"><a href="?sortByCost={{$request->sortByCost == 'asc' ? 'desc' : 'asc'}}">{{__('default.pages.reporting.course_cost')}}</a></th>
                    <th style="min-width: 33px;">{{__('default.pages.reporting.is_quota')}}</th>
                    <th style="min-width: 96px;">{{__('default.pages.reporting.cost_by_quota')}}</th>
                    <th style="min-width: 96px;">{{__('default.pages.reporting.members_free')}}</th>
                    <th style="min-width: 96px;">{{__('default.pages.reporting.certificate_free')}}</th>
{{--                    <th style="min-width: 96px;">{{__('default.pages.reporting.qualificated_free')}}</th>--}}
                    <th style="min-width: 96px;">{{__('default.pages.reporting.members_paid')}}</th>
                    <th style="min-width: 96px;">{{__('default.pages.reporting.certificate_paid')}}</th>
{{--                    <th style="min-width: 96px;">{{__('default.pages.reporting.qualificated_paid')}}</th>--}}
                    <th style="min-width: 96px;">{{__('default.pages.reporting.total_get_paid')}}</th>
                    <th style="min-width: 96px;">{{__('default.pages.reporting.members_quota')}}</th>
                    <th style="min-width: 96px;">{{__('default.pages.reporting.certificate_quota')}}</th>
{{--                    <th style="min-width: 96px;">{{__('default.pages.reporting.qualificated_quota')}}</th>--}}
                    <th style="min-width: 96px;">{{__('default.pages.reporting.total_get_quota')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->user->company_name }}</td>
                        @if(count($item->professional_areas()->pluck('name_ru')->toArray())<= 0)
                            <td>-</td>
                        @else
                            <td>{{implode(', ', array_filter($item->professional_areas()->pluck('name_'.$lang)->toArray())) ?: implode(', ', array_filter($item->professional_areas()->pluck('name_ru')->toArray()))}}</td>
                        @endif
                        @if(count($item->professions()->pluck('name_ru')->toArray())<= 0)
                            <td>-</td>
                        @else
                            <td>{{implode(', ', array_filter($item->professions()->pluck('name_'.$lang)->toArray())) ?: implode(', ', array_filter($item->professions()->pluck('name_ru')->toArray()))}}</td>
                        @endif
                        <td>{{implode(', ', array_filter($item->skills->pluck('name_'.$lang)->toArray())) ?: implode(', ', $item->skills->pluck('name_ru')->toArray())}}</td>
                        <td>{{round($item->rate->pluck('rate')->avg() ?? 0, 1)}}</td>
                        <td>{{__('default.pages.reporting.statuses.'.$item->status)}}</td>
                        <td>{{$item->is_paid == true ? __('default.pages.reporting.paid_course') : __('default.pages.reporting.free_course')}}</td>
                        <td>{{$item->cost}}</td>
                        <td>{{$item->quota_status == 2 ? __('default.yes_title') : __('default.no_title')}}</td>
                        <td>{{$item->quotaCost->last()->cost ?? '-'}}</td>
                        <td>{{$item->course_members->where('paid_status', '=', 3)->count()}}</td>
                        <td>{{$item->course_members->where('paid_status', '=', 3)->where('is_finished', '=', true)->count()}}</td>
{{--                        <td>{{$item->course_members->where('paid_status', '=', 3)->where('is_qualificated', '=', true)->count()}}</td>--}}
                        <td>{{$item->course_members->where('paid_status', '=', 1)->count()}}</td>
                        <td>{{$item->course_members->where('paid_status', '=', 1)->where('is_finished', '=', true)->count()}}</td>
{{--                        <td>{{$item->course_members->where('paid_status', '=', 1)->where('is_qualificated', '=', true)->count()}}</td>--}}
                        <td>{{$item->course_members->where('paid_status', '=', 1)->sum('payment.amount')}}</td>
                        <td>{{$item->course_members->where('paid_status', '=', 2)->count()}}</td>
                        <td>{{$item->course_members->where('paid_status', '=', 2)->where('is_finished', '=', true)->count()}}</td>
{{--                        <td>{{$item->course_members->where('paid_status', '=', 2)->where('is_qualificated', '=', true)->count()}}</td>--}}
                        <td>{{$item->course_members->where('paid_status', '=', 2)->sum('payment.amount')}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-right">
                {{ $items->links('vendor.pagination.bootstrap') }}
            </div>
        </div>

    </div>

@endsection

@section('scripts')

@endsection
