<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratKeluarResource\Pages;
use App\Models\SuratKeluar;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SuratKeluarResource extends Resource
{
    protected static ?string $model = SuratKeluar::class;
    protected static ?string $pluralModelLabel = 'Surat Keluar';
    protected static ?string $navigationGroup = 'Manajemen Surat';
    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    protected static ?string $navigationLabel = 'Surat Keluar';
    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'surat-keluar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('judul')
                    ->nullable()
                    ->label('Judul Surat'),
                TextInput::make('nomor_surat')
                    ->nullable()
                    ->label('Nomor Surat'),
                TextInput::make('perihal')
                    ->label('Perihal')
                    ->nullable(),
                DatePicker::make('tanggal_surat')
                    ->label('Tanggal Surat')
                    ->required(),
                DatePicker::make('tanggal_keluar')
                    ->label('Tanggal Keluar')
                    ->default(now())
                    ->required(),
                Select::make('kategori_id')
                    ->relationship('kategori', 'nama')
                    ->label('Kategori')
                    ->required(),
                TextInput::make('ditujukan')
                    ->label('Ditujukan ke')
                    ->nullable(),
                TextInput::make('deskripsi')
                    ->label('Keterangan'),
                FileUpload::make('file_path')
                    ->label('Upload Dokumen')
                    ->preserveFilenames()
                    ->storeFileNamesIn('file_original')
                    ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword'])
                    ->directory('surat-keluar')
                    ->maxSize(2048),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->label('Nama Dokumen')
                    ->html()
                    ->formatStateUsing(fn($state, $record) => "
                            <div class='leading-tight'>
                                <div class='font-semibold text-gray-900 py-1'>{$record->judul}</div>
                                <div class='text-xs text-gray-750'>No: {$record->nomor_surat}</div>
                                <div class='text-xs text-gray-600 flex items-center gap-2'>
                                    <img src='" . asset('icons/surat_out.png') . "' class='w-4 h-4 opacity-60 inline-block' /> 
                                    " . \Carbon\Carbon::parse($record->tanggal_keluar)->format('d M Y') . "
                                </div>
                            </div>
                        ")
                    ->sortable()
                    ->searchable(),
                TextColumn::make('kategori.nama')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ditujukan')->label('Ditujukan'),
            ])->defaultSort('tanggal_keluar', 'desc')
            ->filters([])
            ->actions([
                Action::make('Lihat Detail')
                    ->label('')
                    ->tooltip('Lihat Detail')
                    ->icon('heroicon-o-eye')
                    ->modalContent(fn($record) => view('filament.modals.detail-surat-keluar', ['record' => $record]))
                    ->modalWidth('3xl'),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->label('')
                    ->tooltip('Hapus')
                    ->visible(fn($record) => !$record->trashed()), // hanya tampil jika belum dihapus,
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuratKeluars::route('/'),
            'create' => Pages\CreateSuratKeluar::route('/create'),
            'edit' => Pages\EditSuratKeluar::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutTrashed();
    }
}
