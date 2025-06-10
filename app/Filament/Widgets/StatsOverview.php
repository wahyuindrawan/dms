<?php

namespace App\Filament\Widgets;

use App\Models\Agenda;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Surat Masuk', SuratMasuk::count()),
            Card::make('Surat Keluar', SuratKeluar::count()),
            Card::make('Disposisi', Disposisi::count()),
            Card::make('Agenda', Agenda::count()),
        ];
    }
}
