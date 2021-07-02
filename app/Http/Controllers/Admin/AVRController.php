<?php

namespace App\Http\Controllers\Admin;

use App\Console\Commands\AVR\AVRGenerate;
use App\Http\Controllers\Controller;
use App\Models\AVR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Illuminate\View\View;
use PhpOffice\PhpWord\Exception\Exception;
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
     * AVRController constructor.
     *
     * @param AVRFilterService $AVRFilterService
     * @param AVRService $AVRService
     */
    public function __construct(AVRFilterService $AVRFilterService, AVRService $AVRService)
    {
        $this->AVRFilterService = $AVRFilterService;
        $this->AVRService       = $AVRService;
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
    public function getAvrHtml(Request $request): string
    {
        return view('app.pages.author.courses.contractDoc', [
            'contract' => $this->AVRService->avrToHtml($request->avr_id)
        ]);
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
