<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriDokumenResource\Pages;
use App\Models\KategoriDokumen;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Enums\ActionsPosition;

class KategoriDokumenResource extends Resource
{
    protected static ?string $model = KategoriDokumen::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Data Dasar';
    protected static ?string $navigationLabel = 'Kategori Dokumen';
    protected static ?int $navigationSort = 6;
    protected static ?string $slug = 'kategori-dokumen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Textarea::make('keterangan')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->sortable()->searchable(),
                TextColumn::make('keterangan')->limit(50),
                // TextColumn::make('created_at')->dateTime('d M Y')->label('Dibuat'),
            ])
            ->defaultSort('nama')

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
            ])
            ->actionsPosition(ActionsPosition::BeforeColumns)
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
            'index' => Pages\ListKategoriDokumens::route('/'),
            'create' => Pages\CreateKategoriDokumen::route('/create'),
            'edit' => Pages\EditKategoriDokumen::route('/{record}/edit'),
        ];
    }
}
