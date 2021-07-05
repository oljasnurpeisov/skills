<?php

namespace App\Http\Controllers\App\Author;

use App\Http\Controllers\Controller;
use App\Models\AVR;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\Exception\Exception;
use Services\Contracts\AuthorAVRService;
use Services\Contracts\AVRService;

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
     * AVRController constructor.
     *
     * @param AuthorAVRService $authorAVRService
     * @param AVRService $AVRService
     */
    public function __construct(AuthorAVRService $authorAVRService, AVRService $AVRService)
    {
        $this->authorAVRService = $authorAVRService;
        $this->AVRService       = $AVRService;
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

        return view('app.pages.author.avr.avrDoc', [
            'avr' => $this->AVRService->avrToHtml($avr->id)
        ]);
    }

    /**
     * Заглушка, пока нет ЭЦП, нет никаких проверок!!!!
     *
     * @TODO: REMOVE THIS!!!
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function next(Request $request): RedirectResponse
    {
        $this->authorAVRService->acceptAvr($request->avr_id);

        return redirect()->route('author.avr.index', ['lang' => $request->lang]);
    }
}
