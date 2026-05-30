<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AppVersionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'app_id' => ['required', 'exists:apps,id'],
            'latest_version' => ['required', 'string', 'max:32'],
            'min_supported_version' => ['required', 'string', 'max:32'],
            'force_update' => ['boolean'],
            'maintenance_mode' => ['boolean'],
            'apk_url' => ['nullable', 'url', 'max:2048'],
            'message' => ['nullable', 'string'],
            'change_log' => ['nullable', 'string'],
        ];
    }
}
