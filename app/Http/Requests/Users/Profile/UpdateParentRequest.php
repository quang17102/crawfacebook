<?php

namespace App\Http\Requests\Users\Profile;

use App\Http\Requests\BaseRequest;

class UpdateParentRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'mother_first_name' => 'required|string',
            'mother_last_name' => 'required|string',
            'mother_email' => 'required|string',
            'mother_phone' => 'required|string|regex:/^0\d{9}$/',
            'mother_employer' => 'required|string',
            'father_first_name' => 'nullable|string',
            'father_last_name' => 'nullable|string',
            'father_email' => 'nullable|string',
            'father_phone' => 'nullable|string|regex:/^0\d{9}$/',
            'father_employer' => 'nullable|string',
        ];
    }
}
