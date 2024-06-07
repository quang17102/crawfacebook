<?php

namespace App\Http\Requests\Users\Profile;

use App\Http\Requests\BaseRequest;

class UpdateDetailRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'pronouns' => 'nullable|string',
            'street' => 'nullable|string',
            'ward' => 'nullable|string',
            'province' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string',
            'school' => 'nullable|string',
        ];
    }
}
