<?php

namespace App\Extensions;

use App\Models\Course;
use App\Models\CourseQuotaCost;
use Services\Course\CalculateQuotaCost\CalculateQuotaCostService;

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
        return (new CalculateQuotaCostService())->attachments_forms_count($course);
    }

    private static function practice_status(Course $course)
    {
       return (new CalculateQuotaCostService())->practice_status($course);
    }

    private static function rate_status(Course $course)
    {
        return (new CalculateQuotaCostService())->rate_status($course);
    }

    private static function course_duration(Course $course)
    {
        return (new CalculateQuotaCostService())->courseDurationService($course);
    }
}
