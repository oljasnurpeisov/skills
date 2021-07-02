<?php

namespace App\Http\Controllers\App\Author;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Services\Contracts\AuthorAVRService;

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
     * AVRController constructor.
     *
     * @param AuthorAVRService $authorAVRService
     */
    public function __construct(AuthorAVRService $authorAVRService)
    {
        $this->authorAVRService = $authorAVRService;
    }

    /**
     * АВР
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        return view('app.pages.author.avr.index', [
            'avrs'      => $this->authorAVRService->getOrSearchMyContracts($request->all()),
            'request'   => $request->all()
        ]);
    }
}
