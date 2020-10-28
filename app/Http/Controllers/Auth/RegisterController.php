<?php

namespace App\Http\Controllers\Auth;

use App\Extensions\RandomStringGenerator;
use App\Http\Controllers\Controller;
use App\Models\Type_of_ownership;
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
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
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
        return Validator::make($data, [
            'email' => 'required|unique:users|max:255',
            'iin' => 'required|unique:users|max:12',
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
     * @param  array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $imageName = time() . '.' . $data['company_logo']->getClientOriginalExtension();
        $data['company_logo']->move(public_path('images/profile_images'), $imageName);


        $user = User::create([
//            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'iin' => $data['iin'],
            'type_of_ownership' => $data['type_of_ownership'],
            'company_name' => $data['company_name'],
            'company_logo' => '/images/profile_images/' . $imageName,
        ]);

        $user->roles()->sync([4]);

        return ($user);
    }
}
