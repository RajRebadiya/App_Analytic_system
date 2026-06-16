<?php

namespace App\Services\Admin;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
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

    public function publicImage(?UploadedFile $file, string $directory): ?string
    {
        if (! $file) {
            return null;
        }

        $directory = trim(str_replace('\\', '/', $directory), '/');
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        File::ensureDirectoryExists(public_path($directory));
        $file->move(public_path($directory), $filename);

        return $directory.'/'.$filename;
    }
}
