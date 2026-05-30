<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'app_id' => ['required', 'exists:apps,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'redirect_screen' => ['nullable', 'string', 'max:120'],
            'redirect_data' => ['nullable', 'array'],
            'send_now' => ['sometimes', 'boolean'],
            'notification_type' => ['required', 'string', 'max:32'],
            'send_to' => ['required', Rule::in(['all', 'active'])],
        ];
    }
}
