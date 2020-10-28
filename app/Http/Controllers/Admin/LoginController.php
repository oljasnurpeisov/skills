<?php

namespace App\Http\Controllers\Admin;

use App\Extensions\RandomStringGenerator;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = "/admin";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware("guest")->except("logout");
    }

    public function showLoginForm()
    {
        return view("admin.v2.pages.auth.login");
    }

    public function showPasswordResetForm()
    {
        return view('admin.v2.pages.auth.password_reset');
    }

    public function passwordReset(Request $request)
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
            $message->from('info@panama.kz', 'Enbek');
            $message->to($request->email, 'Receiver')->subject('');
        });

        $user = User::where('email', '=', $request->email)->first();
        $user->password = Hash::make($generate_password);
        $user->save();

        return redirect('/'.app()->getLocale().'/admin/login');
    }
}
