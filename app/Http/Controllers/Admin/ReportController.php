<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AuthorReportExport;
use App\Exports\CourseReportExport;
use App\Exports\StudentReportExport;
use App\Exports\ConsolidatedReportExport;
use App\Models\Course;
use App\Models\StudentCertificate;
use App\Models\StudentCourse;
use App\Models\User;
use App\Models\RegionTree;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Services\AVR\AVRFilterService;
use Services\Contracts\ContractFilterService;
use ZipArchive;

class ReportController extends Controller
{
    /**
     * @var ContractFilterService
     */
    private $contractFilterService;

    /**
     * @var AVRFilterService
     */
    private $AVRFilterService;

    /**
     * ReportController constructor.
     *
     * @param ContractFilterService $contractFilterService
     * @param AVRFilterService $AVRFilterService
     */
    public function __construct(ContractFilterService $contractFilterService, AVRFilterService $AVRFilterService)
    {
        $this->contractFilterService = $contractFilterService;
        $this->AVRFilterService = $AVRFilterService;
    }

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
        Session::put('authors_report_export', $query->get());

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

        $inputs = $request->except('page');

        return view('admin.v2.pages.reports.authors_report', [
            'items' => $items,
            'request' => $request,
            'inputs' => $inputs,
        ]);
    }

    public function coursesReports(Request $request)
    {
        // Фильтрация
        $course_name = $request->course_name ? $request->course_name : '';
        $author_name = $request->author_name ? $request->author_name : '';
        $cost_from = $request->cost_from;
        $cost_to = $request->cost_to;
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
        $sortByCost = $request->sortByCost;
        $sortByCourseMembers = $request->sortByCourseMembers;
        $sortByCertificateCourseMembers = $request->sortByCertificateCourseMembers;
        $sortByRateCourse = $request->sortByRateCourse;
        $sortByQualificatedStudents = $request->sortByQualificatedStudents;

        $from = $request->date_from;
        $to = $request->date_to;

        $date_from = Carbon::parse($from ?? '01.01.2020')
            ->startOfDay()
            ->toDateTimeString();
        $date_to = Carbon::parse($to)
            ->endOfDay()
            ->toDateTimeString();

        $query = Course::orderBy('created_at', 'DESC')
            ->with(['rate' => function ($q) use ($date_from, $date_to) {
                $q->whereBetween('course_rate.created_at', [$date_from, $date_to]);
                // Записавшиеся
            }])->with(['course_members' => function ($q) use ($date_from, $date_to) {
                $q->whereBetween('student_course.created_at', [$date_from, $date_to]);
            }])->with(['quotaCost' => function ($q) use ($date_from, $date_to) {
                $q->whereBetween('course_quota_cost.created_at', [$date_from, $date_to]);
            }])->with(['course_members' => function ($q) use ($date_from, $date_to) {
                $q->whereBetween('student_course.created_at', [$date_from, $date_to]);
            }]);
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
        // Сортировка по стоимости курса
        if ($sortByCost) {
            $query->orderBy('cost', $sortByCost);
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
        // Поиск по стоимости
        if ($cost_from and empty($cost_to)) {
            $query->where('cost', '>=', $cost_from);
        } else if ($cost_to and empty($cost_from)) {
            $query->where('cost', '<=', $cost_to);
        } else if ($cost_to and $cost_from) {
            $query->where('cost', '>=', $cost_from);
            $query->where('cost', '<=', $cost_to);
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
                $q->whereIn('paid_status', [1, 2, 3]);
            }])->having('course_members_count', '>=', $course_members_count_from);
        } else if ($course_members_count_to and empty($course_members_count_from)) {
            $query->withCount(['course_members' => function ($q) {
                $q->whereIn('paid_status', [1, 2, 3]);
            }])->having('course_members_count', '<=', $course_members_count_to);
        } else if ($course_members_count_to and $course_members_count_from) {
            $query->withCount(['course_members' => function ($q) {
                $q->whereIn('paid_status', [1, 2, 3]);
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
                $q->where('is_qualificated', '=', true);
            }])->having('course_qualifications_members_count', '>=', $qualifications_count_from);
        } else if ($qualifications_count_to and empty($qualifications_count_from)) {
            $query->withCount(['course_members as course_qualifications_members_count' => function ($q) {
                $q->where('is_qualificated', '=', true);
            }])->having('course_qualifications_members_count', '<=', $qualifications_count_to);
        } else if ($qualifications_count_to and $qualifications_count_from) {
            $query->withCount(['course_members as course_qualifications_members_count' => function ($q) {
                $q->where('is_qualificated', '=', true);
            }])->having('course_qualifications_members_count', '>=', $qualifications_count_from)
                ->having('course_qualifications_members_count', '<=', $qualifications_count_to);
        }

        Session::put('courses_report_export', $query->get());

        $items = $query->paginate(10);

        $inputs = $request->except('page');

        return view('admin.v2.pages.reports.courses_report', [
            'items' => $items,
            'request' => $request,
            'inputs' => $inputs,
            'quota_status' => $quota_status,
            'paid_status' => $paid_status
        ]);
    }

    public function studentsReports($lang, Request $request)
    {
        // Фильтрация
        $student_name = $request->student_name ? $request->student_name : '';
        $student_iin =  $request->student_iin ? $request->student_iin : '';
        $unemployed_status = $request->unemployed_status ?? [];
        $is_finished = $request->is_finished ?? [];
        $is_paid = $request->is_paid ?? [];
        $quota_status = $request->quota_status ?? [];
        $area = $request->area_id ?? null;
        $coduoz_id = $request->coduoz_id ?? null;
        $region_id = $request->region_id ?? null;
        // Сортировка
        $sortByName = $request->sortByName;
        $sortByQuota = $request->sortByQuota;

        $course_from = $request->date_course_from;
        $course_to = $request->date_course_to;
        $date_course_from = Carbon::parse($course_from ?? '01.01.2020')
            ->startOfDay()
            ->toDateTimeString();
        $date_course_to = Carbon::parse($course_to)
            ->endOfDay()
            ->toDateTimeString();

        $query = StudentCourse::query()
            ->orderBy('id', 'DESC')
            ->whereBetween('created_at', [$date_course_from, $date_course_to])
            ->with([
                'student_info.cato',
                'student_info.clcz',
                'course'
            ]);

        $first_lesson_from = $request->first_lesson_from;
        $first_lesson_to = $request->first_lesson_to;
        if (!empty($first_lesson_from) || !empty($first_lesson_to))
        {
            $date_first_lesson_from = Carbon::parse($first_lesson_from ?? '01.01.2020')
                ->startOfDay()
                ->toDateTimeString();
            $date_first_lesson_to = Carbon::parse($first_lesson_to)
                ->endOfDay()
                ->toDateTimeString();
            $query->whereBetween('first_lesson_date', [$date_first_lesson_from, $date_first_lesson_to]);
        }
        // Сортировка
        // Сортировка по названию курса
//        if ($sortByName) {
//            $query->with('student_info')
//                ->join('student_information', 'users.id', '=', 'student_information.user_id')
//                ->orderBy('student_information.name', $sortByName);
//        }
//        // Фильтрация
        // Поиск по имени
        if ($student_name) {
            $query->whereHas('student_info', function ($q) use ($student_name) {
                $q->where('name', 'like', '%' . $student_name . '%');
            });
        }
        // Поиск по ИИН
        if ($student_iin) {
            $query->whereHas('student_info', function ($q) use ($student_iin) {
                $q->where('iin', 'like', $student_iin . '%');
            });
        }
        // Поиск по статусу
        if ($unemployed_status) {
            $query->whereIn('unemployed_status', $unemployed_status);
        }
        if ($is_finished) {
            $query->whereIn('is_finished', $is_finished);
        }
        if ($is_paid || $quota_status) {
            $query->whereHas('course', function ($q) use ($is_paid, $quota_status) {
                $q->whereIn('is_paid', $is_paid)
                ->orWhereIn('quota_status', $quota_status);
            });
        }
        // Поиск по адресу
        if ($coduoz_id) {
            $query->whereHas('student_info', function ($q) use ($coduoz_id) {
                $q->where('coduoz', '=', $coduoz_id);
            });
        } elseif ($area) {
            $query->whereHas('student_info', function ($q) use ($area) {
                $q->where('coduoz', 'LIKE', $area . '%');
            });
        }
        if ($region_id) {
            $query->whereHas('student_info', function ($q) use ($region_id) {
                $q->where('region_id', '=', $region_id);
            });
        }

        Session::put('students_report_export', $query->get());

        $items = $query->paginate(10);
//        dd($items);

        $inputs = $request->except('page');
        $areas = RegionTree::getSprUoz($lang);
        return view('admin.v2.pages.reports.students_report', [
            'items' => $items,
            'request' => $request,
            'inputs' => $inputs,
            'unemployed_status' => $unemployed_status,
            'is_finished' => $is_finished,
            'is_paid' => $is_paid,
            'quota_status' => $quota_status,
            'areas' => $areas
        ]);
    }

    public function certificatesReports(Request $request)
    {
        $student_name = $request->student_name ? $request->student_name : '';
        $student_iin = $request->iin;
        $payment_type = $request->payment_type;
        $date_from = $request->get("date_from", Carbon::now()->subDays(90)->format('Y-m-d'));
        $date_to = $request->get("date_to", Carbon::now()->format('Y-m-d'));

        if ($date_from === null && $date_to === null) {
            $date_from = Carbon::now()->subDays(90);
            $date_to = Carbon::now();
        } elseif ($date_from === null) {
            $time = strtotime($date_to);
            $date_from = Carbon::createFromTimestamp($time)->subDays(90);
            $date_to = Carbon::createFromTimestamp($time);
        } elseif ($date_to === null) {
            $date_from = strtotime($date_from);
            $date_from = Carbon::createFromTimestamp($date_from);
            $date_to = Carbon::now();
        } else {
            $date_from = strtotime($date_from);
            $date_from = Carbon::createFromTimestamp($date_from);
            $date_to = strtotime($date_to);
            $date_to = Carbon::createFromTimestamp($date_to);
        }

        $query = StudentCertificate::orderBy('id', 'DESC')
            ->where('user_id', '!=', 90)
            ->whereBetween('created_at', [$date_from, $date_to->endOfDay()]);

        // Поиск по ФИО обучающегося
        if ($student_name) {
            $query->whereHas('students.student_info', function ($q) use ($student_name) {
                $q->where('name', 'like', '%' . $student_name . '%');
            });
        }
        // Поиск по ИИН обучающегося
        if ($student_iin) {
            $query->whereHas('students.student_info', function ($q) use ($student_iin) {
                $q->where('iin', 'like', '%' . $student_iin . '%');
            });
        }
        // Поиск по типу оплаты за курс
        if ($payment_type) {
            $query->whereHas('students.student_course', function ($q) use ($payment_type) {
                $q->where('paid_status', '=', $payment_type);
            })->whereHas('courses.course_members', function ($q) use ($payment_type) {
                $q->where('paid_status', '=', $payment_type);
            });;
        }

        Session::put('certificates_export', $query->get());

        $items = $query->paginate(10);

        foreach ($items as $certificate) {
            $pay_type = StudentCourse::whereCourseId($certificate->course_id)
                ->whereStudentId($certificate->user_id)->latest()->first();
            // Тип оплаты за курс
            $certificate->payment_type = $pay_type->paid_status;
        }

        $inputs = $request->except('page');

        return view('admin.v2.pages.reports.certificates_report', [
            'items' => $items,
            'request' => $request,
            'inputs' => $inputs,
        ]);
    }


    public function exportStudentsReport(Request $request)
    {
        App::setLocale('ru');

        $query = Session::get('students_report_export');
        $export = [[]];
        $lang = app()->getLocale();

        /** @var User $i */
        foreach ($query as $i) {
            // ИИН обучающегося
            $iin = $i->student_info->iin;
            // ФИО обучающегося
            $name = $i->student_info->name;
            // ОБласть
            $area = $i->student_info->area()->NAME_KR_R ?? '';
            // Регион/Район
            $coduoz = $i->student_info->clcz->NAME_KR_R ?? '';
            // Населенный пункт
            $region = $i->student_info->cato->rus_name ?? '' ;
            // Статус безработного
//            if(isset($i->unemployed_status)) {
                $unemployed_status = $i->unemployed_status == '00000$192' ? __('default.yes_title') : __('default.no_title');
//            } else {
//                $unemployed_status = '';
//            }
            // Количество оставшихся доступов по гос.поддержке
            $quota_count = $i->quota_count;
            // Наименование курса
            $course_name = $i->course->name;
            // Тип курса
            if($i->course->quota_status == 2) {
                $course_type = __('admin.pages.reports.quota_course');
            } else {
                $course_type = $i->course->is_paid == true ? __('default.pages.reporting.paid_course') : __('default.pages.reporting.free_course');
            }
            // Дата записи на курс
            $course_date = date('d.m.Y', strtotime($i->created_at));
            // Дата начала обучения
            $first_lesson_date = isset($i->first_lesson_date) ?  date('d.m.Y', strtotime($i->first_lesson_date)) : '';
            // Первые 3 неудачные попытки итогового тестирования
            $attempts = $i->attempts();
            $first_failed_test_date = isset($attempts[0]->created_at) ? date('d.m.Y H:i', strtotime($attempts[0]->created_at)) : '';
            $second_failed_test_date = isset($attempts[1]->created_at) ? date('d.m.Y H:i', strtotime($attempts[1]->created_at)) : '';
            $third_failed_test_date = isset($attempts[2]->created_at) ? date('d.m.Y H:i', strtotime($attempts[2]->created_at)) : '';

            // Дата получения сертификата
            $certificate_date = $i->is_finished == 1 && isset($i->certificate()->created_at) ? date('d.m.Y', strtotime($i->certificate()->created_at)) : '';

            $newElement = [
                'iin' => $iin,
                'name' => $name,
                'area' => $area,
                'coduoz' => $coduoz,
                'region' => $region,
                'unemployed_status' => $unemployed_status,
                'course_name' => $course_name,
                'course_type' => $course_type,
                'course_date' => $course_date,
                'first_lesson_date' => $first_lesson_date,
                'first_failed_test_date' => $first_failed_test_date,
                'second_failed_test_date' => $second_failed_test_date,
                'third_failed_test_date' => $third_failed_test_date,
                'certificate_date' => $certificate_date,
                'quota_count' => $quota_count,
            ];
            array_push($export, $newElement);
        }

        return Excel::download(new StudentReportExport($export), '' . __('default.pages.courses.report_title') . '.xlsx');
    }

    public function exportCoursesReport(Request $request)
    {
        App::setLocale('ru');

        $query = Session::get('courses_report_export');
        $export = [[]];
        $lang = app()->getLocale();
        foreach ($query as $i) {
            // Наименование курса
            $name = $i->name;
            // Наименование автора
            $author_name = $i->user->company_name;
            // Квота
            if ($i->quota_status == 2) {
                $is_quota = __('default.yes_title');
            } else {
                $is_quota = __('default.no_title');
            }
            // Навыки
            $skills = implode(', ', array_filter($i->skills->pluck('name_' . $lang)->toArray())) ?: implode(', ', $i->skills->pluck('name_ru')->toArray());
            // Профессия
            if (count($i->professions()->pluck('name_ru')->toArray()) <= 0) {
                $professions = '-';
            } else {
                $professions = implode(', ', array_filter($i->professions()->pluck('name_' . $lang)->toArray())) ?: implode(', ', array_filter($i->professions()->pluck('name_ru')->toArray()));
            }
            // Проф.область
            if (count($i->professional_areas()->pluck('name_ru')->toArray()) <= 0) {
                $professional_areas = '-';
            } else {
                $professional_areas = implode(', ', array_filter($i->professional_areas()->pluck('name_' . $lang)->toArray())) ?: implode(', ', array_filter($i->professional_areas()->pluck('name_ru')->toArray()));
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
            if ($i->quota_status == 2 and $i->is_paid == true) {
                $students_count = count($i->course_members->where('paid_status', '=', 1)) . "/" . count($i->course_members->where('paid_status', '=', 2));
            } else {
                $students_count = count($i->course_members->whereIn('paid_status', [1, 2]));
            }
            // Получили сертификат
            $students_certificate_count = count($i->course_members->where('is_finished', '=', true));
            // Подтвердили квалификацию
            if ($i->courseWork()) {
                $students_qualification_count = $i->courseWork()->finishedLesson()->count();
            } else {
                $students_qualification_count = '-';
            }
            // Тип курса
            $course_type = $i->is_paid == true ? __('default.pages.reporting.paid_course') : __('default.pages.reporting.free_course');
            // Стоимость по квоте
            $quota_cost = $i->quotaCost->last()->cost ?? '-';
            // Записано обучающихся
            $members_free = $i->course_members->where('paid_status', '=', 3)->count();
            $members_paid = $i->course_members->where('paid_status', '=', 1)->count();
            $members_quota = $i->course_members->where('paid_status', '=', 2)->count();
            // Подтвердили квалификацию
            $qualificated_free = $i->course_members->where('paid_status', '=', 3)->where('is_qualificated', '=', true)->count();
            $qualificated_paid = $i->course_members->where('paid_status', '=', 1)->where('is_qualificated', '=', true)->count();
            $qualificated_quota = $i->course_members->where('paid_status', '=', 2)->where('is_qualificated', '=', true)->count();
            // Получили сертификат
            $certificate_free = $i->course_members->where('paid_status', '=', 3)->where('is_finished', '=', true)->count();
            $certificate_paid = $i->course_members->where('paid_status', '=', 1)->where('is_finished', '=', true)->count();
            $certificate_quota = $i->course_members->where('paid_status', '=', 2)->where('is_finished', '=', true)->count();
            // Итого получено за курсы
            $total_get_paid = $i->course_members->where('paid_status', '=', 1)->sum('payment.amount');
            $total_get_quota = $i->course_members->where('paid_status', '=', 2)->sum('payment.amount');
            // Стоимость курса
            $course_cost = $i->cost ?? '-';

            $newElement = ['name' => $name, 'author_name' => $author_name, 'professional_areas' => $professional_areas,
                'professions' => $professions, 'skills' => $skills, 'course_rate' => $rate, 'course_status' => $status,
                'course_type' => $course_type, 'course_cost' => $course_cost, 'is_quota' => $is_quota, 'quota_cost' => $quota_cost,
                'members_free' => $members_free, 'certificate_free' => $certificate_free, 'members_paid' => $members_paid,
                'certificate_paid' => $certificate_paid, 'total_get_paid' => $total_get_paid, 'members_quota' => $members_quota,
                'certificate_quota' => $certificate_quota, 'total_get_quota' => $total_get_quota];

            array_push($export, $newElement);
        }

        return Excel::download(new CourseReportExport($export), '' . __('default.pages.courses.report_title') . '.xlsx');
    }

    public function exportAuthorsReport(Request $request)
    {
        App::setLocale('ru');

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
                'students_count' => $students_count, 'students_certificate_count' => $students_certificate_count];

            array_push($export, $newElement);
        }

        return Excel::download(new AuthorReportExport($export), '' . __('default.pages.courses.report_title') . '.xlsx');
    }

    public function exportCertificates(Request $request)
    {
        App::setLocale('ru');
        $user = Auth::user();

        $zip = new ZipArchive;

        $fileName = 'Отчет по сертификатам.zip';

        File::ensureDirectoryExists(public_path('/users/user_' . $user->id));
        File::delete(public_path('/users/user_' . $user->id . '/' . $fileName));

        if ($zip->open(public_path('/users/user_' . $user->id . '/' . $fileName), ZipArchive::CREATE) === TRUE) {
            $certificates = Session::get('certificates_export');

            foreach ($certificates as $certificate) {
                $file_ru = public_path($certificate->pdf_ru);
                $file_kk = public_path($certificate->pdf_kk);
                if ($certificate->pdf_kk == null) {
                    if (file_exists($file_ru) && is_file($file_ru)) {
                        $zip->addFile($file_ru, str_replace(' ', '_', $certificate->students->student_info->name) . '-' . date('Y_m_d', strtotime($certificate->created_at)) . '-' . $certificate->course_id . '.pdf');
                    }
                } else {
                    if (file_exists($file_ru) && is_file($file_ru)) {
                        $zip->addFile($file_ru, str_replace(' ', '_', $certificate->students->student_info->name) . '-' . date('Y_m_d', strtotime($certificate->created_at)) . '-' . $certificate->course_id . '-ru.pdf');
                    }
                    if (file_exists($file_kk) && is_file($file_kk)) {
                        $zip->addFile($file_kk, str_replace(' ', '_', $certificate->students->student_info->name) . '-' . date('Y_m_d', strtotime($certificate->created_at)) . '-' . $certificate->course_id . '-kk.pdf');
                    }
                }
            }

            $zip->close();
        }

        try {
            return response()->download(public_path('/users/user_' . $user->id . '/' . $fileName))->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->with('status', 'При экспорте произошла ошибка. Попробуйте позже');
        }
    }

    /**
     * Отчеты по договорам
     *
     * @param Request $request
     * @return View
     */
    public function contracts(Request $request): View
    {
        return view('admin.v2.pages.reports.contracts', [
            'contracts' => $this->contractFilterService->getOrSearch($request->all(), 'signed'),
            'request' => $request->all(),
            'title' => 'Отчет по договорам'
        ]);
    }

    /**
     * Отчеты по АВР
     *
     * @param Request $request
     * @return View
     */
    public function avr(Request $request): View
    {
        return view('admin.v2.pages.reports.avr', [
            'avr' => $this->AVRFilterService->getOrSearch($request->all(), 'signed'),
            'request' => $request->all(),
            'title' => 'Отчет по АВР'
        ]);
    }

    /**
     * Сводный отчет
     *
     * @param Request $request
     * @return View
     */
    public function consolidated(Request $request)
    {
        $course_from = $request->date_course_from;
        $course_to = $request->date_course_to;
        $date_course_from = Carbon::parse($course_from ?? '01.01.2020')
            ->startOfDay()
            ->toDateTimeString();
        $date_course_to = Carbon::parse($course_to)
            ->endOfDay()
            ->toDateTimeString();

        $all = StudentCourse::whereBetween('created_at', [$date_course_from, $date_course_to])->count();
        $allWithCert = StudentCourse::where('is_finished', '=', 1)
            ->whereBetween('created_at', [$date_course_from, $date_course_to])
            ->count();
        $firstLesson = StudentCourse::whereNotNull('first_lesson_date')
            ->whereBetween('created_at', [$date_course_from, $date_course_to])
            ->count();
        $didNotPass = StudentCourse::whereNotNull('last_attempts_date')
            ->whereBetween('created_at', [$date_course_from, $date_course_to])
            ->where('is_finished', '=', 0)
            ->count();


        $unemployed = StudentCourse::whereBetween('created_at', [$date_course_from, $date_course_to])
            ->where('unemployed_status', '=', '00000$192')
            ->count();
        $unemployedWithCert = StudentCourse::whereBetween('created_at', [$date_course_from, $date_course_to])
            ->where('is_finished', '=', 1)
            ->where('unemployed_status', '=', '00000$192')
            ->count();
        $unemployedFirstLesson = StudentCourse::where('unemployed_status', '=', '00000$192')
            ->whereNotNull('first_lesson_date')
            ->whereBetween('created_at', [$date_course_from, $date_course_to])
            ->count();
        $unemployedDidNotPass = StudentCourse::whereNotNull('last_attempts_date')
            ->where('unemployed_status', '=', '00000$192')
            ->whereBetween('created_at', [$date_course_from, $date_course_to])
            ->where('is_finished', '=', 0)
            ->count();

        $employed = StudentCourse::whereBetween('created_at', [$date_course_from, $date_course_to])
            ->where('unemployed_status', '=', null)
            ->count();
        $employedWithCert = StudentCourse::whereBetween('created_at', [$date_course_from, $date_course_to])
            ->where('is_finished', '=', 1)
            ->where('unemployed_status', '=', null)
            ->count();
        $employedFirstLesson = StudentCourse::where('unemployed_status', '=', null)
            ->whereNotNull('first_lesson_date')
            ->whereBetween('created_at', [$date_course_from, $date_course_to])
            ->count();
        $employedDidNotPass = StudentCourse::whereNotNull('last_attempts_date')
            ->where('unemployed_status', '=', null)
            ->whereBetween('created_at', [$date_course_from, $date_course_to])
            ->where('is_finished', '=', 0)
            ->count();
        $data = [
            'all' => [
                'num' => 1,
                'title' => 'Всего записано на курс',
                'count' => $all
            ],
            'firstLesson' => [
                'num' => 2,
                'title' => 'Количество лиц, начавших обучение',
                'count' => $firstLesson
            ],
            'didNotPass' => [
                'num' => 3,
                'title' => 'Количесство лиц, прослушавших курс и не прошедших пороговый уровень итогового тестирования',
                'count' => $didNotPass
            ],
            'allWithCert' => [
                'num' => 4,
                'title' => 'Количество лиц, получивших сертификат',
                'count' => $allWithCert
            ],

            'unemployed' => [
                'num' => 5,
                'title' => 'Всего, имеющих статус безработного',
                'count' => $unemployed
            ],
            'unemployedFirstLesson' => [
                'num' => 6,
                'title' => 'Количество лиц, начавших обучение',
                'count' => $unemployedFirstLesson
            ],
            'unemployedDidNotPass' => [
                'num' => 7,
                'title' => 'Количество лиц, прослушавших курс и не прошедших пороговый уровень итогового тестирования',
                'count' => $unemployedDidNotPass
            ],
            'unemployedWithCert' => [
                'num' => 8,
                'title' => 'Количество лиц, получивших сертификаты',
                'count' => $unemployedWithCert
            ],

            'employed' => [
                'num' => 9,
                'title' => 'Всего, не имеющих статус безработного',
                'count' => $employed
            ],
            'employedFirstLesson' => [
                'num' => 10,
                'title' => 'Количество лиц, начавших обучение',
                'count' => $employedFirstLesson
            ],
            'employedDidNotPass' => [
                'num' => 11,
                'title' => 'Количество лиц, прослушавших курс и не прошедших пороговый уровень итогового тестирования',
                'count' => $employedDidNotPass
            ],
            'employedWithCert' => [
                'num' => 12,
                'title' => 'Количество лиц, получивших сертификаты',
                'count' => $employedWithCert
            ],
        ];
        Session::put('consolidated_report_export', $data);

        return view('admin.v2.pages.reports.consolidated', [
            'data' => $data,
            'request' => $request,
            'title' => 'Сводный отчет'
        ]);
    }

    public function exportConsolidatedReport(Request $request)
    {
        App::setLocale('ru');

        $data = Session::get('consolidated_report_export');

        return Excel::download(new ConsolidatedReportExport($data), '' . __('default.pages.courses.consolidated_report_title') . '.xlsx');
    }

}
