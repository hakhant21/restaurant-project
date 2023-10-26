<?php

namespace App\Services;

class ImageService
{
    public static function singleUpload($file, $path, $file_system)
    {
        $uniqid = uniqid();
        $path = $file->storeAs($path, $uniqid.'.'. $file->extension(), $file_system);
        return 'storage/'.$path;
    }
}
