<?php

namespace App\Filament\Resources\FileCategoryResource\Pages;

use App\Filament\Resources\FileCategoryResource;
use Filament\Actions;
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
}
