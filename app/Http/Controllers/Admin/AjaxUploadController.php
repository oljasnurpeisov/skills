<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Buffet;
use App\Models\Log;

use Illuminate\Http\Request;
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
    public function ajax_upload_image(Request $request)
    {
        $this->validate($request, [
            'file' => 'image|max:15000'
        ]);

        $file = $request->file;
        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/profile_images/'), $imageName);

//        Buffet::log('upload_image', '', '', $imageName);

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

//            Buffet::log('upload_file', '', '', $imageName);

            return Response::json(array('filelink' => '/files/' . $imageName));
        }
    }

    public $imageMaxSize = '1024';
    public $fileMaxSize = '25000';

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

//        Buffet::log('upload_img', '', '', $imageName);

        return Response::json(array('location' => config('APP_URL') . '/images/profile_images/' . $imageName));
    }

    public function ajaxUploadFile(Request $request)
    {
        if ($request->header('Origin') !== env('APP_URL')) {
            return Response::json(array('success' => false, 'message' => 'invalid url'));
        }

        $rules = array(
            'file' => "file|required|max:$this->fileMaxSize|mimes:docx,doc,xls,xlsx,pdf,txt,ppt,pptx,xml"
        );

        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Response::json(array('success' => false, 'message' => $validation->errors()->all()));
        }

        $file = Input::file('file');
        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('files'), $imageName);


//        Buffet::log('upload_file', '', '', $imageName);

        return Response::json(array(
            'success' => true,
            'location' => config('APP_URL') . '/files/' . $imageName
        ));
    }
}
