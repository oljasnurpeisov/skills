<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Extensions\RandomStringGenerator;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    function sendNewPassword(Request $request)
    {

        $request->validate([
            'email' => 'required|exists:users,email|max:255',
        ]);

        $generator = new RandomStringGenerator();
        $generate_password = $generator->generateString();


        $data = [
            'email' => $request->email,
            'password' => $generate_password,
        ];

        Mail::send('app.pages.page.emails.view', ['data' => $data], function ($message) use ($request) {
            $message->from(env("MAIL_USERNAME"), 'Enbek');
            $message->to($request->email, 'Receiver')->subject('');
        });

        $user = User::where('email', '=', $request->email)->first();
        $user->password = Hash::make($generate_password);
        $user->save();

        return redirect('/'.app()->getLocale().'/login');

    }

    function forgotIndex()
    {
        return view("auth.forgot_password", [
            "items" => [],
        ]);
    }
}
