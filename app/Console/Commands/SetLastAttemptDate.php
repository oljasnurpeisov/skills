<?php

namespace App\Console\Commands;

use App\Models\StudentCourse;
use App\Models\StudentLessonAnswerAttempt;
use Illuminate\Console\Command;

class SetLastAttemptDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:last_attempt_date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set last quiz attempt date to Student Course';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $courses = StudentCourse::all();
        $count = count($courses);
        echo 'Получено '.$count.' записей курсов'. PHP_EOL;
        foreach($courses AS $course)
        {
            if (empty($course->last_attempts_date))
            {
                $attempt = StudentLessonAnswerAttempt::where('course_id', '=', $course->course_id)
                    ->where('student_id', '=', $course->student_id)
                    ->get()
                    ->last();
                if ($attempt)
                {
                    $course->last_attempts_date = $attempt->created_at;
                    $course->save();
                    echo 'Курс '.$course->id.' сохранен успешно!'. PHP_EOL;
                }
            }
        }

        echo 'Запись last_attempts_date в таблицу student_course завершена!'. PHP_EOL;
    }
}
