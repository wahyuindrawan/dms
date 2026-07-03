<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Peran & Izin';
    protected static ?string $pluralModelLabel = 'Peran & Izin';
    protected static ?string $modelLabel = 'Peran & Izin';
    protected static ?int $navigationSort = 5;
    protected static ?string $slug = 'peran-izin';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas Peran')
                ->description('Slug digunakan sebagai kunci di sistem, nama tampilan digunakan di UI.')
                ->icon('heroicon-o-identification')
                ->schema([
                    Forms\Components\TextInput::make('nama')
                        ->label('Slug Peran')
                        ->required()
                        ->unique(Role::class, 'nama', ignoreRecord: true)
                        ->alphaDash()
                        ->maxLength(50)
                        ->placeholder('Contoh: admin, tu, karyawan')
                        ->helperText('Hanya huruf kecil, angka, dan tanda hubung. Tidak dapat diubah setelah data dibuat.')
                        ->disabledOn('edit'),

                    Forms\Components\TextInput::make('display_name')
                        ->label('Nama Tampilan')
                        ->required()
                        ->maxLength(100)
                        ->placeholder('Contoh: Administrator, Tata Usaha'),

                    Forms\Components\Textarea::make('deskripsi')
                        ->label('Deskripsi Peran')
                        ->rows(2)
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Select::make('color')
                        ->label('Warna Badge')
                        ->options([
                            'blue'   => '🔵 Biru (Admin)',
                            'green'  => '🟢 Hijau (Pimpinan)',
                            'yellow' => '🟡 Kuning (TU)',
                            'red'    => '🔴 Merah',
                            'gray'   => '⚪ Abu-abu (Default)',
                        ])
                        ->default('gray')
                        ->searchable(),
                ])->columns(2),

            Forms\Components\Section::make('Izin Akses (Permissions)')
                ->description('Centang izin yang dimiliki oleh peran ini. Perubahan berlaku segera setelah disimpan.')
                ->icon('heroicon-o-key')
                ->schema([
                    Forms\Components\CheckboxList::make('permissions')
                        ->relationship('permissions', 'display_name')
                        ->columns(3)
                        ->gridDirection('row')
                        ->bulkToggleable()
                        ->label(false),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('display_name')
                    ->label('Nama Tampilan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('permissions_count')
                    ->label('Jumlah Izin')
                    ->counts('permissions')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('color')
                    ->label('Warna')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'blue'   => '🔵 Biru',
                        'green'  => '🟢 Hijau',
                        'yellow' => '🟡 Kuning',
                        'red'    => '🔴 Merah',
                        default  => '⚪ Abu-abu',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalDescription('Menghapus peran ini dapat mempengaruhi pengguna yang masih menggunakannya.'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index'  => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit'   => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
