<?php

namespace App\Filament\Resources\RukResource\Pages;

use App\Filament\Resources\RukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRuk extends EditRecord
{
    protected static string $resource = RukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
