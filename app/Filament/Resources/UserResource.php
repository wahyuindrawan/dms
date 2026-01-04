<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $pluralModelLabel = "Pengguna";
    protected static ?string $modelLabel = "Pengguna";
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = "Pengguna";
    protected static ?int $navigationSort = 9;
    protected static ?string $slug = "pengguna";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Username')
                    ->required(),

                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('password')
                    ->password()
                    ->label('Password (isi jika ingin mengganti)')
                    ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn($state) => filled($state))
                    ->maxLength(255),

                Select::make('worker_id')
                    ->relationship('worker', 'nama')
                    ->label('Pegawai Terkait')
                    ->searchable()
                    ->nullable(),

                Select::make('role')
                    ->label('Peran Pengguna')
                    ->options(function () {
                        return Role::all()->pluck('display_name', 'nama')->toArray();
                    })
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('id')->sortable(),
                TextColumn::make('name')->searchable()->label('Username'),
                TextColumn::make('email')->copyable(),
                TextColumn::make('worker.nama')->label('Pegawai'),
                TextColumn::make('role')
                ->label('Role')
                ->badge()
                ->formatStateUsing(fn ($state, $record) => $record->roleData?->display_name ?? '-')
                ->color(fn ($state, $record) => $record->roleData?->color ?? 'gray')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $role = $data['role'];

    //     unset($data['role']);

    //     return $data;
    // }

    // protected function afterCreate(): void
    // {
    //     $this->record->assignRole($this->data['role'] ?? null);
    // }
}
