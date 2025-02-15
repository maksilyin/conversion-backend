<?php

namespace App\Filament\Resources\TextBlockResource\Pages;

use App\Filament\Resources\TextBlockResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTextBlock extends EditRecord
{
    protected static string $resource = TextBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
