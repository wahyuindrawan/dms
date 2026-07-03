<?php

namespace App\Filament\Resources\Legacy\KategoriDokumenResource\Pages;

use App\Filament\Resources\Legacy\KategoriDokumenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriDokumens extends ListRecords
{
    protected static string $resource = KategoriDokumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
