<?php

namespace App\Api\V1\Controllers\App;

use App\Api\V1\Classes\Message;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Transformers\MessageTransformer;
use App\Extensions\FormatDate;
use App\Extensions\NotificationsHelper;
use App\Models\Course;
use App\Models\CourseRate;
use App\Models\Lesson;
use App\Models\Professions;
use App\Models\Skill;
use App\Models\StudentCertificate;
use App\Models\StudentCourse;
use App\Models\StudentLesson;
use App\Models\StudentLessonAnswer;
use App\Models\Theme;
use App\Models\User;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use PDF;

class LessonController extends BaseController
{
    public function lessonView(Request $request)
    {
        $lesson_id = $request->get('lesson');
        $user_id = $request->get('user');
        $hash = $request->header('hash');
        $lang = $request->header('lang', 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'lesson' => 'required',
            'user' => 'required',
            'hash' => 'required',
        ];
        $payload = [
            'lesson' => $lesson_id,
            'user' => $user_id,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($validator->fails()) {
            $message = new Message($validator->errors()->first(), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $lesson = Lesson::whereId($lesson_id)->first();
        $user = User::whereId($user_id)->first();

        if (!$user) {
            $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        if (!$lesson) {
            $message = new Message(Lang::get("api/errors.lesson_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        $student_lesson = StudentLesson::whereStudentId($user->id)->whereLessonId($lesson->id)->first();
        $course = Course::whereId($lesson->course_id)->first();

        if (!$student_lesson) {
            $message = new Message(Lang::get("api/errors.user_doesnt_have_access_to_lesson"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        } else {
            return $this->lessonViewAccess($lang, $course, $lesson, $user);
        }

    }

    public function lessonFinish(Request $request)
    {
        $lesson_id = $request->get('lesson');
        $user_id = $request->get('user');
        $hash = $request->header('hash');
        $lang = $request->header('lang', 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'lesson' => 'required',
            'user' => 'required',
            'hash' => 'required',
        ];
        $payload = [
            'lesson' => $lesson_id,
            'user' => $user_id,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($validator->fails()) {
            $message = new Message($validator->errors()->first(), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $lesson = Lesson::whereId($lesson_id)->first();
        $user = User::whereId($user_id)->first();

        if (!$user) {
            $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        if (!$lesson) {
            $message = new Message(Lang::get("api/errors.lesson_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        $lesson_student = StudentLesson::whereLessonId($lesson->id)->whereStudentId($user->id)->first();
        $lesson_student->is_finished = true;
        $lesson_student->save();

        $course = Course::whereId($lesson->course_id)->first();

        return $this->nextLessonShow($lang, $course, $lesson, $user);
    }

    public function sendHomeWork(Request $request)
    {
        $lesson_id = $request->get('lesson');
        $user_id = $request->get('user');
        $answer = $request->get('answer');
        $audios = $request->file('audios');
        $videos = $request->file('videos');
        $files = $request->file('files');
        $hash = $request->header('hash');
        $lang = $request->header('lang', 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'answer' => 'required',
            'lesson' => 'required',
            'user' => 'required',
            'videos' => 'max:50000',
            'audios' => 'max:10000',
            'files' => 'max:20000',
            'hash' => 'required',
        ];
        $payload = [
            'answer' => $answer,
            'audios' => $audios,
            'files' => $files,
            'lesson' => $lesson_id,
            'user' => $user_id,
            'videos' => $videos,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        unset($payload["files"]);
        unset($payload["videos"]);
        unset($payload["audios"]);

        if ($validator->fails()) {
            $message = new Message($validator->errors()->first(), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $lesson = Lesson::whereId($lesson_id)->first();
        $user = User::whereId($user_id)->first();

        if (!$user) {
            $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        if (!$lesson) {
            $message = new Message(Lang::get("api/errors.lesson_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        $course = Course::whereId($lesson->course_id)->first();
        $lesson_student = StudentLesson::whereLessonId($lesson->id)->whereStudentId($user->id)->first();

        if (!empty($lesson_student)) {
            if ($lesson_student->is_access == true) {

                if ($lesson->type == 3) {
                    if (!$request->has('files')) {
                        $message = new Message(Lang::get("api/errors.coursework_send_failed"), 404, null);
                        return $this->response->item($message, new MessageTransformer())->statusCode(404);
                    }
                }

                $result = StudentLessonAnswer::where('student_id', '=', $user->id)
                    ->where('lesson_id', '=', $lesson->id)->first();

                if (!$result) {
                    $user_answer = new StudentLessonAnswer;
                } else {
                    $user_answer = $result;
                }
                $user_answer->student_lesson_id = $lesson_student->id;
                $user_answer->student_id = $user->id;
                $user_answer->lesson_id = $lesson->id;
                $user_answer->type = 1;

                $user_answer->answer = $answer;

                // Видео с устройства
                $videos_array = [];
                if ($request->has('videos')) {
                    foreach ($request->file("videos") as $video) {
                        $videoName = uniqid() . '_' . $video->getClientOriginalName();
                        $video->move(public_path('users/user_' . $user->getAuthIdentifier() . '/lessons/videos'), $videoName);
                        $videos_array[] = '/users/user_' . $user->getAuthIdentifier() . '/lessons/videos/' . $videoName;
                    }
                    $user_answer->videos = json_encode($videos_array);
                }

                // Аудио с устройства
                $audios_array = [];
                if ($request->has('audios')) {
                    foreach ($request->file("audios") as $audio) {
                        $audioName = uniqid() . '_' . $audio->getClientOriginalName();
                        $audio->move(public_path('users/user_' . $user->getAuthIdentifier() . '/lessons/audios'), $audioName);
                        $audios_array[] = '/users/user_' . $user->getAuthIdentifier() . '/lessons/audios/' . $audioName;
                    }
                    $user_answer->audios = json_encode($audios_array);
                }

                // Другие материалы
                $files_array = [];
                if ($request->has('files')) {
                    foreach ($request->file("files") as $file) {
                        $fileName = uniqid() . '_' . $file->getClientOriginalName();
                        $file->move(public_path('users/user_' . $user->getAuthIdentifier() . '/lessons/files'), $fileName);
                        $files_array[] = '/users/user_' . $user->getAuthIdentifier() . '/lessons/files/' . $fileName;
                    }
                    $user_answer->another_files = json_encode($files_array);
                }

                // Пометить урок как законченный
                $lesson_student->is_finished = true;
                $lesson_student->save();

                $user_answer->save();

                if ($lesson->type == 3) {
                    // Подтвердить квалификацию
                    $student_course = StudentCourse::where('student_id', '=', $user->id)->where('course_id', '=', $course->id)->first();
                    $student_course->is_qualificated = true;
                    $student_course->save();
                    $this->finishedCourse($course, $user);
                } else {
                    return $this->nextLessonShow($lang, $course, $lesson, $user);
                }

                $message = new Message(__('api/messages.lesson.title'), 200, null);
                return $this->response->item($message, new MessageTransformer());

            } else {
                $message = new Message(Lang::get("api/errors.user_doesnt_have_access_to_lesson"), 404, null);
                return $this->response->item($message, new MessageTransformer())->statusCode(404);
            }
        } else {
            $message = new Message(Lang::get("api/errors.user_doesnt_have_access_to_lesson"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }
    }

    public function sendTest(Request $request)
    {
        $lesson_id = $request->get('lesson');
        $user_id = $request->get('user');
        $answer = $request->get('answer');
        $hash = $request->header('hash');
        $lang = $request->header('lang', 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'answer' => 'required',
            'lesson' => 'required',
            'user' => 'required',
            'hash' => 'required',
        ];
        $payload = [
            'answer' => $answer,
            'lesson' => $lesson_id,
            'user' => $user_id,
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($validator->fails()) {
            $message = new Message($validator->errors()->first(), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $lesson = Lesson::whereId($lesson_id)->first();
        $user = User::whereId($user_id)->first();

        if (!$user) {
            $message = new Message(Lang::get("api/errors.user_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        if (!$lesson) {
            $message = new Message(Lang::get("api/errors.lesson_does_not_exist"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }

        $course = Course::whereId($lesson->course_id)->first();

        $lesson_student = StudentLesson::whereLessonId($lesson->id)->whereStudentId($user->id)->first();

        if (!empty($lesson_student)) {
            if ($lesson_student->is_access == true) {

                $result = StudentLessonAnswer::where('student_id', '=', $user->id)
                    ->where('lesson_id', '=', $lesson->id)->first();

                if (!$result) {
                    $user_answer = new StudentLessonAnswer;
                } else {
                    $user_answer = $result;
                }
                $user_answer->student_lesson_id = $lesson_student->id;
                $user_answer->student_id = $user->id;
                $user_answer->lesson_id = $lesson->id;
                $user_answer->type = 0;
                $user_answer->answer = $answer;

                $user_answer->save();

                $right_answers = [];
                foreach (json_decode($lesson->practice)->questions as $key => $question) {
                    $right_answers[] = $question->answers[0];
                }
                $answers = json_decode($user_answer->answer);
                $test_results = array_diff($answers, $right_answers);
                $right_answers = 0;

                foreach (json_decode($lesson->practice)->questions as $key => $question) {
                    if (!array_key_exists($key, $test_results)) {
                        $right_answers++;
                    }
                }
                // Если кол-во правильных ответов достаточно
                if ($right_answers >= json_decode($lesson->practice)->passingScore) {
                    // Пометить урок как законченный
                    $lesson_student->is_finished = true;
                    $lesson_student->save();

                    $this->finishedCourse($course, $user);
                }

                if ($right_answers >= json_decode($lesson->practice)->passingScore) {
                    $is_finished_lesson = true;
                } else {
                    $is_finished_lesson = false;
                }

                $test = [];
                foreach (json_decode($lesson->practice)->questions as $key => $question) {
                    if (!array_key_exists($key, $test_results)) {
                        $test[] = [
                            'name' => $question->name,
                            'is_right' => true
                        ];
                    } else {
                        $test[] = [
                            'name' => $question->name,
                            'is_right' => false
                        ];
                    }
                }

                $data = [
                    'id' => $lesson->id,
                    'result' => [
                        'is_finished_lesson' => $is_finished_lesson,
                        'test' => $test
                    ]
                ];

                $message = new Message(__('api/messages.lesson.title'), 200, $data);
                return $this->response->item($message, new MessageTransformer());

            } else {
                $message = new Message(Lang::get("api/errors.user_doesnt_have_access_to_lesson"), 404, null);
                return $this->response->item($message, new MessageTransformer())->statusCode(404);
            }
        } else {
            $message = new Message(Lang::get("api/errors.user_doesnt_have_access_to_lesson"), 404, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(404);
        }
    }

    public function lessonViewAccess($lang, Course $course, Lesson $lesson, User $user)
    {
        $theme = $lesson->themes;

        $time = FormatDate::convertMunitesToTime($lesson->duration);

        $data = $this->lessonAttachments($lang, $lesson, $user);

        $message = new Message(__('api/messages.lesson.title'), 200, $data);
        $success_return = $this->response->item($message, new MessageTransformer());

        $lesson_student = StudentLesson::whereLessonId($lesson->id)->whereStudentId($user->id)->first();

        if (!empty($lesson_student)) {
            // Если доступ к курсу есть
            if ($lesson_student->is_access == true) {
                return $success_return;
                // Если доступа нет, вернуться обратно
            } else {
                $message = new Message(Lang::get("api/errors.user_doesnt_have_access_to_lesson"), 404, null);
                return $this->response->item($message, new MessageTransformer())->statusCode(404);
            }
        } else {
            // Если все уроки не доступны сразу
            if ($course->is_access_all == false) {
                if ($theme) {
                    // Получить первый урок и первую тему из курса
                    $first_theme = Theme::where('course_id', '=', $course->id)->orderBy('index_number', 'asc')->first();
                    $first_lesson = Lesson::where('theme_id', '=', $theme->id)->orderBy('index_number', 'asc')->first();

                    // Проверить является ли урок первым в курсе
                    if (($theme->id == $first_theme->id) and ($first_lesson->id == $lesson->id)) {

                        $this->syncUserLessons($lesson->id, $user);

                        return $success_return;
                        // Если урок не является первым в курсе
                    } else {
                        // Получить предыдущую тему и урок из этой темы
                        $previous_theme = Theme::where('course_id', '=', $course->id)->where('index_number', '<', $theme->index_number)->orderBy('index_number', 'desc')->first();
                        $previous_lesson_theme = Lesson::where('index_number', '<', $lesson->index_number)->where('theme_id', '=', $theme->id)->orderBy('index_number', 'desc')->first();
                        // Если предыдущая тема есть, то получить урок из предыдущей темы
                        if (!empty($previous_theme)) {
                            $previous_lesson = Lesson::where('theme_id', '=', $previous_theme->id)->orderBy('index_number', 'desc')->first();
                        }
                        // Если есть урок из предыдущей темы и он завершен, дать доступ к текущему уроку
                        if (!empty($previous_lesson_theme)) {
                            if (!empty(optional($previous_lesson_theme->lesson_student)->whereStudentId($user->id)->is_finished) == true) {
                                $this->syncUserLessons($lesson->id, $user);

                                return $success_return;
                                // Если урок из предыдущей темы не завершен, вернуться обратно
                            } else {
                                $message = new Message(Lang::get("api/errors.user_doesnt_have_access_to_lesson"), 404, null);
                                return $this->response->item($message, new MessageTransformer())->statusCode(404);
                            }
                        } else {
                            // Если есть урок и он завершен, дать доступ к текущему уроку
                            if (!empty($previous_lesson) and !empty(optional($previous_lesson->lesson_student)->whereStudentId($user->id)->is_finished) == true) {
                                $this->syncUserLessons($lesson->id, $user);

                                return $success_return;
                                // Если урок не завершен, вернуться обратно
                            } else {
                                $message = new Message(Lang::get("api/errors.user_doesnt_have_access_to_lesson"), 404, null);
                                return $this->response->item($message, new MessageTransformer())->statusCode(404);
                            }
                        }
                    }
                } else {
                    // Проверить завершенность уроков
                    $all_course_lessons = $course->lessons()->whereNotIn('type', [3, 4])->pluck('id')->toArray();
                    $finished_lessons = $user->student_lesson()->where('course_id', '=', $course->id)->where('is_finished', '=', true)->pluck('lesson_id')->toArray();
                    // Если все курсы завершены
                    if (array_diff($all_course_lessons, $finished_lessons) == []) {
                        // Если это курсовая работа, дать доступ
                        if ($lesson->type == 3) {
                            $this->syncUserLessons($lesson->id, $user);
                            // Если это финальный тест, проверить завершена ли курсовая
                        } else if ($lesson->type == 4) {
                            $coursework = $course->lessons()->where('type', '=', 3)->first();
                            if ($coursework) {
                                // Если есть курсовая и она завершена, дать доступ
                                if (!empty(optional($coursework->lesson_student)->whereStudentId($user->id)->is_finished) == true) {
                                    $this->syncUserLessons($lesson->id, $user);
                                    // Если есть курсовая, но она не завершена, вернуть обратно
                                } else {
                                    return redirect('/' . $lang . '/course-catalog/course/' . $course->id)->with('error', __('default.pages.lessons.access_denied_message'));
                                }
                                // Если есть курсовой нет, то дать доступ
                            } else {
                                $this->syncUserLessons($lesson->id, $user);
                            }
                        }

                        return $success_return;
                    } else {
                        $message = new Message(Lang::get("api/errors.user_doesnt_have_access_to_lesson"), 404, null);
                        return $this->response->item($message, new MessageTransformer())->statusCode(404);
                    }

                }
                // Если все уроки доступны сразу
            } else {
                // Проверить завершенность уроков
                $all_course_lessons = $course->lessons()->whereNotIn('type', [3, 4])->pluck('id')->toArray();
                $finished_lessons = $user->student_lesson()->where('course_id', '=', $course->id)->where('is_finished', '=', true)->pluck('lesson_id')->toArray();
                switch ($lesson->type) {
                    case (3):
                        if (array_diff($all_course_lessons, $finished_lessons) == []) {
                            $this->syncUserLessons($lesson->id, $user);
                        } else {
                            $message = new Message(Lang::get("api/errors.user_doesnt_have_access_to_lesson"), 404, null);
                            return $this->response->item($message, new MessageTransformer())->statusCode(404);
                        }
                        break;
                    case (4):
                        $coursework = $course->lessons()->where('type', '=', 3)->first();
                        if ($coursework) {
                            // Если есть курсовая и она завершена, дать доступ
                            if (!empty(optional($coursework->lesson_student)->whereStudentId($user->id)->is_finished) == true) {
                                $this->syncUserLessons($lesson->id, $user);
                                // Если есть курсовая, но она не завершена, вернуть обратно
                            } else {
                                $message = new Message(Lang::get("api/errors.user_doesnt_have_access_to_lesson"), 404, null);
                                return $this->response->item($message, new MessageTransformer())->statusCode(404);
                            }
                            // Если есть курсовой нет, то дать доступ
                        } else {
                            $this->syncUserLessons($lesson->id, $user);
                        }
                        break;
                    default:
                        $this->syncUserLessons($lesson->id, $user);
                        break;
                }

                return $success_return;
            }
        }

    }

    public function lessonAttachments($lang, Lesson $lesson, User $user)
    {
        // Вложения
        if (!empty($lesson->lesson_attachment)) {
            // Видео
            if ($lesson->is_poor_vision == true) {
                $videos_array = array_merge(json_decode($lesson->lesson_attachment->videos), json_decode($lesson->lesson_attachment->videos_poor_vision));
            } else {
                $videos_array = json_decode($lesson->lesson_attachment->videos);
            }

            if ($videos_array != []) {
                foreach ($videos_array as $video) {
                    $videos[] = [
                        'url' => env('APP_URL') . $video
                    ];
                }
            } else {
                $videos = null;
            }
            // Видео с YouTube
            if ($lesson->is_poor_vision == true) {
                $youtube_videos_array = array_merge(json_decode($lesson->lesson_attachment->videos_link), json_decode($lesson->lesson_attachment->videos_poor_vision_link));
            } else {
                $youtube_videos_array = json_decode($lesson->lesson_attachment->videos_link);
            }

            if ($youtube_videos_array != []) {
                foreach ($youtube_videos_array as $video) {
                    $youtube_videos[] = [
                        'url' => env('APP_URL') . $video
                    ];
                }
            } else {
                $youtube_videos = null;
            }
            // Аудио
            if ($lesson->is_poor_vision == true) {
                $audios_array = array_merge(json_decode($lesson->lesson_attachment->audios), json_decode($lesson->lesson_attachment->audios_poor_vision));
            } else {
                $audios_array = json_decode($lesson->lesson_attachment->audios);
            }

            if ($audios_array != []) {
                foreach ($audios_array as $audio) {
                    $audios[] = [
                        'url' => env('APP_URL') . $audio
                    ];
                }
            } else {
                $audios = null;
            }
            // Другие материалы
            if ($lesson->is_poor_vision == true) {
                $another_files_array = array_merge(json_decode($lesson->lesson_attachment->another_files), json_decode($lesson->lesson_attachment->another_files_poor_vision));
            } else {
                $another_files_array = json_decode($lesson->lesson_attachment->another_files);
            }

            if ($another_files_array != []) {
                foreach ($another_files_array as $another_file) {
                    $another_files[] = [
                        'url' => env('APP_URL') . $another_file
                    ];
                }
            } else {
                $another_files = null;
            }
        } else {
            $videos = null;
            $youtube_videos = null;
            $audios = null;
            $another_files = null;
        }

        if ($lesson->end_lesson_type == 0 and $lesson->type != 1) {
            foreach (json_decode($lesson->practice)->questions as $item) {
                $practice['questions'][] = [
                    'name' => $item->name,
                    'is_pictures' => $item->is_pictures,
                    'answers' => $item->answers,
                ];
            }
            $practice['mixAnswers'] = json_decode($lesson->practice)->mixAnswers == "true" ? 1 : 0;

        }else if ($lesson->end_lesson_type == 1) {
            $practice = [
                    'theory' => $lesson->practice
                ];
        }

        $data = [
            'id' => $lesson->id,
            'name' => $lesson->name,
            'type' => $lesson->type,
            'finished' => $lesson->student_lessons->where('student_id', '=', $user->id)->first()->is_finished,
            'duration' => $lesson->duration,
            'enabled' => $lesson->student_lessons->where('student_id', '=', $user->id)->first()->is_access,
            'end_lesson_type' => $lesson->end_lesson_type,
            'image' => $lesson->getAvatar(),
            'theory' => $lesson->theory,
            'youtubeLinks' => $youtube_videos,
            'videoLinks' => $videos,
            'audioLinks' => $audios,
            'attachments' => $another_files,
            'practice' => $practice ?? null,
        ];

        return $data;
    }

    public function syncUserLessons(int $lesson_id, User $user)
    {
        $item = StudentLesson::where('lesson_id', '=', $lesson_id)->where('student_id', '=', $user->id)->first();
        if (empty($item)) {
            $item = new StudentLesson;
            $item->lesson_id = $lesson_id;
            $item->student_id = $user->id;
            $item->is_access = true;
            $item->save();
        }

    }

    public function finishedCourse($course, User $user)
    {
        // Получить все уроки данного курса
        $lessons = $course->lessons->pluck('id')->toArray();

        // Получить все завершенные уроки данного курса
        $student_finished_lessons = Lesson::whereHas('student_lessons', function ($q) {
            $q->where('student_lesson.is_finished', '=', true);
        })->whereIn('id', $lessons)->get();

        // Если кол-во уроков и кол-во завершенных уроков равно, то отметить курс как завершенный
        if ($course->lessons->count() == $student_finished_lessons->count()) {
            $student_course = StudentCourse::where('student_id', '=', $user->id)->where('course_id', '=', $course->id)->first();
            if ($student_course->is_finished == false) {
                $student_course->is_finished = true;
                $student_course->save();

                // Сохранить сертификат
                $user_certificate = StudentCertificate::where('user_id', '=', $user->id)
                    ->where('course_id', '=', $course->id)->first();
                if (empty($user_certificate)) {
                    $this->saveCertificates($course, $student_course, $user);
                }
                // Присвоить обучающемуся полученные навыки
                $user->skills()->sync($course->skills->pluck('id')->toArray(), false);
                // Отправить уведомление
                $notification_name = "notifications.course_student_finished";
                NotificationsHelper::createNotification($notification_name, $course->id, $user->id);
            }
        }
    }

    public function nextLessonShow($lang, $course, $lesson, $user)
    {
        $course_status = StudentCourse::where('course_id', '=', $course->id)->where('student_id', '=', $user->id)->first();
        if ($course_status->is_finished == false) {
            // Получить следующий урок
            $theme = $lesson->themes;
            $next_lesson_theme = Lesson::where('index_number', '>', $lesson->index_number)->where('theme_id', '=', $theme->id)->orderBy('index_number', 'asc')->first();
            $next_theme = Theme::where('course_id', '=', $course->id)->where('index_number', '>', $theme->index_number)->orderBy('index_number', 'asc')->first();
            if (!empty($next_theme)) {
                $next_lesson = Lesson::where('theme_id', '=', $next_theme->id)->orderBy('index_number', 'asc')->first();
            }

            // Переход к следующему уроку

            if (!empty($next_lesson_theme)) {
                // Установка доступа к следующему уроку
                $this->syncUserLessons($next_lesson_theme->id, $user);
                // Проверить окончание курса
                $this->finishedCourse($course, $user);

                $data = $this->lessonAttachments($lang, $next_lesson_theme, $user);

                $message = new Message(__('api/messages.lesson.title'), 200, $data);
                return $this->response->item($message, new MessageTransformer());

            } else {
                if (!empty($next_lesson) and !empty(optional($next_lesson->lesson_student)->whereStudentId($user->id)->is_finished) == false) {
                    // Установка доступа к следующему уроку
                    $this->syncUserLessons($next_lesson->id, $user);
                    // Проверить окончание курса
                    $this->finishedCourse($course, $user);
                    $data = $this->lessonAttachments($lang, $next_lesson, $user);

                    $message = new Message(__('api/messages.lesson.title'), 200, $data);
                    return $this->response->item($message, new MessageTransformer());

                    // Если следующего урока нет
                } else {
                    $coursework = $course->lessons()->where('type', '=', 3)->first();
                    $final_test = $course->lessons->where('type', '=', 4)->first();

                    if (!empty($coursework) and ($coursework->id != $lesson->id)) {
                        $this->syncUserLessons($coursework->id, $user);
                    } else if (!empty($final_test) and empty($coursework) and ($final_test->id != $lesson->id)) {
                        $this->syncUserLessons($final_test->id, $user);

                    }
                    if (!empty($final_test) and ($final_test->id != $lesson->id) and !empty($coursework)) {
                        if ($coursework->is_finished == true) {
                            $this->syncUserLessons($final_test->id, $user);
                        }

                    }
                    // Проверить окончание курса
                    $this->finishedCourse($course, $user);
                    $message = new Message(__('api/messages.lesson.title'), 200, null);
                    return $this->response->item($message, new MessageTransformer());
                }

            }

        } else {
            $message = new Message(__('api/messages.lesson.title'), 200, null);
            return $this->response->item($message, new MessageTransformer());
        }
    }

    public function saveCertificates($course, $student_course, User $user)
    {
        $languages = ["ru", "kk"];

        $certificate = new StudentCertificate;
        $certificate->user_id = $user->id;
        $certificate->course_id = $course->id;
        $certificate->save();

        foreach ($languages as $language) {
            $data = [
                'author_name' => $course->user->company_name,
                'student_name' => $user->student_info->name,
                'duration' => $course->lessons->sum('duration'),
                'course_name' => $course->name,
                'skills' => $course->skills,
                'certificate_id' => sprintf("%012d", $certificate->id) . '-' . date('dmY')
            ];
            $pdf = PDF::loadView('app.pages.page.pdf.certificate_' . $course->certificate_id . '_' . $language, ['data' => $data]);
            $pdf = $pdf->setPaper('a4', 'portrait');

            $path = public_path('users/user_' . $user->id . '');
            $pdf->save($path . '/' . 'course_' . $course->id . '_certificate_' . $language . '.pdf');

            $pdf = new \Spatie\PdfToImage\Pdf($path . '/' . 'course_' . $course->id . '_certificate_' . $language . '.pdf');
            $pdf->saveImage($path . '/' . 'course_' . $course->id . '_image_' . $language . '.png');
        }

        $file_path = '/users/user_' . $user->id . '';

        $certificate->pdf_ru = $file_path . '/' . 'course_' . $course->id . '_certificate_' . $languages[0] . '.pdf';
        $certificate->pdf_kk = $file_path . '/' . 'course_' . $course->id . '_certificate_' . $languages[1] . '.pdf';
        $certificate->png_ru = $file_path . '/' . 'course_' . $course->id . '_image_' . $languages[0] . '.png';
        $certificate->png_kk = $file_path . '/' . 'course_' . $course->id . '_image_' . $languages[1] . '.png';

        $certificate->save();

        $cert = base64_encode(file_get_contents(env('APP_URL') . $certificate->png_ru));
        $this->putNewSkills($user->student_info->uid, $course, $cert);

    }

    public function putNewSkills($uid, $course, $cert)
    {
        $data = [
            'uid' => $uid,
            'course' => [
                'id' => $course->id,
                'name' => $course->name,
                'skills' => $course->skills->pluck('code_skill')->toArray()
            ],
            'cert' => $cert
        ];

        $client = new Client(['verify' => false]);

        try {
            $body = $data;
            $response = $client->request('PUT', config('enbek.base_url').'/ru/api/put-navyk-from-obuch', [
                'body' => json_encode($body),
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);
        } catch (BadResponseException $e) {
            return $e;
        }

        return $data;
    }
}
