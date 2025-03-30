<?php

namespace App\Filament\Resources\FileFormatResource\Pages;

use App\Filament\Resources\FileFormatResource;
use App\Helpers\ImportTranslationsHelper;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFileFormat extends EditRecord
{
    protected static string $resource = FileFormatResource::class;

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
