<?php

namespace App\Filament\Resources\Legacy\WorkerResource\Pages;

use App\Filament\Resources\Legacy\WorkerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkers extends ListRecords
{
    protected static string $resource = WorkerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
