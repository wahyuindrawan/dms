<?php

namespace App\Filament\Resources\Legacy\DisposisiResource\Pages;

use App\Filament\Resources\Legacy\DisposisiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisposisi extends EditRecord
{
    protected static string $resource = DisposisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['dari_worker_id']);
        return $data;
    }
}
