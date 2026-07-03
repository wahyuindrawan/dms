<?php

namespace App\Filament\Resources\RpkResource\Pages;

use App\Filament\Resources\RpkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRpks extends ListRecords
{
    protected static string $resource = RpkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
