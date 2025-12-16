<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkerResource\Pages;
use App\Filament\Resources\WorkerResource\RelationManagers;
use App\Models\Worker;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkerResource extends Resource
{
    protected static ?string $model = Worker::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Data Dasar';
    protected static ?string $navigationLabel = "Karyawan";
    protected static ?int $navigationSort = 8;
    protected static ?string $slug = "worker";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_worker')
                    ->label('Kode Petugas')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('nama')
                    ->label('Nama Petugas')
                    ->required(),
                TextInput::make('jabatan')
                    ->label('Jabatan'),
                TextInput::make('email')
                    ->label('Email')
                    ->email(),
                TextInput::make('telepon')
                    ->label('Telepon')
                    ->tel(),
                Textarea::make('keterangan')->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_worker')
                    ->label('Kode Petugas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama')
                    ->label('Nama Petugas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jabatan')
                    ->searchable()
                    ->label('Jabatan'),
                TextColumn::make('email')
                    ->label('Email')
                    ->copyable(),
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
            'index' => Pages\ListWorkers::route('/'),
            'create' => Pages\CreateWorker::route('/create'),
            'edit' => Pages\EditWorker::route('/{record}/edit'),
        ];
    }
}
