<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SumberDokumenResource\Pages;
use App\Models\SumberDokumen;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;

class SumberDokumenResource extends Resource
{
    protected static ?string $model = SumberDokumen::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Data Dasar';
    protected static ?string $navigationLabel = 'Asal Dokumen';
    protected static ?int $navigationSort = 7;
    protected static ?string $slug = 'asal-dokumen';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('kode_sumber')->required()->unique(ignoreRecord: true),
            TextInput::make('nama')->required(),
            Select::make('tipe')->options([
                'internal' => 'Internal',
                'eksternal' => 'Eksternal',
            ])->required(),
            Textarea::make('keterangan')->rows(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_sumber')
                    ->label('Kode')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nama')
                    ->label('Asal Dokumen')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tipe')
                    ->label('Tipe')
                    ->searchable()
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make('view')
                    ->label('')
                    ->tooltip('Lihat detail')
                    ->icon('heroicon-o-eye'),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->label('')
                    ->tooltip('Hapus')
                // ->visible(fn ($record) => !$record->trashed()), // hanya tampil jika belum dihapus,,
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
            'index' => Pages\ListSumberDokumens::route('/'),
            'create' => Pages\CreateSumberDokumen::route('/create'),
            'edit' => Pages\EditSumberDokumen::route('/{record}/edit'),
        ];
    }
}
