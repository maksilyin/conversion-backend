<?php
namespace App\Repositories;

use App\Models\File;
use Illuminate\Support\Facades\Log;

class FileRepository
{
    public function updateFile($id, $data)
    {
        $file = File::find($id);

        if (!empty($data['result'])) {
            $result = $file->result;
            if (!is_array($result)) {
                $result = [];
            }
            $result[] = $data['result'];
            $data['result'] = $result;
        }

        $file->update($data);

        return $file;
    }

    public function deleteFile($id): void
    {
        File::destroy($id);
    }

    public function updateFileTask($taskId, $fileId, $data)
    {
        $file = File::where(['task_id' => $taskId, 'id' => $fileId])->first();
        $file->update($data);

        return $file;
    }
}
