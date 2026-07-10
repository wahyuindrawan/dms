<?php

namespace App\Filament\Resources\Concerns;

use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

/**
 * Trait HasDocumentTable
 *
 * Menyediakan shared table columns, filters, dan actions
 * untuk semua resource dokumen (SK, SOP, RUK, RPK).
 */
trait HasDocumentTable
{
    /**
     * Kolom dasar yang ada di semua tabel dokumen.
     * @param string $label  Label jenis dokumen, mis: 'SK', 'RUK'
     */
    protected static function baseDocumentColumns(string $label): array
    {
        return [
            Tables\Columns\TextColumn::make('doc_number')
                ->label("Nomor {$label}")
                ->searchable()
                ->sortable()
                ->copyable()
                ->weight('bold'),

            Tables\Columns\TextColumn::make('title')
                ->label("Nama / Judul")
                ->searchable()
                ->sortable()
                ->wrap(),
        ];
    }

    /**
     * Kolom tanggal dokumen.
     */
    protected static function documentDateColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('document_date')
            ->label('Tahun')
            ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('Y') : '-')
            ->sortable();
    }

    /**
     * Kolom klaster/kategori dokumen.
     */
    protected static function clusterColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('category.name')
            ->label('Klaster')
            ->searchable()
            ->sortable()
            ->badge()
            ->placeholder('—');
    }

    /**
     * Kolom status dengan badge warna.
     */
    protected static function statusColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('status')
            ->label('Status')
            ->badge()
            ->color(fn (string $state): string => match ($state) {
                'draft'    => 'warning',
                'active'   => 'success',
                'archived' => 'gray',
                'void'     => 'danger',
                default    => 'gray',
            })
            ->sortable();
    }

    /**
     * Kolom jumlah file terlampir.
     */
    protected static function filesCountColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('files_count')
            ->counts('files')
            ->label('File')
            ->badge()
            ->color('info')
            ->sortable();
    }

    /**
     * Filter dasar: Status, Tahun, dan Klaster (opsional).
     * @param bool $withCluster  Tambahkan filter klaster
     */
    protected static function baseDocumentFilters(bool $withCluster = false): array
    {
        $filters = [];

        // Filter Tahun
        $filters[] = Filter::make('year')
            ->label('Tahun')
            ->form([
                \Filament\Forms\Components\Select::make('year')
                    ->label('Pilih Tahun')
                    ->options(
                        collect(range(date('Y'), 2020))
                            ->mapWithKeys(fn ($y) => [$y => $y])
                            ->toArray()
                    )
                    ->placeholder('Semua Tahun'),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query->when(
                    $data['year'],
                    fn (Builder $q, $year) => $q->whereYear('document_date', $year)
                );
            })
            ->indicateUsing(function (array $data): ?string {
                return $data['year'] ? "Tahun: {$data['year']}" : null;
            });

        // Filter Status
        $filters[] = Tables\Filters\SelectFilter::make('status')
            ->label('Status')
            ->options([
                'draft'    => 'Draft',
                'active'   => 'Aktif',
                'archived' => 'Diarsipkan',
                'void'     => 'Dibatalkan',
            ])
            ->multiple();

        // Filter Klaster — opsional
        if ($withCluster) {
            $filters[] = Tables\Filters\SelectFilter::make('document_category_id')
                ->label('Klaster')
                ->relationship('category', 'name')
                ->searchable()
                ->preload()
                ->multiple();
        }

        return $filters;
    }

    /**
     * Actions standar tabel dokumen: Edit, View File, Delete.
     * @param string $typeCode  Untuk link download file
     */
    protected static function baseDocumentActions(): array
    {
        return [
            Tables\Actions\ActionGroup::make([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('preview_file')
                    ->label('Lihat File')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(fn ($record) => $record->files()->exists())
                    ->action(function ($record) {
                        $file = $record->files()->where('is_primary', true)->first()
                            ?? $record->files()->first();

                        if ($file) {
                            return redirect()->route('document-file.preview', ['file' => $file->id]); //inline route preview
                        }
                    })
                    ->openUrlInNewTab(), // perintah untuk membuka di tab baru

                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])->tooltip('Aksi'),
        ];
    }

    /**
     * Bulk actions standar dokumen.
     */
    protected static function baseDocumentBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ];
    }
}
