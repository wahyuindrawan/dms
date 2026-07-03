<?php

namespace App\Filament\Resources\Legacy;

use App\Filament\Resources\Legacy\DokumenLainResource\Pages;
use App\Models\Legacy\DokumenLain;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DokumenLainResource extends Resource
{
    protected static ?string $model = DokumenLain::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $pluralModelLabel = 'Dokumen Lain';
    protected static ?string $navigationGroup = 'Manajemen Surat';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Dokumen Lain';
    protected static ?int $navigationSort = 3;
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
                TextColumn::make('judul')
                    ->label('Nama Dokumen')
                    ->searchable()
                    ->html()
                    ->formatStateUsing(fn($record) => "
                            <div class='leading-tight'>
                                <div class='font-semibold text-gray-900 py-1'>{$record->judul}</div>
                                <div class='text-xs text-gray-600 flex items-center gap-2'>
                                    <img src='" . asset('icons/documen-file.png') . "' class='w-4 h-4 opacity-60 inline-block' />
                                    " . \Carbon\Carbon::parse($record->tanggal_dokumen)->format('d M Y') . "
                                </div>
                            </div>
                        "),
                TextColumn::make('kategori.nama')->label('Kategori'),
                TextColumn::make('sumber.nama')->label('Sumber'),
                TextColumn::make('file_original')->label('Nama File')->toggleable(),
            ])
            ->defaultSort('tanggal_dokumen', 'desc')
            ->filters([])
            ->actions([
                Action::make('Lihat Detail')
                    ->label('')
                    ->tooltip('Lihat detail')
                    ->icon('heroicon-o-eye')
                    ->modalContent(fn($record) => view('filament.modals.detail-dokumen-lain', ['record' => $record]))
                    ->modalWidth('3xl'),
                DeleteAction::make()
                    ->label('')
                    ->tooltip('Hapus'),
            ])

            ->actionsPosition(ActionsPosition::BeforeColumns)    
            ->bulkActions([
                 BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListDokumenLains::route('/'),
            'create' => Pages\CreateDokumenLain::route('/create'),
            'edit' => Pages\EditDokumenLain::route('/{record}/edit'),
        ];
    }
}
