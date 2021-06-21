<?php

namespace App\Http\Requests\Admin\Route;

use Illuminate\Foundation\Http\FormRequest;

class StoreRouteRole extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role_id'   => ['required', 'integer'],
            'sort'      => ['required', 'integer'],
        ];
    }
}
