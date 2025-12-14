<?php

namespace App\Filament\Widgets;

use App\Models\Agenda;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Filament\Resources\DisposisiResource;
use App\Filament\Resources\AgendaResource;
use App\Filament\Resources\SuratMasukResource;
use App\Filament\Resources\SuratKeluarResource;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Stat::make('Surat Masuk', SuratMasuk::count())
                ->description('Buat Surat Masuk')
                ->descriptionIcon('heroicon-m-plus')
                ->color('success')
                ->url(SuratMasukResource::getUrl('create')),

            Stat::make('Surat Keluar', SuratKeluar::count())
                ->description('Buat Surat Keluar')
                ->descriptionIcon('heroicon-m-plus')
                ->color('success')
                ->url(SuratKeluarResource::getUrl('create')),

            Stat::make('Disposisi', Disposisi::count())
                ->description('Buat Disposisi')
                ->descriptionIcon('heroicon-m-plus')
                ->color('success')
                ->url(DisposisiResource::getUrl('create')),

            Stat::make('Agenda', Agenda::count())
                ->description('Buat Agenda')
                ->descriptionIcon('heroicon-m-plus')
                ->color('success')
                ->url(AgendaResource::getUrl('create')),
        ];
    }
}
