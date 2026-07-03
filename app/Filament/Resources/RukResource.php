<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Concerns\HasDocumentForm;
use App\Filament\Resources\Concerns\HasDocumentTable;
use App\Filament\Resources\RukResource\Pages;
use App\Models\Document;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RukResource extends Resource
{
    use HasDocumentForm, HasDocumentTable;

    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'Manajemen Dokumen';
    protected static ?string $navigationLabel = 'RUK';
    protected static ?string $pluralModelLabel = 'Rencana Usulan Kegiatan (RUK)';
    protected static ?string $modelLabel = 'RUK';
    protected static ?int $navigationSort = 3;
    protected static ?string $slug = 'ruk';

    // ─────────────────────────────────────────────────────────
    // FORM
    // Fields RUK: Tahun, Nama, Upload, Keterangan
    // ─────────────────────────────────────────────────────────
    public static function form(Form $form): Form
    {
        return $form->schema([
            // Section info dasar (shared): Tahun, Nama, Status, Keterangan
            // withCluster: false  — RUK tidak punya klaster
            // withDirection: false — RUK selalu internal
            static::documentInfoSection(
                typeCode: 'RUK',
                label: 'RUK',
                withCluster: false,
                withDirection: false,
            ),

            // Section upload file (shared)
            static::fileUploadSection('RUK'),
        ]);
    }

    // ─────────────────────────────────────────────────────────
    // TABLE
    // ─────────────────────────────────────────────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ...static::baseDocumentColumns('RUK'),
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
    // QUERY — hanya tampilkan dokumen berjenis RUK
    // ─────────────────────────────────────────────────────────
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('documentType', fn (Builder $q) => $q->where('code', 'RUK'));
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRuks::route('/'),
            'create' => Pages\CreateRuk::route('/create'),
            'edit'   => Pages\EditRuk::route('/{record}/edit'),
        ];
    }
}
