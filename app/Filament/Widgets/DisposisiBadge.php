<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\DisposisiResource;
use App\Models\Disposisi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Auth;

class DisposisiBadge extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        $workerId = Auth()->user()?->worker_id;

        $jumlahDisposisiMasuk = Disposisi::where('ke_worker_id', $workerId)
            ->whereNull('dibaca') // atau status_disposisi jika ada
            ->count();

        return [
            Card::make('Disposisi Masuk', $jumlahDisposisiMasuk)
                ->description('Klik untuk lihat detail')
                ->descriptionIcon('heroicon-o-inbox')
                ->color('warning')
                ->url(DisposisiResource::getUrl()),
        ];
    }
}
