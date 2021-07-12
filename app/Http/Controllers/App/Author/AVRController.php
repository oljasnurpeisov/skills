<?php

namespace App\Http\Controllers\App\Author;

use App\Http\Controllers\Controller;
use App\Http\Requests\AVR\UpdateAVR;
use App\Libraries\Kalkan\Certificate;
use App\Models\AVR;
use App\Services\Signing\ValidationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpWord\Exception\Exception;
use Services\Contracts\AuthorAVRService;
use Services\Contracts\AVRService;
use Services\Notifications\NotificationService;

/**
 * Class AVRController
 *
 * @author kgurovoy@gmail.com
 * @package App\Http\Controllers\App\Author
 */
class AVRController extends Controller
{
    /**
     * @var AuthorAVRService
     */
    private $authorAVRService;

    /**
     * @var AVRService
     */
    private $AVRService;

    /**
     * @var ValidationService
     */
    private $validationService;

    /**
     * AVRController constructor.
     *
     * @param AuthorAVRService $authorAVRService
     * @param AVRService $AVRService
     * @param ValidationService $validationService
     */
    public function __construct(AuthorAVRService $authorAVRService, AVRService $AVRService, ValidationService $validationService)
    {
        $this->authorAVRService     = $authorAVRService;
        $this->AVRService           = $AVRService;
        $this->validationService    = $validationService;
    }

    /**
     * АВР
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        return view('app.pages.author.avr.index', [
            'avrs'      => $this->authorAVRService->getOrSearchMyContracts($request->all()),
            'request'   => $request->all()
        ]);
    }

    /**
     * Просмотр АВР
     *
     * @param Request $request
     * @return View
     */
    public function avr(Request $request): View
    {
        return view('app.pages.author.avr.signing', [
            'avr' => AVR::whereHas('course', function ($q) {
                return $q->whereAuthorId(Auth::user()->id);
            })->findOrFail($request->avr_id)
        ]);
    }

    /**
     * АВР
     *
     * @param Request $request
     * @return View
     * @throws Exception
     */
    public function avrDoc(Request $request): View
    {
        $avr = AVR::
            whereHas('course', function ($q) {
                return $q->whereAuthorId(Auth::user()->id);
            })
            ->findOrFail($request->avr_id);

        if (empty($avr->invoice_link)) {
            $content = $this->AVRService->avrToHtmlWithoutNumber($avr->id);
        } else {
            $content = $this->AVRService->avrToHtml($avr->id);
        }

        return view('app.pages.author.avr.avrDoc', [
            'avr' => $content
        ]);
    }

    /**
     * Обновляем номер АВР + счет фактура
     *
     * @param UpdateAVR $request
     * @return RedirectResponse
     */
    public function update(UpdateAVR $request): RedirectResponse
    {
        /** @var AVR $act */
        $act = AVR::where('id', $request->avr_id)->firstOrFail();

        $this->authorAVRService->updateAVR($request->avr_id, $request->all());

        $act->refresh();

        if ($act->invoice_link && $act->number && $act->author_signed_at) {
            $this->authorAVRService->acceptAvr($act->id);
            return redirect()->route('author.avr.index', ['lang' => 'ru']);
        }

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function xml(Request $request): JsonResponse
    {
        $act = AVR::where('id', $request->avr_id)->firstOrFail();
        $xml = $this->authorAVRService->generateXml($act);

        return response()->json(['xml' => $xml]);
    }

    /**
     * Check and send contract (act)
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|JsonResponse|RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function next(Request $request)
    {
        $xml = $request->post('xml');

        $success = false;
        $certificate = null;

        $x509 = Certificate::getCertificate($xml, true);

        if ($x509) {
            $certificate = $x509;
        }

        if($certificate->canSign(Auth::user()->iin) && $this->validationService->verifyXml($xml)) {

            $success = true;

            $message = 'Акт успешно подписан';

            $this->authorAVRService->acceptAvr($request->avr_id, $xml, $this->validationService->getResponse());

            Session::flash('status', $message);

            $avr = AVR::find($request->avr_id);
            (new NotificationService('АВР подписан', 'avr_signed_by_author', $avr->course->id, $avr->course->user->id, 'ru', 3))->notify();

        } else {
            $message = $certificate->getError() ?: $this->validationService->getError();
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'certificate' => $certificate,
            'redirect' => route('author.avr.view', ['avr_id' => $request->avr_id, 'lang' => $request->lang]),
            'response' => $this->validationService->getResponse()
        ], $success ? 200 : 500);
    }
}
