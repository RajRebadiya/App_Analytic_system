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
            'description' => ['required', 'string'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'redirect_screen' => ['nullable', 'string', 'max:120'],
            'redirect_data' => ['nullable', 'array'],
            'send_now' => ['sometimes', 'boolean'],
            'notification_type' => ['nullable', 'string', 'max:32'],
            'send_to' => ['nullable', Rule::in(['all', 'active'])],
            'scheduled_at' => ['nullable', 'date'],
        ];
    }
}
