<?php

namespace App\Filament\Resources\TextBlockResource\Pages;

use App\Filament\Resources\TextBlockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTextBlocks extends ListRecords
{
    protected static string $resource = TextBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
