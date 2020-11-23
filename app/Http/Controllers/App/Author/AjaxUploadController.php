<?php

namespace App\Http\Controllers\App\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Symfony\Component\Console\Input\Input;

/**
 * --------------------------------------------------------------------------
 *  AjaxUploadController
 * --------------------------------------------------------------------------
 *
 *  Системный контроллер отвечающий за загрузку медиаматериалов
 *
 */
class AjaxUploadController extends Controller
{
    /**
     * Загрузка изображения на сайт.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public $imageMaxSize = '1024';
    public $fileMaxSize = '25000';
    public $videoMaxSize = '50000';

    public function ajax_upload_image(Request $request)
    {
        $this->validate($request, [
            "file" => "image|required|max:$this->imageMaxSize|mimes:png,jpg,jpeg"
        ]);

        $file = $request->file;
        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/profile/images'), $imageName);

        return Response::json(array('location' => config('APP_URL') . '/users/user_' . Auth::user()->getAuthIdentifier() . '/profile/images/' . $imageName));
    }

    public function ajaxUploadCourseImage(Request $request)
    {
        $this->validate($request, [
            "file" => "image|required|max:$this->imageMaxSize|mimes:png,jpg,jpeg"
        ]);

        $file = $request->file;
        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/courses/images'), $imageName);

        return Response::json(array('location' => config('APP_URL') . '/users/user_' . Auth::user()->getAuthIdentifier() . '/courses/images/' . $imageName));
    }

    /**
     * Загрузка файла на сайт.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxUploadCertificates(Request $request)
    {

        $data = [];
        foreach($request->file('files') as $file)
        {
            $name = time().uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/profile/files'), $name);
            array_push($data, '/users/user_' . Auth::user()->getAuthIdentifier() . '/profile/files/'.$name);
        }

        return Response::json(array('filenames' => $data));
    }

    public function ajaxUploadCourseVideos(Request $request)
    {
        $data = [];
        foreach($request->file('files') as $file)
        {
            $name = time().uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/courses/videos'), $name);
            array_push($data, '/users/user_' . Auth::user()->getAuthIdentifier() . '/courses/videos/'.$name);
        }

        return Response::json(array('filenames' => $data));
    }

    public function ajaxUploadCourseAudios(Request $request)
    {
        $data = [];
        foreach($request->file('files') as $file)
        {
            $name = time().uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/courses/audios'), $name);
            array_push($data, '/users/user_' . Auth::user()->getAuthIdentifier() . '/courses/audios/'.$name);
        }

        return Response::json(array('filenames' => $data));
    }

}
