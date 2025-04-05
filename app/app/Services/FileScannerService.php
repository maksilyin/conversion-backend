<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class FileScannerService
{
    public function scan(string $filePath): bool
    {
        return $this->scanWithClamAV($filePath);
    }

    protected function scanWithClamAV(string $filePath): bool
    {
        $process = new Process(['clamdscan', '--fdpass', $filePath]);
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error("ClamAV scan failed", [
                'error' => $process->getErrorOutput(),
                'exit_code' => $process->getExitCode(),
            ]);
            return false;
        }

        $output = $process->getOutput();
        Log::info("ClamAV scan completed", ['output' => $output]);

        return !str_contains($output, 'FOUND');
    }
}
