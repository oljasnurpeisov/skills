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
            <div class="row">
                <div class="col-md-3">
                    <div class="input-group">
                        <label class="input-group__title">Поиск по ФИО</label>
                        <input type="text" name="student_name" placeholder="" class="input-regular"
                               value="{{$request->student_name}}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <label class="input-group__title">Поиск по ИИН</label>
                        <input type="text" name="student_iin" placeholder="" class="input-regular" onfocus="$(this).inputmask('999999999999')"
                               value="{{$request->student_iin}}">
                    </div>
                </div>
            </div>

            <div class="collapse-block collapsed" style="display: none;" id="collapse1">
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group">
                            <label class="input-group__title">&nbsp;Статус обучающегося на момент записи на курс</label>
                            <div class="input-group">
                                <label class="checkbox">
                                    <input type="checkbox" name="unemployed_status[]"
                                           value="1" {{in_array(1, $unemployed_status) ? 'checked' : ''}}>
                                    <span>Безработный</span>
                                </label>
                            </div>
                            <div class="input-group">
                                <label class="checkbox">
                                    <input type="checkbox" name="unemployed_status[]"
                                           value="0" {{in_array(0, $unemployed_status) ? 'checked' : ''}}>
                                    <span>Работающий</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group">
                            <label class="input-group__title">Получил сертификат</label>
                            <div class="input-group">
                                <label class="checkbox">
                                    <input type="checkbox" name="is_finished[]"
                                           value="1" {{in_array(1, $is_finished) ? 'checked' : ''}}>
                                    <span>Да</span>
                                </label>
                            </div>
                            <div class="input-group">
                                <label class="checkbox">
                                    <input type="checkbox" name="is_finished[]"
                                           value="0" {{in_array(0, $is_finished) ? 'checked' : ''}}>
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>
                    </div>

{{--                    <div class="col-md-3">--}}
{{--                        <div class="input-group">--}}
{{--                            <label class="input-group__title">Тип курса</label>--}}
{{--                            <div class="input-group">--}}
{{--                                <label class="checkbox">--}}
{{--                                    <input type="checkbox" name="is_finished[]" --}}
{{--                                           value="1" {{in_array(1, $is_finished) ? 'checked' : ''}}>--}}
{{--                                    <span>ГП</span>--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            <div class="input-group">--}}
{{--                                <label class="checkbox">--}}
{{--                                    <input type="checkbox" name="is_finished[]" --}}
{{--                                           value="0" {{in_array(0, $is_finished) ? 'checked' : ''}}>--}}
{{--                                    <span>Платный</span>--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            <div class="input-group">--}}
{{--                                <label class="checkbox">--}}
{{--                                    <input type="checkbox" name="is_finished[]" --}}
{{--                                           value="0" {{in_array(0, $is_finished) ? 'checked' : ''}}>--}}
{{--                                    <span>Бесплатный</span>--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="input-group__title">Область</label>
                                    <select type="text" name="area_id" class="selectize-regular">
                                        @foreach($areas as $area)
                                            <option value="{{ $area->cod }}" @if($area->cod==$request->area_id) selected='selected' @endif>
                                                {{ $area->caption }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
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
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('admin.pages.reports.iin')}}</th>
                    <th>
                        {{__('admin.pages.reports.name_student')}}
{{--                        <a href="?sortByName={{$request->sortByName == 'asc' ? 'desc' : 'asc'}}">{{__('admin.pages.reports.name_student')}}</a>--}}
                    </th>
                    <th>{{__('admin.pages.reports.coduoz')}}</th>
                    <th>{{__('admin.pages.reports.region')}}</th>
                    <th>{{__('admin.pages.reports.unemployed')}}</th>
                    <th>{{__('admin.pages.reports.course_name')}}</th>
                    <th>{{__('admin.pages.reports.course_type')}}</th>
                    <th>{{__('admin.pages.reports.course_date')}}</th>
                    <th>{{__('admin.pages.reports.first_lesson_date')}}</th>
                    <th>{{__('admin.pages.reports.first_failed_test_date')}}</th>
                    <th>{{__('admin.pages.reports.second_failed_test_date')}}</th>
                    <th>{{__('admin.pages.reports.third_failed_test_date')}}</th>
                    <th>{{__('admin.pages.reports.certificate_date')}}</th>
                    <th>{{__('admin.pages.reports.quotas_count')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->student_info->iin}}</td>
                        <td>{{$item->student_info->name}}</td>
                        <td>{{ $item->student_info->coduoz ? App\Models\RegionTree::getRegionCaption($lang, $item->student_info->coduoz) : '' }}</td>
                        <td>{{ $item->student_info->region_id ? App\Models\Kato::where('te',  $item->student_info->region_id)->first()->rus_name ?? '' : '' }}</td>
                        <td>
                            @if(isset($item->unemployed_status))
                                {{ $item->unemployed_status == '00000$192' ? __('default.yes_title') : __('default.no_title') }}
                            @endif
                        </td>
                        <td>{{$item->course->name}}</td>
                        <td>
                            @if ($item->course->quota_status == 2)
                                {{__('admin.pages.reports.quota_course')}}
                            @else
                                {{$item->course->is_paid == true ? __('default.pages.reporting.paid_course') : __('default.pages.reporting.free_course')}}
                            @endif
                        </td>
                        <td>{{ date('d.m.Y', strtotime($item->created_at)) }}</td>
                        <td>{{ isset($item->student_first_lesson()->created_at) ?  date('d.m.Y', strtotime($item->student_first_lesson()->created_at)) : ''}}</td>
                        <td>{{ isset($item->attempts()[0]->created_at) ? date('d.m.Y H:i', strtotime($item->attempts()[0]->created_at)) : '' }}</td>
                        <td>{{ isset($item->attempts()[1]->created_at) ? date('d.m.Y H:i', strtotime($item->attempts()[1]->created_at)) : '' }}</td>
                        <td>{{ isset($item->attempts()[2]->created_at) ? date('d.m.Y H:i', strtotime($item->attempts()[2]->created_at)) : '' }}</td>
                        <td>{{ $item->is_finished == 1 && isset($item->certificate()->created_at) ? date('d.m.Y', strtotime($item->certificate()->created_at)) : ''}}</td>
                        <td></td>
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
