<?php

namespace App\Services\Admin;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    public function image(?UploadedFile $file, string $directory): ?string
    {
        if (! $file) {
            return null;
        }

        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();

        return Storage::disk('public')->putFileAs($directory, $file, $filename);
    }
}
