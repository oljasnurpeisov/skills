<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
    public $imageMaxSize = '1024';
    public $fileMaxSize = '25000';

    /**
     * Загрузка изображения на сайт.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ajax_upload_image(Request $request)
    {
        $this->validate($request, [
            'file' => 'image|max:15000'
        ]);

        $file = $request->file;
        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/profile_images/'), $imageName);

        return Response::json(array('filelink' => '/images/profile_images/' . $imageName));
    }

    /**
     * Загрузка файла на сайт.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax_upload_file()
    {
        $rules = array(
            'file' => 'file|required|max:15000|mimes:docx,doc,xls,xlsx,pdf,txt,ppt,pptx',
        );

        $validation = Validator::make(Input::all(), $rules);
        $file = Input::file('file');
        if ($validation->fails()) {
            return FALSE;
        } else {
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('files'), $imageName);

            return Response::json(array('filelink' => '/files/' . $imageName));
        }
    }

    public function ajaxUploadPic(Request $request)
    {
        if ($request->header('Origin') !== env('APP_URL')) {
            return Response::json(array('success' => false));
        }

        $this->validate($request, [
            "file" => "image|required|max:$this->imageMaxSize|mimes:png,jpg,jpeg"
        ]);

        $file = $request->file;
        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/profile_images/'), $imageName);

        return Response::json(array('location' => config('APP_URL') . '/images/profile_images/' . $imageName));
    }

    public function ajaxUploadPicContent(Request $request)
    {
        if ($request->header('Origin') !== env('APP_URL')) {
            return Response::json(array('success' => false));
        }

        $this->validate($request, [
            "file" => "image|required|max:$this->imageMaxSize|mimes:png,jpg,jpeg,svg"
        ]);

        $file = $request->file;
        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/content_images/'), $imageName);

        return Response::json(array('location' => config('APP_URL') . '/images/content_images/' . $imageName));
    }

    public function ajaxUploadFile(Request $request)
    {
        if ($request->header('Origin') !== env('APP_URL')) {
            return Response::json(array('success' => false, 'message' => 'invalid url'));
        }

//        $rules = array(
//            'file' => "files|required|max:$this->fileMaxSize|mimes:docx,doc,xls,xlsx,pdf,txt,ppt,pptx,xml"
//        );

//        $validation = Validator::make($request->all(), $rules);
//        if ($validation->fails()) {
        if (!isset($request->file()['files'][0])) {
            return Response::json(array('success' => false));
//            return Response::json(array('success' => false, 'message' => $validation->errors()->all()));
        }

        $file = $request->file()['files'][0];
        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('files'), $imageName);

        return Response::json(array(
            'success' => true,
            'filenames' => config('APP_URL') . '/files/' . $imageName
        ));
    }

    public function ajaxUploadPicTest(Request $request)
    {
        $file = $request->file;
        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/test/'), $imageName);

        return Response::json(array('location' => config('APP_URL') . '/images/test/' . $imageName));
    }

    public function ajaxUploadFilesTest(Request $request)
    {
        $data = [];
        foreach ($request->file('files') as $file) {
            $name = time() . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/test/files/'), $name);
            array_push($data, '/images/test/files/' . $name);
        }

        return Response::json(array('filenames' => $data));
    }


}
