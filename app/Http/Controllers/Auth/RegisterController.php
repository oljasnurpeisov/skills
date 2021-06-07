<?php

namespace App\Http\Controllers\Auth;

use App\Extensions\RandomStringGenerator;
use App\Http\Controllers\Controller;
use App\Models\Dialog;
use App\Models\PayInformation;
use App\Models\Type_of_ownership;
use App\Models\UserInformation;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Service\Auth\AuthService;
use Services\Auth\RegisterService;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * @var AuthService
     */
    private $authService;

    /**
     * @var RegisterService
     */
    private $registerService;

    /**
     * Create a new controller instance.
     *
     * @param AuthService $authService
     * @param RegisterService $registerService
     */
    public function __construct(AuthService $authService, RegisterService $registerService)
    {
        $this->authService = $authService;
        $this->registerService = $registerService;
        $this->middleware('guest');
        $this->redirectTo = '/' . app()->getLocale() . '/profile-author-information';
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    function showRegistrationForm()
    {

        $types_of_ownership = Type_of_ownership::all();


        return view('auth.register', [
            'types_of_ownership' => $types_of_ownership
        ]);
    }

    protected function validator(array $data)
    {
//        $attributes = [
//            'iin' => 'ИИН/БИН'
//        ];
//
//        $messages = [
//            'numeric' => 'The :attribute may not be greater than :max.',
//        ];

        return Validator::make($data, [
            'email_register' => 'required|email|unique:users,email|max:255',
            'iin' => 'required|unique:users|min:12|max:12',
            'company_name' => 'required|max:255',
//            'company_logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'company_logo' => 'required|max:255',
            'password_register' => ['required',
                'min:8',
                'max:20',
                'confirmed',
//                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data)
    {
        return $this->registerService->register([
//            'name' => $data['name'],
            'email'             => $data['email_register'],
            'password'          => Hash::make($data['password_register']),
            'iin'               => $data['iin'],
            'type_of_ownership' => $data['type_of_ownership'],
            'company_name'      => $data['company_name'],
            'company_logo'      => $data['company_logo'],
        ]);
    }
}
