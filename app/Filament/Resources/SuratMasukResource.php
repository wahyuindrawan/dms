<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratMasukResource\Pages;
use App\Models\SuratMasuk;
use App\Helpers\AccessHelper;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SuratMasukResource extends Resource
{
    protected static ?string $model = SuratMasuk::class;
    protected static ?string $pluralModelLabel = 'Surat Masuk';
    protected static ?string $navigationGroup = 'Manajemen Surat';
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationLabel = 'Surat Masuk';
    protected static ?string $slug = 'surat-masuk';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('judul')->required()->label('Judul Surat'),
            TextInput::make('nomor_surat')->label('Nomor Surat')->nullable(),
            TextInput::make('perihal')->label('Perihal')->nullable(),

            DatePicker::make('tanggal_surat')->required()->label('Tanggal Surat'),
            DatePicker::make('tanggal_masuk')->required()->label('Tanggal Masuk'),

            Select::make('kategori_id')
                ->relationship('kategori', 'nama')
                ->label('Kategori')->required(),

            Select::make('sumber_id')
                ->relationship('sumber', 'nama')
                ->label('Asal Dokumen')->required(),

            Select::make('ditujukan_id')
                ->relationship('ditujukan', 'nama')
                ->label('Ditujukan')
                ->searchable(),

            Textarea::make('deskripsi')->label('Keterangan'),

            FileUpload::make('file_path')
                ->label('Upload File')
                ->directory('surat-masuk')
                ->acceptedFileTypes(['application/pdf', 'application/msword', 'image/*'])
                ->maxSize(2048)
                ->preserveFilenames()
                ->storeFileNamesIn('file_original'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul & Nomor Surat')
                    ->html()
                    ->formatStateUsing(fn ($state, $record) =>
                        "<div class='leading-tight'>
                            <div class='font-semibold text-gray-800'>{$record->judul}</div>
                            <div class='text-sm text-gray-500'>{$record->nomor_surat}</div>
                        </div>"
                    )
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sumber.nama')->label('Asal Dokumen')->searchable()->sortable(),
                TextColumn::make('kategori.nama')->label('Kategori')->searchable()->sortable(),
                TextColumn::make('tanggal_masuk')->label('Tanggal Masuk')->date()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Action::make('Lihat Detail')
                    ->label('')
                    ->tooltip('Lihat detail')
                    ->icon('heroicon-o-eye')
                    ->modalContent(fn($record) => view('filament.modals.detail-surat-masuk', ['record' => $record]))
                    ->modalWidth('3xl'),

                // ViewAction::make(),

                EditAction::make()
                    ->visible(fn () => AccessHelper::canEditSuratMasuk()),

                DeleteAction::make()
                    ->visible(fn () => AccessHelper::canDeleteSuratMasuk())
                    ->label('')
                    ->tooltip('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => AccessHelper::canDeleteSuratMasuk()),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuratMasuks::route('/'),
            'create' => Pages\CreateSuratMasuk::route('/create'),
            'edit' => Pages\EditSuratMasuk::route('/{record}/edit'),
        ];
    }

    // ==========================
    //  OTORISASI PAKAI HELPER
    // ==========================
    public static function canViewAny(): bool
    {
        return AccessHelper::canViewSuratMasuk();
    }

    public static function canCreate(): bool
    {
        return AccessHelper::canCreateSuratMasuk();
    }

    public static function canEdit($record): bool
    {
        return AccessHelper::canEditSuratMasuk();
    }

    public static function canDelete($record): bool
    {
        return AccessHelper::canDeleteSuratMasuk();
    }

    public static function canForceDelete($record): bool { return false; }
    public static function canRestore($record): bool { return false; }
}
