<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
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
            'tel_or_email' => 'required|email:dns,rfc',
            'password' => 'required|string',
        ];
        if (is_numeric($tel_or_email)) {
            $validate['tel_or_email'] = 'required|string|regex:/^0\d{9,10}$/';
        }

        return $validate;
    }
}
