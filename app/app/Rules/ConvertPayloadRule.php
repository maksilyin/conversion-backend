<?php

namespace App\Rules;

use App\Models\FileFormat;
use App\Models\Task;
use App\Services\TaskManager;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Cache;

class ConvertPayloadRule implements ValidationRule
{
    private $extensions = [];
    private Task $task;
    public function __construct(TaskManager $taskManager)
    {
        $this->task = $taskManager->getTask();

        $this->extensions = Cache::remember('file_formats_extensions', 60 * 60, function () {
            $formats = FileFormat::where('active', 1)
                ->select('id', 'category_id', 'extension')
                ->with([
                    'convertible:extension',
                    'convertibleCategory.formats:category_id,extension'
                ])
                ->get();

            return $formats->mapWithKeys(function ($fileFormat) {
                $supportedExtensions = collect();

                if ($fileFormat->convertible->isNotEmpty()) {
                    $supportedExtensions = $supportedExtensions->merge(
                        $fileFormat->convertible->pluck('extension')
                    );
                }

                if ($fileFormat->convertibleCategory->isNotEmpty()) {
                    $supportedExtensions = $supportedExtensions->merge(
                        $fileFormat->convertibleCategory
                            ->flatMap(function ($category) {
                                return $category->formats->pluck('extension');
                            })
                    );
                }

                $filteredExtensions = $supportedExtensions
                    ->unique()
                    ->reject(fn($extension) => $extension === $fileFormat->extension)
                    ->values();

                return [
                    $fileFormat->extension => $filteredExtensions->toArray()
                ];
            })->toArray();
        });
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $valueFiles = [];

        if (!is_array($value) || !isset($value['files'])) {
            $fail('The :attribute must contain a "files" array.');
            return;
        }

        foreach ($value['files'] as $file) {
            if (empty($file['hash']) || empty($file['filename'])) {
                $fail('Each file in :attribute must have a "hash" and a "filename".');
                return;
            }

            if (empty($file['params']) || !is_array($file['params'])) {
                $fail('Each file in :attribute must have a "params" array.');
                return;
            }

            $this->validateParams($file['params'], $fail);

            $valueFiles[$file['hash']] = $file;
        }

        $payload = $this->task->payload;

        foreach ($payload['files'] as $file) {
            if (!isset($valueFiles[$file['hash']])) {
                $fail("File with hash {$file['hash']} was not found in the provided data.");
                return;
            }
        }
    }

    private function validateParams(array $params, Closure $fail): void
    {
        if (!isset($params['convert']) || !is_array($params['convert'])) {
            $fail('The "params" array must contain a "convert" key with an array.');
            return;
        }

        foreach ($params['convert'] as $extension) {
            if (!array_key_exists($extension, $this->extensions)) {
                $fail("$extension is not allowed");
                return;
            }
        }
    }
}
