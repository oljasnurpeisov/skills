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

    // Загрузка изображения компании
    public function ajaxUploadCompanyImage(Request $request)
    {
        $this->validate($request, [
            "file" => "image|required|max:$this->imageMaxSize|mimes:png,jpg,jpeg"
        ]);

        $file = $request->file;
        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/company'), $imageName);

        return Response::json(array('location' => config('APP_URL') . '/images/company/' . $imageName));
    }

    // Загрузка изображения курса
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

    // Загрузка изображения урока
    public function ajaxUploadLessonImage(Request $request)
    {
        $this->validate($request, [
            "file" => "image|required|max:$this->imageMaxSize|mimes:png,jpg,jpeg"
        ]);

        $file = $request->file;
        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/images'), $imageName);

        return Response::json(array('location' => config('APP_URL') . '/users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/images/' . $imageName));
    }

    // Загрузка изображений теста
    public function ajaxUploadTestImages(Request $request)
    {
        $data = [];
        foreach ($request->file('files') as $file) {
            $name = time() . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/images/tests'), $name);
            array_push($data, '/users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/images/tests/' . $name);
        }

        return Response::json(array('filenames' => $data));
    }

    /**
     * Загрузка файла на сайт.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // Загрузка сертификатов автора
    public function ajaxUploadCertificates(Request $request)
    {
        $data = [];
        foreach ($request->file('files') as $file) {
            $name = uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/profile/files'), $name);
            array_push($data, '/users/user_' . Auth::user()->getAuthIdentifier() . '/profile/files/' . $name);
        }

        return Response::json(array('filenames' => $data));
    }

    // Загрузка видео курса
    public function ajaxUploadCourseVideos(Request $request)
    {
        $data = [];
        foreach ($request->file('files') as $file) {
            $name = uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/courses/videos'), $name);
            array_push($data, '/users/user_' . Auth::user()->getAuthIdentifier() . '/courses/videos/' . $name);
        }

        return Response::json(array('filenames' => $data));
    }

    // Загрузка аудио курса
    public function ajaxUploadCourseAudios(Request $request)
    {
        $data = [];
        foreach ($request->file('files') as $file) {
            $name = uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/courses/audios'), $name);
            array_push($data, '/users/user_' . Auth::user()->getAuthIdentifier() . '/courses/audios/' . $name);
        }

        return Response::json(array('filenames' => $data));
    }

    // Загрузка видео урока
    public function ajaxUploadLessonVideos(Request $request)
    {
        $data = [];
        foreach ($request->file('files') as $file) {
            $name = uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/videos'), $name);
            array_push($data, '/users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/videos/' . $name);
        }

        return Response::json(array('filenames' => $data));
    }

    // Загрузка аудио урока
    public function ajaxUploadLessonAudios(Request $request)
    {
        $data = [];
        foreach ($request->file('files') as $file) {
            $name = uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/audios'), $name);
            array_push($data, '/users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/audios/' . $name);
        }

        return Response::json(array('filenames' => $data));
    }

    // Загрузка других материалов урока
    public function ajaxUploadLessonAnotherFiles(Request $request)
    {
        $data = [];
        foreach ($request->file('files') as $file) {
            $name = uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/files'), $name);
            array_push($data, '/users/user_' . Auth::user()->getAuthIdentifier() . '/lessons/files/' . $name);
        }

        return Response::json(array('filenames' => $data));
    }

    public function ajaxUploadLessonAnotherFile(Request $request)
    {
        $file = $request->file;
        $name = uniqid() . '_' . $file->getClientOriginalName();
        $file->move(public_path('files'), $name);

        return Response::json(array('location' => config('APP_URL') . '/files/' . $name));
    }


}
