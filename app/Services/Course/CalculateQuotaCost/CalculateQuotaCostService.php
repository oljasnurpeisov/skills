<?php

namespace Services\Course\CalculateQuotaCost;

use App\Models\Course;

class CalculateQuotaCostService
{
    /**
     * Длительность курса
     * @param Course $course
     * @return float|int
     */
    public function courseDurationService(Course $course)
    {
        // Длительность курса
        $course_duration = 0;
        foreach ($course->lessons as $lesson) {
            // Время урока
            if ($lesson->type != 3 and $lesson->type != 4) {
                $course_duration += $lesson->duration;
            }
        }
        // Конвертирование минут в часы
        $course_duration = $course_duration / 60;

        return $course_duration;
    }

    /**
     * Формат учебного контента
     *
     * @param Course $course
     * @return int
     */
    public function attachments_forms_count(Course $course)
    {
        // Вложения курса
        $attachments_forms_count = 1; // По умолчанию 1 - текст и изображения курса присутсвует всегда
        $videos_forms_count = 0;
        $audios_forms_count = 0;
        $files_forms_count = 0;

        foreach ($course->lessons as $lesson) {
            if ($lesson->lesson_attachment != null) {
                // Видео
                if ($lesson->lesson_attachment->videos != null) {
                    $videos_forms_count += count(json_decode($lesson->lesson_attachment->videos));
                }
                if ($lesson->lesson_attachment->videos_poor_vision != null && $course->is_poor_vision == true) {
                    $videos_forms_count += count(json_decode($lesson->lesson_attachment->videos_poor_vision));
                }
                if ($lesson->lesson_attachment->videos_poor_hearing != null && $course->is_poor_hearing == true) {
                    $videos_forms_count += count(json_decode($lesson->lesson_attachment->videos_poor_hearing));
                }
                // Ссылки на видео
                if ($lesson->lesson_attachment->videos_link != null) {
                    if (json_decode($lesson->lesson_attachment->videos_link) != [null]) {
                        $videos_forms_count += count(json_decode($lesson->lesson_attachment->videos_link));
                    }
                }
                if ($lesson->lesson_attachment->videos_poor_vision_link != null && $course->is_poor_vision == true) {
                    if (json_decode($lesson->lesson_attachment->videos_poor_vision_link) != [null]) {
                        $videos_forms_count += count(json_decode($lesson->lesson_attachment->videos_poor_vision_link));
                    }
                }
                if ($lesson->lesson_attachment->videos_poor_hearing_link != null && $course->is_poor_hearing == true) {
                    if (json_decode($lesson->lesson_attachment->videos_poor_hearing_link) != [null]) {
                        $videos_forms_count += count(json_decode($lesson->lesson_attachment->videos_poor_hearing_link));
                    }
                }
                // Аудио
                if ($lesson->lesson_attachment->audios != null) {
                    $audios_forms_count += count(json_decode($lesson->lesson_attachment->audios));
                }
                if ($lesson->lesson_attachment->audios_poor_vision != null && $course->is_poor_vision == true) {
                    $audios_forms_count += count(json_decode($lesson->lesson_attachment->audios_poor_vision));
                }
                if ($lesson->lesson_attachment->audios_poor_hearing != null && $course->is_poor_hearing == true) {
                    $audios_forms_count += count(json_decode($lesson->lesson_attachment->audios_poor_hearing));
                }
                // Другие файлы
                if ($lesson->lesson_attachment->another_files != null) {
                    $files_forms_count += count(json_decode($lesson->lesson_attachment->another_files));
                }
                if ($lesson->lesson_attachment->another_files_poor_vision != null && $course->is_poor_vision == true) {
                    $files_forms_count += count(json_decode($lesson->lesson_attachment->another_files_poor_vision));
                }
                if ($lesson->lesson_attachment->another_files_poor_hearing != null && $course->is_poor_hearing == true) {
                    $files_forms_count += count(json_decode($lesson->lesson_attachment->another_files_poor_hearing));
                }
            }
        }
        // Посчитать количество форм
        if ($videos_forms_count > 0) {
            $attachments_forms_count++;
        }
        if ($audios_forms_count > 0) {
            $attachments_forms_count++;
        }
        if ($files_forms_count > 0) {
            $attachments_forms_count++;
        }

        return $attachments_forms_count;
    }

    /**
     * Контрольно-измерительные материалы
     *
     * @param Course $course
     * @return int
     */
    public function practice_status(Course $course)
    {
        // Количество уроков
        $lessons_count = 0;
        // Количество тестов
        $practice_count = 0;
        // Промежуточный тест или практическое задание, 0 - отсутствуют, 1 - разработаны к отдельным модулям, 2 - есть в каждом модуле
        $practice_status = 0;

        foreach ($course->lessons as $lesson) {
            // Посчитать количество уроков без учета курсовой и финального теста
            if ($lesson->type != 3 and $lesson->type != 4) {
                $lessons_count++;
            }
            // Тесты
            if ($lesson->end_lesson_type == 0 and $lesson->type == 2) {
                $practice_count++;
            }
            // Практическое задание
            if ($lesson->end_lesson_type == 1 and $lesson->type == 2) {
                $practice_count++;
            }
        }
        // Посчитать промежуточные тесты
        if ($practice_count < $lessons_count and $practice_count != 0) {
            $practice_status = 1;
        } else if ($practice_count == $lessons_count) {
            $practice_status = 2;
        } else if ($practice_count == 0) {
            $practice_status = 0;
        }

        return $practice_status;
    }

    /**
     * Уровень рейтинга курса
     *
     * @param Course $course
     * @return int
     */
    public function rate_status(Course $course)
    {
        // Рейтинг, 0 - от 1 до 2 из 5, 1 - от 2 до 3 из 5, 2 - от 4 до 5 из 5
        $rate_status = 0;
        // Посчитать оценки по курсу
        $course_rate_avg = $course->rate->avg('rate') ?? 0;
        if ($course_rate_avg < 2) {
            $rate_status = 0;
        } else if ($course_rate_avg >= 2 and $course_rate_avg < 4) {
            $rate_status = 1;
        } else if ($course_rate_avg >= 4) {
            $rate_status = 2;
        }

        return $rate_status;
    }
}
