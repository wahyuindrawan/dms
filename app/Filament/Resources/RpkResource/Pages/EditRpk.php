<?php

namespace App\Filament\Resources\RpkResource\Pages;

use App\Filament\Resources\RpkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRpk extends EditRecord
{
    protected static string $resource = RpkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
