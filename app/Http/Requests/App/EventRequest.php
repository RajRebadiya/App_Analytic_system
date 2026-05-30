<?php

namespace App\Http\Requests\App;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_id' => ['required', 'string', 'max:128'],
            'event_name' => ['required', 'string', 'max:64', Rule::in(['app_open', 'screen_view', 'ad_click', 'notification_open', 'button_click'])],
            'event_data' => ['nullable', 'array'],
        ];
    }
}
