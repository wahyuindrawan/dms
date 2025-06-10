<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AgendaHariIni;
use App\Filament\Widgets\DisposisiBadge;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Page;
use App\Models\Agenda;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Disposisi;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\View\View;

class Dashboard extends BaseDashboard
{
    protected static string $routePath = '/';
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'Dashboard';
    protected static ?string $navigationLabel = 'Dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            AgendaHariIni::class,
            DisposisiBadge::class,
            StatsOverview::class,
        ];
    }

    public function render(): View
    {
        $agendaToday = Agenda::whereDate('tanggal', now())->get();

        return view('filament.pages.dashboard', [
            'agendaToday' => $agendaToday,
        ]);
    }
}
