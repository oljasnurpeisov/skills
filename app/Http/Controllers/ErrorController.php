<?php

namespace App\Http\Controllers;

use App\Http\Requests\ErrorOnPageRequest;
use App\Mail\ErrorOnPageMail;
use Illuminate\Support\Facades\Mail;

class ErrorController extends Controller
{
    public function store(ErrorOnPageRequest $request)
    {
        Mail::to(config('info@enbek.kz'))
            ->queue(new ErrorOnPageMail(
                $request->input('url', ''),
                $request->input('phone', ''),
                $request->input('text', ''),
                $request->input('comment', ''),
            ));

        return view('success_send_error_on_page');
    }
}
