<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserBiodataRequest extends FormRequest
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
            'about' => 'required',
            'state' => 'required|exists:states,id',
            'lga' => 'required',
            'resume' => 'required|file|mimes:pdf',
            'photo' => 'required|file|image:png,jpg,jpeg|max:1023'
        ];
    }
}
