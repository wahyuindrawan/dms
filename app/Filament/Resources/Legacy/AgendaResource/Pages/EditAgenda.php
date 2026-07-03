<?php

namespace App\Filament\Resources\Legacy\AgendaResource\Pages;

use App\Filament\Resources\Legacy\AgendaResource;
use App\Models\Legacy\Worker;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgenda extends EditRecord
{
    protected static string $resource = AgendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $workerSelection = $this->form->getState()['worker_selection'] ?? [];

        $data['workers'] = in_array('ALL', $workerSelection)
            ? Worker::pluck('id')->toArray()
            : array_map('intval', $workerSelection);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->workers()->sync($this->data);
    }
}
