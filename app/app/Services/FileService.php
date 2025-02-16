<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
class FileService
{
    const BASE_DIR = 'uploads';

    private $disk;
    private $uuid;
    private $mainDir;
    private $tmpDir;
    public function __construct($uuid = null)
    {
        $this->disk = Storage::disk(config('filesystems.default'));
        $this->setUuid($uuid);
    }

    public function getTmpDir(): string
    {
        return self::BASE_DIR.'/temp/' . $this->uuid . '/';
    }

    public function getDir(): string
    {
        return self::BASE_DIR.'/complete/' . $this->uuid . '/';
    }

    public function setUuid($uuid): void
    {
        $this->uuid = $uuid;
        $this->mainDir = self::BASE_DIR.'/complete/' . $this->uuid . '/';
        $this->tmpDir = self::BASE_DIR.'/temp/' . $this->uuid . '/';
    }

    public function deleteTaskFolder(): void
    {
        $this->deleteFolder($this->mainDir);
        $this->deleteFolder($this->tmpDir);
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
}
