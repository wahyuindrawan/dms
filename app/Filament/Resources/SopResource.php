<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Concerns\HasDocumentForm;
use App\Filament\Resources\Concerns\HasDocumentTable;
use App\Filament\Resources\SopResource\Pages;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SopResource extends Resource
{
    use HasDocumentForm, HasDocumentTable;

    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Manajemen Dokumen';
    protected static ?string $navigationLabel = 'SOP';
    protected static ?string $pluralModelLabel = 'SOP';
    protected static ?string $modelLabel = 'SOP';
    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'sop';

    // ─────────────────────────────────────────────────────────
    // FORM
    // Fields SOP: Cluster, Program, Nomor, Tahun, Nama, Upload
    // + Section khusus SOP: Detail (versi, unit penanggung jawab, dll.)
    // ─────────────────────────────────────────────────────────
    public static function form(Form $form): Form
    {
        return $form->schema([
            // Section info dasar (shared): Cluster, Nomor, Tahun, Nama, Status
            static::documentInfoSection(
                typeCode: 'SOP',
                label: 'SOP',
                withCluster: true,      // SOP punya klaster
                withDirection: false,   // SOP selalu internal
            ),

            // Section Detail SOP (spesifik SOP — relasi 1-to-1 ke sop_details)
            Forms\Components\Section::make('Detail SOP')
                ->description('Informasi tambahan spesifik Standar Operasional Prosedur.')
                ->icon('heroicon-o-queue-list')
                ->relationship('sopDetail')
                ->schema([
                    Forms\Components\TextInput::make('version')
                        ->label('Versi SOP')
                        ->default('1.0')
                        ->required()
                        ->maxLength(20)
                        ->helperText('Contoh: 1.0, 2.1'),

                    Forms\Components\TextInput::make('revision_number')
                        ->label('Nomor Revisi')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->required(),

                    Forms\Components\DatePicker::make('effective_date')
                        ->label('Tanggal Mulai Berlaku')
                        ->required()
                        ->displayFormat('d/m/Y'),

                    Forms\Components\DatePicker::make('review_date')
                        ->label('Tanggal Review / Evaluasi')
                        ->displayFormat('d/m/Y')
                        ->helperText('Tanggal evaluasi berkala SOP ini.'),

                    // "Program" field — diwakili oleh unit penanggung jawab
                    Forms\Components\Select::make('process_owner_unit_id')
                        ->label('Program / Unit Penanggung Jawab')
                        ->relationship('processOwner', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->helperText('Unit/program yang bertanggung jawab atas SOP ini.'),

                    Forms\Components\Textarea::make('scope')
                        ->label('Ruang Lingkup')
                        ->rows(2)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('purpose')
                        ->label('Tujuan SOP')
                        ->rows(2)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('reference')
                        ->label('Referensi / Dasar Hukum')
                        ->rows(2)
                        ->columnSpanFull(),
                ])->columns(2),

            // Section upload file (shared)
            static::fileUploadSection('SOP'),
        ]);
    }

    // ─────────────────────────────────────────────────────────
    // TABLE
    // ─────────────────────────────────────────────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ...static::baseDocumentColumns('SOP'),
                static::clusterColumn(),
                static::documentDateColumn(),

                \Filament\Tables\Columns\TextColumn::make('sopDetail.version')
                    ->label('Versi')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->toggleable(),

                \Filament\Tables\Columns\TextColumn::make('sopDetail.processOwner.name')
                    ->label('Program / Unit')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('—'),

                \Filament\Tables\Columns\TextColumn::make('sopDetail.effective_date')
                    ->label('Tgl Berlaku')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),

                static::statusColumn(),
                static::filesCountColumn(),
            ])
            ->filters(
                static::baseDocumentFilters(withCluster: true)
            )
            ->actions(static::baseDocumentActions())
            ->bulkActions(static::baseDocumentBulkActions())
            ->defaultSort('document_date', 'desc');
    }

    // ─────────────────────────────────────────────────────────
    // QUERY — hanya tampilkan dokumen berjenis SOP
    // ─────────────────────────────────────────────────────────
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('documentType', fn (Builder $q) => $q->where('code', 'SOP'));
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSops::route('/'),
            'create' => Pages\CreateSop::route('/create'),
            'edit'   => Pages\EditSop::route('/{record}/edit'),
        ];
    }
}
