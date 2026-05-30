<?php

namespace App\Http\Requests\App;

use Illuminate\Foundation\Http\FormRequest;

class HeartbeatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_id' => ['required', 'string', 'max:128'],
            'app_version' => ['nullable', 'string', 'max:32'],
        ];
    }
}
