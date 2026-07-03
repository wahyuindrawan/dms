<?php

namespace App\Filament\Resources\Legacy\KategoriDokumenResource\Pages;

use App\Filament\Resources\Legacy\KategoriDokumenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategoriDokumen extends EditRecord
{
    protected static string $resource = KategoriDokumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
