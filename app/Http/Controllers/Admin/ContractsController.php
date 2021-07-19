<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\Kalkan\Certificate;
use App\Models\Contract;
use App\Models\Course;
use App\Services\Files\StorageService;
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
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
     * История договоров по курсу
     *
     * @param Request $request
     * @return view
     */
    public function history(Request $request): View
    {
        return view('admin.v2.pages.contracts.history', [
            'history'   => $this->adminCourseService->getHistory($request->contract_id),
            'title'     => 'Просмотр истории договоров'
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
     * Просмотр подписанного договора
     *
     * @param Request $request
     * @return string
     */
    public function getContractPdf(Request $request)
    {
        /** @var Contract $contract */
        $contract = Contract::where('id', $request->contract_id)->firstOrFail();

        if ($contract && $contract->link) {
            return StorageService::preview($contract->link);
        }

        abort(404);
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

            $x509 = Certificate::getCertificate($xml, true);

            if ($x509) {
                $certificate = $x509;
            }

            if($certificate->canSign(env('SIGN_OWNER')) && $this->validationService->verifyXml($xml)) {

                $success = true;

                $message = 'Договор успешно подписан';

                $this->adminCourseService->acceptContract($request->contract_id, $xml, $this->validationService->getResponse());

            } else {
                $message = $certificate->getError() ?: $this->validationService->getError();
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
     * Get XML for signing
     * @param Request $request
     * @return JsonResponse
     */
    public function xml(Request $request): JsonResponse
    {
        /** @var Contract $contract */
        $contract = Contract::findOrFail($request->contract_id);

        if ($contract && $contract->document) {
            return response()->json(['xml' => $contract->xml()]);
        }

        return response()->json(['message' => 'Электронный договор не найден'], 500);
    }

    /**
     * @param Request $request
     * @return StreamedResponse|BinaryFileResponse
     * @throws Exception
     */
    public function download(Request $request)
    {
        /** @var Contract $contract */
        $contract = Contract::findOrFail($request->contract_id);

        if (!empty($contract->link) && pathinfo($contract->link)['extension'] === 'pdf') {
            return StorageService::download($contract->link, sprintf('Договор №%s.pdf', $contract->number));
        } else {
            $path = $this->contractService->contractToPdf($request->contract_id, false, true);

            return response()->download(StorageService::path($path), sprintf('Соглашение №%s.pdf', $contract->number))->deleteFileAfterSend(true);
        }
    }
}
