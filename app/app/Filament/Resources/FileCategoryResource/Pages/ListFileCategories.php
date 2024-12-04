<?php

namespace App\Filament\Resources\FileCategoryResource\Pages;

use App\Filament\Resources\FileCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFileCategories extends ListRecords
{
    protected static string $resource = FileCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
