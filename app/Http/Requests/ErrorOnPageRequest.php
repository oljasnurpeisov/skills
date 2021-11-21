<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ErrorOnPageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'url'     => 'required|string',
            'phone'   => 'nullable',
            'text'    => 'required|string',
            'comment' => 'nullable'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
