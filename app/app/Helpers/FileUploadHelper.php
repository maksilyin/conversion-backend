<?php

namespace App\Helpers;

use finfo;

class FileUploadHelper
{
    const FILE_STATUS_CREATED = 0;
    const FILE_STATUS_LOADING = 1;
    const FILE_STATUS_UPLOADED = 2;
    const FILE_STATUS_ERROR = 3;
    const FILE_STATUS_DELETE = 4;
    const FILE_STATUS_COMPLETED = 5;

    public static function getTmpDir($uuid): string
    {
        return storage_path('app/uploads/temp/' . $uuid . '/');
    }

    public static function getDir($uuid): string
    {
        return storage_path('app/uploads/complete/' . $uuid . '/');
    }

    public static function getFileName($hash, $filename): string
    {
        return $hash . '_' .$filename;
    }

    public static function getFilePathForService($uuid, $hash, $filename): string
    {
        return 'storage/complete/' . $uuid . '/' . self::getFileName($hash, $filename);
    }

    public static function getFilePathOriginal($uuid, $hash, $filename): string
    {
        return self::getDir($uuid) . self::getFileName($hash, $filename);
    }

    public static function getFileMimeType($filePath)
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($filePath);
        $extensionFromPath = pathinfo($filePath, PATHINFO_EXTENSION);

        return [
            'mimetype' => $mimeType,
            'extension' => $extensionFromPath,
        ];
    }
}
