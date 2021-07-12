<?php

namespace App\Http\Requests\AVR;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAVR extends FormRequest
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
            'invoice'     => 'sometimes|required',
            'avr_number'  => 'sometimes|required'
        ];
    }
}

