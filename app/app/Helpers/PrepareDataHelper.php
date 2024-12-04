<?php

namespace App\Helpers;

class PrepareDataHelper
{
    private static array $requireFieldsServiceType = [
        'convert' => [
            'save' => 'prepareDataFileToSave',
            'require' => [
                'service' => ['files:array'],
                'params' => ['convert:array']
            ],
        ]
    ];

    public static function prepareDataToSave($data, $task, $taskType, $payload = [])
    {
        if (!self::validateFields($taskType, $data, 'service')) {
            abort(400);
        }

        $dataFields = self::getServiceTypeData($taskType, $data);

        return self::executeMethod('save', $dataFields, $task, $taskType, $payload);
    }

    public static function prepareDataFileToSave($data, $task, $taskType, $payload = []): array
    {
        $preparedData = $payload;

        $isCheck = isset($preparedData['files']);

        foreach ($data['files'] as $file) {
            if (self::validateFields($taskType, $file['params'], 'params')
                && $file['status'] === FileUploadHelper::FILE_STATUS_UPLOADED
                && FileUploadHelper::isFileExists($task, $file['hash'], $file['filename'])
            ) {
                $path = FileUploadHelper::getFilePathOriginal($task, $file['hash'], $file['filename']);
                $mimeType = FileUploadHelper::getFileInfo($path);
                $index = false;

                if ($isCheck) {
                    $index = array_keys(array_column($preparedData['files'], 'hash'), $file['hash']);
                }

                $params = [
                    'hash' => $file['hash'],
                    'status' => $file['status'],
                    'filename' => $file['filename'],
                    ...$mimeType,
                    'params' => [...self::getServiceTypeData($taskType, $file['params'], 'params')],
                ];

                if (empty($index)) {
                    $preparedData['files'][] = $params;
                }
                else {
                    $preparedData['files'][$index[0]] = $params;
                }
            }
        }

        return $preparedData;
    }

    public static function validateFields($type, $array, $key='service'): bool
    {
        if (!isset(self::$requireFieldsServiceType[$type])) {
            return false;
        }

        $fields = self::$requireFieldsServiceType[$type]['require'][$key];

        foreach ($fields as $field) {
            $field = explode(':', $field);
            $fieldType = '';

            if (count($field) > 1) {
                $fieldType = $field[1];
            }

            $field = $field[0];

            if (!array_key_exists($field, $array)) {
                return false;
            }
            else if (!empty($fieldType) && !self::checkType($array[$field], $fieldType)) {
                return false;
            }
        }

        return true;
    }

    public static function getServiceTypeData($type, $array, $key='service'): array
    {
        $fields = self::$requireFieldsServiceType[$type]['require'][$key];

        $data = [];

        foreach ($fields as $field) {
            $field = explode(':', $field)[0];
            $data[$field] = $array[$field];
        }

        return $data;
    }

    private static function executeMethod($methodKey, $data, $task, $type, $payload = [])
    {
        $method = self::$requireFieldsServiceType[$type][$methodKey];
        return self::$method($data, $task, $type, $payload);
    }

    public static function checkType($value, string $type): bool
    {
        return match ($type) {
            'array' => is_array($value),
            'string' => is_string($value),
            'integer', 'int' => is_int($value),
            'float', 'double' => is_float($value),
            'boolean', 'bool' => is_bool($value),
            'object' => is_object($value),
            'null' => is_null($value),
            default => false,
        };
    }
}
