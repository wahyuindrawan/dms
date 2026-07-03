<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RecentDocumentsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Document::query()
                    ->with(['documentType', 'category', 'creator'])
                    ->latest()
                    ->limit(8)
            )
            ->columns([
                Tables\Columns\TextColumn::make('doc_number')
                    ->label('Nomor')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul / Nama')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('documentType.code')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'SK'  => 'primary',
                        'SOP' => 'success',
                        'RUK' => 'warning',
                        'RPK' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Klaster')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft'    => 'warning',
                        'active'   => 'success',
                        'archived' => 'gray',
                        'void'     => 'danger',
                        default    => 'gray',
                    }),

                Tables\Columns\TextColumn::make('document_date')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->placeholder('—'),
            ])
            ->heading('Dokumen Terbaru')
            ->paginated(false);
    }
}
