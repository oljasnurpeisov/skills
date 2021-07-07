<?php

namespace App\Http\Controllers\Admin;

use App\Console\Commands\AVR\AVRGenerate;
use App\Http\Controllers\Controller;
use App\Libraries\Kalkan\Certificate;
use App\Models\AVR;
use App\Services\Signing\ValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Illuminate\View\View;
use PhpOffice\PhpWord\Exception\Exception;
use Services\AVR\AdminAVRService;
use Services\AVR\AVRFilterService;
use Services\Contracts\AVRService;

class AVRController extends Controller
{
    /**
     * @var AVRFilterService
     */
    private $AVRFilterService;

    /**
     * @var AVRService
     */
    private $AVRService;

    /**
     * @var AdminAVRService
     */
    private $adminAVRService;

    /**
     * @var ValidationService
     */
    private $validationService;

    /**
     * AVRController constructor.
     *
     * @param AVRFilterService $AVRFilterService
     * @param AVRService $AVRService
     * @param AdminAVRService $adminAVRService
     */
    public function __construct(AVRFilterService $AVRFilterService, AVRService $AVRService, AdminAVRService $adminAVRService, ValidationService $validationService)
    {
        $this->AVRFilterService = $AVRFilterService;
        $this->AVRService       = $AVRService;
        $this->adminAVRService  = $adminAVRService;
        $this->validationService = $validationService;
    }

    /**
     * АВР
     *
     * @param Request $request
     * @return View
     */
    public function all(Request $request): View
    {
        return view('admin.v2.pages.avr.index', [
            'title'     => 'АВР',
            'avr'       => $this->AVRFilterService->getOrSearch($request->all()),
            'request'   => $request->all()
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
        return view('admin.v2.pages.avr.index', [
            'avr' => $this->AVRFilterService->getOrSearch($request->all(), 'pending'),
            'request'   => $request->all(),
            'title'     => 'Ожидающие подписания АВР'
        ]);
    }

    /**
     * Подписанные АВР
     *
     * @param Request $request
     * @return View
     */
    public function signed(Request $request): View
    {
        return view('admin.v2.pages.avr.index', [
            'avr' => $this->AVRFilterService->getOrSearch($request->all(), 'signed'),
            'request'   => $request->all(),
            'title'     => 'Подписанные АВР'
        ]);
    }

    /**
     * Просмотр АВР
     *
     * @param Request $request
     * @return View
     */
    public function view(Request $request): View
    {
        return view('admin.v2.pages.avr.view', [
            'avr'  => AVR::findOrFail($request->avr_id),
            'title'     => 'Просмотр АВР'
        ]);
    }

    /**
     * Предпросмотр договора
     *
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public function getAvrHtml(Request $request): string
    {
        return view('app.pages.author.courses.contractDoc', [
            'contract' => $this->AVRService->avrToHtml($request->avr_id)
        ]);
    }

    /**
     * Check and send
     * @param Request $request
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function next(Request $request): JsonResponse
    {
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

            $this->adminAVRService->acceptAvr($request->avr_id, $xml, $this->validationService->getResponse());

        } else {
            $message = $this->validationService->getError();
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'certificate' => $certificate,
            'redirect' => route('admin.avr.pending', ['lang' => $request->lang]),
            'response' => $this->validationService->getResponse()
        ], $success ? 200 : 500);
    }

    /**
     * Запуск генератора АВР
     *
     * @TODO REMOVE THIS!!!!
     */
    public function generate()
    {
        Queue::push(new AVRGenerate());
    }
}
