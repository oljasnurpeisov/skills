<?php

namespace App\Http\Controllers\Admin;

//use App\Helpers\Buffet;
//use App\Models\Card;
//use App\Models\Company;
use App\Extensions\RandomStringGenerator;
use App\Models\PayInformation;
use App\Models\Role;
use App\Models\Type_of_ownership;
use App\Models\User;
//use App\Models\Log;

use App\Models\UserInformation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

/**
 * --------------------------------------------------------------------------
 *  UserController
 * --------------------------------------------------------------------------
 *
 *  Этот контроллер отвечает за редактирование данных пользователя
 *
 */
class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->term ? $request->term : '';

        $query = User::orderBy('id', 'desc')->whereHas('roles', function($q){
            $q->where('role_id', '=', 4);
        });
        if ($term) {
            $query = $query->where('email', 'like', '%' . $term . '%');
        }

        $items = $query->paginate();

        foreach ($items as $key => $item) {
            $fill_user_info = UserInformation::where('user_id', '=', $item->id)->first();
            $fill_pay_info = PayInformation::where('user_id', '=', $item->id)->first();
            if(empty($fill_user_info) and empty($fill_pay_info)){
                $items->forget($key);
            }
        }

        return view('admin.v2.pages.authors.index', [
            'items' => $items,
            'term' => $term,
        ]);
    }

    public function create(Request $request)
    {
        $item = new User;
        $types_of_ownership = Type_of_ownership::all();

        $roles = Role::orderBy('name', 'asc')->get();
        return view('admin.v2.pages.users.create', [
            'item' => $item,
            'roles' => $roles,
            'types_of_ownership' => $types_of_ownership
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|min:3|max:255|unique:users,email',
            'iin' => 'required|unique:users,iin|max:12',
        ]);

        $generator = new RandomStringGenerator();
        $generate_password = $generator->generateString();

        $item = new User;
        $item->name = $request->name;
        $item->email = $request->email;
        $item->iin = $request->iin;
        $item->company_name = $request->company_name;
        $item->company_logo = $request->company_logo;
        $item->password = Hash::make($generate_password);
        $item->is_activate = 1;
        $item->email_verified_at = Carbon::now()->toDateTimeString();
        $item->type_of_ownership = $request->type_of_ownership;

        $item->save();
        $item->roles()->sync([$request->role_id]);

//        Buffet::log('add', 'users', $item->id, $item->name);

        return redirect('/' . app()->getLocale() . '/admin/user/' . $item->id)->with('status', __('admin.notifications.record_stored') . '<br>' . __('admin.notifications.new_password', ['password' => $generate_password]));
    }

    public function edit($lang, User $item)
    {
        $roles = Role::orderBy('name', 'asc')->get();
        $types_of_ownership = Type_of_ownership::all();
        $statuses = [0, 1, 2];
        $pay_information = PayInformation::where('user_id', '=', $item->id)->first();
        $user_information = UserInformation::where('user_id', '=', $item->id)->first();
        return view('admin.v2.pages.authors.view', [
            'item' => $item,
            'roles' => $roles,
            'types_of_ownership' => $types_of_ownership,
            'statuses' => $statuses,
            'pay_information' => $pay_information,
            'user_information' => $user_information
        ]);
    }

    public function update(Request $request, $lang, User $item)
    {

        switch ($request->input('action')) {
            case 'activate':
                $item->is_activate = 1;
                $item->save();
                return redirect('/' . app()->getLocale() . '/admin/author/index')->with('status', __('admin.notifications.record_updated'));
                break;

        }
        $data = [
            'message_text' => $request->rejectMessage,
        ];

        if (!empty($item->company_logo)) {
            File::delete(public_path($item->company_logo));
        }
        $item->delete();

        Mail::send('app.pages.page.emails.reject', ['data' => $data], function ($message) use ($request, $item) {
            $message->from(env("MAIL_USERNAME"), 'Enbek');
            $message->to($item->email, 'Receiver')->subject('');
        });


        return redirect('/' . app()->getLocale() . '/admin/author/index')->with('status', __('admin.notifications.record_updated'));
    }

    public function passwordUpdate($lang, User $item)
    {

        $generator = new RandomStringGenerator();
        $generate_password = $generator->generateString();

        $user = User::where('email', '=', $item->email)->first();
        $user->password = Hash::make($generate_password);
        $user->save();

//        Buffet::log('edit', 'users', $item->id, $item->name);

        return redirect('/' . app()->getLocale() . '/admin/user/' . $item->id)->with('status', __('admin.notifications.new_password', ['password' => $generate_password]));
    }

    public function delete($lang, User $item)
    {
        if (!empty($item->company_logo)) {
            File::delete(public_path($item->company_logo));
        }
        $item->delete();
        return redirect('/' . app()->getLocale() . '/admin/user/index')->with('status', __('admin.notifications.record_deleted'));
    }

    public function profile(Request $request)
    {
        return view('admin.v2.pages.users.profile', [
            'item' => $request->user()
        ]);
    }

    public function profileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'max:255',
            'email' => [Rule::unique('users')->ignore($request->user()->id), 'max:255'],
            'password' => ['min:8',
                'max:20',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/',
                'different:old_password'],
        ]);

        $user = $request->user();
        if (!empty($request->old_password)) {
            if (!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                $validator->errors()->add('old_password', 'Пароль не совпадает');
            }
        }

        if (count($validator->errors()) > 0) {
            return redirect('/' . app()->getLocale() . '/admin/profile')
                ->withErrors($validator);
        }

        if (!empty($request->name)) {
            $user->name = $request->name;
        }
        if (!empty($request->email)) {
            $user->email = $request->email;
        }
        if (!empty($request->old_password) && !empty($request->password)) {
            $hash = bcrypt($request->password);
            $user->password = $hash;
        }
        $user->save();

//        Buffet::log('edit', 'users', $user->id, $user->name);

        return redirect('/' . app()->getLocale() . '/admin/profile');
    }


}
