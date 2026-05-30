<?php

namespace App\Http\Requests\App;

use Illuminate\Foundation\Http\FormRequest;

class SaveTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_id' => ['required', 'string', 'max:128'],
            'fcm_token' => ['required', 'string', 'max:4096'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
