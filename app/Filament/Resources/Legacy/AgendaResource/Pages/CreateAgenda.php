<?php

namespace App\Filament\Resources\Legacy\AgendaResource\Pages;

use App\Filament\Resources\Legacy\AgendaResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateAgenda extends CreateRecord
{
    protected static string $resource = AgendaResource::class;

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
        $userId = $this->data['workers'] ?? [];
        Log::info('userIds sebelum filter', $userId);

        if (in_array('ALL', $userId)) {
            $userId = User::whereNotNull('id')->pluck('id')->toArray();
        } else {
            $userId = array_filter($userId, fn($id) => $id !== 'ALL');
            $userId = array_map('intval', $userId);
        }

        Log::info('userId setelah filter', $userId);

        $this->record->workers()->sync($userId);
    }
}
