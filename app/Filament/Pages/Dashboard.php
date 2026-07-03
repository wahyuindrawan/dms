<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DocumentsPerTypeChart;
use App\Filament\Widgets\DocumentsPerYearChart;
use App\Filament\Widgets\RecentDocumentsWidget;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\WelcomeWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string $routePath = '/';
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard';

    public function getWidgets(): array
    {
        return [
            WelcomeWidget::class,
            StatsOverview::class,
            DocumentsPerYearChart::class,
            DocumentsPerTypeChart::class,
            RecentDocumentsWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'default' => 1,
            'sm'      => 2,
            'lg'      => 2,
        ];
    }
}
