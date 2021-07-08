<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\Kalkan\Certificate;
use App\Models\Contract;
use App\Models\Course;
use App\Services\Signing\ValidationService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpWord\Exception\Exception;
use Services\Contracts\ContractFilterService;
use Services\Contracts\ContractService;
use Services\Course\AdminCourseService;
use Services\Notifications\NotificationService;

/**
 * Class ContractsController
 *
 * @author kgurovoy@gmail.com
 * @package App\Http\Controllers\Admin
 */
class ContractsController extends Controller
{
    /**
     * @var ContractFilterService
     */
    private $contractFilterService;

    /**
     * @var ContractService
     */
    private $contractService;

    /**
     * @var AdminCourseService
     */
    private $adminCourseService;

    /**
     * @var ValidationService
     */
    private $validationService;

    /**
     * ContractsController constructor.
     *
     * @param ContractFilterService $contractFilterService
     * @param ContractService $contractService
     * @param AdminCourseService $adminCourseService
     * @param ValidationService $validationService
     */
    public function __construct(
        ContractFilterService $contractFilterService,
        ContractService $contractService,
        AdminCourseService $adminCourseService,
        ValidationService  $validationService
    )
    {
        $this->contractFilterService    = $contractFilterService;
        $this->contractService          = $contractService;
        $this->adminCourseService       = $adminCourseService;
        $this->validationService        = $validationService;
    }

    /**
     * Просмотр всех договоров
     *
     * @param Request $request
     * @return View
     */
    public function all(Request $request): View
    {
        return view('admin.v2.pages.contracts.index', [
            'contracts' => $this->contractFilterService->getOrSearch($request->all()),
            'request'   => $request->all(),
            'title'     => 'Договоры'
        ]);
    }

    /**
     * Подписанные договора
     *
     * @param Request $request
     * @return View
     */
    public function signed(Request $request): View
    {
        return view('admin.v2.pages.contracts.index', [
            'contracts' => $this->contractFilterService->getOrSearch($request->all(), 'signed'),
            'request'   => $request->all(),
            'title'     => 'Подписанные договора'
        ]);
    }

    /**
     * Расторгнутые договора
     *
     * @param Request $request
     * @return View
     */
    public function distributed(Request $request): View
    {
        return view('admin.v2.pages.contracts.index', [
            'contracts' => $this->contractFilterService->getOrSearch($request->all(), 'distributed'),
            'request'   => $request->all(),
            'title'     => 'Расторгнутые договора'
        ]);
    }

    /**
     * Отклонены автором
     *
     * @param Request $request
     * @return View
     */
    public function rejectedByAuthor(Request $request): View
    {
        return view('admin.v2.pages.contracts.index', [
            'contracts' => $this->contractFilterService->getOrSearch($request->all(), 'rejectedByAuthor'),
            'request'   => $request->all(),
            'title'     => 'Отклонены автором'
        ]);
    }

    /**
     * Отклонены администрацией
     *
     * @param Request $request
     * @return View
     */
    public function rejectedByAdmin(Request $request): View
    {
//        $this->authorize('moderatorOnly', [Contract::class]);

        return view('admin.v2.pages.contracts.index', [
            'contracts' => $this->contractFilterService->getOrSearch($request->all(), 'rejectedByAdminOrModerator'),
            'request'   => $request->all(),
            'title'     => 'Отклонены администрацией'
        ]);
    }

    /**
     * Ожидающие подписания
     *
     * @param Request $request
     * @return View
     */
    public function pending(Request $request): View
    {
        return view('admin.v2.pages.contracts.index', [
            'contracts' => $this->contractFilterService->getOrSearch($request->all(), 'pending'),
            'request'   => $request->all(),
            'title'     => 'Ожидающие подписания'
        ]);
    }

    /**
     * Предпросмотр без сохранения
     *
     * @param Request $request
     * @return View
     */
    public function previewContract(Request $request): View
    {
        return view('admin.v2.pages.contracts.view', [
            'course'    => Course::find($request->course_id),
            'type'      => $request->type,
            'title'     => 'Просмотр договора'
        ]);
    }

    /**
     * Просмотр договора
     *
     * @param Request $request
     * @return View
     */
    public function view(Request $request): View
    {
        return view('admin.v2.pages.contracts.view', [
            'contract'  => Contract::findOrFail($request->contract_id),
            'title'     => 'Просмотр договора'
        ]);
    }

