<?php

namespace App\Filament\Resources\FileCategoryResource\Pages;

use App\Filament\Resources\FileCategoryResource;
use App\Helpers\ImportTranslationsHelper;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditFileCategory extends EditRecord
{
    protected static string $resource = FileCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $importData = $this->form->getRawState()['import_translations'] ?? null;

        if (!empty($importData)) {
            ImportTranslationsHelper::importTranslations($importData, $this->record);
        }

        unset($data['import_translations']);

        return $data;
    }
}

/*{
  "id": { "name": "Gambar", "excerpt": "Konversi gambar ke berbagai format: JPG, PNG, GIF, dan lainnya." },
  "cs": { "name": "Obrázek", "excerpt": "Převádějte obrázky do různých formátů: JPG, PNG, GIF a dalších." },
  "da": { "name": "Billede", "excerpt": "Konvertér billeder til forskellige formater: JPG, PNG, GIF og flere." }
},*/
