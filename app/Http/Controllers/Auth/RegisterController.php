<?php

namespace App\Http\Controllers\Auth;

use App\Extensions\RandomStringGenerator;
use App\Http\Controllers\Controller;
use App\Models\PayInformation;
use App\Models\Type_of_ownership;
use App\Models\UserInformation;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
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
            'email' => 'required|unique:users|max:255',
            'iin' => 'required|unique:users|min:12|max:12',
            'company_name' => 'required|max:255',
            'company_logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'password' => ['required',
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
     * @return \App\Models\User
     */
    protected function create(array $data)
    {


        $user = User::create([
//            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'iin' => $data['iin'],
            'type_of_ownership' => $data['type_of_ownership'],
            'company_name' => $data['company_name'],
//            'company_logo' => '/images/profile_images/' . $imageName,
        ]);

        $imageName = time() . '.' . $data['company_logo']->getClientOriginalExtension();
        $user->company_logo = '/users/user_' . $user->id . '/profile/image/' . $imageName;
        $data['company_logo']->move(public_path('users/user_' . $user->id . '/profile/image'), $imageName);

//        $user->company_logo = $imageName;
        $user->save();

        $user_information = new UserInformation;
        $user_information->user_id = $user->id;
        $user_information->save();

        $user_pay_information = new PayInformation;
        $user_pay_information->user_id = $user->id;
        $user_pay_information->save();

        $user->roles()->sync([4]);

        return ($user);
    }
}
