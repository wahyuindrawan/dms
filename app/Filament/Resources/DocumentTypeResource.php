<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentTypeResource\Pages;
use App\Models\DocumentType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentTypeResource extends Resource
{
    protected static ?string $model = DocumentType::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Jenis Dokumen';
    protected static ?string $pluralModelLabel = 'Jenis Dokumen';
    protected static ?string $modelLabel = 'Jenis Dokumen';
    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'jenis-dokumen';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas Jenis Dokumen')
                ->description('Kode dan nama digunakan sebagai referensi di seluruh sistem.')
                ->icon('heroicon-o-document-text')
                ->schema([
                    Forms\Components\TextInput::make('code')
                        ->label('Kode Jenis')
                        ->required()
                        ->maxLength(10)
                        ->unique(DocumentType::class, 'code', ignoreRecord: true)
                        ->placeholder('Contoh: SK, SOP, RUK')
                        ->alphaDash()
                        ->helperText('Hanya huruf, angka, dan tanda hubung (-).')
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('name')
                        ->label('Nama Jenis Dokumen')
                        ->required()
                        ->maxLength(100)
                        ->columnSpan(1),

                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(2)
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Konfigurasi Penomoran Otomatis')
                ->description('Sistem akan membuat nomor dokumen secara otomatis berdasarkan format ini.')
                ->icon('heroicon-o-hashtag')
                ->schema([
                    Forms\Components\TextInput::make('prefix')
                        ->label('Prefix Nomor')
                        ->required()
                        ->maxLength(10)
                        ->placeholder('Contoh: SK-, SOP-')
                        ->helperText('Ditambahkan di awal nomor dokumen.'),

                    Forms\Components\TextInput::make('seq_length')
                        ->label('Panjang Urutan (digits)')
                        ->numeric()
                        ->default(4)
                        ->minValue(1)
                        ->maxValue(8)
                        ->required()
                        ->helperText('Contoh: 4 → 0001, 3 → 001'),

                    Forms\Components\TextInput::make('numbering_format')
                        ->label('Format Penomoran')
                        ->default('{prefix}{seq}/{mm}/{yyyy}')
                        ->required()
                        ->maxLength(100)
                        ->columnSpanFull()
                        ->helperText('Token: {prefix}, {seq}, {dd}, {mm}, {yyyy}. Contoh: {prefix}{seq}/{mm}/{yyyy}'),
                ])->columns(2),

            Forms\Components\Section::make('Tabel Detail Tambahan')
                ->description('Aktifkan jika jenis dokumen ini memiliki tabel detail khusus (mis: sk_details, sop_details).')
                ->icon('heroicon-o-table-cells')
                ->schema([
                    Forms\Components\Toggle::make('has_detail')
                        ->label('Memiliki Tabel Detail')
                        ->live()
                        ->default(false),

                    Forms\Components\TextInput::make('detail_table')
                        ->label('Nama Tabel Detail')
                        ->maxLength(100)
                        ->placeholder('Contoh: sk_details')
                        ->visible(fn (Forms\Get $get) => $get('has_detail')),
                ])->columns(2),

            Forms\Components\Section::make('Pengaturan Tampilan')
                ->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Urutan Tampil')
                        ->numeric()
                        ->default(0),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Jenis')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('prefix')
                    ->label('Prefix')
                    ->sortable(),

                Tables\Columns\TextColumn::make('numbering_format')
                    ->label('Format Nomor')
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\IconColumn::make('has_detail')
                    ->label('Ada Detail')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->trueLabel('Hanya Aktif')
                    ->falseLabel('Hanya Non-Aktif'),

                Tables\Filters\TernaryFilter::make('has_detail')
                    ->label('Memiliki Detail'),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScope(SoftDeletingScope::class);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDocumentTypes::route('/'),
            'create' => Pages\CreateDocumentType::route('/create'),
            'edit'   => Pages\EditDocumentType::route('/{record}/edit'),
        ];
    }
}
