<?php

namespace App\Filament\Resources\AgendaResource\Pages;

use App\Filament\Resources\AgendaResource;
use App\Models\Worker;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAgenda extends CreateRecord
{
    protected static string $resource = AgendaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $workerSelection = $this->form->getState()['workers'] ?? [];

        $data['workers'] = in_array('ALL', $workerSelection)
            ? Worker::pluck('id')->toArray()
            : array_map('intval', $workerSelection);

        return $data;
    }

    protected function afterCreate(): void
    {
        $workerIds = $this->data['workers'] ?? [];
        Log::info('workerIds sebelum filter', $workerIds);

        if (in_array('ALL', $workerIds)) {
            $workerIds = Worker::pluck('id')->toArray();
        } else {
            $workerIds = array_filter($workerIds, fn($id) => $id !== 'ALL');
            $workerIds = array_map('intval', $workerIds);
        }

        Log::info('workerIds setelah filter', $workerIds);

        $this->record->workers()->sync($workerIds);
    }
}
