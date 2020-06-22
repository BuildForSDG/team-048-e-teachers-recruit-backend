<?php

namespace App\Http\Requests;

use App\Services\ResponseService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetPasswordRequest extends FormRequest
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
            'token' => 'required|exists:email_verifications,token',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ];
    }
}
