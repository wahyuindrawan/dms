<?php

namespace App\Filament\Resources\Legacy\AgendaResource\Pages;

use App\Filament\Resources\Legacy\AgendaResource;
use App\Models\User;
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
        $userSelection = $this->form->getState()['workers'] ?? [];

        $data['workers'] = in_array('ALL', $userSelection)
            ? User::whereNotNull('id')->pluck('id')->toArray()
            : array_map('intval', $userSelection);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->workers()->sync($this->data);
    }
}
