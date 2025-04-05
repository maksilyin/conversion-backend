<?php

namespace App\Exceptions;

use App\Helpers\FileUploadHelper;
use App\Repositories\FileRepository;
use App\Services\TaskManager;
use Exception;
use Illuminate\Support\Facades\Log;

class FileUploadException extends Exception
{
    protected int $status;
    protected ?string $hash;

    public function __construct(string $message = "File upload error", int $status = 422, ?string $hash = null)
    {
        parent::__construct($message);
        $this->status = $status;
        $this->hash = $hash;
    }

    public function render($request)
    {
        if ($this->hash) {
            $fileRepository = new FileRepository();

            $fileRepository->updateAndDeleteFile($this->hash, [
                'status' => FileUploadHelper::FILE_STATUS_ERROR,
                'result' => [
                    'error' => $this->getMessage(),
                    'status' => false,
                ]
            ]);
        }
        return response()->json([
            'message' => $this->getMessage(),
            'error_code' => 'file_upload_error',
        ], $this->status);
    }
}
