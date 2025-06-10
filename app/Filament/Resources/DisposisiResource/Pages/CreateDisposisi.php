<?php

namespace App\Filament\Resources\DisposisiResource\Pages;

use App\Filament\Resources\DisposisiResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateDisposisi extends CreateRecord
{
    protected static string $resource = DisposisiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['dari_worker_id'] = auth()->user()?->worker_id;

        return $data;
    }

    // protected function afterCreate(): void
    // {
    //     if ($this->record->keWorker && $this->record->dariWorker) {
    //         Notification::make()
    //             ->title('Disposisi Dikirim')
    //             ->body('Anda menerima disposisi baru dari ' . $this->record->dariWorker->nama)
    //             ->sendToDatabase($this->record->keWorker?->user);
    //     }
    // }
}
