<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use App\Helpers\ImportTranslationsHelper;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

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
