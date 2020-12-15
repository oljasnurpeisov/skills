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
                                       class="input-regular" value="">
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <label class="input-group__title">до</label>
                            <label class="">
                                <input type="number" name="certificates_count_to" placeholder=""
                                       class="input-regular" value="">
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
                                       class="input-regular" value="">
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <label class="input-group__title">до</label>
                            <label class="">
                                <input type="number" name="qualifications_count_to" placeholder=""
                                       class="input-regular" value="">
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
            <h2 class="title-secondary">{{__('admin.pages.reports.authors_report')}}</h2>
            <table class="table records">
                <colgroup>
                    <col span="1" style="width: 15%;">
                    <col span="1" style="width: 15%;">
                    <col span="1" style="width: 15%;">
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
                    <th>{{__('admin.pages.reports.skills')}}</a></th>
                    <th>{{__('admin.pages.reports.group_profession')}}</th>
                    <th>{{__('admin.pages.reports.course_rate')}}</th>
                    <th>{{__('admin.pages.reports.course_status')}}</th>
                    <th>{{__('admin.pages.reports.quota_access')}}</th>
                    <th>{{__('admin.pages.reports.paid_or_free')}}</th>
                    <th><a href="?sortByCourseMembers={{$request->sortByCourseMembers == 'asc' ? 'desc' : 'asc'}}">{{__('admin.pages.reports.course_members')}}</a></th>
                    <th>{{__('admin.pages.reports.course_members_certificates')}}</th>
                    <th>{{__('admin.pages.reports.course_members_qualification')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->user->company_name }}</td>
                        <td>{{implode(', ', array_filter($item->skills->pluck('name_'.$lang)->toArray())) ?: implode(', ', $item->skills->pluck('name_ru')->toArray())}}</td>
                        @if(count($item->professionsBySkills()->pluck('id')->toArray())<= 0)
                            <td>-</td>
                        @else
                            <td>{{implode(', ', array_filter($item->professionsBySkills()->pluck('name_'.$lang)->toArray())) ?: implode(', ', array_filter($item->professionsBySkills()->pluck('name_ru')->toArray()))}}</td>
                        @endif
                        <td>{{round($item->rate->pluck('rate')->avg() ?? 0, 1)}}</td>
                        <td>{{__('default.pages.reporting.statuses.'.$item->status)}}</td>
                        <td>@if($item->quota_status == 2){{__('default.yes_title')}}@else{{__('default.no_title')}}@endif</td>
                        <td>@if($item->is_paid == true){{__('default.pages.reporting.paid_course')}}@else{{__('default.pages.reporting.free_course')}}@endif</td>
                        <td>{{count($item->course_members->whereIn('paid_status', [1,2]))}}</td>
                        <td>{{count($item->course_members->where('is_finished', '=', true))}}</td>
                        @if($item->courseWork())
                            <td>{{$item->courseWork()->finishedLesson()->count()}}</td>
                        @else
                            <td>-</td>
                        @endif
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
