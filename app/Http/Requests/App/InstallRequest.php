<?php

namespace App\Http\Requests\App;

use Illuminate\Foundation\Http\FormRequest;

class InstallRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_id' => ['required', 'string', 'max:128'],
            'device_name' => ['nullable', 'string', 'max:255'],
            'device_brand' => ['nullable', 'string', 'max:255'],
            'android_version' => ['nullable', 'string', 'max:32'],
            'country_code' => ['nullable', 'string', 'size:2'],
            'app_version' => ['required', 'string', 'max:32'],
        ];
    }
}
