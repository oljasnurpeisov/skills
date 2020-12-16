<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Serializers\CustomSerializer;
use App\Http\Controllers\Controller;
use App\Models\User;
use Dingo\Api\Routing\Helpers;
use Dingo\Api\Transformer\Adapter\Fractal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use League\Fractal\Manager;

abstract class BaseController extends Controller
{
    use Helpers;
    protected $language;
    protected $user;

    protected $per_page = 12;

    public function __construct()
    {
        app("Dingo\Api\Transformer\Factory")->setAdapter(function ($app) {
            $fractal = new Manager();
            $fractal->setSerializer(new CustomSerializer());

            return new Fractal($fractal);
        });
    }

    protected function setLocale(Request $request)
    {
        $this->language = $request->header("language");

        $rules = [
            "language" => ["required"]
        ];
        $payload = [
            "language" => $this->language
        ];
        $validator = app("validator")->make($payload, $rules);
        if ($validator->fails()) {
            return $this->response->errorBadRequest($validator->errors()->first());
        }

        $lang = ["ru", "en", "kk"];
        if (in_array($this->language, $lang)) {
            $locale = $this->language;
        } else {
            $locale = "en";
        }
        App::setLocale($locale);
        return true;
    }

    protected $user_id;

    public function validUser(Request $request)
    {
        $this->user_id = intval($request->header("user", null));
        $user = User::whereId($this->user_id)->first();
        return (!empty($user)) ? true : false;
    }

    public function validateHash(array $payload = array(), $showHash = false)
    {
        $hash = $payload['hash'];
        unset($payload['hash']);

        $salt = env('API_SALT', null);

        ksort($payload);
        $str = implode('', $payload) . $salt;
        $md5 = md5($str);

        $data = [
            'input hash ' . $hash,
            'valid hash ' . $md5,
            'generated string ' . $str
        ];

        $successResult = $showHash ? $data : true;

        return $md5 !== $hash ? $successResult : false;
    }


    public function validLanguage(Request $request)
    {
        $language = $request->header("language", null);
        $array = ["ru", "en", "kk", "qq"];
        if (in_array($language, $array)) {
            App::setLocale($language);
        }
        return !in_array($language, $array);
    }
}
