<?php

namespace App\Filament\Resources\DokumenLainResource\Pages;

use App\Filament\Resources\DokumenLainResource;
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
