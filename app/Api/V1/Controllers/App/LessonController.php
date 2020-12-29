<?php

namespace App\Api\V1\Controllers\App;

use App\Api\V1\Classes\Message;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Transformers\MessageTransformer;
use App\Extensions\FormatDate;
use App\Models\Course;
use App\Models\CourseRate;
use App\Models\Lesson;
use App\Models\Professions;
use App\Models\Skill;
use App\Models\StudentCertificate;
use App\Models\StudentCourse;
use App\Models\StudentLesson;
use App\Models\Theme;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

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

    public function sendHomeWork(Request $request)
    {
        return 302;
    }

    public function sendTest(Request $request)
    {
        return 302;
    }

    public function lessonViewAccess($lang, Course $course, Lesson $lesson, User $user)
    {
        $theme = $lesson->themes;

        $time = FormatDate::convertMunitesToTime($lesson->duration);

        $data = $this->lessonAttachments($lang, $lesson);

        $message = new Message(__('api/messages.courses.title'), 200, $data);
        $success_return = $this->response->item($message, new MessageTransformer());

        if (!empty($lesson->lesson_student)) {
            // Если доступ к курсу есть
            if ($lesson->lesson_student->is_access == true) {
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
                            if (!empty($previous_lesson_theme->lesson_student->is_finished) == true) {
                                $this->syncUserLessons($lesson->id, $user);

                                return $success_return;
                                // Если урок из предыдущей темы не завершен, вернуться обратно
                            } else {
                                $message = new Message(Lang::get("api/errors.user_doesnt_have_access_to_lesson"), 404, null);
                                return $this->response->item($message, new MessageTransformer())->statusCode(404);
                            }
                        } else {
                            // Если есть урок и он завершен, дать доступ к текущему уроку
                            if (!empty($previous_lesson) and !empty($previous_lesson->lesson_student->is_finished) == true) {
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
                                if (!empty($coursework->lesson_student->is_finished) == true) {
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
                            if (!empty($coursework->lesson_student->is_finished) == true) {
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

    public function lessonAttachments($lang, Lesson $lesson){
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
                        'link' => $video
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
                        'link' => $video
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
                        'link' => $audio
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
                        'link' => $another_file
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

        if ($lesson->end_lesson_type == 0){
            $lesson->practice = json_decode($lesson->practice);
        }

        $data = [
            'id' => $lesson->id,
            'name' => $lesson->name,
            'type' => $lesson->lesson_type['name_'.$lang] ?? $lesson->lesson_type->name_ru,
            'end_lesson_type' => $lesson->end_lesson_type,
            'theory' => $lesson->theory,
            'image' => $lesson->getAvatar(),
            'practice' => $lesson->practice,
            'videos' => $videos,
            'youtube_videos' => $youtube_videos,
            'audios' => $audios,
            'another_files' => $another_files

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

}
