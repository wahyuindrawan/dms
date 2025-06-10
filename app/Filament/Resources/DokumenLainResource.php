<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokumenLainResource\Pages;
use App\Filament\Resources\DokumenLainResource\RelationManagers;
use App\Models\DokumenLain;
use Filament\Forms;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DokumenLainResource extends Resource
{
    protected static ?string $model = DokumenLain::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Manajemen Surat';
    protected static ?string $navigationLabel = 'Dokumen Lain';
    protected static ?string $slug = 'dokumen-lain';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('judul')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('tanggal_dokumen')
                    ->required(),
                Select::make('kategori_id')
                    ->relationship('kategori', 'nama')
                    ->label('Kategori')
                    ->nullable(),
                Select::make('sumber_id')
                    ->relationship('sumber', 'nama')
                    ->label('Sumber')
                    ->nullable(),
                FileUpload::make('file_path')
                    ->label('Upload Dokumen')
                    ->preserveFilenames()
                    ->storeFileNamesIn('file_original')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'image/*'])
                    ->directory('dokumen-lain')
                    ->maxSize(2048)
                    ->nullable(),
                Textarea::make('deskripsi')
                    ->label('Keterangan')
                    ->rows(3)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')->searchable(),
                TextColumn::make('kategori.nama')->label('Kategori'),
                TextColumn::make('sumber.nama')->label('Sumber'),
                TextColumn::make('tanggal_dokumen')->date()->sortable(),
                TextColumn::make('file_original')->label('Nama File')->toggleable(),
            ])
            ->defaultSort('tanggal_dokumen', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('Lihat Detail')
                    ->label('')
                    ->tooltip('Lihat detail')
                    ->icon('heroicon-o-eye')
                    ->modalContent(fn($record) => view('filament.modals.detail-dokumen-lain', ['record' => $record]))
                    ->modalWidth('3xl'),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->tooltip('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDokumenLains::route('/'),
            'create' => Pages\CreateDokumenLain::route('/create'),
            'edit' => Pages\EditDokumenLain::route('/{record}/edit'),
        ];
    }
}
