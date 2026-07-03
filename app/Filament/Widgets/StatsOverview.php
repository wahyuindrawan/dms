<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use App\Filament\Resources\SkResource;
use App\Filament\Resources\SopResource;
use App\Filament\Resources\RukResource;
use App\Filament\Resources\RpkResource;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $total  = Document::count();
        $skCount  = Document::whereHas('documentType', fn ($q) => $q->where('code', 'SK'))->count();
        $sopCount = Document::whereHas('documentType', fn ($q) => $q->where('code', 'SOP'))->count();
        $rukCount = Document::whereHas('documentType', fn ($q) => $q->where('code', 'RUK'))->count();
        $rpkCount = Document::whereHas('documentType', fn ($q) => $q->where('code', 'RPK'))->count();

        // Trend bulan ini vs bulan lalu
        $thisMonth = Document::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $lastMonth = Document::whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->count();
        $trend     = $thisMonth >= $lastMonth ? 'up' : 'down';

        return [
            Stat::make('Total Dokumen', $total)
                ->description("Bulan ini: {$thisMonth} dokumen")
                ->descriptionIcon($trend === 'up' ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($trend === 'up' ? 'success' : 'danger')
                ->icon('heroicon-o-document-duplicate'),

            Stat::make('SK — Surat Keputusan', $skCount)
                ->description('Total SK')
                ->color('primary')
                ->icon('heroicon-o-document-text')
                ->url(SkResource::getUrl('index')),

            Stat::make('SOP', $sopCount)
                ->description('Total SOP')
                ->color('success')
                ->icon('heroicon-o-book-open')
                ->url(SopResource::getUrl('index')),

            Stat::make('RUK', $rukCount)
                ->description('Rencana Usulan Kegiatan')
                ->color('warning')
                ->icon('heroicon-o-presentation-chart-line')
                ->url(RukResource::getUrl('index')),

            Stat::make('RPK', $rpkCount)
                ->description('Rencana Pelaksanaan Kegiatan')
                ->color('info')
                ->icon('heroicon-o-presentation-chart-bar')
                ->url(RpkResource::getUrl('index')),
        ];
    }
}
