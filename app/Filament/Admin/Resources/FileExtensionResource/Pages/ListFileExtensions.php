<?php

namespace App\Filament\Admin\Resources\FileExtensionResource\Pages;

use App\Filament\Admin\Resources\FileExtensionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFileExtensions extends ListRecords
{
    protected static string $resource = FileExtensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
