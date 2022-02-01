<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name'     => 'required|max:45',
            'lastName' => 'required|max:45',
            'surName'  => 'required|max:45',
            'birthday' => 'required|date',
            'rfc'      => 'required',
            'image'    => 'image|max:5000',
            'email'    => 'email',
        ];
    }
}
