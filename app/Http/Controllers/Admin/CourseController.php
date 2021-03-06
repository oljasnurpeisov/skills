<?php

namespace App\Http\Controllers\Admin;

use App\Extensions\CalculateQuotaCost;
use App\Extensions\NotificationsHelper;
use App\Models\Course;
use App\Models\CourseQuotaCost;
use App\Models\Notification;
use App\Models\Log;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Libraries\Word\Agreement;
use Services\Course\CourseService;
use Services\Course\CourseStatusService;

/**
 * --------------------------------------------------------------------------
 *  CourseController
 * --------------------------------------------------------------------------
 *
 *  Этот контроллер отвечает за управление курсами
 *
 */
class CourseController extends Controller
{
    /**
     * @var CourseService
     */
    private $courseService;

    /**
     * @var CourseStatusService
     */
    private $courseStatusService;

    /**
     * CourseController constructor.
     *
     * @param CourseService $courseService
     * @param CourseStatusService $courseStatusService
     */
    public function __construct(CourseService $courseService, CourseStatusService $courseStatusService)
    {
        $this->courseService        = $courseService;
        $this->courseStatusService  = $courseStatusService;
    }

    public function index(Request $request)
    {
        $term = $request->term ? $request->term : '';

        $query = Course::orderBy('id', 'desc');
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }

        $query = $query->where('name', 'like', '%' . $term . '%');
        $count = $query->count();
        $items = $query->paginate();

