<?php

namespace App\Filament\Resources\Legacy\SuratMasukResource\Pages;

use App\Filament\Resources\Legacy\SuratMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuratMasuk extends EditRecord
{
    protected static string $resource = SuratMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
