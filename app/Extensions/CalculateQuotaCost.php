<?php

namespace App\Extensions;

use App\Models\Course;
use App\Models\CourseQuotaCost;

class CalculateQuotaCost
{

    public static function calculate_quota_cost(Course $course, Bool $withRate = false)
    {

        $content_format_percent = 60;
        $lesson_tests_percent = 4;
        $final_test_percent = 6;
        $rate_percent = 10;
        $lang_percent = 10;
        $poor_vision_percent = 10;
        $coefficient_1 = 0;
        $coefficient_2 = 0.5;
        $coefficient_3 = 1;
        $coefficient_4 = 0.1;
        $hour_cost = CourseQuotaCost::hour_cost;
        $group_members_count = CourseQuotaCost::group_members_count;

        $increase = 0;

        // Количество уроков
        $lessons_count = 0;
        // Вложения курса
        $attachments_forms_count = 0;
        $videos_forms_count = 0;
        $audios_forms_count = 0;
        $files_forms_count = 0;
        // Тесты, 0 - отсутствуют, 1 - разработаны к отдельным модулям, 2 - есть в каждом модуле
        $test_status = 0;
        // Количество тестов
        $tests_count = 0;
        // Рейтинг, 0 - менее 30 %, 1 - 31-50%, 2 - 51% и более
        $rate_status = 0;
        // Длительность курса
        $course_duration = 0;

        foreach ($course->lessons as $lesson) {
            // Посчитать количество уроков без учета курсовой и финального теста
            if ($lesson->type != 3 and $lesson->type != 4){
                $lessons_count++;
            }

            if ($lesson->lesson_attachment != null) {
                // Видео
                if ($lesson->lesson_attachment->videos != null) {
                    $videos_forms_count += count(json_decode($lesson->lesson_attachment->videos));
                }
                if ($lesson->lesson_attachment->videos_poor_vision != null) {
                    $videos_forms_count += count(json_decode($lesson->lesson_attachment->videos_poor_vision));
                }
                // Ссылки на видео
                if ($lesson->lesson_attachment->videos_link != null) {
                    if (json_decode($lesson->lesson_attachment->videos_link) != [null]) {
                        $videos_forms_count += count(json_decode($lesson->lesson_attachment->videos_link));
                    }
                }
                if ($lesson->lesson_attachment->videos_poor_vision_link != null) {
                    if (json_decode($lesson->lesson_attachment->videos_poor_vision_link) != [null]) {
                        $videos_forms_count += count(json_decode($lesson->lesson_attachment->videos_poor_vision_link));
                    }
                }
                // Аудио
                if ($lesson->lesson_attachment->audios != null) {
                    $audios_forms_count += count(json_decode($lesson->lesson_attachment->audios));
                }
                if ($lesson->lesson_attachment->audios_poor_vision != null) {
                    $audios_forms_count += count(json_decode($lesson->lesson_attachment->audios_poor_vision));
                }
                // Другие файлы
                if ($lesson->lesson_attachment->another_files != null) {
                    $files_forms_count += count(json_decode($lesson->lesson_attachment->another_files));
                }
                if ($lesson->lesson_attachment->another_files_poor_vision != null) {
                    $files_forms_count += count(json_decode($lesson->lesson_attachment->another_files_poor_vision));
                }
            }
            // Тесты
            if ($lesson->end_lesson_type == 0 and $lesson->type == 2){
                $tests_count++;
            }
            // Время урока
            if ($lesson->type != 3 and $lesson->type != 4) {
                $course_duration += $lesson->duration;
            }
        }
        // Финальный тест
        if ($course->finalTest() != null) {
            $questions_count = count(json_decode($course->finalTest()->practice)->questions);
        }else{
            $questions_count = 0;
        }
        // Посчитать количетсво вопросов в финальном тесте
        // Статус финального теста, 0 - отсутствует или меньше 20, 1 - 20-25 заданий, 25-35
        if ($questions_count >= 20 and $questions_count <= 25 and $questions_count != 0) {
            $final_test_status = 1;
        }else if ($questions_count >= 26){
            $final_test_status = 2;
        }else{
            $final_test_status = 0;
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
        // Посчитать промежуточные тесты
        if ($tests_count < $lessons_count and $tests_count != 0){
            $test_status = 1;
        } else if ($tests_count == $lessons_count) {
            $test_status = 2;
        } else if ($tests_count == 0) {
            $test_status = 0;
        }
        // Посчитать оценки по курсу
        $course_rate_avg = $course->rate->avg('rate') ?? 0;
        $course_rate_avg_percent = ($course_rate_avg * 100) / 5;
        if ($course_rate_avg_percent < 30){
            $rate_status = 0;
        } else if ($course_rate_avg_percent >= 31 and $course_rate_avg_percent <= 50){
            $rate_status = 1;
        } else if ($course_rate_avg_percent >= 51) {
            $rate_status = 2;
        }
        // Посчитать надбавку
        // Подсчет надбавки по формам
        if ($attachments_forms_count <= 2 and $attachments_forms_count > 0 ){
            $increase += $content_format_percent * $coefficient_2;
            // Подсчет надбавки по языку
            if ($course->lang == 0) {
                $increase += $lang_percent * $coefficient_2;
            }
        } else if ($attachments_forms_count >= 3) {
            $increase += $content_format_percent * $coefficient_3;
            if ($course->lang == 0) {
                $increase += $lang_percent * $coefficient_3;
            }
        } else if ($attachments_forms_count == 0) {
            $increase += $content_format_percent * $coefficient_1;
            if ($course->lang == 0) {
                $increase += $lang_percent * $coefficient_4;
            }
        }
        // Подсчет надбавки по тестам
        switch ($test_status){
            case 0:
                $increase += $final_test_percent * $coefficient_1;
                break;
            case 1:
                $increase += $final_test_percent * $coefficient_2;
                break;
            case 2:
                $increase += $final_test_percent * $coefficient_3;
        }
        // Подсчет надбавки по финальному тесту
        switch ($final_test_status){
            case 0:
                $increase += $lesson_tests_percent * $coefficient_1;
                break;
            case 1:
                $increase += $lesson_tests_percent * $coefficient_2;
                break;
            case 2:
                $increase += $lesson_tests_percent * $coefficient_3;
        }
        // Подсчет надбавки по рейтингу
        if ($withRate == true){
            switch ($rate_status){
                case 0:
                    $increase += $rate_percent * $coefficient_1;
                    break;
                case 1:
                    $increase += $rate_percent * $coefficient_2;
                    break;
                case 2:
                    $increase += $rate_percent * $coefficient_3;
            }
        }
        // Подсчет надбавки по доступной среде
        if ($course->is_poor_vision == true) {
            $increase += $poor_vision_percent * $coefficient_2;
        }else{
            $increase += $poor_vision_percent * $coefficient_1;
        }
        // Подсчет времени курса
        $course_duration = $course_duration / 60;
        // Подсчет стоимости курса
        $course_cost = $hour_cost * round($course_duration);
        $increase_cost = ($course_cost * $increase) / 100;
        $course_cost = $course_cost + $increase_cost;
        $course_cost_person = $course_cost / $group_members_count;

        return round($course_cost_person);
    }

}
