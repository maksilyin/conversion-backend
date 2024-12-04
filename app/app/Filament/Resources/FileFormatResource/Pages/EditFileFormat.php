<?php

namespace App\Filament\Resources\FileFormatResource\Pages;

use App\Filament\Resources\FileFormatResource;
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
}
