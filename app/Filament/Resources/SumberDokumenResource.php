<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SumberDokumenResource\Pages;
use App\Filament\Resources\SumberDokumenResource\RelationManagers;
use App\Models\SumberDokumen;
use Filament\Forms;
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

class SumberDokumenResource extends Resource
{
    protected static ?string $model = SumberDokumen::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Data Dasar';
    protected static ?string $navigationLabel = 'Asal Dokumen';
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
                TextColumn::make('kode_sumber'),
                TextColumn::make('nama'),
                TextColumn::make('tipe')->badge(),
                // TextColumn::make('created_at')->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSumberDokumens::route('/'),
            'create' => Pages\CreateSumberDokumen::route('/create'),
            'edit' => Pages\EditSumberDokumen::route('/{record}/edit'),
        ];
    }
}
