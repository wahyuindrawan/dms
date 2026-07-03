<?php

namespace App\Filament\Resources\Legacy\DokumenLainResource\Pages;

use App\Filament\Resources\Legacy\DokumenLainResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDokumenLains extends ListRecords
{
    protected static string $resource = DokumenLainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
