<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Concerns\HasDocumentForm;
use App\Filament\Resources\Concerns\HasDocumentTable;
use App\Filament\Resources\RpkResource\Pages;
use App\Models\Document;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RpkResource extends Resource
{
    use HasDocumentForm, HasDocumentTable;

    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';
    protected static ?string $navigationGroup = 'Manajemen Dokumen';
    protected static ?string $navigationLabel = 'RPK';
    protected static ?string $pluralModelLabel = 'Rencana Pelaksanaan Kegiatan (RPK)';
    protected static ?string $modelLabel = 'RPK';
    protected static ?int $navigationSort = 4;
    protected static ?string $slug = 'rpk';

    // ─────────────────────────────────────────────────────────
    // FORM
    // Fields RPK: Tahun, Nama, Upload
    // ─────────────────────────────────────────────────────────
    public static function form(Form $form): Form
    {
        return $form->schema([
            // Section info dasar (shared): Tahun, Nama, Status
            // withCluster: false  — RPK tidak punya klaster
            // withDirection: false — RPK selalu internal
            static::documentInfoSection(
                typeCode: 'RPK',
                label: 'RPK',
                withCluster: false,
                withDirection: false,
            ),

            // Section upload file (shared)
            static::fileUploadSection('RPK'),
        ]);
    }

    // ─────────────────────────────────────────────────────────
    // TABLE
    // ─────────────────────────────────────────────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ...static::baseDocumentColumns('RPK'),
                static::documentDateColumn(),
                static::statusColumn(),
                static::filesCountColumn(),
            ])
            ->filters(
                static::baseDocumentFilters(withCluster: false)
            )
            ->actions(static::baseDocumentActions())
            ->bulkActions(static::baseDocumentBulkActions())
            ->defaultSort('document_date', 'desc');
    }

    // ─────────────────────────────────────────────────────────
    // QUERY — hanya tampilkan dokumen berjenis RPK
    // ─────────────────────────────────────────────────────────
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('documentType', fn (Builder $q) => $q->where('code', 'RPK'));
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRpks::route('/'),
            'create' => Pages\CreateRpk::route('/create'),
            'edit'   => Pages\EditRpk::route('/{record}/edit'),
        ];
    }
}
