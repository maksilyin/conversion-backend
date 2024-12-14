<?php

namespace App\Helpers;

use finfo;

class FileUploadHelper
{
    const FILE_STATUS_CREATED = 0;
    const FILE_STATUS_LOADING = 1;
    const FILE_STATUS_ERROR = 2;
    const FILE_STATUS_DELETE = 3;
    const FILE_STATUS_UPLOADED = 4;
    const FILE_STATUS_PROCESSING = 5;
    const FILE_STATUS_COMPLETED = 6;

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

    public static function getFileInfo($filePath): array
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($filePath);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        return [
            'mimetype' => $mimeType,
            'extension' => $extension,
            'size' => filesize($filePath)
        ];
    }

    public static function isFileExists($uuid, $hash, $filename): bool
    {
        $path = self::getFilePathOriginal($uuid, $hash, $filename);
        return file_exists($path);
    }

    public static function getFile($uuid, $filename): false|string
    {
        $dir = self::getDir($uuid);

        if (file_exists($dir . $filename)) {
            return $dir . $filename;
        }

        return false;
    }

    public static function getFileFromService($uuid, $output): false|string
    {
        $filePath = str_replace('storage/complete/'.$uuid.'/', '', $output);
        $dir = self::getDir($uuid);

        if (file_exists($dir . $filePath)) {
            return $filePath;
        }

        return $filePath;
    }

    public static function getOriginalName($filename) {
        if (preg_match('/.+?_(.+)/', $filename, $matches)) {
            $filename = $matches[1];
        }

        return $filename;
    }

    public static function getFileArray($uuid, $filename): false|array
    {
        if ($filePath = self::getFile($uuid, $filename)) {
            $fileInfo = self::getFileInfo($filePath);
            $originalName = self::getOriginalName($filename);

            return [
                'src' => $filePath,
                'filename' => $filename,
                'originalName' => $originalName,
                ...$fileInfo
            ];
        }

        return false;
    }
}
