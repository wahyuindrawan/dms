<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratMasukResource\Pages;
use App\Models\SuratMasuk;
use Filament\Tables\Actions\Action;
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
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationGroup = 'Manajemen Surat';
    protected static ?string $navigationLabel = 'Surat Masuk';
    protected static ?string $slug = 'surat-masuk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('judul')->required()->label('Judul Surat'),
                TextInput::make('nomor_surat')->label('Nomor Surat')->nullable(),
                TextInput::make('perihal')->label('Perihal')->nullable(),
                DatePicker::make('tanggal_surat')->required()->label('Tanggal Surat'),
                DatePicker::make('tanggal_masuk')->required()->label('Tanggal Masuk'),
                Select::make('kategori_id')
                    ->relationship('kategori', 'nama')
                    ->label('Kategori')
                    ->required(),
                Select::make('sumber_id')
                    ->relationship('sumber', 'nama')
                    ->label('Sumber')
                    ->required(),
                Select::make('ditujukan_id')
                    ->relationship('ditujukan', 'nama') // pastikan model Worker ada kolom 'nama'
                    ->label('Ditujukan ke')
                    ->searchable()
                    ->nullable(),
                Textarea::make('deskripsi')->label('Keterangan'),
                FileUpload::make('file_path')
                    ->label('Upload File')
                    ->directory('surat-masuk')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'image/*'])
                    ->maxSize(2048)
                    ->preserveFilenames() // ⬅️ ini yang menyimpan nama asli
                    ->storeFileNamesIn('file_original'), // menyimpan nama asli file
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->columns([
            //     TextColumn::make('judul')->searchable(),
            //     TextColumn::make('nomor_surat')->label('No. Surat'),
            //     TextColumn::make('perihal'),
            //     TextColumn::make('ditujukan.nama')->label('Ditujukan'),
            //     TextColumn::make('tanggal_surat')->date(),
            //     TextColumn::make('tanggal_masuk')->date(),
            //     TextColumn::make('kategori.nama')->label('Kategori'),
            //     TextColumn::make('sumber.nama')->label('Sumber'),
            // ])->defaultSort('tanggal_masuk', 'desc')
            // ->filters([
            //     //
            // ])
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul & Nomor Surat')
                    ->formatStateUsing(function ($state, $record) {
                        return "<div class='leading-tight'>
                        <div class='font-semibold text-gray-800'>{$record->judul}</div>
                        <div class='text-sm text-gray-500'>{$record->nomor_surat}</div>
                    </div>";
                    })
                    ->html()
                    ->sortable()
                    ->searchable(),

                // TextColumn::make('tanggal_surat')
                //     ->label('Tanggal Surat')
                //     ->date()
                //     ->sortable(),

                TextColumn::make('sumber.nama')
                    ->label('Asal Dokumen')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('kategori.nama')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->date()
                    ->sortable(),

            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Action::make('Lihat Detail')
                    ->label('')
                    ->tooltip('Lihat detail')
                    ->icon('heroicon-o-eye')
                    ->modalContent(fn($record) => view('filament.modals.detail-surat-masuk', ['record' => $record]))
                    ->modalWidth('3xl'),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->tooltip('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListSuratMasuks::route('/'),
            'create' => Pages\CreateSuratMasuk::route('/create'),
            'edit' => Pages\EditSuratMasuk::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes()->withoutTrashed();
    }
}
