@extends('admin.v2.layout.default.template')

@section('title',__('default.pages.task_pattern.title').' | '.__('default.site_name'))

@section('content')
    <div class="container container-fluid">
        <ul class="breadcrumbs">
            <li><a href="/admin/task/pattern/index">{{ __('default.pages.task_patterns.title') }}</a>
            </li>
            <li class="active">{{ __('default.pages.task_pattern.title') }}</li>
        </ul>
        @include('admin.v2.partials.components.warning')
        @include('admin.v2.partials.components.errors')
        <form action="/admin/task/pattern/create" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="block">
                <h2 class="title-secondary">{{ __('default.pages.task_pattern.title') }}</h2>
                <div class="input-group {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label class="input-group__title">{{ __('default.pages.task_pattern.name') }} *</label>
                    <input type="text" name="name" value="{{ $item->name }}"
                           placeholder="{{ __('default.labels.fill_field',['field' => __('default.pages.task_pattern.name')]) }}"
                           class="input-regular" required>
                    @if ($errors->has('name'))
                        <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
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
