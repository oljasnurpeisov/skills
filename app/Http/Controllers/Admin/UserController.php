<?php

namespace App\Http\Controllers\Admin;

use App\Extensions\RandomStringGenerator;
use App\Models\PayInformation;
use App\Models\Role;
use App\Models\Type_of_ownership;
use App\Models\User;
use App\Models\UserInformation;
use App\Services\Users\UserFilterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
class UserController extends Controller
{
    /**
     * @var UserFilterService
     */
    private $userFilterService;

    /**
     * UserController constructor.
     *
     * @param UserFilterService $userFilterService
     */
    public function __construct(UserFilterService $userFilterService)
    {
        $this->userFilterService = $userFilterService;
    }

    public function index(Request $request)
    {
        $term = $request->term ? $request->term : '';

        $query = User::orderBy('id', 'desc')->whereHas('roles', function ($q) {
            $q->whereIn('slug', ['admin', 'moderator', 'tech_support', 'yurist', 'planirovshchik', 'buhgalter', 'rukovoditel']);
        });
        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }

        $query = $this->userFilterService->getOrSearch($query, $request->all());

        $items = $query->paginate();

        return view('admin.v2.pages.users.index', [
            'items' => $items,
            'term' => $term,
            'request' => $request->all()
        ]);
    }

    public function index_all(Request $request)
    {
        $term = $request->term ? $request->term : '';

        $query = User::orderBy('id', 'desc');

        if ($term) {
            $query = $query->where('name', 'like', '%' . $term . '%');
        }

        $query = $this->userFilterService->getOrSearch($query, $request->all());

        $items = $query->paginate();

        return view('admin.v2.pages.users.index', [
            'items' => $items,
            'term' => $term,
            'request' => $request->all()
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
        ]);

        $generator = new RandomStringGenerator();
        $generate_password = $generator->generateString();

        $item = new User;
        $item->name = $request->name;
        $item->email = $request->email;
        $item->password = Hash::make($generate_password);
        $item->is_activate = 1;
        $item->email_verified_at = Carbon::now()->toDateTimeString();
        $item->type_of_ownership = 1;

        $item->save();
        $item->roles()->sync([$request->role_id]);

        $role = Role::where('id', '=', $request->role_id)->first();

        if ($role->slug == 'author') {
            $item_information = new UserInformation;
            $item_information->user_id = $item->id;
            $item_information->name = $request->name;
            $item_information->save();

            $item_pay_information = new PayInformation;
            $item_pay_information->user_id = $item->id;
            $item_pay_information->save();

        }

        return redirect('/' . app()->getLocale() . '/admin/user/' . $item->id)->with('status', __('admin.notifications.record_stored') . '<br>' . __('admin.notifications.new_password', ['password' => $generate_password]));
    }

    public function edit($lang, User $item)
    {
        $roles = Role::orderBy('name', 'asc')->get();
        $types_of_ownership = Type_of_ownership::all();
        $statuses = [0, 1, 2];

        return view('admin.v2.pages.users.edit', [
            'item' => $item,
            'roles' => $roles,
            'types_of_ownership' => $types_of_ownership,
            'statuses' => $statuses
        ]);
    }

    public function update(Request $request, $lang, User $item)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:255',
            'email' => ['required', Rule::unique('users', 'email')->ignore($item->id), 'max:255']
        ]);

        if ($request->is_activate != 2) {

            if ($request->company_logo != $item->company_logo) {
                File::delete(public_path($item->company_logo));
            }

            $item->name = $request->name;
            $item->email = $request->email;

            $role = Role::where('id', '=', $request->role_id)->first();

            if ($role->slug == 'author') {
                $info = UserInformation::where('user_id', '=', $item->id)->first();
                $pay_info = PayInformation::where('user_id', '=', $item->id)->first();
                if (empty($info)) {
                    $item_information = new UserInformation;
                    $item_information->user_id = $item->id;
                    $item_information->name = $request->name;
                    $item_information->save();
                } else {
                    $info->name = $request->name;
                    $info->save();
                }
                if (empty($pay_info)) {
                    $item_pay_information = new PayInformation;
                    $item_pay_information->user_id = $item->id;
                    $item_pay_information->save();
                }

            }

            $item->save();
            $item->roles()->sync([$request->role_id]);

        } else {
            $data = [
                'message_text' => $request->rejectMessage,
            ];

            if (!empty($item->company_logo)) {
                File::delete(public_path($item->company_logo));
            }
            $item->delete();

            Mail::send('app.pages.page.emails.reject', ['data' => $data], function ($message) use ($request) {
                $message->from(env("MAIL_USERNAME"), 'Enbek');
                $message->to($request->email, 'Receiver')->subject('');
            });

            return redirect('/' . app()->getLocale() . '/admin/user/index')->with('status', __('admin.notifications.record_deleted'));
        }

        return redirect('/' . app()->getLocale() . '/admin/user/' . $item->id)->with('status', __('admin.notifications.record_updated'));
    }

    public function passwordUpdate($lang, User $item)
    {
        $generator = new RandomStringGenerator();
        $generate_password = $generator->generateString();

        $user = User::where('email', '=', $item->email)->first();
        $user->password = Hash::make($generate_password);
        $user->save();

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

        return redirect('/' . app()->getLocale() . '/admin/profile');
    }
}
