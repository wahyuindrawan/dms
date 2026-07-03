<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use App\Models\DocumentType;
use Filament\Widgets\ChartWidget;

class DocumentsPerTypeChart extends ChartWidget
{
    protected static ?int $sort = 4;
    protected static ?string $heading = 'Distribusi Dokumen per Jenis';
    protected static ?string $description = 'Proporsi setiap jenis dokumen dari total keseluruhan.';
    protected int | string | array $columnSpan = 1;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $types = DocumentType::withCount('documents')->get();

        $labels     = $types->pluck('name')->toArray();
        $counts     = $types->pluck('documents_count')->toArray();
        $colors     = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#06B6D4'];

        return [
            'datasets' => [
                [
                    'data'            => $counts,
                    'backgroundColor' => array_slice($colors, 0, count($labels)),
                    'borderWidth'     => 2,
                    'borderColor'     => '#ffffff',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display'  => true,
                    'position' => 'bottom',
                ],
            ],
            'cutout' => '65%',
        ];
    }
}
