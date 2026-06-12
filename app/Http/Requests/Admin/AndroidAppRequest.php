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
        ];
    }
}