    /**
     * Предпросмотр договора
     *
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public function getContractHtml(Request $request): string
    {
        return view('app.pages.author.courses.contractDoc', [
            'contract' => $this->contractService->contractToHtml($request->contract_id)
        ]);
    }

    /**
     * Предпросмотр договора без сохранения
     *
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public function getContractHtmlPreview(Request $request): string
    {
        return view('app.pages.author.courses.contractDoc', [
            'contract' => $this->contractService->createPreviewContract(Course::find($request->course_id), $request->type)
        ]);
    }

    /**
     * Check and send
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     * @throws GuzzleException
     */
    public function next(Request $request)
    {
        if (!empty($_POST)) {

            $xml = $request->post('xml');

            $success = false;
            $certificate = null;

            if($this->validationService->verifyXml($xml)) {

                $success = true;
                $x509 = Certificate::getCertificate($xml, true);
                $message = 'Договор успешно подписан';

                if ($x509) {
                    $certificate = $x509;
                }

                $this->adminCourseService->acceptContract($request->contract_id, $xml, $this->validationService->getResponse());

            } else {
                $message = $this->validationService->getError();
            }

            return response()->json([
                'success' => $success,
                'message' => $message,
                'certificate' => $certificate,
                'redirect' => route('admin.contracts.pending', ['lang' => $request->lang]),
                'response' => $this->validationService->getResponse()
            ], $success ? 200 : 500);
        } else {
            $this->adminCourseService->acceptContract($request->contract_id);
            return redirect()->route('admin.contracts.pending', ['lang' => $request->lang]);
        }
    }

    /**
     * Расторжение договора директором
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function rejectContract(Request $request): RedirectResponse
    {
        $contract = Contract::findOrFail($request->contract_id);

        $this->authorize('rejectContract', [Contract::class, $contract]);

        // Расторжение договора
        $this->contractService->rejectContract($contract->id, $request->message);

        // Снимаем с публикации
        if (!$contract->isQuota()) {
            $this->adminCourseService->rejectCourse($contract->course->id);
        }

        // Отменяем доступ по квоте
        $this->adminCourseService->rejectQuota($contract->course->id);

        (new NotificationService('Договор расторгнут', 'notifications.contract_rejected', $contract->course->id, $contract->course->user->id, 'ru', 3, $request->message))->notify();

        return redirect()->back();
    }

    /**
     * Отклонение договора администрацией
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function rejectContractByAdmin(Request $request): RedirectResponse
    {
        $contract = $this->contractService->getContractIfMyCurrentRoute($request->contract_id);

        $this->contractService->rejectContractByAdmin($contract->id, $request->message);

        return redirect()->back();
    }

    /**
     * Отмена отклонения договора администрацией,
     * возращаем обратно на подписание
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function rejectContractByAdminCancel(Request $request): RedirectResponse
    {
        $contract = Contract::findOrFail($request->contract_id);

        $this->authorize('rejectContractByAdminCancel', [Contract::class, $contract]);

        $this->contractService->rejectContractByAdminCancel($contract->id);

        return redirect()->back();
    }

    /**
     * Отклонение договора модератором
     * с отправкой курса в отклоненные
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function rejectContractByModerator(Request $request): RedirectResponse
    {
        $contract = Contract::findOrFail($request->contract_id);

        $this->authorize('rejectContractByModerator', [Contract::class, $contract]);

        // Расторжение договора
        $this->contractService->rejectContractConfirmation($contract->id, $request->message);

        $this->adminCourseService->rejectOnContract($contract);

        // Снимаем с публикации
        if (!$contract->isQuota()) {
            $this->adminCourseService->rejectCourse($contract->course->id);
        }

        (new NotificationService('Договор отклонен', 'notifications.contract_rejected_by_admin', $contract->course->id, $contract->course->user->id, 'ru', 3, $request->message))->notify();

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function xml(Request $request): JsonResponse
    {
        /** @var Contract $contract */
        $contract = Contract::findOrFail($request->contract_id);

        if ($contract && $contract->document) {
            return response()->json(['xml' => $contract->document->content]);
        }

        return response()->json(['message' => 'Электронный договор не найден'], 500);
    }
}
