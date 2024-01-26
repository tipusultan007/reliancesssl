<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageUploadHelper
{
    public static function upload($file, $folder, $oldFilePath = null)
    {
        if ($oldFilePath) {
            Storage::delete($oldFilePath);
        }

        $filePath = $file->store($folder);

        return $filePath;
    }
}
