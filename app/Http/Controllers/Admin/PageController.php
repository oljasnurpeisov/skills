<?php

namespace App\Http\Controllers\Admin;

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

class PageController extends Controller
{
    public function main()
    {
        return view('admin.v2.pages.static_pages.main', [

        ]);
    }
}
