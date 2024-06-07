<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $tel_or_email = $this->tel_or_email;
        $validate = [
            'tel_or_email' => !is_numeric($tel_or_email) ? 'required|email:dns,rfc'
            : 'required|string|regex:/^0\d{9,10}$/',
            'password' => 'required|string',
            'repassword' => 'required|string|same:password',
        ];

        return $validate;
    }
}
