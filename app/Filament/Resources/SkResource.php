<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Concerns\HasDocumentForm;
use App\Filament\Resources\Concerns\HasDocumentTable;
use App\Filament\Resources\SkResource\Pages;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SkResource extends Resource
{
    use HasDocumentForm, HasDocumentTable;

    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Manajemen Dokumen';
    protected static ?string $navigationLabel = 'Surat Keputusan (SK)';
    protected static ?string $pluralModelLabel = 'Surat Keputusan (SK)';
    protected static ?string $modelLabel = 'SK';
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'surat-keputusan';

    // ─────────────────────────────────────────────────────────
    // FORM
    // Fields SK: Cluster, Nomor, Tahun, Nama, Upload
    // + Section khusus SK: Detail (effective_date, decree_type, signer, dll.)
    // ─────────────────────────────────────────────────────────
    public static function form(Form $form): Form
    {
        return $form->schema([
            // Section info dasar (shared): Cluster, Nomor, Tahun, Nama, Status
            static::documentInfoSection(
                typeCode: 'SK',
                label: 'SK',
                withCluster: true,      // SK punya klaster
                withDirection: true,    // SK bisa internal atau keluar
            ),

            // Section Detail SK (spesifik SK — relasi 1-to-1 ke sk_details)
            Forms\Components\Section::make('Detail Surat Keputusan')
                ->description('Informasi tambahan yang spesifik untuk Surat Keputusan.')
                ->icon('heroicon-o-clipboard-document-check')
                ->relationship('skDetail')
                ->schema([
                    Forms\Components\DatePicker::make('effective_date')
                        ->label('Tanggal Mulai Berlaku')
                        ->required()
                        ->displayFormat('d/m/Y'),

                    Forms\Components\DatePicker::make('expiry_date')
                        ->label('Tanggal Berakhir')
                        ->displayFormat('d/m/Y')
                        ->helperText('Kosongkan jika tidak terbatas.'),

                    Forms\Components\Select::make('decree_type')
                        ->label('Jenis Keputusan')
                        ->options([
                            'pengangkatan'   => 'Pengangkatan',
                            'pemberhentian'  => 'Pemberhentian',
                            'penugasan'      => 'Penugasan',
                            'penetapan'      => 'Penetapan',
                            'kebijakan'      => 'Kebijakan',
                            'peraturan'      => 'Peraturan',
                            'lainnya'        => 'Lainnya',
                        ])
                        ->default('penetapan')
                        ->required(),

                    Forms\Components\Select::make('decree_scope')
                        ->label('Ruang Lingkup')
                        ->options([
                            'internal' => 'Internal',
                            'external' => 'Eksternal',
                        ])
                        ->default('internal')
                        ->required(),

                    Forms\Components\TextInput::make('signer_name')
                        ->label('Nama Penandatangan')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('signer_position')
                        ->label('Jabatan Penandatangan')
                        ->maxLength(100),

                    Forms\Components\Textarea::make('regarding')
                        ->label('Tentang / Isi Pokok')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('consideration')
                        ->label('Menimbang / Mengingat')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(2),

            // Section upload file (shared)
            static::fileUploadSection('SK'),
        ]);
    }

    // ─────────────────────────────────────────────────────────
    // TABLE
    // ─────────────────────────────────────────────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ...static::baseDocumentColumns('SK'),
                static::clusterColumn(),
                static::documentDateColumn(),

                \Filament\Tables\Columns\TextColumn::make('skDetail.decree_type')
                    ->label('Jenis Keputusan')
                    ->badge()
                    ->sortable()
                    ->toggleable(),

                \Filament\Tables\Columns\TextColumn::make('skDetail.effective_date')
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
    // QUERY — hanya tampilkan dokumen berjenis SK
    // ─────────────────────────────────────────────────────────
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('documentType', fn (Builder $q) => $q->where('code', 'SK'));
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSks::route('/'),
            'create' => Pages\CreateSk::route('/create'),
            'edit'   => Pages\EditSk::route('/{record}/edit'),
        ];
    }
}
