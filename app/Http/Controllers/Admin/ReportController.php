<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AuthorReportExport;
use App\Exports\CourseReportExport;
use App\Exports\ReportingExport;
use App\Exports\StudentReportExport;
use App\Models\Course;
use App\Models\Professions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function authorsReports(Request $request)
    {
        // Сортировка
        $sortByName = $request->sortByName;
        $sortByCoursesCount = $request->sortByCoursesCount;
        $sortByPaidCoursesCount = $request->sortByPaidCoursesCount;
        $sortByFreeCoursesCount = $request->sortByFreeCoursesCount;
        $sortByQuotaCoursesCount = $request->sortByQuotaCoursesCount;
        $sortByStudentsCount = $request->sortByStudentsCount;
        $sortByCertificateStudentsCount = $request->sortByCertificateStudentsCount;
        $sortByRateAuthor = $request->sortByRateAuthor;
        // Фильтрация
        $author_name = $request->author_name ? $request->author_name : '';
        $specialization = $request->specialization ? $request->specialization : '';
        $rate_from = $request->rate_from;
        $rate_to = $request->rate_to;
        $courses_count_from = $request->courses_count_from;
        $courses_count_to = $request->courses_count_to;
        $paid_courses_count_from = $request->paid_courses_count_from;
        $paid_courses_count_to = $request->paid_courses_count_to;
        $free_courses_count_from = $request->free_courses_count_from;
        $free_courses_count_to = $request->free_courses_count_to;
        $quota_courses_count_from = $request->quota_courses_count_from;
        $quota_courses_count_to = $request->quota_courses_count_to;
        $course_members_count_from = $request->course_members_count_from;
        $course_members_count_to = $request->course_members_count_to;
        $certificates_count_from = $request->certificates_count_from;
        $certificates_count_to = $request->certificates_count_to;
        $qualifications_count_from = $request->qualifications_count_from;
        $qualifications_count_to = $request->qualifications_count_to;

        $query = User::whereHas('roles', function ($q) {
            $q->whereSlug('author');
        })->where('email_verified_at', '!=', null);

        // Сортировка
        // Сортировка по имени автора
        if ($sortByName) {
            $query->with('author_info')->orderBy('name', $sortByName);
        }
        // Сортировка по количеству курсов
        if ($sortByCoursesCount) {
            $query->withCount('courses')->orderBy('courses_count', $sortByCoursesCount);
        }
        // Сортировка по количеству платных курсов
        if ($sortByPaidCoursesCount) {
            $query->withCount(['courses as paid_courses_count' => function ($q) {
                $q->where('is_paid', '=', true);
            }])->orderBy('paid_courses_count', $sortByPaidCoursesCount);
        }
        // Сортировка по количеству бесплатных курсов
        if ($sortByFreeCoursesCount) {
            $query->withCount(['courses as free_courses_count' => function ($q) {
                $q->where('is_paid', '=', false);
            }])->orderBy('free_courses_count', $sortByFreeCoursesCount);
        }
        // Сортировка по количеству курсов по квоте
        if ($sortByQuotaCoursesCount) {
            $query->withCount(['courses as quota_courses_count' => function ($q) {
                $q->where('quota_status', '=', 2);
            }])->orderBy('quota_courses_count', $sortByQuotaCoursesCount);
        }
        // Сортировка по количеству курсов по квоте
        if ($sortByQuotaCoursesCount) {
            $query->withCount(['courses as quota_courses_count' => function ($q) {
                $q->where('quota_status', '=', 2);
            }])->orderBy('quota_courses_count', $sortByQuotaCoursesCount);
        }
        // Сортировка по количеству обучающихся
        if ($sortByStudentsCount) {
            $query->withCount(['courses as members_count' => function ($q) use ($sortByStudentsCount) {
                $q->whereHas('course_members', function ($q) use ($sortByStudentsCount) {
                    $q->whereIn('paid_status', [1, 2]);
                });
            }])->orderBy('members_count', $sortByStudentsCount);
        }
        // Сортировка по рейтингу
        if ($sortByRateAuthor) {
            $query->withCount(['author_rates as average_rate' => function ($query) {
                $query->select(DB::raw('round(avg(rate),1)'));
            }])->orderBy('average_rate', $sortByRateAuthor);
        }
        //
        if ($sortByCertificateStudentsCount) {
            $query->withCount(['courses as certificate_members_count' => function ($q) {
                $q->whereHas('course_members', function ($q) {
                    $q->where('is_finished', '=', true);
                });
            }])->orderBy('certificate_members_count', $sortByCertificateStudentsCount);
        }
        // Фильтрация
        // Поиск по имени
        if ($author_name) {
            $query->whereHas('author_info', function ($q) use ($author_name) {
                $q->where('name', 'like', '%' . $author_name . '%')
                    ->orWhere('surname', 'like', '%' . $author_name . '%');
            });
        }
        // Поиск по специализации
        if ($specialization) {
            $query->whereHas('author_info', function ($q) use ($specialization) {
                $q->where('specialization', 'like', '%' . $specialization . '%');
            });
        }
        // Поиск по количеству курсов
        if ($courses_count_from and empty($courses_count_to)) {
            $query->withCount('courses')->having('courses_count', '>=', $courses_count_from);
        } else if ($courses_count_to and empty($courses_count_from)) {
            $query->withCount('courses')->having('courses_count', '<=', $courses_count_to);
        } else if ($courses_count_to and $courses_count_from) {
            $query->withCount('courses')->having('courses_count', '>=', $courses_count_from)
                ->having('courses_count', '<=', $courses_count_to);
        }
        // Поиск по количеству платных курсов
        if ($paid_courses_count_from and empty($paid_courses_count_to)) {
            $query->withCount(['courses as paid_courses_count' => function ($q) {
                $q->where('is_paid', '=', true);
            }])->having('paid_courses_count', '>=', $paid_courses_count_from);
        } else if ($paid_courses_count_to and empty($paid_courses_count_from)) {
            $query->withCount(['courses as paid_courses_count' => function ($q) {
                $q->where('is_paid', '=', true);
            }])->having('paid_courses_count', '<=', $paid_courses_count_to);
        } else if ($paid_courses_count_to and $paid_courses_count_from) {
            $query->withCount(['courses as paid_courses_count' => function ($q) {
                $q->where('is_paid', '=', true);
            }])->having('paid_courses_count', '>=', $paid_courses_count_from)
                ->having('paid_courses_count', '<=', $paid_courses_count_to);
        }
        // Поиск по количеству бесплатных курсов
        if ($free_courses_count_from and empty($free_courses_count_to)) {
            $query->withCount(['courses as free_courses_count' => function ($q) {
                $q->where('is_paid', '=', false);
            }])->having('free_courses_count', '>=', $free_courses_count_from);
        } else if ($free_courses_count_to and empty($free_courses_count_from)) {
            $query->withCount(['courses as free_courses_count' => function ($q) {
                $q->where('is_paid', '=', false);
            }])->having('free_courses_count', '<=', $free_courses_count_to);
        } else if ($free_courses_count_to and $free_courses_count_from) {
            $query->withCount(['courses as free_courses_count' => function ($q) {
                $q->where('is_paid', '=', false);
            }])->having('free_courses_count', '>=', $free_courses_count_from)
                ->having('free_courses_count', '<=', $free_courses_count_to);
        }
        // Поиск по количеству курсов по квоте
        if ($quota_courses_count_from and empty($quota_courses_count_to)) {
            $query->withCount(['courses as quota_courses_count' => function ($q) {
                $q->where('quota_status', '=', 2);
            }])->having('quota_courses_count', '>=', $quota_courses_count_from);
        } else if ($quota_courses_count_to and empty($quota_courses_count_from)) {
            $query->withCount(['courses as quota_courses_count' => function ($q) {
                $q->where('quota_status', '=', 2);
            }])->having('quota_courses_count', '<=', $quota_courses_count_to);
        } else if ($quota_courses_count_to and $quota_courses_count_from) {
            $query->withCount(['courses as quota_courses_count' => function ($q) {
                $q->where('quota_status', '=', 2);
            }])->having('quota_courses_count', '>=', $quota_courses_count_from)
                ->having('quota_courses_count', '<=', $quota_courses_count_to);
        }
        // Поиск по рейтингу автора
        if ($rate_from and empty($rate_to)) {
            $query->whereHas('courses.rate', function ($q) use ($rate_from) {
                $q->havingRaw('round(AVG(rate),1) >= ' . $rate_from);
            });
        } else if ($rate_to and empty($rate_from)) {
            $query->whereHas('courses.rate', function ($q) use ($rate_to) {
                $q->havingRaw('round(AVG(rate),1) <= ' . $rate_to);
            });
        } else if ($rate_to and $rate_from) {
            $query->whereHas('courses.rate', function ($q) use ($rate_to, $rate_from) {
                $q->havingRaw('round(AVG(rate),1) >= ' . $rate_from);
                $q->havingRaw('round(AVG(rate),1) <= ' . $rate_to);
            });
        }
        // Поиск по количеству обучающихся
        if ($course_members_count_from and empty($course_members_count_to)) {
            $query->whereHas('courses.course_members', function ($q) use ($course_members_count_from) {
                $q->where('paid_status', '!=', 0)
                    ->havingRaw('count(*) >= ' . $course_members_count_from);
            });
        } else if ($course_members_count_to and empty($course_members_count_from)) {
            $query->whereHas('courses.course_members', function ($q) use ($course_members_count_to) {
                $q->where('paid_status', '!=', 0)
                    ->havingRaw('count(*) <= ' . $course_members_count_to);
            });
        } else if ($course_members_count_to and $course_members_count_from) {
            $query->whereHas('courses.course_members', function ($q) use ($course_members_count_to, $course_members_count_from) {
                $q->where('paid_status', '!=', 0)
                    ->havingRaw('count(*) >= ' . $course_members_count_from)
                    ->havingRaw('count(*) <= ' . $course_members_count_to);
            });
        }
        // Поиск по количеству сертифицированных
        if ($certificates_count_from and empty($certificates_count_to)) {
            $query->whereHas('courses.course_members', function ($q) use ($certificates_count_from) {
                $q->where('is_finished', '=', true)
                    ->havingRaw('count(*) >= ' . $certificates_count_from);
            });
        } else if ($certificates_count_to and empty($certificates_count_from)) {
            $query->whereHas('courses.course_members', function ($q) use ($certificates_count_to) {
                $q->where('is_finished', '=', true)
                    ->havingRaw('count(*) <= ' . $certificates_count_to);
            });
        } else if ($certificates_count_to and $certificates_count_from) {
            $query->whereHas('courses.course_members', function ($q) use ($certificates_count_to, $certificates_count_from) {
                $q->where('is_finished', '=', true)
                    ->havingRaw('count(*) >= ' . $certificates_count_from)
                    ->havingRaw('count(*) <= ' . $certificates_count_to);
            });
        }
        $items = $query->paginate(10);

        foreach ($items as $item) {
            $rates_array = [];
            $author_students = [];
            $author_students_finished = [];
            $author_students_finished_courseWork = [];
            foreach ($item->courses as $course) {
                // Количество отзывов
                foreach ($course->rate as $rate) {
                    $rates_array[] = $rate->rate;
                }
                // Количество обучающихся во всех курсах автора
                foreach ($course->course_members->where('paid_status', '!=', 0) as $member) {
                    $author_students[$member['student_id']][] = $member;
                }
                // Количество завершивших курсы
                foreach ($course->course_members->where('is_finished', '=', true) as $member) {
                    $author_students_finished[$member['student_id']][] = $member;
                }
                if ($course->courseWork()) {
                    $author_students_finished_courseWork[] = $course->courseWork()->finishedLesson();
                }
            }
            // Оценка автора исходя из всех оценок
            if (count($rates_array) == 0) {
                $item->average_rates = 0;
            } else {
                $item->average_rates = array_sum($rates_array) / count($rates_array);
            }
            // Количество уникальных обучающихся
            $item->members = $author_students;
            // Количество сертифицированных
            $item->certificate_members = $author_students_finished;
            // Количество подтвердивших квалификацию
            $author_students_finished_courseWork_count = 0;
            foreach ($author_students_finished_courseWork as $lesson) {
                $author_students_finished_courseWork_count += count($lesson);
            }
            $item->qualification_students = $author_students_finished_courseWork_count;
        }
        Session::put('authors_report_export', $query->get());
//        return $items;
        return view('admin.v2.pages.reports.authors_report', [
            'items' => $items,
            'request' => $request
        ]);
    }

    public function coursesReports(Request $request)
    {
        // Фильтрация
        $course_name = $request->course_name ? $request->course_name : '';
        $author_name = $request->author_name ? $request->author_name : '';
        $skill = $request->skill ? $request->skill : '';
        $rate_from = $request->rate_from;
        $rate_to = $request->rate_to;
        $course_status = $request->course_status;
        $quota_status = $request->quota_status ?? [];
        $paid_status = $request->paid_status ?? [];
        $course_members_count_from = $request->course_members_count_from;
        $course_members_count_to = $request->course_members_count_to;
        $certificates_count_from = $request->certificates_count_from;
        $certificates_count_to = $request->certificates_count_to;
        $qualifications_count_from = $request->qualifications_count_from;
        $qualifications_count_to = $request->qualifications_count_to;
        // Сортировка
        $sortByName = $request->sortByName;
        $sortByAuthor = $request->sortByAuthor;
        $sortByCourseMembers = $request->sortByCourseMembers;
        $sortByCertificateCourseMembers = $request->sortByCertificateCourseMembers;
        $sortByRateCourse = $request->sortByRateCourse;
        $sortByQualificatedStudents = $request->sortByQualificatedStudents;

        $query = (new Course)->newQuery();
        // Сортировка
        // Сортировка по названию курса
        if ($sortByName) {
            $query->orderBy('name', $sortByName);
        }
        // Сортировка по автору
        if ($sortByAuthor) {
            $query->whereHas('users', function ($q) use ($sortByName) {
                $q->orderBy('company_name', $sortByName);
            });
        }
        // Сортировка количеству учащихся
        if ($sortByCourseMembers) {
            $query->withCount(['course_members' => function ($q) {
                $q->whereIn('paid_status', [1, 2]);
            }])->orderBy('course_members_count', $sortByCourseMembers);
        }
        // Сортировка количеству учащихся получившие сертификат
        if ($sortByCertificateCourseMembers) {
            $query->withCount(['course_members' => function ($q) {
                $q->where('is_finished', '=', true);
            }])->orderBy('course_members_count', $sortByCertificateCourseMembers);
        }
        // Сортировка по рейтингу
        if ($sortByRateCourse) {
            $query->withCount(['rate as average_rate' => function ($query) {
                $query->select(DB::raw('round(avg(rate),1)'));
            }])->orderBy('average_rate', $sortByRateCourse);
        }
        // Сортировка по квалифицированным обучающимся
        if ($sortByQualificatedStudents) {
            $query->withCount(['course_members as course_qualifications_members_count' => function ($q) {
                $q->where('is_finished', '=', true);
                $q->whereHas('student.student_lesson', function ($q) {
                    $q->where('is_finished', '=', true);
                });
            }])->whereHas('lessons', function ($q) {
                $q->where('type', '=', 3);
            })->orderBy('course_qualifications_members_count', $sortByQualificatedStudents);
        }
        // Фильтрация
        // Поиск по имени
        if ($course_name) {
            $query->where('name', 'like', '%' . $course_name . '%');
        }
        // Поиск по автору
        if ($author_name) {
            $query->whereHas('users', function ($q) use ($author_name) {
                $q->where('company_name', 'like', '%' . $author_name . '%');
            });
        }
        // Поиск по рейтингу
        if ($rate_from and empty($rate_to)) {
            $query->whereHas('rate', function ($q) use ($rate_from) {
                $q->havingRaw('round(AVG(rate),1) >= ' . $rate_from);
            });
        } else if ($rate_to and empty($rate_from)) {
            $query->whereHas('rate', function ($q) use ($rate_to) {
                $q->havingRaw('round(AVG(rate),1) <= ' . $rate_to);
            });
        } else if ($rate_to and $rate_from) {
            $query->whereHas('rate', function ($q) use ($rate_to, $rate_from) {
                $q->havingRaw('round(AVG(rate),1) >= ' . $rate_from);
                $q->havingRaw('round(AVG(rate),1) <= ' . $rate_to);
            });
        }
        // Поиск по навыкам
        if ($skill) {
            $query->whereHas('skills', function ($q) use ($skill) {
                $q->where('name_ru', 'like', '%' . $skill . '%')
                    ->orWhere('name_kk', 'like', '%' . $skill . '%')
                    ->orWhere('name_en', 'like', '%' . $skill . '%');
            });
        }

        // Поиск по статусу
        if (isset($course_status)) {
            $query->whereStatus($course_status);
        }
        // Поиск по доступности по квоте
        if ($quota_status) {
            if ($quota_status == ["0"]) {
                $query->where('quota_status', '!=', 2);
            } else if (in_array("2", $quota_status) and in_array("0", $quota_status)) {
                $query->whereIn('quota_status', [0, 1, 2, 3]);
            } else {
                $query->whereIn('quota_status', $quota_status);
            }
        }
        // Поиск по способу оплаты
        if ($paid_status) {
            $query->whereIn('is_paid', $paid_status);
        }
        // Поиск по количеству участников
        if ($course_members_count_from and empty($course_members_count_to)) {
            $query->withCount(['course_members' => function ($q) {
                $q->whereIn('paid_status', [1, 2]);
            }])->having('course_members_count', '>=', $course_members_count_from);
        } else if ($course_members_count_to and empty($course_members_count_from)) {
            $query->withCount(['course_members' => function ($q) {
                $q->whereIn('paid_status', [1, 2]);
            }])->having('course_members_count', '<=', $course_members_count_to);
        } else if ($course_members_count_to and $course_members_count_from) {
            $query->withCount(['course_members' => function ($q) {
                $q->whereIn('paid_status', [1, 2]);
            }])->having('course_members_count', '>=', $course_members_count_from)
                ->having('course_members_count', '<=', $course_members_count_to);
        }
        // Поиск по количеству получивших сертификат участников
        if ($certificates_count_from and empty($certificates_count_to)) {
            $query->withCount(['course_members as course_certificates_members_count' => function ($q) {
                $q->where('is_finished', '=', true);
            }])->having('course_certificates_members_count', '>=', $certificates_count_from);
        } else if ($certificates_count_to and empty($certificates_count_from)) {
            $query->withCount(['course_members as course_certificates_members_count' => function ($q) {
                $q->where('is_finished', '=', true);
            }])->having('course_certificates_members_count', '<=', $certificates_count_to);
        } else if ($certificates_count_to and $certificates_count_from) {
            $query->withCount(['course_members as course_certificates_members_count' => function ($q) {
                $q->where('is_finished', '=', true);
            }])->having('course_certificates_members_count', '>=', $certificates_count_from)
                ->having('course_certificates_members_count', '<=', $certificates_count_to);
        }
        // Поиск по квалифицированным участникам
        if ($qualifications_count_from and empty($qualifications_count_to)) {
            $query->withCount(['course_members as course_qualifications_members_count' => function ($q) {
                $q->where('is_finished', '=', true);
                $q->whereHas('student.student_lesson', function ($q) {
                    $q->where('is_finished', '=', true);
                });
            }])->whereHas('lessons', function ($q) {
                $q->where('type', '=', 3);
            })->having('course_qualifications_members_count', '>=', $qualifications_count_from);
        } else if ($qualifications_count_to and empty($qualifications_count_from)) {
            $query->withCount(['course_members as course_qualifications_members_count' => function ($q) {
                $q->where('is_finished', '=', true);
                $q->whereHas('student.student_lesson', function ($q) {
                    $q->where('is_finished', '=', true);
                });
            }])->whereHas('lessons', function ($q) {
                $q->where('type', '=', 3);
            })->having('course_qualifications_members_count', '<=', $qualifications_count_to);
        } else if ($qualifications_count_to and $qualifications_count_from) {
            $query->withCount(['course_members as course_qualifications_members_count' => function ($q) {
                $q->where('is_finished', '=', true);
                $q->whereHas('student.student_lesson', function ($q) {
                    $q->where('is_finished', '=', true);
                });
            }])->whereHas('lessons', function ($q) {
                $q->where('type', '=', 3);
            })->having('course_qualifications_members_count', '>=', $qualifications_count_from)
                ->having('course_qualifications_members_count', '<=', $qualifications_count_to);
        }

        $items = $query->paginate(10);

        Session::put('courses_report_export', $query->get());

        return view('admin.v2.pages.reports.courses_report', [
            'items' => $items,
            'request' => $request,
            'quota_status' => $quota_status,
            'paid_status' => $paid_status
        ]);
    }

    public function studentsReports(Request $request)
    {
        // Фильтрация
        $student_name = $request->student_name ? $request->student_name : '';
        $unemployed_status = $request->unemployed_status ?? [];
        $quota_count_from = $request->quota_count_from;
        $quota_count_to = $request->quota_count_to;
        $courses_count_from = $request->courses_count_from;
        $courses_count_to = $request->courses_count_to;
        $certificates_count_from = $request->certificates_count_from;
        $certificates_count_to = $request->certificates_count_to;
        $qualifications_count_from = $request->qualifications_count_from;
        $qualifications_count_to = $request->qualifications_count_to;
        // Сортировка
        $sortByName = $request->sortByName;
        $sortByQuota = $request->sortByQuota;

        $query = User::whereHas('roles', function ($q) {
            $q->whereSlug('student');
        });
        // Сортировка
        // Сортировка по названию курса
        if ($sortByName) {
            $query->with('student_info')
                ->join('student_information', 'users.id', '=', 'student_information.user_id')
                ->orderBy('student_information.name', $sortByName);
        }
        if ($sortByQuota) {
            $query->with('student_info')
                ->join('student_information', 'users.id', '=', 'student_information.user_id')
                ->orderBy('student_information.quota_count', $sortByQuota);
        }
        // Фильтрация
        // Поиск по имени
        if ($student_name) {
            $query->whereHas('student_info', function ($q) use ($student_name) {
                $q->where('name', 'like', '%' . $student_name . '%');
            });
        }
        // Поиск по статусу
        if ($unemployed_status) {
            $query->whereHas('student_info', function ($q) use ($unemployed_status) {
                $q->whereIn('unemployed_status', $unemployed_status);
            });
        }
        // Поиск по количеству квот
        if ($quota_count_from and empty($quota_count_to)) {
            $query->whereHas('student_info', function ($q) use ($quota_count_from) {
                $q->where('quota_count', '>=', $quota_count_from);
            });
        } else if ($quota_count_to and empty($quota_count_from)) {
            $query->whereHas('student_info', function ($q) use ($quota_count_to) {
                $q->where('quota_count', '>=', $quota_count_to);
            });
        } else if ($quota_count_to and $quota_count_from) {
            $query->whereHas('student_info', function ($q) use ($quota_count_to, $quota_count_from) {
                $q->whereBetween('quota_count', [$quota_count_from, $quota_count_to]);
            });
        }
        // Поиск по количеству курсов
        if ($courses_count_from and empty($courses_count_to)) {
            $query->withCount(['student_course' => function ($q) {
                $q->whereIn('paid_status', [1, 2]);
            }])->with(['student_course' => function ($q) {
                $q->whereIn('paid_status', [1, 2]);
            }])->having('student_course_count', '>=', $courses_count_from);
        } else if ($courses_count_to and empty($courses_count_from)) {
            $query->withCount(['student_course' => function ($q) {
                $q->whereIn('paid_status', [1, 2]);
            }])->with(['student_course' => function ($q) {
                $q->whereIn('paid_status', [1, 2]);
            }])->having('student_course_count', '<=', $courses_count_to);
        } else if ($courses_count_to and $courses_count_from) {
            $query->withCount(['student_course' => function ($q) {
                $q->whereIn('paid_status', [1, 2]);
            }])->with(['student_course' => function ($q) {
                $q->whereIn('paid_status', [1, 2]);
            }])->having('student_course_count', '>=', $courses_count_from)
                ->having('student_course_count', '<=', $courses_count_to);
        }
        // Поиск по количеству сертификатов
        if ($certificates_count_from and empty($certificates_count_to)) {
            $query->withCount(['student_course as student_course_certificates_count' => function ($q) {
                $q->where('is_finished', '=', true);
            }])->with(['student_course' => function ($q) {
                $q->where('is_finished', '=', true);
            }])->having('student_course_certificates_count', '>=', $certificates_count_from);
        } else if ($certificates_count_to and empty($certificates_count_from)) {
            $query->withCount(['student_course as student_course_certificates_count' => function ($q) {
                $q->where('is_finished', '=', true);
            }])->with(['student_course' => function ($q) {
                $q->where('is_finished', '=', true);
            }])->having('student_course_certificates_count', '<=', $certificates_count_to);
        } else if ($certificates_count_to and $certificates_count_from) {
            $query->withCount(['student_course as student_course_certificates_count' => function ($q) {
                $q->where('is_finished', '=', true);
            }])->with(['student_course' => function ($q) {
                $q->where('is_finished', '=', true);
            }])->having('student_course_certificates_count', '>=', $certificates_count_from)
                ->having('student_course_certificates_count', '<=', $certificates_count_to);
        }
        $qualifications_count = $query->withCount(['student_course as qualifications_count' => function ($query) {
            $query->whereHas('course.lessons', function ($q) {
                $q->where('type', '=', 3);
            });
            $query->whereHas('course.lessons.student_lessons', function ($q) {
                $q->where('is_finished', '=', true);
            });
        }]);
        // Поиск по количеству квалификаций
        if ($qualifications_count_from) {
            $qualifications_count->having('qualifications_count', '>=', $qualifications_count_from);
        } else if ($qualifications_count_to and empty($qualifications_count_from)) {
            $qualifications_count->having('qualifications_count', '<=', $qualifications_count_to);
        } else if ($qualifications_count_to and $qualifications_count_from) {
            $qualifications_count->having('qualifications_count', '>=', $qualifications_count_from)
                ->having('qualifications_count', '<=', $qualifications_count_to);
        }
//        return $query->get();
        $student_courses = [];
        //
        $items = $query->paginate(10);

        $i = [];
        foreach ($items as $item) {
            $finishedCourseWorks = 0;
            foreach ($item->student_course->whereIn('paid_status', [1, 2]) as $course) {
                if ($course->course->courseWork()) {
                    if ($item->student_lesson->where('lesson_id', '=', $course->course->courseWork()->id)) {
                        $i[] = $course->course->courseWork();
                        $finishedCourseWorks++;
                    }
                }
            }
            // Количество законченных курсовых работ
            $item->finishedCourseWorkrs = $finishedCourseWorks;
        }
        Session::put('students_report_export', $query->get());

        return view('admin.v2.pages.reports.students_report', [
            'items' => $items,
            'request' => $request,
            'unemployed_status' => $unemployed_status
        ]);
    }

    public function exportStudentsReport(Request $request)
    {
        $query = Session::get('students_report_export');
        $export = [[]];
        $lang = app()->getLocale();

        foreach ($query as $i) {
            // ФИО обучающегося
            $name = $i->student_info->name;
            // Статус безработного
            if ($i->unemployed_status == 0) {
                $unemployed_status = __('default.yes_title');
            } else {
                $unemployed_status = __('default.no_title');
            }
            // Кол-во квот
            $quota_count = $i->student_info->quota_count;
            // Кол-во курсов
            $courses_count = $i->student_course->whereIn('paid_status', [1, 2])->count();
            // Кол-во сертификатов
            $certificates = $i->student_course->where('is_finished', '=', true)->count();
            // Кол-во подтвержденных квалификаций
            $qualifications = $i->qualifications_count;

            $newElement = ['name' => $name, 'unemployed_status' => $unemployed_status, 'quota_count' => $quota_count,
                'courses_count' => $courses_count, 'certificates' => $certificates, 'qualifications' => $qualifications];

            array_push($export, $newElement);
        }

//        asort($export);
        return Excel::download(new StudentReportExport($export), '' . __('default.pages.courses.report_title') . '.xlsx');
    }

    public function exportCoursesReport(Request $request)
    {
        $query = Session::get('courses_report_export');
        $export = [[]];
        $lang = app()->getLocale();
        foreach ($query as $i) {
            // Наименование курса
            $name = $i->name;
            // Наименование автора
            $author_name = $i->user->company_name;
            // Навыки
            $skills = implode(', ', array_filter($i->skills->pluck('name_' . $lang)->toArray())) ?: implode(', ', $i->skills->pluck('name_ru')->toArray());
            // Группа профессий
            if (count($i->groupProfessionsBySkills()->pluck('id')->toArray()) <= 0) {
                $group_professions = '-';
            } else {
                $group_professions = implode(', ', array_filter($i->groupProfessionsBySkills()->pluck('name_' . $lang)->toArray())) ?: implode(', ', array_filter($i->groupProfessionsBySkills()->pluck('name_ru')->toArray()));
            }
            // Группа профессий
            if (count($i->professionsBySkills()->pluck('id')->toArray()) <= 0) {
                $professions = '-';
            } else {
                $professions = implode(', ', array_filter($i->professionsBySkills()->pluck('name_' . $lang)->toArray())) ?: implode(', ', array_filter($i->professionsBySkills()->pluck('name_ru')->toArray()));
            }
            // Рейтинг курса
            $rate = round($i->rate->pluck('rate')->avg() ?? 0, 1);
            // Статус курса
            $status = __('default.pages.reporting.statuses.' . $i->status);
            // Доступен по квоте
            $quota_access = $i->quota_status == 2 ? __('default.yes_title') : __('default.no_title');
            // Платежный статус
            $is_paid = $i->is_paid == true ? __('default.pages.reporting.paid_course') : __('default.pages.reporting.free_course');
            // Записано обучающихся
            $students_count = count($i->course_members->whereIn('paid_status', [1, 2]));
            // Получили сертификат
            $students_certificate_count = count($i->course_members->where('is_finished', '=', true));
            // Подтвердили квалификацию
            if ($i->courseWork()) {
                $students_qualification_count = $i->courseWork()->finishedLesson()->count();
            } else {
                $students_qualification_count = '-';
            }

            $newElement = ['name' => $name, 'author_name' => $author_name, 'skills' => $skills, 'group_professions' => $group_professions,
                'professions' => $professions, 'rate' => $rate, 'status' => $status, 'quota_access' => $quota_access,
                'is_paid' => $is_paid, 'students_count' => $students_count, 'students_certificate_count' => $students_certificate_count,
                'students_qualification_count' => $students_qualification_count];

            array_push($export, $newElement);
        }

//        asort($export);
        return Excel::download(new CourseReportExport($export), '' . __('default.pages.courses.report_title') . '.xlsx');
    }

    public function exportAuthorsReport(Request $request)
    {
        $query = Session::get('authors_report_export');
        $export = [[]];
        $lang = app()->getLocale();

        foreach ($query as $i) {
            $rates_array = [];
            $author_students = [];
            $author_students_finished = [];
            $author_students_finished_courseWork = [];

            foreach ($i->courses as $course) {
                // Количество отзывов
                foreach ($course->rate as $rate) {
                    $rates_array[] = $rate->rate;
                }
                // Количество обучающихся во всех курсах автора
                foreach ($course->course_members->where('paid_status', '!=', 0) as $member) {
                    $author_students[$member['student_id']][] = $member;
                }
                // Количество завершивших курсы
                foreach ($course->course_members->where('is_finished', '=', true) as $member) {
                    $author_students_finished[$member['student_id']][] = $member;
                }
                if ($course->courseWork()) {
                    $author_students_finished_courseWork[] = $course->courseWork()->finishedLesson();
                }
            }
            // Оценка автора исходя из всех оценок
            if (count($rates_array) == 0) {
                $i->average_rates = 0;
            } else {
                $i->average_rates = array_sum($rates_array) / count($rates_array);
            }
            // Количество уникальных обучающихся
            $i->members = $author_students;
            // Количество сертифицированных
            $i->certificate_members = $author_students_finished;
            // Количество подтвердивших квалификацию
            $author_students_finished_courseWork_count = 0;
            foreach ($author_students_finished_courseWork as $lesson) {
                $author_students_finished_courseWork_count += count($lesson);
            }
            $i->qualification_students = $author_students_finished_courseWork_count;

            // ФИО автора
            $name = $i->author_info->name . ' ' . $i->author_info->surname;
            // Специализация
            $specialization = implode(', ', json_decode($i->author_info->specialization) ?? []);
            // Рейтинг
            $rate = round($i->average_rates, 1);
            // Количество курсов
            $courses_count = $i->courses->count();
            // Количество платных курсов
            $paid_courses_count = $i->courses->where('is_paid', '=', true)->count();
            // Количество бесплатных курсов
            $free_courses_count = $i->courses->where('is_paid', '=', false)->count();
            // Количество доступных по квоте
            $quota_courses_count = $i->courses->where('quota_status', '=', 2)->count();
            // Количество обучающихся
            $students_count = count($i->members) ?? 0;
            // Количество сертифицированных обучающихся
            $students_certificate_count = count($i->certificate_members) ?? 0;
            // Количество подтвердивших квалификацию обучающихся
            $students_qualification_count = $i->qualification_students ?? 0;

            $newElement = ['name' => $name, 'specialization' => $specialization, 'rate' => $rate, 'courses_count' => $courses_count,
                'paid_courses_count' => $paid_courses_count, 'free_courses_count' => $free_courses_count, 'quota_courses_count' => $quota_courses_count,
                'students_count' => $students_count, 'students_certificate_count' => $students_certificate_count,
                'students_qualification_count' => $students_qualification_count];

            array_push($export, $newElement);
        }

//        asort($export);
        return Excel::download(new AuthorReportExport($export), '' . __('default.pages.courses.report_title') . '.xlsx');
    }

}
