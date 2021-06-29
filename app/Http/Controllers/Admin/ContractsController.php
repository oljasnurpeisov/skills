<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Course;
use Dingo\Api\Auth\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpWord\Exception\Exception;
use Services\Contracts\ContractFilterService;
use Services\Contracts\ContractService;
use Services\Course\AdminCourseService;

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
     * ContractFilterService constructor.
     *
     * @param ContractFilterService $contractFilterService
     * @param ContractService $contractService
     * @param AdminCourseService $adminCourseService
     */
    public function __construct(ContractFilterService $contractFilterService, ContractService $contractService, AdminCourseService $adminCourseService)
    {
        $this->contractFilterService    = $contractFilterService;
        $this->contractService          = $contractService;
        $this->adminCourseService       = $adminCourseService;
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
     * Заглушка, пока нет ЭЦП
     *
     * @TODO: REMOVE THIS!!!
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function next(Request $request): RedirectResponse
    {
        $this->adminCourseService->acceptContract($request->contract_id);

        return redirect()->route('admin.contracts.pending', ['lang' => $request->lang]);
    }

    /**
     * Расторжение договора директором
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function rejectContract(Request $request): RedirectResponse
    {
        $contract = Contract::findOrFail($request->contract_id);

        if (!$contract->isSigned() or !$contract->isQuota() or ! \Auth::user()->hasRole('rukovoditel')) abort(403);

        // Отклоняем договор
        $this->contractService->rejectContract($contract->id, $request->message);

        // Отменяем доступ по квоте
        $this->adminCourseService->rejectQuota($contract->course->id);

        // @TODO Send author notification

        return redirect()->back();
    }
}
