<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitResource\Pages;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Unit Organisasi';
    protected static ?string $pluralModelLabel = 'Unit Organisasi';
    protected static ?string $modelLabel = 'Unit Organisasi';
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'unit';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas Unit')
                ->description('Informasi utama unit organisasi.')
                ->icon('heroicon-o-identification')
                ->schema([
                    Forms\Components\TextInput::make('code')
                        ->label('Kode Unit')
                        ->required()
                        ->maxLength(20)
                        ->unique(Unit::class, 'code', ignoreRecord: true)
                        ->placeholder('Contoh: DIV-IT')
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('name')
                        ->label('Nama Unit')
                        ->required()
                        ->maxLength(150)
                        ->columnSpan(1),

                    Forms\Components\Select::make('parent_id')
                        ->label('Unit Induk')
                        ->relationship('parent', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->placeholder('Pilih unit induk (opsional)'),

                    Forms\Components\Select::make('type')
                        ->label('Tipe Unit')
                        ->options([
                            'lembaga'   => 'Lembaga',
                            'direktorat'=> 'Direktorat',
                            'divisi'    => 'Divisi',
                            'bagian'    => 'Bagian',
                            'seksi'     => 'Seksi',
                            'unit'      => 'Unit',
                        ])
                        ->default('unit')
                        ->required(),
                ])->columns(2),

            Forms\Components\Section::make('Penanggung Jawab')
                ->description('Data kepala atau penanggung jawab unit.')
                ->icon('heroicon-o-user-circle')
                ->schema([
                    Forms\Components\TextInput::make('head_name')
                        ->label('Nama Kepala Unit')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('head_position')
                        ->label('Jabatan')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('phone')
                        ->label('Telepon')
                        ->tel()
                        ->maxLength(25),

                    Forms\Components\TextInput::make('email')
                        ->label('Email Unit')
                        ->email()
                        ->maxLength(100),
                ])->columns(2),

            Forms\Components\Section::make('Konfigurasi')
                ->schema([
                    Forms\Components\Textarea::make('address')
                        ->label('Alamat')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Unit Aktif')
                        ->default(true),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Urutan Tampil')
                        ->numeric()
                        ->default(0),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Unit')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Unit Induk')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'lembaga'    => 'danger',
                        'direktorat' => 'warning',
                        'divisi'     => 'info',
                        'bagian'     => 'success',
                        'seksi'      => 'gray',
                        default      => 'primary',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('head_name')
                    ->label('Kepala Unit')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe Unit')
                    ->options([
                        'lembaga'    => 'Lembaga',
                        'direktorat' => 'Direktorat',
                        'divisi'     => 'Divisi',
                        'bagian'     => 'Bagian',
                        'seksi'      => 'Seksi',
                        'unit'       => 'Unit',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->trueLabel('Hanya Aktif')
                    ->falseLabel('Hanya Non-Aktif'),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScope(SoftDeletingScope::class);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'edit'   => Pages\EditUnit::route('/{record}/edit'),
        ];
    }
}
