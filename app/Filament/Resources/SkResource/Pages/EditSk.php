<?php

namespace App\Filament\Resources\SkResource\Pages;

use App\Filament\Resources\SkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSk extends EditRecord
{
    protected static string $resource = SkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
