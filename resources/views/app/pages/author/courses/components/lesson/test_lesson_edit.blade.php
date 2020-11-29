<div id="test" @if($item->end_lesson_type == 0) style="display: block" @else style="display: none" @endif>
    <div class="test-constructor">
        <div class="title-secondary">{{__('default.pages.lessons.test_title')}}</div>
        <div class="questions" id="questions">
            @if(($item->practice != null) and ($item->end_lesson_type == 0))
                @foreach(json_decode($item->practice)->questions as $key => $question)
                    <div class="question form-group">
                        <label class="form-group__label">{{__('default.pages.lessons.question_title')}}</label>
                        <div class="input-addon">
                            <div>
                                <div class="form-group">
                                            <textarea name="questions[{{ $key+1 }}]"
                                                      class="input-regular tinymce-here question-text"
                                                      placeholder="{{__('default.pages.lessons.question_text')}}"
                                                      required>{{$question->name}}</textarea>
                                </div>
                                <div class="answers-bar">
                                    <span>{{__('default.pages.lessons.answers_title')}}</span>
                                    <label class="checkbox"><input type="checkbox" name="isPictures[{{ $key+1 }}]"
                                                                   value="true" {{ ($question->is_pictures == true ? ' checked' : '') }}><span>{{__('default.pages.lessons.pictures_type_title')}}</span></label>
                                </div>
                                @if($question->is_pictures == true)
                                    <div class="answers-wrapper">
                                        <div class="answers">
                                            @foreach($question->answers as $answer)
                                                <div class="form-group">
                                                    <div class="input-addon">
                                                        <div data-url="/ajax_upload_test_images?_token={{ csrf_token() }}"
                                                             data-maxfiles="1" data-maxsize="1"
                                                             data-acceptedfiles="image/*"
                                                             class="dropzone-default dropzone-multiple dz-max-files-reached">
                                                            <input type="text" name="answers[{{ $key+1 }}][]"
                                                                   value="{{ $answer }}" required="">
                                                            <div class="dropzone-default__info">JPG, PNG • макс. 1MB
                                                            </div>
                                                            <div class="previews-container">
                                                                <div class="dz-preview dz-processing dz-image-preview dz-success dz-complete">
                                                                    <div class="dz-details">
                                                                        <img data-dz-thumbnail=""
                                                                             alt=""
                                                                             src="{{ $answer }}">
                                                                        <div class="dz-filename"><span data-dz-name="">{{basename($answer)}}</span>
                                                                        </div>
                                                                        <div class="dz-size" data-dz-size="">
                                                                            <strong> {{ round(filesize(public_path($answer)) / 1024) }}</strong>
                                                                            KB
                                                                        </div>
                                                                    </div>
                                                                    <a href="javascript:undefined;" title="{{__('default.pages.courses.delete')}}"
                                                                       class="link red" data-dz-remove="">{{__('default.pages.courses.delete')}}</a>
                                                                </div>
                                                            </div>
                                                            <a href="javascript:;" title="{{__('default.pages.courses.add_file_btn_title')}}"
                                                               class="dropzone-default__link dz-clickable">{{__('default.pages.courses.add_file_btn_title')}}</a>
                                                        </div>
                                                        <div class="addon small">
                                                            <span class="required">*</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="text-right">
                                            <div title="Добавить" class="add-btn"><span
                                                        class="add-btn__title">Добавить</span><span
                                                        class="btn-icon extra-small icon-plus"> </span></div>
                                        </div>
                                    </div>
                                @else
                                    <div class="answers-wrapper">
                                        <div class="answers">
                                            <div class="form-group green">
                                                <div class="input-addon">
                                                    <input type="text" name="answers[{{ $key+1 }}][]"
                                                           placeholder="{{__('default.pages.lessons.right_answer_title')}}"
                                                           class="input-regular small" value="{{$question->answers[0]}}"
                                                           required>
                                                    <div class="addon small">
                                                        <span class="required">*</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @foreach(array_slice($question->answers,1) as $answer)
                                                <div class="form-group">
                                                    <div class="input-addon">
                                                        <input type="text" name="answers[{{ $key+1 }}][]"
                                                               placeholder="{{__('default.pages.lessons.input_answer_title')}}"
                                                               class="input-regular small" value="{{$answer}}" required>
                                                        <div class="addon small">
                                                            <span class="required">*</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="text-right">
                                            <div title="{{__('default.pages.profile.add_btn_title')}}"
                                                 class="add-btn"><span
                                                        class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span
                                                        class="btn-icon extra-small icon-plus"> </span></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="addon addon-btn">
                                <span class="required">*</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="question form-group">
                    <label class="form-group__label">{{__('default.pages.lessons.question_title')}}</label>
                    <div class="input-addon">
                        <div>
                            <div class="form-group">
                                            <textarea name="questions[1]"
                                                      class="input-regular tinymce-here question-text"
                                                      placeholder="{{__('default.pages.lessons.question_text')}}"
                                                      required></textarea>
                            </div>
                            <div class="answers-bar">
                                <span>{{__('default.pages.lessons.answers_title')}}</span>
                                <label class="checkbox"><input type="checkbox" name="isPictures[1]"
                                                               value="true" ><span>{{__('default.pages.lessons.pictures_type_title')}}</span></label>
                            </div>
                            <div class="answers-wrapper">
                                <div class="answers">
                                    <div class="form-group green">
                                        <div class="input-addon">
                                            <input type="text" name="answers[1][]"
                                                   placeholder="{{__('default.pages.lessons.right_answer_title')}}"
                                                   class="input-regular small" required>
                                            <div class="addon small">
                                                <span class="required">*</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-addon">
                                            <input type="text" name="answers[1][]"
                                                   placeholder="{{__('default.pages.lessons.input_answer_title')}}"
                                                   class="input-regular small" required>
                                            <div class="addon small">
                                                <span class="required">*</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div title="{{__('default.pages.profile.add_btn_title')}}" class="add-btn"><span
                                                class="add-btn__title">{{__('default.pages.profile.add_btn_title')}}</span><span
                                                class="btn-icon extra-small icon-plus"> </span></div>
                                </div>
                            </div>
                        </div>
                        <div class="addon addon-btn">
                            <span class="required">*</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <a href="#" title="{{__('default.pages.lessons.add_question')}}" class="btn small"
           id="addQuestion">{{__('default.pages.lessons.add_question')}}</a>
        <div class="test-constructor__info">
            <div class="row row--multiline">
                <div class="col-auto">
                    <div class="text">{{__('default.pages.lessons.questions_count')}}: <span
                                id="questionsCount"></span></div>
                </div>
                <div class="col-auto">
                    <div class="passing-score">
                        <span class="text">{{__('default.pages.lessons.passing_score')}}</span>
                        <input id="passingScore" type="text" name="passingScore" class="input-regular small"
                               placeholder="" value="{{json_decode($item->practice)->passingScore ?? 1}}">
                    </div>
                </div>
                <div class="col-auto">
                    <label class="checkbox small"><input type="checkbox" name="mixAnswers"
                                                         value="true"
                                                         checked><span>{{__('default.pages.lessons.mix_answers')}}</span></label>
                </div>
            </div>
        </div>
    </div>
</div>
