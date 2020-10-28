<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\PayInformation;
use App\Models\Type_of_ownership;
use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class UserController extends Controller
{

    public function profile()
    {
        $user = User::where('id', '=', Auth::user()->getAuthIdentifier())->with('type_ownership')->first();
        return view("app.pages.page.profile.profile", [
            "user" => $user,
        ]);
    }

    public function edit_profile()
    {
        $user = User::where('id', '=', Auth::user()->getAuthIdentifier())->with('type_ownership')->first();
        $types_of_ownership = Type_of_ownership::all();

        return view("app.pages.page.profile.edit_profile", [
            "user" => $user,
            "types_of_ownership" => $types_of_ownership
        ]);
    }

    public function update_profile(Request $request)
    {
        $request->validate([
            'email' => ['required', Rule::unique('users')->ignore($request->user()->id), 'max:255'],
            'iin' => ['required', Rule::unique('users')->ignore($request->user()->id), 'max:12'],
            'company_logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);


        $user = $request->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->iin = $request->iin;
        $user->company_name = $request->company_name;
        $user->type_of_ownership = $request->type_of_ownership;

        if (!empty($request->company_logo)) {
            File::delete(public_path($user->company_logo));

            $imageName = time() . '.' . $request['company_logo']->getClientOriginalExtension();
            $request['company_logo']->move(public_path('images/profile_images'), $imageName);
            $user->company_logo = '/images/profile_images/' . $imageName;
        }

        $user->save();

        return redirect("/" . app()->getLocale() . "/profile");
    }

    public function change_password()
    {
        $user = Auth::user();
        return view("app.pages.page.profile.change_password", [
            "user" => $user,
        ]);
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => ['required',
                'min:8',
                'max:20',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/',
                'different:old_password'],
        ]);

        $user = $request->user();

        if (Hash::check($request->old_password, $user->password)) {

            $user->password = Hash::make($request['password']);
            $user->save();
        }

        return view("app.pages.page.profile.profile", [
            "user" => $user,
        ]);
    }

    public function profile_pay_information()
    {
        $user = User::where('id', '=', Auth::user()->getAuthIdentifier())->with('type_ownership')->first();
        $pay_information = PayInformation::where('user_id', '=', $user->id)->first();
        return view("app.pages.page.profile.profile_pay_information", [
            "user" => $user,
            "pay_information" => $pay_information
        ]);
    }

    public function update_profile_pay_information(Request $request)
    {
        $information = PayInformation::where('user_id', '=', Auth::user()->getAuthIdentifier())->first();

        if (empty($information)) {

            $item = new PayInformation;
            $item->user_id = Auth::user()->getAuthIdentifier();
            $item->merchant_certificate_id = $request->merchant_certificate_id;
            $item->merchant_name = $request->merchant_name;
            $item->private_key_pass = $request->private_key_pass;
            $item->merchant_id = $request->merchant_id;

            if (!empty($request->public_key_path)) {
                $public_key_path_name = time() . '_pbk' . '.' . $request['public_key_path']->getClientOriginalExtension();
                $item->public_key_path = '/files/payment_certificates/' . $public_key_path_name;
                $request['public_key_path']->move(public_path('files/payment_certificates'), $public_key_path_name);
            }
            if (!empty($request->private_key_path)) {
                $private_key_path_name = time() . '_prk' . '.' . $request['private_key_path']->getClientOriginalExtension();
                $item->private_key_path = '/files/payment_certificates/' . $public_key_path_name;
                $request['private_key_path']->move(public_path('files/payment_certificates'), $private_key_path_name);
            }

            $item->save();


            return redirect("/" . app()->getLocale() . "/profile_pay_information");
        } else {

            if (!empty($request->public_key_path)) {
                File::delete(public_path($information->public_key_path));

                $public_key_path_name = time() . '_pbk' . '.' . $request['public_key_path']->getClientOriginalExtension();
                $request['public_key_path']->move(public_path('files/payment_certificates'), $public_key_path_name);
                $information->public_key_path = '/files/payment_certificates/' . $public_key_path_name;
            }

            if (!empty($request->private_key_path)) {
                File::delete(public_path($information->private_key_path));

                $private_key_path_name = time() . '_prk' . '.' . $request['private_key_path']->getClientOriginalExtension();
                $request['private_key_path']->move(public_path('files/payment_certificates'), $private_key_path_name);
                $information->private_key_path = '/files/payment_certificates/' . $private_key_path_name;
            }

            $item = $information;
            $item->user_id = Auth::user()->getAuthIdentifier();
            $item->merchant_certificate_id = $request->merchant_certificate_id;
            $item->merchant_name = $request->merchant_name;
//            $item->private_key_path	= $request->private_key_path;
            $item->private_key_pass = $request->private_key_pass;
//            $item->public_key_path	= $request->public_key_path;
            $item->merchant_id = $request->merchant_id;
            $item->save();

            return redirect("/" . app()->getLocale() . "/profile_pay_information");
        }

    }

    public function author_data_show()
    {
        $item = UserInformation::where('user_id', '=', Auth::user()->id)->first();
//        $certificates = json_decode($item->certificates);
        return view("app.pages.page.profile.author_data_profile", [
            'item' => $item,
//            'certificates' => $certificates,
        ]);
    }

    public function update_author_data_profile(Request $request)
    {
        $information = UserInformation::where('user_id', '=', Auth::user()->getAuthIdentifier())->first();

        if (empty($information)) {
            $item = new UserInformation;
            $item->user_id = Auth::user()->getAuthIdentifier();
            $item->name = $request->name;
            $item->surname = $request->surname;
            $item->specialization = $request->specialization;
            $item->about = $request->about;
            $item->phone_1 = $request->phone_1;
            $item->phone_2 = $request->phone_2;
            $item->site_url = $request->site_url;
            $item->vk_link = $request->vk_link;
            $item->fb_link = $request->fb_link;
            $item->instagram_link = $request->instagram_link;

            if (!empty($request->avatar)) {
                File::delete(public_path($item->avatar));

                $imageName = time() . '.' . $request['avatar']->getClientOriginalExtension();
                $request['avatar']->move(public_path('images/profile_images'), $imageName);
                $item->avatar = '/images/profile_images/' . $imageName;
            }

            if ($request->hasFile('certificates')) {
                $names = [];
                foreach ($request->file('certificates') as $key => $certificate) {
                    File::delete(public_path($certificate));
                    $filename = time() . $key .'.' . $certificate->getClientOriginalExtension();
                    $certificate->move(public_path('images/certificates'), $filename);
                    array_push($names, '/images/certificates/' . $filename);

                }
                $item->certificates = json_encode($names);
            }

            $item->save();
        } else {
            $item = $information;
            $item->user_id = Auth::user()->getAuthIdentifier();
            $item->name = $request->name;
            $item->surname = $request->surname;
            $item->specialization = $request->specialization;
            $item->about = $request->about;
            $item->phone_1 = $request->phone_1;
            $item->phone_2 = $request->phone_2;
            $item->site_url = $request->site_url;
            $item->vk_link = $request->vk_link;
            $item->fb_link = $request->fb_link;
            $item->instagram_link = $request->instagram_link;
            if (!empty($request->avatar)) {
                File::delete(public_path($item->avatar));

                $imageName = time() . '.' . $request['avatar']->getClientOriginalExtension();
                $request['avatar']->move(public_path('images/profile_images'), $imageName);
                $item->avatar = '/images/profile_images/' . $imageName;
            }
            if ($request->hasFile('certificates')) {
                $names = [];
                foreach ($request->file('certificates') as $key => $certificate) {
                    File::delete(public_path($certificate));
                    $filename = time() . $key .'.' . $certificate->getClientOriginalExtension();
                    $certificate->move(public_path('images/certificates'), $filename);
                    array_push($names, '/images/certificates/' . $filename);

                }
                $item->certificates = json_encode($names);
            }
            $item->save();
        }

        return redirect("/" . app()->getLocale() . "/profile-author-information");

    }


}
