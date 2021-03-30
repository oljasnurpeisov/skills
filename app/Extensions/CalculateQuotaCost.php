<?php

namespace App\Extensions;

use App\Models\Course;
use App\Models\CourseQuotaCost;

/**
 *
 * Класс отвечающий за расчет стоимости курса по квоте
 *
 */
class CalculateQuotaCost
{
    public static function calculate_quota_cost(Course $course, bool $withRate = false)
    {
        // Длительность курса
        $course_duration = self::course_duration($course);
        /* Нагрузка преподавателя в год
           18 часов * 4 недели * 10 месяцев = 720
        */
        $year_teacher_load = 720;
        // Коэффициент по категории и стажу
        $category_experience_coefficient = 5.01;
        /* Базовый должностной оклад
           Поправка для сотрудников образования 17697 * 1.5 = 26545.50
        */
        $base_salary = 26545.50;
        // Должностной оклад
        $official_salary = $base_salary * $category_experience_coefficient;
        /* Годовая заработная плата
           Должностной оклад * 13 месяцев
        */
        $year_salary = $official_salary * 13;
        // Стоимость часа
        $hour_cost = $year_salary / $year_teacher_load;
        // Надбавка
        $increase = self::calculate_increase_coefficient_cost($course, $withRate);
        $increase = $course_duration * (1 + ($increase / 100));
        // Базовая стоимость курса
        $base_course_cost = $hour_cost * $increase;
        // Стоимость курса на одного человека
        $course_cost_person = $base_course_cost / CourseQuotaCost::group_members_count;

        return round($course_cost_person);
    }

    private static function calculate_increase_coefficient_cost(Course $course, bool $withRate = false)
    {
        // Коэффициенты
        $coefficient_1 = 0;
        $coefficient_2 = 0.5;
        $coefficient_3 = 0.75;
        $coefficient_4 = 1;
        // Надбавка
        $increase = 0;
        // Доля коэффициента
        $content_format_percent = 50;
        $lesson_practice_percent = 10;
        $rate_percent = 20;
        $lang_percent = 10;
        $poor_vision_and_hearing_percent = 10;

        // Количество вложений курса
        $attachments_forms_count = self::attachments_forms_count($course);
        // Промежуточный тест или практическое задание, 0 - отсутствуют, 1 - разработаны к отдельным модулям, 2 - есть в каждом модуле
        $practice_status = self::practice_status($course);
        // Рейтинг, 0 - от 1 до 2 из 5, 1 - от 2 до 3 из 5, 2 - от 4 до 5 из 5
        $rate_status = self::rate_status($course);

        // Посчитать надбавку
        // Подсчет надбавки по формам
        if ($attachments_forms_count <= 2 and $attachments_forms_count > 0) {
            $increase += $content_format_percent * $coefficient_3;
            // Подсчет надбавки по языку
            if ($course->lang == 0) {
                if ($attachments_forms_count == 2) {
                    $increase += $lang_percent * $coefficient_3;
                } else if ($attachments_forms_count == 1) {
                    $increase += $lang_percent * $coefficient_2;
                }
            }
        } else if ($attachments_forms_count >= 3) {
            $increase += $content_format_percent * $coefficient_4;
            // Подсчет надбавки по языку
            if ($course->lang == 0) {
                $increase += $lang_percent * $coefficient_4;
            }
        }
        // Подсчет надбавки по практическим заданиям
        switch ($practice_status) {
            case 0:
                $increase += $lesson_practice_percent * $coefficient_1;
                break;
            case 1:
                $increase += $lesson_practice_percent * $coefficient_2;
                break;
            case 2:
                $increase += $lesson_practice_percent * $coefficient_4;
        }
        // Подсчет надбавки по рейтингу
        if ($withRate == true) {
            switch ($rate_status) {
                case 0:
                    $increase += $rate_percent * $coefficient_1;
                    break;
                case 1:
                    $increase += $rate_percent * $coefficient_2;
                    break;
                case 2:
                    $increase += $rate_percent * $coefficient_4;
            }
        }
        // Подсчет надбавки по доступной среде
        $poor_opportunities_count = $course->is_poor_vision + $course->is_poor_hearing;
        switch ($poor_opportunities_count) {
            case 0:
                $increase += $poor_vision_and_hearing_percent * $coefficient_1;
                break;
            case 1:
                $increase += $poor_vision_and_hearing_percent * $coefficient_2;
                break;
            case 2:
                $increase += $poor_vision_and_hearing_percent * $coefficient_4;
        }

        return $increase;
    }

    private static function attachments_forms_count(Course $course)
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

    private static function practice_status(Course $course)
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

    private static function rate_status(Course $course)
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

    private static function course_duration(Course $course)
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
}
