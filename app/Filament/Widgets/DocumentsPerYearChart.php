<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DocumentsPerYearChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Dokumen per Tahun';
    protected static ?string $description = 'Tren jumlah dokumen yang dibuat setiap tahun.';
    protected int | string | array $columnSpan = 1;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Document::query()
            ->select(
                DB::raw('YEAR(document_date) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('document_date')
            ->whereYear('document_date', '>=', now()->subYears(5)->year)
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'datasets' => [
                [
                    'label'           => 'Total Dokumen',
                    'data'            => $data->pluck('total')->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor'     => 'rgb(59, 130, 246)',
                    'borderWidth'     => 2,
                    'fill'            => true,
                    'tension'         => 0.4,
                ],
            ],
            'labels' => $data->pluck('year')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks'       => ['stepSize' => 1],
                ],
            ],
        ];
    }
}
