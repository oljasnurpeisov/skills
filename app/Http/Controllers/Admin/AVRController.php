<?php

namespace App\Http\Controllers\Admin;

use App\Console\Commands\AVR\AVRGenerate;
use App\Http\Controllers\Controller;
use App\Libraries\Kalkan\Certificate;
use App\Models\AVR;
use App\Models\Contract;
use App\Services\Files\StorageService;
use App\Services\Signing\ValidationService;
use GuzzleHttp\Exception\GuzzleException;
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
     * @param ValidationService $validationService
     */
    public function __construct(AVRFilterService $AVRFilterService, AVRService $AVRService, AdminAVRService $adminAVRService, ValidationService $validationService)
    {
        $this->AVRFilterService     = $AVRFilterService;
        $this->AVRService           = $AVRService;
        $this->adminAVRService      = $adminAVRService;
        $this->validationService    = $validationService;
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
            'avr'       => $this->AVRFilterService->getOrSearch($request->all(), 'pending'),
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
            'avr'       => $this->AVRFilterService->getOrSearch($request->all(), 'signed'),
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
        $avr = AVR::findOrFail($request->avr_id);

        return view('admin.v2.pages.avr.view', [
            'avr'           => $avr,
            'certificates'  => $this->AVRService->searchCertifications($avr),
            'title'         => 'Просмотр АВР'
        ]);
    }

    /**
     * Предпросмотр акта
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
     * Просмотр подписанного акта
     *
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public function getAvrPdf(Request $request): string
    {
        /** @var AVR $act */
        $act = AVR::where('id', $request->avr_id)->firstOrFail();

        if ($act && $act->link) {
            return StorageService::preview($act->link);
        }

        abort(404);
    }

    /**
     * Check and send
     *
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function next(Request $request): JsonResponse
    {
        $xml = $request->post('xml');

        $success = false;
        $certificate = null;

        $x509 = Certificate::getCertificate($xml, true);

        if ($x509) {
            $certificate = $x509;
        }

        if($certificate->canSign(env('SIGN_OWNER')) && $this->validationService->verifyXml($xml)) {

            $success = true;

            $message = 'Акт успешно подписан';

            $this->adminAVRService->acceptAvr($request->avr_id, $xml, $this->validationService->getResponse());

        } else {
            $message = $certificate->getError() ?: $this->validationService->getError();
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
     * Get XML for signing
     * @param Request $request
     * @return JsonResponse
     */
    public function xml(Request $request): JsonResponse
    {
        /** @var AVR $act */
        $act = AVR::findOrFail($request->avr_id);

        if ($act && $act->document) {
            return response()->json(['xml' => $act->xml()]);
        }

        return response()->json(['message' => 'Электронный акт не найден'], 500);
    }
}
