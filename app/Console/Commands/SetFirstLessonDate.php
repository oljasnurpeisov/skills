<?php

namespace App\Console\Commands;

use App\Models\Lesson;
use App\Models\Professions;
use App\Models\StudentCourse;
use App\Models\StudentLesson;
use Illuminate\Console\Command;
use Orchestra\Parser\Xml\Facade as XmlParser;

class SetFirstLessonDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:first_lesson_date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set first lesson date to Student Course';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $courses = StudentCourse::all();
        $count = count($courses);
        echo 'Получено '.$count.'записей курсов'. PHP_EOL;
        foreach($courses AS $course)
        {
            if (empty($course->first_lesson_date))
            {
                $firstLesson = Lesson::where('course_id', '=', $course->course_id)
                    ->orderBy('theme_id', 'asc')
                    ->orderBy('index_number', 'asc')
                    ->first();
//
                $studentLesson = StudentLesson::where('lesson_id', '=', $firstLesson->id)
                    ->where('student_id', '=', $course->student_id)
                    ->first();
                if ($studentLesson)
                {
                    $course->first_lesson_date = $studentLesson->created_at;
                    $course->save();
                    echo 'Курс '.$course->id.' сохранен успешно!'. PHP_EOL;
                }
            }
        }

        echo 'Запись first_lesson_date в таблицу student_course завершена!'. PHP_EOL;
    }
}
