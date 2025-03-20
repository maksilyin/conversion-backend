<?php

namespace App\Services;

use finfo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class FileService
{
    const BASE_DIR = 'uploads';
    private $disk;
    private $uuid;
    private $mainDir;
    private $resultDir;
    private $serviceDir;
    private $tmpDir;
    public function __construct($uuid = null)
    {
        $this->disk = Storage::disk(config('filesystems.default'));
        $this->setUuid($uuid);
    }

    public function getTmpDir(): string
    {
        return $this->tmpDir;
    }

    public function getDir(): string
    {
        return $this->mainDir;
    }

    public function uploadChunk($chunk, $hash, $chunkIndex): void
    {
        $tmpPath = $this->tmpDir.$hash;

        if (!$this->disk->exists($tmpPath)) {
            $this->disk->makeDirectory($tmpPath);
        }
        $this->disk->putFileAs($tmpPath, $chunk, $chunkIndex.'_'.$hash);
    }

    public function mergeChunks($hash, $filename): string
    {
        $this->disk->makeDirectory($this->mainDir);

        $tmpPath = $this->tmpDir.$hash;
        $filePath = $this->mainDir . $hash . '_' . $filename;
        $chunks = collect($this->disk->files($tmpPath))->sort();

        $stream = fopen($this->disk->path($filePath), 'ab');

        if (!$stream) {
            throw new \Exception("Failed to open file for writing: $filePath");
        }

        try {
            foreach ($chunks as $chunk) {
                $chunkStream = fopen($this->disk->path($chunk), 'rb');
                if ($chunkStream) {
                    stream_copy_to_stream($chunkStream, $stream);
                    fclose($chunkStream);
                    $this->disk->delete($chunk);
                }
            }
        } finally {
            fclose($stream);
        }

        return $filePath;
    }

    public function setUuid($uuid): void
    {
        $this->uuid = $uuid;
        $this->mainDir = self::BASE_DIR.'/complete/' . $this->uuid . '/';
        $this->resultDir = $this->mainDir.'result/';
        $this->serviceDir = 'storage/complete/' . $this->uuid . '/';
        $this->tmpDir = self::BASE_DIR.'/temp/' . $this->uuid . '/';
    }

    public function deleteTaskFolder(): void
    {
        $this->deleteFolder($this->mainDir);
        $this->deleteFolder($this->tmpDir);
    }

    public function deleteFileByHash($hash): void
    {
        if ($file = $this->getFileByHash($hash)) {
            $this->disk->delete($file);
        }

        if ($fileResult = $this->getFileResultByHash($hash)) {
            $this->disk->delete($fileResult);
        }
    }

    protected function deleteFolder($folder): void
    {
        $files = $this->disk->allFiles($folder);
        $this->disk->delete($files);

        $directories = $this->disk->allDirectories($folder);

        foreach ($directories as $dir) {
            $this->disk->deleteDirectory($dir);
        }

        $this->disk->deleteDirectory($folder);
    }

    public function getFileInfo($filePath): array
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($this->disk->path($filePath));
        $extension = pathinfo($this->disk->path($filePath), PATHINFO_EXTENSION);

        return [
            'mimetype' => $mimeType,
            'extension' => $extension,
            'size' => $this->disk->size($filePath),
        ];
    }

    public function getFileByHash($hash, $fullPath = false): ?string
    {
        $filePath = collect($this->disk->files($this->mainDir))
            ->filter(fn($file) => str_starts_with(basename($file), $hash))
            ->first();

        if (!$filePath) {
            return null;
        }

        if ($fullPath) {
            return $this->disk->path($filePath);
        }
        return $filePath;
    }

    public function getFileResultByHash($hash, $fullPath = false): ?string
    {
        $filePath = collect($this->disk->files($this->resultDir))
            ->filter(fn($file) => str_starts_with(basename($file), $hash))
            ->first();

        if (!$filePath) {
            return null;
        }

        Log::info($this->disk->path($filePath));

        if ($fullPath) {
            return $this->disk->path($filePath);
        }
        return $filePath;
    }

    public function getFilePathForService($hash): ?string
    {
        $filePath = collect($this->disk->files($this->mainDir))
            ->filter(fn($file) => str_starts_with(basename($file), $hash))
            ->first();

        return $filePath ? $this->serviceDir . pathinfo($filePath, PATHINFO_BASENAME) : null;
    }
}