        return view('admin.v2.pages.courses.index', [
            'items' => $items,
            'term' => $term,
            'count' => $count,
            'title' => 'Все курсы'
        ]);
    }

    public function wait_verification(Request $request)
    {
        $term = $request->term ? $request->term : '';

        $query = Course::orderBy('id', 'desc')->where('status', '=', Course::onCheck);
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }

        $query = $query->where('name', 'like', '%' . $term . '%');
        $count = $query->count();
        $items = $query->paginate();

        return view('admin.v2.pages.courses.index', [
            'items' => $items,
            'term' => $term,
            'count' => $count,
            'title' => "Ожидающие проверки курсов"
        ]);
    }

    public function unpublished_index(Request $request)
    {
        $term = $request->term ? $request->term : '';

        $query = Course::orderBy('id', 'desc')->where('status', '=', Course::unpublished);
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }

        $query = $query->where('name', 'like', '%' . $term . '%');
        $count = $query->count();
        $items = $query->paginate();

        return view('admin.v2.pages.courses.index', [
            'items' => $items,
            'count' => $count,
            'term' => $term,
            'title' => 'Отклоненные курсы'
        ]);
    }

    public function published_index(Request $request)
    {
        $term = $request->term ? $request->term : '';

        $query = Course::orderBy('id', 'desc')->where('status', '=', Course::published);
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }

        $query = $query->where('name', 'like', '%' . $term . '%');
        $count = $query->count();
        $items = $query->paginate();

        return view('admin.v2.pages.courses.index', [
            'items' => $items,
            'term' => $term,
            'count' => $count,
            'title' => 'Опубликованные курсы'
        ]);
    }

    public function drafts_index(Request $request)
    {
        $term = $request->term ? $request->term : '';

        $query = Course::orderBy('id', 'desc')->where('status', '=', Course::draft);
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }

        $query = $query->where('name', 'like', '%' . $term . '%');
        $count = $query->count();
        $items = $query->paginate();

        return view('admin.v2.pages.courses.index', [
            'items' => $items,
            'count' => $count,
            'term' => $term,
            'title' => 'Черновики'
        ]);
    }

    public function deleted_index(Request $request)
    {
        $term = $request->term ? $request->term : '';

        $query = Course::orderBy('id', 'desc')->where('status', '=', Course::deleted);
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }

        $query = $query->where('name', 'like', '%' . $term . '%');
        $count = $query->count();
        $items = $query->paginate();

        return view('admin.v2.pages.courses.index', [
            'items' => $items,
            'count' => $count,
            'term' => $term,
            'title' => 'Удаленные курсы'
        ]);
    }

    public function view($lang, Course $item)
    {
        return view('admin.v2.pages.courses.view', [
            'item' => $item
        ]);
    }

    /**
     * Одобрение курса
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function accept(Request $request): RedirectResponse
    {
        $this->courseStatusService->acceptCourse($request->item);

        return redirect()->back();
    }

    /**
     * Отклонение курса (перенесено из $this->publish)
     *
     * @param $lang
     * @param Course $item
     * @param Request $request
     * @return RedirectResponse
     */
    public function reject($lang, Course $item, Request $request)
    {
        $user = $item->user()->first();

        $item->status = 2;
        $item->save();

        $notification_data = [
            'course_reject_message' => $request->rejectMessage
        ];
        $notification_name = "notifications.course_reject";
        NotificationsHelper::createNotification($notification_name, $item->id, $user->id, 0, $notification_data);

        $data = [
            'item' => $item,
            'lang' => $lang,
            'message_text' => $request->rejectMessage,
        ];

        try {

            Mail::send('app.pages.page.emails.course_reject', ['data' => $data], function ($message) use ($request, $item, $user) {
                $message->from(env("MAIL_USERNAME"), env('APP_NAME'));
                $message->to($user->email, 'Receiver')->subject(__('notifications.publish_course_title'));
            });

        } catch (\Exception $e) {

        }

        return redirect()->back()->with('status', trans('admin.pages.courses.course_reject', ['course_name' => $item->name, 'rejectMessage' => $request->rejectMessage]));
    }


    public function publish($lang, Course $item, Request $request)
    {
        if ($item->status == 1 or $item->status == 2 or $item->status == 3) {
            $user = $item->user()->first();
            switch ($request->input('action')) {
                case 'reject':
                    $item->status = 2;
                    $item->save();

                    $notification_data = [
                        'course_reject_message' => $request->rejectMessage
                    ];
                    $notification_name = "notifications.course_reject";
                    NotificationsHelper::createNotification($notification_name, $item->id, $user->id, 0, $notification_data);

                    $data = [
                        'item' => $item,
                        'lang' => $lang,
                        'message_text' => $request->rejectMessage,
                    ];

                    try {

                        Mail::send('app.pages.page.emails.course_reject', ['data' => $data], function ($message) use ($request, $item, $user) {
                            $message->from(env("MAIL_USERNAME"), env('APP_NAME'));
                            $message->to($user->email, 'Receiver')->subject(__('notifications.publish_course_title'));
                        });

                    } catch (\Exception $e) {

                    }

                    return redirect()->back()->with('status', trans('admin.pages.courses.course_reject', ['course_name' => $item->name, 'rejectMessage' => $request->rejectMessage]));

            }

            $item->status = 3;
            $item->save();

            $qouta_cost = $item->quotaCost()->exists();
            if ($qouta_cost == false) {
                $calculate_quota_cost = CalculateQuotaCost::calculate_quota_cost($item, false);
                $qouta_cost_item = new CourseQuotaCost;
                $qouta_cost_item->course_id = $item->id;
                $qouta_cost_item->cost = $calculate_quota_cost;
                $qouta_cost_item->save();
            }

            $notification_name = "notifications.course_publish";
            NotificationsHelper::createNotification($notification_name, $item->id, $user->id);

            $data = [
                'item' => $item,
                'lang' => $lang,
            ];

            try {

                Mail::send('app.pages.page.emails.course_confirm', ['data' => $data], function ($message) use ($request, $item, $user) {
                    $message->from(env("MAIL_USERNAME"), env('APP_NAME'));
                    $message->to($user->email, 'Receiver')->subject(__('notifications.publish_course_title'));
                });

            } catch (\Exception $e) {

            }

            return redirect()->back()->with('status', trans('admin.pages.courses.course_published', ['course_name' => $item->name]));
        }
        return redirect('/' . $lang . '/admin/courses/index');
    }

    public function unpublish($lang, Course $item)
    {
        if ($item->status == 1 or $item->status == 2 or $item->status == 3) {
            $item->status = 4;
            $item->save();

            return redirect()->back()->with('status', trans('admin.pages.courses.course_unpublished', ['course_name' => $item->name]));
        }
        return redirect('/' . $lang . '/admin/courses/index');
    }

    public function quota_request($lang, Course $item)
    {
        if ($item->status == 1 or $item->status == 2 or $item->status == 3) {
            $author_course = $item->user()->first()->id;

            if ($item->quota_status != 1 and $item->cost > 0) {
                $notification_data = [
                    'course_quota_cost' => $item->quotaCost->last()->cost ?? 0
                ];

                $notification = new Notification;
                $notification->data = json_encode([$notification_data]);
                $notification->name = "notifications.quota_request_description";
                $notification->course_id = $item->id;
                $notification->type = 1;
                $notification->save();

                $item->quota_status = 1;
                $item->save();

                $notification->users()->sync([$author_course]);

                $data = [
                    'email' => $item->user()->first()->email,
                    'description' => 'notifications.quota_request_description_mail',
                    'course_id' => $item->id,
                    'course_name' => $item->name,
                    'user_id' => $item->user()->first()->id,
                    'lang' => $lang
                ];

                try {

                    Mail::send('app.pages.page.emails.quota_confirm', ['data' => $data], function ($message) use ($item) {
                        $message->from(env("MAIL_USERNAME"), env('APP_NAME'));
                        $message->to($item->user()->first()->email, 'Receiver')->subject(__('notifications.publish_course_title'));
                    });

                } catch (\Exception $e) {

                }

            }

            $logger = new Log;
            $logger->log("quota_change", "courses", $item->id, 'Уведомление о публикации курса по квоте было отправлено автору курса');

            return redirect()->back()->with('status', __('admin.pages.courses.course_quote_request'));
        }
        return redirect('/' . $lang . '/admin/courses/index');
    }

    public function quota_contract($lang, Course $item, Request $request)
    {
        $item->quota_status = 2;
        $item->quota_contract_number = $request->quota_contract_number;
        $item->save();

        $notification = new Notification;
        $notification->name = 'notifications.course_quota_access';
        $notification->course_id = $item->id;
        $notification->type = 0;
        $notification->save();

        $notification->users()->sync([$item->author_id]);

        return redirect()->back()->with('status', __('admin.pages.courses.quote_contract_saved'));
    }

    /**
     * Ожидающие проверки договора
     *
     * @param Request $request
     * @return View
     */
    public function waitCheckContracts(Request $request): View
    {
        return view('admin.v2.pages.courses.index', [
            'items' => $this->courseService->waitCheckContracts(),
            'count' => $this->courseService->waitCheckContractsCount(),
            'term'  => $request->term,
            'title' => "Ожидающие проверки договора"
        ]);
    }

    /**
     * Ожидающие подписания договора со стороны Автора
     *
     * @param Request $request
     * @return View
     */
    public function waitSigningAuthor(Request $request): View
    {
        return view('admin.v2.pages.courses.index', [
            'items' => $this->courseService->waitSigningAuthor(),
            'count' => $this->courseService->waitSigningAuthorCount(),
            'term'  => $request->term,
            'title' => " Ожидающие подписания договора со стороны Автора"
        ]);
    }

    /**
     * Ожидающие подписания договора со стороны Администрации
     *
     * @param Request $request
     * @return View
     */
    public function waitSigningAdmin(Request $request): View
    {
        return view('admin.v2.pages.courses.index', [
            'items' => $this->courseService->waitSigningAdmin(),
            'count' => $this->courseService->waitSigningAdminCount(),
            'term'  => $request->term,
            'title' => "Ожидающие подписания договора со стороны Администрации"
        ]);
    }

}
