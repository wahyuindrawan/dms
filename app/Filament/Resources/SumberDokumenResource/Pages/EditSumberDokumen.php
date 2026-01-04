<?php

namespace App\Filament\Resources\SumberDokumenResource\Pages;

use App\Filament\Resources\SumberDokumenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSumberDokumen extends EditRecord
{
    protected static string $resource = SumberDokumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
