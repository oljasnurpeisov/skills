<?php

namespace App\Api\V1\Controllers\App;

use App\Api\V1\Classes\Message;
use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Transformers\MessageTransformer;
use App\Models\Course;
use App\Models\Dialog;
use App\Models\Professions;
use App\Models\Role;
use App\Models\Skill;
use App\Models\StudentInformation;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class CourseController extends BaseController
{
    public function getAuthors(Request $request)
    {
        $hash = $request->header("hash");
        $lang = $request->header("lang", 'ru');
        app()->setLocale($lang);

        // Валидация
        $rules = [
            'hash' => 'required',
        ];
        $payload = [
            'hash' => $hash
        ];

        $validator = Validator::make($payload, $rules);

        if ($validator->fails()) {
            $message = new Message($validator->errors()->first(), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        if ($hash = $this->validateHash($payload, env('APP_DEBUG'))) {
            if (is_bool($hash)) {
                $validator->errors()->add('hash', __('api/errors.invalid_hash'));
            } else {
                $validator->errors()->add('hash', __('api/errors.invalid_hash') . ' ' . implode(' | ', $hash));
            }
        }

        if (count($validator->errors()) > 0) {
            $errors = $validator->errors()->all();
            $message = new Message(implode(' ', $errors), 400, null);
            return $this->response->item($message, new MessageTransformer())->statusCode(400);
        }

        $items = User::whereHas('roles', function ($q) {
            $q->where('slug', '=', 'author');
        })->paginate($this->per_page);

        $next_page_number = null;
        if ($items->nextPageUrl()) {
            $t = parse_url($items->nextPageUrl());
            $t = isset($t["query"]) ? $t["query"] : "";
            $t = explode("=", $t);
            $next_page_number = in_array("page", $t) ? $t[array_search("page", $t) + 1] : null;
        }

        $data = [
            "items" => [],
            "next" => $next_page_number,
        ];

        foreach ($items as $item) {
            $data["items"][] = [
                'id' => $item->id,
                'email' => $item->email,
                'name' => $item->author_info->name . $item->author_info->surname,
                'avatar' => $item->author_info->avatar
            ];
        }

        $message = new Message(__('api/messages.authors.title'), 200, $data);
        return $this->response->item($message, new MessageTransformer());
    }

    public function courseRate(Request $request){

    }
}
