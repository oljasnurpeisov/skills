<?php

namespace App\Http\Requests\Author;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequisites extends FormRequest
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
            'type_of_ownership'     => ['required'],
            'company_name'          => ['required'],
            'position_ru'           => ['required'],
            'position_kk'           => ['required'],
            'legal_address_ru'      => ['required'],
            'legal_address_kk'      => ['required'],
            'fio_director'          => ['required'],
            'base_id'               => ['required'],
            'iin'                   => ['required'],
            'iik_kz'                => ['required'],
            'kbe'                   => ['required'],
            'bik'                   => ['required'],
            'bank_id'               => ['required'],
        ];
    }
}

