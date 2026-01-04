<?php

namespace App\Filament\Resources\AgendaResource\Pages;

use App\Filament\Resources\AgendaResource;
use App\Models\Worker;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

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
        $workerId = $this->data['workers'] ?? [];
        Log::info('workerIds sebelum filter', $workerId);

        if (in_array('ALL', $workerId)) {
            $workerId = Worker::pluck('id')->toArray();
        } else {
            $workerId = array_filter($workerId, fn($id) => $id !== 'ALL');
            $workerId = array_map('intval', $workerId);
        }

        Log::info('workerId setelah filter', $workerId);

        $this->record->workers()->sync($workerId);
    }
}
