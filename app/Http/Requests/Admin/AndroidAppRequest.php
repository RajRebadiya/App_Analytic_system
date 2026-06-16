<?php

namespace App\Http\Requests\Admin;

use App\Models\AndroidApp;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AndroidAppRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var AndroidApp|null $app */
        $app = $this->route('app');

        return [
            'name' => ['required', 'string', 'max:255'],
            'package_name' => ['required', 'string', 'max:255', Rule::unique('apps', 'package_name')->ignore($app?->id)],
            'current_version' => ['required', 'string', 'max:32'],
            'onesignal_app_id' => ['nullable', 'string', 'max:255'],
            'onesignal_api_key' => ['nullable', 'string', 'max:4096'],
        ];
    }
}
