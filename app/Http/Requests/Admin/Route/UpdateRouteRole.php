<?php

namespace App\Http\Requests\Admin\Route;

use App\Rules\Admin\Route\RouteSortExist;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRouteRole extends FormRequest
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
            'sort' => ['required', 'integer', new RouteSortExist($this->request->get('type'), $this->request->get('sort'), $this->request->get('id')), 'min:1'],
        ];
    }
}

