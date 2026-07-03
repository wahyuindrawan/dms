<?php

namespace App\Filament\Resources\Legacy\DisposisiResource\Pages;

use App\Filament\Resources\Legacy\DisposisiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDisposisis extends ListRecords
{
    protected static string $resource = DisposisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
