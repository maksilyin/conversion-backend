<?php

namespace App\Helpers;

use Filament\Notifications\Notification;

class ImportTranslationsHelper
{
    public static function importTranslations($jsonData, $record)
    {
        $translations = json_decode($jsonData, true);

        if (is_array($translations)) {
            foreach ($translations as $locale => $values) {
                $record->translations()->updateOrCreate(
                    [
                        'locale' => $locale,
                    ],
                    $values
                );
            }

            Notification::make()
                ->title('Переводы успешно импортированы!')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Ошибка: Неверный формат JSON')
                ->danger()
                ->send();
        }
    }
}
