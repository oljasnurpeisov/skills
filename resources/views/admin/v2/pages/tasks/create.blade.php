@extends('admin.v2.layout.default.template')

@section('title',__('default.pages.task.title').' | '.__('default.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li><a href="/admin/task/index">{{ __('default.pages.tasks.title') }}</a>
            </li>
            <li class="active">{{ __('default.pages.task.title') }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @include('admin.v2.partials.components.errors')
        <form action="/admin/task/create" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="block">
                <h2 class="title-secondary">{{ __('default.pages.task.title') }}</h2>
                <div class="input-group {{ $errors->has('producer_id') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('default.pages.fridge.title') }} *</label>
                    <select name="fridge_id" id="fridge_id" class="input-regular chosen" data-placeholder=" " required>
                        <option value="" disabled selected>-</option>
                        @foreach($fridges as $fridge)
                            <option value="{{ $fridge->id }}"
                                    @if($item->fridge_id === $fridge->id) selected @endif
                                    data-dishes="{{ implode(',',$fridge->dishes->pluck('id')->toArray()) }}">{{ $fridge->address }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('fridge_id'))
                        <span class="help-block"><strong>{{ $errors->first('fridge_id') }}</strong></span>
                    @endif
                </div>
                <div class="input-group {{ $errors->has('producer_id') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('default.pages.task.courier') }} *</label>
                    <select name="user_id" class="input-regular chosen" data-placeholder=" " required>
                        <option value="" disabled selected>-</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                    @if($item->user_id === $user->id) selected @endif>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('user_id'))
                        <span class="help-block"><strong>{{ $errors->first('user_id') }}</strong></span>
                    @endif
                </div>
                <div class="input-group {{ $errors->has('date') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('default.pages.task.date') }} *</label>
                    <label class="date">
                        <input type="text" name="date"
                               value="{{ $item->date === null ? date('Y-m-d') : $item->date->format('Y-m-d') }}"
                               placeholder="" class="input-regular custom-datepicker" required>
                    </label>
                    @if ($errors->has('date'))
                        <span class="help-block"><strong>{{ $errors->first('date') }}</strong></span>
                    @endif
                </div>
                <div class="input-group">
                    <label class="input-group__title">{{ __('default.pages.task.status') }}</label>
                    <select name="status" class="input-regular chosen" data-placeholder=" " disabled>
                        <option value="" disabled selected>-</option>
                        @foreach($statuses as $key => $status)
                            <option @if($item->status === $status) selected @endif>{{ __("default.pages.task.statuses.$item->status") }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group {{ $errors->has('pattern_id') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('default.pages.task.pattern') }}</label>
                    <select name="pattern_id" class="input-regular chosen" data-placeholder=" ">
                        <option value="" disabled selected>-</option>
                        @foreach($patterns as $pattern)
                            <option value="{{ $pattern->id }}">{{ $pattern->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('pattern_id'))
                        <span class="help-block"><strong>{{ $errors->first('pattern_id') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row" style="margin-bottom: 1.875rem;">
                <div class="col-md-6">
                    <div class="block">
                        <h2 class="title-secondary">{{ __('default.pages.task_pattern.out') }}</h2>
                        <table class="table records">
                            <colgroup>
                                <col span="1" style="width: 60%;">
                                <col span="1" style="width: 20%;">
                                <col span="1" style="width: 20%;">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>{{ __('default.pages.dish.name') }}</th>
                                <th>{{ __('default.pages.task_pattern.count') }}</th>
                                <th>{{ __('default.labels.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody id="dishes-to-unload">
                            </tbody>
                        </table>
                        <span class="btn btn--blue"
                              onclick="addDishToUnload();">{{ __('default.pages.task_pattern.add_dish_for_unload') }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="block">
                        <h2 class="title-secondary">{{ __('default.pages.task_pattern.in') }}</h2>
                        <table class="table records">
                            <colgroup>
                                <col span="1" style="width: 60%;">
                                <col span="1" style="width: 20%;">
                                <col span="1" style="width: 20%;">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>{{ __('default.pages.dish.name') }}</th>
                                <th>{{ __('default.pages.task_pattern.count') }}</th>
                                <th>{{ __('default.labels.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody id="dishes-to-load">
                            </tbody>
                        </table>
                        <span class="btn btn--blue"
                              onclick="addDishToLoad();">{{ __('default.pages.task_pattern.add_dish_for_load') }}</span>
                    </div>
                </div>
            </div>
            <div class="block">
                <div class="buttons">
                    <div>
                        <button type="submit" class="btn btn--green">{{ __('default.labels.save') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="hidden">
        <table>
            <tr id="dish-to-unload-component">
                <td>
                    <select name="dishes[unload][ids][]" class="input-regular" required>
                        <option value="" selected disabled>-</option>
                        @foreach($dishes as $dish)
                            <option value="{{ $dish->id }}">{{ $dish->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="dishes[unload][count][]" placeholder="0" class="input-regular" disabled>
                </td>
                <td>
                    <div class="action-buttons">
                        <span class="icon-btn icon-btn--pink icon-delete"></span>
                    </div>
                </td>
            </tr>
            <tr id="dish-to-load-component">
                <td>
                    <select name="dishes[load][ids][]" class="input-regular" required>
                        <option value="" selected disabled>-</option>
                        @foreach($dishes as $dish)
                            <option value="{{ $dish->id }}">{{ $dish->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="dishes[load][count][]" placeholder="0" class="input-regular" required>
                </td>
                <td>
                    <div class="action-buttons">
                        <span class="icon-btn icon-btn--pink icon-delete"></span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $("#fridge_id").change(function () {
            $("#dishes-to-unload").html("");

            var option = $("option:selected", "#fridge_id"),
                dishes = option.attr("data-dishes").split(",");

            $("select option", "#dish-to-unload-component").each(function () {
                var option = $(this);
                if (jQuery.inArray(option.val(), dishes) !== -1) {
                    option.removeAttr('disabled');
                } else {
                    option.attr('disabled', 'disabled');
                }
            });
        });

        function addDishToUnload() {
            var element = $("#dish-to-unload-component").clone();
            element.removeAttr('id');
            element.find('select').addClass('chosen');
            element.find('.icon-delete').click(function (e) {
                element.remove();
            });
            element.appendTo("#dishes-to-unload");
            chosenInit();
        }

        function addDishToLoad() {
            var element = $("#dish-to-load-component").clone();
            element.removeAttr('id');
            element.find('select').addClass('chosen');
            element.find('.icon-delete').click(function (e) {
                element.remove();
            });
            element.appendTo("#dishes-to-load");
            chosenInit();
        }
    </script>
@endsection
