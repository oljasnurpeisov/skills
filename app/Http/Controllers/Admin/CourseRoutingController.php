<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpWord\Exception\Exception;
use Services\Contracts\ContractService;
use Services\Contracts\ContractServiceRouting;

class CourseRoutingController extends Controller
{
    /**
     * @var ContractService
     */
    private $contractService;

    /**
     * @var ContractServiceRouting
     */
    private $contractServiceRouting;

    /**
     * CourseRoutingController constructor.
     *
     * @param ContractService $contractService
     * @param ContractServiceRouting $contractServiceRouting
     */
    public function __construct(ContractService $contractService, ContractServiceRouting $contractServiceRouting)
    {
        $this->contractService          = $contractService;
        $this->contractServiceRouting   = $contractServiceRouting;
    }

    /**
     * Создаем договор, закрепляем за курсом,
     * отправляем автору на подписание
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function start(Request $request): RedirectResponse
    {
        $course = Course::findOrFail($request->course_id);

        $contract = $this->contractService->createContract($course, $request->type);

        if ($contract) {
            Session::flash('status', 'Договор ('. $contract->getTypeName() .') отправлен на подписание');
            $this->contractServiceRouting->toNextRoute($contract);
        }

        return redirect()->back();
    }
}
