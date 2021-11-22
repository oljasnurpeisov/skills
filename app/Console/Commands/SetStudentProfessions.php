<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StudentCourse;
use App\Models\CourseSkill;
use App\Models\StudentProfessions;

class SetStudentProfessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:student_professions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set professions mastered by the students';

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
            $course_skills = CourseSkill::where('course_id', '=', $course->course_id)
                ->whereNotNull('profession_id')
                ->get();
            foreach ($course_skills as $course_skill)
            {
                $student_profession = StudentProfessions::where('user_id', '=', $course->student_id)
                    ->where('profession_id', '=', $course_skill->profession_id)
                    ->where('course_id', '=', $course->course_id)
                    ->first();
                if (!$student_profession) {
                    $student_profession = new StudentProfessions();
                    $student_profession->user_id = $course->student_id;
                    $student_profession->profession_id = $course_skill->profession_id;
                    $student_profession->professional_area_id = $course_skill->professional_area_id;
                    $student_profession->course_id = $course->course_id;
                    $student_profession->is_finished = $course->is_finished;
                    $student_profession->created_at = $course->created_at;
                    $student_profession->updated_at = $course->updated_at;
                    $student_profession->save();

                    echo 'Курс '.$course->id.' сохранен успешно!'. PHP_EOL;
                }
            }
        }

        echo 'Запись профессии в таблицу student_professions завершена!'. PHP_EOL;
    }
}
